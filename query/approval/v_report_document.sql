USE [APPROVAL]
GO

/****** Object:  View [dbo].[v_report_document]    Script Date: 5/11/2020 11:14:00 AM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO


ALTER view [dbo].[v_report_document] as
select A.id, A.nama, format(A.created_at,'yyyy-MM-dd') as created_at, format(A.created_at,'MM') as bulan, A.kode_departemen, A.keterangan, A.last_status,
A.document_priority_id,
no_digital = STUFF((
          SELECT ',' + md.kode_category + '-' + md.no_digital
          FROM approval.dbo.document_digital md
          WHERE A.id = md.document_id
          FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'), 1, 1, ''),
no_ref = STUFF((
          SELECT ',' + md.kode_category + '-' + md.no_ref
          FROM approval.dbo.document_digital md
          WHERE A.id = md.document_id
          FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'), 1, 1, ''),
rekening = STUFF((
          SELECT ',' + md.kode_category + '-' + md.rek_tujuan
          FROM approval.dbo.document_digital md
          WHERE A.id = md.document_id
          FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'), 1, 1, ''),
jumlah = STUFF((
          SELECT ',' + md.kode_category + '-' + REPLACE(FORMAT(md.jumlah, 'N', 'en-us'), '.00', '') 
          FROM approval.dbo.document_digital md
          WHERE A.id = md.document_id
          FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'), 1, 1, ''),
tujuan = STUFF((
          SELECT ',' + md.kode_category + '-' + md.nama_tujuan
          FROM approval.dbo.document_digital md
          WHERE A.id = md.document_id
          FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'), 1, 1, ''),
deskripsi = STUFF((
          SELECT ',' + md.kode_category + '-' + md.keterangan
          FROM approval.dbo.document_digital md
          WHERE A.id = md.document_id
          FOR XML PATH(''), TYPE).value('.', 'NVARCHAR(MAX)'), 1, 1, ''),
		  (select top 1 format(z.tanggal_bayar,'yyyy-MM-dd') from document_digital z where z.document_id=A.id order by z.tanggal_bayar desc) as tanggal_jt,
(select top 1 z.is_pu from document_digital z where z.document_id=A.id and z.kode_category='VM') as is_pu,
(select top 1 z.jumlah from document_digital z where z.document_id=A.id and z.kode_category='VM') as jum_vm,
(select distinct format(z.tanggal_realisasi,'yyyy-MM-dd') from finance.dbo.realisasi_document z where z.document_id=A.id) as tanggal_realisasi,
(select top 1 format(z.created_at,'yyyy-MM-dd') from document_status z where z.document_id=A.id
and A.last_status=z.status order by z.id desc) as tanggal_status
from document_master A

GO