<?php

namespace App\Http\Controllers\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use DataTables;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        //abort_unless(\Gate::allows('library_peminjaman_access'), 403);
        $bulan = (!empty($_GET["bulan"])) ? ($_GET["bulan"]) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        if($request->ajax())
        {

            if($bulan == ''){
                $where = '';
            }else{
                $where = ' and month(A.created_at)=\''.$bulan.'\'';
            }

            $data = DB::select('
            select A.no_peminjaman, A.nik_peminjam, A.nama_peminjam, 
            format(A.tgl_pinjam,\'dd-MMM-yyyy\') as tgl_pinjam, 
            format(A.tgl_kembali,\'dd-MMM-yyyy\') as tgl_kembali, 
            A.tipe_peminjaman, A.status_pinjam
            from elibrary.dbo.peminjaman_master A
            where A.nik_peminjam= \''.$nik.'\''.$where.'
            order by A.tgl_kembali asc');
            
            return DataTables::of($data)
                    ->addColumn('action', function($data){
                        $button = '';
                        //if($data->last_status == "open" || $data->last_status == "closed"){
                        if($data->status_pinjam == "open"){
                            $button .= '<a href="peminjaman/'.$data->no_peminjaman.'/edit">Edit</a>';
                        }else{
                            if(Str::contains($data->current_jab,  ['MS','SP','MG','MA'])){
                                $button .= '<a href="peminjaman/'.$data->no_peminjaman.'/proses">Proses</a>';
                            }
                        }
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('library.peminjaman.index',compact('data','nik','name'));
    }

    public function store(Request $request)
    {
        try{
            $temp = DB::select('elibrary.dbo.sp_add_peminjaman \''.auth()->user()->nik.'\',\''.auth()->user()->name.'\',\''.$request->tipe_pinjam.'\',\''.$request->keterangan.'\'');
            $data = $temp[0];
            if ($data->hasil == "gagal"){
                return response()->json(['errors' => 'gagal create peminjaman']);
            }else{
                DB::rollback();
                return response()->json(['success' => $data->hasil]);
            }
        }
        catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }
}
