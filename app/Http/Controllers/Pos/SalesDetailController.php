<?php

namespace App\Http\Controllers\Pos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;

class SalesDetailController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(\Gate::allows('pos_daily_report_sales_all_access'), 403);

        //date('Y-m-d H:i:s');
        $start = date('d-M-Y');
        $end = date('d-M-Y');
        $brand_pilih = "";
        $store = "";
        $brands = DB::select('select kode_brand,nama_brand FROM pos_server.dbo.dt_brand where aktif=\'1\' ORDER BY nama_brand');
        return view('pos.sales-detail.index', compact('start','end','brands','brand_pilih','store'));
    }

    public function searchData($brand, $store, $start, $end)
    {
        abort_unless(\Gate::allows('pos_daily_report_sales_all_access'), 403);
        
        $bulan = (!empty($bulan)) ? ($bulan):('01');
        $brand_pilih = (!empty($brand)) ? ($brand):('all');
        $store = (!empty($store)) ? ($store):('all');

        if($store != "all"){
            $brand = "all";
        }else{
            $brand = $brand_pilih;
        }

        $brandnya = (!empty($request->brandnya)) ? ($request->brandnya):('all');

        $datanya = DB::select('select (format(A.tanggal_transaksi,\'dd-MMM-yyyy\')) as tgl,
        concat(left(A.id_tr_sales_header,6),right(A.id_tr_sales_header,6)) as no_struk,
        A.id_user as kasir,(format(A.tanggal_transaksi,\'HH:mm\')) as jam,
        (select sum(B.qty) from pos_server.dbo.tr_sales_detail B where A.id_tr_sales_header=B.id_tr_sales_header) as total_qty,
        (select sum(B.hpp) from pos_server.dbo.tr_sales_detail B where A.id_tr_sales_header=B.id_tr_sales_header
        and B.qty > 0) as total_hpp,
        (select sum(B.harga_jual) from pos_server.dbo.tr_sales_detail B where A.id_tr_sales_header=B.id_tr_sales_header
        and B.qty > 0) as total_harga,
        (select sum(B.net) from pos_server.dbo.tr_sales_detail B where A.id_tr_sales_header=B.id_tr_sales_header
        and B.qty = 0) as total_discount,
        (select sum(B.net) from pos_server.dbo.tr_sales_detail B where A.id_tr_sales_header=B.id_tr_sales_header) as grand_total
        from pos_server.dbo.tr_sales_header A
        where A.id_Store=\''.$store.'\'
        and format(A.tanggal_transaksi,\'yyyy-MM-dd\') >=\''.$start.'\' 
        and format(A.tanggal_transaksi,\'yyyy-MM-dd\') <=\''.$end.'\' 
        order by (format(A.tanggal_transaksi,\'dd-MM-yyyy\')), (format(A.tanggal_transaksi,\'HH:mm\')) asc');

        $brands = DB::select('select kode_brand,nama_brand FROM pos_server.dbo.dt_brand where aktif=\'1\' ORDER BY nama_brand');

        $start = date("d-M-Y", strtotime($start));
        $end = date("d-M-Y", strtotime($end));

        return view('pos.sales-detail.index', compact('datanya','start','end','brands','brandnya','brand_pilih','store'));    
    }
}
