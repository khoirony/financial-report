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
}
