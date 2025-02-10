<?php

namespace App\Http\Controllers\Approval;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;

class NotesController extends Controller
{
    public function index(Request $request)
    {
        //abort_unless(\Gate::allows('approval_report_document'), 403);
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $datanya = DB::select('select id, document_id, notes, pembuat, 
        format(created_at,\'dd-MM-yyyy\') as tgl, 
        isnull(format(read_date,\'dd-MM-yyyy\'),\'\') as read_date, read_by
        from approval.dbo.document_note where penerima=\''.auth()->user()->nik.'\'
        order by created_at asc');

        return view('approval.notes.index',compact('datanya','nik','name'));
    }

    public function readNotes(Request $request)
    {
        $id             = $request->id;
        $nik            = auth()->user()->nik;

        try{
            $temp = DB::select('approval.dbo.sp_read_notes \''.$id.'\',\''.$nik.'\'');
            $data = $temp[0];
            if ($data->hasil == "ok"){
                return response()->json(['success' => "Berhasil"]);
            }else{
                return response()->json(['errors' => $data->hasil]);
            }
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }
}
