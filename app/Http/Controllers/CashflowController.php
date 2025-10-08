<?php

namespace App\Http\Controllers;

class CashflowController extends Controller
{
    public function index()
    {
        return view('cashflow.index');
    }

    public function import()
    {
        return view('cashflow.import');
    }
}
