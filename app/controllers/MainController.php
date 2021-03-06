<?php

namespace App\controllers;
use GuzzleHttp\Client;

class MainController extends CoreController {
  public function home() {
    $this->show('home');
  }

  public function exchangeDiscord() {
    $client = new Client([
      'timeout' => 2.0,
    ]);
    // when the state parameter is returned, it is compared with the state previously stored in the session, if they do not match there is a CSRF risk and the script is terminated
    session_start();
    if($_SESSION['state'] !== $_GET['state']) {
      http_response_code(403);
      exit('CSRF risk, abort');
    }
    // else retrieves access code that is in query string
    $code = $_GET['code'];
    $tokenEndpoint = 'https://discord.com/api/oauth2/token';
    $redirectURI = $_ENV['DISCORD_REDIRECT_URI'];

    try {
      // exchanging the access code for an access token
      $response = $client->request('POST', $tokenEndpoint, [
        'form_params' => [
          'client_id' => $_ENV['DISCORD_CLIENT_ID'],
          'client_secret'=> $_ENV['DISCORD_CLIENT_SECRET'],
          'grant_type'=> 'authorization_code',
          'code'=> $code,
          'redirect_uri'=> $redirectURI,
          'scope'=> urlencode('identify email'),
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
        $_SESSION['username'] = $username;
        header('Location: /secret');
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
    // when the state parameter is returned, it is compared with the state previously stored in the session, if they do not match there is a CSRF risk and the script is terminated
    session_start();
    if($_SESSION['state'] !== $_GET['state']) {
      http_response_code(403);
      exit('CSRF risk, abort');
    }
    // else retrieves access code that is in query string
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
                'redirect_uri' => $_ENV['GOOGLE_REDIRECT_URI'],
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
            header('Location: /secret');
            exit();
        }
    } catch(\GuzzleHttp\Exception\ClientException $exception) {
        exit($exception->getMessage());
    }
  }

  public function exchangeGitHub() {
    $client = new Client([
      'timeout' => 2.0,
    ]);
    // when the state parameter is returned, it is compared with the state previously stored in the session, if they do not match there is a CSRF risk and the script is terminated
    session_start();
    if($_SESSION['state'] !== $_GET['state']) {
      http_response_code(403);
      exit('CSRF risk, abort');
    }
    // else retrieves access code that is in query string
    $code = $_GET['code'];
    $tokenEndpoint = 'https://github.com/login/oauth/access_token';
    $redirectURI = $_ENV['GITHUB_REDIRECT_URI'];

    try {
      // exchanging the access code for an access token
      $response = $client->request('POST', $tokenEndpoint, [
        'form_params' => [
          'client_id' => $_ENV['GITHUB_CLIENT_ID'],
          'client_secret'=> $_ENV['GITHUB_CLIENT_SECRET'],
          'code'=> $code,
          'redirect_uri'=> $redirectURI,
          'state'=> $_GET['state'],
        ],
        'headers' => [
          'Accept'     => 'application/json',
        ]
      ]);
      $accessToken = json_decode($response->getBody())->access_token;

      // if the exchange is successful, calling the API endpoint with the access token
      $response = $client->request('GET', 'https://api.github.com/user', [
        'headers' => [
          'Authorization' => 'token ' . $accessToken
        ]
      ]);
      $response = json_decode($response->getBody());
      $username = $response->login;
      // if username exists, it is saved in the session
      if($username) {
        $_SESSION['username'] = $username;
        header('Location: /secret');
        exit();
      }
    } catch(\GuzzleHttp\Exception\ClientException $exception) {
        exit($exception->getMessage());
    }
  }

  public function secret() {
    $this->show('secret');
  }

  public function logout() {
    session_start();
    // destroys the session's variables
    $_SESSION = array();
    // destroys session cookie
    // if (ini_get("session.use_cookies")) {
    //     $params = session_get_cookie_params();
    //     setcookie(session_name(), '', time() - 42000,
    //         $params["path"], $params["domain"],
    //         $params["secure"], $params["httponly"], $params["samesite"]
    //     );
    // }
    // <= not put to use in production due to public suffix list issue (https://devcenter.heroku.com/articles/cookies-and-herokuapp-com)
    session_destroy();
    $this->show('logout');
  }
}