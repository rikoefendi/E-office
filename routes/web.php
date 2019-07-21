<?php declare(strict_types = 1);

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


use Illuminate\Http\Request;

use PhpMimeMailParser\Parser;
Route::get('/', function (Request $req) {
    return view('auth.login');
});

Auth::routes();

Route::get('/users', 'UserController@index');
Route::get('user/create', 'UserController@create');
Route::post('user/store', 'UserController@store');
Route::get('user/{id}/edit', 'UserController@edit');
Route::post('user/update', 'UserController@update');
Route::get('user/{id}/delete', 'UserController@destroy');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/test', function (Request $req) {
  $google = new \App\Http\GoogleApi();
  if($req->code){
    $google->getAccessTokenWithAuthCode($req->code);
    return back();
  }
  if($google->isAccessTokenExpired()){
    return redirect($google->getAuthUrl());
  }

  return view('test');
});

Route::get('/mailbox',  'MailBoxController@mailbox');

Route::get('/compose',  'ComposeController@create');

Route::post('/compose', 'ComposeController@compose');
Route::get('/forward/{id}', 'ComposeController@forward');

Route::get('/read/{id}',  'MailBoxController@show');

Route::get('/read/{id}/download/{index}', 'MailBoxController@download');
Route::get('/mailbox/{id}/to-approval', 'MailBoxController@toApproval');
Route::get('/approval', 'ApprovalController@index');
Route::get('/approval/{id}', 'ApprovalController@show');
Route::get('/approval/{id}/{state}', 'ApprovalController@changeStat');
