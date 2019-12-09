<?php 

class Router
{
    public $routes = [];

    public function get($uri, $action = null)
    {
        $this->createRoute('GET', $uri, $action);
    }

    public function post($uri, $action = null)
    {
        $this->createRoute('POST', $uri, $action);
    }

    public function put($uri, $action = null)
    {
        $this->createRoute('PUT', $uri, $action);
    }

    public function delete($uri, $action = null)
    {
        $this->createRoute('DELETE', $uri, $action);
    }

    public function createRoute($method, $uri, $action)
    {
        $this->routes[$uri] = new Route($method, $uri, $action);
    }

    public function direct($uri)
    {   
        $route = $this->routes[$uri];
        
        if($route->action instanceof closure){
            ($route->action)();
        }

        $pieces = explode('@', $route->action);
        
        $controller = new $pieces[0];

        $controller->{$pieces[1]}();
    }
}