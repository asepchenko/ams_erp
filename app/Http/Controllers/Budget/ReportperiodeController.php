<?php

namespace App\Http\Controllers\Budget;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;

class ReportperiodeController extends Controller
{
    public function index(Request $request)
    {
        // $periode = (!empty($_GET["periode"])) ? ($_GET["periode"]) : ('');
        $tahun = date("Y");
        // $kodegroup = (!empty($_GET["kodegroup"])) ? ($_GET["kodegroup"]) : ('');
        $periode = "q1";
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen; 
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group; 
        $kodegroup = '';
        
        if(\Gate::allows('budget_access_special')){
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget  
            union select \'all\' as kode_groupstr order by kode_groupstr asc');
        }else{
            $temp_group = auth()->user()->kode_group; 
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget where kode_group = \''.$temp_group.'\') as x');
            
        }
        $datanya = "";

        return view('budget.reportperiode.index', compact('nik','name','group','tahun','data_group','kodegroup','periode'));
    }

    public function searchData($periode, $tahun, $group)
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
        if($periode == 'q1'){
            $query = "
            SELECT 'Q1' as periode, kode_group, budget_q1 as jum_budget, real_q1 as jum_real, sisa_q1 as jum_sisa, 
            case when budget_q1 = 0 then 0 else (real_q1/budget_q1) * 100 end as persentase, status, tahun 
            from budget.dbo.v_report_periode as data ".$where." order by kode_group";
        }elseif($periode =='q2'){
            $query = "
            SELECT 'Q2' as periode, kode_group, budget_q2 as jum_budget, real_q2 as jum_real, sisa_q2 as jum_sisa, 
            case when budget_q2 = 0 then 0 else (real_q2/budget_q2) * 100 end as persentase, status, tahun 
            from budget.dbo.v_report_periode as data ".$where." 
            order by kode_group";
        }elseif($periode =='q3'){
            $query = "
            SELECT 'Q3' as periode, kode_group, budget_q3 as jum_budget, real_q3 as jum_real, sisa_q3 as jum_sisa, 
            case when budget_q3 = 0 then 0 else (real_q3/budget_q3) * 100 end as persentase, status, tahun 
            from budget.dbo.v_report_periode as data ".$where." 
            order by kode_group";
        }elseif($periode =='q4'){
            $query = "
            SELECT 'Q4' as periode, kode_group, budget_q3 as jum_budget, real_q3 as jum_real, sisa_q3 as jum_sisa, 
            case when budget_q3 = 0 then 0 else (real_q3/budget_q3) * 100 end as persentase, status, tahun 
            from budget.dbo.v_report_periode as data ".$where." 
            order by kode_group";
        }else{
            $query = "
            SELECT 'ALL' as periode, kode_group, budget_q4 + budget_q3 + budget_q2 + budget_q1 as jum_budget, 
            real_q4 + real_q3 + real_q2 + real_q1 as jum_real, sisa_q4 + sisa_q3 + sisa_q2 + sisa_q1 as jum_sisa, 
            case when (budget_q4 + budget_q3 + budget_q2 + budget_q1) = 0 then 0 else 
            ((real_q4 + real_q3 + real_q2 + real_q1)/(budget_q4 + budget_q3 + budget_q2 + budget_q1)) * 100 end as persentase, status, tahun 
            from budget.dbo.v_report_periode as data ".$where." 
            order by kode_group";
        }
        //dd($query);
        $datanya = DB::select($query);

        return view('budget.reportperiode.index', compact('name','datanya','kodegroup','tahun','data_group','periode'));
    }
}
