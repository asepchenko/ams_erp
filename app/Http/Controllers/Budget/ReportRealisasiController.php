<?php

namespace App\Http\Controllers\Budget;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator, Redirect, Response, File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;

class ReportRealisasiController extends Controller
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

        return view('budget.reportrealisasi.index', compact('nik', 'name', 'group', 'tahun', 'data_group', 'kodegroup'));
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
        $where = "";

        if (\Gate::allows('budget_access_special')) {
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget order by kode_groupstr asc');
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
            $where .= " and a.[year] = year(getdate())";
        } else {
            $where .= " and a.[year] = '" . $tahun . "'";
        }

        if (\Gate::allows('budget_access_special') and $kodegroup == 'all') {
            $where .= '';
        } elseif (\Gate::allows('budget_access_special') and $kodegroup != 'all') {
            $where .= ' and a.kode_group = \'' . $kodegroup . '\'';
        } elseif ($kodegroup == 'all') {
            $where .= '';
        } else {
            $where .= ' and a.kode_group = \'' . $kodegroup . '\'';
        }
        $query = " select a.*, Total_realisasi = a.January + a.February + a.March + a.April + a.May + a.June + a.July + a.August + a.September + a.October + a.November + a.December from 
            (select a.kode_group,a.description,
            January = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
            ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 1 and created_by != 'open budget' and document_type not in ('kbr')),
            February = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
            ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 2 and created_by != 'open budget' and document_type not in ('kbr')),
            March = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
            ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 3 and created_by != 'open budget' and document_type not in ('kbr')),
            April = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
            ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 4 and created_by != 'open budget' and document_type not in ('kbr')),
            May = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
            ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 5 and created_by != 'open budget' and document_type not in ('kbr')),
            June = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
            ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 6 and created_by != 'open budget' and document_type not in ('kbr')),
            July = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
            ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 7 and created_by != 'open budget' and document_type not in ('kbr')),
            August = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
            ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 8 and created_by != 'open budget' and document_type not in ('kbr')),
            September = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
            ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 9 and created_by != 'open budget' and document_type not in ('kbr')),
            October = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
            ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 10 and created_by != 'open budget' and document_type not in ('kbr')),
            November = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
            ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 11 and created_by != 'open budget' and document_type not in ('kbr')),
            December = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
            ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 12 and created_by != 'open budget' and document_type not in ('kbr')), 
			Ori_budget = (select sum(z.nilai_request) from BUDGET.dbo.tr_budget_request z where z.budget_id = a.budget_id),	
            Total_budget = (select total_budget from BUDGET.dbo.tr_budget_new z where z.budget_id = a.budget_id), a.[year]
            from BUDGET.dbo.tr_budget_new a
            where 1=1 " . $where . "
            group by a.kode_group, a.budget_id, a.description, a.[year]) a order by a.kode_group, a.description";



        //dd($query);
        $datanya = DB::select($query);

        return view('budget.reportrealisasi.index', compact('name', 'datanya', 'kodegroup', 'group', 'tahun', 'data_group'));
    }
}
