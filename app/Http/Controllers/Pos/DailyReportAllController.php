<?php

namespace App\Http\Controllers\Pos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;

class DailyReportAllController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(\Gate::allows('pos_daily_report_sales_all_access'), 403);

        //date('Y-m-d H:i:s');
        $bulan = date('m');
        $brand_pilih = "";
        $store = "";
        $brands = DB::select('select kode_brand,nama_brand FROM pos_server.dbo.dt_brand where aktif=\'1\' ORDER BY nama_brand');
        //$datanya = [];
        $month = "[]";
        $data_satu = "[]";
        $data_dua = "[]";
        $data_tiga = "[]";
        return view('pos.dailyreportall.index', compact('bulan','brands','brand_pilih','store'),['Months' => $month, 'data1' => $data_satu, 'data2' => $data_dua,'data3' => $data_tiga]);
    }

    public function searchData($bulan, $brand, $store)
    {
        abort_unless(\Gate::allows('pos_daily_report_sales_all_access'), 403);
        
        $bulan = (!empty($bulan)) ? ($bulan):('01');
        $brand_pilih = (!empty($brand)) ? ($brand):('all');
        $store = (!empty($store)) ? ($store):('all');

        if($store != "all"){
            $brand = "all";
            $data_batch = DB::select('select A.store_id, format(max(B.tgl),\'dd\')+1 as last_batch
            from pos_server.dbo.dt_store A
            left join pos_server.dbo.dt_batch B on A.store_id=B.store_id
            where A.Store_id=\''.$store.'\' group by A.store_id');
            $batas_hari = $data_batch[0]->last_batch;
            //dd($batas_hari);
        }else{
            $brand = $brand_pilih;
        }

        $brandnya = (!empty($request->brandnya)) ? ($request->brandnya):('all');
        $query = 'exec pos_server.dbo.yud_report_sales_2020 \''.$bulan.'\',\''.$brand.'\',\''.$store.'\'';
        $datanya = DB::select($query);

        $brands = DB::select('select kode_brand,nama_brand FROM pos_server.dbo.dt_brand where aktif=\'1\' ORDER BY nama_brand');

        if (count($datanya) == 0) {
            $month = "[]";
            $data_satu = "[]";
            $data_dua = "[]";
            $data_tiga = "[]";
            return view('pos.dailyreportall.index', compact('batas_hari','bulan','brands','brandnya','brand_pilih','store'),['success' => 'ok','Months' => $month, 'data1' => $data_satu, 'data2' => $data_dua,'data3' => $data_tiga]);

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
                return view('pos.dailyreportall.index', compact('datanya','batas_hari','bulan','brands','brandnya','brand_pilih','store'),['success' => 'ok','Months' => $month, 'data1' => $data_satu, 'data2' => $data_dua,'data3' => $data_tiga]);
            
            }else{
                //bukan bulan berjalan
                return view('pos.dailyreportall.index', compact('datanya','bulan','brands','brandnya','brand_pilih','store'),['success' => 'ok','Months' => $month, 'data1' => $data_satu, 'data2' => $data_dua,'data3' => $data_tiga]);
            
            }
        
        }
    }

    public function getDataStore($brand){
        /*$data = DB::select('select store_id FROM pos_server.dbo.dt_store 
        where store_id like \'%'.$brand.'%\' and status_active=1
        ORDER BY store_id');*/

        $data = DB::select('select A.store_id from pos_server.dbo.dt_store A
        join pos_server.dbo.dt_brand B on A.store_brand_id=B.nama_brand
        where B.aktif=1 and A.store_id like \'%'.$brand.'%\' and A.status_active=1
        and A.store_brand_id_2 = B.nama_brand
        ORDER BY A.store_id');

        $options = array();
        foreach($data as $row)
        {
            $options += array($row->store_id => $row->store_id);
        }
        return Response::json($options);
    }
}
