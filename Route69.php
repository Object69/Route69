<?php

namespace Route69;

use Exception;
use ReflectionFunction;

/**
 * @property Route $route Route
 * @property RouteProvider $routeProvider Route Provider
 */
class Route69{

    protected
            $method      = "get",
            $path        = array(),
            $items       = array(),
            $controllers = array();

    /**
     * Executes the Route
     */
    public function __destruct(){
        $controller = $this->_findRoute();
        if($controller !== null){
            $this->_executeController($controller);
        }
    }

    /**
     * Initializes the Routing settings
     */
    public function __construct(){
        $this->items = [
            "route"       => new Route(),
            "routeParams" => new RouteParams()
        ];

        $this->method = strtolower(filter_input(INPUT_SERVER, 'REQUEST_METHOD'));
        $this->path   = $this->_pathToArray(filter_input(INPUT_SERVER, 'REQUEST_URI'));
    }

    /**
     * A callable function that will define your routes
     * @param callable $callback
     * @throws Exception
     */
    public function config(callable $callback){
        if(!is_callable($callback)){
            throw new Exception('Config Parameter 1 is not a callback');
        }
        call_user_func_array($callback, array($this->items['route']));
    }

    /**
     * A controller that will handle the route
     * @param string $name
     * @param callable $callback
     * @throws Exception
     */
    public function controller($name, callable $callback){
        if(!is_callable($callback)){
            throw new Exception('Controller Parameter 2 for "' . $name . '" is not a callback');
        }
        $this->controllers[$name] = $callback;
    }

    /**
     * Tests the routes against the current path
     * @return callable The controller to execute
     */
    protected function _findRoute(){
        $routes = $this->items['route']->getRoutes();
        // Foreach user defined route
        foreach($routes as $r){
            $controller = null;
            $settings   = null;
            // Route::when
            if(isset($r["path"])){
                $route      = $this->_pathToArray($r["path"]);
                $route_good = true;
                // If the path lengths match, test them
                // Otherwise it isn't worth testing
                if(count($this->path) == count($route)){
                    foreach($route as $index => $item){
                        if(!isset($this->path[$index])){
                            $route_good = false;
                            break;
                        }
                        $good = $this->_comparePathItems($this->path[$index], $route[$index]);
                        if(!$good){
                            $route_good = false;
                            break;
                        }
                        $controller = $r["settings"]["controller"];
                        $settings   = $r["settings"];
                    }
                }else{
                    $controller = null;
                    $settings   = null;
                }
                if($route_good){
                    if(is_callable($controller)){
                        return [
                            "controller" => $controller,
                            "settings"   => $settings
                        ];
                    }
                    if(isset($this->controllers[$controller])){
                        return [
                            "controller" => $this->controllers[$controller],
                            "settings"   => $settings
                        ];
                    }
                }
            }
        }
        // Our route was not found, use our fallback
        // Route::otherwise
        foreach($routes as $route){
            if(isset($route["fallback"])){

            }
        }
        return null;
    }

    /**
     * The controller to execute
     * @param callable $controller
     */
    protected function _executeController(array $controller){
        $rf       = new ReflectionFunction($controller["controller"]);
        $params   = $rf->getParameters();
        $cbParams = array();

        foreach($params as $param){
            if(isset($this->items[$param->name])){
                $cbParams[] = $this->items[$param->name];
            }elseif(isset($controller["settings"]["resolve"][$param->name])){
                $cbParams[] = $controller["settings"]["resolve"][$param->name];
            }
        }

        call_user_func_array($controller["controller"], $cbParams);
    }

    /**
     * Compares the URL path item to the user defined path item
     * @param string $item1 The URL path item
     * @param string $item2 The User defined path item
     * @return boolean
     */
    protected function _comparePathItems($item1, $item2){
        $matches = array();
        // Test if item is a parameter
        if(preg_match("/^(:|@|#).+?/", $item2, $matches) && !empty($item1)){
            if($matches[1] == '@' && !ctype_alpha($item1)){
                return false;
            }
            if($matches[1] == '#' && !ctype_digit($item1)){
                return false;
            }
            $val = ltrim($item2, ':@#');

            $this->items["routeParams"]->$val = $item1;
            return true;
        }
        // Test if the two items match
        if($this->items["route"]->getStrict()){
            if($item1 === $item2){
                return true;
            }
        }else{
            $item1 = strtolower($item1);
            $item2 = strtolower($item2);
            if($item1 == $item2){
                return true;
            }
        }
        return false;
    }

    /**
     * Converts a strng path to an array removing the prefixed "/"
     * @param string $path
     * @return string
     */
    protected function _pathToArray($path){
        return explode("/", ltrim($path, '/'));
    }

}

spl_autoload_register(function($class){
    $filename = __DIR__ . "/../" . str_replace("\\", "/", $class) . ".php";
    if(is_file($filename)){
        require_once $filename;
    }
});
