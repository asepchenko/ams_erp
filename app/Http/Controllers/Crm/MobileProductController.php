<?php

namespace App\Http\Controllers\Crm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect,Response;
use Illuminate\Support\Facades\DB;
use DataTables;

class MobileProductController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(\Gate::allows('crm_mobile_product_access'), 403);
        $status_pilih = (!empty($_GET["status_filter"])) ? ($_GET["status_filter"]) : ('all');
        $nik = auth()->user()->nik;
        $brand_pilih = (!empty($_GET["brand_filter"])) ? ($_GET["brand_filter"]) : ('all');
        $category_pilih = (!empty($_GET["category_filter"])) ? ($_GET["category_filter"]) : ('all');
        $where = ' where 1=1 ';

        if($request->ajax())
        {
            if($status_pilih == 'all'){
                $where .= '';
            }else{
                $where .= ' and is_aktif='.$status_pilih.' ';
            }

            if($brand_pilih == 'all'){
                $where .= '';
            }else{
                $where .= ' and brand=\''.$brand_pilih.'\' ';
            }

            if($category_pilih == 'all'){
                $where .= '';
            }else{
                $where .= ' and category=\''.$category_pilih.'\' ';
            }

            $data = DB::select('
            select id, title, image, keterangan, brand, category,
            REPLACE(FORMAT(old_price, \'N\', \'en-us\'), \'.00\', \'\') as old_price, 
            REPLACE(FORMAT(new_price, \'N\', \'en-us\'), \'.00\', \'\') as new_price, 
            case when is_aktif=0 then \'Nonaktif\' else \'Aktif\' end as status_aktif, is_aktif
            from pos_server.dbo.dt_member_mobile_product '.$where.'
            order by title asc');
            
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
        return view('crm.mobile-product.index', compact('status_pilih','nik','brand_pilih','brands','category_pilih'));
    }

    public function getDataCategory($brand){

        $data = DB::select('select category from pos_server.dbo.dt_member_mobile_category
        where brand =\''.$brand.'\' and is_aktif=\'1\'
        ORDER BY category');

        $options = array();
        foreach($data as $row)
        {
            $options += array($row->category => $row->category);
        }
        return Response::json($options);
    }

    public function store(Request $request)
    {
        try{
            $temp = DB::select('sp_add_mobile_product \''.auth()->user()->nik.'\',\''.$request->nama.'\',\''.$request->brand.'\',\''.$request->category.'\',\''.$request->gambar.'\',\''.$request->keterangan.'\',\''.$request->harga_lama.'\',\''.$request->harga_baru.'\',\''.$request->status.'\'');
            $data = $temp[0];
            if ($data->hasil == "gagal"){
                return response()->json(['errors' => 'gagal create promo']);
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
            $temp = DB::select('sp_update_mobile_product \''.$request->id_product.'\',\''.auth()->user()->nik.'\',\''.$request->nama.'\',\''.$request->brand.'\',\''.$request->category.'\',\''.$request->gambar.'\',\''.$request->keterangan.'\',\''.$request->harga_lama.'\',\''.$request->harga_baru.'\',\''.$request->status.'\'');
            $data = $temp[0];
            if ($data->hasil == "gagal"){
                return response()->json(['errors' => 'gagal update promo']);
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
            $temp = DB::select('select id, title, image, keterangan, 
            REPLACE(FORMAT(old_price, \'N\', \'en-us\'), \'.00\', \'\') as old_price, 
            REPLACE(FORMAT(new_price, \'N\', \'en-us\'), \'.00\', \'\') as new_price, is_aktif
            from pos_server.dbo.dt_member_mobile_product where id=\''.$id.'\'');
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
            DB::table('pos_server.dbo.dt_member_mobile_product')
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