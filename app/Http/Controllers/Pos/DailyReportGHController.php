<?php

namespace App\Http\Controllers\Pos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;

class DailyReportGHController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(\Gate::allows('pos_daily_report_sales_gh_access'), 403);

        //date('Y-m-d H:i:s');
        $batas_hari = date('d');
        $bulan = date('m');
        $gh_pilih = "";
        $store = "";
        $ghs = DB::select('select distinct id, nama from pos_server.dbo.master_gh where status=1 order by nama');
        //$datanya = [];
        $month = "[]";
        $data_satu = "[]";
        $data_dua = "[]";
        $data_tiga = "[]";
        return view('pos.dailyreportgh.index', compact('bulan','ghs','gh_pilih','store'),['Months' => $month, 'data1' => $data_satu, 'data2' => $data_dua,'data3' => $data_tiga]);
    }

    public function searchData($bulan, $gh)
    {
        abort_unless(\Gate::allows('pos_daily_report_sales_gh_access'), 403);

        $bulan = (!empty($bulan)) ? ($bulan):('01');
        $gh = (!empty($gh)) ? ($gh):('0');
        $gh_pilih = $gh;
        $brandnya = "";
        $query = 'exec pos_server.dbo.yud_report_sales_by_gh_2020 \''.$bulan.'\',\''.$gh.'\'';
        $datanya = DB::select($query);

        $ghs = DB::select('select distinct id, nama from pos_server.dbo.master_gh where status=1 order by nama');

        if (count($datanya) == 0) {
            $month = "[]";
            $data_satu = "[]";
            $data_dua = "[]";
            $data_tiga = "[]";
            return view('pos.dailyreportgh.index', compact('batas_hari','bulan','ghs','brandnya','gh_pilih'),['success' => 'ok','Months' => $month, 'data1' => $data_satu, 'data2' => $data_dua,'data3' => $data_tiga]);
        
        }else{

            $month = "";
            $data_satu = "";
            $data_dua = "";
            $data_tiga = "";

            foreach($datanya as $row)
            {
                $month .= ",".$row->tanggal;
                $data_satu .= ",".$row->sales_2018;
                $data_dua .= ",".$row->sales_2019;
                $data_tiga .= ",".$row->sales_2020;
            }

            $month = substr($month, 1);
            $data_satu = substr($data_satu, 1);
            $data_dua = substr($data_dua, 1);
            $data_tiga = substr($data_tiga, 1);

            $month = "[".str_replace('.', '', $month)."]";
            $data_satu = "[".str_replace('.', '', $data_satu)."]";
            $data_dua = "[".str_replace('.', '', $data_dua)."]";
            $data_tiga = "[".str_replace('.', '', $data_tiga)."]";
            
            $batas_bulan = date('m');

            if($batas_bulan == $bulan){
                //berarti masuk bulan berjalan
                $batas_hari = date('d');
                if(strlen($batas_hari) < 2){
                    $batas_hari = "0".$batas_hari;
                }
                return view('pos.dailyreportgh.index', compact('datanya','batas_hari','bulan','ghs','brandnya','gh_pilih'),['success' => 'ok','Months' => $month, 'data1' => $data_satu, 'data2' => $data_dua,'data3' => $data_tiga]);
        
            }else{
                //bukan bulan berjalan
                return view('pos.dailyreportgh.index', compact('datanya','bulan','ghs','brandnya','gh_pilih'),['success' => 'ok','Months' => $month, 'data1' => $data_satu, 'data2' => $data_dua,'data3' => $data_tiga]);
        
            }
            
        }
    }

    /*public function getDataStore($id){
        $data = DB::select('select store FROM pos_server.dbo.master_gh_detail 
        where gh_id='.$id.'
        ORDER BY store');

        $options = array();
        foreach($data as $row)
        {
            $options += array($row->store => $row->store);
        }
        return Response::json($options);
    }*/
}
