<?php

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
//frontend
Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

Route::get('/news/view', 'News\NewsController@view');
//backend
Route::group(['prefix' => 'admin','middleware' => 'auth'], function () {

    Route::get('/parse', 'Admin\AdminController@parse');
    Route::resource('/article', 'Admin\ArticleController', ['except' => ['create']]);
    Route::get('api/article', 'Admin\ArticleController@apiArticle')->name('api.article');

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

