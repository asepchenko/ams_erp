USE [APPROVAL]
GO
/****** Object:  StoredProcedure [dbo].[sp_reject_document]    Script Date: 8/27/2020 11:33:03 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- exec sp_submit_document '2003310001','00003599'
ALTER PROCEDURE [dbo].[sp_reject_document]
	@id as nvarchar(10),
	@alasan as nvarchar(128),
	@nik as nvarchar(10)
AS
BEGIN
Declare
	@current_status as nvarchar(50),
	@current_jab as nvarchar(50),
	@kode_dept nvarchar(50),
	@next_status as nvarchar(50),
	@next_dept as nvarchar(50),
	@current_dept as nvarchar(10),
	@document_type as nvarchar(3)
BEGIN TRANSACTION A1
BEGIN TRY
	SET NOCOUNT ON;
	--dapetin document_type
	select @document_type = document_type from document_master where id=@id

	--dapetin status saat ini
	select @current_status = last_status,@current_dept = kode_departemen,
	@kode_dept = kode_departemen from document_master where id=@id

	--dapetin jabatan awal pembuat dokumen
	select @current_jab = kode_jabatan from yud_test.dbo.users where nik =
	(select top 1 nik from document_master where id=@id)

	if @document_type='kbr'
	begin
		--dapetin nex status & dept
		select @next_status = next_status, @next_dept = next_departemen from document_flow where tipe_flow='reject'
		and kode_dept=@kode_dept and status=@current_status and document_type=@document_type
	end
	else
	begin
		--dapetin nex status & dept
		select @next_status = next_status, @next_dept = next_departemen from document_flow where tipe_flow='reject'
		and kode_dept=@kode_dept and status=@current_status and document_type='doc'
	end
	--update status
	update document_status set alasan=@alasan, updated_at=getdate(), updated_by=@nik
	where document_id=@id and status=@current_status

	--insert status
	Insert Into document_status (document_id, status, last_reject, created_at,created_by)
	Values(@id, @next_status, @current_status, getdate(), @nik)

	--update master status
	Update document_master set last_status=@next_status, current_dept=@current_dept,
	current_jab = @current_jab where id=@id

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