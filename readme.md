=======================
AMS ERP Framework
(c) 2020 Yudha T. Putra
=======================

Web App Framework [ERP], memudahkan membuat aplikasi web baru dengan satu framework.
Saling terintegrasi dan menggunakan satu user untuk semua aplikasi.

----------------------
Teknologi yang dipakai
----------------------
- Laravel 6.x LTS
- AdminLTE 3 (Bootstrap 4 + CSS3 + HTML5)
- DataTables
- Jquery + AJAX
- ChartJS
- Leaflet JS (openstreetmap)
- SignaturePad JS

--------
Aplikasi
--------
A. Admin
   Aplikasi utama untuk user management. Mengatur permission dan role untuk setiap user disemua aplikasi.

B. POS
   Aplikasi untuk melihat report sales, disajikan dengan data yang lengkap dan penuh visualisasi grafik.

C. CRM
   Aplikasi untuk mengelola data customer (member), melihat sales dari customer dan mengatur promosi apa saja yang akan
   ditampilkan dalam aplikasi mobile apps membership. 
   - terintegrasi dengan 
        * E-Member Mobile

D. Ticketing/Helpdesk
   Aplikasi untuk membantu user yang mengalami kendala pada aplikasi/hal lainnya melalui submit ticket. Ticket bisa di assign
   otomatis ke departemen tertentu atau PIC langsung. Dilengkapi dashboard, e-mail notification dan SLA.

E. Approval System
   Aplikasi untuk approval dokumen sesuai workflow yang telah disetting. User bisa melakukan submit dokumen,
   kemudian di approve/reject/cancel oleh atasannya kemudian diteruskan kepada departemen terkait. Dokumen tersedia
   dalam bentuk digital (Voucher Merah, Kasbon, Surat Pengajuan Dana) dan bisa melakukan upload file sebagai lampiran.
   Dilengkapi e-mail notification dan e-signature membuat proses approval menjadi informatif dan serba elektronik.
   - terintegrasi dengan 
        * GASYSTEM (Nomor PO)
        * Finance (Realisasi)

F. Finance
   Aplikasi khusus untuk dept finance, terintegrasi dengan Approval System.

G. E-Library
   Aplikasi untuk manajemen file atau surat berharga. Proses peminjaman file atau surat berharga dan tracking posisi terakhir dari surat berharga tsb.

------
Fitur:
------
- E-mail notifikasi
- Notifikasi dari sistem ketika ada update dsb

init :
- Copy __.env.example to .env
- composer install
- php artisan key:generate
- php artisan migrate --seed

test user:
00003599 -> admin

run :
- php -S localhost:8000 -t public/
- php artisan serve
- php artisan serve --port=8080 (jika mau dgn port berbeda)

useful command :
- php artisan make:model nama_model, misal -> Brand
- php artisan make:controller nama_controller, misal -> Admin\BrandController
- php artisan make:request nama_request, misal -> StoreBrandRequest
- php artisan cache:clear (kalau habis buat route baru tapi statusnya not found)
- php artisan route:cache (kalau habis buat route baru tapi statusnya not found)
- php artisan route:list
- php artisan make:mail submitMail (buat email)
- php artisan make:rule MatchOldPassword (buat rule baru saat simpan data)
- php artisan make:import StoreTargetImport --model=StoreTarget

--------------
run production
--------------
- C:\xampp\apache\conf\extra\httpd-vhosts.conf
- isi file dgn script berikut:
<VirtualHost *:88>
    DocumentRoot "D:/xampp/htdocs/ams_core/public"
    ServerName 103.227.145.122
	<Directory "D:/xampp/htdocs/ams_core/public">
        Options Indexes FollowSymLinks Includes ExecCGI
        AllowOverride All
    Require all granted
    </Directory>
</VirtualHost>

- C:\xampp\apache\conf\httpd.conf
- isi dengan script berikut:
Listen 88
Listen 192.168.3.9:86

----------
git update
----------
git add --all
git commit --all
i untuk insert keterangan
esc untuk keluar
:wq untuk save dan exit
git push origin master

-------
catatan
-------
- jika ($data1->kode == $data2->kode) tidak bisa,pastikan tipe datanya sama
- https://github.com/yajra/laravel-datatables#php-artisan-serve-bug
- https://hpduy17.wordpress.com/2019/01/29/deploy-laravel-web-app-to-windows-server-2012/
- https://www.hashmicro.com/id/blog/pengertian-erp-software/
- tambah document_type : doc, kbr, kbt di table document_master, document_flow

todo listnya :
- pasang SSL (https://seegatesite.com/the-steps-how-to-install-openssl-on-xampp-windows/)
- kolom ditable bisa seperti excel, freeze kolom
- inline edit ditable (https://editor.datatables.net/purchase/index)
- report dinamis
- menu dinamis by user role
- bulk insert
- upload file to googledrive (http://www.expertphp.in/article/laravel-php-create-folder-and-upload-file-to-google-drive-with-access-token)

https://medium.com/@dadanasep74/upload-file-storage-laravel-to-google-drive-2809f917f97a