<?php

namespace App\Http\Controllers\Budget;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class ReportprogressController extends Controller
{
    public function index(Request $request)
    {
        // $periode = (!empty($_GET["periode"])) ? ($_GET["periode"]) : ('');
        $tahun = date("Y");
        // $kodegroup = (!empty($_GET["kodegroup"])) ? ($_GET["kodegroup"]) : ('');
        $start_period = "";
        $end_period = "";
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen; 
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group; 
        $status = "";
        $kodegroup = '';
        $title = "Report Detil Realisasi";
        
        if(\Gate::allows('budget_access_special')){
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget  
            union select \'all\' as kode_groupstr order by kode_groupstr asc');
        }else{
            $temp_group = auth()->user()->kode_group; 
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget where kode_group = \''.$temp_group.'\') as x');
            
        }
        $datanya = "";

        return view('budget.reportprogress.index', compact('title','status','nik','name','group','tahun','data_group','kodegroup','start_period','end_period'));
    }

    public function searchData($start_period, $end_period, $tahun, $group,$status)
    {
        // abort_unless(\Gate::allows('approval_report_document'), 403);
        $title = "Report Detil Realisasi ".strtoupper($start_period)." s/d ".strtoupper($end_period)." Tahun ".$tahun;
        $tahun = (!empty($tahun)) ? ($tahun) : ('');
        $kodegroup = (!empty($group)) ? ($group) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen; 
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group; 
        $where = "";
        
        if(\Gate::allows('budget_access_special')){
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget  
            union select \'all\' as kode_groupstr order by kode_groupstr asc');
        }else{
            $temp_group = auth()->user()->kode_group; 
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget where kode_group = \''.$temp_group.'\') as x');
        }

        //$where .= " and bulan=".$bulan."";
        if($tahun == "now"){
            $tahun = date("Y");
            $where .= " and tahun = year(getdate())";
        }
         else{
            $where .= " and tahun = '".$tahun."'";
        }

        if($start_period == "q1"){
            $awal = $tahun."-03-31";
        }
        elseif($start_period == "q2"){
            $awal = $tahun."-06-30";
        }
        elseif($start_period == "q3"){
            $awal = $tahun."-09-30";
        }
        else{
            $awal = $tahun."-12-31";
        }
        if($end_period == "q1"){
            $akhir = $tahun."-03-31";
        }
        elseif($end_period == "q2"){
            $akhir = $tahun."-06-30";
        }
        elseif($end_period == "q3"){
            $akhir = $tahun."-09-30";
        }
        else{
            $akhir = $tahun."-12-31";
        }
        if($status =='closed'){
            $where .= " and last_status = 'closed'";
        }elseif($status == 'realisasi'){
            $where .= " and last_status = 'proses_realisasi'";
        }else{
            $where .= "";
        }

        if(\Gate::allows('budget_access_special') and $kodegroup == 'all'){
            $where .= '';
        }elseif(\Gate::allows('budget_access_special') and $kodegroup != 'all'){
            $where .= ' and kode_group = \''.$kodegroup.'\'';
        }
        elseif($kodegroup == 'all'){
            $where .= '';
        }else{
            $where .= ' and kode_group = \''.$kodegroup.'\'';
        }
        $query = "select distinct kodeanggaran, coa, deskripsi, kode_group, nilai_anggaran, last_status, sum(realisasi) as nilai_realisasi
        from budget.dbo.v_report_realisasi  
        where valid_to >= '".$awal."' and valid_to <= '".$akhir."' ".$where." group by kodeanggaran, coa, deskripsi, kode_group, nilai_anggaran, last_status
         order by kodeanggaran asc";
        //dd($query);
        $datanya = DB::select($query);

        return view('budget.reportprogress.index', compact('status','title','name','datanya','kodegroup','tahun','data_group','start_period','end_period'));
    }
}
