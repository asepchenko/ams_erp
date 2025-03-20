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

class ReportSemesterController extends Controller
{
    public function index(Request $request)
    {
        // $periode = (!empty($_GET["periode"])) ? ($_GET["periode"]) : ('');
        $tahun = date("Y");
        // $kodegroup = (!empty($_GET["kodegroup"])) ? ($_GET["kodegroup"]) : ('');
        $start_period = "";
        $end_period = "";
        $type_budget = "";
        $brand = "";
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen;
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group;
        $kodegroup = '';

        if (\Gate::allows('budget_access_special')) {
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget_new  
            union select \'all\' as kode_groupstr order by kode_groupstr asc');
        } elseif ($nik == '80002825') {
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr 
            from budget.dbo.tr_budget_new where kode_group in (\'OPR\',\'BDV\',\'MARKETING\')) as x');
        } else {
            $temp_group = auth()->user()->kode_group;
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget_new where kode_group = \'' . $temp_group . '\') as x');
        }
        $datanya = "";

        return view('budget.reportsemester.index', compact('nik', 'name', 'group', 'tahun', 'data_group', 'kodegroup', 'start_period', 'end_period', 'type_budget', 'brand'));
    }

    public function searchData($start_period, $tahun, $type_budget, $group, $brand)
    {
        // abort_unless(\Gate::allows('approval_report_document'), 403);
        $title = "Report Realisasi " . strtoupper($start_period) . " Tahun " . $tahun;
        $tahun = (!empty($tahun)) ? ($tahun) : ('');
        $kodegroup = (!empty($group)) ? ($group) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen;
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group;
        $where = "where 1 = 1 ";

        if (\Gate::allows('budget_access_special')) {
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget_new  order by kode_groupstr asc');
        } elseif ($nik == '80002825') {
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr 
            from budget.dbo.tr_budget_new where kode_group in (\'OPR\',\'BDV\',\'MARKETING\')) as x');
        } else {
            $temp_group = auth()->user()->kode_group;
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget_new where kode_group = \'' . $temp_group . '\') as x');
        }

        //$where .= " and bulan=".$bulan."";
        if ($tahun == "now") {
            $tahun = date("Y");
            $where .= " and a.tahun = year(getdate())";
        } else {
            $where .= " and a.tahun = " . $tahun . "";
        }
        $query1 = "";

        if ($start_period == "sm1") {
            if ($type_budget == "ori") {
                $query1 .= " awal.budget_sm1 as jum_budget, awal.progres_sm1 as progress, 
            awal.real_sm1 as jum_real, cast((awal.budget_sm1 * 75) / 100 as decimal(18,2)) as budget_75 ";
            } else {
                $query1 .= " awal.progres_sm1 as progress, 
            awal.real_sm1 as jum_real, cast((awal.budget_sm1 * 75) / 100 as decimal(18,2)) as jum_budget ";
            }
        } elseif ($start_period == "sm2") {
            if ($type_budget == "ori") {
                $query1 .= " awal.budget_sm2 as jum_budget, awal.progres_sm2 as progress, 
            awal.real_sm2 as jum_real, cast((awal.budget_sm1 * 75) / 100 as decimal(18,2)) as budget_75 ";
            } else {
                $query1 .= " awal.progres_sm2 as progress, 
            awal.real_sm2 as jum_real, cast((awal.budget_sm1 * 75) / 100 as decimal(18,2)) as jum_budget ";
            }
        } else {
            if ($type_budget == "ori") {
                $query1 .= " awal.budget_1th as jum_budget, awal.progres_1th as progress, 
            awal.real_1th as jum_real, cast((awal.budget_1th * 75) / 100 as decimal(18,2)) as budget_75 ";
            } else {
                $query1 .= " awal.progres_1th as progress, 
            awal.real_1th as jum_real, cast((awal.budget_1th * 75) / 100 as decimal(18,2)) as jum_budget ";
            }
        }

        $where1 = "";
        if ($brand == "all") {
            $where1 .= "";
        } elseif ($brand == "HO") {
            $where1 .= " and b.brand = 'HO'";
        } elseif ($brand == "RC") {
            $where1 .= " and b.brand = 'RC'";
        } elseif ($brand == "RCW") {
            $where1 .= " and b.brand = 'RCW'";
        } elseif ($brand == "RM") {
            $where1 .= " and b.brand = 'RM'";
        } elseif ($brand == "RQ") {
            $where1 .= " and b.brand = 'RQ'";
        } else {
            $where1 .= "";
        }


        if (\Gate::allows('budget_access_special') and $kodegroup == 'all') {
            $where .= '';
        } elseif (\Gate::allows('budget_access_special') and $kodegroup != 'all') {
            $where .= ' and kode_group = \'' . $kodegroup . '\'';
        } elseif ($kodegroup == 'all') {
            $where .= '';
        } else {
            $where .= ' and a.kode_group = \'' . $kodegroup . '\'';
        }
        $query = "select awal.kode_group, awal.description as keterangan, " . $query1 . " from (
select a.kode_group, a.budget_id, a.keterangan as description, a.tahun, a.value_01 + a.value_02 + a.value_03 + a.value_04 + a.value_05 + a.value_06 as budget_sm1,
	(select isnull(sum(b.jumlah_vm),0) from approval.dbo.v_document_budget_new b where b.budget_id = a.budget_id and b.bulan  in (1,2,3,4,5,6) and last_status not in ('cancel','closed') and created_by != 'open budget' and b.document_type not in ('kbr') " . $where1 . " ) as progres_sm1,
	(select isnull(sum(b.jumlah_vm),0) from approval.dbo.v_document_budget_new b where b.budget_id = a.budget_id and b.bulan in (1,2,3,4,5,6) and last_status in ('closed') and created_by != 'open budget' and b.document_type not in ('kbr') " . $where1 . " ) as real_sm1,
	a.value_07 + a.value_08 + a.value_09 + a.value_10 + a.value_11 + a.value_12 as budget_sm2,
	(select isnull(sum(b.jumlah_vm),0) from approval.dbo.v_document_budget_new b where b.budget_id = a.budget_id and b.bulan in (7,8,9,10,11,12) and last_status not in ('cancel','closed') and created_by != 'open budget' and b.document_type not in ('kbr') " . $where1 . " ) as progres_sm2,
	(select isnull(sum(b.jumlah_vm),0) from approval.dbo.v_document_budget_new b where b.budget_id = a.budget_id and b.bulan in (7,8,9,10,11,12) and last_status in ('closed') and created_by != 'open budget' and b.document_type not in ('kbr') " . $where1 . " ) as real_sm2,
	a.value_01 + a.value_02 + a.value_03 + a.value_04 + a.value_05 + a.value_06 + a.value_07 + a.value_08 + a.value_09 + a.value_10 + a.value_11 + a.value_12 as budget_1th,
	(select isnull(sum(b.jumlah_vm),0) from approval.dbo.v_document_budget_new b where b.budget_id = a.budget_id and b.bulan in (1,2,3,4,5,6,7,8,9,10,11,12) and last_status not in ('cancel','closed') and created_by != 'open budget' and b.document_type not in ('kbr') " . $where1 . " ) as progres_1th,
	(select isnull(sum(b.jumlah_vm),0) from approval.dbo.v_document_budget_new b where b.budget_id = a.budget_id and b.bulan in (1,2,3,4,5,6,7,8,9,10,11,12) and last_status in ('closed') and created_by != 'open budget' and b.document_type not in ('kbr') " . $where1 . " ) as real_1th
	from budget.dbo.v_budget_awal a " . $where . " ) as awal 
	order by [description]";
        // dd($query);
        $datanya = DB::select($query);

        return view('budget.reportsemester.index', compact('title', 'name', 'datanya', 'kodegroup', 'tahun', 'data_group', 'start_period', 'type_budget', 'brand'));
    }
}
