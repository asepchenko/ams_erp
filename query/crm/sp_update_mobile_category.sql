USE [yud_test]
GO
/****** Object:  StoredProcedure [dbo].[sp_update_mobile_product]    Script Date: 5/15/2020 10:48:31 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- exec sp_add_document 'A5832C94E062452E82304E784B81E7BF'
CREATE PROCEDURE [dbo].[sp_update_mobile_category]
	@id as bigint,
	@nik as nvarchar(10),
	@brand as nvarchar(64),
	@image as nvarchar(max),
	@category as nvarchar(64),
	@isaktif as nvarchar(1)
AS
BEGIN
BEGIN TRANSACTION A1
BEGIN TRY
	SET NOCOUNT ON;
	
	update pos_server.dbo.dt_member_mobile_category
	set brand = @brand, image = @image, category = @category,
	is_aktif = @isaktif, updated_at = getdate(), updated_by= @nik
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