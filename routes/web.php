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
     $google = new GoogleApi();

     $service = new Google_Service_Gmail($google->getClient());
//
// // Print the labels in the user's account.
//
      $user = 'me';
//

      // dd($results);
              $get = $service->users_messages->get($user, '1674d29fad32a88c');
              dd($get);
//       $messages = [];
//
//       foreach ($results['messages'] as $result) {
//
//         $rawData = $get['raw'];
//         $switched = str_replace(['-', '_'], ['+', '/'], $rawData);
//
//         $decodedMessage = base64_decode($switched);
//         $parser = (new Parser)->setText($decodedMessage);
//         $messages[] = $parser->getMessageBody();
//       }
//       // $switched = str_replace(['-', '_'], ['+', '/'], $results);
//
//       dd(collect($messages));
//
//       // $parser->setText(base64_decode($switched));
//       $rawData = $results['raw'];
//     $switched = str_replace(['-', '_'], ['+', '/'], $rawData);
//
//     $decodedMessage = base64_decode($switched);
//     $parser = (new Parser)->setText($decodedMessage);
//     $uri = $parser->getAttachments()[0]->getContent();
//     header('Content-Description: File Transfer');
//     header('Content-Type: application/octet-stream');
//     header('Content-Disposition: attachment; filename="test.docx"');
//     header('Expires: 0');
//     header('Cache-Control: must-revalidate');
//     header('Pragma: public');
//     return $uri;
//     return fopen($uri, 'r');
    // if(!$decodedMessage){
    //     $decodedMessage = FALSE;
    // }
    // dd($decodedMessage);
      // return $decodedMessage;
})->middleware('google');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/test', function (Request $req) {
  $google = new GoogleApi();
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

Route::get('/compose',  function() {
  return view('compose');
});

Route::get('/read',  function() {
  return view('read');
});
