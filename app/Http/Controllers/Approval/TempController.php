<?php

namespace App\Http\Controllers\Approval;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;

class TempController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(\Gate::allows('approval_document_access'), 403);
        $bulan = (!empty($_GET["bulan"])) ? ($_GET["bulan"]) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen; 
        $jab = auth()->user()->kode_jabatan; 
        $data_prioritas = DB::select('select id, priority_name from approval.dbo.document_priority where is_active=1 order by priority_name');

        if($request->ajax())
        {
            if($bulan == ''){
                $where = '';
            }else{
                $where = ' and month(A.created_at)=\''.$bulan.'\'';
            }

            $data = DB::select('select A.id, A.nik, A.nama, A.kode_departemen, A.keterangan, A.last_status, A.document_type,
            format(A.created_at,\'dd-MMM-yyyy HH:mm\') as created_at,
            no_ref = STUFF((
                SELECT \',\' + md.kode_category
                FROM approval.dbo.document_digital md
                WHERE A.id = md.document_id
                FOR XML PATH(\'\'), TYPE).value(\'.\', \'NVARCHAR(MAX)\'), 1, 1, \'\')
            from approval.dbo.document_master A
            join approval.dbo.document_digital D on A.id = D.document_id
            join users B on A.kode_departemen = B.kode_departemen COLLATE SQL_Latin1_General_CP1_CI_AS 
            where left(A.kode_departemen,3) like (select left(Z.kode_departemen,3) from users Z where Z.nik=\''.$nik.'\')
            and D.kode_category = \'KB\' and A.document_type <> \'kbr\'
            and A.id not in(select document_kb from approval.dbo.log_kasbon_realisasi) 
			and A.last_status = \'closed\'
            group by A.id, A.nik, A.nama, A.kode_departemen, A.keterangan, A.last_status, A.document_type, A.created_at 
            order by A.created_at desc');
            
            return DataTables::of($data)
                    ->addColumn('action', function($data){
                        $button = '<a href="tempkasbon/'.$data->id.'/transfer">Transfer</a>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        
        return view('approval.tempkasbon.index',compact('bulan','nik','name','data_prioritas'));
    }
    
    public function transfer($id){
        $nik = auth()->user()->nik;
        $cek = DB::select('select id from approval.dbo.document_master where id=\''.$id.'\' and nik=\''.$nik.'\'');
        if(count($cek) <= 0){
            abort(403);
        }
        try{
            $temp = DB::select('approval.dbo.sp_transfer_kasbonlama \''.$id.'\',\''.auth()->user()->nik.'\'');
            $data = $temp[0];
            if ($data->hasil == "gagal"){
                return redirect('approval/tempkasbon')->withErrors($data->hasil);                               
            }else{
                return redirect('approval/outstanding-kasbon/'.$data->hasil.'/edit')->withSuccess('berhasil Transfer Kasbon');
            }
        }
        catch (\Exception $e) {
            return redirect('approval/tempkasbon')->withErrors(['errors', $e->getMessage()]);
        }
    }
}
