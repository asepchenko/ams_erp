<?php

namespace App\Http\Controllers\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use DataTables;
use Illuminate\Support\Str;

class RealisasiDocumentController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(\Gate::allows('finance_realisasi_document'), 403);
        $bulan = (!empty($_GET["bulan"])) ? ($_GET["bulan"]) : ('');
        $status = (!empty($_GET["status"])) ? ($_GET["status"]) : ("0");

        if($request->ajax())
        {
            if($bulan == ''){
                $where = '';
            }else{
                $where = ' and (month(A.tanggal_bayar)=\''.$bulan.'\' or A.tanggal_bayar is null) ';
            }

            $data = DB::select('
                select A.id, A.document_id, A.kode_category, 
                format(A.tanggal_bayar,\'dd-MMM-yyyy\') as tanggal_bayar, 
                format(C.tanggal_realisasi,\'dd-MMM-yyyy\') as tanggal_realisasi,
                A.nama_tujuan, A.kode_bank, 
                REPLACE(FORMAT(A.jumlah, \'N\', \'en-us\'), \'.00\', \'\') as jumlah, 
                isnull(REPLACE(FORMAT(C.jumlah_realisasi, \'N\', \'en-us\'), \'.00\', \'\'),\'0\') as jumlah_realisasi, 
                A.is_pu, A.no_ref, A.keterangan, isnull(C.status_realisasi,\'0\') as status_realisasi, B.last_status
                from approval.dbo.document_digital A
                join approval.dbo.document_master B on A.document_id=B.id
                left join finance.dbo.realisasi_document C on A.id=c.document_digital_id
                where B.last_status in(\'proses_payment\',\'closed\') '.$where.'
                and isnull(C.status_realisasi,\'0\')='.$status.' and 
				datediff(day,A.tanggal_bayar,getdate()) >= -7 order by A.document_id desc');
            
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $button = '';
                    if($data->last_status == "proses_payment"){
                    $button .= '<a href="realisasi-document/'.$data->document_id.'/edit">Edit</a>';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('finance.realisasi-document.index', compact('bulan','status'));
    }

    public function edit($id)
    {
        abort_unless(\Gate::allows('finance_realisasi_document'), 403);
        $nik = auth()->user()->nik;
        $kode_dept= auth()->user()->kode_departemen;
        $kode_jabatan= auth()->user()->kode_jabatan;

        $temp = DB::select('select id, nik, nama, kode_departemen, keterangan, last_status, created_at, created_by
        from approval.dbo.document_master where id=\''.$id.'\' and last_status=\'proses_payment\'');
    
        if(count($temp) <= 0){
            abort(403);
        }

        $data = $temp[0];

        $data_pu = DB::Select('select isnull(is_pu,\'0\') as pu from approval.dbo.document_digital 
        where document_id=\''.$id.'\' and kode_category=\'VM\'');

        if(count($data_pu) > 0){
            if($data_pu[0]->pu == "0"){
                $punya = "unchecked";
            }else{
                $punya = "checked";
            }
        }else{
            $punya = "";
        }

        return view('finance.realisasi-document.edit', compact('data','nik','punya'));

    }

    public function realisasiDocument(Request $request)
    {
        
        $id             = $request->id;
        $tgl_realisasi  = $request->tanggal_realisasi;
        $nik            = auth()->user()->nik;

        if($request->file){
            request()->validate([
                'file'  => 'mimetypes:application/pdf,image/jpeg,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|max:1024',
            ]);

            $file       = $request->file;
            $tgl        = date('Ymd_His');
            $nama_file  = $id.'_BUKTI_'.$tgl.'.'.$file->getClientOriginalExtension();
        }else{
            $nama_file = "-";
        }
        

        try{
            $temp = DB::select('finance.dbo.sp_realisasi_document \''.$id.'\',\''.$tgl_realisasi.'\',\''.$nama_file.'\',\''.$nik.'\'');
            $data = $temp[0];
            if ($data->hasil == "ok"){
                if($request->file){
                    $file->move('file', $nama_file);
                }

                
            //kirim e-mail notifikasi
            $hasil_email = $this->notifMail($id);

            if($hasil_email == "ok"){
                return response()->json(['success' => "Berhasil realisasi dan mengirim email"]);
            }else{
                return response()->json(['success' => "Berhasil realisasi tapi gagal mengirim email -> ".$hasil_email]);
            }
            }else{
                return response()->json(['errors' => $data->hasil]);
            }
            
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function rejectDocument(Request $request)
    {
        $id             = $request->id;
        $alasan         = $request->alasan;
        $nik            = auth()->user()->nik;
        
        try{
            $temp = DB::select('finance.dbo.sp_reject_document \''.$id.'\',\''.$alasan.'\',\''.$nik.'\'');
            $data = $temp[0];
            if ($data->hasil == "ok"){
                    return response()->json(['success' => "Berhasil reject"]);
            }else{
                return response()->json(['errors' => $data->hasil]);
            }
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function notifMail($id){
        try{

            //dapetin email pembuat dokumen
            $temp_email = DB::select('select A.nama as name, B.email from approval.dbo.document_master A
            join users B on A.nik=B.nik
            where A.id=\''.$id.'\'');
            $data_email = $temp_email[0];

            $name            = auth()->user()->name;

            $details = [
                'title' => 'AMS - Finance',
                'body' => 'Dh, '.$data_email->name.'. Nomor Dokumen : '.$id.'
                 sudah dibayarkan oleh '.$name.' '
            ];
        
            \Mail::to($data_email->email)->send(new \App\Mail\financeNotifMail($details));
            return "ok";
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
?>