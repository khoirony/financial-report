<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Psy\Util\Str;

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
