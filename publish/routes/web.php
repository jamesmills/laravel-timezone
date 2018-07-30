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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
/*
|--------------------------------------------------------------------------
| These routes were added by jamesmills/laravel-admin page
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'roles'], 'roles' => 'admin'], function () {
    Route::get('/', 'Admin\AdminController@index')->name('dashboard');
    Route::resource('roles', '\JamesMills\LaravelTimezone\Controllers\Admin\RolesController');
    Route::resource('permissions', '\JamesMills\LaravelTimezone\Controllers\Admin\PermissionsController');
    Route::resource('users', '\JamesMills\LaravelTimezone\Controllers\Admin\UsersController');
});
