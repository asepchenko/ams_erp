<?php

namespace App\Http\Controllers\Pos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(\Gate::allows('pos_dashboard_access'), 403);
        //and id_store not in(\'777\',\'888\',\'MS001\',\'01E\',\'02E\',\'03E\',\'04E\',\'7777\',\'MS002\')
        $today = date('Y-m-d');
        $data_brands = DB::select('select y.nama_brand, x.kode_brand, 
        isnull(REPLACE(x.today,\'.00\', \'\'),0) as today,
        isnull(REPLACE(x.yesterday,\'.00\', \'\'),0) as yesterday, today as temp 
        from(
        select z.kode_brand, sum(z.today) as today,  sum(z.yesterday) as yesterday from(
                    select B.store_brand_id, case when C.kode_brand=\'RIF\' then \'RF\' else C.kode_brand end as kode_brand, sum(Grand_Total) as total,
                    \'today\' as nama_kolom
                    from pos_server.dbo.tr_sales_header A
                    join pos_server.dbo.dt_store B on A.id_store=B.store_id
                    join pos_server.dbo.dt_brand C on B.store_brand_id=C.nama_brand
                    where datediff(day,tanggal_transaksi,getdate())=0 and C.aktif=1
                    and left(id_store,1) = \'R\'
                    group by B.store_brand_id, C.kode_brand
                    union all
                    select B.store_brand_id, case when C.kode_brand=\'RIF\' then \'RF\' else C.kode_brand end as kode_brand, sum(Grand_Total) as total,
                    \'yesterday\' as nama_kolom
                    from pos_server.dbo.tr_sales_header A
                    join pos_server.dbo.dt_store B on A.id_store=B.store_id
                    join pos_server.dbo.dt_brand C on B.store_brand_id=C.nama_brand
                    where datediff(day,tanggal_transaksi,getdate()-1)=0 and C.aktif=1
                    and left(id_store,1) = \'R\'
                    group by B.store_brand_id, C.kode_brand
                    ) d
                pivot
                (
                max(d.total)
                for d.nama_kolom in (today, yesterday)
                )as z
                group by z.kode_brand
                ) as x join pos_server.dbo.dt_brand y on x.kode_brand=y.kode_brand
                order by temp desc');
        //dd($data_brands);

        $top_sales = DB::select('select Y.id_store, Y.total, Y.gt from( select id_store, 
        REPLACE(FORMAT(sum(grand_total), \'N\', \'en-us\'), \'.00\', \'\') as total, 
        sum(grand_total) as gt
        from pos_server.dbo.tr_sales_header where datediff(day,tanggal_transaksi,format(getdate(),\'yyyy-MM-dd\'))=0
        and left(id_store,1) = \'R\'
        group by id_store) Y
        order by Y.total desc');

        $store_location = "";
        //$datanya = DB::select('select store_id, concat(\'["\',store_id,\' \', alamat,\'",\',latlong,\']\') as lokasi from dt_Store_location');
        $datanya = DB::select('select store_id, concat(\'["\',store_id,\' \', alamat,\' \', REPLACE(sum(B.grand_total), \'.00\', \'\'),\'",\',latlong,\']\') as lokasi, 
        sum(B.grand_total) as total from 
        yud_test.dbo.dt_Store_location A
        left join pos_Server.dbo.tr_sales_header B on A.store_id=B.id_store COLLATE SQL_Latin1_General_CP1_CI_AS 
        where datediff(day,B.tanggal_transaksi,getdate())=0
        group by store_id, alamat,latlong
        union all
        select store_id, concat(\'["\',store_id,\' \', alamat,\' \', 0,\'",\',latlong,\']\') as lokasi, 
        0 as total from 
        yud_test.dbo.dt_Store_location A
        where A.store_id not in(select store_id from 
        yud_test.dbo.dt_Store_location A
        join pos_Server.dbo.tr_sales_header B on A.store_id=B.id_store COLLATE SQL_Latin1_General_CP1_CI_AS 
        where datediff(day,B.tanggal_transaksi,getdate())=0
        group by store_id)');
        foreach($datanya as $row)
        {
            $store_location .= ",".$row->lokasi;
        }
        
        $store_location = substr($store_location, 1);

        $chart_brands = "";
        $chart_data = "";
        foreach($data_brands as $row)
        {
            $chart_brands .= ","."'".$row->kode_brand."'";
            $chart_data .= ",".$row->today;
        }

        $chart_brands = substr($chart_brands, 1);
        $chart_data = substr($chart_data, 1);

        $chart_brands = "[".str_replace('.', '', $chart_brands)."]";
        $chart_data = "[".str_replace('.', '', $chart_data)."]";
        return view('pos.dashboard.index',compact('data_brands','top_sales','today','chart_brands','chart_data','store_location'));
    }
}
