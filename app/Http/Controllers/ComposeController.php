<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpMimeMailParser\Parser;
use Carbon\Carbon;
use Google_Service_Gmail_Message;

class ComposeController extends Controller
{

    public function create(Request $request){
        if(!$this->isLoggedInGoogle()['login']) return view('api', ['redirect' => $this->isLoggedInGoogle()['redirect']]);
        $data['reply_to'] = $request->reply;
        $data['threadId'] = $request->threadId;
        $data['subject'] = $request->subject;
        $data['body'] = '';
        $forwardMessage = '';
        return view('compose', compact('data', 'forwardMessage'));
    }

    public function compose(Request $request)
    {
      if(!$this->isLoggedInGoogle()['login']) return view('api', ['redirect' => $this->isLoggedInGoogle()['redirect']]);
        try {
        $message = (new \Swift_Message('Wonderful Subject'))
          ->setSubject($request->subject)
          ->setTo([$request->to])
          ->setContentType('text/html')
          ->setCharset('utf-8')
          ->setBody($request->body);
          if($request->file('attachment')){
            $message->attach(\Swift_Attachment::fromPath($request->file('attachment')->getPathName())->setFileName($request->file('attachment')->getClientOriginalName()));
          }
          // die($message);

              $msg = new Google_Service_Gmail_Message();
              if($request->threadId){
                $message->setReplyTo($request->to);
                $msg->setThreadId($request->threadId);
              }
              $message = rtrim(strtr(base64_encode($message->toString()), '+/', '-_'), '=');
              $msg->setRaw($message);
              // dd($msg);
              $a = $this->service->users_messages->send('me', $msg);
              // die($a);
            } catch (Exception $e) {
                print "An error occurred: " . $e->getMessage();
            }

            return redirect('http://localhost:8000/mailbox?q=in:sent')->with('messages', 'Berhasil Dikirim');
    }

    public function forward($id)
    {
      $parser = $this->show($id);
      $data['reply_to'] = '';
      $data['threadId'] = '';
      $data['subject'] = $parser->getHeader('subject');
      $data['body'] = $parser->getMessageBody('html');
      $forwardMessage = '
      <div dir="ltr" style="color: rgb(34, 34, 34); font-family: Arial, Helvetica, sans-serif; font-size: small;"><br >---------- Forwarded message ---------<br>Dari: '.htmlspecialchars($parser->getHeader('from')).'<br>Date: '.$this->date($parser->getHeader('date')).'<br>Subject: '.htmlspecialchars($parser->getHeader('subject')).'<br></div><div style="color: rgb(34, 34, 34); font-family: Arial, Helvetica, sans-serif; font-size: small;"><br></div><br style="color: rgb(34, 34, 34); font-family: Arial, Helvetica, sans-serif; font-size: small;">
      <div><br></div><div><br></div>';
        return view('compose', compact('data', 'forwardMessage'));
    }

    public function show($id)
    {
      //cek token is exists
      if(!$this->isLoggedInGoogle()['login']) return view('api', ['redirect' => $this->isLoggedInGoogle()['redirect']]);
        //get message email by id in format raw
        $result = $this->get($id, ['format' => 'raw']);
        $threadId = $result['threadId'];
        // parsing message raw with php-mime-mail-parser
        $switched = str_replace(['-', '_'], ['+', '/'], $result['raw']);
        $decodedMessage = base64_decode($switched);
        $parser = (new Parser)->setText($decodedMessage);
        // return view and result of parser
        return $parser;
    }

    private function get($id, $opt = [])
    {

        return $this->service->users_messages->get('me', $id, $opt);
    }

    private function date($date)
    {
      return (New Carbon($date))->locale('id')->isoFormat('ddd, d MMMM Y HH:mm');
    }
}
