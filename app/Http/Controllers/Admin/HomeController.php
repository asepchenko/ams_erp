<?php

namespace App\Http\Controllers\Admin;

class HomeController
{
    public function index()
    {
        $month = array('Jan', 'Feb', 'Mar', 'Apr', 'May');
        $data  = array(1, 2, 3, 4, 5);
        //return view('chartjs',['Months' => $month, 'Data' => $data]);
        return view('home',['Months' => $month, 'Data' => $data]);
    }
}
