<?php

namespace App\Http\Controllers\Approval;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;

class SPDReportController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(\Gate::allows('approval_report_document'), 403);
        $bulan = date('m');
        $status_pilih = "";
        $dept_pilih = "";
        $nama = "";
        $status = DB::select('select nama_status FROM approval.dbo.status_list ORDER BY id');
        if(\Gate::allows('approval_file_special_access')){
            $dept = DB::select('select kodedepartemenstr FROM hris.dbo.tbldepartemen 
            where KodeDepartemenStr not in(\'\',\'* none\') 
            union select \'all\' as kodedepartemenstr
            ORDER BY kodedepartemenstr');
        }else{
            $temp_dept = auth()->user()->kode_departemen; 
            $dept = DB::select('select kodedepartemenstr FROM hris.dbo.tbldepartemen where KodeDepartemenStr=\''.$temp_dept.'\'');
        }
    
        return view('approval.spd-report.index',compact('nama','bulan','status','dept','status_pilih','dept_pilih'));
    }

    public function searchData($bulan, $status, $dept, $nama)
    {
        abort_unless(\Gate::allows('approval_report_document'), 403);
        
        $bulan = (!empty($bulan)) ? ($bulan):('01');
        $dept_pilih = (!empty($dept)) ? ($dept):('all');
        $where = " and month(A.created_at)=".$bulan."";
        $status_pilih = (!empty($status)) ? ($status):('all');
        $nama = (!empty($nama)) ? ($nama):('all');

        if($dept_pilih != "all"){
            $where .= " and A.kode_departemen='".$dept_pilih."' ";
        }

        if($status_pilih != "all"){
            $where .= " and A.last_status='".$status_pilih."'";
        }

        if($nama != "all"){
            $where .= " and A.nama='".$nama."'";
        }

        if(\Gate::allows('approval_file_special_access')){
            $where .= " ";
        }else{
            $where .= " and A.document_priority_id in (1,2) ";
        }

        $datanya = DB::select('select B.id, B.document_id, B.no_digital, B.nama_tujuan, B.rek_tujuan, 
        B.kode_bank, B.nama_rek, B.keterangan, A.kode_departemen, A.document_priority_id,
        B.jumlah, A.nama,
        A.last_status
        from approval.dbo.document_master A 
        join approval.dbo.document_digital B on A.id=B.document_id
        where B.no_digital <> \'\' '.$where.'
        order by B.no_digital desc');

        $status = DB::select('select nama_status FROM approval.dbo.status_list ORDER BY id');

        if(\Gate::allows('approval_file_special_access')){
            $dept = DB::select('select kodedepartemenstr FROM hris.dbo.tbldepartemen 
            where KodeDepartemenStr not in(\'\',\'* none\') 
            union select \'all\' as kodedepartemenstr
            ORDER BY kodedepartemenstr');
        }else{
            $temp_dept = auth()->user()->kode_departemen; 
            $dept = DB::select('select kodedepartemenstr FROM hris.dbo.tbldepartemen where KodeDepartemenStr=\''.$temp_dept.'\'');
        }
        return view('approval.spd-report.index', compact('nama','datanya','status','dept','bulan','status_pilih','dept_pilih'));
    }

    
}
