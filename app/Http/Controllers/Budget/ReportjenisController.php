<?php

namespace App\Http\Controllers\Budget;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;

class ReportjenisController extends Controller
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
        $coapilih = ''; 
        $kodegroup = '';
        $coaname = '';

        if(\Gate::allows('budget_access_special')){
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget  
            union select \'all\' as kode_groupstr order by kode_groupstr asc');
        }else{
            $temp_group = auth()->user()->kode_group; 
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget where kode_group = \''.$temp_group.'\') as x');
            
        }
        $data_coa = DB::select('select a.coagroup, concat(a.coagroup,\' - \',b.keterangan) as deskripsi from BUDGET.dbo.v_coabudget a left join 
        budget.dbo.dt_coa b on a.coagroup =  b.account order by a.coagroup desc');
        $datanya = "";

        return view('budget.reportjenis.index', compact('nik','name','group','tahun','data_group','kodegroup','periode','data_coa','coapilih','coaname'));
    }

    public function searchData($coa, $periode, $tahun, $group)
    {
        //abort_unless(\Gate::allows('approval_document_access'), 403);
        $coapilih = (!empty($coa)) ? ($coa) : ('');
        $periode = (!empty($periode)) ? ($periode) : ('');
        $tahun = (!empty($tahun)) ? ($tahun) : ('');
        $kodegroup = (!empty($group)) ? ($group) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen; 
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group; 
        $data_prioritas = DB::select('select id, priority_name from approval.dbo.document_priority where is_active=1 order by priority_name');
        
        $data_coa = DB::select('select a.coagroup, concat(a.coagroup,\' - \',b.keterangan) as deskripsi from BUDGET.dbo.v_coabudget a left join 
        budget.dbo.dt_coa b on a.coagroup =  b.account order by a.coagroup desc');

        if(\Gate::allows('budget_access_special')){
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget  
            union select \'all\' as kode_groupstr order by kode_groupstr asc');
        }else{
            $temp_group = auth()->user()->kode_group; 
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget where kode_group = \''.$temp_group.'\') as x');
        }
        
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
                if(\Gate::allows('budget_access_special') and $kodegroup == 'all'){
                    $where1 .= '';
                }elseif(\Gate::allows('budget_access_special') and $kodegroup != ''){
                    $where1 .= ' and sumber.kode_group = \''.$kodegroup.'\'';
                }
                elseif($kodegroup == ''){
                    $where1 .= ' and sumber.kode_group = \''.$temp_group.'\'';
                }else{
                    $where1 .= ' and sumber.kode_group = \''.$kodegroup.'\'';
                }
                $where1 .= ' and left(sumber.coa,3) = \''.$coapilih.'\'';
    
                $query = '
                select sumber.nilai as budget, sumber.realisasi, sumber.sisa, sumber.periode, sumber.tahun, sumber.kode_group, sumber.coagroup, sumber.coa, 
                sumber.description as deskripsi, b.keterangan, case when sumber.nilai = 0 then 0 else (sumber.realisasi/sumber.nilai) * 100 end as persentase  from
                (
                    SELECT \'Q1\' as periode, year as tahun, budget_id, kode_group, coa, left(coa,3) as coagroup,description, isnull(value_q1,0) as nilai, isnull(progress_q1,0) as progress, 
                    isnull(real_q1,0) as realisasi, isnull(value_q1,0) - (isnull(progress_q1,0) + isnull(real_q1,0)) as sisa
                    FROM budget.dbo.tr_budget 
                    union all
                    SELECT \'Q2\' as periode, year as tahun, budget_id, kode_group, coa,left(coa,3) as coagroup, description, isnull(value_q2,0) as nilai, isnull(progress_q2,0) as progress, 
                    isnull(real_q2,0) as realisasi, isnull(value_q2,0) - (isnull(progress_q2,0) + isnull(real_q2,0)) as sisa
                    FROM budget.dbo.tr_budget 
                    union all
                    SELECT \'Q3\' as periode, year as tahun,  budget_id, kode_group, coa, left(coa,3) as coagroup,description, isnull(value_q3,0) as nilai, isnull(progress_q3,0) as progress, 
                    isnull(real_q3,0) as realisasi, isnull(value_q3,0) - (isnull(progress_q3,0) + isnull(real_q3,0)) as sisa
                    FROM budget.dbo.tr_budget 
                    union all
                    SELECT \'Q4\' as periode, year as tahun, budget_id, kode_group, coa, left(coa,3) as coagroup,description, isnull(value_q4,0) as nilai, isnull(progress_q4,0) as progress, 
                    isnull(real_q4,0) as realisasi, isnull(value_q4,0) - (isnull(progress_q4,0) + isnull(real_q4,0)) as sisa
                    FROM budget.dbo.tr_budget 
                ) as sumber
                left join budget.dbo.dt_coa b on sumber.coagroup = b.account
                where 1=1 '.$where1.'';
               //dd($query);
                $datanya = DB::select($query);
                
            // }
            //where A.current_dept=(select Z.kode_departemen from users Z where Z.nik=\''.$nik.'\')

            
            //return DataTables::of($data)
              //      ->make(true);

        return view('budget.reportjenis.index', compact('data_coa','periode','tahun','coapilih','name','datanya','kodegroup','tahun','data_group'));
    }
}
