<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpMimeMailParser\Parser;
class MailBoxController extends Controller
{
    private $google;
    private $service;

    public function __construct()
    {
      $this->google = new \GoogleApi();
      $this->service = new \Google_Service_Gmail($this->google->getClient());
      $this->middleware('google');
    }

    public function mailbox(Request $request)
    {
        $opt = ['maxResults' => '10'];
        if($request->q){
            $opt['q'] = $request->q;
        }
        if($request->pageToken){
            $opt['pageToken'] = $request->pageToken;
        }

      $data = $this->list($opt);
      return view('mailbox', compact('data'));
    }

    public function show($id)
    {
        $result = $this->get($id, ['format' => 'raw'])['raw'];
        $switched = str_replace(['-', '_'], ['+', '/'], $result);
        $decodedMessage = base64_decode($switched);
        $parser = (new Parser)->setText($decodedMessage);
        return view('read', compact('parser'));
    }
    public function download($id, $index)
    {
        $result = $this->get($id, ['format' => 'raw'])['raw'];
        $switched = str_replace(['-', '_'], ['+', '/'], $result);
        $decodedMessage = base64_decode($switched);
        $parser = (new Parser)->setText($decodedMessage);
        $attach = $parser->getAttachments()[$index];
        header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.$attach->getFileName().'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
        return $attach->getContent();
    }

    private function list($opt = [])
    {
        $callback = [];
        $messages = [];

        $results = $this->service->users_messages->listUsersMessages('me', $opt);

          foreach ($results->getMessages() as $result) {
            $messages[] = $this->parser($this->get($result['id'], ['format' => 'metadata', 'metadataHeaders' => ['date', 'from', 'subject']]));
            // dd($messages);
          }
          $pageToken =  $results->getNextPageToken();
          if(array_key_exists('q', $opt)){
              $callback['nextPage'] = url()->current().'?q='.$opt['q']. '&pageToken='. $pageToken;
              $callback['title'] = $this->getTitle(explode(':', $opt['q'])[1]);
          }else{
              $callback['nextPage'] = url()->current().'?pageToken='. $pageToken;
              $callback['title'] = $this->getTitle();
          }
          $callback['messages'] = $messages;
          $callback['prevPage'] = url()->previous();
          return $callback;
    }



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
