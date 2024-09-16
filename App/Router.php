<?php
declare(strict_types = 1);

namespace App;

use App\Controller\BaseController;

class Router{
    private array $routes = [];

    public function addRoute(string $method, string $route, string $controller, string $action){
        $this->routes[$method][$route] = [$controller, $action];
        return $this;
    }

    public function get(string $route, string $controller, string $action){
        return $this->addRoute('get', $route, $controller, $action);
    }

    public function post(string $route, string $controller, string $action){
        return $this->addRoute('post', $route, $controller, $action);
    }

    public function dispatch(string $url, string $method){
        $method = strtolower($method);
        $isRouteFound = false;
        foreach($this->routes as $routeMethod => $route){
            if($routeMethod != $method)
                continue;

            foreach($route as $key => $val){
                if($key == $url){
                    $isRouteFound = true;
                    break 2;
                }
            }
        }

        if(!$isRouteFound)
            throw new \Exception("Route {$url} not found.");

        [$controller, $action] = $this->routes[$method][$url];
        if(!(class_exists($controller) && is_subclass_of($controller, BaseController::class)))
            throw new \Exception("Controller for {$url} not found.");

        $controllerObj = new $controller;
        if(!method_exists($controllerObj, $action))
            throw new \Exception("Action '{$action}' for the controller '{$controller}' not found.");
        
        $controllerObj->$action();
    }
}