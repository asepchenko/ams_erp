<?php

namespace App\Http\Controllers\Library;

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
        $category = DB::select('select kategori from elibrary.dbo.file_kategori order by kategori');
        $kategori = "all";
        $lokasi = "all";
        return view('library.file.index', compact('category','kategori','lokasi'));
    }

    public function dataMasterFile($kategori, $lokasi){
        $where = ' where 1=1 ';
        if($kategori == "all"){
            $where .= '';
        }else{
            $where .= ' and kategori=\''.$kategori.'\' ';
        }

        if($lokasi == "all"){
            $where .= '';
        }else{
            $where .= ' and lokasi=\''.$lokasi.'\' ';
        }

        $category = DB::select('select kategori from elibrary.dbo.file_kategori order by kategori');
        $datanya = DB::select('select id, kategori, tipe, jenis, lokasi, kode_departemen, 
        nama_file, keterangan, status,posisi,tempat,
        no_tempat,no_map,is_pinjam,nik_peminjam,ada_fisik
        from elibrary.dbo.file_master '.$where.'');
            
        return view('library.file.index', compact('datanya','category','kategori','lokasi'));
    }
}
?>