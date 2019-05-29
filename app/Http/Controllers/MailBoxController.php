<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
      $this->list([]);
      return view('mailbox');
    }

    public function list($opt)
    {
      $messages = [];

      $results = $this->service->users_messages->listUsersMessages('me', $opt);

      foreach ($results['messages'] as $result) {
        $this->get($result['id']);
      }
    }

    public function get($id)
    {
      dd($this->service->users_messages->get('me', $id, ['format' => 'raw']));
    }
}
