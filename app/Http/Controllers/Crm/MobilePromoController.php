<?php

namespace App\Http\Controllers\Crm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect,Response;
use Illuminate\Support\Facades\DB;
use DataTables;

class MobilePromoController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(\Gate::allows('crm_mobile_promo_access'), 403);
        $status = (!empty($_GET["status"])) ? ($_GET["status"]) : ('');
        $nik = auth()->user()->nik;

        if($status != ""){
            $where = " where is_aktif=".$status." " ;
        }else{
            $where = "";
        }

        if($request->ajax())
        {
            $data = DB::select('
            select id, nama_promo, image, keterangan, syarat_ketentuan, is_aktif
            from pos_server.dbo.dt_member_mobile_promo '.$where.'
            order by nama_promo asc');
            
            return DataTables::of($data)
                    ->addColumn('action', function($data){
                        $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm">Edit</button>';
                        $button .= '&nbsp<button type="button" name="push" id="'.$data->id.'" class="push btn btn-info btn-sm">Push Notification</button>';
                        $button .= '&nbsp<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-sm">Delete</button>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('crm.mobile-promo.index', compact('status','nik'));
    }

    public function store(Request $request)
    {
        try{
            $temp = DB::select('sp_add_mobile_promo \''.auth()->user()->nik.'\',\''.$request->nama.'\',\''.$request->gambar.'\',\''.$request->keterangan.'\',\''.$request->sk.'\',\''.$request->status.'\'');
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
            $temp = DB::select('sp_update_mobile_promo \''.$request->id_promo.'\',\''.auth()->user()->nik.'\',\''.$request->nama.'\',\''.$request->gambar.'\',\''.$request->keterangan.'\',\''.$request->sk.'\',\''.$request->status.'\'');
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
            $temp = DB::select('select id, nama_promo, image, keterangan, syarat_ketentuan, is_aktif
            from pos_server.dbo.dt_member_mobile_promo where id=\''.$id.'\'');
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
            DB::table('pos_server.dbo.dt_member_mobile_promo')
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