<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpMimeMailParser\Parser;
use App\Email;

class ApprovalController extends Controller
{
    public function __construct(){
      $this->middleware('auth');
      parent::__construct();
    }

    public function index(){
      if(!$this->isLoggedInGoogle()['login']) return view('api', ['redirect' => $this->isLoggedInGoogle()['redirect']]);
      $data = Email::all('subject', 'from', 'date', 'status', 'id');
      // return view('mailbox', compact('data'));
      return view('approval', compact('data'));
    }

    public function show($id)
    {
      if(!$this->isLoggedInGoogle()['login']) return view('api', ['redirect' => $this->isLoggedInGoogle()['redirect']]);

        $data = Email::findOrFail($id);
        $switched = str_replace(['-', '_'], ['+', '/'], $data['raw']);
        $decodedMessage = base64_decode($switched);
        $parser = (new Parser)->setText($decodedMessage);
        return view('approval-read', compact('parser', 'data'));
    }

    public function changeStat($id, $state)
    {
      $states = $state == 'approve' ? 1: 2;
      $email = Email::findOrFail($id);
      $email->status = $states;
      $email->save();

      return back()->with('message', 'Email berhasil di'.$state);
    }
}
