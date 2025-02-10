<?php

namespace App\Http\Controllers\Approval;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;

class MyReportController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(\Gate::allows('approval_report_document'), 403);
        $nik = auth()->user()->nik;
        $database = 'APPROVAL';
        $tabel = DB::select('select table_name, keterangan FROM dt_report_database 
        where database_name=\''.$database.'\' order by table_name');
        $datanya = DB::select('select id, report_name, database_name, table_name 
        FROM dt_report_master where nik=\''.$nik.'\'');
    
        return view('approval.my-report.index',compact('datanya','nik','database','tabel'));
    }

    public function store(Request $request)
    {
        try{
            $temp = DB::select('sp_create_report \''.$request->nik.'\',\''.$request->nama.'\',\''.$request->databasenya.'\',\''.$request->tablenya.'\',\'custom\'');
            $data = $temp[0];
            if ($data->hasil == "gagal"){
                return response()->json(['errors' => 'gagal generate report']);
            }else{
                DB::rollback();
                return response()->json(['success' => $data->hasil]);
            }
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        //abort_unless(\Gate::allows('user_create'), 403);
        $nik = auth()->user()->nik;
        $database = 'APPROVAL';
        $temp = DB::select('select id, report_name, nik, table_name FROM dt_report_master where id=\''.$id.'\'');
        $data = $temp[0];
        return view('approval.my-report.edit', compact('nik','id','database','data'));
    }

    public function viewData($id)
    {
        $data_column = DB::select('
            select A.field_name 
            from dt_report_field A
            where A.master_id=\''.$id.'\'');

            $arr = "";
            foreach($data_column as $row)
            {
                $arr .= ",'".$row->field_name."'";
            }
            $arr = substr($arr, 1);
            $arr = "[".str_replace('.', '', $arr)."]";
            //dd($arr);
        $temp_judul = DB::select('select report_name FROM dt_report_master where id=\''.$id.'\'');
        $judul = $temp_judul[0]->report_name;
        return view('approval.my-report.view', compact('id','data_column','judul'),['arrData' => $arr]);
    }


    public function getReport($id)
    {
        $master = DB::select('select concat(\'select \', yud.field_name, 
        \' from \', yud.database_name, \'.dbo.\', yud.table_name,
        \' where \',yud.kondisi) as hasil
        from(
        select A.database_name, A.table_name, 
        field_name = STUFF((
                  SELECT \',\' + md.field_name
                  FROM dt_report_field md
                  WHERE A.id = md.master_id
                  FOR XML PATH(\'\'), TYPE).value(\'.\', \'NVARCHAR(MAX)\'), 1, 1, \'\'),
        concat(B.field_name,B.operator_name,B.value_name) as kondisi
        from dt_report_master A
        join dt_report_condition B on A.id=B.master_id
        where A.id=\''.$id.'\') as yud');
        $data = DB::select($master[0]->hasil);
        //return response()->json($data);
        return DataTables::of($data)
                    ->make(true);
    }
}
