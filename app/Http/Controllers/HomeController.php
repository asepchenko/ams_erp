<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $data_brands = DB::select('select B.store_brand_id, sum(Grand_Total) as total
            from pos_server.dbo.tr_sales_header A
            join pos_server.dbo.dt_store B on A.id_store=B.store_id
            where datediff(day,tanggal_transaksi,getdate())=0
            and id_store not in(\'777\',\'888\',\'MS001\',\'01E\',\'02E\',\'03E\',\'04E\',\'7777\',\'MS002\')
            group by B.store_brand_id
            order by total desc');
        dd($data_brands);
        return view('welcome',compact('data_brands'));
    }
}
