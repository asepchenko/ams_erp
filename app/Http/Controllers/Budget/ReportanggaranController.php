<?php

namespace App\Http\Controllers\Budget;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;

class ReportanggaranController extends Controller
{
    public function index(Request $request)
    {
        // $periode = (!empty($_GET["periode"])) ? ($_GET["periode"]) : ('');
        $tahun = (!empty($_GET["tahun"])) ? ($_GET["tahun"]) : ('');
        // $kodegroup = (!empty($_GET["kodegroup"])) ? ($_GET["kodegroup"]) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen; 
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group; 
        $kodegroup = '';
        
        if(\Gate::allows('budget_access_special')){
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget  
            union select \'all\' as kode_groupstr order by kode_groupstr asc');
        }elseif($nik == '80002825'){
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr 
            from budget.dbo.tr_budget where kode_group in (\'OPR\',\'BDV\',\'MARKETING\')) as x');
        }
        else{
            $temp_group = auth()->user()->kode_group; 
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget where kode_group = \''.$temp_group.'\') as x');
            
        }
        $datanya = "";

        return view('budget.reporttahunan.index', compact('nik','name','group','tahun','data_group','kodegroup'));
    }

    public function searchData($tahun, $group)
    {
        // abort_unless(\Gate::allows('approval_report_document'), 403);
        $tahun = (!empty($tahun)) ? ($tahun) : ('');
        $kodegroup = (!empty($group)) ? ($group) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen; 
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group; 
        $where = "where 1 = 1 ";

        if(\Gate::allows('budget_access_special')){
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget  
            union select \'all\' as kode_groupstr order by kode_groupstr asc');
        }elseif($nik == '80002825'){
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr 
            from budget.dbo.tr_budget where kode_group in (\'OPR\',\'BDV\',\'MARKETING\')) as x');
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

        $query = " select 
        budget_q1, real_q1, sisa_q1, budget_q2, real_q2, sisa_q2, budget_q3, real_q3, sisa_q3, budget_q4, 
        real_q4 ,sisa_q4, sumtotal, kode_group, sumtotal as summary, sumreal,
        sisa_q1 + sisa_q2 + sisa_q4 + sisa_q4 as sumsisa,
        case when budget_q1 = 0 then 0 else (real_q1/budget_q1) * 100 end as persen_q1,
        case when budget_q2 = 0 then 0 else (real_q2/budget_q2) * 100 end as persen_q2,
        case when budget_q3 = 0 then 0 else (real_q3/budget_q3) * 100 end as persen_q3,
        case when budget_q4 = 0 then 0 else (real_q4/budget_q4) * 100 end as persen_q4,
         tahun, status from budget.dbo.v_report_periode as data 
        ".$where." order by kode_group";
        //dd($query);
        $datanya = DB::select($query);

        return view('budget.reporttahunan.index', compact('name','datanya','kodegroup','tahun','data_group'));
    }

    public function PrintPdf($tahun, $group)
    {
        $tahun = (!empty($tahun)) ? ($tahun) : ('');
        $kodegroup = (!empty($group)) ? ($group) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen; 
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group; 
        $where = "where 1 = 1 ";

        if(\Gate::allows('budget_access_special')){
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget  
            union select \'all\' as kode_groupstr order by kode_groupstr asc');
        }else{
            $temp_group = auth()->user()->kode_group; 
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget where kode_group = \''.$temp_group.'\') as x');
        }

        //$where .= " and bulan=".$bulan."";
        if($tahun == "now"){
            $where .= " and data.tahun = year(getdate())";
        }
         else{
            $where .= " and data.tahun = '".$tahun."'";
        }

        if(\Gate::allows('budget_access_special') and $kodegroup == 'all'){
            $where .= '';
        }elseif(\Gate::allows('budget_access_special') and $kodegroup != 'all'){
            $where .= ' and data.kode_group = \''.$kodegroup.'\'';
        }
        elseif($kodegroup == 'all'){
            $where .= '';
        }else{
            $where .= ' and data.kode_group = \''.$kodegroup.'\'';
        }

        $query = " select 
        REPLACE(FORMAT(data.budget_q1, 'N', 'en-us'), '.00', '') as budget_q1, 
        REPLACE(FORMAT(data.real_q1, 'N', 'en-us'), '.00', '') as real_q1,
        REPLACE(FORMAT(data.sisa_q1, 'N', 'en-us'), '.00', '') as sisa_q1,
        REPLACE(FORMAT(data.budget_q2, 'N', 'en-us'), '.00', '') as budget_q2, 
        REPLACE(FORMAT(data.real_q2, 'N', 'en-us'), '.00', '') as real_q2,
        REPLACE(FORMAT(data.sisa_q2, 'N', 'en-us'), '.00', '') as sisa_q2,
        REPLACE(FORMAT(data.budget_q3, 'N', 'en-us'), '.00', '') as budget_q3, 
        REPLACE(FORMAT(data.real_q3, 'N', 'en-us'), '.00', '') as real_q3,
        REPLACE(FORMAT(data.sisa_q3, 'N', 'en-us'), '.00', '') as sisa_q3,
        REPLACE(FORMAT(data.budget_q4, 'N', 'en-us'), '.00', '') as budget_q4, 
        REPLACE(FORMAT(data.real_q4, 'N', 'en-us'), '.00', '') as real_q4,
        REPLACE(FORMAT(data.sisa_q4, 'N', 'en-us'), '.00', '') as sisa_q4,
        REPLACE(FORMAT(data.sumtotal, 'N', 'en-us'), '.00', '') as sumtotal,
        data.kode_group, data.sumtotal as summary, data.tahun, data.status  from budget.dbo.v_report_periode as data 
        ".$where." order by kode_group asc";
        //dd($query);
        $datanya = DB::select($query);

        return view('budget.reporttahunan.printpdf', compact('nama','datanya','kodegroup','tahun','data_group'));
    }
}
