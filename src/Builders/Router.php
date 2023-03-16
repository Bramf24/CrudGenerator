<?php namespace Bramf\CrudGenerator\Builders;

use Illuminate\Support\Facades\File;

class Router{
    public function __construct(
        private array $params
    ){
        $this->routesPath = base_path().'/vendor/bramf/crud-generator/src/routes/crud.php';
    }

    /**
     * Build routes file
     */
    public function build(){
        // $routes = app()->router->getRoutes();
        // dump($routes);
    }
}