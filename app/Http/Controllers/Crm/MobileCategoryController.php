<?php

namespace App\Http\Controllers\Crm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect,Response;
use Illuminate\Support\Facades\DB;
use DataTables;

class MobileCategoryController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(\Gate::allows('crm_mobile_product_access'), 403);
        $status = (!empty($_GET["status"])) ? ($_GET["status"]) : ('all');
        $nik = auth()->user()->nik;
        $brand_pilih = (!empty($_GET["brand_filter"])) ? ($_GET["brand_filter"]) : ('all');
        $where = ' where 1=1 ';

        if($request->ajax())
        {
            if($status == 'all'){
                $where .= '';
            }else{
                $where .= ' and is_aktif=\''.$status.'\' ';
            }

            if($brand_pilih == 'all'){
                $where .= '';
            }else{
                $where .= ' and brand=\''.$brand_pilih.'\' ';
            }

            $data = DB::select('
            select id, brand, category, image, 
            case when is_aktif=0 then \'Nonaktif\' else \'Aktif\' end as status_aktif, is_aktif
            from pos_server.dbo.dt_member_mobile_category '.$where.'
            order by brand asc');
            
            return DataTables::of($data)
                    ->addColumn('action', function($data){
                        $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-info btn-sm">Edit</button>';
                        $button .= '&nbsp<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-sm">Delete</button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        $brands = DB::select('select nama_brand FROM pos_server.dbo.dt_brand where aktif=\'1\' ORDER BY nama_brand');
        return view('crm.mobile-category.index', compact('status','nik','brands','brand_pilih'));
    }

    public function store(Request $request)
    {
        try{
            $temp = DB::select('sp_add_mobile_category \''.auth()->user()->nik.'\',\''.$request->brand.'\',\''.$request->image.'\',\''.$request->category.'\',\''.$request->status_form.'\'');
            $data = $temp[0];
            if ($data->hasil == "gagal"){
                return response()->json(['errors' => 'gagal create category']);
            }else{
                DB::rollback();
                return response()->json(['success' => $data->hasil]);
            }
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try{
            $temp = DB::select('sp_update_mobile_category \''.$request->id_category.'\',\''.auth()->user()->nik.'\',\''.$request->brand.'\',\''.$request->image.'\',\''.$request->category.'\',\''.$request->status_form.'\'');
            $data = $temp[0];
            if ($data->hasil == "gagal"){
                return response()->json(['errors' => 'gagal update category']);
            }else{
                DB::rollback();
                return response()->json(['success' => $data->hasil]);
            }
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function edit($id){
        try{
            $temp = DB::select('select id, brand, image, category, is_aktif
            from pos_server.dbo.dt_member_mobile_category where id=\''.$id.'\'');
            $data = $temp[0];
            return response()->json(['success' => $data]);
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        DB::begintransaction();
        try {
            DB::table('pos_server.dbo.dt_member_mobile_category')
            ->where('id', $request->id)
            ->delete();
            DB::commit();
            return response()->json(['success' => "OK"]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json(['errors' => $e->getMessage()]);
        }
    }
}