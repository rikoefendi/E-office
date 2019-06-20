<?php

namespace App\Http\Controllers;

use App\Http\GoogleApi;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public $google;
    public $client;
    public $service;

    public function __construct(){
      $this->google = new GoogleApi();
      $this->client = $this->google->getClient();
      $this->service = new \Google_Service_Gmail($this->client);
    }

    public function getAccessToken(){
      return json_decode(session('access_api'), true);
    }

    public function isAccessTokenExpired(){
      if(!$this->getAccessToken()) return true;
      $this->client->setAccessToken($this->getAccessToken());
      return $this->client->isAccessTokenExpired();
    }

    public function isLoggedInGoogle(){
      if($this->isAccessTokenExpired()){
        return ['login' => false, 'redirect' => $this->google->getAuthUrl()];
      }
      return ['login' => true];
    }
}
