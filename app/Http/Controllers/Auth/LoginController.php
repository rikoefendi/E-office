<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */

     protected function sendLoginResponse(Request $request)
     {
         $request->session()->regenerate();
         $this->clearLoginAttempts($request);
         $user = $this->guard()->user();
         if($user->status == 2){
           // dd($user);
           $this->guard()->logout();

           $request->session()->invalidate();
           throw ValidationException::withMessages([
               $this->username() => ['Akun anda di suspend'],
           ]);
         }
         session()->flash('status', 'Berhasil Login');
         return $this->authenticated($request, $user)
                 ?: redirect()->intended($this->redirectPath());
     }

      public function logout(Request $request)
      {
          $this->guard()->logout();

          $request->session()->invalidate();

          return $this->loggedOut($request) ?: redirect('/')->with('status', 'Berhasil Logout');
      }

}
