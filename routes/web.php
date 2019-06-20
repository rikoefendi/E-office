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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/test', function (Request $req) {
  $google = new \App\Http\GoogleApi();
  if($req->code){
    $google->getAccessTokenWithAuthCode($req->code);
    return redirect('/home');
  }
  if($google->isAccessTokenExpired()){
    return redirect($google->getAuthUrl());
  }

  return view('test');
});

Route::get('/mailbox',  'MailBoxController@mailbox');

Route::get('/compose',  'ComposeController@create');

Route::post('/compose', 'ComposeController@compose');

Route::get('/read/{id}',  'MailBoxController@show');

Route::get('/read/{id}/download/{index}', 'MailBoxController@download');
