USE [yud_test]
GO
/****** Object:  StoredProcedure [dbo].[sp_add_mobile_product]    Script Date: 5/15/2020 10:39:51 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- exec sp_add_document 'A5832C94E062452E82304E784B81E7BF'
CREATE PROCEDURE [dbo].[sp_add_mobile_category]
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
	
	Insert Into pos_server.dbo.dt_member_mobile_category (brand, image, category, is_aktif,
	created_at,created_by,updated_at,updated_by)
	Values(@brand, @image, @category, @isaktif, getdate(), @nik, getdate(), @nik)

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