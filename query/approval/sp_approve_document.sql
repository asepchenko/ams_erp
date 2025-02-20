USE [APPROVAL]
GO
/****** Object:  StoredProcedure [dbo].[sp_approve_document]    Script Date: 8/27/2020 11:05:46 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- exec sp_submit_document '2003310001','00003599'
ALTER PROCEDURE [dbo].[sp_approve_document]
	@id as nvarchar(10),
	@signature as text,
	@notes as nvarchar(max),
	@nik as nvarchar(10)
AS
BEGIN
Declare
	@document_type as nvarchar(3),
	@current_status as nvarchar(50),
	@current_jab nvarchar(50),
	@kode_dept nvarchar(50),
	@next_status as nvarchar(50),
	@next_dept as nvarchar(50),
	@penerima as nvarchar(10),
	@user_dept as nvarchar(50),
	@jum_cek_dept as int
BEGIN TRANSACTION A1
BEGIN TRY
	SET NOCOUNT ON;
	--dapetin kode_dept user yg approve
	select @user_dept = left(kode_departemen,3)
	from yud_test.dbo.users where nik=@nik

	--dapetin document_type
	select @document_type = document_type from document_master where id=@id

	--cek user_dept & current_dept, harus sama
	select @jum_cek_dept = count(id)
	from document_master where id=@id and current_dept like '%'+@user_dept+'%'

	if @jum_cek_dept > 0
		begin
			--dapetin status saat ini
			select @current_status = last_status, @kode_dept = kode_departemen, @penerima = nik
			from document_master where id=@id and current_dept like '%'+@user_dept+'%'

			if @document_type = 'kbr'
				begin
					--dapetin nex status & dept
					select @next_status = next_status, @next_dept = next_departemen, @current_jab=next_jabatan 
					from document_flow where tipe_flow='submit'
					and kode_dept=@kode_dept and status=@current_status and document_type=@document_type
				end
			else
				begin
					--dapetin nex status & dept
					select @next_status = next_status, @next_dept = next_departemen, @current_jab=next_jabatan 
					from document_flow where tipe_flow='submit'
					and kode_dept=@kode_dept and status=@current_status and document_type='doc'
				end
			

			if @notes <> ''
				begin
					--insert ke notes
					Insert Into document_note (document_id, penerima, pembuat, kode_departemen,notes,
					created_at,created_by)
					Values(@id, @penerima, @nik, @kode_dept, @notes,
					getdate(), @nik)
				end

			--update status
			update document_status set signature=@signature, updated_at=getdate(), updated_by=@nik
			where document_id=@id and status=@current_status

			--insert status
			Insert Into document_status (document_id, status, created_at,created_by)
			Values(@id, @next_status, getdate(), @nik)

			--update master status
			Update document_master set last_status=@next_status, current_dept=@next_dept,
			current_jab=@current_jab where id=@id

			COMMIT TRANSACTION A1
			select 'ok' as hasil

		end
	else
		begin
			select 'Tidak bisa approve document. Document ini sudah tidak di dept anda' as hasil
		end
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