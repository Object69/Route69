<?php

namespace Route69;

/**
 *
 * @author Ryan Naddy <rnaddy@corp.acesse.com>
 * @name Route.php
 * @version 1.0.0 Aug 3, 2015
 */
class Route{

    protected $routes = array();

    /**
     * Sets a new route to be tested
     * @param string $path The path of the route
     * @param array $settings The settings fo the route
     * @return \Route69\Route
     */
    public function when($path, array $settings = null){
        $this->routes[] = array(
            "path"     => $path,
            "settings" => $settings
        );
        return $this;
    }

    /**
     * If no when statement gets executed default to this
     * @param array $settings
     * @return \Route69\Route
     */
    public function otherwise(array $settings){
        $this->routes[] = array(
            "settings" => $settings
        );
        return $this;
    }

    /**
     * Gets a list of all the setup routes
     * @return type
     */
    public function getRoutes(){
        return $this->routes;
    }

}
