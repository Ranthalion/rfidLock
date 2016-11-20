<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Services\PaymentImporter;

class ReportsController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }

    public function phpInfo()
    {
        return view('reports.phpInfo');
    }

    public function payments()
    {
        $importer = new PaymentImporter;
        
        $startDate = new \DateTime;
        $startDate->sub(new \DateInterval("P1M"));
        $startDate = $startDate->format(\DateTime::ATOM);

        $summaries = $importer->getCustomerPayments($startDate);

        return view('reports.payments', compact('summaries'));
    }
}
