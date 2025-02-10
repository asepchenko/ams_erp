<?php

namespace App\Http\Controllers\Budget;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use DataTables;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        //abort_unless(\Gate::allows('approval_document_access'), 403);
        $periode = (!empty($_GET["periode"])) ? ($_GET["periode"]) : ('');
        $tahun = (!empty($_GET["tahun"])) ? ($_GET["tahun"]) : ('');
        $kodegroup = (!empty($_GET["kodegroup"])) ? ($_GET["kodegroup"]) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen; 
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group; 
        $data_prioritas = DB::select('select id, priority_name from approval.dbo.document_priority where is_active=1 order by priority_name');
        
        if(\Gate::allows('budget_access_special')){
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget  
            union select \'all\' as kode_groupstr order by kode_groupstr asc');
        }else{
            $temp_group = auth()->user()->kode_group; 
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget where kode_group = \''.$temp_group.'\') as x');
            
        }
        
        
        if($request->ajax())
        {
                $where = 'where 1=1 ';
                $query = '';
                if($periode == 'all'){
                    $where .= '';
                    $query .= ', UPPER(\''.$periode.'\') as periode, isnull(a.value_q1,0) + isnull(a.value_q2,0) + isnull(a.value_q3,0) + isnull(a.value_q4,0) as jum_budget, 
                    isnull(a.progress_q1,0) + isnull(a.progress_q2,0) + isnull(a.progress_q3,0) + isnull(a.progress_q4,0) as jum_proses, 
                    isnull(a.real_q1,0) + isnull(real_q2,0) + isnull(real_q3,0) + isnull(real_q4,0) as jum_realisasi, 
                    (isnull(a.value_q1,0) + isnull(a.value_q2,0) + isnull(a.value_q3,0) + isnull(a.value_q4,0)) - 
                    ((isnull(a.progress_q1,0) + isnull(a.progress_q2,0) + isnull(a.progress_q3,0) + isnull(a.progress_q4,0)) + (isnull(a.real_q1,0) + isnull(real_q2,0) + isnull(real_q3,0) + isnull(real_q4,0))) as jum_sisa';
                }else{
                    $kvalue = 'a.value_'.$periode;
                    $kproses = 'a.progress_'.$periode;
                    $kreal = 'a.real_'.$periode;
                    $ksisa = 'a.sisa_'.$periode;
                    $query .= ', UPPER(\''.$periode.'\') as periode, isnull('.$kvalue.',0) as jum_budget, isnull('.$kproses.',0) as jum_proses, isnull('.$kreal.',0) as jum_realisasi, isnull('.$kvalue.',0) - (isnull('.$kproses.',0) + isnull('.$kreal.',0)) as jum_sisa'; 
                }
                if($tahun == ''){
                    $where .= ' and year = year(getdate())';
                    $query .= ', year(getdate()) as tahun ';
                }else{
                    $where .= ' and year = \''.$tahun.'\'';
                    $query .= ', UPPER(\''.$tahun.'\') as tahun ';
                }
                if(\Gate::allows('budget_access_special') and $kodegroup == 'all'){
                    $where .= '';
                }elseif(\Gate::allows('budget_access_special') and $kodegroup == ''){
                    $where .= '';
                }elseif(\Gate::allows('budget_access_special') and $kodegroup != ''){
                    $where .= ' and kode_group = \''.$kodegroup.'\'';
                }
                elseif($kodegroup == ''){
                    $where .= ' and kode_group = \''.$temp_group.'\'';
                }else{
                    $where .= ' and kode_group = \''.$kodegroup.'\'';
                }

    
                $data = DB::select('select sumber.* from (select a.budget_id, a.kode_group, a.coa, a.description'.$query.' from budget.dbo.tr_budget a '.$where.'
                ) as sumber order by sumber.kode_group asc');
                
            // }
            //where A.current_dept=(select Z.kode_departemen from users Z where Z.nik=\''.$nik.'\')

            
            return DataTables::of($data)
                    ->make(true);

            
        }
        return view('budget.dashboard.index', compact('bulan','nik','name','data_prioritas','group','data_group','periode','tahun'));
    }

    public function test(Request $request)
    {
        //abort_unless(\Gate::allows('approval_document_access'), 403);
        $periode = (!empty($_GET["periode"])) ? ($_GET["periode"]) : ('');
        $tahun = (!empty($_GET["tahun"])) ? ($_GET["tahun"]) : ('');
        $kodegroup = (!empty($_GET["kodegroup"])) ? ($_GET["kodegroup"]) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen; 
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group; 
        $data_prioritas = DB::select('select id, priority_name from approval.dbo.document_priority where is_active=1 order by priority_name');
        
        if(\Gate::allows('budget_access_special')){
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget  
            union select \'all\' as kode_groupstr order by kode_groupstr asc');
        }else{
            $temp_group = auth()->user()->kode_group; 
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget where kode_group = \''.$temp_group.'\') as x');
        }
        
        
        if($request->ajax())
        {
                $where2 = 'where 1=1 ';
                $where1 = ' ';
                if($periode == 'all'){
                    $where1 .= '';
                }else{
                    $where1 = ' and sumber.periode = UPPER(\''.$periode.'\') ';
                }
                if($tahun == ''){
                    $where1 .= ' and sumber.tahun = year(getdate())';
                    
                }else{
                    $where1 .= ' and sumber.tahun = \''.$tahun.'\'';
                    
                }
                if(\Gate::allows('budget_access_special') and $kodegroup == ''){
                    $where2 .= '';
                }elseif(\Gate::allows('budget_access_special') and $kodegroup != ''){
                    $where2 .= ' and kode_group = \''.$kodegroup.'\'';
                }
                elseif($kodegroup == ''){
                    $where2 .= ' and kode_group = \''.$temp_group.'\'';
                }else{
                    $where2 .= ' and kode_group = \''.$kodegroup.'\'';
                }
    
                $data = DB::select('
                select * from
                (
                    SELECT \'Q1\' as periode, year as tahun, budget_id, kode_group, coa,description, value_q1 as nilai, progress_q1 as progress, 
                    real_q1 as realisasi, isnull(value_q1,0) - (isnull(progress_q1,0) + isnull(real_q1,0)) as sisa
                    FROM budget.dbo.tr_budget '.$where2.'
                    union all
                    SELECT \'Q2\' as periode, year as tahun, budget_id, kode_group, coa,description, value_q2 as nilai, progress_q2 as progress, 
                    real_q2 as realisasi, isnull(value_q2,0) - (isnull(progress_q2,0) + isnull(real_q2,0)) as sisa
                    FROM budget.dbo.tr_budget '.$where2.'
                    union all
                    SELECT \'Q3\' as periode, year as tahun,  budget_id, kode_group, coa,description, value_q3 as nilai, progress_q3 as progress, 
                    real_q3 as realisasi, isnull(value_q3,0) - (isnull(progress_q3,0) + isnull(real_q3,0)) as sisa
                    FROM budget.dbo.tr_budget '.$where2.'
                    union all
                    SELECT \'Q4\' as periode, year as tahun, budget_id, kode_group, coa,description, value_q4 as nilai, progress_q4 as progress, 
                    real_q4 as realisasi, isnull(value_q4,0) - (isnull(progress_q4,0) + isnull(real_q4,0)) as sisa
                    FROM budget.dbo.tr_budget '.$where2.'
                ) as sumber
                where 1=1 '.$where1.'
                ');
                
            // }
            //where A.current_dept=(select Z.kode_departemen from users Z where Z.nik=\''.$nik.'\')

            
            return DataTables::of($data)
                    ->make(true);

            
        }
        return view('budget.dashboard.index', compact('bulan','nik','name','data_prioritas','group','data_group','periode','tahun'));
    }
}