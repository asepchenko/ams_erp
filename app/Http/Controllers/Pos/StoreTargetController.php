<?php

namespace App\Http\Controllers\Pos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\StoreTarget;
use App\Imports\StoreTargetImport;

class StoreTargetController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(\Gate::allows('pos_store_target_access'), 403);
        $bulan = (!empty($_GET["bulan"])) ? ($_GET["bulan"]) : (date('m'));
        $store = "";
        $ghs = DB::select('select distinct id, nama from pos_server.dbo.master_gh where status=1 order by nama');
        $gh_pilih = "";

        if($request->ajax())
        {
            //$bulan = (!empty($_GET["bulan"])) ? ($_GET["bulan"]) : (date('m'));
            //REPLACE(FORMAT(isnull(sum(B.store_target),0), \'#,#\', \'en-US\'), \',\', \'.\') as total_store_target
            $data = DB::select('
            select A.store_id, B.bulan, B.tahun, 
            isnull(sum(B.store_target),0) as total_store_target
            from pos_server.dbo.dt_store A
            left join pos_server.dbo.dt_store_target B on A.store_id=B.store_id
            where left(A.store_id,1) = \'R\' and B.bulan=\''.$bulan.'\'  and B.tahun=\'2020\'
            group by B.bulan, B.tahun, A.store_id
            union all
            select store_id, \''.$bulan.'\'  as bulan, \'2020\' as tahun, 0 as total_store_target
            from pos_server.dbo.dt_store
            where left(store_id,1) = \'R\' and store_id not in(
            select store_id from pos_server.dbo.dt_store_target where bulan=\''.$bulan.'\'  and tahun=\'2020\'
            )
            order by store_id');
            return DataTables::of($data)
                    ->addColumn('action', function($data){
                        $button = '<a href="storetarget/'.$data->store_id.'/'.$data->bulan.'/'.$data->tahun.'/edit">Edit</a>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('pos.storetarget.index', compact('bulan','ghs','gh_pilih'));
    }

    /*public function searchData($bulan, $gh)
    {
        abort_unless(\Gate::allows('daily_report_sales_gh_access'), 403);

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
            return view('admin.storetarget.index', compact('batas_hari','bulan','ghs','brandnya','gh_pilih'),['success' => 'ok','Months' => $month, 'data1' => $data_satu, 'data2' => $data_dua,'data3' => $data_tiga]);
        
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
                return view('admin.storetarget.index', compact('datanya','batas_hari','bulan','ghs','brandnya','gh_pilih'),['success' => 'ok','Months' => $month, 'data1' => $data_satu, 'data2' => $data_dua,'data3' => $data_tiga]);
        
            }else{
                //bukan bulan berjalan
                return view('admin.storetarget.index', compact('datanya','bulan','ghs','brandnya','gh_pilih'),['success' => 'ok','Months' => $month, 'data1' => $data_satu, 'data2' => $data_dua,'data3' => $data_tiga]);
        
            }
            
        }
    }*/

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

    public function downloadExcel()
    {
        //return Storage::download('ImportStoreTarget.xlsx');
        return response()->download(storage_path("app/public/ImportStoreTarget.xlsx"));
    }

    public function importExcel(Request $request)
    {
        // validasi
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        $file = $request->file('file');
        $nama_file = rand().$file->getClientOriginalName();
    
        // upload ke dalam folder public
        $file->move('store_target',$nama_file);
        
        // import data
        Excel::import(new StoreTargetImport, public_path('/store_target/'.$nama_file));
        return response()->json(['success' => "OK"]);

        /*if ($data->hasil == "sukses"){
            return response()->json(['success' => $data->hasil]);
        }else{
            return response()->json(['errors' => $data->hasil]);
        }*/
    }

    public function editData($store, $bulan, $tahun)
    {
        abort_unless(\Gate::allows('pos_store_target_edit'), 403);
        $query = 'select * from pos_server.dbo.dt_store_target where store_id= \''.$store.'\' and bulan=\''.$bulan.'\' and tahun=\''.$tahun.'\'';
        $datanya = DB::select($query);
        return view('pos.storetarget.edit', compact('datanya','store'));
    }
}
