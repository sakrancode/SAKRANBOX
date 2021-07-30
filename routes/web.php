<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

/**
 * AUTH User route
 */
Route::group(['middleware'=>'auth'], function() {

    Route::group(['middleware'=>'role:admin'], function() {
        Route::post('user/getDataUsers', 'UserController@getDataUsers')->name('user.getDataUsers');
        Route::resource('user', 'UserController');

        #penggunaan tanda tanya ?, untuk kondisi bisa jika parameter null
        Route::get('document/createFile/{parent_id?}', 'DocumentController@createFile')->name('document.createFile');
        Route::get('document/createFolder/{parent_id?}', 'DocumentController@createFolder')->name('document.createFolder');
        Route::post('document/storeFile', 'DocumentController@storeFile')->name('document.storeFile');
        Route::post('document/storeFolder', 'DocumentController@storeFolder')->name('document.storeFolder');
        Route::get('document/{document}/editFile', 'DocumentController@editFile')->name('document.editFile');
        Route::get('document/{document}/editFolder', 'DocumentController@editFolder')->name('document.editFolder');
        Route::match(['put', 'patch'],'document/{document}/updateFile', 'DocumentController@updateFile')->name('document.updateFile');
        Route::match(['put', 'patch'],'document/{document}/updateFolder', 'DocumentController@updateFolder')->name('document.updateFolder');

        Route::get('document/copyFile/{document}', 'DocumentController@copyFile')->name('document.copyFile');
        Route::get('document/copyFolder/{document}', 'DocumentController@copyFolder')->name('document.copyFolder');
        Route::get('document/getJtreeData/{co_id}', 'DocumentController@getJtreeData')->name('document.getJtreeData');
        Route::match(['put', 'patch'],'document/{document}/updateCopyFile', 'DocumentController@updateCopyFile')->name('document.updateCopyFile');
        Route::match(['put', 'patch'],'document/{document}/updateCopyFolder', 'DocumentController@updateCopyFolder')->name('document.updateCopyFolder');
        Route::get('document/moveDocument/{document}', 'DocumentController@moveDocument')->name('document.moveDocument');
        Route::match(['put', 'patch'],'document/{document}/updateMoveDocument', 'DocumentController@updateMoveDocument')->name('document.updateMoveDocument');

        Route::resource('document', 'DocumentController')->except(['create', 'store', 'edit', 'update', 'show']);
    });

    Route::group(['middleware'=>'role:admin|user'], function() {
        // Route::resource('user', 'UserController');
        Route::get('/', 'DashboardController@index');
        // Route::get('/home', 'HomeController@index')->name('home');
        Route::get('/home', 'DashboardController@index')->name('home');
        // Route::get('/document', 'DashboardController@document')->name('document');
        Route::post('dashboard/getDataDocument', 'DashboardController@getDataDocument')->name('dashboard.getDataDocument');

        Route::get('document/{document}/subFolder', 'DocumentController@subFolder')->name('document.subFolder');
        Route::post('document/getDataSubFolder', 'DocumentController@getDataSubFolder')->name('document.getDataSubFolder');
        Route::get('document/download/{document}', 'DocumentController@download')->name('document.download');
        Route::resource('document', 'DocumentController')->except(['create', 'store', 'edit', 'update', 'show', 'destroy']);
    });

});

Route::fallback(function(){
    if (Auth::check()) {
        abort(404);
    }else{
        //is a guest so redirect
        return redirect('login');
    }
});
