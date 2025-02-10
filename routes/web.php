<?php

if (env('APP_ENV') === 'production') {
    URL::forceSchema('https');
}

Route::redirect('/', '/login');
Route::redirect('/home', '/admin');
Auth::routes(['register' => false]);

//Aplikasi Admin
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::resource('permissions', 'PermissionsController');
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');

    Route::resource('roles', 'RolesController');
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');

    Route::resource('users', 'UsersController');
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');

    Route::resource('profile', 'ProfileController');
    Route::post('profile/change-password', 'ProfileController@changePassword');

    Route::resource('stockopname', 'StockOpnameController');
    Route::resource('selisih', 'SelisihController');
    Route::resource('coba', 'CobaController');
});

//aplikasi POS
Route::group(['prefix' => 'pos', 'as' => 'pos.', 'namespace' => 'Pos', 'middleware' => ['auth']], function () {
    Route::resource('dashboard', 'DashboardController');

    Route::post('dailyreportall', 'DailyReportAllController@index');
    Route::get('dailyreportall/search/{bulan}/{brand}/{store}', 'DailyReportAllController@searchData');
    Route::get('dailyreportall-store/{brand}', 'DailyReportAllController@getDataStore');
    Route::resource('dailyreportall', 'DailyReportAllController', ['except' => ['store']]);

    Route::post('dailyreportgh', 'DailyReportGHController@index');
    Route::get('dailyreportgh/search/{bulan}/{gh}', 'DailyReportGHController@searchData');
    Route::get('dailyreportgh-store/{id}', 'DailyReportGHController@getDataStore');
    Route::resource('dailyreportgh', 'DailyReportGHController', ['except' => ['store']]);

    Route::get('storetarget/search/{bulan}/{gh}', 'StoreTargetController@searchData');
    Route::get('storetarget/{store}/{bulan}/{tahun}/edit', 'StoreTargetController@editData');
    Route::get('storetarget-store/{id}', 'StoreTargetController@getDataStore');
    Route::post('storetarget-import-excel', 'StoreTargetController@importExcel');
    Route::get('storetarget-download-excel', 'StoreTargetController@downloadExcel');
    Route::resource('storetarget', 'StoreTargetController');

    Route::post('sales-detail', 'SalesDetailController@index');
    Route::get('sales-detail/search/{brand}/{store}/{start}/{end}', 'SalesDetailController@searchData');
    Route::resource('sales-detail', 'SalesDetailController', ['except' => ['store']]);
});

//Aplikasi Ticketing
Route::group(['prefix' => 'ticketing', 'as' => 'ticketing.', 'namespace' => 'Ticketing', 'middleware' => ['auth']], function () {
    Route::resource('dashboard', 'DashboardController');
});

//Aplikasi CRM
Route::group(['prefix' => 'crm', 'as' => 'crm.', 'namespace' => 'Crm', 'middleware' => ['auth']], function () {
    Route::resource('dashboard', 'DashboardController');

    Route::resource('customerstore', 'CustomerStoreController');

    Route::resource('mobile-member', 'MobileMemberController');
    Route::get('mobile-member/{id}/detail', 'MobileMemberController@detail');

    Route::resource('mobile-promo', 'MobilePromoController');
    Route::get('mobile-promo/edit/{id}', 'MobilePromoController@edit');
    Route::post('mobile-promo/update', 'MobilePromoController@update');
    Route::post('mobile-promo/delete', 'MobilePromoController@delete');

    Route::resource('mobile-product', 'MobileProductController');
    Route::get('mobile-product/edit/{id}', 'MobileProductController@edit');
    Route::post('mobile-product/update', 'MobileProductController@update');
    Route::post('mobile-product/delete', 'MobileProductController@delete');
    Route::get('mobile-product-category/{brand}', 'MobileProductController@getDataCategory');

    Route::resource('mobile-category', 'MobileCategoryController');
    Route::get('mobile-category/edit/{id}', 'MobileCategoryController@edit');
    Route::post('mobile-category/update', 'MobileCategoryController@update');
    Route::post('mobile-category/delete', 'MobileCategoryController@delete');
});

//Aplikasi Approval
Route::group(['prefix' => 'approval', 'as' => 'approval.', 'namespace' => 'Approval', 'middleware' => ['auth']], function () {
    Route::resource('dashboard', 'DashboardController');

    Route::resource('document', 'DocumentController');
    Route::get('document/data-digital/{id}', 'DocumentController@dataDigital');
    Route::get('document/data-file/{id}', 'DocumentController@dataFile');
    Route::get('document/{id}/edit', 'DocumentController@edit');
    Route::get('document/{id}/proses', 'DocumentController@getDataProses');
    Route::get('document/{id}/cetak', 'DocumentController@cetakDocument');
    Route::get('document/submit-mail/{id}', 'DocumentController@submitMail');
    Route::get('document/edit-document-digital/{id}', 'DocumentController@editDataDigital');
    Route::get('document/data-history-status/{id}', 'DocumentController@dataHistoryStatus');
    Route::post('document/get-no-po', 'DocumentController@getNoPO');
    Route::post('document/get-supplier', 'DocumentController@getDataSupplier');
    Route::post('document/document-save-digital', 'DocumentController@saveDigital');
    Route::post('document/document-update-digital', 'DocumentController@updateDigital');
    Route::post('document/document-save-file', 'DocumentController@saveFile');
    Route::post('document/submit', 'DocumentController@submitDocument');
    Route::post('document/approve', 'DocumentController@approveDocument');
    Route::post('document/reject', 'DocumentController@rejectDocument');
    Route::post('document/cancel', 'DocumentController@cancelDocument');
    Route::post('document/delete-digital', 'DocumentController@deleteDigital');
    Route::post('document/delete-file', 'DocumentController@deleteFile');
    Route::post('document/update-pu', 'DocumentController@updatePU');
    Route::post('document/mass-approve', 'DocumentController@massApproveDocument');
    Route::get('document/data-program/{id}', 'DocumentController@dataProgram');
    Route::post('document/get-poprogram', 'DocumentController@getDataPoProgram');
    Route::get('document/get-detilpo/{kode}/{param}', 'DocumentController@getDetilplu');
    Route::post('document/document-save-program', 'DocumentController@saveProgram');
    Route::post('document/document-update-program', 'DocumentController@updateProgram');
    Route::get('document/edit-document-program/{id}', 'DocumentController@editDataProgram');
    Route::post('document/delete-program', 'DocumentController@deleteProgram');
    Route::any('documentproses', 'DocumentController@getproses');
    Route::post('document/get-anggaran', 'DocumentController@getDataAnggaran');
    Route::get('document/get-detilanggaran/{param}', 'DocumentController@getDetilanggaran');
    Route::post('document/document-save-anggaran', 'DocumentController@saveAnggaran');
    Route::post('document/document-update-anggaran', 'DocumentController@updateAnggaran');
    Route::get('document/data-anggaran/{id}', 'DocumentController@dataAnggaran');
    Route::get('document/edit-document-anggaran/{id}', 'DocumentController@editDataAnggaran');
    Route::post('document/delete-anggaran', 'DocumentController@deleteAnggaran');
    Route::get('document/data-anggaranproses/{id}', 'DocumentController@dataAnggaranProses');

    Route::resource('outstanding-kasbon', 'OutstandingKasbonController');
    Route::get('outstanding-kasbon/{id}/proses', 'OutstandingKasbonController@getDataProses');
    Route::get('outstanding-kasbon/data-digital/{id}', 'OutstandingKasbonController@dataDigital');
    Route::get('outstanding-kasbon/data-file/{id}', 'OutstandingKasbonController@dataFile');
    Route::post('outstanding-kasbon/add-kasbon-tambahan', 'OutstandingKasbonController@addkbt');
    Route::get('outstanding-kasbon/data-file-realisasi/{id}', 'OutstandingKasbonController@dataFileRealisasi');
    Route::any('outstanding-kasbonproses', 'OutstandingKasbonController@getproses');
    Route::get('outstanding-kasbon/data-file-kbt/{id}', 'OutstandingKasbonController@dataFileKBT');
    Route::get('outstanding-kasbon/data-file-kb/{id}', 'OutstandingKasbonController@dataFileKB');

    Route::resource('skbdn', 'SkbdnController');
    Route::get('skbdn/{id}/proses', 'SkbdnController@getDataProses');
    Route::get('skbdn/data-digital/{id}', 'SkbdnController@dataDigital');

    Route::resource('tempkasbon', 'TempController', ['except' => ['store']]);
    Route::get('tempkasbon/{id}/transfer', 'TempController@transfer');

    Route::post('report', 'ReportController@index');
    Route::get('report/search/{start_date}/{end_date}/{status}/{dept}/{nama}/{typetgl}', 'ReportController@searchData');
    Route::get('report/{id}/print', 'ReportController@printData');
    Route::resource('report', 'ReportController', ['except' => ['store']]);

    Route::resource('file', 'FileController');

    Route::resource('my-document', 'MyDocumentController');

    Route::resource('notes', 'NotesController');
    Route::post('notes/read', 'NotesController@readNotes');

    Route::post('spd-report', 'SPDReportController@index');
    Route::get('spd-report/search/{bulan}/{status}/{dept}/{nama}', 'SPDReportController@searchData');
    Route::resource('spd-report', 'SPDReportController', ['except' => ['store']]);

    Route::post('kasbon-report', 'KasbonReportController@index');
    Route::get('kasbon-report/search/{bulan}/{status}/{dept}/{nama}', 'KasbonReportController@searchData');
    Route::get('get-name-by-dept/{id}', 'KasbonReportController@getNameByDept');
    Route::resource('kasbon-report', 'KasbonReportController', ['except' => ['store']]);

    Route::post('outstanding-report', 'OutstandingReportController@index');
    Route::get('outstanding-report/search/{key_tgl}/{start_date}/{end_date}/{dept}/{status}', 'OutstandingReportController@searchData');
    Route::resource('outstanding-report', 'OutstandingReportController', ['except' => ['store']]);
    Route::get('outstanding-report/{id}/print', 'OutstandingReportController@printData');

    Route::resource('monitoring', 'MonitoringController');
    Route::post('monitoring/searchdocument', 'MonitoringController@search')->name('monitoring.search');

    Route::resource('my-report', 'MyReportController');
    Route::get('my-report/{id}/view', 'MyReportController@viewData');

    Route::get('my-report/get-report/{id}', 'MyReportController@getReport');
});

//Aplikasi Finance
Route::group(['prefix' => 'finance', 'as' => 'finance.', 'namespace' => 'Finance', 'middleware' => ['auth']], function () {
    Route::resource('dashboard', 'DashboardController');

    Route::resource('realisasi-document', 'RealisasiDocumentController');
    Route::post('realisasi-document/realisasi', 'RealisasiDocumentController@realisasiDocument');
    Route::post('realisasi-document/reject', 'RealisasiDocumentController@rejectDocument');
});

//Aplikasi SAP (System Anggaran Perusahaan)
Route::group(['prefix' => 'budget', 'as' => 'budget.', 'namespace' => 'Budget', 'middleware' => ['auth']], function () {
    Route::resource('dashboard', 'DashboardController');
    Route::get('dashboard/search/{periode}/{tahun}', 'DashboardController@searchdata');

    Route::resource('dashboard2024', 'DashboardNewController');
    Route::get('dashboard2024/search/{periode}/{tahun}', 'DashboardNewController@searchdata');

    Route::resource('budgeting', 'BudgetController');
    Route::resource('budgetingnew', 'BudgetNewController');
    Route::get('budgetingnew-download-excel', 'BudgetNewController@downloadExcel');
    Route::post('budgetingnew-import-excel', 'BudgetNewController@importExcel');

    Route::resource('anggaran', 'AnggaranController');
    Route::post('anggaran/submit', 'AnggaranController@submitPermohonan');
    Route::get('anggaran/{id}/proses', 'AnggaranController@getDataProses');
    Route::post('anggaran/reject', 'AnggaranController@rejectPermohonan');
    Route::post('anggaran/cancel', 'AnggaranController@cancelPermohonan');
    Route::get('anggaran/databudget/{id}', 'AnggaranController@dataBudget');
    Route::get('anggaran/datarealisasi/{id}', 'AnggaranController@dataRealisasi');
    Route::post('anggaran/approve', 'AnggaranController@approvePermohonan');
    Route::get('anggaran/{id}/realisasi', 'AnggaranController@getDataRealisasi');
    Route::post('anggaran/save-file', 'AnggaranController@saveFile');
    Route::get('anggaran/data-file/{id}', 'AnggaranController@dataFile');
    Route::post('anggaran/delete-file', 'AnggaranController@deleteFile');

    // Route::resource('addonanggaran', 'AddonAnggaranController');
    // Route::get('addonanggaran/{id}/proses', 'AddonAnggaranController@getDataProses');

    // Route::resource('openkuartal', 'OpenkuartalController');

    Route::resource('reportanggaran', 'ReportanggaranController');
    Route::get('reportanggaran/search/{tahun}/{group}', 'ReportanggaranController@searchData');
    Route::get('reportanggaran/printpdf/{tahun}/{group}', 'ReportanggaranController@PrintPdf');

    Route::resource('reportrealisasi', 'ReportRealisasiController');
    Route::get('reportrealisasi/search/{tahun}/{group}', 'ReportRealisasiController@searchData');

    Route::resource('reportcf', 'ReportCfController');
    Route::get('reportcf/search/{tahun}/{group}', 'ReportCfController@searchData');
    Route::get('reportcf/print/{tahun}/{kodegroup}', 'ReportCfController@print')->name('reportcf.print');
    Route::get('reportcfdetil/search/{coa}/{tahun}/{bulan}', 'ReportCfController@searchdetil');

    Route::resource('reportperiode', 'ReportperiodeController');
    Route::get('reportperiode/search/{periode}/{tahun}/{group}', 'ReportperiodeController@searchData');

    Route::resource('reportjenis', 'ReportjenisController');
    Route::get('reportjenis/search/{coa}/{periode}/{tahun}/{group}', 'ReportjenisController@searchData');

    Route::resource('reportkumulatif', 'ReportkumulatifController');
    Route::get('reportkumulatif/search/{start_period}/{end_period}/{tahun}/{group}', 'ReportkumulatifController@searchData');


    Route::resource('reportperbulan', 'ReportperbulanController');
    Route::get('reportperbulan/search/{start_period}/{tahun}/{group}', 'ReportperbulanController@searchData');
    Route::get('reportperbulan/search/{start_period}/{end_period}/{tahun}/{group}', 'ReportperbulanController@searchData');
    // Route::get('reportperbulan2/search/{start_period}/{end_period}/{tahun}/{group}', 'ReportperbulanController@searchData');

    Route::resource('reportkodanggaran', 'ReportkodanggaranController');
    Route::get('reportkodanggaran/search/{start_period}/{tahun}/{group}', 'ReportkodanggaranController@searchData');


    Route::resource('reportdetail', 'ReportdetailController');
    Route::get('reportdetail/search/{group}/{tahun}/{periode}/{anggaran}/{status}', 'ReportdetailController@searchData');
    Route::get('reportdetail/getanggaran/{start_periode}/{group}', 'ReportdetailController@getanggaran');

    Route::resource('reportkumulatif2', 'Reportkumulatif2Controller');
    Route::get('reportkumulatif2/search/{start_period}/{end_period}/{tahun}/{group}', 'Reportkumulatif2Controller@searchData');

    Route::resource('reportprogress', 'ReportprogressController');
    Route::get('reportprogress/search/{start_period}/{end_period}/{tahun}/{group}/{status}', 'ReportprogressController@searchData');

    Route::resource('incomecf', 'IncomecfController');
    Route::get('incomecf-download-excel', 'IncomecfController@downloadExcel');
    Route::post('incomecf-import-excel', 'IncomecfController@importExcel');
    Route::get('incomecf/edit-income/{id}', 'IncomecfController@editDataincome');
    Route::post('incomecf/update', 'IncomecfController@update')->name('incomecf.update');
});

//Aplikasi E-Library
Route::group(['prefix' => 'library', 'as' => 'library.', 'namespace' => 'Library', 'middleware' => ['auth']], function () {
    Route::resource('dashboard', 'DashboardController');

    Route::resource('peminjaman', 'PeminjamanController');

    Route::resource('file', 'FileController');
    Route::get('file/master/{kategori}/{lokasi}', 'FileController@dataMasterFile');
});

//Aplikasi GASystem
Route::group(['prefix' => 'gasystem', 'as' => 'gasystem.', 'namespace' => 'Gasystem', 'middleware' => ['auth']], function () {
    Route::resource('dashboard', 'DashboardController');

    // Route::resource('realisasi-document', 'RealisasiDocumentController');
    // Route::post('realisasi-document/realisasi', 'RealisasiDocumentController@realisasiDocument');
    // Route::post('realisasi-document/reject', 'RealisasiDocumentController@rejectDocument');
});
