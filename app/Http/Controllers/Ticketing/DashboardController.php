<?php

namespace App\Http\Controllers\Ticketing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        abort_unless(\Gate::allows('ticketing_dashboard_access'), 403);
        $month = array('Jan', 'Feb', 'Mar', 'Apr', 'May');
        $data  = array(1, 2, 3, 4, 5);
        //return view('chartjs',['Months' => $month, 'Data' => $data]);
        return view('ticketing.dashboard.index',['Months' => $month, 'Data' => $data]);
    }
}
