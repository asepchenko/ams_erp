<?php

namespace App\Http\Controllers\Approval;
use Illuminate\Http\Request;
use DateTime;
use DateInterval;
use DatePeriod;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        abort_unless(\Gate::allows('approval_dashboard_access'), 403);
        $today = date('d-M-Y');
        $bulan = date('m');
        $tahun = date('Y');
        $dept = auth()->user()->kode_departemen;
        $data_document = DB::select('select A.current_dept,
        (select x.departemen from 
			(select distinct departemen from yud_test.dbo.users z 
        where left(z.kode_departemen,3)=left(A.current_dept,3)) as x) as nama_dept,
        count(id) as jum, 
        concat(\'report/search/\',\'01 January 2020\',\'/\',format(getdate(),\'dd MMMM yyyy\'),\'/approval_\', lower(A.current_dept),\'/all/all/tglbuat\') as link
        from approval.dbo.document_master A
        where A.last_status not in(\'open\',\'approval_manager\',\'cancel\',\'closed\',\'proses_payment\') and A.current_dept <> \'-\' 
        and A.document_type <> \'kbr\'
        group by A.current_dept');
        // $data_document = DB::select('select A.current_dept,
        // (select x.departemen from 
		// 	(select distinct departemen from yud_test.dbo.users z 
        // where left(z.kode_departemen,3)=left(A.current_dept,3)) as x) as nama_dept,
        // count(id) as jum, 
        // concat(\'report/search/\',\'01 January \',format(getdate(),\'yyyy\'),\'/\',format(getdate(),\'dd MMMM yyyy\'),\'/approval_\', lower(A.current_dept),\'/all/all/tglbuat\') as link
        // from approval.dbo.document_master A
        // where A.last_status not in(\'open\',\'approval_manager\',\'cancel\',\'closed\',\'proses_payment\') and A.current_dept <> \'-\' 
        // and A.document_type <> \'kbr\' and datediff(year,a.created_at,getdate()) = 0
        // group by A.current_dept');

        $data_document_open = DB::select('select A.current_dept,
        (select x.departemen from 
			(select distinct departemen from yud_test.dbo.users z 
        where left(z.kode_departemen,3)=left(A.current_dept,3)) as x) as nama_dept,
        count(id) as jum, 
        concat(\'report/search/\',\'01 January 2020\',\'/\',format(getdate(),\'dd MMMM yyyy\'),\'/open/\', upper(A.current_dept), \'/all/tglbuat\') as link
        from approval.dbo.document_master A
        where A.last_status in(\'open\') and A.current_dept <> \'-\' 
        and A.document_type <> \'kbr\'
        group by A.current_dept');

        // $data_document_open = DB::select('select A.current_dept,
        // (select x.departemen from 
		// 	(select distinct departemen from yud_test.dbo.users z 
        // where left(z.kode_departemen,3)=left(A.current_dept,3)) as x) as nama_dept,
        // count(id) as jum, 
        // concat(\'report/search/\',\'01 January \',format(getdate(),\'yyyy\'),\'/\',format(getdate(),\'dd MMMM yyyy\'),\'/open/\', upper(A.current_dept), \'/all/tglbuat\') as link
        // from approval.dbo.document_master A
        // where A.last_status in(\'open\') and A.current_dept <> \'-\' 
        // and A.document_type <> \'kbr\' and datediff(year,a.created_at,getdate()) = 0
        // group by A.current_dept');

        $data_document_man = DB::select('select A.current_dept,
        (select x.departemen from 
			(select distinct departemen from yud_test.dbo.users z 
        where left(z.kode_departemen,3)=left(A.current_dept,3)) as x) as nama_dept,
        count(id) as jum, 
        concat(\'report/search/\',\'01 January 2020\',\'/\',format(getdate(),\'dd MMMM yyyy\'),\'/approval_manager/\', upper(A.current_dept), \'/all/tglbuat\') as link
        from approval.dbo.document_master A
        where A.last_status in(\'approval_manager\') and A.current_dept <> \'-\' 
        and A.document_type <> \'kbr\' 
        group by A.current_dept');

        // $data_document_man = DB::select('select A.current_dept,
        // (select x.departemen from 
		// 	(select distinct departemen from yud_test.dbo.users z 
        // where left(z.kode_departemen,3)=left(A.current_dept,3)) as x) as nama_dept,
        // count(id) as jum, 
        // concat(\'report/search/\',\'01 January \',format(getdate(),\'yyyy\'),\'/\',format(getdate(),\'dd MMMM yyyy\'),\'/approval_manager/\', upper(A.current_dept), \'/all/tglbuat\') as link
        // from approval.dbo.document_master A
        // where A.last_status in(\'approval_manager\') and A.current_dept <> \'-\' 
        // and A.document_type <> \'kbr\' and datediff(year,a.created_at,getdate()) = 0
        // group by A.current_dept');

        $data_graph = DB::select('
        select format(A.created_at,\'yyyy-MM-dd\') as tanggal, 
        count(id) as jum from approval.dbo.document_master A
        where A.last_status <> \'cancel\' and month(A.created_at)=\''.$bulan.'\' and year(A.created_at)='.$tahun.' 
        group by format(A.created_at,\'yyyy-MM-dd\') ');

        if (count($data_graph) == 0) {
            $chartData =[];
        }else{
            $arr = [];
            foreach($data_graph as $row)
            {
                $arr[] = (array) $row;
            }

            $begin = new DateTime( date('Y-m-01') ); //or given date
            $end = new DateTime( date('Y-m-t') );
            $end = $end->modify( '+1 day' ); 
            $interval = new DateInterval('P1D');
            $dateRange = new DatePeriod($begin, $interval ,$end);

            $chartData =[];

            foreach($dateRange as $date){
                $dataKey = array_search($date->format("Y-m-d"), array_column($arr, 'tanggal'));
                if ($dataKey !== false) { // if we have the data in given date
                    $chartData[$date->format("Y-m-d")] = $arr[$dataKey]['jum'];
                }else {
                    //if there is no record, create default values
                    $chartData[$date->format("Y-m-d")] = 0;
                }
            }
        }
        
        return view('approval.dashboard.index',compact('dept','today','bulan','data_document','data_document_open','data_document_man','data_graph'),['chartData' => $chartData]);
    }
}
