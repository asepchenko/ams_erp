USE [APPROVAL]
GO
/****** Object:  StoredProcedure [dbo].[sp_update_document_digital]    Script Date: 7/8/2020 10:34:07 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- exec sp_add_document_digital '2003310001', 'VM', 'YUDA', 12345678, 1000, '', 'TEST', '00003599'
ALTER PROCEDURE [dbo].[sp_update_document_digital]
	@id as nvarchar(32),
	@category as nvarchar(50),
	@tgl_bayar as datetime,
	@nama_tujuan as nvarchar(128), 
	@kode_bank as nvarchar(50),
	@nama_rek as nvarchar(128),
	@rek as nvarchar(50), 
	@jumlah as numeric(18,2), 
	@mata_uang as nvarchar(10),
	@no_ref as nvarchar(64),
	@keterangan as nvarchar(max),
	@nik as nvarchar(10)
AS
BEGIN
BEGIN TRANSACTION A1
BEGIN TRY
	SET NOCOUNT ON;
	--digital
	update document_digital set kode_category = @category, tanggal_bayar = @tgl_bayar, nama_tujuan = @nama_tujuan, 
	rek_tujuan = @rek, kode_bank = @kode_bank, nama_rek = @nama_rek, jumlah = @jumlah, mata_uang=@mata_uang, no_ref = @no_ref, 
	keterangan = @keterangan, updated_at = getdate(), updated_by = @nik
	where id=@id

	COMMIT TRANSACTION A1
	select 'ok' as hasil

END TRY
BEGIN CATCH
        IF @@TRANCOUNT > 0
        BEGIN
            ROLLBACK TRANSACTION A1; -- rollback to MySavePoint
			SELECT   
			ERROR_NUMBER() AS ErrorNumber, 
			ERROR_MESSAGE() AS hasil;
        END
    END CATCH
END;