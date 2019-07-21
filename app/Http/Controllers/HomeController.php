<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Email;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      $data['email'] = [];
      $mail = Email::all('status');
      $data['user'] = count(User::all('id'));
      $pending = 0;
      $approved = 0;
      $declined = 0;
      foreach ($mail as $stat) {
        if($stat->status == 0){
          $pending += 1;
        }
        if($stat->status == 1){
          $approved += 1;
        }
        if($stat->status == 2){
          $declined += 1;
        }
      }
        $data['email'] = [
          'declined' => $declined,
          'approved' => $approved,
          'pending' => $pending
        ];
        return view('home', $data);
    }
}
