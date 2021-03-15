<?php

require_once '../vendor/autoload.php';

$router = new AltoRouter();

$router->map(
    'GET',
    '/',
    ['controller' => 'CoreController', 'method' => 'show', ],
    'home'
);

// match current request url
$match = $router->match();

// if no match throw 404 status
if(!is_array($match)) {
    header('HTTP/1.0 404 Not Found');
    exit('404 Not Found');
}
// else the script continues
// retrieves name of the controller to use
$controllerToUse = $match['target']['controller'];
// retrieves name of the method to use
$methodToUse = $match['target']['method'];

$controllerToUse = 'App\\controllers\\' . $controllerToUse;

$controller = new $controllerToUse();

$controller->$methodToUse($match['name']);