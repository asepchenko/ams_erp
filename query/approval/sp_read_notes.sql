USE [APPROVAL]
GO
/****** Object:  StoredProcedure [dbo].[sp_approve_document]    Script Date: 5/12/2020 1:39:49 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- exec sp_submit_document '2003310001','00003599'
CREATE PROCEDURE [dbo].[sp_read_notes]
	@id as nvarchar(10),
	@nik as nvarchar(10)
AS
BEGIN
BEGIN TRANSACTION A1
BEGIN TRY
	SET NOCOUNT ON;

	--update note
	Update document_note set read_date=getdate(), read_by=@nik where id=@id

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