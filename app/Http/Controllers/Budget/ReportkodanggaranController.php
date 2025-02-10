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

class ReportkodanggaranController extends Controller
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
            order by kode_groupstr asc');
        } elseif ($nik == '80002825') {
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr 
            from budget.dbo.tr_budget where kode_group in (\'OPR\',\'BDV\',\'MARKETING\')) as x');
        } else {
            $temp_group = auth()->user()->kode_group;
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget where kode_group = \'' . $temp_group . '\') as x');
        }
        $datanya = "";

        return view('budget.reportkodanggaran.index', compact('nik', 'name', 'group', 'tahun', 'data_group', 'kodegroup', 'start_period'));
    }

    public function searchData($start_period, $tahun, $group)
    {
        // abort_unless(\Gate::allows('approval_report_document'), 403);
        $title = "Report Anggaran Bulanan, Periode " . strtoupper($start_period) . " Tahun " . $tahun;
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
            order by kode_groupstr asc');
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
            $awal = $tahun . "-01-01";
            $budget = "value_q1";
        } elseif ($start_period == "q2") {
            $awal = $tahun . "-04-01";
            $budget = "value_q2";
        } elseif ($start_period == "q3") {
            $awal = $tahun . "-07-01";
            $budget = "value_q3";
        } else {
            $awal = $tahun . "-10-01";
            $budget = "value_q4";
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
        $query = "select * from (
            select xx.* from(
            select a.kode_group, a.kode_anggaran, a.descanggaran, sum(pemakaian) as pemakaian, bln_buat, bulan_buat, periode, tahun, thn_buat,
            case when last_status not in ('closed','cancel') then 'belum' else 'closed' end as last_status from approval.dbo.v_document_budget a
            where last_status in ('closed') and periode = '" . $start_period . "'
            group by kode_group, kode_anggaran, descanggaran, bln_buat, bulan_buat, periode, tahun, thn_buat, last_status
            union all
            select a.kode_group, a.kode_anggaran, a.descanggaran, sum(pemakaian) as pemakaian, bln_buat, bulan_buat, periode, tahun, thn_buat,
            case when last_status not in ('closed','cancel') then 'belum' else 'closed' end as last_status from approval.dbo.v_document_budget a
            where last_status not in ('closed','cancel') and periode = '" . $start_period . "'
            group by kode_group, kode_anggaran, descanggaran, bln_buat, bulan_buat, periode, tahun, thn_buat, last_status) as xx)
            src 
            pivot
            (sum(pemakaian)
            for last_status in ([closed],[belum])
            ) piv
            where 1=1" . $where . " 
            order by kode_group,descanggaran, thn_buat, bln_buat";
        //dd($query);
        $datanya = DB::select($query);
        // $qbudget = "select sum(".$budget.") as nilai_budget, kode_group from budget.dbo.tr_budget group by kode_group";
        $qbudget = "select isnull(nilai_budget,0) as nilai_budget, kode_group, keterangan from budget.dbo.v_listbudget where kode_group = '" . $kodegroup . "' and periode = '" . $start_period . "' and tahun = " . $tahun . "";

        $databudget = DB::select($qbudget);

        return view('budget.reportkodanggaran.index', compact('title', 'name', 'datanya', 'kodegroup', 'tahun', 'data_group', 'start_period', 'databudget'));
    }
}
