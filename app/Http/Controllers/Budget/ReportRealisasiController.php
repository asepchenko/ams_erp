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
            $where .= " and tahun = year(getdate())";
        } else {
            $where .= " and tahun = '" . $tahun . "'";
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
select kode_group, tahun, 
sum(value_01) as bud_januari, sum(progres_01) as prg_januari, sum(real_01) as real_januari,
sum(value_02) as bud_februari, sum(progres_02) as prg_februari, sum(real_02) as real_februari, 
sum(value_03) as bud_maret, sum(progres_03) as prg_maret, sum(real_03) as real_maret, 
sum(value_04) as bud_april, sum(progres_04) as prg_april, sum(real_04) as real_april, 
sum(value_05) as bud_mei, sum(progres_05) as prg_mei, sum(real_05) as real_mei, 
sum(value_06) as bud_juni, sum(progres_06) as prg_juni, sum(real_06) as real_juni,
sum(value_07) as bud_juli, sum(progres_07) as prg_juli, sum(real_07) as real_juli,
sum(value_08) as bud_agustus, sum(progres_08) as prg_agustus, sum(real_08) as real_agustus,
sum(value_09) as bud_september, sum(progres_09) as prg_september, sum(real_09) as real_september,
sum(value_10) as bud_oktober, sum(progres_10) as prg_oktober, sum(real_10) as real_oktober,
sum(value_11) as bud_november, sum(progres_11) as prg_november, sum(real_11) as real_november,
sum(value_12) as bud_desember, sum(progres_12) as prg_desember, sum(real_12) as real_desember,
sum(Budget) as tot_budget, sum(Progress) as tot_progress, sum(Realisasi) as tot_realisasi
From v_report_budget2024_awal" . $where . "
group by kode_group, tahun
order by kode_group ";
        // if ($tahun == '2023') {
        //     $query = " select a.*, Total_realisasi = a.January + a.February + a.March + a.April + a.May + a.June + a.July + a.August + a.September + a.October + a.November + a.December from 
        //     (select a.kode_group,a.descanggaran,
        //     January = (select isnull(sum(z.pemakaian),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bln_realisasi = 1),
        //     February = (select isnull(sum(z.pemakaian),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bln_realisasi = 2),
        //     March = (select isnull(sum(z.pemakaian),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bln_realisasi = 3),
        //     April = (select isnull(sum(z.pemakaian),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bln_realisasi = 4),
        //     May = (select isnull(sum(z.pemakaian),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bln_realisasi = 5),
        //     June = (select isnull(sum(z.pemakaian),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bln_realisasi = 6),
        //     July = (select isnull(sum(z.pemakaian),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bln_realisasi = 7),
        //     August = (select isnull(sum(z.pemakaian),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bln_realisasi = 8),
        //     September = (select isnull(sum(z.pemakaian),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bln_realisasi = 9),
        //     October = (select isnull(sum(z.pemakaian),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bln_realisasi = 10),
        //     November = (select isnull(sum(z.pemakaian),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bln_realisasi = 11),
        //     December = (select isnull(sum(z.pemakaian),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bln_realisasi = 12),tahun, thn_buat, 
        //     Total_budget = (select total_budget from BUDGET.dbo.tr_budget z where z.budget_id = a.budget_id)
        //     from approval.dbo.v_document_budget a
        //     where  a.last_status in ('closed') " . $where . "
        //     group by a.kode_group, a.budget_id, a.descanggaran, a.tahun, a.thn_buat ) a order by a.kode_group, a.descanggaran";
        // } else {
        //     $query = " select a.*, Total_realisasi = a.January + a.February + a.March + a.April + a.May + a.June + a.July + a.August + a.September + a.October + a.November + a.December from 
        //     (select a.kode_group,a.descanggaran,
        //     January = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 1 and created_by != 'open budget' and document_type not in ('kbr')),
        //     February = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 2 and created_by != 'open budget' and document_type not in ('kbr')),
        //     March = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 3 and created_by != 'open budget' and document_type not in ('kbr')),
        //     April = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 4 and created_by != 'open budget' and document_type not in ('kbr')),
        //     May = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 5 and created_by != 'open budget' and document_type not in ('kbr')),
        //     June = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 6 and created_by != 'open budget' and document_type not in ('kbr')),
        //     July = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 7 and created_by != 'open budget' and document_type not in ('kbr')),
        //     August = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 8 and created_by != 'open budget' and document_type not in ('kbr')),
        //     September = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 9 and created_by != 'open budget' and document_type not in ('kbr')),
        //     October = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 10 and created_by != 'open budget' and document_type not in ('kbr')),
        //     November = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 11 and created_by != 'open budget' and document_type not in ('kbr')),
        //     December = (select isnull(sum(z.jumlah_vm),0) from approval.dbo.v_document_budget z where  last_status in 
        //     ('closed') and a.kode_group = z.kode_group and a.budget_id = z.budget_id and  z.bulan = 12 and created_by != 'open budget' and document_type not in ('kbr')),tahun, thn_buat, 
        // 	Ori_budget = (select sum(z.nilai_request) from BUDGET.dbo.tr_budget_request z where z.budget_id = a.budget_id),	
        //     Total_budget = (select total_budget from BUDGET.dbo.tr_budget_new z where z.budget_id = a.budget_id)
        //     from approval.dbo.v_document_budget a
        //     where  a.last_status in ('closed') and created_by != 'open budget'  " . $where . "
        //     group by a.kode_group, a.budget_id, a.descanggaran, a.tahun, a.thn_buat ) a order by a.kode_group, a.descanggaran";
        // }

        //dd($query);
        $datanya = DB::select($query);

        return view('budget.reportrealisasi.index', compact('name', 'datanya', 'kodegroup', 'group', 'tahun', 'data_group'));
    }
}
