<?php

namespace App\Http\Controllers\Budget;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator, Redirect, Response, File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;

class ReportanggaranController extends Controller
{
    public function index(Request $request)
    {
        // $kodegroup = (!empty($_GET["kodegroup"])) ? ($_GET["kodegroup"]) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen;
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group;
        $kodegroup = '';
        $bulan = '';
        $brand = "";

        if (\Gate::allows('budget_access_special')) {
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget_new order by kode_groupstr asc');
        } elseif ($nik == '80002825') {
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr 
            from budget.dbo.tr_budget_new where kode_group in (\'OPR\',\'BDV\',\'MARKETING\')) as x');
        } else {
            $temp_group = auth()->user()->kode_group;
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget_new where kode_group = \'' . $temp_group . '\') as x');
        }
        $datanya = "";

        return view('budget.reportdocanggaran.index', compact('brand', 'nik', 'name', 'group', 'bulan', 'data_group', 'kodegroup'));
    }

    public function searchData($bulan, $brand, $group)
    {
        // abort_unless(\Gate::allows('approval_report_document'), 403);
        $kodegroup = (!empty($group)) ? ($group) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen;
        $jab = auth()->user()->kode_jabatan;
        $where = "where 1 = 1 ";

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

        // $tahun = now('year');
        //$where .= " and bulan=".$bulan."";
        $where = "where last_status not in ('cancel') ";
        $where .= "and month(tanggal_buat) = '" . $bulan . "' and year(tanggal_buat) = 2025 ";

        if (\Gate::allows('budget_access_special') and $kodegroup == 'all') {
            $where .= '';
        } elseif (\Gate::allows('budget_access_special') and $kodegroup != 'all') {
            $where .= ' and kode_group = \'' . $kodegroup . '\'';
        } elseif ($kodegroup == 'all') {
            $where .= '';
        } else {
            $where .= ' and kode_group = \'' . $kodegroup . '\'';
        }
        if ($brand == 'all') {
            $where .= "";
        } else {
            $where .= " and brand = '" . $brand . "'";
        }

        $query = " select document_id, no_document, format(tanggal_buat,'dd-MMM-yyyy') as tgl_buat, kode_departemen, nama, 
        keterangan, descanggaran, kode_group, jumlah_vm, pemakaian,  brand, last_status, bulan 
        From approval.dbo.v_document_budget_new " . $where . " order by tanggal_buat asc";
        // dd($query);
        $datanya = DB::select($query);

        return view('budget.reportdocanggaran.index', compact('datanya', 'brand', 'nik', 'name', 'group', 'bulan', 'data_group', 'kodegroup'));
    }
}
