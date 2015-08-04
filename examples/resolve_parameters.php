<?php

use Route69\Route;
use Route69\Route69;
use Route69\RouteParams;

require_once '../Route69.php';

$app = new Route69();

$app->config(function (Route $route){
    $route->when('/user/:username', [
        'controller' => 'user',
        'resolve'    => [
            'user' => new User(),
            'wage' => rand(8.50 * 10, 15.00 * 10) / 10
        ]
    ]);
});

// example route: /user/fred123
$app->controller('user', function(RouteParams $routeParams, User $user, $wage){
    $user->setUsername($routeParams->username);
    $user->setWage($wage);
    echo $user->getMessage();
});

/**
 * This is the example User class used in the controller
 * The $user parameter links to this class and is defined
 * in the resolve setting of the route config.
 */
class User{

    protected
            $username = '',
            $wage     = 8.50;

    /**
     * Sets the username
     * @param string $username
     */
    public function setUsername($username){
        $this->username = $username;
    }

    /**
     * Gets the message
     * @return string
     */
    public function getMessage(){
        return $this->username . ' makes $' . number_format($this->wage, 2) . ' per hour';
    }

    /**
     * Gets a random number between 8.50 and 15.00
     * @return type
     */
    public function setWage($wage){
        return $this->wage = $wage;
    }

}
