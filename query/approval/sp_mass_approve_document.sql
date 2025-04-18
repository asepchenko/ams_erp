USE [APPROVAL]
GO
/****** Object:  StoredProcedure [dbo].[sp_mass_approve_document]    Script Date: 8/27/2020 11:43:51 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- exec sp_mass_approve_document '2004210027,2004210029,2004210033','test','00003599'
ALTER PROCEDURE [dbo].[sp_mass_approve_document]
	@id as nvarchar(max),
	@signature as text,
	@nik as nvarchar(10)
AS
BEGIN
Declare
	@current_status as nvarchar(50),
	@current_jab nvarchar(50),
	@kode_dept nvarchar(50),
	@next_status as nvarchar(50),
	@next_dept as nvarchar(50),
	@document_type as nvarchar(3),
	@i int
BEGIN TRANSACTION A1
BEGIN TRY
	SET NOCOUNT ON;
	
	set @id = @id+','
	select @i=0

	while( @i < LEN(@id))
	begin
		declare @item varchar(MAX)
		set @item = SUBSTRING(@id,  @i,CHARINDEX(',',@id,@i)-@i)
		--select @item
		--dapetin status saat ini
		select @current_status = last_status, @kode_dept = kode_departemen, @document_type = document_type from document_master where id=@item

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

		--update status
		update document_status set signature=@signature, updated_at=getdate(), updated_by=@nik
		where document_id=@item and status=@current_status

		--insert status
		Insert Into document_status (document_id, status, created_at,created_by)
		Values(@item, @next_status, getdate(), @nik)

		--update master status
		Update document_master set last_status=@next_status, current_dept=@next_dept,
		current_jab=@current_jab where id=@item

		--insert log mass approve
		Insert Into log_mass_approve (document_id, created_at,created_by)
		Values(@item, getdate(), @nik)

		set @i = CHARINDEX(',',@id,@i)+1
		if(@i = 0) set @i = LEN(@id) 
	end

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