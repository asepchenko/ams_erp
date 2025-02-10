USE [POS_SERVER]
GO
/****** Object:  StoredProcedure [dbo].[yud_report_sales_2020]    Script Date: 1/31/2020 11:16:06 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Author:		yudha T. Putra
-- Create date: 27 Jan 2020
-- Description:	Report Sales Achievement
-- exec yud_report_sales_2020 '01','RQ','all'
-- =============================================
ALTER PROCEDURE [dbo].[yud_report_sales_2020]
	@bulan as nvarchar(2),
	@brand as nvarchar(5),
	@store as nvarchar(10)
	--@tahun as nvarchar(4)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;

	if @brand = 'all' and @store = 'all'
		--report all
		begin
			select tanggal, 
			--REPLACE(FORMAT(sales_2018, '#,#', 'es-ES'), '.00', '') as sales_2018,
			isnull(REPLACE(sales_2018,'.00', ''),0) as sales_2018,
			isnull(REPLACE(sales_2019, '.00', ''),0) as sales_2019,
			concat(CAST(ROUND(([sales_2019]-[sales_2018]) * 100.0 / [sales_2019], 1) as INT), ' %') AS growth_sales_1,
			isnull(REPLACE(sales_2020, '.00', ''),0) as sales_2020,
			concat(CAST(ROUND(([sales_2020]-[sales_2019]) * 100.0 / [sales_2020], 1) as INT), ' %') AS growth_sales_2,
			isnull(REPLACE(sales_2018_qty, '.00', ''),0) as sales_2018_qty,
			isnull(REPLACE(sales_2019_qty, '.00', ''),0) as sales_2019_qty,
			concat(CAST(ROUND(([sales_2019_qty]-[sales_2018_qty]) * 100.0 / [sales_2019_qty], 1) as INT), ' %') AS growth_qty_1,
			isnull(REPLACE(sales_2020_qty, '.00', ''),0) as sales_2020_qty,
			concat(CAST(ROUND(([sales_2020_qty]-[sales_2019_qty]) * 100.0 / [sales_2020_qty], 1) as INT), ' %') AS growth_qty_2,
			isnull(REPLACE(sales_2018_jum, '.00', ''),0) as sales_2018_jum,
			isnull(REPLACE(sales_2019_jum, '.00', ''),0) as sales_2019_jum,
			concat(CAST(ROUND(([sales_2019_jum]-[sales_2018_jum]) * 100.0 / [sales_2019_jum], 1) as INT), ' %') AS growth_jum_1,
			isnull(REPLACE(sales_2020_jum, '.00', ''),0) as sales_2020_jum,
			concat(CAST(ROUND(([sales_2020_jum]-[sales_2019_jum]) * 100.0 / [sales_2020_jum], 1) as INT), ' %') AS growth_jum_2,
			isnull(REPLACE(b.store_target,'.00', ''),0) as store_target,
			concat(CAST(ROUND([sales_2020] * 100.0 / b.store_target, 1) as INT), ' %') AS ach,
			isnull(REPLACE(discount_2020, '.00', ''),0) as discount_2020,
			isnull(REPLACE(voucher_2020, '.00', ''),0) as voucher_2020
			from
			(
			select day(tanggal_transaksi) as tanggal, sum(grand_total) as sales, 'sales_2018' as tahun
			from tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2018
			--and id_store not in('777','888','MS001','01E','02E','03E','04E','7777','MS002')
			and left(id_store,1) = 'R'
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(grand_total) as sales,'sales_2019' as tahun
			from tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2019
			and left(id_store,1) = 'R'
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(grand_total) as sales,'sales_2020' as tahun
			from tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2020
			and left(id_store,1) = 'R'
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(B.qty) as sales, 'sales_2018_qty' as tahun
			from tr_sales_header A
			join tr_sales_detail B on A.id_tr_sales_header=B.id_tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2018
			and left(A.id_store,1) = 'R'
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(B.qty) as sales, 'sales_2019_qty' as tahun
			from tr_sales_header A
			join tr_sales_detail B on A.id_tr_sales_header=B.id_tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2019
			and left(A.id_store,1) = 'R'
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(B.qty) as sales, 'sales_2020_qty' as tahun
			from tr_sales_header A
			join tr_sales_detail B on A.id_tr_sales_header=B.id_tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2020
			and left(A.id_store,1) = 'R'
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, count(id_tr_sales_header) as sales, 'sales_2018_jum' as tahun
			from tr_sales_header A
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2018
			and left(id_store,1) = 'R'
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, count(id_tr_sales_header) as sales, 'sales_2019_jum' as tahun
			from tr_sales_header A
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2019
			and left(id_store,1) = 'R'
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, count(id_tr_sales_header) as sales, 'sales_2020_jum' as tahun
			from tr_sales_header A
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2020
			and left(id_store,1) = 'R'
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(B.net) as sales, 'discount_2020' as tahun
			from tr_sales_header A
			join tr_sales_detail B on A.id_tr_sales_header=B.id_tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2020
			and left(A.id_store,1) = 'R'
			and (B.deskripsi like '%PP-%' or B.deskripsi like '%DISC-%')
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(B.Bayar) as sales, 'voucher_2020' as tahun
			from tr_sales_header A
			join tr_sales_payment_detail B on A.id_tr_sales_header=B.id_tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2020
			and left(A.id_store,1) = 'R'
			and B.tipe_bayar='Voucher'
			group by day(tanggal_transaksi)

			) a
			pivot
			(
			  max(a.sales)
			  for a.tahun in ([sales_2018],[sales_2019],[sales_2020],[sales_2018_qty],[sales_2019_qty],[sales_2020_qty],[sales_2018_jum],[sales_2019_jum],[sales_2020_jum],[discount_2020],[voucher_2020])
			) piv
			join dt_Store_target_all b on tanggal=b.hari
			where b.bulan=@bulan and b.tahun='2020'
			order by tanggal
		end
	else if @brand <> 'all' and @store = 'all'
		--report by brand
		begin
			select tanggal, 
			--REPLACE(FORMAT(sales_2018, '#,#', 'es-ES'), '.00', '') as sales_2018,
			isnull(REPLACE(sales_2018,'.00', ''),0) as sales_2018,
			isnull(REPLACE(sales_2019, '.00', ''),0) as sales_2019,
			concat(CAST(ROUND(([sales_2019]-[sales_2018]) * 100.0 / [sales_2019], 1) as INT), ' %') AS growth_sales_1,
			isnull(REPLACE(sales_2020, '.00', ''),0) as sales_2020,
			concat(CAST(ROUND(([sales_2020]-[sales_2019]) * 100.0 / [sales_2020], 1) as INT), ' %') AS growth_sales_2,
			isnull(REPLACE(sales_2018_qty, '.00', ''),0) as sales_2018_qty,
			isnull(REPLACE(sales_2019_qty, '.00', ''),0) as sales_2019_qty,
			concat(CAST(ROUND(([sales_2019_qty]-[sales_2018_qty]) * 100.0 / [sales_2019_qty], 1) as INT), ' %') AS growth_qty_1,
			isnull(REPLACE(sales_2020_qty, '.00', ''),0) as sales_2020_qty,
			concat(CAST(ROUND(([sales_2020_qty]-[sales_2019_qty]) * 100.0 / [sales_2020_qty], 1) as INT), ' %') AS growth_qty_2,
			isnull(REPLACE(sales_2018_jum, '.00', ''),0) as sales_2018_jum,
			isnull(REPLACE(sales_2019_jum, '.00', ''),0) as sales_2019_jum,
			concat(CAST(ROUND(([sales_2019_jum]-[sales_2018_jum]) * 100.0 / [sales_2019_jum], 1) as INT), ' %') AS growth_jum_1,
			isnull(REPLACE(sales_2020_jum, '.00', '') ,0)as sales_2020_jum,
			concat(CAST(ROUND(([sales_2020_jum]-[sales_2019_jum]) * 100.0 / [sales_2020_jum], 1) as INT), ' %') AS growth_jum_2,
			isnull(REPLACE(b.store_target,'.00', ''),0) as store_target,
			concat(CAST(ROUND([sales_2020] * 100.0 / b.store_target, 1) as INT), ' %') AS ach,
			isnull(REPLACE(discount_2020, '.00', ''),0) as discount_2020,
			isnull(REPLACE(voucher_2020, '.00', ''),0) as voucher_2020
			from
			(
			select day(tanggal_transaksi) as tanggal, sum(grand_total) as sales, 'sales_2018' as tahun
			from tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2018
			and id_store like '%'+@brand+'%'
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(grand_total) as sales,'sales_2019' as tahun
			from tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2019
			and id_store like '%'+@brand+'%'
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(grand_total) as sales,'sales_2020' as tahun
			from tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2020
			and id_store like '%'+@brand+'%'
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(B.qty) as sales, 'sales_2018_qty' as tahun
			from tr_sales_header A
			join tr_sales_detail B on A.id_tr_sales_header=B.id_tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2018
			and A.id_store like '%'+@brand+'%'
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(B.qty) as sales, 'sales_2019_qty' as tahun
			from tr_sales_header A
			join tr_sales_detail B on A.id_tr_sales_header=B.id_tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2019
			and A.id_store like '%'+@brand+'%'
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(B.qty) as sales, 'sales_2020_qty' as tahun
			from tr_sales_header A
			join tr_sales_detail B on A.id_tr_sales_header=B.id_tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2020
			and A.id_store like '%'+@brand+'%'
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, count(id_tr_sales_header) as sales, 'sales_2018_jum' as tahun
			from tr_sales_header A
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2018
			and id_store like '%'+@brand+'%'
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, count(id_tr_sales_header) as sales, 'sales_2019_jum' as tahun
			from tr_sales_header A
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2019
			and id_store like '%'+@brand+'%'
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, count(id_tr_sales_header) as sales, 'sales_2020_jum' as tahun
			from tr_sales_header A
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2020
			and id_store like '%'+@brand+'%'
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(B.net) as sales, 'discount_2020' as tahun
			from tr_sales_header A
			join tr_sales_detail B on A.id_tr_sales_header=B.id_tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2020
			and A.id_store like '%'+@brand+'%'
			and (B.deskripsi like '%PP-%' or B.deskripsi like '%DISC-%')
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(B.Bayar) as sales, 'voucher_2020' as tahun
			from tr_sales_header A
			join tr_sales_payment_detail B on A.id_tr_sales_header=B.id_tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2020
			and A.id_store like '%'+@brand+'%'
			and B.tipe_bayar='Voucher'
			group by day(tanggal_transaksi)

			) a
			pivot
			(
			  max(a.sales)
			  for a.tahun in ([sales_2018],[sales_2019],[sales_2020],[sales_2018_qty],[sales_2019_qty],[sales_2020_qty],[sales_2018_jum],[sales_2019_jum],[sales_2020_jum],[discount_2020],[voucher_2020])
			) piv
			left join dt_Store_target_brand b on b.kode_brand=@brand 
			where tanggal=b.hari and b.bulan=@bulan and b.tahun='2020'
			or b.kode_brand is null
			order by tanggal
		end
	else if @brand = 'all' and @store <> 'all'
		--report by store
		begin
			select tanggal, 
			--REPLACE(FORMAT(sales_2018, '#,#', 'es-ES'), '.00', '') as sales_2018,
			isnull(REPLACE(sales_2018,'.00', ''),0) as sales_2018,
			isnull(REPLACE(sales_2019, '.00', ''),0) as sales_2019,
			concat(CAST(ROUND(([sales_2019]-[sales_2018]) * 100.0 / [sales_2019], 1) as INT), ' %') AS growth_sales_1,
			isnull(REPLACE(sales_2020, '.00', ''),0) as sales_2020,
			concat(CAST(ROUND(([sales_2020]-[sales_2019]) * 100.0 / [sales_2020], 1) as INT), ' %') AS growth_sales_2,
			isnull(REPLACE(sales_2018_qty, '.00', ''),0) as sales_2018_qty,
			isnull(REPLACE(sales_2019_qty, '.00', ''),0) as sales_2019_qty,
			concat(CAST(ROUND(([sales_2019_qty]-[sales_2018_qty]) * 100.0 / [sales_2019_qty], 1) as INT), ' %') AS growth_qty_1,
			isnull(REPLACE(sales_2020_qty, '.00', ''),0) as sales_2020_qty,
			concat(CAST(ROUND(([sales_2020_qty]-[sales_2019_qty]) * 100.0 / [sales_2020_qty], 1) as INT), ' %') AS growth_qty_2,
			isnull(REPLACE(sales_2018_jum, '.00', ''),0) as sales_2018_jum,
			isnull(REPLACE(sales_2019_jum, '.00', ''),0) as sales_2019_jum,
			concat(CAST(ROUND(([sales_2019_jum]-[sales_2018_jum]) * 100.0 / [sales_2019_jum], 1) as INT), ' %') AS growth_jum_1,
			isnull(REPLACE(sales_2020_jum, '.00', ''),0) as sales_2020_jum,
			concat(CAST(ROUND(([sales_2020_jum]-[sales_2019_jum]) * 100.0 / [sales_2020_jum], 1) as INT), ' %') AS growth_jum_2,
			isnull(REPLACE(b.store_target,'.00', ''),0) as store_target,
			concat(CAST(ROUND([sales_2020] * 100.0 / b.store_target, 1) as INT), ' %') AS ach,
			isnull(REPLACE(discount_2020, '.00', ''),0) as discount_2020,
			isnull(REPLACE(voucher_2020, '.00', ''),0) as voucher_2020
			from
			(
			select day(tanggal_transaksi) as tanggal, sum(grand_total) as sales, 'sales_2018' as tahun
			from tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2018
			and id_store = @store
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(grand_total) as sales,'sales_2019' as tahun
			from tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2019
			and id_store = @store
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(grand_total) as sales,'sales_2020' as tahun
			from tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2020
			and id_store = @store
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(B.qty) as sales, 'sales_2018_qty' as tahun
			from tr_sales_header A
			join tr_sales_detail B on A.id_tr_sales_header=B.id_tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2018
			and A.id_store = @store
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(B.qty) as sales, 'sales_2019_qty' as tahun
			from tr_sales_header A
			join tr_sales_detail B on A.id_tr_sales_header=B.id_tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2019
			and A.id_store = @store
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(B.qty) as sales, 'sales_2020_qty' as tahun
			from tr_sales_header A
			join tr_sales_detail B on A.id_tr_sales_header=B.id_tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2020
			and A.id_store = @store
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, count(id_tr_sales_header) as sales, 'sales_2018_jum' as tahun
			from tr_sales_header A
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2018
			and id_store = @store
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, count(id_tr_sales_header) as sales, 'sales_2019_jum' as tahun
			from tr_sales_header A
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2019
			and id_store = @store
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, count(id_tr_sales_header) as sales, 'sales_2020_jum' as tahun
			from tr_sales_header A
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2020
			and id_store = @store
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(B.net) as sales, 'discount_2020' as tahun
			from tr_sales_header A
			join tr_sales_detail B on A.id_tr_sales_header=B.id_tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2020
			and A.id_store = @store
			and (B.deskripsi like '%PP-%' or B.deskripsi like '%DISC-%')
			group by day(tanggal_transaksi)
			union all
			select day(tanggal_transaksi) as tanggal, sum(B.Bayar) as sales, 'voucher_2020' as tahun
			from tr_sales_header A
			join tr_sales_payment_detail B on A.id_tr_sales_header=B.id_tr_sales_header
			where month(tanggal_transaksi)=@bulan and year(tanggal_transaksi)=2020
			and A.id_store = @store
			and B.tipe_bayar='Voucher'
			group by day(tanggal_transaksi)

			) a
			pivot
			(
			  max(a.sales)
			  for a.tahun in ([sales_2018],[sales_2019],[sales_2020],[sales_2018_qty],[sales_2019_qty],[sales_2020_qty],[sales_2018_jum],[sales_2019_jum],[sales_2020_jum],[discount_2020],[voucher_2020])
			) piv
			left join dt_Store_target b on b.store_id=@store 
			where tanggal=b.hari and b.bulan=@bulan and b.tahun='2020'
			or b.store_id is null
			order by tanggal
		end
END
