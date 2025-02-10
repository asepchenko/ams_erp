<?php

namespace App\Http\Controllers\Budget;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator, Redirect, Response, File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;

class ReportCfController extends Controller
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

        return view('budget.reportcf.index2', compact('nik', 'name', 'group', 'tahun', 'data_group', 'kodegroup'));
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
        // $group = auth()->user()->kode_group;
        $where = "where 1=1 ";

        if ($tahun == "now") {
            $tahun = date("Y");
            $where .= " and a.tahun = year(getdate())";
        } else {
            $where .= " and a.tahun = '" . $tahun . "'";
        }

        $query_income = "
select a.tahun, a.label_income as label_report, a.urutan, 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 1) as budget_01 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 1) as realisasi_01 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 2) as budget_02 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 2) as realisasi_02 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 3) as budget_03 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 3) as realisasi_03 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 4) as budget_04,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 4) as realisasi_04 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 5) as budget_05 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 5) as realisasi_05 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 6) as budget_06 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 6) as realisasi_06 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 7) as budget_07 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 7) as realisasi_07 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 8) as budget_08 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 8) as realisasi_08 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 9) as budget_09 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 9) as realisasi_09 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 10) as budget_10 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 10) as realisasi_10 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 11) as budget_11 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 11) as realisasi_11 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 12) as budget_12 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 12) as realisasi_12 
from budget.dbo.tr_income a " . $where . " order by urutan asc";
        $dataincome = DB::select($query_income);

        $query = "select a.label_report, a.urutan, (select isnull(sum(z.value_01),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as budget_01,
(select isnull((sum(z.real_01) + sum(z.progres_01)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_01,
(select isnull(sum(z.value_02),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_02,
(select isnull((sum(z.real_02) + sum(z.progres_02)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_02,
(select isnull(sum(z.value_03),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_03,
(select isnull((sum(z.real_03) + sum(z.progres_03)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_03,
(select isnull(sum(z.value_04),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_04,
(select isnull((sum(z.real_04) + sum(z.progres_04)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_04,
(select isnull(sum(z.value_05),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_05,
(select isnull((sum(z.real_05) + sum(z.progres_05)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_05,
(select isnull(sum(z.value_06),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_06,
(select isnull((sum(z.real_06) + sum(z.progres_06)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_06,
(select isnull(sum(z.value_07),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_07,
(select isnull((sum(z.real_07) + sum(z.progres_07)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_07,
(select isnull(sum(z.value_08),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_08,
(select isnull((sum(z.real_08) + sum(z.progres_08)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_08,
(select isnull(sum(z.value_09),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_09,
(select isnull((sum(z.real_09) + sum(z.progres_09)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_09,
(select isnull(sum(z.value_10),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_10,
(select isnull((sum(z.real_10) + sum(z.progres_10)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_10,
(select isnull(sum(z.value_11),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_11,
(select isnull((sum(z.real_11) + sum(z.progres_11)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_11,
(select isnull(sum(z.value_12),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_12,
(select isnull((sum(z.real_12) + sum(z.progres_12)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_12
from budget.dbo.tr_report_cashflow a " . $where . " order by urutan asc";
        // dd($query);
        // $datanya = DB::select($query);
        // if ($kodegroup == 'sm1') {
        //     return view('budget.reportcf.index', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        // } elseif ($kodegroup == 'sm2') {
        //     return view('budget.reportcf.sm2', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        // } elseif ($kodegroup == 'q1') {
        //     return view('budget.reportcf.q1', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        // } elseif ($kodegroup == 'q2') {
        //     return view('budget.reportcf.q2', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        // } elseif ($kodegroup == 'q3') {
        //     return view('budget.reportcf.q3', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        // } elseif ($kodegroup == 'q4') {
        //     return view('budget.reportcf.q4', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        // } else {
        //     return view('budget.reportcf.index', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        // }
        $datanya = DB::select($query);
        if ($kodegroup == 'sm1') {
            return view('budget.reportcf.index2', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        } elseif ($kodegroup == 'sm2') {
            return view('budget.reportcf.sm22', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        } elseif ($kodegroup == 'q1') {
            return view('budget.reportcf.q12', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        } elseif ($kodegroup == 'q2') {
            return view('budget.reportcf.q22', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        } elseif ($kodegroup == 'q3') {
            return view('budget.reportcf.q32', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        } elseif ($kodegroup == 'q4') {
            return view('budget.reportcf.q42', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        } else {
            return view('budget.reportcf.index2', compact(
                'name',
                'datanya',
                'kodegroup',
                'tahun'
            ));
        }
    }
    public function print($tahun, $group)
    {
        // abort_unless(\Gate::allows('approval_report_document'), 403);
        $tahun = (!empty($tahun)) ? ($tahun) : ('');
        $kodegroup = (!empty($group)) ? ($group) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen;
        $jab = auth()->user()->kode_jabatan;
        // $group = auth()->user()->kode_group;
        $where = "where 1=1 ";

        if ($tahun == "now") {
            $tahun = date("Y");
            $where .= " and a.tahun = year(getdate())";
        } else {
            $where .= " and a.tahun = '" . $tahun . "'";
        }

        $query_income = "
select a.tahun, a.label_income as label_report, a.urutan, 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 1) as budget_01 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 1) as realisasi_01 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 2) as budget_02 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 2) as realisasi_02 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 3) as budget_03 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 3) as realisasi_03 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 4) as budget_04,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 4) as realisasi_04 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 5) as budget_05 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 5) as realisasi_05 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 6) as budget_06 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 6) as realisasi_06 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 7) as budget_07 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 7) as realisasi_07 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 8) as budget_08 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 8) as realisasi_08 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 9) as budget_09 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 9) as realisasi_09 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 10) as budget_10 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 10) as realisasi_10 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 11) as budget_11 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 11) as realisasi_11 , 
(select ISNULL(z.budget_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 12) as budget_12 ,
(select ISNULL(z.real_income,0) from budget.dbo.tr_income_detail z where a.id = z.id_income and bulan = 12) as realisasi_12 
from budget.dbo.tr_income a " . $where . " order by urutan asc";
        $dataincome = DB::select($query_income);

        $query = "select a.label_report, a.urutan, (select isnull(sum(z.value_01),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as budget_01,
(select isnull((sum(z.real_01) + sum(z.progres_01)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_01,
(select isnull(sum(z.value_02),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_02,
(select isnull((sum(z.real_02) + sum(z.progres_02)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_02,
(select isnull(sum(z.value_03),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_03,
(select isnull((sum(z.real_03) + sum(z.progres_03)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_03,
(select isnull(sum(z.value_04),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_04,
(select isnull((sum(z.real_04) + sum(z.progres_04)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_04,
(select isnull(sum(z.value_05),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_05,
(select isnull((sum(z.real_05) + sum(z.progres_05)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_05,
(select isnull(sum(z.value_06),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_06,
(select isnull((sum(z.real_06) + sum(z.progres_06)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_06,
(select isnull(sum(z.value_07),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_07,
(select isnull((sum(z.real_07) + sum(z.progres_07)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_07,
(select isnull(sum(z.value_08),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_08,
(select isnull((sum(z.real_08) + sum(z.progres_08)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_08,
(select isnull(sum(z.value_09),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_09,
(select isnull((sum(z.real_09) + sum(z.progres_09)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_09,
(select isnull(sum(z.value_10),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_10,
(select isnull((sum(z.real_10) + sum(z.progres_10)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_10,
(select isnull(sum(z.value_11),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_11,
(select isnull((sum(z.real_11) + sum(z.progres_11)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_11,
(select isnull(sum(z.value_12),0) from budget.dbo.tr_budget_new z where a.urutan = z.coa) as budget_12,
(select isnull((sum(z.real_12) + sum(z.progres_12)),0) from budget.dbo.v_report_cf z where a.urutan = z.coa) as realisasi_12
from budget.dbo.tr_report_cashflow a " . $where . " order by urutan asc";
        // dd($query);
        $datanya = DB::select($query);
        if ($kodegroup == 'sm1') {
            return view('budget.reportcf.printsm1', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        } elseif ($kodegroup == 'sm2') {
            return view('budget.reportcf.printsm2', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        } elseif ($kodegroup == 'q1') {
            return view('budget.reportcf.printq1', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        } elseif ($kodegroup == 'q2') {
            return view('budget.reportcf.printq2', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        } elseif ($kodegroup == 'q3') {
            return view('budget.reportcf.printq3', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        } elseif ($kodegroup == 'q4') {
            return view('budget.reportcf.printq4', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        } else {
            return view('budget.reportcf.printsm1', compact('name', 'datanya', 'kodegroup', 'tahun', 'dataincome'));
        }
    }
    public function searchdetil($coa, $tahun, $bulan)
    {

        // abort_unless(\Gate::allows('approval_report_document'), 403);

        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen;
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group;
        $where = " where c.urutan = " . $coa . " and  a.bulan = 1 and a.bln_realisasi = 1";


        //$where .= " and bulan=".$bulan."";
        if ($tahun == "now") {
            $tahun = date("Y");
            $where .= " and a.tahun = year(getdate())";
        } else {
            $where .= " and a.tahun = '" . $tahun . "'";
        }
        $query = "select a.document_id, a.nama,  a.descanggaran, a.kode_anggaran, a.keterangan, format(a.tanggal_buat,'dd-MMM-yyyy') as tanggal_buat, 
format(a.tanggal_realisasi,'dd-MMM-yyyy') as tanggal_realisasi, a.pemakaian from approval.dbo.v_document_budget a 
inner join BUDGET.dbo.tr_budget_new b on a.budget_id = b.budget_id
inner join BUDGET.dbo.tr_report_cashflow c on b.coa = c.urutan " . $where . " order by kode_anggaran ";
        //dd($query);
        $datanya = DB::select($query);

        $label = DB::select('select label_report from BUDGET.dbo.tr_report_cashflow where urutan = ' . $coa . '');

        //$title = "Report Periode Bulanan, Kuartal " . $label[0]->label_report . " Tahun " . $tahun;

        return view('budget.reportcfdetil.index', compact('name', 'datanya', 'tahun'));
    }
}
