<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentProviders\QuickbooksService;
use App\Services\PaymentProviders\PayPalService;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Log;

use App\Models\Member;
use App\Models\MemberTier;
use App\Models\PaymentProvider;
use App\Models\Resource;
use App\Events\MemberAdded;
use Session;
use DateTime;
use DateInterval;

use App\Services\MailChimp;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function info()
    {
        return view('info');
    }

    public function addMember(Member $member)
    {
        $tiers = MemberTier::pluck('description', 'id');
        $providers = PaymentProvider::pluck('description', 'id');

        $member->memberTier = MemberTier::where('description', 'Standard')->first();
        $member->paymentProvider = PaymentProvider::where('description', 'Paypal')->first();
        
        return view('home.registerMember', compact('member', 'tiers', 'providers'));
    }

    public function checkForPayment(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:members|max:255'
        ]);
        
        if ($request->payment_provider_id == 1)
        {
            $qbo = new QuickbooksService;        
            $response = $qbo->findMember($request->email);
        }
        else if ($request->payment_provider_id == 2)
        {
            $paypal = new PayPalService;        
            $response = $paypal->findMember($request->email);
        }

        Log::info($response->status);
        if ($response->status != "Success")
        {
            Session::flash('error', 'There are no recorded payments in the last month from '.$request->email.'.');

            return Redirect::back()
                ->withInput();       
        }

        $input = $request->all();
        
        $member = new Member;
        $member->fill($input);
        
        $member->member_status_id = 1;
        $member->name = $response->name;

        if ($response->amount == 50.00){
            $member->member_tier_id = 3;
        }
        else if ($response->amount == 30.00)
        {
            $member->member_tier_id = 2;
        }
        else if ($respone->amount == 20.00)
        {
            $member->member_tier_id = 1;
        }

        session(['memberToAdd' => $member]);

        return redirect()->route('home.confirm');
        
    }

    public function confirmMember(Request $request)
    {
        
        if ($request->session()->exists('memberToAdd')) 
        {
            $member = $request->session()->get('memberToAdd');
            $tier = MemberTier::find($member->member_tier_id);
            $provider = PaymentProvider::find($member->payment_provider_id);

            return view('home.confirm', compact('member', 'tier', 'provider'));        
        }
        else
        {
            Session::flash('error', 'Unable to register member.  Please try again.');   
            return redirect('/');
        }
    }

    public function storeMember(Request $request)
    {
  
        if ($request->session()->exists('memberToAdd')) 
        {
            $member = $request->session()->pull('memberToAdd');

            $expireDate = new DateTime;
            $expireDate->add(new DateInterval("P62D"));

            $member->expire_date = $expireDate;

            $member->rfid = $request->input('rfid');

            $member->save();

            $member->resources()->attach([1,2]);

            event(new MemberAdded($member));

            // redirect
            Session::flash('message', 'Successfully registered member and activated key.');
        }
        else
        {
            Session::flash('error', 'Unable to register member.  Please try again.');   
        }
        return redirect('/');
    }

}
