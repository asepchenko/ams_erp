<?php

namespace App\Http\Controllers\Crm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect,Response;
use Illuminate\Support\Facades\DB;
use DataTables;
use App\MemberStore;

class CustomerStoreController extends Controller
{
    public function index(Request $request)
    {
        //abort_unless(\Gate::allows('approval_document_access'), 403);
        $bulan = (!empty($_GET["bulan"])) ? ($_GET["bulan"]) : (date('m'));
        $nik = auth()->user()->nik;

        if($request->ajax())
        {
            /*$data = DB::select('
            select D.id_member, D.nama, D.alamat, D.notelpon, D.email, D.tgl_daftar, D.store_daftar,
            D.first_login, D.last_active, D.latlong, 
            isnull((D.total_point-D.total_redeem),0) as point, isnull(D.total_redeem,0) as total_redeem
            from(
            select A.id_member, A.nama, concat(A.alamat1,\' \',A.alamat2) as alamat,
            format(A.tgllahir,\'dd-MMM-yyyy\') as tgl_lahir, 
            A.notelpon, A.email, format(A.tglmember,\'dd-MMM-yyyy\') as tgl_daftar, A.tglmember,
            A.store_id as store_daftar,
            isnull(B.first_login,NULL) as first_login, B.last_active as last_active, 
            isnull(B.latlong,\'-\') as latlong,
            (select sum(Z.Point_Reward + Z.Bonus_Point_Reward + Z.Bonus_Tanggal_Bagus) from ams_store.dbo.dt_member_transaksi Z
            where Z.id_member=A.id_member COLLATE SQL_Latin1_General_CP1_CI_AS group by Z.id_member ) as total_point,
            (select sum(X.PointVoucher) from ams_store.dbo.dt_member_voucher X
            where X.id_member=A.id_member COLLATE SQL_Latin1_General_CP1_CI_AS group by X.id_member ) as total_redeem
            from pos_server.dbo.dt_member A
            left join pos_server.dbo.dt_member_mobile_status B on A.id_member=B.id_member
            ) as D
            order by D.tglmember desc');*/

            $data = DB::select('
            select A.id_member, A.nama, concat(A.alamat1,\' \',A.alamat2) as alamat,
            format(A.tgllahir,\'dd-MMM-yyyy\') as tgl_lahir, 
            A.notelpon, A.email, format(A.tglmember,\'dd-MMM-yyyy\') as tgl_daftar, A.tglmember,
            A.store_id as store_daftar
            from pos_server.dbo.dt_member A
            order by A.tglmember desc');
            
            return DataTables::of($data)
                    ->addColumn('action', function($data){
                        $button = '<a href="customerstore/'.$data->id_member.'/detail">Detail</a>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('crm.customerstore.index', compact('bulan','nik'));
    }
}