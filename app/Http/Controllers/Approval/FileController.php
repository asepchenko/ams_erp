<?php

namespace App\Http\Controllers\Approval;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function index(Request $request)
    {
        //abort_unless(\Gate::allows('store_target_access'), 403);
        $bulan = (!empty($_GET["bulan"])) ? ($_GET["bulan"]) : (date('m'));

        if($request->ajax())
        {
                if(\Gate::allows('approval_file_special_access')){
                    $where = " ";
                }else{
                    $where = " and B.document_priority_id in (1,2) ";
                }
                //priority selain 1,2 tidak boleh muncul disini
                $data = DB::select('
                select A.id, A.document_id, A.category_name, A.nama_file, A.keterangan, 
                A.created_at, A.created_by, B.last_status
                from approval.dbo.document_file A
                join approval.dbo.document_master B on A.document_id=B.id
                where month(A.created_at)=\''.$bulan.'\' '.$where.' order by A.nama_file desc');
            
            return DataTables::of($data)
                ->addColumn('action', function($data){
                    $url = asset('/file/'.$data->nama_file);
                    $button = '<a href="'.$url.'" target="_blank" class="btn btn-primary btn-sm">Download</a>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('approval.file.index', compact('bulan'));
    }
}
?>