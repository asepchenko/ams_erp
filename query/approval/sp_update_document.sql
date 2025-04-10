USE [APPROVAL]
GO
/****** Object:  StoredProcedure [dbo].[sp_update_document]    Script Date: 4/13/2020 11:52:45 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- exec sp_add_document 'A5832C94E062452E82304E784B81E7BF'
ALTER PROCEDURE [dbo].[sp_update_document]
	@id as nvarchar(10),
	@nik as nvarchar(10),
	@keterangan as nvarchar(128)
AS
BEGIN

BEGIN TRANSACTION A1
BEGIN TRY
	SET NOCOUNT ON;

	--master
	update document_master set keterangan=@keterangan,
	updated_at=getdate(),updated_by=@nik
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
			ERROR_MESSAGE() AS ErrorMessage,
			'gagal' as hasil;
        END
    END CATCH
END;