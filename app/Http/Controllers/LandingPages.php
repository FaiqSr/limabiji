<?php

namespace App\Http\Controllers;


class LandingPages extends Controller
{
    public function index()
    {
        return view('landingpages.index');
    }
}
