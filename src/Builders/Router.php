<?php namespace Bramf\CrudGenerator\Builders;

use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Support\Facades\DB;

class Router{
    public function __construct(
        private array $params
    ){
        $this->routesPath = base_path().'/vendor/bramf/crud-generator/src/routes/crud.php';
        $this->output = new ConsoleOutput();
    }

    /**
     * Build routes file
     */
    public function build(){
        DB::table('crud_route_groups')->upsert([
            ['name'=>$this->params['crud_url']]
        ],['name']);
        // $groups = DB::table('crud_route_groups')->get();

    }
}