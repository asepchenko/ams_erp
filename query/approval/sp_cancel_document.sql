USE [APPROVAL]
GO
/****** Object:  StoredProcedure [dbo].[sp_cancel_document]    Script Date: 4/13/2020 11:51:48 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- exec sp_submit_document '2003310001','00003599'
ALTER PROCEDURE [dbo].[sp_cancel_document]
	@id as nvarchar(10),
	@alasan as nvarchar(128),
	@nik as nvarchar(10)
AS
BEGIN
BEGIN TRANSACTION A1
BEGIN TRY
	SET NOCOUNT ON;
	--insert status
	Insert Into document_status (document_id, status, alasan, created_at,created_by)
	Values(@id, 'cancel', @alasan, getdate(), @nik)
	
	--update master status
	Update document_master set last_status='cancel', current_dept='-' where id=@id

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