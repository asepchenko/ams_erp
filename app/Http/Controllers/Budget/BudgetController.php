<?php

namespace App\Http\Controllers\Budget;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator, Redirect, Response, File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use DataTables;
use Illuminate\Support\Str;

class BudgetController extends Controller
{
    public function test(Request $request)
    {
        //abort_unless(\Gate::allows('approval_document_access'), 403);
        $periode        = (!empty($_GET["periode"])) ? ($_GET["periode"]) : ('');
        $tahun          = (!empty($_GET["tahun"])) ? ($_GET["tahun"]) : ('');
        $kodegroup      = (!empty($_GET["kodegroup"])) ? ($_GET["kodegroup"]) : ('');
        $nik            = auth()->user()->nik;
        $name           = auth()->user()->name;
        $dept           = auth()->user()->kode_departemen;
        $jab            = auth()->user()->kode_jabatan;
        $group          = auth()->user()->kode_group;
        $data_prioritas = DB::select('select id, priority_name from approval.dbo.document_priority where is_active=1 order by priority_name');

        if (\Gate::allows('budget_access_special')) {
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget  
            union select \'all\' as kode_groupstr order by kode_groupstr asc');
        } elseif ($nik == '00002158' or $nik == '80002825') {
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr 
            from budget.dbo.tr_budget where kode_group in (\'OPR\',\'BDV\',\'MARKETING\')) as x');
        } else {
            $temp_group = auth()->user()->kode_group;
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget where kode_group = \'' . $temp_group . '\') as x');
        }


        if ($request->ajax()) {
            $where = 'where 1=1 ';
            $query = '';
            if ($periode == 'all') {
                $where .= '';
                $query .= ', UPPER(\'' . $periode . '\') as periode, a.value_q1 + a.value_q2 + a.value_q3 + a.value_q4 as jum_budget, 
                    isnull(a.progress_q1,0) + isnull(a.progress_q2,0) + isnull(a.progress_q3,0) + isnull(a.progress_q4,0) as jum_proses, 
                    isnull(a.real_q1,0) + isnull(real_q2,0) + isnull(real_q3,0) + isnull(real_q4,0) as jum_realisasi, 
                    (a.value_q1 + a.value_q2 + a.value_q3 + a.value_q4) - 
                    ((isnull(a.progress_q1,0) + isnull(a.progress_q2,0) + isnull(a.progress_q3,0) + isnull(a.progress_q4,0)) + (isnull(a.real_q1,0) + isnull(real_q2,0) + isnull(real_q3,0) + isnull(real_q4,0))) as jum_sisa';
            } else {
                $kvalue   = 'a.value_' . $periode;
                $kproses  = 'a.progress_' . $periode;
                $kreal    = 'a.real_' . $periode;
                $ksisa    = 'a.sisa_' . $periode;
                $query   .= ', UPPER(\'' . $periode . '\') as periode, ' . $kvalue . ' as jum_budget, ' . $kproses . ' as jum_proses, ' . $kreal . ' as jum_realisasi, ' . $kvalue . ' - (isnull(' . $kproses . ',0) + isnull(' . $kreal . ',0)) as jum_sisa';
            }
            if ($tahun == '') {
                $where .= ' and year = year(getdate())';
                $query .= ', year(getdate()) as tahun ';
            } else {
                $where .= ' and year = \'' . $tahun . '\'';
                $query .= ', UPPER(\'' . $tahun . '\') as tahun ';
            }
            if (\Gate::allows('budget_access_special') and $kodegroup == '') {
                $where .= '';
            } elseif (\Gate::allows('budget_access_special') and $kodegroup != '') {
                $where .= ' and kode_group = \'' . $kodegroup . '\'';
            } elseif ($kodegroup == '') {
                $where .= ' and kode_group = \'' . $temp_group . '\'';
            } else {
                $where .= ' and kode_group = \'' . $kodegroup . '\'';
            }


            $data = DB::select('select sumber.* from (select a.budget_id, a.kode_group, a.coa, a.description' . $query . ' from budget.dbo.tr_budget a ' . $where . '
                ) as sumber order by sumber.kode_group asc');

            // }
            //where A.current_dept=(select Z.kode_departemen from users Z where Z.nik=\''.$nik.'\')


            return DataTables::of($data)
                ->make(true);
        }
        return view('budget.budgeting.index', compact('nik', 'name', 'data_prioritas', 'group', 'data_group', 'periode', 'tahun'));
    }

    public function index(Request $request)
    {
        //abort_unless(\Gate::allows('approval_document_access'), 403);
        $periode        = (!empty($_GET["periode"])) ? ($_GET["periode"]) : ('');
        $tahun          = (!empty($_GET["tahun"])) ? ($_GET["tahun"]) : date("Y");
        $kodegroup      = (!empty($_GET["kodegroup"])) ? ($_GET["kodegroup"]) : ('');
        $nik            = auth()->user()->nik;
        $name           = auth()->user()->name;
        $dept           = auth()->user()->kode_departemen;
        $jab            = auth()->user()->kode_jabatan;
        $group          = auth()->user()->kode_group;
        $data_prioritas = DB::select('select id, priority_name from approval.dbo.document_priority where is_active=1 order by priority_name');

        if (\Gate::allows('budget_access_special')) {
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget_new order by kode_groupstr asc');
        } elseif ($nik == '80002825') {
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr 
            from budget.dbo.tr_budget_new where kode_group in (\'OPR\',\'BDV\',\'MARKETING\')) as x');
        } else {
            $temp_group = auth()->user()->kode_group;
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget_new where kode_group = \'' . $temp_group . '\') as x');
        }


        if ($request->ajax()) {


            // if ($tahun == 2023) {
            //     $data = DB::select('
            //     select * from
            //     (
            //         SELECT \'Q1\' as periode, \' \' as bulanstr, a.year as tahun, a.budget_id, a.kode_group, a.coa,a.description, a.value_q1 as nilai, a.progress_q1 as progress, 
            //         a.real_q1 as realisasi, isnull(a.value_q1,0) - (isnull(a.progress_q1,0) + isnull(a.real_q1,0)) as sisa,
            // 		(select x.id from budget.dbo.tr_budget_request x where x.budget_id = a.budget_id and x.tahun = \'' . $tahun . '\' and x.periode = \'q1\') as kode_anggaran
            //         FROM budget.dbo.tr_budget a ' . $where2 . '
            //         union all
            //         SELECT \'Q2\' as periode, \' \' as bulanstr, a.year as tahun, a.budget_id, a.kode_group, a.coa,a.description, a.value_q2 as nilai, a.progress_q2 as progress, 
            //         a.real_q2 as realisasi, isnull(a.value_q2,0) - (isnull(a.progress_q2,0) + isnull(a.real_q2,0)) as sisa,
            // 		(select x.id from budget.dbo.tr_budget_request x where x.budget_id = a.budget_id and x.tahun = \'' . $tahun . '\' and x.periode = \'q2\') as kode_anggaran
            //         FROM budget.dbo.tr_budget a ' . $where2 . '
            //         union all
            //         SELECT \'Q3\' as periode, \' \' as bulanstr, a.year as tahun,  a.budget_id, a.kode_group, a.coa,a.description, a.value_q3 as nilai, a.progress_q3 as progress, 
            //         a.real_q3 as realisasi, isnull(a.value_q3,0) - (isnull(a.progress_q3,0) + isnull(a.real_q3,0)) as sisa,
            // 		(select x.id from budget.dbo.tr_budget_request x where x.budget_id = a.budget_id and x.tahun = \'' . $tahun . '\' and x.periode = \'q3\') as kode_anggaran
            //         FROM budget.dbo.tr_budget a ' . $where2 . '
            //         union all
            //         SELECT \'Q4\' as periode, \' \' as bulanstr, a.year as tahun, a.budget_id, a.kode_group, a.coa, a.description, a.value_q4 as nilai, a.progress_q4 as progress, 
            //         a.real_q4 as realisasi, isnull(a.value_q4,0) - (isnull(a.progress_q4,0) + isnull(a.real_q4,0)) as sisa,
            // 		(select x.id from budget.dbo.tr_budget_request x where x.budget_id = a.budget_id and x.tahun = \'' . $tahun . '\' and x.periode = \'q4\') as kode_anggaran
            //         FROM budget.dbo.tr_budget a ' . $where2 . '
            //     ) as sumber
            //     where 1=1 ' . $where1 . '  order by sumber.kode_group, sumber.description, sumber.periode');
            // } else {
            if ($periode == 'all') {
                $where3 = '';
            } else {
                $where3 = ' and a.periode = UPPER(\'' . $periode . '\') ';
            }


            $data = DB::select("select a.periode, concat(a.bulan,'-',a.bulanstr) as bulanstr, a.tahun, a.budget_id, a.kode_group, a.coa, a.deskripsi as description, a.nilai_budget as nilai, 
                a.progress, a.realisasi, a.sisa_anggaran as sisa, a.kode_anggaran from budget.dbo.v_listbudget a 
                where a.tahun = " . $tahun . " and a.kode_group = '" . $kodegroup . "'" . $where3 . "  ORDER by a.bulan asc");
            // }


            // }
            //where A.current_dept=(select Z.kode_departemen from users Z where Z.nik=\''.$nik.'\')


            return DataTables::of($data)
                ->addColumn('btnprogress', function ($data) {
                    $button  = '';
                    $button .= '<a href="reportdetail/search/' . $data->kode_group . '/' . $data->tahun . '/' . strtolower($data->periode) . '/' . $data->kode_anggaran . '/proses" target="_blank">Progress</a>';
                    return $button;
                })
                ->addColumn('btnrealisasi', function ($data) {
                    $button  = '';
                    $button .= '<a href="reportdetail/search/' . $data->kode_group . '/' . $data->tahun . '/' . strtolower($data->periode) . '/' . $data->kode_anggaran . '/closed" target="_blank">Realisasi</a>';
                    return $button;
                })
                ->rawColumns(['btnprogress', 'btnrealisasi'])
                ->make(true);
        }
        return view('budget.budgeting.index', compact('nik', 'name', 'data_prioritas', 'group', 'data_group', 'periode', 'tahun'));
    }
}
