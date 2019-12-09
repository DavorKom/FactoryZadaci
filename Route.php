<?php 

class Route
{
    public $uri;
    public $method;
    public $action;

    public function __construct($method, $uri, $action)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->action = $action;
    }
}