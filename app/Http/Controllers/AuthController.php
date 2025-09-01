<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Psy\Util\Str;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }
}
