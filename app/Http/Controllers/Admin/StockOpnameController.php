<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;

class StockOpnameController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(\Gate::allows('stock_opname_access'), 403);
        if($request->ajax())
        {
            $id_store = (!empty($_GET["id_store"])) ? ($_GET["id_store"]):('');

            if($id_store != ''){
                $query = 'POS_SERVER.dbo.yud_cek_so \''.$id_store.'\'';
                $data = DB::select($query);
            }else {
                $query = 'select * from tbl_cek_so where no_tr_so_header=\'123\'';
                $data = [];
            }
            
            return DataTables::of($data)
                    ->addColumn('action', function($data){
                        $button = '<button type="button" name="detail" id="'.$data->plu.'" class="edit btn btn-primary btn-sm">Detail</button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.stockopname.index');
    }
}
