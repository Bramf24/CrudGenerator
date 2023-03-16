<?php namespace Bramf\CrudGenerator\Builders;

class Router{
    public function __construct(
        private array $params
    ){}

    /**
     * Build routes file
     */
    public function build(){
        $routes = app()->router->getRoutes();
        dump($routes);
    }
}