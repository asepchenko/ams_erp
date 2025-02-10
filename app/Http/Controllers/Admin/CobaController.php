<?php

namespace App\Http\Controllers\Admin;

class CobaController
{
    public function index()
    {
        $active_tab = "tab1";
        return view('coba', compact('active_tab'));
        //return view('coba');
    }

}
