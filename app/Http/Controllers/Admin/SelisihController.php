<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;

class SelisihController extends Controller
{
    public function index(Request $request)
    {
        //abort_unless(\Gate::allows('stock_opname_access'), 403);
        $id_store = (!empty($_GET["id_store"])) ? ($_GET["id_store"]):('');

        if($id_store != ''){
            $query = 'POS_SERVER.dbo.yud_cek_so \''.$id_store.'\'';
            $dataso = DB::select($query);
        }else {
            $query = 'select * from tbl_cek_so where no_tr_so_header=\'123\'';
            $dataso = [];
        }
        return view('admin.selisih.index', compact('dataso'));
    }
}
