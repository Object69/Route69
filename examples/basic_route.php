<?php

use Route69\Route;
use Route69\Route69;

require_once '../Route69.php';

$app = new Route69();

$app->config(function (Route $route){
    $route->when('/test', [
        'controller' => 'test'
    ]);
});

$app->controller('test', function(){
    echo '<h1>Welcome</h1>';
});
