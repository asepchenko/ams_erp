<?php

namespace App\Http\Controllers\Approval;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator, Redirect, Response, File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;

class OutstandingReportController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(\Gate::allows('approval_report_document'), 403);
        $status_pilih = "";
        $dept_pilih = "";
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

        return view('approval.outstanding-report.index', compact('dept', 'status_pilih', 'dept_pilih'));
    }

    public function searchData($key_tgl, $start_date, $end_date, $dept, $status)
    {
        abort_unless(\Gate::allows('approval_report_document'), 403);
        $start_date = (!empty($start_date)) ? (date("Y-m-d", strtotime($start_date))) : ('');
        $end_date = (!empty($end_date)) ? (date("Y-m-d", strtotime($end_date))) : ('');
        $key = (!empty($key_tgl)) ? $key_tgl : ('');
        $dept_pilih = (!empty($dept)) ? ($dept) : ('all');
        $where = " where 1=1";
        $status_pilih = (!empty($status)) ? ($status) : ('all');
        //dd($status_pilih);
        if ($start_date and $end_date != '') {
            $where .= ' and format(' . $key . ', \'yyyy-MM-dd\') >=\'' . $start_date . '\' and format(' . $key . ', \'yyyy-MM-dd\') <=\'' . $end_date . '\'';
        }
        if ($dept_pilih == 'ANALIS-DC') {
            $where .= "and kode_departemen in ('ANALIS-DC','IC')";
        } elseif ($dept_pilih == 'MDFOB') {
            $where .= "and kode_departemen in ('MDFOB','MDP')";
        } elseif ($dept_pilih == "all") {
            $where .= "";
        } else {
            $where .= " and kode_departemen = '" . $dept_pilih . "'";
        }
        // if ($dept_pilih != "all") {
        //     $where .= " and kode_departemen='" . $dept_pilih . "' ";
        // }

        if ($status_pilih != "all") {
            $where .= " and status='" . $status_pilih . "'";
        }

        $datanya = DB::select('select * from approval.dbo.v_report_outstanding ' . $where . ' order by created_at desc');

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
        return view('approval.outstanding-report.index', compact('datanya', 'dept', 'status_pilih', 'dept_pilih'));
    }
    public function printData($id)
    {
        //$id adalah document kbr
        //dapetin dlu $id_kb, $id_kbt
        $doc = DB::select('select document_kb, isnull(document_kbt,0) as document_kbt from approval.dbo.log_kasbon_realisasi where document_kbr =\'' . $id . '\'');
        $id_kb = $doc[0]->document_kb;
        $id_kbt = $doc[0]->document_kbt;

        $data_digital = DB::select('select A.id, A.document_id, A.mata_uang, A.kode_category, A.no_digital,
        replace(replace(C.nama_category,\'_\',\' \'),\'merah\',\'Pengeluaran\') as nama_category, 
        format(B.created_at,\'dd-MMM-yyyy\') as tanggal, 
        format(A.tanggal_bayar,\'dd-MMM-yyyy\') as tanggal_bayar,
        A.nama_tujuan,  replace(A.kode_bank,\'null\',\'\') as bank, A.nama_rek,
        A.rek_tujuan, REPLACE(FORMAT(A.jumlah, \'N\', \'en-us\'), \'.00\', \'\') as jumlahb,
        REPLACE(FORMAT(D.jumlah_realisasi,\'N\', \'en-us\'),\'.00\',\'\') as jumlah, D.jumlah_realisasi as jum, A.no_ref, 
        A.keterangan, A.created_at, A.created_by, B.last_status, 
        case when A.is_pu =\'1\' then \'checked\' else \'unchecked\' end as pu
        from approval.dbo.document_digital A
        join approval.dbo.document_master B on A.document_id=B.id
        join approval.dbo.category C on A.kode_category=C.kode_category
        join approval.dbo.log_kasbon_realisasi D on A.document_id = D.document_kbr
        where A.document_id=\'' . $id . '\' order by A.kode_category desc');

        $data_digitalkb = DB::select('select A.id, A.document_id, A.mata_uang, A.kode_category, A.no_digital,
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
        where A.document_id=\'' . $id_kb . '\' order by A.kode_category desc');

        if ($id_kbt != "0") {
            $data_digitalkbt = DB::select('select A.id, A.document_id, A.mata_uang, A.kode_category, A.no_digital,
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
            where A.document_id=\'' . $id_kbt . '\' order by A.kode_category desc');

            //temp data kbt
            $tempkbt = DB::select('select isnull(id,0) as id, nik, nama, kode_departemen, keterangan, last_status, created_at, created_by
            from approval.dbo.document_master where id=\'' . $id_kbt . '\'');
            $datakbt = $tempkbt[0];

            $temp_bayarkbt = DB::select('select distinct format(z.tanggal_realisasi,\'dd-MMM-yyyy\') as tgl_realisasi
            from finance.dbo.realisasi_document z where z.document_id=\'' . $id_kbt . '\'');

            if (count($temp_bayarkbt) > 0) {
                $bayarkbt = $temp_bayarkbt[0];
            } else {
                $bayarkbt = "-";
            }
        } else {
            $data_digitalkbt = "-";
            $bayarkbt = "-";
            $datakbt = "-";
        }

        $temp_pr = DB::select('select document_priority_id as prioritas from approval.dbo.document_master 
        where id=\'' . $id . '\'');
        $prioritas = $temp_pr[0]->prioritas;

        // $data_ttd = DB::select('select a.status,format(a.updated_at,\'dd MMM yyyy\') as tgl,
        // (select name from users z where z.nik=a.updated_by) as nama , 
        // (select departemen from users z where z.nik=a.updated_by) as departemen, a.signature
        // from approval.dbo.document_status a where a.document_id=\''.$id.'\' 
        // and a.status not in(\'cancel\') and a.alasan is null and a.signature is not null');

        $data_ttd = DB::select('select a.status,format(a.updated_at,\'dd MMM yyyy\') as tgl,
            (select name from users z where z.nik=a.updated_by) as nama , 
            (select departemen from users z where z.nik=a.updated_by) as departemen, a.signature
            from approval.dbo.document_status a where a.document_id=\'' . $id . '\' 
            and a.status not in(\'cancel\') and a.alasan is null and a.signature is not null order by a.id asc');

        $tgl = now();

        //temp data kbr
        $temp = DB::select('select id, case when no_document is null then id else no_document end as no_document, nik, nama, kode_departemen, keterangan, last_status, created_at, created_by
        from approval.dbo.document_master where id=\'' . $id . '\'');
        $data = $temp[0];
        //temp data kb
        $tempkb = DB::select('select id, nik, nama, kode_departemen, keterangan, last_status, created_at, created_by
        from approval.dbo.document_master where id=\'' . $id_kb . '\'');
        $datakb = $tempkb[0];

        $data_file = DB::select('select A.id, A.document_id, A.category_name, A.nama_file, A.keterangan, 
        A.created_at, A.created_by, B.last_status
        from approval.dbo.document_file A
        join approval.dbo.document_master B on A.document_id=B.id
        where A.document_id=\'' . $id . '\' order by A.category_name desc');

        $temp_bayar = DB::select('select format(z.created_at,\'dd-MMM-yyyy\') as tgl_realisasi
        from approval.dbo.document_status z where z.status = \'closed\' and z.document_id =\'' . $id . '\'');

        if (count($temp_bayar) > 0) {
            $bayar = $temp_bayar[0];
        } else {
            $bayar = "-";
        }

        $temp_bayarkb = DB::select('select distinct format(z.tanggal_realisasi,\'dd-MMM-yyyy\') as tgl_realisasi
        from finance.dbo.realisasi_document z where z.document_id=\'' . $id_kb . '\'');

        if (count($temp_bayarkb) > 0) {
            $bayarkb = $temp_bayarkb[0];
        } else {
            $bayarkb = "-";
        }

        return view('approval.outstanding-report.print', compact('bayarkb', 'bayarkbt', 'data_digital', 'data_digitalkb', 'data_digitalkbt', 'data_ttd', 'data', 'datakb', 'datakbt', 'tgl', 'data_file', 'bayar', 'prioritas'));
    }
}
