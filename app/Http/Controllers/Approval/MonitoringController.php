<?php

namespace App\Http\Controllers\Approval;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator, Redirect, Response, File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use DataTables;
use Illuminate\Support\Str;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        return view('approval.monitoring.index');
    }
    public function search(Request $request)
    {
        $nodocument = $request->searchtext;
        //dd($noarticle);

        $datanya = DB::select('select b.id, b.nik, b.nama, 
        case when b.no_document is null then b.id 
else b.no_document end as no_document,
            no_ref = STUFF(( SELECT \',\' + md.kode_category
                     FROM approval.dbo.document_digital md
                     WHERE b.id = md.document_id
                     FOR XML PATH(\'\'), TYPE).value(\'.\', \'NVARCHAR(MAX)\'), 1, 1, \'\'),b.keterangan,
            a.status, a.alasan, format(a.created_at,\'dd-MMM-yyyy HH:mm\') as tglin, format(a.updated_at,\'dd-MMM-yyyy HH:mm\') as tglout 
            from approval.dbo.document_status a join approval.dbo.document_master b 
            on a.document_id = b.id
            where b.id =\'' . $nodocument . '\' or b.no_document =\'' . $nodocument . '\' order by a.id asc');
        // dd($datanya);

        return view('approval.monitoring.search', compact('datanya'));
    }
}
