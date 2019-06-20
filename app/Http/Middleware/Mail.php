<?php

namespace App\Http\Middleware;

use Closure;

class Mail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      // dd(json_decode(session('access_api'), true));
      // $google = new \App\Http\Controllers\GoogleApi();
      // if($google->isAccessTokenExpired()){
      //   return redirect($google->getAuthUrl());
      // }
      // // dd($google->getAccessToken());
      // // $google->setAccessToken($this->google->getAccessToken());
      //   return $next($request);
    }
}
