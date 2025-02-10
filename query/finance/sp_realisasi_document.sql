USE [FINANCE]
GO
/****** Object:  StoredProcedure [dbo].[sp_realisasi_document]    Script Date: 4/20/2020 2:45:33 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- exec sp_submit_document '2003310001','00003599'
ALTER PROCEDURE [dbo].[sp_realisasi_document]
	@id as nvarchar(10),
	@tgl_real as datetime,
	@nama_file as nvarchar(128),
	@nik as nvarchar(10)
AS
BEGIN
Declare
	@id_baru as nvarchar(32)
BEGIN TRANSACTION A1
BEGIN TRY
	SET NOCOUNT ON;
	set @id_baru = Replace(Convert(Varchar(255),NewID()),'-','')

	--salin data ke db finance
	insert into realisasi_document (id, document_id, document_digital_id, kode_category, 
	tanggal_bayar, tanggal_realisasi,
	nama_tujuan, rek_tujuan, kode_bank, nama_rek, jumlah, jumlah_realisasi,
	is_pu, no_ref, keterangan, status_realisasi,
	created_at, created_by, updated_at, updated_by)
	select (Replace(Convert(Varchar(255),NewID()),'-','')) as id, document_id, id as document_digital_id, kode_category, 
	tanggal_bayar, @tgl_real as tanggal_realisasi,
	nama_tujuan, rek_tujuan, kode_bank, nama_rek, jumlah, jumlah as jumlah_realisasi,
	is_pu, no_ref, keterangan, '1' as status_realisasi,
	getdate() as created_at, @nik as created_by, getdate() as updated_at, @nik as updated_by
	from approval.dbo.document_digital where document_id=@id

	if @nama_file <> '-'
		begin
			--insert file
			insert into approval.dbo.document_file(id, document_id, category_name,nama_file,
			keterangan, created_at, created_by, updated_at, updated_by)values(
			@id_baru, @id, 'BUKTI', @nama_file, 'BUKTI REALISASI',getdate(), @nik, getdate(), @nik)
		end

	--insert status
	Insert Into approval.dbo.document_status (document_id, status, created_at,created_by)
	Values(@id, 'closed', getdate(), @nik)

	--update master status
	Update approval.dbo.document_master set last_status='closed' where id=@id

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