<?php

namespace App\controllers;
use GuzzleHttp\Client;

class MainController extends CoreController {
  public function home() {
    $this->show('home');
  }

  public function exchange() {
    $client = new Client([
      'timeout' => 2.0,
    ]);
    // access code that is in query string
    $code = $_GET['code'];
    $tokenEndpoint = 'https://discord.com/api/oauth2/token';
    $redirectURI = 'http://localhost:8000/connect';

    try {
      // exchanging the access code for an access token
      $response = $client->request('POST', $tokenEndpoint, [
        'form_params' => [
          'client_id' => $_ENV['CLIENT_ID'],
          'client_secret'=> $_ENV['CLIENT_SECRET'],
          'grant_type'=> 'authorization_code',
          'code'=> $code,
          'redirect_uri'=> $redirectURI,
          'scope'=> urlencode('identify email')
        ]
      ]);
      $accessToken = json_decode($response->getBody())->access_token;

      // if the exchange is successful, calling the API endpoint with the access token
      $response = $client->request('GET', 'https://discord.com/api/v8/users/@me', [
        'headers' => [
          'Authorization' => 'Bearer ' . $accessToken
        ]
      ]);
      $response = json_decode($response->getBody());
      $username = $response->username;
      // if username exists, it is saved in the session
      if($username) {
        session_start();
        $_SESSION['username'] = $username;
        header('Location: http://localhost:8000/secret');
        exit();
      }
    } catch(\GuzzleHttp\Exception\ClientException $exception) {
        exit($exception->getMessage());
    }
  }

  public function exchangeGoogle() {
    $client = new Client([
      'timeout'  => 2.0,
    ]);
    // access code that is in query string
    $code = $_GET['code'];

    try {
        // requesting the discovery document
        $response = $client->request('GET', 'https://accounts.google.com/.well-known/openid-configuration');
        $discoveryJSON = json_decode((string)$response->getBody());
        // retrieving the token endpoint (to exchange the access code for a token)
        $tokenEndpoint = $discoveryJSON->token_endpoint;
        // exchanging code for token
        $response = $client->request('POST', $tokenEndpoint, [
            'form_params' => [
                'code' => $code,
                'client_id' => $_ENV['GOOGLE_CLIENT_ID'],
                'client_secret' => $_ENV['GOOGLE_CLIENT_SECRET'],
                'redirect_uri' => 'http://localhost:8000/connect-via-google',
                'grant_type' => 'authorization_code'
            ]
        ]);
        $accessToken = json_decode($response->getBody())->access_token;
        // retrieving the userinfo endpoint (to obtain info on user)
        $userinfoEndpoint = $discoveryJSON->userinfo_endpoint;
        // requesting userinfo thanks to accessToken
        $response = $client->request('GET', $userinfoEndpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ]
        ]);
        $response = json_decode($response->getBody());
        $username = $response->name;
        // if username exists, it is saved in the session
        if ($username) {
            session_start();
            $_SESSION['username'] = $username;
            header('Location: http://localhost:8000/secret');
            exit();
        }
    } catch(\GuzzleHttp\Exception\ClientException $exception) {
        exit($exception->getMessage());
    }
  }

  public function secret() {
    $this->show('secret');
  }
}