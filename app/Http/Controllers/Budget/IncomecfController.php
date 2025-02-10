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

class IncomecfController extends Controller
{
    public function index(Request $request)
    {
        //abort_unless(\Gate::allows('approval_document_access'), 403);
        $bulan = (!empty($_GET["bulan"])) ? ($_GET["bulan"]) : ('');
        $tahun = (!empty($_GET["tahun"])) ? ($_GET["tahun"]) : (date("Y"));
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen;
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group;
        // $tahun = date("Y");
        $komponen_income = DB::select('select * From budget.dbo.tr_income where tahun =' . $tahun . ' order by urutan asc');
        $data_bulan = DB::select('select * from budget.dbo.dt_periode');
        if ($request->ajax()) {
            // if ($bulan == '') {
            //     $where = '';
            // } else {
            //     $where = ' and month(a.created_at)=\'' . $bulan . '\'';
            // }
            $data = DB::select('select a.*, b.tahun, b.label_income, b.urutan,
            case when a.bulan = 1 then \'Januari\'
when a.bulan = 2 then \'Februari\'
when a.bulan = 3 then \'Maret\'
when a.bulan = 4 then \'April\'
when a.bulan = 5 then \'Mei\'
when a.bulan = 6 then \'Juni\'
when a.bulan = 7 then \'Juli\'
when a.bulan = 8 then \'Agustus\'
when a.bulan = 9 then \'September\'
when a.bulan = 10 then \'Oktober\'
when a.bulan = 11 then \'November\'
when a.bulan = 12 then \'Desember\'
end as label_bulan from budget.dbo.tr_income_detail a 
            left join budget.dbo.tr_income b on a.id_income = b.id 
            where aktif = 1
            order by urutan asc');
            // WHERE left(a.current_group,3) like (select left(z.kode_group,3) from users z where z.nik =\'' . $nik . '\')
            // and a.current_jab like \'%' . $jab . '%\'' . $where . '

            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = '';
                    //if($data->last_status == "open" || $data->last_status == "closed"){

                    $button = '<button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-primary btn-sm">Edit</button>';
                    $button .= '&nbsp<button type="button" name="delete_digital" id="' . $data->id . '" class="delete_digital btn btn-danger btn-sm">Delete</button>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('budget.incomecf.index', compact('bulan', 'nik', 'name', 'komponen_income', 'tahun', 'bulan', 'data_bulan'));
    }

    public function getkomponen($param)
    {
        $data = DB::select('select id as id_income, label_income from budget.dbo.tr_income where tahun =' . $param . '');
        // $dept = DB::select('select ID_DEPT from DT_DEPT WHERE KODE_DEPT = \'' . $param . '\'');
        // $iddept = $dept[0]->ID_DEPT;
        // $data = DB::table('DT_CLASS')
        //     ->where(['ID_DEPT' => $iddept])
        //     ->get();
        $options = array();
        foreach ($data as $row) {
            $options += array($row->id_income => $row->label_income);
        }
        return Response::json($options);
    }

    public function store(Request $request)
    {
        //dd(auth()->user()->kode_jabatan);
        try {
            $id_income = $request->komponen_income;
            $bulan = $request->bulan_budget;
            $budget = str_replace(',', '', $request->budget);
            $realisasi = str_replace(',', '', $request->realisasi);

            $temp = DB::select('budget.dbo.sp_add_income ' . $id_income . ', \'' . $bulan . '\',\'' . auth()->user()->nik . '\',' . $budget . ',' . $realisasi . '');
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

    public function update(Request $request)
    {
        //dd(auth()->user()->kode_jabatan);
        try {
            $id_income = $request->komponen_income;
            $bulan = $request->bulan_budget;
            $budget = str_replace(',', '', $request->budget);
            $realisasi = str_replace(',', '', $request->realisasi);
            $id = $request->id_detail;

            $temp = DB::select('budget.dbo.sp_update_income ' . $id . ', \'' . $bulan . '\',\'' . auth()->user()->nik . '\',' . $budget . ',' . $realisasi . '');
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

    public function editDataincome($id)
    {
        try {
            $temp = DB::select('select a.*, b.tahun, b.label_income, b.urutan,c.nama_bulan
            from budget.dbo.tr_income_detail a 
            left join budget.dbo.tr_income b on a.id_income = b.id
			left join budget.dbo.dt_periode c on a.bulan = c.id  where a.id=' . $id . '');
            $data_digital_edit = $temp[0];
            return response()->json(['success' => $data_digital_edit]);
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

        // if ($laststatus[0]->code_category == 'REF') {
        $data_coa = DB::select('select budget_id, concat(coa, \' - \', description) as deskripsi 
                from budget.dbo.tr_budget_new where kode_group = \'' . $laststatus[0]->kode_group . '\'');
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
            'file'  => 'required|mimetypes:application/pdf,image/jpeg,text/csv,application/vnd.ms-word,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|max:1024',
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
