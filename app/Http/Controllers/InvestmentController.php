<?php

namespace App\Http\Controllers;

class InvestmentController extends Controller
{
    public function index()
    {
        return view('investment.index');
    }

    public function manageInvestment()
    {
        return view('admin.manage-investment');
    }

    public function fireCalculator()
    {
        return view('investment.fire-calculator');
    }

    public function analysis()
    {
        return view('investment.analysis');
    }

    public function brokerSummary()
    {
        return view('investment.broker-summary');
    }
}
