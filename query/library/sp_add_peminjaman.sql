USE [ELIBRARY]
GO
/****** Object:  StoredProcedure [dbo].[sp_add_document]    Script Date: 4/13/2020 11:49:51 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- exec sp_add_document 'A5832C94E062452E82304E784B81E7BF'
ALTER PROCEDURE [dbo].[sp_add_peminjaman]
	@nik as nvarchar(10),
	@nama as nvarchar(128),
	@tipe_pinjam as nvarchar(10),
	@keterangan as nvarchar(128)
AS
BEGIN
 Declare 
	@tglnya as varchar(6),
	@id_max as varchar(10),
	@id_baru as varchar(10),
	@kode_departemen as nvarchar(10)
BEGIN TRANSACTION A1
BEGIN TRY
	SET NOCOUNT ON;
	select @kode_departemen = kode_departemen from yud_test.dbo.users where nik=@nik

	set @tglnya = format(getdate(),'yyMMdd')
	--dapetin ID max
	select @id_max = max(no_peminjaman) from peminjaman_master where left(no_peminjaman,6)=@tglnya
	
	if @id_max IS NULL
		begin
			set @id_baru = concat(@tglnya, '0001')
		end
	else
		begin
			select @id_baru = max(no_peminjaman)+1 from peminjaman_master
		end

	--master
	Insert Into peminjaman_master (no_peminjaman,nik_peminjam,nama_peminjam,kode_departemen,tgl_pinjam,
	tipe_peminjaman, keterangan,status_pinjam,
	created_at,created_by,updated_at,updated_by)
	Values(@id_baru,@nik,@nama, @kode_departemen, getdate(),
	@tipe_pinjam, @keterangan, 'open',
	getdate(), @nik, getdate(), @nik)

	--status
	Insert Into peminjaman_status (no_peminjaman,status,
	created_at,created_by)
	Values(@id_baru,'open', getdate(), @nik)

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