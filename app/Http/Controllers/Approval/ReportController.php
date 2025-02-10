<?php

namespace App\Http\Controllers\Approval;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator, Redirect, Response, File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(\Gate::allows('approval_report_document'), 403);
        $bulan = date('m');
        $status_pilih = "";
        $dept_pilih = "";
        $start_date = "";
        $end_date = "";
        $type_tgl = "";
        $nama = "";
        $status = DB::select('select nama_status FROM approval.dbo.status_list ORDER BY id');
        if (\Gate::allows('approval_file_special_access')) {
            $dept = DB::select('select distinct kode_departemen as kodedepartemenstr from yud_test.dbo.users 
            union select \'all\' as kodedepartemenstr order by kode_departemen asc');
        } else {
            $temp_dept = auth()->user()->kode_departemen;
            $dept = DB::select('select x.kodedepartemenstr from (select distinct kode_departemen as kodedepartemenstr from yud_test.dbo.users where kode_departemen = \'' . $temp_dept . '\') as x');
            // $dept = DB::select('select kodedepartemenstr FROM hris.dbo.tbldepartemen where KodeDepartemenStr LIKE \'%'.$temp_dept.'%\'');
        }

        return view('approval.report.index', compact('start_date', 'end_date', 'type_tgl', 'nama', 'bulan', 'status', 'dept', 'status_pilih', 'dept_pilih'));
    }

    public function searchData($start_date, $end_date, $status, $dept, $nama, $typetgl)
    {
        abort_unless(\Gate::allows('approval_report_document'), 403);

        // $bulan = (!empty($bulan)) ? ($bulan):('01');
        $start_date = $start_date;
        $end_date = $end_date;
        $status_pilih = (!empty($status)) ? ($status) : ('all');
        $dept_pilih = (!empty($dept)) ? ($dept) : ('all');
        $type_tgl = $typetgl;
        $startdate = (!empty($start_date)) ? (date("Y-m-d", strtotime($start_date))) : ('');
        $enddate = (!empty($end_date)) ? (date("Y-m-d", strtotime($end_date))) : ('');
        $where = " where 1=1 ";
        $nama = (!empty($nama)) ? ($nama) : ('all');

        //$where .= " and bulan=".$bulan."";
        if ($startdate and $enddate != '') {
            $where .= " and format(" . $typetgl . ", 'yyyy-MM-dd') >='" . $startdate . "' and format(" . $typetgl . ", 'yyyy-MM-dd') <='" . $enddate . "'";
        } else {
            $where .= " and datediff(month," . $typetgl . ",getdate) = 0";
        }
        if ($dept_pilih == 'ANALIS-DC') {
            $where .= "and A.kode_departemen in ('ANALIS-DC','IC')";
        } elseif ($dept_pilih == 'MDFOB') {
            $where .= "and A.kode_departemen in ('MDFOB','MDP')";
        } elseif ($dept_pilih == "all") {
            $where .= "";
        } else {
            $where .= " and LEFT(A.kode_departemen,3) = LEFT('" . $dept_pilih . "',3) ";
        }

        if ($status_pilih != "all") {
            $where .= " and A.last_status='" . $status_pilih . "'";
        }

        if ($nama != "all") {
            $where .= " and A.nama='" . $nama . "'";
        }

        if (\Gate::allows('approval_file_special_access')) {
            $where .= " ";
        } elseif ($dept_pilih == 'MDR') {
            $where .= " and A.document_priority_id in (1,2,11) ";
        } else {
            $where .= " and A.document_priority_id in (1,2) ";
        }

        $query = "select * from approval.dbo.v_report_document_new A " . $where . " and document_type <> 'kbr' order by A.tanggal_status desc";
        //dd($query);
        $datanya = DB::select($query);

        $status = DB::select('select nama_status FROM approval.dbo.status_list ORDER BY id');

        if (\Gate::allows('approval_file_special_access')) {
            $dept = DB::select('select distinct kode_departemen as kodedepartemenstr from yud_test.dbo.users 
            union select \'all\' as kodedepartemenstr order by kode_departemen asc');
            // $dept = DB::select('select kodedepartemenstr FROM hris.dbo.tbldepartemen 
            // where KodeDepartemenStr not in(\'\',\'* none\') 
            // union select \'all\' as kodedepartemenstr
            // ORDER BY kodedepartemenstr');
        } else {
            $temp_dept = auth()->user()->kode_departemen;
            $dept = DB::select('select x.kodedepartemenstr from (select distinct kode_departemen as kodedepartemenstr from yud_test.dbo.users where kode_departemen = \'' . $temp_dept . '\') as x');
            // $dept = DB::select('select kodedepartemenstr FROM hris.dbo.tbldepartemen where KodeDepartemenStr LIKE \'%'.$temp_dept.'%\'');
        }
        return view('approval.report.index', compact('start_date', 'end_date', 'type_tgl', 'nama', 'datanya', 'status', 'dept', 'status_pilih', 'dept_pilih'));
    }

    public function printData($id)
    {
        $doc = DB::select('select id from approval.dbo.v_document where no_document = \'' . $id . '\'');
        $docid = $doc[0]->id;
        $data_digital = DB::select('select A.id, A.document_id, A.mata_uang, A.kode_category, A.no_digital,
        replace(replace(C.nama_category,\'_\',\' \'),\'merah\',\'Pengeluaran\') as nama_category, 
        format(B.created_at,\'dd-MMM-yyyy\') as tanggal, 
        format(A.tanggal_bayar,\'dd-MMM-yyyy\') as tanggal_bayar,
        A.nama_tujuan,  replace(A.kode_bank,\'null\',\'\') as bank, A.nama_rek,
        A.rek_tujuan, REPLACE(FORMAT(A.jumlah, \'N\', \'en-us\'), \'.00\', \'\') as jumlah, A.jumlah as jum, A.no_ref, 
        A.keterangan, A.created_at, A.created_by, B.last_status, 
        case when A.is_pu =\'1\' then \'checked\' else \'unchecked\' end as pu
        from approval.dbo.document_digital A
        join approval.dbo.document_master B on A.document_id=B.id
        join approval.dbo.category C on A.kode_category=C.kode_category
        where A.document_id=\'' . $docid . '\' order by A.kode_category desc');

        $temp_pr = DB::select('select document_priority_id as prioritas, kode_departemen as dept from approval.dbo.document_master 
        where id=\'' . $docid . '\'');
        $prioritas = $temp_pr[0]->prioritas;
        $dept = $temp_pr[0]->dept;

        if ($dept == 'PRD' or $dept == 'KON') {
            //(select concat(departemen,\' - \',jabatan) from users z where z.nik=a.updated_by) as jabatan, a.signature
            $data_ttd = DB::select('select acc.* from (
            select top 1 a.id, a.status,format(a.updated_at,\'dd MMM yyyy \') as tgl,
                        (select name from users z where z.nik=a.updated_by) as nama , 
                        (select departemen from users z where z.nik=a.updated_by) as departemen, a.signature
                        from approval.dbo.document_status a where a.document_id=\'' . $docid . '\'  and a.status = \'open\'
            union all
            select a.id, a.status,format(a.updated_at,\'dd MMM yyyy \') as tgl,
                        (select name from users z where z.nik=a.updated_by) as nama , 
                        (select departemen from users z where z.nik=a.updated_by) as departemen, a.signature
                        from approval.dbo.document_status a where a.document_id=\'' . $docid . '\' 
                        and a.status not in(\'cancel\',\'open\',\'approval_manager\') and a.alasan is null and a.signature is not null)
            as acc order by acc.id asc');
        } else {
            //(select concat(departemen,\' - \',jabatan) from users z where z.nik=a.updated_by) as jabatan, a.signature
            $data_ttd = DB::select('select acc.* from (
            select top 1 a.id, a.status,format(a.updated_at,\'dd MMM yyyy \') as tgl,
                        (select name from users z where z.nik=a.updated_by) as nama , 
                        (select departemen from users z where z.nik=a.updated_by) as departemen, a.signature
                        from approval.dbo.document_status a where a.document_id=\'' . $docid . '\'  and a.status = \'open\'
            union all
            select a.id, a.status,format(a.updated_at,\'dd MMM yyyy \') as tgl,
                        (select name from users z where z.nik=a.updated_by) as nama , 
                        (select departemen from users z where z.nik=a.updated_by) as departemen, a.signature
                        from approval.dbo.document_status a where a.document_id=\'' . $docid . '\' 
                        and a.status not in(\'cancel\',\'open\') and a.alasan is null and a.signature is not null)
            as acc order by acc.id asc');
        }

        $tgl = now();

        $temp = DB::select('select a.id, a.nik, a.nama, 
        case when a.no_document is null then a.id 
        else a.no_document end as no_document, a.kode_departemen, a.keterangan, a.last_status, a.created_at,
        a.created_by, a.updated_by, (select name from users z where z.nik=a.updated_by) as nama_update
        from approval.dbo.v_document a where a.no_document=\'' . $id . '\'');
        $data = $temp[0];
        //dd($data->nama_update);

        $data_file = DB::select('select A.id, A.document_id, A.category_name, A.nama_file, A.keterangan, 
        A.created_at, A.created_by, B.last_status
        from approval.dbo.document_file A
        join approval.dbo.document_master B on A.document_id=B.id
        where A.document_id=\'' . $docid . '\' order by A.category_name desc');

        $temp_bayar = DB::select('select top(1) format(z.tanggal_realisasi,\'dd-MMM-yyyy\') as tgl_realisasi
        from finance.dbo.realisasi_document z where z.document_id=\'' . $docid . '\'');

        if (count($temp_bayar) > 0) {
            $bayar = $temp_bayar[0];
        } else {
            $bayar = "-";
        }
        if (auth()->user()->kode_departemen == 'FIN') {
            //cek dulu udh pernah diprint sama finance apa belum
            $cek = DB::select('select printfinance from approval.dbo.document_master where id=\'' . $docid . '\'');
            if ($cek[0]->printfinance == 0) {
                DB::update('update approval.dbo.document_master set printfinance = 1, printby =\'' . auth()->user()->nik . '\', printdate = getdate() where id=\'' . $docid . '\'');
                return view('approval.report.print', compact('data_digital', 'data_ttd', 'data', 'tgl', 'data_file', 'bayar', 'prioritas'));
            } else {
                return view('approval.report.printcopy', compact('data_digital', 'data_ttd', 'data', 'tgl', 'data_file', 'bayar', 'prioritas'));
            }
        } else if (auth()->user()->kode_departemen == 'TAX') {
            return view('approval.report.print', compact('data_digital', 'data_ttd', 'data', 'tgl', 'data_file', 'bayar', 'prioritas'));
        } else {
            return view('approval.report.printcopy', compact('data_digital', 'data_ttd', 'data', 'tgl', 'data_file', 'bayar', 'prioritas'));
        }
    }
}
