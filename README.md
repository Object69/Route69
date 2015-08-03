# Route69

## Server Configuration

Before we begin, we need to configure the server to route all files to the main index file where are configuration will be located.

### Apache

Create an `.htaccess` file in the same directory as your `index.php` file and then place this code in the file

```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule . /index.php [L]
```

### Nginx

Inside your server block add the following location:

```
location / {
    try_files $uri $uri/ /index.php;
}
```

## My First App

First thing that we need to do is create the routing app, then use it to setup our configuration.

```php
$app = new Route69();

$app->config(function(Route $route){
    $route->when('/users/get/#id', [
        'controller' => 'getUsers',
        // Pass an extra users parameter to the controller (this is optional)
        'resolve' => [
            'users' => new Users
        ]
    ]);
});
```

Once we have the config ready, we need to setup the controller. To do that, it will look something like this:

```php
$app->controller('getUsers', function(RouteParams $routeParams, Users $users){
    echo $users->get($routeParams->id);
});
```

**Note:** The order of the parameters passed to the controller do not matter.

We can also do this without defining a variable (depending on your php version):

```php
(new Route69)->config(function(Route $route){
    // Config setup
})->controller('myController1', function(){
    // Controller commands
})->controller('myController2', function(){
    // Controller commands
});
```

## The Config
The `config` is a where we configure the all the routing magic. This method should only be called once per app, but it works if you call it more than once, as routing items just get appended to the current ones.

The config callback takes one parameter, and that is the `Route` which holds all the routing information and methods to define your routes.

Method | Description
--- | ---
`when($path, $settings)` | <table><tr><td>`$path`</td><td>A string describing the path</td></tr><tr><td>`$settings`</td><td><ul><li>`controller` The name of a controller or callback to use for this route</li><li>`resolve` An associative array of items to resolve, which then can be used as parameters in the controller</li></ul></td></tr></table>
`otherwise($settings)` | When the url doesn't match anything use these `$settings`
`getRoutes()` | Returns an array of all the routes that are currently set

## The Routes
The routes are strings of wich the path must match in order for the controller to be executed, otherwise the controller will not execute.

Method | Description
--- | ---
`setStrict($isStrict)` | Turns on/off case sensitivity matching; by default routes are case sensitive where `/ROUTE1`, `/route1` and `/Route1` are each different routes with their own or shared controller.

### Parameters

There are 3 types of parameters that you can place within a route, one that matches anything, one that matches digits, and one that matches alpha characters.

* `:` will match any value, such as `abc`, `123`, `abc123`, etc.
* `@` will match any alpha character(s) such as `abc` but it will not match `123` or `abc123`
* `#` will match any digit character(s) such as `123` but it will not match `abc` or `abc123`

## The Controller
The `controller` is where you will define what should happen when the route matches a defined route from your config. You can do basically anything here.

The config will need to define a controller, either as a name of a controller (public) or as callback (private). The common way is to use a string as a callback so other routes can use the same controller if needed, but it isn't required.

```php
$app->config(function(Route $route){
    $route->when('/test1', [
        'controller' => 'myController'
    ])->when('/test2', [
        'controller' => function(/* Parameters here */){
            // execute controller code here
        }
    ]);
});

$app->controller('myController', function(/* Parameters here */){
    // execute controller code here
});
```
