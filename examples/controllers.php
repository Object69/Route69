<?php

use Route69\Route;
use Route69\Route69;

require_once '../Route69.php';

$app = new Route69();

/**
 * Routes can be defined two ways, they can be defined directly in the config
 * as a callback, but when you do that then that is only route can access that
 * controller (see the route: '/private').
 *
 * If the controller is defined using a string, than any route can use that
 * controller and the controller is considered public
 * (see the two routes: '/public' and '/i/am/public/too').
 */
$app->config(function (Route $route){
    $route->when('/public', [
        'controller' => 'public',
    ])->when('/private', [
        // example route: /private
        'controller' => function(){
            echo '<h1>I am a private controller</h1>';
        }
    ])->when('/i/am/public/too', [
        'controller' => 'public'
    ]);
});

// example route: /public or /i/am/public/too
$app->controller('public', function(){
    echo '<h1>I am a public controller</h1>';
});
