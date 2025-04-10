USE [APPROVAL]
GO
/****** Object:  StoredProcedure [dbo].[sp_add_document_digital]    Script Date: 7/8/2020 10:31:29 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- exec sp_add_document_digital '2003310001', 'VM', 'YUDA', 12345678, 1000, '', 'TEST', '00003599'
ALTER PROCEDURE [dbo].[sp_add_document_digital]
	@id as nvarchar(10),
	@category as nvarchar(50),
	@tgl_bayar as datetime,
	@nama_tujuan as nvarchar(128), 
	@kode_bank as nvarchar(50),
	@nama_rek as nvarchar(128),
	@rek as nvarchar(50), 
	@jumlah as numeric(18,2), 
	@mata_uang as nvarchar(10),
	@no_ref as nvarchar(64),
	@keterangan as nvarchar(max),
	@nik as nvarchar(10)
AS
BEGIN
Declare
	@id_baru as nvarchar(32),
	@kode_dept as nvarchar(15),
	@tglnya as nvarchar(6),
	@temp_no as nvarchar(21),
	@max_no as nvarchar(25),
	@id_sekarang as int,
	@id_counternya as int,
	@no_digital as nvarchar(25)
BEGIN TRANSACTION A1
BEGIN TRY
	SET NOCOUNT ON;
	set @id_baru = Replace(Convert(Varchar(255),NewID()),'-','')

	--generate no spd
	--contoh : BDV/270420/0001 | dept/tglbulantahun/nourut
	if @category = 'SPD'
		begin
			select @kode_dept = kode_departemen from yud_test.dbo.users where nik=@nik
			select @tglnya = format(created_at,'ddMMyy') from document_master where id=@id
			select @temp_no = concat(@kode_dept,'/',@tglnya,'/')

			--dapetin no digital terakhir berdasarkan dept dan tglbulantahun
			select @max_no = max(no_digital) from document_digital where kode_category='SPD' and no_digital like '%'+@temp_no+'%'

			if @max_no is null
				begin
					set @no_digital = concat(@temp_no,'0001')
				end
			else
				begin
					set @id_sekarang = len(right(@max_no,4)+1)
					--dapetin id counternya
					set @id_counternya = right(@max_no,4)+1

					if @id_sekarang = 1
						begin
							set @no_digital = concat(@temp_no, '000', @id_counternya)
						end
					else if @id_sekarang = 2
						begin
							set @no_digital = concat(@temp_no, '00', @id_counternya)
						end
					else if @id_sekarang = 3
						begin
							set @no_digital = concat(@temp_no, '0', @id_counternya)
						end
					else if @id_sekarang = 4
						begin
							set @no_digital = concat(@temp_no, @id_counternya)
						end
				end
		end
	else
		begin
			set @no_digital = ''
		end

	--digital
	Insert Into document_digital (id, document_id, kode_category, no_digital, tanggal_bayar, nama_tujuan, rek_tujuan, 
	kode_bank, nama_rek,jumlah, mata_uang,no_ref, keterangan,
	created_at,created_by,updated_at,updated_by)
	Values(@id_baru, @id, @category, @no_digital, @tgl_bayar, @nama_tujuan, @rek, 
	@kode_bank, @nama_rek, @jumlah, @mata_uang, @no_ref, @keterangan, 
	getdate(), @nik, getdate(), @nik)

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