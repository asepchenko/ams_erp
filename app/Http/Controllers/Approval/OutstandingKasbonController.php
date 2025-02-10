<?php

namespace App\Http\Controllers\Approval;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator, Redirect, Response, File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use DataTables;
use Illuminate\Support\Str;

class OutstandingKasbonController extends Controller
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
        $where = '';
        if ($request->ajax()) {
            if ($bulan == '') {
                $where .= '';
            } else {
                $where .= ' and month(A.created_at)=\'' . $bulan . '\'';
            }
            if ($dept == 'MDFOB') {
                $where .= ' and A.current_dept in(\'MDFOB\',\'MDP\')';
            } elseif ($dept == 'ANALIS-DC')
                $where .= ' and A.current_dept in(\'ANALIS-DC\',\'IC\')';
            else {
                $where .= ' and left(A.current_dept,3) like (select left(Z.kode_departemen,3) from users Z where Z.nik=\'' . $nik . '\')';
            }
            $data = DB::select('
            select \'\' as pilih, case when A.no_document is null then A.id else A.no_document end as id,
		    document_kb = (select case when x.no_document is null then x.id 
			else x.no_document end from approval.dbo.document_master x where x.id = d.document_kb),
			document_kbt = (select case when y.no_document is null then y.id 
			else y.no_document end from approval.dbo.document_master y where y.id = d.document_kbt),
            A.nik, C.priority_name, A.nama, A.kode_departemen, A.current_jab, A.keterangan, A.last_status, 
            format(A.created_at,\'dd-MMM-yyyy HH:mm\') as created_at,
            format(approval.dbo.GET_WEEKDAY(A.created_at,3),\'dd-MMM-yyyy HH:mm\') as dateline,
            case when GETDATE() <= approval.dbo.GET_WEEKDAY(A.created_at,3) then \'Normal\'
            else \'Late\'
            end as status,
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
            left join approval.dbo.log_kasbon_realisasi d on a.id = d.document_kbr
            where A.document_type = \'kbr\' and A.current_jab like \'%' . $jab . '%\'' . $where . '
            group by A.no_document, A.id, d.document_kb, d.document_kbt, A.nik, A.nama, C.priority_name, A.kode_departemen, A.current_jab, A.keterangan, A.last_status, A.created_at 
            order by A.created_at desc');

            return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $button = '';
                    //if($data->last_status == "open" || $data->last_status == "closed"){
                    if ($data->last_status == "open") {
                        $button .= '<a href="outstanding-kasbon/' . $data->id . '/edit">Edit</a>';
                    } else {
                        if (Str::contains($data->current_jab,  ['MS', 'SP', 'MG', 'MA'])) {
                            $button .= '<a href="outstanding-kasbon/' . $data->id . '/proses">Proses</a>';
                        }
                    }
                    return $button;
                })
                ->addColumn('bdocument_kb', function ($data) {
                    $buttonkb = '';
                    //if($data->last_status == "open" || $data->last_status == "closed"){
                    $buttonkb .= '<a href="report/' . $data->document_kb . '/print" target="_blank">' . $data->document_kb . '</a>';

                    return $buttonkb;
                })
                ->addColumn('bdocument_kbt', function ($data) {
                    $buttonkbt = '';
                    if ($data->document_kbt != "tidak ada") {
                        $buttonkbt .= '<a href="report/' . $data->document_kbt . '/print" target="_blank">' . $data->document_kbt . '</a>';
                    } else {
                        $buttonkbt .= '';
                    }
                    return $buttonkbt;
                })
                ->rawColumns(['action', 'bdocument_kb', 'bdocument_kbt'])
                ->make(true);
        }

        return view('approval.outstanding-kasbon.index', compact('bulan', 'nik', 'name', 'data_prioritas', 'dept'));
    }

    public function getproses()
    {
        $bulan = (!empty($_GET["bulan"])) ? ($_GET["bulan"]) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen;
        $jab = auth()->user()->kode_jabatan;
        $data = DB::select('
            select A.id, document_kb = (select case when x.no_document is null then x.id 
			else x.no_document end from approval.dbo.document_master x where x.id = d.document_kb),
			document_kbt = (select case when x.no_document is null then x.id 
			else x.no_document end from approval.dbo.document_master x where x.id = d.document_kbt),
             A.nik, C.priority_name, A.nama, A.kode_departemen, A.current_jab, A.keterangan, A.last_status, 
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
			left join approval.dbo.log_kasbon_realisasi d on a.id = d.document_kbr
            where A.nik=\'' . $nik . '\'
            and A.document_type = \'kbr\' 
            and A.last_status not in (\'closed\',\'open\',\'cancel\')
            group by A.id, d.document_kb, d.document_kbt, A.nik, A.nama, C.priority_name, A.kode_departemen, A.current_jab, A.keterangan, A.last_status, A.created_at 
            order by A.created_at desc');

        return DataTables::of($data)
            ->addColumn('bpdocument_kb', function ($data) {
                $button = '';
                $button .= '<a href="report/' . $data->document_kb . '/print" target="_blank">' . $data->document_kb . '</a>';

                return $button;
            })

            ->addColumn('bpdocument_kbt', function ($data) {
                $buttonkbt = '';
                if ($data->document_kbt != "tidak ada") {
                    $buttonkbt .= '<a href="report/' . $data->document_kbt . '/print" target="_blank">' . $data->document_kbt . '</a>';
                } else {
                    $buttonkbt .= '';
                }
                return $buttonkbt;
            })

            ->rawColumns(['bpdocument_kb', 'bpdocument_kbt'])
            ->make(true);
    }

    public function edit($id)
    {
        //abort_unless(\Gate::allows('approval_document_access'), 403);
        $nik = auth()->user()->nik;
        $kode_dept = auth()->user()->kode_departemen;
        $kode_jabatan = auth()->user()->kode_jabatan;

        $data_category_document = DB::select('select replace(concat(kode_category,\' - \',
        nama_category),\'_\',\' \') as nama_category, kode_category
        from approval.dbo.category order by kode_category');
        $data_category_file = DB::select('select category_name from approval.dbo.category_file order by category_name');
        $data_category_program = DB::select('select kode_category, concat(kode_category,\' - \', nama_category) as nama_category 
        from approval.dbo.category_program where is_aktif = 1');
        $data_bank = DB::select('select kode_bank from dt_bank order by kode_bank');
        $data_matauang = DB::select('select currency_type from dt_currency where is_aktif=\'1\' order by currency_type');

        $cekkbt = DB::select('select count(document_kbt) as adakbt from approval.dbo.log_kasbon_realisasi where document_kbr =\'' . $id . '\'');
        $kbt = $cekkbt[0];
        $temp = DB::select('select a.id, b.document_kb, a.nik, a.nama, a.kode_departemen, a.keterangan, a.last_status, a.created_at, a.created_by,
        (select sum(z.jumlah) from approval.dbo.document_digital z where z.document_id=A.id and z.kode_category=\'VM\') as jum_kasbon,
        b.jumlah_realisasi as jum_realisasi, 
        (select sum(z.jumlah) from approval.dbo.document_digital z where z.document_id=A.id and z.kode_category=\'VM\') - b.jumlah_realisasi as sisa 
        from approval.dbo.document_master a left join approval.dbo.log_kasbon_realisasi b on a.id = b.document_kbr 
        where a.id=\'' . $id . '\' and a.nik=\'' . $nik . '\' and a.last_status=\'open\'');

        if (count($temp) <= 0) {
            abort(403);
        }

        $data = $temp[0];

        $temp_alasan = DB::select('select top 1 alasan, (select name from users where nik=updated_by) as nama 
        from approval.dbo.document_status 
        where document_id=\'' . $id . '\' and alasan is not null order by id desc');
        if (count($temp_alasan) <= 0) {
            $alasan = NULL;
        } else {
            $alasan = $temp_alasan[0];
        }

        return view('approval.outstanding-kasbon.edit', compact('kbt', 'data_category_program', 'data', 'nik', 'data_matauang', 'data_category_document', 'data_category_file', 'alasan', 'data_bank'));
    }

    public function getDataProses($id)
    {
        $nik = auth()->user()->nik;
        $kode_dept = auth()->user()->kode_departemen;
        $kode_jabatan = auth()->user()->kode_jabatan;
        $link_cetak = "approval/report/" . $id . "/print";
        $kodedpt = substr(auth()->user()->kode_departemen, 0, 3);

        $cekkbt = DB::select('select count(document_kbt) as adakbt from approval.dbo.log_kasbon_realisasi where document_kbr =\'' . $id . '\'');
        $kbt = $cekkbt[0];
        $temp = DB::select('select a.id, b.document_kb, a.nik, a.nama, a.kode_departemen, a.keterangan, a.last_status, a.created_at, a.created_by,
        (select sum(z.jumlah) from approval.dbo.document_digital z where z.document_id=A.id and z.kode_category=\'VM\') as jum_kasbon,
        b.jumlah_realisasi as jum_realisasi,
        (select sum(z.jumlah) from approval.dbo.document_digital z where z.document_id=A.id and z.kode_category=\'VM\') - b.jumlah_realisasi as sisa 
        from approval.dbo.document_master a left join approval.dbo.log_kasbon_realisasi b on a.id = b.document_kbr 
        where a.id=\'' . $id . '\'
        and A.current_dept like \'%' . $kodedpt . '%\'');
        if (count($temp) <= 0) {
            abort(403);
        }

        $data = $temp[0];

        $data_pu = DB::Select('select isnull(is_pu,\'0\') as pu from approval.dbo.document_digital 
        where document_id=\'' . $id . '\' and kode_category=\'VM\'');

        if (count($data_pu) > 0) {
            if ($data_pu[0]->pu == "0") {
                $punya = "unchecked";
            } else {
                $punya = "checked";
            }
        } else {
            $punya = "";
        }

        return view('approval.outstanding-kasbon.proses', compact('kbt', 'data', 'nik', 'link_cetak', 'punya', 'kode_dept'));
    }

    public function dataDigital($id)
    {
        $data = DB::select('select A.id, A.document_id, A.mata_uang, A.kode_category, A.no_digital, A.tanggal_bayar, A.nama_tujuan, 
        A.rek_tujuan, A.kode_bank, A.nama_rek, A.is_pu, REPLACE(FORMAT(A.jumlah, \'N\', \'en-us\'), \'.00\', \'\') as jumlah, A.no_ref, 
        A.keterangan, A.created_at, A.created_by, B.last_status
        from approval.dbo.document_digital A
        join approval.dbo.document_master B on A.document_id=B.id
        where A.document_id=\'' . $id . '\' order by A.kode_category desc');

        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                if ($data->last_status == "blabla") {
                    $button = '<button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-primary btn-sm">Edit</button>';
                    $button .= '&nbsp<button type="button" name="delete_digital" id="' . $data->id . '" class="delete_digital btn btn-danger btn-sm">Delete</button>';
                } else {
                    $button = '';
                }

                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function dataFile($id)
    {
        $data = DB::select('select A.id, A.document_id, A.category_name, A.nama_file, A.keterangan, 
        A.created_at, A.created_by, B.last_status
        from approval.dbo.document_file A
        join approval.dbo.document_master B on A.document_id=B.id
        where A.document_id=\'' . $id . '\' order by A.category_name desc');

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

    public function dataFileRealisasi($id)
    {
        //dapetin document id kasbonnya dulu.
        $docid = DB::select('select document_kb from approval.dbo.log_kasbon_realisasi where document_kbr =\'' . $id . '\'');
        $doc_id = $docid[0]->document_kb;
        $data = DB::select('select A.id, A.document_id, A.category_name, A.nama_file, A.keterangan, 
        A.created_at, A.created_by, B.last_status
        from approval.dbo.document_file A
        join approval.dbo.document_master B on A.document_id=B.id
        where A.document_id=\'' . $doc_id . '\' order by A.category_name desc');

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

    public function dataFileKB($id)
    {
        //dapetin document id kasbonnya dulu.
        $docid = DB::select('select document_kb from approval.dbo.log_kasbon_realisasi where document_kbt =\'' . $id . '\'');
        $doc_id = $docid[0]->document_kb;
        $data = DB::select('select A.id, A.document_id, A.category_name, A.nama_file, A.keterangan, 
        A.created_at, A.created_by, B.last_status
        from approval.dbo.document_file A
        join approval.dbo.document_master B on A.document_id=B.id
        where A.document_id=\'' . $doc_id . '\' order by A.category_name desc');

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

    public function dataFileKBT($id)
    {
        //dapetin document id kasbonnya dulu.
        $docid = DB::select('select isnull(document_kbt,\'none\') as document_kbt from approval.dbo.log_kasbon_realisasi where document_kbr =\'' . $id . '\'');
        $kbt_id = $docid[0]->document_kbt;
        $data = DB::select('select A.id, A.document_id, A.category_name, A.nama_file, A.keterangan, 
        A.created_at, A.created_by, B.last_status
        from approval.dbo.document_file A
        join approval.dbo.document_master B on A.document_id=B.id
        where A.document_id=\'' . $kbt_id . '\' order by A.category_name desc');

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

    public function update(Request $request)
    {
        $id         = $request->id;
        $keterangan =  $request->keterangan;
        $realisasi = str_replace('.', '', $request->jumrealisasi);
        $jumrealisasi = str_replace(',', '.', $realisasi);
        $kasbon = str_replace('.', '', $request->jumkasbon);
        $jumkasbon = str_replace(',', '.', $kasbon);
        $kode_anggaran = $request->kode_anggaran;
        //dd($jumrealisasi);

        try {
            $temp = DB::select('approval.dbo.sp_update_kasbon_realisasi \'' . $id . '\',\'' . auth()->user()->nik . '\',\'' . $keterangan . '\',' . $jumrealisasi . ',' . $jumkasbon . '');
            $data = $temp[0];
            if ($data->hasil == "ok") {
                return redirect('approval/outstanding-kasbon/' . $id . '/edit')->withSuccess('berhasil update document');
            } else {
                return redirect('approval/outstanding-kasbon/' . $id . '/edit')->withErrors($data->hasil);
            }
        } catch (\Exception $e) {
            return redirect('approval/outstanding-kasbon/' . $id . '/edit')->withErrors(['errors', $e->getMessage()]);
        }
    }
    public function addkbt(Request $request)
    {
        $id             = $request->id;
        $nik            = auth()->user()->nik;

        try {

            $temp = DB::select('approval.dbo.sp_add_kasbon_tambahan \'' . $id . '\'');
            $data = $temp[0];
            if ($data->hasil == "gagal") {
                return response()->json(['errors' => 'gagal create document,pastikan semua notes sudah dibaca atau hubungi IT']);
            } else {
                DB::rollback();
                return response()->json(['success' => $data->hasil]);
            }
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }
}
