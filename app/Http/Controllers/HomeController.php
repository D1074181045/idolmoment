<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function spa()
    {
        return view('home.app');
    }
}
