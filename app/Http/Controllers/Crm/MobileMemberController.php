<?php

namespace App\Http\Controllers\Crm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect,Response;
use Illuminate\Support\Facades\DB;
use DataTables;

class MobileMemberController extends Controller
{
    public function index(Request $request)
    {
        //abort_unless(\Gate::allows('approval_document_access'), 403);
        //$bulan = (!empty($_GET["bulan"])) ? ($_GET["bulan"]) : (date('m'));
        $nik = auth()->user()->nik;

        if($request->ajax())
        {

            $data = DB::select('
            select A.id_member, B.nama, B.email, 
            format(A.first_login,\'dd-MMM-yyyy HH:mm\') as first_login,
            format(A.last_active,\'dd-MMM-yyyy HH:mm\') as last_active,
            latlong 
            from pos_server.dbo.dt_member_mobile_status A
            join pos_server.dbo.dt_member B on A.id_member=B.id_member
            order by A.last_active desc');
            
            return DataTables::of($data)
                    ->addColumn('action', function($data){
                        $button = '<a href="mobile-member/'.$data->id_member.'/detail">Detail</a>';
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('crm.mobile-member.index', compact('nik'));
    }

    public function detail($id)
    {
        //abort_unless(\Gate::allows('approval_document_access'), 403);
        $nik = auth()->user()->nik;

        $temp_profil = DB::select('select A.id_member, B.nama, B.email, 
        format(A.first_login,\'dd-MMM-yyyy HH:mm\') as first_login,
        format(A.last_active,\'dd-MMM-yyyy HH:mm\') as last_active,
        latlong, concat(B.alamat1, \' \', B.alamat2) as alamat,
        concat(\'["\',B.alamat1, \' \', B.alamat2, 0,\'",\',latlong,\']\') as lokasi, 
        format(B.tgllahir,\'dd-MMM-yyyy HH:mm\') as tgllahir, B.notelpon, B.email,
        format(B.tglmember,\'dd-MMM-yyyy HH:mm\') as tglmember, B.store_id as store_daftar
        from pos_server.dbo.dt_member_mobile_status A
        join pos_server.dbo.dt_member B on A.id_member=B.id_member
        where B.id_member=\''.$id.'\'');
        if(count($temp_profil) <= 0){
            abort(403);
        }

        $profil = $temp_profil[0];

        $temp_data = DB::select('select D.id_member, D.nama,
        isnull((D.total_point-D.total_redeem),0) as point, isnull(D.total_redeem,0) as total_redeem,
        total_trans_month, total_trans
        from(
        select A.id_member, A.nama,
        (select sum(Z.Point_Reward + Z.Bonus_Point_Reward + Z.Bonus_Tanggal_Bagus) from ams_store.dbo.dt_member_transaksi Z
        where Z.id_member=A.id_member COLLATE SQL_Latin1_General_CP1_CI_AS group by Z.id_member ) as total_point,
        (select sum(X.PointVoucher) from ams_store.dbo.dt_member_voucher X
        where X.id_member=A.id_member COLLATE SQL_Latin1_General_CP1_CI_AS group by X.id_member ) as total_redeem,
        (select count(Y.billing_code_id) from ams_Store.dbo.dt_member_transaksi Y
        where Y.id_member=A.id_member COLLATE SQL_Latin1_General_CP1_CI_AS 
        and month(Y.tanggal)=month(getdate()) and year(Y.tanggal)=year(getdate())) as total_trans_month,
        (select count(Y.billing_code_id) from ams_Store.dbo.dt_member_transaksi Y
        where Y.id_member=A.id_member COLLATE SQL_Latin1_General_CP1_CI_AS) as total_trans
        from pos_server.dbo.dt_member A
        where A.id_member=\''.$id.'\'
        ) as D');
        $data = $temp_data[0];

        $data_plu = DB::select('select B.plu, B.article, B.deskripsi, sum(B.qty) as total from ams_Store.dbo.dt_member_transaksi A
        join pos_server.dbo.tr_sales_detail B on A.billing_code_id=B.id_tr_sales_header COLLATE SQL_Latin1_General_CP1_CI_AS
        where A.id_member = \''.$id.'\'
        group by B.plu, B.article, B.deskripsi
        order by B.article');

        $data_transaksi = DB::select('select B.billing_code_id, 
        format(B.tanggal,\'dd-MMM-yyyy HH:mm\') as tanggal, B.Store_id,
        B.point_reward, B.bonus_point_reward, B.bonus_tanggal_bagus, isnull(C.name,\'\') as tgl_special, 
        isnull(D.nama_event,\'\') as nama_event,
        plu = STUFF((
            SELECT \',\' + md.plu + \' (\' + md.article +\')\'
            FROM pos_server.dbo.tr_sales_detail md
            WHERE B.billing_code_id = md.id_tr_sales_header COLLATE SQL_Latin1_General_CP1_CI_AS
            FOR XML PATH(\'\'), TYPE).value(\'.\', \'NVARCHAR(MAX)\'), 1, 1, \'\')
        from pos_server.dbo.dt_member A
        join ams_Store.dbo.dt_member_transaksi B on A.id_member=B.id_member COLLATE SQL_Latin1_General_CP1_CI_AS
        left join pos_server.dbo.dt_member_special_day C on B.id_dt_member_special_day=C.id_dt_member_special_day COLLATE SQL_Latin1_General_CP1_CI_AS
        left join pos_server.dbo.dt_member_special_case D on B.id_member_special_case=D.id_member_special_case COLLATE SQL_Latin1_General_CP1_CI_AS
        where A.id_member= \''.$id.'\'
        order by B.tanggal desc');

        return view('crm.mobile-member.detail', compact('profil','data','data_plu','data_transaksi','nik'));

    }
}