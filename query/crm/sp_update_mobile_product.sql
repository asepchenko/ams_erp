USE [yud_test]
GO
/****** Object:  StoredProcedure [dbo].[sp_update_mobile_product]    Script Date: 5/16/2020 9:48:07 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- exec sp_add_document 'A5832C94E062452E82304E784B81E7BF'
ALTER PROCEDURE [dbo].[sp_update_mobile_product]
	@id as bigint,
	@nik as nvarchar(10),
	@nama as nvarchar(64),
	@brand as nvarchar(64),
	@category as nvarchar(64),
	@image as nvarchar(max),
	@keterangan as nvarchar(max),
	@old_price as int,
	@new_price as int,
	@isaktif as nvarchar(1)
AS
BEGIN
BEGIN TRANSACTION A1
BEGIN TRY
	SET NOCOUNT ON;
	
	update pos_server.dbo.dt_member_mobile_product
	set title = @nama, brand = @brand, category = @category, image = @image, keterangan = @keterangan, 
	old_price = @old_price, new_price = @new_price,
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