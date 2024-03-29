<?php


/**
 *
 */


namespace App\Http;


use Carbon\Carbon;
class GoogleApi
{
  private $scope = [
  \Google_Service_Gmail::MAIL_GOOGLE_COM,
  \Google_Service_Gmail::GMAIL_COMPOSE,
  // \Google_Service_Gmail::GMAIL_INSERT,
  // \Google_Service_Gmail::GMAIL_LABELS,
  // \Google_Service_Gmail::GMAIL_METADATA,
  \Google_Service_Gmail::GMAIL_MODIFY,
  \Google_Service_Gmail::GMAIL_READONLY,
  \Google_Service_Gmail::GMAIL_SEND,
  // \Google_Service_Gmail::GMAIL_SETTINGS_BASIC,
  // \Google_Service_Gmail::GMAIL_SETTINGS_SHARING,
  ];

  public $client;

  private $tokenPath = 'token.json';

  public function __construct()
  {
    $this->client = $this->start([
      'clientId' => env('GOOGLE_CLIENT_ID'),
      'secret' => env('GOOGLE_CLIENT_SECRET'),
      'redirect' => env('GOOGLE_REDIRECT_API')
    ]);
  }


  private function start($access){
    $client = new \Google_Client();
    $client->setApplicationName('Gmail API PHP Quickstart');
    $client->setScopes($this->scope);
    $client->setClientId($access['clientId']);
    $client->setClientSecret($access['secret']);
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');
    $client->setRedirectUri($access['redirect']);
    return $client;
  }

  public function getAuthUrl(){
    return $this->client->createAuthUrl();
  }

  public function getAccessTokenWithAuthCode($code){
    $accessToken = $this->client->fetchAccessTokenWithAuthCode($code);
    if (array_key_exists('error', $accessToken)) {
                  throw new Exception(join(', ', $accessToken));
              }

    session(['access_api' => json_encode($this->client->getAccessToken())]);

    return $accessToken;

  }

  public function getClient(){
      return $this->client;
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
      return ['login' => false, 'redirect' => $this->getAuthUrl()];
    }
    return ['login' => true];
  }
}
