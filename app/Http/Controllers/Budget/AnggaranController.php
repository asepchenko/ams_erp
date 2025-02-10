<?php

namespace App\Http\Controllers\Budget;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator, Redirect, Response, File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use DataTables;
use Illuminate\Support\Str;

class AnggaranController extends Controller
{
    public function index(Request $request)
    {
        //abort_unless(\Gate::allows('approval_document_access'), 403);
        $bulan = (!empty($_GET["bulan"])) ? ($_GET["bulan"]) : ('');
        $category = (!empty($_GET["category"])) ? ($_GET["category"]) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen;
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group;
        $category_request = DB::select('select code_category, concat(code_category,\' - \', category_request) as category_request from budget.dbo.dt_category_request where aktif=1 order by category_request');
        if ($group == 'IT') {
            $code_group = DB::select('select distinct(kode_group) as kode_group, group_budget as deskripsigroup from yud_test.dbo.users');
        } else {
            $code_group = DB::select('select distinct(kode_group) as kode_group, group_budget as deskripsigroup from yud_test.dbo.users where kode_group = \'' . $group . '\'');
        }
        if ($request->ajax()) {
            if ($bulan == '') {
                $where = '';
            } else {
                $where = ' and month(a.created_at)=\'' . $bulan . '\'';
            }
            $data = DB::select('select a.request_id, a.nik, a.nama, a.kode_group, a.current_jab,
            format(a.created_at,\'dd-MMM-yyyy HH;mm\') as created_at, a.last_status, b.category_request,
            upper(a.periode) as periode, a.tahun, concat(c.coa, \' - \', c.description) as coa_budget, a.keterangan,
            REPLACE(FORMAT(isnull(a.nilai_request,0), \'N\', \'en-us\'), \'.00\', \'\') as nilai_request, REPLACE(FORMAT(isnull(a.nilai_realisasi,0), \'N\', \'en-us\'), \'.00\', \'\') as realisasi,
            REPLACE(FORMAT((isnull(a.nilai_request,0) - isnull(a.nilai_realisasi,0)), \'N\', \'en-us\'), \'.00\', \'\') as sisa, a.last_status 
            FROM BUDGET.dbo.tr_budget_request_p a 
            left join BUDGET.dbo.dt_category_request b on a.code_category = b.code_category 
            left join BUDGET.dbo.tr_budget_new c on a.budget_id = c.budget_id
            join users d on a.kode_group = d.kode_group COLLATE SQL_Latin1_General_CP1_CI_AS 
            group by a.request_id, a.nik, a.nama, a.kode_group, a.current_jab, a.keterangan, a.created_at, a.last_status, b.category_request,
            a.periode, a.tahun, c.coa, c.description, a.nilai_request, a.nilai_realisasi');
            // WHERE left(a.current_group,3) like (select left(z.kode_group,3) from users z where z.nik =\'' . $nik . '\')
            // and a.current_jab like \'%' . $jab . '%\'' . $where . '

            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = '';
                    //if($data->last_status == "open" || $data->last_status == "closed"){
                    if ($data->last_status == "open") {
                        $button .= '<a href="anggaran/' . $data->request_id . '/edit">Edit</a>';
                    } elseif ($data->last_status == "approval_acc") {
                        if (Str::contains($data->current_jab,  ['MS', 'MG'])) {
                            $button .= '<a href="anggaran/' . $data->request_id . '/proses">Proses</a>';
                        }
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('budget.anggaran.index', compact('bulan', 'nik', 'name', 'group', 'category_request', 'code_group'));
    }

    public function store(Request $request)
    {
        //dd(auth()->user()->kode_jabatan);
        try {
            $temp = DB::select('budget.dbo.sp_add_requestbudget \'' . auth()->user()->nik . '\',\'' . auth()->user()->name . '\',\'' . $request->kode_group . '\',\'' . auth()->user()->kode_jabatan . '\',\'' . $request->keterangan . '\',' . $request->category_request . '');
            $data = $temp[0];
            if ($data->hasil == "gagal") {
                return response()->json(['errors' => $data->pesanGagal]);
            } else {
                DB::rollback();
                return response()->json(['success' => $data->hasil]);
            }
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        //abort_unless(\Gate::allows('approval_document_access'), 403);
        $nik = auth()->user()->nik;
        $kode_dept = auth()->user()->kode_departemen;
        $kode_group = auth()->user()->kode_group;
        $kode_jabatan = auth()->user()->kode_jabatan;
        $laststatus = DB::select('select periode, kode_group, tahun, code_category, last_status from budget.dbo.tr_budget_request_p where request_id = \'' . $id . '\'');
        $status = $laststatus[0]->last_status;
        $periode = $laststatus[0]->periode;
        $tahun = $laststatus[0]->tahun;
        $key = 'value_' . $periode;
        $cek  = DB::select('select YEAR(created_at) as tahun From BUDGET.dbo.tr_budget_request_p where request_id = \'' . $id . '\'');

        // if ($laststatus[0]->code_category == 'REF') {
        $data_coa = DB::select('select budget_id, concat(coa, \' - \', description) as deskripsi 
                from budget.dbo.tr_budget_new where kode_group = \'' . $laststatus[0]->kode_group . '\' and [year] = ' . $cek[0]->tahun . '');
        $data_bulan = DB::select('select * from budget.dbo.dt_periode order by id asc');
        // } else {
        //     $data_coa = DB::select('select budget_id, concat(coa, \' - \', description) as deskripsi 
        //         from budget.dbo.tr_budget_new where kode_group = \'' . $laststatus[0]->kode_group . '\' and ' . $key . ' > 0 and coa not in (\'611101\',\'611102\',\'611105\')');
        // }
        // dd($laststatus[0]->kode_group);
        if ($status != "open") {
            $temp = DB::select('select  * from budget.dbo.tr_budget_request_p where request_id=\'' . $id . '\' and current_group=\'' . $laststatus[0]->kode_group . '\'');
        } else {
            $temp = DB::select('select * from budget.dbo.tr_budget_request_p where request_id=\'' . $id . '\' and nik=\'' . $nik . '\' and last_status=\'open\'');
        }

        if (count($temp) <= 0) {
            abort(403);
        }

        $data = $temp[0];

        $temp_alasan = DB::select('select top 1 alasan, (select name from users where nik=updated_by) as nama 
        from budget.dbo.tr_budget_status_p
        where request_id=\'' . $id . '\' and alasan is not null order by id desc');
        if (count($temp_alasan) <= 0) {
            $alasan = NULL;
        } else {
            $alasan = $temp_alasan[0];
        }
        $data_category_file = DB::select('select category_name from approval.dbo.category_file order by category_name');


        return view('budget.anggaran.edit', compact('data_coa', 'data', 'nik', 'alasan', 'data_bulan', 'data_category_file', 'alasan'));
    }
    public function update(Request $request)
    {
        $id         = $request->id;
        $keterangan =  $request->keterangan;
        $budget_id = $request->budget_id;
        $nrequest = (!empty($request->nilai_request) ? $request->nilai_request : '0,00');
        $nrealisasi = (!empty($request->nilai_realisasi) ? $request->nilai_realisasi : '0,00');
        $nilaireq = str_replace('.', '', $nrequest);
        $nilai_request = str_replace(',', '.', $nilaireq);
        $nilaireal = str_replace('.', '', $nrealisasi);
        $nilai_realisasi = str_replace(',', '.', $nilaireal);
        $category = $request->category;
        $bulan = $request->periode;
        //dd($request->last_status);
        if ($request->last_status == "approval_manager" or $request->last_status == "approval_acc") {
            $aksi = "proses";
        } else {
            $aksi = "edit";
        }
        //dd($request->last_status);

        try {
            // if($category == 'BGN'){
            //generatein cariin kode anggarannya berdasarkan periode dan budget_id nya
            $temp = DB::select('budget.dbo.sp_update_anggaran \'' . $id . '\',\'' . auth()->user()->nik . '\',\'' . $keterangan . '\',\'' . $budget_id . '\',\'' . $nilai_request . '\',\'' . $nilai_realisasi . '\',\'' . $bulan . '\' ');
            $data = $temp[0];
            if ($data->hasil == "gagal") {

                return redirect('budget/anggaran/' . $id . '/' . $aksi)->withErrors(['errors', 'gagal update Voucher Anggaran']);
            } else {

                return redirect('budget/anggaran/' . $id . '/' . $aksi)->withSuccess('berhasil update Voucher Anggaran');
            }
            // }
        } catch (\Exception $e) {
            return redirect('budget/anggaran/' . $id . '/' . $aksi)->withErrors(['errors', $e->getMessage()]);
        }
    }

    public function submitPermohonan(Request $request)
    {
        $id             = $request->id;
        $signature      = $request->signature;
        $nik            = auth()->user()->nik;
        $nama           = auth()->user()->name;
        $group           = auth()->user()->kode_group;

        try {
            //cek apakah id kabontambahan
            // $cek = DB::select('select code_category from budget.dbo.tr_budget_request where id =\''.$id.'\'');
            // kalo kbr, cek dulu ada kbt yg statusnya blom closed?
            // if ($cek[0]->document_type == 'kbr'){
            //     $cekkbt = DB::select('select a.id from approval.dbo.document_master a where 
            //     a.id = (select isnull(document_kbt,0) from approval.dbo.log_kasbon_realisasi where document_kbr = \''.$id.'\') 
            //     and a.last_status not in(\'closed\',\'cancel\')');
            //     if(count($cekkbt) > 0){
            //         return response()->json(['errors' => "Tidak Submit, Selama Kasbon Tambahan Belum Realisasi"]);
            //     }
            // }
            $temp = DB::select('budget.dbo.sp_submit_request \'' . $id . '\',\'' . $group . '\',\'' . $signature . '\',\'' . $nik . '\'');
            $data = $temp[0];
            if ($data->hasil == "ok") {

                //cek dulu apakah habis dari reject
                $cek = DB::select('select count(id) as jum from budget.dbo.tr_budget_status where request_id=\'' . $id . '\'
                and last_reject is not null');

                // if ($cek[0]->jum == 0) {
                //kirim e-mail submit
                //$hasil_email = $this->submitMail($id);

                //if($hasil_email == "ok"){
                return response()->json(['success' => "Berhasil submit"]);
                //}else{
                //    return response()->json(['success' => "Berhasil submit tapi gagal mengirim email -> ".$hasil_email]);
                //}
                // } else {
                //kirim e-mail setelah reject
                //$hasil_email = $this->submitAfterRejectMail($id);

                //if($hasil_email == "ok"){
                return response()->json(['success' => "Berhasil submit kembali setelah reject dan mengirim email"]);
                //}else{
                //    return response()->json(['success' => "Berhasil submit kembali setelah reject tapi gagal mengirim email -> ".$hasil_email]);
                //}
                // }
            } else {
                return response()->json(['errors' => $data->hasil]);
            }
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function getDataProses($id)
    {
        $nik = auth()->user()->nik;
        $kode_group = auth()->user()->kode_group;
        $kode_jabatan = auth()->user()->kode_jabatan;
        $link_cetak = "budget/reportanggaran/" . $id . "/print";
        $kodegroup = substr(auth()->user()->kode_group, 0, 3);

        $temp = DB::select('select a.request_id, a.nama, a.nik, a.kode_group, a.code_category, concat(b.code_category,\' - \',b.category_request) as category, upper(a.periode) as periode,
        a.tahun,a.budget_id, CONCAT(c.coa,\' - \',c.description) as coa_budget, a.nilai_request, a.nilai_realisasi, a.keterangan, a.last_status
        from budget.dbo.tr_budget_request_p a 
        left join budget.dbo.dt_category_request b on a.code_category = b.code_category 
        left join budget.dbo.tr_budget_new c on a.budget_id = c.budget_id where a.request_id=\'' . $id . '\'
        and a.current_group like \'%' . $kodegroup . '%\'');
        if (count($temp) <= 0) {
            abort(403);
        }

        $data = $temp[0];

        return view('budget.anggaran.proses', compact('data', 'nik', 'link_cetak', 'kode_group'));
    }

    public function getDataRealisasi($id)
    {
        $nik = auth()->user()->nik;
        $kode_group = auth()->user()->kode_group;
        $kode_jabatan = auth()->user()->kode_jabatan;
        $link_cetak = "budget/reportanggaran/" . $id . "/print";
        $kodegroup = substr(auth()->user()->kode_group, 0, 3);

        $temp = DB::select('select a.id, a.nama, a.nik, a.kode_group, concat(b.code_category,\' - \',b.category_request) as category, upper(a.periode) as periode,
        a.tahun, CONCAT(c.coa,\' - \',c.description) as coa_budget, a.nilai_request, a.nilai_realisasi, a.keterangan 
        from budget.dbo.tr_budget_request a 
        left join budget.dbo.dt_category_request b on a.code_category = b.code_category 
        left join budget.dbo.tr_budget c on a.budget_id = c.budget_id where a.id=\'' . $id . '\'
        and a.current_group like \'%' . $kodegroup . '%\'');
        if (count($temp) <= 0) {
            abort(403);
        }

        $data = $temp[0];

        return view('budget.anggaran.realisasi', compact('data', 'nik', 'link_cetak', 'kode_group'));
    }

    public function cancelPermohonan(Request $request)
    {
        $id             = $request->id;
        $alasan         = $request->alasan;
        $nik            = auth()->user()->nik;

        try {
            $temp = DB::select('budget.dbo.sp_cancel_anggaran \'' . $id . '\',\'' . $alasan . '\',\'' . $nik . '\'');
            $data = $temp[0];
            if ($data->hasil == "ok") {
                //kirim e-mail cancel
                //$hasil_email = $this->cancelMail($id);

                //if($hasil_email == "ok"){
                return response()->json(['success' => "Berhasil cancel dan mengirim email"]);
                //}else{
                //    return response()->json(['success' => "Berhasil cancel tapi gagal mengirim email -> ".$hasil_email]);
                //}
            } else {
                return response()->json(['errors' => $data->hasil]);
            }
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function dataBudget($id)
    {
        $databudget = DB::select('select kode_group, periode, tahun from budget.dbo.tr_budget_request where id =\'' . $id . '\'');
        $periode = $databudget[0]->periode;
        $tahun = $databudget[0]->tahun;
        $kodegroup = $databudget[0]->kode_group;

        $where = 'where 1=1 ';
        $query = '';
        $kvalue = 'a.value_' . $periode;
        $kproses = 'a.progress_' . $periode;
        $kreal = 'a.real_' . $periode;
        $ksisa = 'a.sisa_' . $periode;
        $query .= ', UPPER(\'' . $periode . '\') as periode, ' . $kvalue . ' as jum_budget, ' . $kproses . ' as jum_proses, ' . $kreal . ' as jum_realisasi, ' . $kvalue . ' - (isnull(' . $kproses . ',0) + isnull(' . $kreal . ',0)) as jum_sisa';
        $where .= ' and kode_group = \'' . $kodegroup . '\'';
        $where .= ' and year = \'' . $tahun . '\'';
        $query .= ', UPPER(\'' . $tahun . '\') as tahun ';

        $data = DB::select('
        select * from
        (
            SELECT \'Q1\' as periode, year as tahun, budget_id, kode_group, coa,description, value_q1 as nilai, progress_q1 as progress, 
            real_q1 as realisasi, isnull(value_q1,0) - (isnull(progress_q1,0) + isnull(real_q1,0)) as sisa
            FROM budget.dbo.tr_budget where kode_group = \'' . $kodegroup . '\'
            union all
            SELECT \'Q2\' as periode, year as tahun, budget_id, kode_group, coa,description, value_q2 as nilai, progress_q2 as progress, 
            real_q2 as realisasi, isnull(value_q2,0) - (isnull(progress_q2,0) + isnull(real_q2,0)) as sisa
            FROM budget.dbo.tr_budget where kode_group = \'' . $kodegroup . '\'
            union all
            SELECT \'Q3\' as periode, year as tahun,  budget_id, kode_group, coa,description, value_q3 as nilai, progress_q3 as progress, 
            real_q3 as realisasi, isnull(value_q3,0) - (isnull(progress_q3,0) + isnull(real_q3,0)) as sisa
            FROM budget.dbo.tr_budget where kode_group = \'' . $kodegroup . '\'
            union all
            SELECT \'Q4\' as periode, year as tahun, budget_id, kode_group, coa,description, value_q4 as nilai, progress_q4 as progress, 
            real_q4 as realisasi, isnull(value_q4,0) - (isnull(progress_q4,0) + isnull(real_q4,0)) as sisa
            FROM budget.dbo.tr_budget where kode_group = \'' . $kodegroup . '\'
        ) as sumber
        where sumber.sisa != 0
        ');

        return DataTables::of($data)
            ->make(true);
    }

    public function dataRealisasi($id)
    {
        $data = DB::select('
        select a.document_id, b.nama_tujuan, a.keterangan, format(c.tanggal_realisasi,\'dd-MMM-yyyy\') as tglrealisasi, 
        REPLACE(FORMAT(a.pemakaian, \'N\', \'en-us\'), \'.00\', \'\') as jmlpemakaian, a.pemakaian from approval.dbo.document_budget a
left join APPROVAL.dbo.document_digital b on a.document_id = b.document_id 
left join FINANCE.dbo.realisasi_document c on a.document_id = c.document_id
where a.kode_anggaran = \'' . $id . '\'');

        return DataTables::of($data)
            ->make(true);
    }
    public function rejectPermohonan(Request $request)
    {
        $id             = $request->id;
        $alasan         = $request->alasan;
        $nik            = auth()->user()->nik;

        try {
            $temp = DB::select('budget.dbo.sp_reject_permohonan \'' . $id . '\',\'' . $alasan . '\',\'' . $nik . '\'');
            $data = $temp[0];
            if ($data->hasil == "ok") {
                //kirim e-mail reject
                //$hasil_email = $this->rejectMail($id);

                //if($hasil_email == "ok"){
                return response()->json(['success' => "Berhasil reject dan mengirim email"]);
                //}else{
                //return response()->json(['success' => "Berhasil reject tapi gagal mengirim email -> ".$hasil_email]);
                //}
            } else {
                return response()->json(['errors' => $data->hasil]);
            }
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function approvePermohonan(Request $request)
    {
        $id             = $request->id;
        $signature      = $request->signature;
        $notes          = $request->notes;
        $nik            = auth()->user()->nik;

        try {
            //cek apakah id kabontambahan
            //$cek = DB::select('select document_type from approval.dbo.document_master where id =\''.$id.'\'');
            // kalo kbr, cek dulu ada kbt yg statusnya blom closed?
            //if ($cek[0]->document_type == 'kbr'){
            //$cekkbt = DB::select('select a.id from approval.dbo.document_master a where 
            //a.id = (select isnull(document_kbt,0) from approval.dbo.log_kasbon_realisasi where document_kbr = \''.$id.'\') 
            //and a.last_status not in (\'closed\',\'cancel\')');
            //if(count($cekkbt) > 0){
            //return response()->json(['errors' => "Tidak bisa approve, Outstanding ini punya Kasbon Tambahan yg belum realisasi"]);
            //}
            //}

            $temp = DB::select('budget.dbo.sp_approve_budget_2024 \'' . $id . '\',\'' . $signature . '\',\'' . $notes . '\',\'' . $nik . '\'');
            $data = $temp[0];
            if ($data->hasil == "ok") {
                //kirim e-mail approve
                //$hasil_email = $this->approveMail($id);

                //if($hasil_email == "ok"){
                return response()->json(['success' => "Berhasil approve dan mengirim email"]);
                //}else{
                //return response()->json(['success' => "Berhasil approve tapi gagal mengirim email -> ".$hasil_email]);
                //}
            } else {
                return response()->json(['errors' => $data->hasil]);
            }
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function dataFile($id)
    {
        $data = DB::select('select A.id, A.request_id, A.nama_file, A.keterangan,
        A.created_at, A.created_by, B.last_status
        from budget.dbo.request_budget_file A
        join budget.dbo.tr_budget_request_p B on A.request_id=B.request_id
        where A.request_id=\'' . $id . '\' order by A.keterangan desc');

        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                $url = asset('/file/' . $data->nama_file);
                if ($data->last_status == "open") {
                    $button = '<a href="' . $url . '" target="_blank" class="btn btn-primary btn-sm">Download</a>';
                    $button .= '&nbsp<button type="button" name="delete_file" id="' . $data->nama_file . '" class="delete_file btn btn-danger btn-sm">Delete</button>';
                } else {
                    $button = '<a href="' . $url . '" target="_blank" class="btn btn-primary btn-sm">Download</a>';
                }

                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function saveFile(Request $request)
    {
        request()->validate([
            'file'  => 'required|mimetypes:application/pdf,image/jpeg,text/csv,application/vnd.ms-word,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|max:2024',
        ]);

        $id             = $request->id;
        $file           = $request->file;
        $keterangan     = $request->keterangan_file;
        $nik            = auth()->user()->nik;

        $ori_file = $file->getClientOriginalName();
        $filename = pathinfo($ori_file, PATHINFO_FILENAME);

        $tgl = date('Ymd_His');
        $nama_file = $filename . '_' . $id . '_' . $tgl . '.' . $file->getClientOriginalExtension();

        try {
            $temp = DB::select('budget.dbo.sp_add_anggaran_file \'' . $id . '\',\'' . $keterangan . '\',\'' . $nik . '\',\'' . $nama_file . '\'');
            $data = $temp[0];
            if ($data->hasil == "ok") {
                $file->move('file', $nama_file);
                return response()->json(['success' => $data->hasil]);
            } else {
                return response()->json(['errors' => $data->hasil]);
            }
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }
    public function deleteFile(Request $request)
    {
        DB::begintransaction();
        try {
            DB::table('budget.dbo.request_budget_file')
                ->where('nama_file', $request->nama_file)
                ->delete();
            DB::commit();
            //hapus file lamanya
            $path = public_path('file/' . $request->nama_file);
            if (File::exists($path)) {
                File::delete($path);
            }
            return response()->json(['success' => "OK"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['errors' => $e->getMessage()]);
        }
    }
}
