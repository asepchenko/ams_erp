<?php

namespace App\Http\Controllers\Approval;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;

class MyDocumentController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(\Gate::allows('approval_document_access'), 403);
        return view('approval.my-document.index');
    }
  
}
