<?php

namespace App\Http\Controllers\Budget;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator, Redirect, Response, File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class ReportperbulanController extends Controller
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
        $kodegroup = '';

        if (\Gate::allows('budget_access_special')) {
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget  
            union select \'all\' as kode_groupstr order by kode_groupstr asc');
        } elseif ($nik == '80002825') {
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr 
            from budget.dbo.tr_budget where kode_group in (\'OPR\',\'BDV\',\'MARKETING\')) as x');
        } else {
            $temp_group = auth()->user()->kode_group;
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget where kode_group = \'' . $temp_group . '\') as x');
        }
        $datanya = "";

        return view('budget.reportperbulan.index', compact('nik', 'name', 'group', 'tahun', 'data_group', 'kodegroup', 'start_period', 'end_period'));
    }

    public function searchData($start_period, $end_period, $tahun, $group)
    {
        // abort_unless(\Gate::allows('approval_report_document'), 403);
        $title = "Report Periode Bulanan, Kuartal " . strtoupper($start_period) . " Tahun " . $tahun;
        $tahun = (!empty($tahun)) ? ($tahun) : ('');
        $kodegroup = (!empty($group)) ? ($group) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen;
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group;
        $where = "";

        if (\Gate::allows('budget_access_special')) {
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget  
            union select \'all\' as kode_groupstr order by kode_groupstr asc');
        } elseif ($nik == '80002825') {
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr 
            from budget.dbo.tr_budget where kode_group in (\'OPR\',\'BDV\',\'MARKETING\')) as x');
        } else {
            $temp_group = auth()->user()->kode_group;
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget where kode_group = \'' . $temp_group . '\') as x');
        }

        //$where .= " and bulan=".$bulan."";
        if ($tahun == "now") {
            $tahun = date("Y");
            $where .= " and tahun = year(getdate())";
        } else {
            $where .= " and tahun = '" . $tahun . "'";
        }
        if ($start_period == "q1") {
            $awal = 1;
        } elseif ($start_period == "q2") {
            $awal = 2;
        } elseif ($start_period == "q3") {
            $awal = 3;
        } else {
            $awal = 4;
        }
        if ($end_period == "q1") {
            $akhir = 1;
        } elseif ($end_period == "q2") {
            $akhir = 2;
        } elseif ($end_period == "q3") {
            $akhir = 3;
        } else {
            $akhir = 4;
        }



        if (\Gate::allows('budget_access_special') and $kodegroup == 'all') {
            $where .= '';
        } elseif (\Gate::allows('budget_access_special') and $kodegroup != 'all') {
            $where .= ' and kode_group = \'' . $kodegroup . '\'';
        } elseif ($kodegroup == 'all') {
            $where .= '';
        } else {
            $where .= ' and kode_group = \'' . $kodegroup . '\'';
        }
        $query = "
        select * From approval.dbo.v_document_budget_pivot where 1=1 " . $where . " and periode_buat >= " . $awal . " and periode_buat <= " . $akhir . "
        order by descanggaran, bln_buat";





        // select piv.*, b.nilai_request as budgetnya from (
        //     select xx.* from(
        //     select kode_group, descanggaran, periode_buat, bln_buat, bulan_buat, tahun, thn_buat, sum(pemakaian) as pemakaian, case when last_status not in ('closed','cancel') then 'belum' else 'closed' end as last_status
        //     from APPROVAL.dbo.v_document_budget where last_status = 'closed' and periode_buat >= " . $awal . " and periode_buat <= " . $akhir . " and tahun = " . $tahun . " and last_status != 'open budget' and document_type not in ('kbr') 
        //     group by kode_group, descanggaran, periode_buat, bln_buat, bulan_buat, tahun, thn_buat, last_status
        //     union all
        //     select kode_group, descanggaran, periode_buat, bln_buat, bulan_buat, tahun, thn_buat, sum(pemakaian) as pemakaian, case when last_status not in ('closed','cancel') then 'belum' else 'closed' end as last_status
        //     from APPROVAL.dbo.v_document_budget where last_status not in ('closed','cancel') and periode_buat >= " . $awal . " and periode_buat <= " . $akhir . " and tahun = " . $tahun . " and last_status != 'open budget' and document_type not in ('kbr') 
        //     group by kode_group, descanggaran, periode_buat, bln_buat, bulan_buat, tahun, thn_buat, last_status) as xx)
        //     src 
        //     pivot
        //     (sum(pemakaian)
        //     for last_status in ([closed],[belum])
        //     ) piv 
        //     left join BUDGET.dbo.tr_budget_request b on descanggaran = b.keterangan and periode_buat = b.bulan and b.kode_group = '" . $kodegroup . "'
        //     where 1=1" . $where . " 
        //     order by kode_group, descanggaran, bln_buat";
        //dd($query);
        $datanya = DB::select($query);
        $where2 = "";
        if (\Gate::allows('budget_access_special') and $kodegroup == 'all') {
            $where2 .= '';
        } elseif (\Gate::allows('budget_access_special') and $kodegroup != 'all') {
            $where2 .= ' and kode_group = \'' . $kodegroup . '\'';
        } elseif ($kodegroup == 'all') {
            $where2 .= '';
        } else {
            $where2 .= ' and kode_group = \'' . $kodegroup . '\'';
        }
        // if ($tahun == 2023) {

        //     $qbudget = "select sum(" . $budget . ") as nilai_budget, kode_group from budget.dbo.tr_budget where 1=1 " . $where2 . " group by kode_group";
        // } else {
        //     $qbudget = "select sum(nilai_budget) as nilai_budget, kode_group from BUDGET.dbo.v_listbudget where 1=1 and periode = '" . $start_period . "' and tahun = 2024 " . $where2 . "  group by kode_group";
        // }
        //dd($qbudget);
        // $databudget = DB::select($qbudget);

        return view('budget.reportperbulan.index', compact('title', 'name', 'datanya', 'kodegroup', 'tahun', 'data_group', 'start_period', 'end_period'));
    }
}
