<?php

use Route69\Route;
use Route69\Route69;
use Route69\RouteParams;

require_once '../Route69.php';

$app = new Route69();

$app->config(function (Route $route){
    $route->when('/age/#id', [
        'controller' => 'age'
    ])->when('/username/:username', [
        'controller' => 'name'
    ])->when('/color/@color', [
        'controller' => 'color'
    ]);
});

// example route: /age/15
$app->controller('age', function(RouteParams $routeParams){
    echo '<h1>Your age is: ' . $routeParams->id . '</h1>';
});

// example route: /username/billy123
$app->controller('username', function(RouteParams $routeParams){
    echo '<h1>Your username is: ' . $routeParams->username . '</h1>';
});

// example route: /color/red
$app->controller('color', function(RouteParams $routeParams){
    echo '<h1>Your color is: ' . $routeParams->color . '</h1>';
});
