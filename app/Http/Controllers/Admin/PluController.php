<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Plu;
use DataTables;

class PluController extends Controller
{

    public function index(){
        return view('admin.plus.index');
    }

    public function getData(){
        return DataTables::of(Plu::query())->make(true);
        /*$plus = DB::table('POS_SERVER.dbo.DT_PLU')->select('*');
        return datatables()->of($plus)
            ->make(true);*/

    }
}
