<?php

namespace App\Http\Controllers\Approval;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Redirect,Response,File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use DataTables;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(\Gate::allows('approval_document_access'), 403);
        $bulan = (!empty($_GET["bulan"])) ? ($_GET["bulan"]) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen; 
        $jab = auth()->user()->kode_jabatan; 
        $data_prioritas = DB::select('select id, priority_name from approval.dbo.document_priority where is_active=1 order by priority_name');

        if($request->ajax())
        {
            //where A.current_dept=(select Z.kode_departemen from users Z where Z.nik=\''.$nik.'\')

            if($bulan == ''){
                $where = '';
            }else{
                $where = ' and month(A.created_at)=\''.$bulan.'\'';
            }

            $data = DB::select('
            select \'\' as pilih, A.id, A.nik, C.priority_name, A.nama, A.kode_departemen, A.current_jab, A.keterangan, A.last_status, 
            format(A.created_at,\'dd-MMM-yyyy HH:mm\') as created_at,
            (select top 1 z.is_pu 
            from approval.dbo.document_digital z where z.document_id=A.id and z.kode_category=\'VM\') as is_pu,
            no_ref = STUFF((
                SELECT \',\' + md.kode_category
                FROM approval.dbo.document_digital md
                WHERE A.id = md.document_id
                FOR XML PATH(\'\'), TYPE).value(\'.\', \'NVARCHAR(MAX)\'), 1, 1, \'\')
            from approval.dbo.document_master A
            join approval.dbo.document_priority C on A.document_priority_id=C.id
            join users B on A.kode_departemen = B.kode_departemen COLLATE SQL_Latin1_General_CP1_CI_AS 
            where left(A.current_dept,3) like (select left(Z.kode_departemen,3) from users Z where Z.nik=\''.$nik.'\')
            and A.current_jab like \'%'.$jab.'%\''.$where.'
            group by A.id, A.nik, A.nama, C.priority_name, A.kode_departemen, A.current_jab, A.keterangan, A.last_status, A.created_at');
            
            return DataTables::of($data)
                    ->addColumn('action', function($data){
                        $button = '';
                        //if($data->last_status == "open" || $data->last_status == "closed"){
                        if($data->last_status == "open"){
                            $button .= '<a href="document/'.$data->id.'/edit">Edit</a>';
                        }else{
                            if(Str::contains($data->current_jab,  ['MS','SP','MG','MA'])){
                                $button .= '<a href="document/'.$data->id.'/proses">Proses</a>';
                            }
                        }
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('approval.document.index', compact('bulan','nik','name','data_prioritas'));
    }

    public function update(Request $request)
    {
        $id         = $request->id;
        $keterangan =  $request->keterangan;

        try{
            $temp = DB::select('approval.dbo.sp_update_document \''.$id.'\',\''.auth()->user()->nik.'\',\''.$keterangan.'\'');
            $data = $temp[0];
            if ($data->hasil == "gagal"){
                return redirect('approval/document/'.$id.'/edit')->withErrors(['errors', 'gagal update document']);
                //return response()->json(['errors' => 'gagal update document']);
            }else{
                return redirect('approval/document/'.$id.'/edit')->withSuccess('berhasil update document');
            }
        }
        catch (\Exception $e) {
            return redirect('approval/document/'.$id.'/edit')->withErrors(['errors', $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try{
            $temp = DB::select('approval.dbo.sp_add_document \''.auth()->user()->nik.'\',\''.auth()->user()->name.'\',\''.auth()->user()->kode_jabatan.'\',\''.$request->keterangan.'\','.$request->prioritas.'');
            $data = $temp[0];
            if ($data->hasil == "gagal"){
                return response()->json(['errors' => 'gagal create document,pastikan semua notes sudah dibaca atau hubungi IT']);
            }else{
                DB::rollback();
                return response()->json(['success' => $data->hasil]);
            }
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function getDataProses($id){
        //abort_unless(\Gate::allows('article_edit'), 403);
        $nik = auth()->user()->nik;
        $kode_dept= auth()->user()->kode_departemen;
        $kode_jabatan= auth()->user()->kode_jabatan;
        $link_cetak = "approval/report/".$id."/print";
        $kodedpt= substr(auth()->user()->kode_departemen,0,3);

        $temp = DB::select('select A.id, A.nik, A.nama, A.kode_departemen, A.keterangan, A.last_status, 
        A.created_at, A.created_by, B.priority_name
        from approval.dbo.document_master A
        join approval.dbo.document_priority B on A.document_priority_id=B.id where A.id=\''.$id.'\'
        and A.current_dept like \'%'.$kodedpt.'%\'');
        if(count($temp) <= 0){
            abort(403);
        }

        $data = $temp[0];

        $data_pu = DB::Select('select isnull(is_pu,\'0\') as pu from approval.dbo.document_digital 
        where document_id=\''.$id.'\' and kode_category=\'VM\'');

        if(count($data_pu) > 0){
            if($data_pu[0]->pu == "0"){
                $punya = "unchecked";
            }else{
                $punya = "checked";
            }
        }else{
            $punya = "";
        }

        return view('approval.document.proses', compact('data','nik','link_cetak','punya','kode_dept'));

    }

    public function edit($id)
    {
        //abort_unless(\Gate::allows('approval_document_access'), 403);
        $nik = auth()->user()->nik;
        $kode_dept= auth()->user()->kode_departemen;
        $kode_jabatan= auth()->user()->kode_jabatan;

        $data_category_document = DB::select('select replace(concat(kode_category,\' - \',
        nama_category),\'_\',\' \') as nama_category, kode_category
        from approval.dbo.category order by kode_category');
        $data_category_file = DB::select('select category_name from approval.dbo.category_file order by category_name');
        $data_bank = DB::select('select kode_bank from dt_bank order by kode_bank');
        $data_matauang = DB::select('select currency_type from dt_currency where is_aktif=\'1\' order by currency_type');

        $temp = DB::select('select id, nik, nama, kode_departemen, keterangan, last_status, created_at, created_by
        from approval.dbo.document_master where id=\''.$id.'\' and nik=\''.$nik.'\' and last_status=\'open\'');
    
        if(count($temp) <= 0){
            abort(403);
        }

        $data = $temp[0];

        $temp_alasan = DB::select('select top 1 alasan, (select name from users where nik=updated_by) as nama 
        from approval.dbo.document_status 
        where document_id=\''.$id.'\' and alasan is not null order by id desc');
        if(count($temp_alasan) <= 0){
            $alasan = NULL;
        }else{
            $alasan = $temp_alasan[0];
        }

        return view('approval.document.edit', compact('data','nik','data_matauang','data_category_document','data_category_file','alasan','data_bank'));

    }

    public function editDataDigital($id){
        try{
            $temp = DB::select('select id, document_id, kode_category, format(tanggal_bayar,\'dd-MMM-yyyy HH:mm:ss\') as tanggal_bayar,
            nama_tujuan, rek_tujuan, kode_bank, nama_rek, mata_uang, jumlah, no_ref, keterangan 
            from approval.dbo.document_digital where id=\''.$id.'\'');
            $data_digital_edit = $temp[0];
            return response()->json(['success' => $data_digital_edit]);
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function dataHistoryStatus($id){
        $data = DB::select('select A.status, format(A.created_at,\'dd-MMM-yyyy\') as tanggal, A.id,
        case when A.updated_by is null then
        case when A.status = \'open\' and A.updated_by is null then
            B.nama
        end
        else
        (select z.name from users z where z.nik=A.updated_by) 
        end
        as nama , A.alasan from approval.dbo.document_status A
        join approval.dbo.document_master B on A.document_id=B.id
        where A.document_id=\''.$id.'\' order by A.id asc');
            
        return DataTables::of($data)
            ->make(true);
    }

    public function dataDigital($id){
        $data = DB::select('select A.id, A.document_id, A.mata_uang, A.kode_category, A.no_digital, A.tanggal_bayar, A.nama_tujuan, 
        A.rek_tujuan, A.kode_bank, A.nama_rek, A.is_pu, REPLACE(FORMAT(A.jumlah, \'N\', \'en-us\'), \'.00\', \'\') as jumlah, A.no_ref, 
        A.keterangan, A.created_at, A.created_by, B.last_status
        from approval.dbo.document_digital A
        join approval.dbo.document_master B on A.document_id=B.id
        where A.document_id=\''.$id.'\' order by A.kode_category desc');
            
        return DataTables::of($data)
            ->addColumn('action', function($data){
                    if($data->last_status == "open"){
                        $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm">Edit</button>';
                        $button .= '&nbsp<button type="button" name="delete_digital" id="'.$data->id.'" class="delete_digital btn btn-danger btn-sm">Delete</button>';
                    }else{
                        $button = '';
                    }
                    
                    return $button;
                })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function dataFile($id){
        $data = DB::select('select A.id, A.document_id, A.category_name, A.nama_file, A.keterangan, 
        A.created_at, A.created_by, B.last_status
        from approval.dbo.document_file A
        join approval.dbo.document_master B on A.document_id=B.id
        where A.document_id=\''.$id.'\' order by A.category_name desc');
            
        return DataTables::of($data)
            ->addColumn('action', function($data){
                    $url = asset('/file/'.$data->nama_file);
                    if($data->last_status == "open"){
                        $button = '<a href="'.$url.'" target="_blank" class="btn btn-primary btn-sm">Download</a>';
                        $button .= '&nbsp<button type="button" name="delete_file" id="'.$data->nama_file.'" class="delete_file btn btn-danger btn-sm">Delete</button>';
                    }else{
                        $button = '<a href="'.$url.'" target="_blank" class="btn btn-primary btn-sm">Download</a>';
                    }

                    return $button;
                })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getDataSupplier(Request $request){
        $search = $request->search;
        if($search <> ''){
            $q = $request->search;
            $data = DB::select('select distinct Deskripsi as nama_supplier from 
            GASYSTEM.dbo.GAMasterSupplier where Deskripsi like \'%'.$search.'%\' order by Deskripsi');
            return response()->json($data);
        }
    }

    public function getNoPO(Request $request){
        $search = $request->search;
    
        if($search <> ''){
            $data = DB::select('select A.no_po, B.deskripsi, replace(A.net_total,\'.00\',\'\') as net_total, 
            B.Nama_Rekening1, B.No_Rekening1, A.Keterangan from gasystem.dbo.gapo A
            join gasystem.dbo.GAMasterSupplier B on A.kode_supplier=B.kode_supplier
            where A.no_po like \'%'.$search.'%\' order by A.no_po');
        }

        $response = array();
        foreach($data as $po){
        $response[] = array("value"=>$po->no_po,"label"=>$po->no_po,
            "deskripsi"=>$po->deskripsi,"jumlah"=>$po->net_total, "nama_rek"=>$po->Nama_Rekening1,
            "no_rek"=>$po->No_Rekening1, "keterangan"=>$po->Keterangan);
        }
        return Response::json($response);
    }

    public function submitMail($id){
        try{
            //dapetin email atasan
            $nik            = auth()->user()->nik;
            $name            = auth()->user()->name;
            $temp_email = DB::select('select top 1 name, email from yud_test.dbo.users where kode_departemen in(
                select kode_departemen from users
                where nik=\''.$nik.'\') and jabatan like \'%manager%\'');
            $data_email = $temp_email[0];

            $details = [
                'title' => 'AMS - Approval System',
                'body' => 'Dh, '.$data_email->name.', Silahkan lakukan proses untuk Nomor Dokumen : '.$id.'
                 dari '.$name.' '
            ];
        
            \Mail::to($data_email->email)->send(new \App\Mail\submitMail($details));
            return "ok";
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function submitAfterRejectMail($id){
        try{
            //dapetin last_dept dulu
            $next = DB::select('select top 1 upper(replace(last_reject,\'APPROVAL_\',\'\')) as dept
            from approval.dbo.document_status where document_id=\''.$id.'\'
            and last_reject is not null order by id desc');

            //dapetin email dept terkait
            $temp_email = DB::select('select top 1 kode_departemen, name, email from users 
            where kode_departemen =\''.$next[0]->dept.'\' and kode_jabatan in (\'MG\',\'MS\')');
            $data_email = $temp_email[0];

            $nik            = auth()->user()->nik;
            $name            = auth()->user()->name;

            $details = [
                'title' => 'AMS - Approval System',
                'body' => 'Dh, '.$data_email->name.'. Silahkan lakukan proses untuk Nomor Dokumen : '.$id.'
                 yang sudah diperbaiki oleh '.$name.' '
            ];
        
            \Mail::to($data_email->email)->send(new \App\Mail\submitMail($details));
            return "ok";
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function approveMail($id){
        try{
            //dapetin current_dept dulu
            $next = DB::select('select current_dept,last_status from approval.dbo.document_master
            where id=\''.$id.'\'');

            //dapetin email dept terkait
            $temp_email = DB::select('select top 1 kode_departemen, name, email from users 
            where kode_departemen =\''.$next[0]->current_dept.'\' and kode_jabatan in (\'MG\',\'MS\')');
            $data_email = $temp_email[0];

            $nik            = auth()->user()->nik;
            $name            = auth()->user()->name;

            $details = [
                'title' => 'AMS - Approval System',
                'body' => 'Dh, '.$data_email->name.'. Silahkan lakukan proses untuk Nomor Dokumen : '.$id.'
                 yang sudah disetujui oleh '.$name.' '
            ];
        
            /*Mail::to($request->user())
            ->cc($moreUsers)
            ->bcc($evenMoreUsers)
            ->send(new OrderShipped($order));*/

            \Mail::to($data_email->email)->send(new \App\Mail\submitMail($details));
            return "ok";
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function rejectMail($id){
        try{
            $nik            = auth()->user()->nik;
            $name            = auth()->user()->name;

            //dapetin nik pembuat dulu
            $next = DB::select('select B.name, B.email, B.kode_departemen from approval.dbo.document_master A 
            join users B on A.nik=B.nik where A.id=\''.$id.'\'');
            $data_email = $next[0];

            //dapetin alasan reject
            $alasan = DB::select('select top 1 alasan as alasan from approval.dbo.document_status 
            where document_id=\''.$id.'\' and alasan is not null and updated_by=\''.$nik.'\'');

            $details = [
                'title' => 'AMS - Approval System',
                'body' => 'Dh, '.$data_email->name.'. Silahkan perbaiki Nomor Dokumen : '.$id.'
                 yang di reject oleh '.$name.' ('.$alasan[0]->alasan.')'
            ];
        
            \Mail::to($data_email->email)->send(new \App\Mail\submitMail($details));
            return "ok";
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function cancelMail($id){
        try{
            $nik            = auth()->user()->nik;
            $name            = auth()->user()->name;

            //dapetin nik pembuat dulu
            $next = DB::select('select B.name, B.email, B.kode_departemen from approval.dbo.document_master A 
            join users B on A.nik=B.nik where A.id=\''.$id.'\'');
            $data_email = $next[0];

            //dapetin alasan cancel
            $alasan = DB::select('select top 1 alasan as alasan from approval.dbo.document_status 
            where document_id=\''.$id.'\' and alasan is not null and status=\'cancel\' and created_by=\''.$nik.'\'');

            $details = [
                'title' => 'AMS - Approval System',
                'body' => 'Dh, '.$data_email->name.'. Nomor Dokumen : '.$id.'
                 yang anda submit telah DIBATALKAN oleh '.$name.' ('.$alasan[0]->alasan.')'
            ];
        
            \Mail::to($data_email->email)->send(new \App\Mail\submitMail($details));
            return "ok";
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function submitDocument(Request $request)
    {
        $id             = $request->id;
        $signature      = $request->signature;
        $nik            = auth()->user()->nik;
        $nama           = auth()->user()->name;
        $dept           = auth()->user()->kode_departemen;

        try{
            $temp = DB::select('approval.dbo.sp_submit_document \''.$id.'\',\''.$dept.'\',\''.$signature.'\',\''.$nik.'\'');
            $data = $temp[0];
            if ($data->hasil == "ok"){

                 //cek dulu apakah habis dari reject
                $cek = DB::select('select count(id) as jum from approval.dbo.document_status where document_id=\''.$id.'\'
                and last_reject is not null');

                if($cek[0]->jum == 0){
                    //kirim e-mail submit
                    $hasil_email = $this->submitMail($id);

                    if($hasil_email == "ok"){
                        return response()->json(['success' => "Berhasil submit dan mengirim email"]);
                    }else{
                        return response()->json(['success' => "Berhasil submit tapi gagal mengirim email -> ".$hasil_email]);
                    }
                }else{
                    //kirim e-mail setelah reject
                    $hasil_email = $this->submitAfterRejectMail($id);

                    if($hasil_email == "ok"){
                        return response()->json(['success' => "Berhasil submit kembali setelah reject dan mengirim email"]);
                    }else{
                        return response()->json(['success' => "Berhasil submit kembali setelah reject tapi gagal mengirim email -> ".$hasil_email]);
                    }
                }
            }else{
                return response()->json(['errors' => $data->hasil]);
            }
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function approveDocument(Request $request)
    {
        $id             = $request->id;
        $signature      = $request->signature;
        $notes          = $request->notes;
        $nik            = auth()->user()->nik;

        try{
            $temp = DB::select('approval.dbo.sp_approve_document \''.$id.'\',\''.$signature.'\',\''.$notes.'\',\''.$nik.'\'');
            $data = $temp[0];
            if ($data->hasil == "ok"){
                //kirim e-mail approve
                $hasil_email = $this->approveMail($id);

                if($hasil_email == "ok"){
                    return response()->json(['success' => "Berhasil approve dan mengirim email"]);
                }else{
                    return response()->json(['success' => "Berhasil approve tapi gagal mengirim email -> ".$hasil_email]);
                }
            }else{
                return response()->json(['errors' => $data->hasil]);
            }
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function massApproveDocument(Request $request)
    {
        $id             = $request->id;
        $signature      = $request->signature;
        $nik            = auth()->user()->nik;

        try{
            $temp = DB::select('approval.dbo.sp_mass_approve_document \''.$id.'\',\''.$signature.'\',\''.$nik.'\'');
            $data = $temp[0];
            if ($data->hasil == "ok"){
                //kirim e-mail approve
                $hasil_email = $this->approveMail($id);

                if($hasil_email == "ok"){
                    return response()->json(['success' => "Berhasil mass approve dan mengirim email"]);
                }else{
                    return response()->json(['success' => "Berhasil mas  approve tapi gagal mengirim email -> ".$hasil_email]);
                }
            }else{
                return response()->json(['errors' => $data->hasil]);
            }
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function rejectDocument(Request $request)
    {
        $id             = $request->id;
        $alasan         = $request->alasan;
        $nik            = auth()->user()->nik;
        
        try{
            $temp = DB::select('approval.dbo.sp_reject_document \''.$id.'\',\''.$alasan.'\',\''.$nik.'\'');
            $data = $temp[0];
            if ($data->hasil == "ok"){
                //kirim e-mail reject
                $hasil_email = $this->rejectMail($id);

                if($hasil_email == "ok"){
                    return response()->json(['success' => "Berhasil reject dan mengirim email"]);
                }else{
                    return response()->json(['success' => "Berhasil reject tapi gagal mengirim email -> ".$hasil_email]);
                }
            }else{
                return response()->json(['errors' => $data->hasil]);
            }
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function cancelDocument(Request $request)
    {
        $id             = $request->id;
        $alasan         = $request->alasan;
        $nik            = auth()->user()->nik;

        try{
            $temp = DB::select('approval.dbo.sp_cancel_document \''.$id.'\',\''.$alasan.'\',\''.$nik.'\'');
            $data = $temp[0];
            if ($data->hasil == "ok"){
                //kirim e-mail cancel
                $hasil_email = $this->cancelMail($id);

                if($hasil_email == "ok"){
                    return response()->json(['success' => "Berhasil cancel dan mengirim email"]);
                }else{
                    return response()->json(['success' => "Berhasil cancel tapi gagal mengirim email -> ".$hasil_email]);
                }
            }else{
                return response()->json(['errors' => $data->hasil]);
            }
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function updatePU(Request $request)
    {
        $id             = $request->id;
        $is_pu          = $request->pu;
        $nik            = auth()->user()->nik;
        try{
            $temp = DB::select('approval.dbo.sp_update_pu \''.$id.'\',\''.$is_pu.'\',\''.$nik.'\'');
            
            $data = $temp[0];
            if ($data->hasil == "ok"){
                return response()->json(['success' => $data->hasil]);
            }else{
                return response()->json(['errors' => $data->hasil]);
            }
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function saveDigital(Request $request)
    {
        $id             = $request->id;
        $category       = $request->category_document;
        $tanggal_bayar  = $request->tanggal_bayar;
        $nama_tujuan    = $request->nama_tujuan;
        $kode_bank      = $request->kode_bank;
        $rek            = $request->no_rek;
        $nama_rek       = $request->nama_rek;
        $mata_uang      = $request->mata_uang;
        $jumlah         = $request->jumlah;
        $no_ref         = $request->no_ref;
        $keterangan     = $request->keterangan_document;
        $nik            = auth()->user()->nik;

        try{
            if($tanggal_bayar == ""){
                $temp = DB::select('approval.dbo.sp_add_document_digital \''.$id.'\',\''.$category.'\',NULL,\''.$nama_tujuan.'\',\''.$kode_bank.'\',\''.$nama_rek.'\',\''.$rek.'\','.$jumlah.',\''.$mata_uang.'\',\''.$no_ref.'\',\''.$keterangan.'\',\''.$nik.'\'');
            }else{
                $temp = DB::select('approval.dbo.sp_add_document_digital \''.$id.'\',\''.$category.'\',\''.$tanggal_bayar.'\',\''.$nama_tujuan.'\',\''.$kode_bank.'\',\''.$nama_rek.'\',\''.$rek.'\','.$jumlah.',\''.$mata_uang.'\',\''.$no_ref.'\',\''.$keterangan.'\',\''.$nik.'\'');
            }

            $data = $temp[0];
            if ($data->hasil == "ok"){
                return response()->json(['success' => $data->hasil]);
            }else{
                return response()->json(['errors' => $data->hasil]);
            }
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function updateDigital(Request $request)
    {
        $id             = $request->id;
        $category       = $request->category_document;
        $tanggal_bayar  = $request->tanggal_bayar;
        $nama_tujuan    = $request->nama_tujuan;
        $kode_bank      = $request->kode_bank;
        $rek            = $request->no_rek;
        $nama_rek       = $request->nama_rek;
        $mata_uang      = $request->mata_uang;
        $jumlah         = $request->jumlah;
        $no_ref         = $request->no_ref;
        $keterangan     = $request->keterangan_document;
        $nik            = auth()->user()->nik;

        try{
            if($tanggal_bayar == ""){
                $temp = DB::select('approval.dbo.sp_update_document_digital \''.$id.'\',\''.$category.'\',NULL,\''.$nama_tujuan.'\',\''.$kode_bank.'\',\''.$nama_rek.'\',\''.$rek.'\','.$jumlah.',\''.$mata_uang.'\',\''.$no_ref.'\',\''.$keterangan.'\',\''.$nik.'\'');
            }else{
                $temp = DB::select('approval.dbo.sp_update_document_digital \''.$id.'\',\''.$category.'\',\''.$tanggal_bayar.'\',\''.$nama_tujuan.'\',\''.$kode_bank.'\',\''.$nama_rek.'\',\''.$rek.'\','.$jumlah.',\''.$mata_uang.'\',\''.$no_ref.'\',\''.$keterangan.'\',\''.$nik.'\'');
            }

            $data = $temp[0];
            if ($data->hasil == "ok"){
                return response()->json(['success' => $data->hasil]);
            }else{
                return response()->json(['errors' => $data->hasil]);
            }
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function saveFile(Request $request)
    {
        request()->validate([
            'file'  => 'required|mimetypes:application/pdf,image/jpeg,text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|max:1024',
        ]);

        $id             = $request->id;
        $category       = $request->category_file;
        $file           = $request->file;
        $keterangan     = $request->keterangan_file;
        $nik            = auth()->user()->nik;

        /*$validator = Validator::make($request->all(),
                ['file' => 'image',],
                ['file_front.image' => 'The file must be an image (jpeg, png, bmp, gif, or svg)']);

        if ($validator->fails())
            return array(
                'fail' => true,
                'errors' => $validator->errors()
        );*/

        $ori_file = $file->getClientOriginalName();
        $filename = pathinfo($ori_file, PATHINFO_FILENAME);

        $tgl = date('Ymd_His');
        $nama_file = $filename.'_'.$id.'_'.$category.'_'.$tgl.'.'.$file->getClientOriginalExtension();

        try{
            $temp = DB::select('approval.dbo.sp_add_document_file \''.$id.'\',\''.$category.'\',\''.$keterangan.'\',\''.$nik.'\',\''.$nama_file.'\'');
            $data = $temp[0];
            if ($data->hasil == "ok"){
                $file->move('file', $nama_file);
                return response()->json(['success' => $data->hasil]);
            }else{
                return response()->json(['errors' => $data->hasil]);
            }
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function deleteDigital(Request $request)
    {
        DB::begintransaction();
        try {
            DB::table('approval.dbo.document_digital')
            ->where('id', $request->id)
            ->delete();
            DB::commit();
            return response()->json(['success' => "OK"]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function deleteFile(Request $request)
    {
        //hapus file lamanya
        $path = public_path('file/'.$request->nama_file);
        if (File::exists($path)) {
            File::delete($path);
        }

        DB::begintransaction();
        try {
            DB::table('approval.dbo.document_file')
            ->where('nama_file', $request->nama_file)
            ->delete();
            DB::commit();
            return response()->json(['success' => "OK"]);
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json(['errors' => $e->getMessage()]);
        }
    }
}
