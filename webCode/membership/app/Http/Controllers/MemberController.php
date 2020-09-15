<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UpdateKey;
use App\Http\Requests\StoreMember;
use App\Http\Requests\UpdateMember;
use App\Events\MemberRevoked;
use App\Events\MemberReinstated;
use App\Models\Member;
use App\Models\MemberTier;
use App\Models\PaymentProvider;
use App\Models\Resource;
use App\Models\Customer;

use Session;
use View;
use DateTime;
use DateInterval;

class MemberController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status_id = 1; //Active
        
        $members = Member::where('member_status_id', $status_id)->orderBy('name')->get();
        return view('members.index', compact('members'));
    }

    /**
     * Display a listing of the inactive members.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function inactive()
    {
        $status_id = 2; //Inactive
        
        $members = Member::where('member_status_id', $status_id)->orderBy('name')->get();
        return view('members.inactive', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     *S
     * @return \Illuminate\Http\Response
     */
    public function create(Member $member)
    {
        $tiers = MemberTier::pluck('description', 'id');
        $providers = PaymentProvider::pluck('description', 'id');

        $expireDate = new DateTime;
        $expireDate->add(new DateInterval("P62D"));

        $member->expire_date = date('m/d/Y', $expireDate->getTimestamp());

        $member->memberTier = MemberTier::where('description', 'Standard')->first();
        $member->paymentProvider = PaymentProvider::where('description', 'Paypal')->first();
        
        return view('members.create', compact('member', 'tiers', 'providers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMember $request)
    {

        $input = $request->all();
         
        $customer = new Customer;
        $customer->name = $input["name"];
        $customer->email = $input["email"];
        $customer->payment_provider_id = $input["payment_provider_id"];
        $customer->save();

        $member = new Member;
        $member->fill($input);
        $expireDate = new DateTime;
        $expireDate->add(new DateInterval("P62D"));

        $member->expire_date = $expireDate;

        $member->member_status_id = 1;
        $member->customer_id = $customer->id;
        
        $member->save();

        $member->resources()->attach([1,2]);
        
        // redirect
        Session::flash('message', 'Successfully saved member!');
        
        return redirect('members');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $member = Member::find($id);
        $tiers = MemberTier::pluck('description', 'id');
        $providers = PaymentProvider::pluck('description', 'id');
        $resources = Resource::pluck('description', 'id');
        $member->expire_date = date('m/d/Y', strtotime($member->expire_date));
        
        return view('members.edit', compact('member', 'tiers', 'providers', 'resources'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMember $request, $id)
    {
     
        $input = $request->all();
        
        $member = Member::find($id);
        
        $member->fill($input);
        
        $member->save();

        $member->resources()->sync($request->get('resources'));
        // redirect
        Session::flash('message', 'Successfully saved member!');
        return redirect('members');
    }

    public function changeKey($id)
    {
        $member = Member::find($id);
        
        return view('members.changeKey', compact('member'));
    }

    public function updateKey(UpdateKey $request, $id)
    {
     
        $input = $request->all();
        
        $member = Member::find($id);
        $member->rfid = $request->input('rfid');
        
        $member->save();

        // redirect
        Session::flash('message', 'Successfully updated key!');
        return redirect('members');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $member = Member::find($id);
        $member->member_status_id = 2;
        $member->save();

        event(new MemberRevoked($member));

        Session::flash('message', $member->name.' has been revoked.');
        return redirect('members');
    }

    /**
     * Restore the member's access
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {

        $member = Member::find($id);
        $member->member_status_id = 1;
        $member->save();

        event(new MemberReinstated($member));

        Session::flash('message', $member->name.' has been restored.');
        return redirect('members/inactive');
    }

}
