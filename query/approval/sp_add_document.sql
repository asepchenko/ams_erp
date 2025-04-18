USE [APPROVAL]
GO
/****** Object:  StoredProcedure [dbo].[sp_add_document]    Script Date: 5/12/2020 1:52:45 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- exec sp_add_document 'A5832C94E062452E82304E784B81E7BF'
ALTER PROCEDURE [dbo].[sp_add_document]
	@nik as nvarchar(10),
	@nama as nvarchar(128),
	@kode_jabatan as nvarchar(50),
	@keterangan as nvarchar(128),
	@prioritas as int
AS
BEGIN
 Declare 
	@jum_notes as int,
	@tglnya as varchar(6),
	@id_max as varchar(10),
	@id_baru as varchar(10),
	@kode_departemen as nvarchar(10)
BEGIN TRANSACTION A1
BEGIN TRY
	SET NOCOUNT ON;
	--cek notesnya dulu
	select @jum_notes = count(id) from document_note where penerima=@nik and read_by is null

	if @jum_notes > 0
		begin
			select 'gagal' as hasil
		end
	else
		begin
			select @kode_departemen = kode_departemen from yud_test.dbo.users where nik=@nik

			set @tglnya = format(getdate(),'yyMMdd')
			--dapetin ID max
			select @id_max = max(id) from document_master where left(id,6)=@tglnya
	
			if @id_max IS NULL
				begin
					set @id_baru = concat(@tglnya, '0001')
				end
			else
				begin
					select @id_baru = max(id)+1 from document_master
				end

			--master
			Insert Into document_master (id,nik,nama,kode_departemen,keterangan,last_status,current_dept,current_jab,
			document_priority_id, created_at,created_by,updated_at,updated_by)
			Values(@id_baru,@nik,@nama, @kode_departemen, @keterangan, 'open', @kode_departemen, @kode_jabatan, 
			@prioritas, getdate(), @nik, getdate(), @nik)

			--status
			Insert Into document_status (document_id,status,
			created_at,created_by)
			Values(@id_baru,'open', getdate(), @nik)

			COMMIT TRANSACTION A1
			select @id_baru as hasil
	end

END TRY
BEGIN CATCH
        IF @@TRANCOUNT > 0
        BEGIN
            ROLLBACK TRANSACTION A1; -- rollback to MySavePoint
			SELECT   
			ERROR_NUMBER() AS ErrorNumber, 
			ERROR_MESSAGE() AS ErrorMessage,
			'gagal' as hasil;
        END
    END CATCH
END;