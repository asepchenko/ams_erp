USE [yud_test]
GO
/****** Object:  StoredProcedure [dbo].[sp_add_document]    Script Date: 4/13/2020 11:49:51 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- exec sp_add_document 'A5832C94E062452E82304E784B81E7BF'
ALTER PROCEDURE [dbo].[sp_add_mobile_promo]
	@nik as nvarchar(10),
	@nama as nvarchar(64),
	@image as nvarchar(max),
	@keterangan as nvarchar(max),
	@sk as nvarchar(max),
	@isaktif as nvarchar(1)
AS
BEGIN
BEGIN TRANSACTION A1
BEGIN TRY
	SET NOCOUNT ON;
	
	Insert Into pos_server.dbo.dt_member_mobile_promo (nama_promo, image, keterangan, syarat_ketentuan, is_aktif,
	created_at,created_by,updated_at,updated_by)
	Values(@nama, @image, @keterangan, @sk, @isaktif, getdate(), @nik, getdate(), @nik)

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