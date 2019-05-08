<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
	# Get current logged user
    return $request->user();
});

Route::post("login", "ApiController@login");

# Get all user articles
// Route::get("articles", "ApiController@articles");

Route::group(['middleware' => 'auth:api'], function () {
	# Categories
	Route::get("categories", "ApiController@categories");
	Route::get("category/{id}", "ApiController@getCategory");
	Route::post("category/{id?}", "ApiController@postCategory");
	Route::delete("category/{id}", "ApiController@deleteCategory");

	# Get all users 
	Route::get("users", "ApiController@users");

	# ARTICLES
	# Get all user articles
	Route::get("articles", "ApiController@articles");
	# Find article by id
	Route::get("article/{id}", "ApiController@getArticle");
	# Add or update article
	Route::post("article/{id?}", "ApiController@postArticle");
	# Delete article
	Route::delete("article/{id}", "ApiController@deleteArticle");

	Route::any("logout", "ApiController@logout");
});



