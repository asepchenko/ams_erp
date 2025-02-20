USE [APPROVAL]
GO
/****** Object:  StoredProcedure [dbo].[sp_update_document]    Script Date: 4/17/2020 9:50:18 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- exec sp_add_document 'A5832C94E062452E82304E784B81E7BF'
ALTER PROCEDURE [dbo].[sp_update_pu]
	@id as nvarchar(10),
	@is_pu as nvarchar(10),
	@nik as nvarchar(50)
AS
BEGIN

BEGIN TRANSACTION A1
BEGIN TRY
	SET NOCOUNT ON;

	--master
	update document_digital set is_pu=@is_pu,
	pu_created_at=getdate(),pu_created_by=@nik
	where document_id=@id and kode_category='VM'

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