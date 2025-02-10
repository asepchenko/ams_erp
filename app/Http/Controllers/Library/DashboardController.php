<?php

namespace App\Http\Controllers\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        abort_unless(\Gate::allows('library_dashboard_access'), 403);
        $bulan = date('m');
        $temp = DB::select('
                select isnull(count(A.id),0) as jum
                from approval.dbo.document_digital A
                join approval.dbo.document_master B on A.document_id=B.id
                left join finance.dbo.realisasi_document C on A.id=c.document_digital_id
                where B.last_status=\'proses_payment\' 
                and month(A.tanggal_bayar)=\''.$bulan.'\' 
                and isnull(C.status_realisasi,\'0\')=0');
        $data = $temp[0];
        return view('library.dashboard.index',compact('data'));
    }
}
