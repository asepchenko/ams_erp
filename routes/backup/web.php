<?php

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

    Route::post('report', 'ReportController@index');
    Route::get('report/search/{bulan}/{status}/{dept}/{nama}', 'ReportController@searchData');
    Route::get('report/{id}/print', 'ReportController@printData');
    Route::resource('report', 'ReportController', ['except' => ['store']]);

    Route::resource('file', 'FileController');

    Route::resource('my-document', 'MyDocumentController');

    Route::resource('outstanding-kasbon', 'OutstandingKasbonController');

    Route::resource('notes', 'NotesController');
    Route::post('notes/read', 'NotesController@readNotes');

    Route::post('spd-report', 'SPDReportController@index');
    Route::get('spd-report/search/{bulan}/{status}/{dept}/{nama}', 'SPDReportController@searchData');
    Route::resource('spd-report', 'SPDReportController', ['except' => ['store']]);

    Route::post('kasbon-report', 'KasbonReportController@index');
    Route::get('kasbon-report/search/{bulan}/{status}/{dept}/{nama}', 'KasbonReportController@searchData');
    Route::get('get-name-by-dept/{id}', 'KasbonReportController@getNameByDept');
    Route::resource('kasbon-report', 'KasbonReportController', ['except' => ['store']]);

    Route::resource('my-report', 'MyReportController');
    Route::get('my-report/{id}/view', 'MyReportController@viewData');
    Route::get('my-report/get-report/{id}', 'MyReportController@getReport');
});

//Aplikasi Finance
Route::group(['prefix' => 'finance', 'as' => 'finance.', 'namespace' => 'Finance', 'middleware' => ['auth']], function () {
    Route::resource('dashboard', 'DashboardController');

    Route::resource('realisasi-document', 'RealisasiDocumentController');
    Route::post('realisasi-document/realisasi', 'RealisasiDocumentController@realisasiDocument');
});

//Aplikasi E-Library
Route::group(['prefix' => 'library', 'as' => 'library.', 'namespace' => 'Library', 'middleware' => ['auth']], function () {
    Route::resource('dashboard', 'DashboardController');

    Route::resource('peminjaman', 'PeminjamanController');

    Route::resource('file', 'FileController');
    Route::get('file/master/{kategori}/{lokasi}', 'FileController@dataMasterFile');
});