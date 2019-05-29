<?php



/**
 *
 */
class GoogleApi
{
  private $scope = [
  Google_Service_Gmail::MAIL_GOOGLE_COM,
  Google_Service_Gmail::GMAIL_COMPOSE,
  Google_Service_Gmail::GMAIL_INSERT,
  Google_Service_Gmail::GMAIL_LABELS,
  // Google_Service_Gmail::GMAIL_METADATA,
  Google_Service_Gmail::GMAIL_MODIFY,
  Google_Service_Gmail::GMAIL_READONLY,
  Google_Service_Gmail::GMAIL_SEND,
  Google_Service_Gmail::GMAIL_SETTINGS_BASIC,
  Google_Service_Gmail::GMAIL_SETTINGS_SHARING,
  ];

  public $client;

  private $tokenPath = 'token.json';

  public function __construct()
  {
    $this->client = $this->start([
      'clientId' => '890877645480-2kpk90aje5j1jtksim26qcb16sajjh4r.apps.googleusercontent.com',
      'secret' => 'oyOuGZ7tGeZa4W70fO1jtNcc',
      'redirect' => 'http://localhost:8000/test'
    ]);
    if(!$this->isAccessTokenExpired()){
      $this->client->setAccessToken($this->getAccessToken());
    }

  }


  private function start($access){
    $client = new Google_Client();
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
    if (!file_exists($this->tokenPath)) {
              mkdir($this->tokenPath, 0700, true);
          }
    if(file_put_contents($this->tokenPath, json_encode($this->client->getAccessToken())));

    return $accessToken;

  }

  public function getAccessToken(){
    if (!file_exists($this->tokenPath)) {
              file_put_contents($this->tokenPath, json_decode('{}'));
          }
    return json_decode(file_get_contents($this->tokenPath), true);
  }

  public function isAccessTokenExpired(){
    if(!$this->getAccessToken()) return true;
    $this->client->setAccessToken($this->getAccessToken());
    return $this->client->isAccessTokenExpired();
  }

  public function getClient(){
      return $this->client;
  }

}
