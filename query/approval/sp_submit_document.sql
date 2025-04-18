USE [APPROVAL]
GO
/****** Object:  StoredProcedure [dbo].[sp_submit_document]    Script Date: 8/27/2020 11:13:16 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- exec sp_submit_document '2003310001','00003599'
ALTER PROCEDURE [dbo].[sp_submit_document]
	@id as nvarchar(10),
	@kode_dept as nvarchar(50),
	@signature as text,
	@nik as nvarchar(10)
AS
BEGIN
Declare
	@ada_digital as int,
	@ada_file as int,
	@pernah_reject as int,
	@current_status as nvarchar(50),
	@current_dept as nvarchar(50),
	@current_jab nvarchar(50),
	@next_status as nvarchar(50),
	@next_dept as nvarchar(10),
	@document_type as nvarchar(3)
BEGIN TRANSACTION A1
BEGIN TRY
	SET NOCOUNT ON;
	select @ada_digital = count(id) from document_digital where document_id=@id

	--dapetin document_type
	select @document_type = document_type from document_master where id=@id

	--blocking step 1
	if @ada_digital > 0 
		begin
			--select @ada_file = count(id) from document_file where document_id=@id

			--blocking step 2
			--if @ada_file > 0 
				--begin
					--dapetin status saat ini
					select @current_status = last_status from document_master where id=@id

					--cek dulu apakah habis dari reject
					select @pernah_reject = count(id) from document_status where document_id=@id
					and last_reject is not null

					if @pernah_reject > 0
						begin
						--dapetin next status
						select top(1) @next_status = last_reject,@next_dept = upper(replace(last_reject,'APPROVAL_','')) 
						from document_status where document_id=@id
						and last_reject is not null order by id desc

						if @next_dept = 'MANAGER'
							begin
								--berarti masih satu divisi
								select @next_dept = kode_departemen from document_master where id=@id
							end

						--next_jab
						select @current_jab = next_jabatan
						from document_flow where tipe_flow='submit'
						and kode_dept=@kode_dept and next_status=@next_status

						--insert status
						Insert Into document_status (document_id, status,created_at,created_by)
						Values(@id, @next_status,getdate(), @nik)

						--update status
						Update document_master set last_status=@next_status, current_jab=@current_jab,
						current_dept=@next_dept where id=@id
						end
					else
						begin
						if @document_type = 'kbr'
						begin
							--dapetin next status & next dept
							select @next_status = next_status, @current_dept=next_departemen,
							@current_jab = next_jabatan
							from document_flow where tipe_flow='submit'
							and kode_dept=@kode_dept and status=@current_status and document_type=@document_type
						end
						else 
						begin
							--dapetin next status & next dept
							select @next_status = next_status, @current_dept=next_departemen,
							@current_jab = next_jabatan
							from document_flow where tipe_flow='submit'
							and kode_dept=@kode_dept and status=@current_status and document_type='doc'
						end
						--update status
						update document_status set signature=@signature, updated_at=getdate(), updated_by=@nik
						where document_id=@id and status=@current_status

						--insert status
						Insert Into document_status (document_id, status,created_at,created_by)
						Values(@id, @next_status,getdate(), @nik)

						--update status
						Update document_master set last_status=@next_status, current_dept=@current_dept,
						current_jab=@current_jab where id=@id
						end

					COMMIT TRANSACTION A1
					select 'ok' as hasil
				--end
			--else
				--begin
					--select 'belum ada file yang diupload' as hasil
				--end
		end
	else
		begin
			select 'belum ada form yang dibuat' as hasil
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