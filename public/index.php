<?php

require_once '../vendor/autoload.php';

// to enable the use of the .env
if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'] . '/..');
    $dotenv->load();
}

// router
$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$requestData = array('request' => $request, 'method' => $method);

switch ($requestData) {
    case ['request' => '/', 'method' => 'GET']:
        $controllerToUse = 'MainController';
        $methodToUse = 'home';
        break;
        // expected URI is /connect?=<code provided by Discord>, so to make the route match without knowing in advance what the code will be, preg_match is used
        // if the string matches the regex, the return value is 1, else it is 0
    case ['request' => preg_match('/\/connect-via-discord\?(.*)/', $requestData['request']) == 1, 'method' => 'GET']:
        $controllerToUse = 'MainController';
        $methodToUse = 'exchangeDiscord';
        break;
    case ['request' => preg_match('/\/connect-via-google\?(.*)/', $requestData['request']) == 1, 'method' => 'GET']:
        $controllerToUse = 'MainController';
        $methodToUse = 'exchangeGoogle';
        break;
    case ['request' => preg_match('/\/connect-via-github\?(.*)/', $requestData['request']) == 1, 'method' => 'GET']:
        $controllerToUse = 'MainController';
        $methodToUse = 'exchangeGitHub';
        break;
    case ['request' => '/secret', 'method' => 'GET']:
        $controllerToUse = 'MainController';
        $methodToUse = 'secret';
        break;
    case ['request' => '/logout', 'method' => 'GET']:
        $controllerToUse = 'MainController';
        $methodToUse = 'logout';
        break;
    default:
        http_response_code(404);
        header('HTTP/1.0 404 Not Found');
        exit('404 Not Found');
        break;
}

$controllerToUse = 'App\\controllers\\' . $controllerToUse;
$controller = new $controllerToUse();
$controller->$methodToUse();