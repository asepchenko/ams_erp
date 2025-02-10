<?php

namespace App\Http\Controllers\Budget;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\BudgetImport;
use App\Imports\StoreBudgetImport;
use Validator, Redirect, Response, File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use DataTables;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class BudgetNewController extends Controller
{
    public function importExcel(Request $request)
    {
        // validasi
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);
        $file = $request->file('file');
        $nama_file = rand() . $file->getClientOriginalName();

        // upload ke dalam folder public
        $file->move('budget', $nama_file);

        // import data
        //Excel::import(new StoreBudgetImport, $request->file('file'));

        // Excel::import(new StoreBudgetImport, $request->file('file'));
        // dd($request->file);

        Excel::import(new StoreBudgetImport, public_path('/budget/' . $nama_file)); // $request->file('file'));
        //Excel::import(new StoreResiImport, $path);

        // notifikasi dengan session
        $request->session()->flash('sukses', 'Data Berhasil di Import');

        // alihkan halaman kembali
        // return redirect('/resi');
        return back();
    }
    public function test(Request $request)
    {
        //abort_unless(\Gate::allows('approval_document_access'), 403);
        $periode = (!empty($_GET["periode"])) ? ($_GET["periode"]) : ('');
        $tahun = (!empty($_GET["tahun"])) ? ($_GET["tahun"]) : ('');
        $kodegroup = (!empty($_GET["kodegroup"])) ? ($_GET["kodegroup"]) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen;
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group;
        $data_prioritas = DB::select('select id, priority_name from approval.dbo.document_priority where is_active=1 order by priority_name');

        if (\Gate::allows('budget_access_special')) {
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget  
            union select \'all\' as kode_groupstr order by kode_groupstr asc');
        } elseif ($nik == '00002158' or $nik == '80002825') {
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr 
            from budget.dbo.tr_budget where kode_group in (\'OPR\',\'BDV\',\'MARKETING\')) as x');
        } else {
            $temp_group = auth()->user()->kode_group;
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget where kode_group = \'' . $temp_group . '\') as x');
        }


        if ($request->ajax()) {
            $where = 'where 1=1 ';
            $query = '';
            if ($periode == 'all') {
                $where .= '';
                $query .= ', UPPER(\'' . $periode . '\') as periode, a.value_q1 + a.value_q2 + a.value_q3 + a.value_q4 as jum_budget, 
                    isnull(a.progress_q1,0) + isnull(a.progress_q2,0) + isnull(a.progress_q3,0) + isnull(a.progress_q4,0) as jum_proses, 
                    isnull(a.real_q1,0) + isnull(real_q2,0) + isnull(real_q3,0) + isnull(real_q4,0) as jum_realisasi, 
                    (a.value_q1 + a.value_q2 + a.value_q3 + a.value_q4) - 
                    ((isnull(a.progress_q1,0) + isnull(a.progress_q2,0) + isnull(a.progress_q3,0) + isnull(a.progress_q4,0)) + (isnull(a.real_q1,0) + isnull(real_q2,0) + isnull(real_q3,0) + isnull(real_q4,0))) as jum_sisa';
            } else {
                $kvalue = 'a.value_' . $periode;
                $kproses = 'a.progress_' . $periode;
                $kreal = 'a.real_' . $periode;
                $ksisa = 'a.sisa_' . $periode;
                $query .= ', UPPER(\'' . $periode . '\') as periode, ' . $kvalue . ' as jum_budget, ' . $kproses . ' as jum_proses, ' . $kreal . ' as jum_realisasi, ' . $kvalue . ' - (isnull(' . $kproses . ',0) + isnull(' . $kreal . ',0)) as jum_sisa';
            }
            if ($tahun == '') {
                $where .= ' and year = year(getdate())';
                $query .= ', year(getdate()) as tahun ';
            } else {
                $where .= ' and year = \'' . $tahun . '\'';
                $query .= ', UPPER(\'' . $tahun . '\') as tahun ';
            }
            if (\Gate::allows('budget_access_special') and $kodegroup == '') {
                $where .= '';
            } elseif (\Gate::allows('budget_access_special') and $kodegroup != '') {
                $where .= ' and kode_group = \'' . $kodegroup . '\'';
            } elseif ($kodegroup == '') {
                $where .= ' and kode_group = \'' . $temp_group . '\'';
            } else {
                $where .= ' and kode_group = \'' . $kodegroup . '\'';
            }


            $data = DB::select('select a.* from (select a.budget_id, a.kode_group, a.coa, a.description' . $query . ' from budget.dbo.tr_budget a ' . $where . '
                ) as sumber order by a.kode_group asc');

            // }
            //where A.current_dept=(select Z.kode_departemen from users Z where Z.nik=\''.$nik.'\')


            return DataTables::of($data)
                ->make(true);
        }
        return view('budget.budgetnew.index', compact('nik', 'name', 'data_prioritas', 'group', 'data_group', 'periode', 'tahun'));
    }

    public function index(Request $request)
    {
        //abort_unless(\Gate::allows('approval_document_access'), 403);
        $tahun = (!empty($_GET["tahun"])) ? ($_GET["tahun"]) : ('');
        $kodegroup = (!empty($_GET["kodegroup"])) ? ($_GET["kodegroup"]) : ('');
        $nik = auth()->user()->nik;
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen;
        $jab = auth()->user()->kode_jabatan;
        $group = auth()->user()->kode_group;
        $data_prioritas = DB::select('select id, priority_name from approval.dbo.document_priority where is_active=1 order by priority_name');

        if (\Gate::allows('budget_access_special')) {
            $data_group = DB::select('select distinct(kode_group) as kode_groupstr from budget.dbo.tr_budget  
            union select \'all\' as kode_groupstr order by kode_groupstr asc');
        } elseif ($nik == '80002825') {
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr 
            from budget.dbo.tr_budget where kode_group in (\'OPR\',\'BDV\',\'MARKETING\')) as x');
        } else {
            $temp_group = auth()->user()->kode_group;
            $data_group = DB::select('select x.kode_groupstr from (select distinct kode_group as kode_groupstr from budget.dbo.tr_budget where kode_group = \'' . $temp_group . '\') as x');
        }


        if ($request->ajax()) {
            $where2 = 'where 1=1 ';
            $where1 = ' ';
            if ($tahun == '') {
                $tahun = date("Y");
                $where1 .= ' and a.year = \'' . $tahun . '\'';
            } else {
                $where1 .= ' and a.year = \'' . $tahun . '\'';
            }
            if (\Gate::allows('budget_access_special') and $kodegroup == '') {
                $where1 .= '';
            } elseif (\Gate::allows('budget_access_special') and $kodegroup != '') {
                $where1 .= ' and a.kode_group = \'' . $kodegroup . '\'';
            } elseif ($nik == '80002825' and $kodegroup == '') {  //80002825
                $where1 .= ' and a.kode_group in (\'OPR\',\'BDV\',\'MARKETING\')';
            } elseif ($nik == '80002825' and $kodegroup != '') {
                $where1 .= ' and a.kode_group = \'' . $kodegroup . '\'';
            } elseif ($kodegroup == '') {
                $where1 .= ' and a.kode_group = \'' . $temp_group . '\'';
            } else {
                $where1 .= ' and a.kode_group = \'' . $kodegroup . '\'';
            }

            $data = DB::select('
                select * from
                budget.dbo.tr_budget_new a                
                where 1=1 ' . $where1 . '  order by a.kode_group, a.description');

            // }
            //where A.current_dept=(select Z.kode_departemen from users Z where Z.nik=\''.$nik.'\')


            return DataTables::of($data)
                // ->addColumn('btnprogress', function ($data) {
                //     $button = '';
                //     $button .= '<a href="reportdetail/search/' . $data->kode_group . '/' . $data->tahun . '/' . strtolower($data->periode) . '/' . $data->kode_anggaran . '/proses" target="_blank">Progress</a>';
                //     return $button;
                // })
                // ->addColumn('btnrealisasi', function ($data) {
                //     $button = '';
                //     $button .= '<a href="reportdetail/search/' . $data->kode_group . '/' . $data->tahun . '/' . strtolower($data->periode) . '/' . $data->kode_anggaran . '/closed" target="_blank">Realisasi</a>';
                //     return $button;
                // })
                // ->rawColumns(['btnprogress', 'btnrealisasi'])
                ->make(true);
        }
        return view('budget.budgetnew.index', compact('nik', 'name', 'data_prioritas', 'group', 'data_group', 'tahun'));
    }
    public function downloadExcel()
    {
        return response()->download(storage_path("app/public/Importbudget2024.xlsx"));
    }
}
