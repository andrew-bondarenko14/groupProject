<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpaController extends Controller
{
    public function auth()
    {
        return view('spa_auth');
    }
    public function main()
    {
        return view('spa_main');
    }
}
