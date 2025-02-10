USE [yud_test]
GO
/****** Object:  StoredProcedure [dbo].[sp_add_document]    Script Date: 4/13/2020 11:49:51 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- exec sp_add_document 'A5832C94E062452E82304E784B81E7BF'
ALTER PROCEDURE [dbo].[sp_create_report]
	@nik as nvarchar(10),
	@nama as nvarchar(128),
	@databasenya as nvarchar(64),
	@tablenya as nvarchar(64),
	@typenya as nvarchar(50)
AS
BEGIN
Declare
	@id_baru as nvarchar(32)
BEGIN TRANSACTION A1
BEGIN TRY
	SET NOCOUNT ON;
	set @id_baru = Replace(Convert(Varchar(255),NewID()),'-','')
	
	Insert Into dt_report_master (id, nik, report_name, database_name, table_name, select_type,
	created_at,created_by,updated_at,updated_by)
	Values(@id_baru, @nik, @nama, @databasenya, @tablenya, @typenya, getdate(), @nik, getdate(), @nik)

	COMMIT TRANSACTION A1
	select @id_baru as hasil

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