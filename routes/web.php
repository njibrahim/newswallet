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
Auth::routes();

Route::get('/', function () {
    return redirect("home");
});

Route::get('about', 'HomeController@about');
Route::get('do', 'HomeController@do');
Route::get('home', 'HomeController@index')->name('home');
Route::get('article/{id}', 'HomeController@article');
Route::get('pockets', 'HomeController@pockets');
Route::any('category', 'HomeController@category');
Route::any('articles', 'HomeController@articles');
Route::any('reports', 'HomeController@reports');
