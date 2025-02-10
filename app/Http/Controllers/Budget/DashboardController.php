<?php

namespace App\Http\Controllers\Budget;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $periode = 'all';
        $tahun = 2023;
        $periodelabel = 'ALL';
        $kodegroup = (!empty($_GET["kodegroup"])) ? ($_GET["kodegroup"]) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen;
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group;
        $data_prioritas = DB::select('select id, priority_name from approval.dbo.document_priority where is_active=1 order by priority_name');

        if (\Gate::allows('budget_access_special')) {
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget  
            union select \'all\' as kode_groupstr order by kode_groupstr asc');
        } else {
            $temp_group = auth()->user()->kode_group;
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget where kode_group = \'' . $temp_group . '\') as x');
        }

        $datapie_ams = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_amsbudget
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\'');

        //pie acc
        $datapie_acc = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'ACC\'');

        //pie audit
        $datapie_audit = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'AUDIT\'');

        //pie bdv
        $datapie_bdv = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'BDV\'');

        //pie creative
        $datapie_creative = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'CREATIVE\'');

        //pie dgm
        $datapie_dgm = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'DGM\'');

        //pie direksi
        $datapie_direksi = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'DIREKSI\'');

        //pie finance
        $datapie_finance = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'FINANCE\'');

        //pie GA
        $datapie_ga = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'GA\'');

        //pie HRD
        $datapie_hrd = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'HRD\'');

        //pie HRDP
        $datapie_hrdp = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'HRDP\'');

        //pie IT
        $datapie_it = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'IT\'');

        //pie legal
        $datapie_legal = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'LEGAL\'');

        //pie marcomm
        $datapie_marcomm = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'MARCOMM\'');

        //pie marketing
        $datapie_marketing = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'MARKETING\'');

        //pie opr
        $datapie_opr = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'OPR\'');

        //pie OVERSEAS
        $datapie_overseas = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'MDB\'');

        //pie scm
        $datapie_scm = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'SCM\'');

        //pie scmwh
        // $datapie_scmwh = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
        // from budget.dbo.v_report_dashboard
        // unpivot (
        //     nilai for kode IN (Budget, Realisasi, Progress, Sisa
        //     )
        // ) unpvt where tahun = \''.$tahun.'\' and kode_group = \'SCMWH\'');

        //pie tax
        $datapie_tax = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'TAX\'');

        //pie vm
        $datapie_vm = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'VM\'');


        $chart_label = "";
        $chart_data = "";
        foreach ($datapie_ams as $row) {
            $chart_label .= "," . "'" . $row->kode . "'";
            $chart_data .= "," . $row->nilai;
        }

        $chart_label = substr($chart_label, 1);
        $chart_data = substr($chart_data, 1);

        $chart_label = "[" . str_replace('.', '', $chart_label) . "]";
        $chart_data = "[" . str_replace('.', '', $chart_data) . "]";

        //acc
        $label_acc = "";
        $data_acc = "";
        foreach ($datapie_acc as $row) {
            $label_acc .= "," . "'" . $row->kode . "'";
            $data_acc .= "," . $row->nilai;
        }

        $label_acc = substr($label_acc, 1);
        $data_acc = substr($data_acc, 1);

        $label_acc = "[" . str_replace('.', '', $label_acc) . "]";
        $data_acc = "[" . str_replace('.', '', $data_acc) . "]";

        //Audit
        $label_audit = "";
        $data_audit = "";
        foreach ($datapie_audit as $row) {
            $label_audit .= "," . "'" . $row->kode . "'";
            $data_audit .= "," . $row->nilai;
        }

        $label_audit = substr($label_audit, 1);
        $data_audit = substr($data_audit, 1);

        $label_audit = "[" . str_replace('.', '', $label_audit) . "]";
        $data_audit = "[" . str_replace('.', '', $data_audit) . "]";

        //bdv
        $label_bdv = "";
        $data_bdv = "";
        foreach ($datapie_bdv as $row) {
            $label_bdv .= "," . "'" . $row->kode . "'";
            $data_bdv .= "," . $row->nilai;
        }

        $label_bdv = substr($label_bdv, 1);
        $data_bdv = substr($data_bdv, 1);

        $label_bdv = "[" . str_replace('.', '', $label_bdv) . "]";
        $data_bdv = "[" . str_replace('.', '', $data_bdv) . "]";

        //creative
        $label_creative = "";
        $data_creative = "";
        foreach ($datapie_creative as $row) {
            $label_creative .= "," . "'" . $row->kode . "'";
            $data_creative .= "," . $row->nilai;
        }

        $label_creative = substr($label_creative, 1);
        $data_creative = substr($data_creative, 1);

        $label_creative = "[" . str_replace('.', '', $label_creative) . "]";
        $data_creative = "[" . str_replace('.', '', $data_creative) . "]";

        //dgm
        $label_dgm = "";
        $data_dgm = "";
        foreach ($datapie_dgm as $row) {
            $label_dgm .= "," . "'" . $row->kode . "'";
            $data_dgm .= "," . $row->nilai;
        }

        $label_dgm = substr($label_dgm, 1);
        $data_dgm = substr($data_dgm, 1);

        $label_dgm = "[" . str_replace('.', '', $label_dgm) . "]";
        $data_dgm = "[" . str_replace('.', '', $data_dgm) . "]";

        //direksi
        $label_direksi = "";
        $data_direksi = "";
        foreach ($datapie_direksi as $row) {
            $label_direksi .= "," . "'" . $row->kode . "'";
            $data_direksi .= "," . $row->nilai;
        }

        $label_direksi = substr($label_direksi, 1);
        $data_direksi = substr($data_direksi, 1);

        $label_direksi = "[" . str_replace('.', '', $label_direksi) . "]";
        $data_direksi = "[" . str_replace('.', '', $data_direksi) . "]";

        //finance
        $label_finance = "";
        $data_finance = "";
        foreach ($datapie_finance as $row) {
            $label_finance .= "," . "'" . $row->kode . "'";
            $data_finance .= "," . $row->nilai;
        }

        $label_finance = substr($label_finance, 1);
        $data_finance = substr($data_finance, 1);

        $label_finance = "[" . str_replace('.', '', $label_finance) . "]";
        $data_finance = "[" . str_replace('.', '', $data_finance) . "]";

        //GA
        $label_ga = "";
        $data_ga = "";
        foreach ($datapie_ga as $row) {
            $label_ga .= "," . "'" . $row->kode . "'";
            $data_ga .= "," . $row->nilai;
        }

        $label_ga = substr($label_ga, 1);
        $data_ga = substr($data_ga, 1);

        $label_ga = "[" . str_replace('.', '', $label_ga) . "]";
        $data_ga = "[" . str_replace('.', '', $data_ga) . "]";

        //hrd
        $label_hrd = "";
        $data_hrd = "";
        foreach ($datapie_hrd as $row) {
            $label_hrd .= "," . "'" . $row->kode . "'";
            $data_hrd .= "," . $row->nilai;
        }

        $label_hrd = substr($label_hrd, 1);
        $data_hrd = substr($data_hrd, 1);

        $label_hrd = "[" . str_replace('.', '', $label_hrd) . "]";
        $data_hrd = "[" . str_replace('.', '', $data_hrd) . "]";

        //hrdp
        $label_hrdp = "";
        $data_hrdp = "";
        foreach ($datapie_hrdp as $row) {
            $label_hrdp .= "," . "'" . $row->kode . "'";
            $data_hrdp .= "," . $row->nilai;
        }

        $label_hrdp = substr($label_hrdp, 1);
        $data_hrdp = substr($data_hrdp, 1);

        $label_hrdp = "[" . str_replace('.', '', $label_hrdp) . "]";
        $data_hrdp = "[" . str_replace('.', '', $data_hrdp) . "]";

        //IT
        $label_it = "";
        $data_it = "";
        foreach ($datapie_it as $row) {
            $label_it .= "," . "'" . $row->kode . "'";
            $data_it .= "," . $row->nilai;
        }

        $label_it = substr($label_it, 1);
        $data_it = substr($data_it, 1);

        $label_it = "[" . str_replace('.', '', $label_it) . "]";
        $data_it = "[" . str_replace('.', '', $data_it) . "]";

        //legal
        $label_legal = "";
        $data_legal = "";
        foreach ($datapie_legal as $row) {
            $label_legal .= "," . "'" . $row->kode . "'";
            $data_legal .= "," . $row->nilai;
        }

        $label_legal = substr($label_legal, 1);
        $data_legal = substr($data_legal, 1);

        $label_legal = "[" . str_replace('.', '', $label_legal) . "]";
        $data_legal = "[" . str_replace('.', '', $data_legal) . "]";

        //marcomm
        $label_marcomm = "";
        $data_marcomm = "";
        foreach ($datapie_marcomm as $row) {
            $label_marcomm .= "," . "'" . $row->kode . "'";
            $data_marcomm .= "," . $row->nilai;
        }

        $label_marcomm = substr($label_marcomm, 1);
        $data_marcomm = substr($data_marcomm, 1);

        $label_marcomm = "[" . str_replace('.', '', $label_marcomm) . "]";
        $data_marcomm = "[" . str_replace('.', '', $data_marcomm) . "]";
        //marketing
        $label_marketing = "";
        $data_marketing = "";
        foreach ($datapie_marketing as $row) {
            $label_marketing .= "," . "'" . $row->kode . "'";
            $data_marketing .= "," . $row->nilai;
        }

        $label_marketing = substr($label_marketing, 1);
        $data_marketing = substr($data_marketing, 1);

        $label_marketing = "[" . str_replace('.', '', $label_marketing) . "]";
        $data_marketing = "[" . str_replace('.', '', $data_marketing) . "]";
        //opr
        $label_opr = "";
        $data_opr = "";
        foreach ($datapie_opr as $row) {
            $label_opr .= "," . "'" . $row->kode . "'";
            $data_opr .= "," . $row->nilai;
        }

        $label_opr = substr($label_opr, 1);
        $data_opr = substr($data_opr, 1);

        $label_opr = "[" . str_replace('.', '', $label_opr) . "]";
        $data_opr = "[" . str_replace('.', '', $data_opr) . "]";

        //overseas
        $label_overseas = "";
        $data_overseas = "";
        foreach ($datapie_overseas as $row) {
            $label_overseas .= "," . "'" . $row->kode . "'";
            $data_overseas .= "," . $row->nilai;
        }

        $label_overseas = substr($label_overseas, 1);
        $data_overseas = substr($data_overseas, 1);

        $label_overseas = "[" . str_replace('.', '', $label_overseas) . "]";
        $data_overseas = "[" . str_replace('.', '', $data_overseas) . "]";
        //scm
        $label_scm = "";
        $data_scm = "";
        foreach ($datapie_scm as $row) {
            $label_scm .= "," . "'" . $row->kode . "'";
            $data_scm .= "," . $row->nilai;
        }

        $label_scm = substr($label_scm, 1);
        $data_scm = substr($data_scm, 1);

        $label_scm = "[" . str_replace('.', '', $label_scm) . "]";
        $data_scm = "[" . str_replace('.', '', $data_scm) . "]";

        //scm
        //  $label_scmwh = "";
        //  $data_scmwh = "";
        //  foreach($datapie_scmwh as $row)
        //  {
        //      $label_scmwh .= ","."'".$row->kode."'";
        //      $data_scmwh .= ",".$row->nilai;
        //  }

        //  $label_scmwh = substr($label_scmwh, 1);
        //  $data_scmwh = substr($data_scmwh, 1);

        //  $label_scmwh = "[".str_replace('.', '', $label_scmwh)."]";
        //  $data_scmwh = "[".str_replace('.', '', $data_scmwh)."]";
        //tax
        $label_tax = "";
        $data_tax = "";
        foreach ($datapie_tax as $row) {
            $label_tax .= "," . "'" . $row->kode . "'";
            $data_tax .= "," . $row->nilai;
        }

        $label_tax = substr($label_tax, 1);
        $data_tax = substr($data_tax, 1);

        $label_tax = "[" . str_replace('.', '', $label_tax) . "]";
        $data_tax = "[" . str_replace('.', '', $data_tax) . "]";

        //vm
        $label_vm = "";
        $data_vm = "";
        foreach ($datapie_vm as $row) {
            $label_vm .= "," . "'" . $row->kode . "'";
            $data_vm .= "," . $row->nilai;
        }

        $label_vm = substr($label_vm, 1);
        $data_vm = substr($data_vm, 1);

        $label_vm = "[" . str_replace('.', '', $label_vm) . "]";
        $data_vm = "[" . str_replace('.', '', $data_vm) . "]";

        return view('budget.dashboard.index', compact(
            'nik',
            'name',
            'chart_label',
            'chart_data',
            'label_acc',
            'data_acc',
            'data_audit',
            'label_audit',
            'data_bdv',
            'label_bdv',
            'data_creative',
            'label_creative',
            'data_dgm',
            'label_dgm',
            'label_direksi',
            'data_direksi',
            'label_finance',
            'data_finance',
            'data_ga',
            'label_ga',
            'label_hrd',
            'data_hrd',
            'label_hrdp',
            'data_hrdp',
            'label_it',
            'data_it',
            'label_legal',
            'data_legal',
            'label_marcomm',
            'data_marcomm',
            'label_marketing',
            'data_marketing',
            'label_opr',
            'data_opr',
            'label_overseas',
            'data_overseas',
            'label_scm',
            'data_scm',
            'label_tax',
            'data_tax',
            'data_vm',
            'label_vm',
            'periode',
            'tahun',
            'group'
        ));
    }

    public function searchData($periode, $tahun)
    {

        //abort_unless(\Gate::allows('approval_document_access'), 403);
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen;
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group;

        if (\Gate::allows('budget_access_special')) {
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget  
            union select \'all\' as kode_groupstr order by kode_groupstr asc');
        } else {
            $temp_group = auth()->user()->kode_group;
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget where kode_group = \'' . $temp_group . '\') as x');
        }
        //query tahunan
        if ($periode == 'q1') {
            $query = "select budget_q1 as Budget, real_q1 as Realisasi, progress_q1 as Progress, sisa_q1 as Sisa from budget.dbo.v_report_dashboard";

            $queryams = "select sum(budget_q1) as Budget, sum(real_q1) as Realisasi, sum(progress_q1) as Progress, 
            sum(sisa_q1) as Sisa from budget.dbo.v_report_dashboard";
        } elseif ($periode == 'q2') {
            $query = "select budget_q2 as Budget, real_q2 as Realisasi, progress_q2 as Progress, sisa_q2 as Sisa from budget.dbo.v_report_dashboard";

            $queryams = "select sum(budget_q2) as Budget, sum(real_q2) as Realisasi, sum(progress_q2) as Progress, 
            sum(sisa_q2) as Sisa from budget.dbo.v_report_dashboard";
        } elseif ($periode == 'q3') {
            $query = "select budget_q3 as Budget, real_q3 as Realisasi, progress_q3 as Progress, sisa_q3 as Sisa from budget.dbo.v_report_dashboard";

            $queryams = "select sum(budget_q3) as Budget, sum(real_q3) as Realisasi, sum(progress_q3) as Progress, 
            sum(sisa_q3) as Sisa from budget.dbo.v_report_dashboard";
        } elseif ($periode == 'q4') {
            $query = "select budget_q4 as Budget, real_q4 as Realisasi, progress_q4 as Progress, sisa_q4 as Sisa from budget.dbo.v_report_dashboard";

            $queryams = "select sum(budget_q4) as Budget, sum(real_q4) as Realisasi, sum(progress_q4) as Progress, 
            sum(sisa_q4) as Sisa from budget.dbo.v_report_dashboard";
        } elseif ($periode == 'semester1') {
            $query = "select budget_q1 + budget_q2 as Budget, real_q1 + real_q2 as Realisasi, progress_q1 + progress_q2 as Progress, sisa_q1 + sisa_q2 as Sisa from budget.dbo.v_report_dashboard";
            $queryams = "select sum(budget_q1) + sum(budget_q2) as Budget, sum(real_q1) + sum(real_q2) as Realisasi, sum(progress_q1) + sum(progress_q2) as Progress, 
            sum(sisa_q1) + sum(sisa_q2) as Sisa from budget.dbo.v_report_dashboard";
        } elseif ($periode == 'semester2') {
            $query = "select budget_q3 + budget_q4 as Budget, real_q3 + real_q4 as Realisasi, progress_q3 + progress_q4 as Progress, sisa_q3 + sisa_q4 as Sisa from budget.dbo.v_report_dashboard";
            $queryams = "select sum(budget_q3) + sum(budget_q4) as Budget, sum(real_q3) + sum(real_q4) as Realisasi, sum(progress_q3) + sum(progress_q4) as Progress, 
            sum(sisa_q3) + sum(sisa_q4) as Sisa from budget.dbo.v_report_dashboard";
        } else {
            $kbudget = 'test';
            $kreal = 'test';
            $kprogress = 'test';
            $ksisa = 'test';
        }
        if ($periode == 'all') {
            //pie ams group
            $datapie_ams = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai 
                from budget.dbo.v_report_amsbudget
                unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
                ) unpvt where tahun = \'' . $tahun . '\'');
            //pie acc
            $datapie_acc = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'ACC\'');

            //pie audit
            $datapie_audit = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'AUDIT\'');

            //pie bdv
            $datapie_bdv = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'BDV\'');

            //pie creative
            $datapie_creative = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'CREATIVE\'');

            //pie dgm
            $datapie_dgm = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'DGM\'');

            //pie direksi
            $datapie_direksi = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'DIREKSI\'');

            //pie finance
            $datapie_finance = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'FINANCE\'');

            //pie GA
            $datapie_ga = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'GA\'');

            //pie HRD
            $datapie_hrd = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'HRD\'');

            //pie HRDP
            $datapie_hrdp = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'HRDP\'');

            //pie IT
            $datapie_it = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'IT\'');

            //pie legal
            $datapie_legal = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'LEGAL\'');

            //pie marcomm
            $datapie_marcomm = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'MARCOMM\'');

            //pie marketing
            $datapie_marketing = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'MARKETING\'');

            //pie opr
            $datapie_opr = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'OPR\'');

            //pie OVERSEAS
            $datapie_overseas = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'MDB\'');

            //pie scm
            $datapie_scm = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'SCM\'');

            //pie scmwh
            // $datapie_scmwh = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            // from budget.dbo.v_report_dashboard
            // unpivot (
            //     nilai for kode IN (Budget, Realisasi, Progress, Sisa
            //     )
            // ) unpvt where tahun = \''.$tahun.'\' and kode_group = \'SCMWH\'');

            //pie tax
            $datapie_tax = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'TAX\'');

            //pie vm
            $datapie_vm = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from budget.dbo.v_report_dashboard
            unpivot (
                nilai for kode IN (Budget, Realisasi, Progress, Sisa
                )
            ) unpvt where tahun = \'' . $tahun . '\' and kode_group = \'VM\'');
        } else {
            //pie ams group
            $datapie_ams = DB::select('
            select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from 
	        (' . $queryams . ' where tahun = \'' . $tahun . '\') as sumber
            unpivot (
	        nilai for kode IN (sumber.Budget, sumber.Realisasi, sumber.Progress, sumber.Sisa
	        )
            ) unpvt');

            //pie acc
            $datapie_acc = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from (' . $query . ' where tahun = \'' . $tahun . '\' and kode_group = \'ACC\') a
            unpivot (
                nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
                )
            ) unpvt');

            //pie AUDIT
            $datapie_audit = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from (' . $query . ' where tahun = \'' . $tahun . '\' and kode_group = \'AUDIT\') a
            unpivot (
                nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
                )
            ) unpvt');

            //pie BDV
            $datapie_bdv = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from (' . $query . ' where tahun = \'' . $tahun . '\' and kode_group = \'BDV\') a
            unpivot (
                nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
                )
            ) unpvt');
            //pie creative
            $datapie_creative = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from (' . $query . ' where tahun = \'' . $tahun . '\' and kode_group = \'CREATIVE\') a
            unpivot (
                nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
                )
            ) unpvt');
            //pie dgm
            $datapie_dgm = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from (' . $query . ' where tahun = \'' . $tahun . '\' and kode_group = \'DGM\') a
            unpivot (
                nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
                )
            ) unpvt');
            //pie direksi
            $datapie_direksi = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from (' . $query . ' where tahun = \'' . $tahun . '\' and kode_group = \'DIREKSI\') a
            unpivot (
                nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
                )
            ) unpvt');
            //pie finance
            $datapie_finance = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from (' . $query . ' where tahun = \'' . $tahun . '\' and kode_group = \'FINANCE\') a
            unpivot (
                nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
                )
            ) unpvt');
            //pie GA
            $datapie_ga = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from (' . $query . ' where tahun = \'' . $tahun . '\' and kode_group = \'GA\') a
            unpivot (
                nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
                )
            ) unpvt');
            //pie HRD
            $datapie_hrd = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from (' . $query . ' where tahun = \'' . $tahun . '\' and kode_group = \'HRD\') a
            unpivot (
                nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
                )
            ) unpvt');
            //pie HRDP
            $datapie_hrdp = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from (' . $query . ' where tahun = \'' . $tahun . '\' and kode_group = \'HRDP\') a
            unpivot (
                nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
                )
            ) unpvt');
            //pie IT
            $datapie_it = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from (' . $query . ' where tahun = \'' . $tahun . '\' and kode_group = \'IT\') a
            unpivot (
                nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
                )
            ) unpvt');
            //pie LEGAL
            $datapie_legal = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from (' . $query . ' where tahun = \'' . $tahun . '\' and kode_group = \'LEGAL\') a
            unpivot (
                nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
                )
            ) unpvt');
            //pie MARCOMM
            $datapie_marcomm = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from (' . $query . ' where tahun = \'' . $tahun . '\' and kode_group = \'MARCOMM\') a
            unpivot (
                nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
                )
            ) unpvt');
            //pie MARKETING
            $datapie_marketing = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from (' . $query . ' where tahun = \'' . $tahun . '\' and kode_group = \'MARKETING\') a
            unpivot (
                nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
                )
            ) unpvt');
            //pie OPR
            $datapie_opr = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from (' . $query . ' where tahun = \'' . $tahun . '\' and kode_group = \'OPR\') a
            unpivot (
                nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
                )
            ) unpvt');
            //pie OVERSEAS
            $datapie_overseas = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from (' . $query . ' where tahun = \'' . $tahun . '\' and kode_group = \'MDB\') a
            unpivot (
                nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
                )
            ) unpvt');
            //pie SCM
            $datapie_scm = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from (' . $query . ' where tahun = \'' . $tahun . '\' and kode_group = \'SCM\') a
            unpivot (
                nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
                )
            ) unpvt');
            //pie SCMWH
            // $datapie_scmwh = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            // from ('.$query.' where tahun = \''.$tahun.'\' and kode_group = \'SCMWH\') a
            // unpivot (
            //     nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
            //     )
            // ) unpvt'); 
            //pie TAX
            $datapie_tax = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from (' . $query . ' where tahun = \'' . $tahun . '\' and kode_group = \'TAX\') a
            unpivot (
                nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
                )
            ) unpvt');
            //pie VM
            $datapie_vm = DB::select('select kode, CAST(nilai AS DECIMAL(18,0)) as nilai
            from (' . $query . ' where tahun = \'' . $tahun . '\' and kode_group = \'VM\') a
            unpivot (
                nilai for kode IN (a.Budget, a.Realisasi, a.Progress, a.Sisa
                )
            ) unpvt');
        }


        $chart_label = "";
        $chart_data = "";
        foreach ($datapie_ams as $row) {
            $chart_label .= "," . "'" . $row->kode . "'";
            $chart_data .= "," . $row->nilai;
        }

        $chart_label = substr($chart_label, 1);
        $chart_data = substr($chart_data, 1);

        $chart_label = "[" . str_replace('.', '', $chart_label) . "]";
        $chart_data = "[" . str_replace('.', '', $chart_data) . "]";
        //acc
        $label_acc = "";
        $data_acc = "";
        foreach ($datapie_acc as $row) {
            $label_acc .= "," . "'" . $row->kode . "'";
            $data_acc .= "," . $row->nilai;
        }

        $label_acc = substr($label_acc, 1);
        $data_acc = substr($data_acc, 1);

        $label_acc = "[" . str_replace('.', '', $label_acc) . "]";
        $data_acc = "[" . str_replace('.', '', $data_acc) . "]";

        //Audit
        $label_audit = "";
        $data_audit = "";
        foreach ($datapie_audit as $row) {
            $label_audit .= "," . "'" . $row->kode . "'";
            $data_audit .= "," . $row->nilai;
        }

        $label_audit = substr($label_audit, 1);
        $data_audit = substr($data_audit, 1);

        $label_audit = "[" . str_replace('.', '', $label_audit) . "]";
        $data_audit = "[" . str_replace('.', '', $data_audit) . "]";

        //bdv
        $label_bdv = "";
        $data_bdv = "";
        foreach ($datapie_bdv as $row) {
            $label_bdv .= "," . "'" . $row->kode . "'";
            $data_bdv .= "," . $row->nilai;
        }

        $label_bdv = substr($label_bdv, 1);
        $data_bdv = substr($data_bdv, 1);

        $label_bdv = "[" . str_replace('.', '', $label_bdv) . "]";
        $data_bdv = "[" . str_replace('.', '', $data_bdv) . "]";

        //creative
        $label_creative = "";
        $data_creative = "";
        foreach ($datapie_creative as $row) {
            $label_creative .= "," . "'" . $row->kode . "'";
            $data_creative .= "," . $row->nilai;
        }

        $label_creative = substr($label_creative, 1);
        $data_creative = substr($data_creative, 1);

        $label_creative = "[" . str_replace('.', '', $label_creative) . "]";
        $data_creative = "[" . str_replace('.', '', $data_creative) . "]";

        //dgm
        $label_dgm = "";
        $data_dgm = "";
        foreach ($datapie_dgm as $row) {
            $label_dgm .= "," . "'" . $row->kode . "'";
            $data_dgm .= "," . $row->nilai;
        }

        $label_dgm = substr($label_dgm, 1);
        $data_dgm = substr($data_dgm, 1);

        $label_dgm = "[" . str_replace('.', '', $label_dgm) . "]";
        $data_dgm = "[" . str_replace('.', '', $data_dgm) . "]";

        //direksi
        $label_direksi = "";
        $data_direksi = "";
        foreach ($datapie_direksi as $row) {
            $label_direksi .= "," . "'" . $row->kode . "'";
            $data_direksi .= "," . $row->nilai;
        }

        $label_direksi = substr($label_direksi, 1);
        $data_direksi = substr($data_direksi, 1);

        $label_direksi = "[" . str_replace('.', '', $label_direksi) . "]";
        $data_direksi = "[" . str_replace('.', '', $data_direksi) . "]";

        //finance
        $label_finance = "";
        $data_finance = "";
        foreach ($datapie_finance as $row) {
            $label_finance .= "," . "'" . $row->kode . "'";
            $data_finance .= "," . $row->nilai;
        }

        $label_finance = substr($label_finance, 1);
        $data_finance = substr($data_finance, 1);

        $label_finance = "[" . str_replace('.', '', $label_finance) . "]";
        $data_finance = "[" . str_replace('.', '', $data_finance) . "]";

        //GA
        $label_ga = "";
        $data_ga = "";
        foreach ($datapie_ga as $row) {
            $label_ga .= "," . "'" . $row->kode . "'";
            $data_ga .= "," . $row->nilai;
        }

        $label_ga = substr($label_ga, 1);
        $data_ga = substr($data_ga, 1);

        $label_ga = "[" . str_replace('.', '', $label_ga) . "]";
        $data_ga = "[" . str_replace('.', '', $data_ga) . "]";

        //hrd
        $label_hrd = "";
        $data_hrd = "";
        foreach ($datapie_hrd as $row) {
            $label_hrd .= "," . "'" . $row->kode . "'";
            $data_hrd .= "," . $row->nilai;
        }

        $label_hrd = substr($label_hrd, 1);
        $data_hrd = substr($data_hrd, 1);

        $label_hrd = "[" . str_replace('.', '', $label_hrd) . "]";
        $data_hrd = "[" . str_replace('.', '', $data_hrd) . "]";

        //hrdp
        $label_hrdp = "";
        $data_hrdp = "";
        foreach ($datapie_hrdp as $row) {
            $label_hrdp .= "," . "'" . $row->kode . "'";
            $data_hrdp .= "," . $row->nilai;
        }

        $label_hrdp = substr($label_hrdp, 1);
        $data_hrdp = substr($data_hrdp, 1);

        $label_hrdp = "[" . str_replace('.', '', $label_hrdp) . "]";
        $data_hrdp = "[" . str_replace('.', '', $data_hrdp) . "]";

        //IT
        $label_it = "";
        $data_it = "";
        foreach ($datapie_it as $row) {
            $label_it .= "," . "'" . $row->kode . "'";
            $data_it .= "," . $row->nilai;
        }

        $label_it = substr($label_it, 1);
        $data_it = substr($data_it, 1);

        $label_it = "[" . str_replace('.', '', $label_it) . "]";
        $data_it = "[" . str_replace('.', '', $data_it) . "]";

        //legal
        $label_legal = "";
        $data_legal = "";
        foreach ($datapie_legal as $row) {
            $label_legal .= "," . "'" . $row->kode . "'";
            $data_legal .= "," . $row->nilai;
        }

        $label_legal = substr($label_legal, 1);
        $data_legal = substr($data_legal, 1);

        $label_legal = "[" . str_replace('.', '', $label_legal) . "]";
        $data_legal = "[" . str_replace('.', '', $data_legal) . "]";

        //marcomm
        $label_marcomm = "";
        $data_marcomm = "";
        foreach ($datapie_marcomm as $row) {
            $label_marcomm .= "," . "'" . $row->kode . "'";
            $data_marcomm .= "," . $row->nilai;
        }

        $label_marcomm = substr($label_marcomm, 1);
        $data_marcomm = substr($data_marcomm, 1);

        $label_marcomm = "[" . str_replace('.', '', $label_marcomm) . "]";
        $data_marcomm = "[" . str_replace('.', '', $data_marcomm) . "]";
        //marketing
        $label_marketing = "";
        $data_marketing = "";
        foreach ($datapie_marketing as $row) {
            $label_marketing .= "," . "'" . $row->kode . "'";
            $data_marketing .= "," . $row->nilai;
        }

        $label_marketing = substr($label_marketing, 1);
        $data_marketing = substr($data_marketing, 1);

        $label_marketing = "[" . str_replace('.', '', $label_marketing) . "]";
        $data_marketing = "[" . str_replace('.', '', $data_marketing) . "]";
        //opr
        $label_opr = "";
        $data_opr = "";
        foreach ($datapie_opr as $row) {
            $label_opr .= "," . "'" . $row->kode . "'";
            $data_opr .= "," . $row->nilai;
        }

        $label_opr = substr($label_opr, 1);
        $data_opr = substr($data_opr, 1);

        $label_opr = "[" . str_replace('.', '', $label_opr) . "]";
        $data_opr = "[" . str_replace('.', '', $data_opr) . "]";

        //overseas
        $label_overseas = "";
        $data_overseas = "";
        foreach ($datapie_overseas as $row) {
            $label_overseas .= "," . "'" . $row->kode . "'";
            $data_overseas .= "," . $row->nilai;
        }

        $label_overseas = substr($label_overseas, 1);
        $data_overseas = substr($data_overseas, 1);

        $label_overseas = "[" . str_replace('.', '', $label_overseas) . "]";
        $data_overseas = "[" . str_replace('.', '', $data_overseas) . "]";
        //scm
        $label_scm = "";
        $data_scm = "";
        foreach ($datapie_scm as $row) {
            $label_scm .= "," . "'" . $row->kode . "'";
            $data_scm .= "," . $row->nilai;
        }

        $label_scm = substr($label_scm, 1);
        $data_scm = substr($data_scm, 1);

        $label_scm = "[" . str_replace('.', '', $label_scm) . "]";
        $data_scm = "[" . str_replace('.', '', $data_scm) . "]";

        //scm
        // $label_scmwh = "";
        // $data_scmwh = "";
        // foreach($datapie_scmwh as $row)
        // {
        //     $label_scmwh .= ","."'".$row->kode."'";
        //     $data_scmwh .= ",".$row->nilai;
        // }

        // $label_scmwh = substr($label_scmwh, 1);
        // $data_scmwh = substr($data_scmwh, 1);

        // $label_scmwh = "[".str_replace('.', '', $label_scmwh)."]";
        // $data_scmwh = "[".str_replace('.', '', $data_scmwh)."]";
        //tax
        $label_tax = "";
        $data_tax = "";
        foreach ($datapie_tax as $row) {
            $label_tax .= "," . "'" . $row->kode . "'";
            $data_tax .= "," . $row->nilai;
        }

        $label_tax = substr($label_tax, 1);
        $data_tax = substr($data_tax, 1);

        $label_tax = "[" . str_replace('.', '', $label_tax) . "]";
        $data_tax = "[" . str_replace('.', '', $data_tax) . "]";

        //vm
        $label_vm = "";
        $data_vm = "";
        foreach ($datapie_vm as $row) {
            $label_vm .= "," . "'" . $row->kode . "'";
            $data_vm .= "," . $row->nilai;
        }

        $label_vm = substr($label_vm, 1);
        $data_vm = substr($data_vm, 1);

        $label_vm = "[" . str_replace('.', '', $label_vm) . "]";
        $data_vm = "[" . str_replace('.', '', $data_vm) . "]";

        return view('budget.dashboard.index', compact(
            'nik',
            'name',
            'chart_label',
            'chart_data',
            'label_acc',
            'data_acc',
            'data_audit',
            'label_audit',
            'data_bdv',
            'label_bdv',
            'data_creative',
            'label_creative',
            'data_dgm',
            'label_dgm',
            'label_direksi',
            'data_direksi',
            'label_finance',
            'data_finance',
            'data_ga',
            'label_ga',
            'label_hrd',
            'data_hrd',
            'label_hrdp',
            'data_hrdp',
            'label_it',
            'data_it',
            'label_legal',
            'data_legal',
            'label_marcomm',
            'data_marcomm',
            'label_marketing',
            'data_marketing',
            'label_opr',
            'data_opr',
            'label_overseas',
            'data_overseas',
            'label_scm',
            'data_scm',
            'label_tax',
            'data_tax',
            'data_vm',
            'label_vm',
            'periode',
            'tahun',
            'group'
        ));
    }
}
