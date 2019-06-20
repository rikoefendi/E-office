<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Service_Gmail_Message;

class ComposeController extends Controller
{
    private $google;
    private $service;

    public function __construct()
    {
        $this->middleware('google');
      $this->google = new \GoogleApi();
      $this->service = new \Google_Service_Gmail($this->google->getClient());
    }

    public function compose(Request $request)
    {
        try {
        $message = (new \Swift_Message('Wonderful Subject'))
          ->setSubject($request->subject)
          ->setTo([$request->to])
          ->setContentType('text/html')
    ->setCharset('utf-8')
    ->attach(\Swift_Attachment::fromPath($request->file('attachment')->getPathName())->setFileName($request->file('attachment')->getClientOriginalName()))
          ->setBody($request->body);
          $message = rtrim(strtr(base64_encode($message->toString()), '+/', '-_'), '=');
          // die($message);

              $msg = new Google_Service_Gmail_Message();
              $msg->setRaw($message);
              // dd($msg);
              $a = $this->service->users_messages->send('me', $msg);
              // die($a);
            } catch (Exception $e) {
                print "An error occurred: " . $e->getMessage();
            }

            echo "string";
    }
}
