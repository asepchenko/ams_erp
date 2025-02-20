USE [APPROVAL]
GO
/****** Object:  StoredProcedure [dbo].[sp_add_document_file]    Script Date: 4/13/2020 11:51:00 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- exec sp_add_document 'A5832C94E062452E82304E784B81E7BF'
ALTER PROCEDURE [dbo].[sp_add_document_file]
	@id as nvarchar(10),
	@category as nvarchar(50),
	@keterangan as nvarchar(max),
	@nik as nvarchar(10),
	@nama_file as nvarchar(128)
AS
BEGIN
Declare
	@id_baru as nvarchar(32)
BEGIN TRANSACTION A1
BEGIN TRY
	SET NOCOUNT ON;
	set @id_baru = Replace(Convert(Varchar(255),NewID()),'-','')

	--file
	Insert Into document_file (id, document_id, category_name,nama_file, keterangan,
	created_at,created_by,updated_at,updated_by)
	Values(@id_baru, @id, @category, @nama_file, @keterangan, getdate(), @nik, getdate(), @nik)

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