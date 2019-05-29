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
      $google = new \GoogleApi();

      if($google->isAccessTokenExpired()){
        return redirect($google->getAuthUrl());
      }
        return $next($request);
    }
}
