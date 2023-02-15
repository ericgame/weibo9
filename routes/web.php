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

//靜態頁面
Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');

//會員註冊
Route::get('signup', 'UsersController@create')->name('signup');

/*會員註冊
Route::get('/users', 'UsersController@index')->name('users.index');
Route::get('/users/create', 'UsersController@create')->name('users.create');
Route::get('/users/{user}', 'UsersController@show')->name('users.show');
Route::post('/users', 'UsersController@store')->name('users.store');
Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
Route::patch('/users/{user}', 'UsersController@update')->name('users.update');
Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');

GET|HEAD        users ..... users.index › UsersController@index
POST            users ..... users.store › UsersController@store
GET|HEAD        users/create ..... users.create › UsersController@create
GET|HEAD        users/{user} ..... users.show › UsersController@show
PUT|PATCH       users/{user} ..... users.update › UsersController@update
DELETE          users/{user} ..... users.destroy › UsersController@destroy
GET|HEAD        users/{user}/edit ..... users.edit › UsersController@edit
*/
Route::resource('users', 'UsersController');

//會員登入、登出
Route::get('login', 'SessionsController@create')->name('login');
Route::post('login', 'SessionsController@store')->name('login');
Route::delete('logout', 'SessionsController@destroy')->name('logout');

//帳號激活
Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');

//忘記密碼
Route::get('password/reset', 'PasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'PasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'PasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'PasswordController@reset')->name('password.update');

/*微博相關的操作
POST            statuses ................ statuses.store › StatusesController@store
DELETE          statuses/{status} ....... statuses.destroy › StatusesController@destroy
*/
Route::resource('statuses', 'StatusesController', ['only' => ['store', 'destroy']]);

//粉絲頁面
Route::get('/users/{user}/followings', 'UsersController@followings')->name('users.followings');
Route::get('/users/{user}/followers', 'UsersController@followers')->name('users.followers');


