<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use PhpMimeMailParser\Parser;
use App\Email;


class MailBoxController extends Controller
{

    public function mailbox(Request $request)
    {
      //cek token is exists
      if(!$this->isLoggedInGoogle()['login']) return view('api', ['redirect' => $this->isLoggedInGoogle()['redirect']]);

      //params api gmail
      //get max 10 message
        $opt = ['maxResults' => '10'];
        //if exist query added in url return to params $opt
        if($request->q){
            $opt['q'] = $request->q;
        }
        //if next page add token in params $opt
        if($request->pageToken){
            $opt['pageToken'] = $request->pageToken;
        }
        // get list gmail in another function
      $data = $this->list($opt);
      //return data to view
      return view('mailbox', compact('data'));
    }

    public function show($id)
    {
      //cek token is exists
      if(!$this->isLoggedInGoogle()['login']) return view('api', ['redirect' => $this->isLoggedInGoogle()['redirect']]);
        //get message email by id in format raw
        $result = $this->get($id, ['format' => 'raw']);
        $threadId = $result['threadId'];
        $messageId = $result['id'];
        // parsing message raw with php-mime-mail-parser
        $switched = str_replace(['-', '_'], ['+', '/'], $result['raw']);
        $decodedMessage = base64_decode($switched);
        $parser = (new Parser)->setText($decodedMessage);
        // return view and result of parser
        return view('read', compact('parser', 'threadId', 'messageId'));
    }

    ///to download attachment message gmail
    public function download($id, $index)
    {
      //cek token is exists
      if(!$this->isLoggedInGoogle()['login']) return view('api', ['redirect' => $this->isLoggedInGoogle()['redirect']]);
      //get message email by id in format raw
        $result = $this->get($id, ['format' => 'raw'])['raw'];
        // parsing message raw with php-mime-mail-parser
        $switched = str_replace(['-', '_'], ['+', '/'], $result);
        $decodedMessage = base64_decode($switched);
        $parser = (new Parser)->setText($decodedMessage);
        // return response attachment message email
        $attach = $parser->getAttachments()[$index];
        header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.$attach->getFileName().'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
        return $attach->getContent();
    }

    public function toApproval($id)
    {
      if(!$this->isLoggedInGoogle()['login']) return view('api', ['redirect' => $this->isLoggedInGoogle()['redirect']]);
      if(Email::where('email_id', $id)->first()){
        return back()->with('status', 'Email sudah disampaikan');
      }
      $result = $this->get($id, ['format' => 'raw']);
      $switched = str_replace(['-', '_'], ['+', '/'], $result['raw']);
      $decodedMessage = base64_decode($switched);
      $parser = (new Parser)->setText($decodedMessage);
      $email = new Email();
      $email->raw = $result['raw'];
      $email->email_id = $result['id'];
      $email->subject = $parser->getHeader('subject');
      $email->from = $parser->getHeader('from');
      $email->date = $parser->getHeader('date');
      $email->save();

      return back()->with('status', 'Berhasil mengirim ke atasan');

    }

    private function list($opt = [])
    {
      //cek token is exists
      if(!$this->isLoggedInGoogle()['login']) return view('api', ['redirect' => $this->isLoggedInGoogle()['redirect']]);
        $callback = [];
        $messages = [];
        //function from google service gmail
        $results = $this->service->users_messages->listUsersMessages('me', $opt);
          //parser message gmail get the data date, form, subject
          foreach ($results->getMessages() as $result) {
            $messages[] = $this->parser($this->get($result['id'], ['format' => 'metadata', 'metadataHeaders' => ['date', 'from', 'subject']]));
          }

          //get next page token
          $pageToken =  $results->getNextPageToken();
          ///callback data
          if(array_key_exists('q', $opt)){
              //if exist query concat with nextpagetoken
              $callback['nextPage'] = url()->current().'?q='.$opt['q']. '&pageToken='. $pageToken;
              //title for listing message
              $callback['title'] = $this->getTitle(explode(':', $opt['q'])[1]);
          }else{
            //if not exist query concat url with nextpagetoken
              $callback['nextPage'] = url()->current().'?pageToken='. $pageToken;
              $callback['title'] = $this->getTitle();
          }

          //callback messages
          $callback['messages'] = $messages;
          //previous page
          $callback['prevPage'] = url()->previous();
          // return all callback
          return $callback;
    }


    //get message by id
    private function get($id, $opt = [])
    {

        return $this->service->users_messages->get('me', $id, $opt);
    }

    private function parser($message)
    {
        $part = $message->getPayload()->getHeaders();

        foreach ($part as $key => $value) {
            if($part[$key]['name'] == 'Date'){
                $message['date'] = $part[$key]['value'];
            }
            if($part[$key]['name'] == 'From'){
                $message['from'] = $part[$key]['value'];
            }
            if($part[$key]['name'] == 'Subject'){
                $message['subject'] = $part[$key]['value'];
            }
        }
        return $message;
    }

    private function getTitle($q = '')
    {
        switch ($q) {
            case 'sent':
                return 'Sent';
                break;
            case 'starred':
                return 'Starred';
                break;
            case 'draft':
                return 'Drafts';
                break;
            default:
                return 'Inbox';
                break;
        }
    }
}
