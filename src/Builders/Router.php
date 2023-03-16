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
        $this->buildParams['ParamController'] = $this->params['controller_name'];
        $this->buildParams['ParamUrl'] = $this->params['crud_url'];
    }

    /**
     * Build routes file
     */
    public function build(){
        $this->addNewRouteGroup();
        $this->generateCrudRoutes();
        $this->output->writeln('<info>Routes for url '.$this->buildParams['ParamUrl'].' created successfully</info>');
    }

    /**
     * Add new crud group name to crud_route_groups table
     */
    private function addNewRouteGroup(){
        DB::table('crud_route_groups')->updateOrInsert([
            'group_name'=>$this->buildParams['ParamUrl']            
        ],[
            'controller_name'=>$this->buildParams['ParamController']
        ]);
    }

    /**
     * Generate crud routes file
     */
    private function generateCrudRoutes(){
        $groups = DB::table('crud_route_groups')->get();
        $routesData = '';
        foreach($groups as $group){
            $template = file_get_contents(base_path().'/vendor/bramf/crud-generator/src/Templates/Routes/route-group.template');
            $template = str_replace('ParamUrl',$group->group_name,$template);
            $template = str_replace('ParamController',$group->controller_name,$template);
            $routesData .= $template;
        }
        file_put_contents(base_path().'/vendor/bramf/crud-generator/src/routes/crud.php','<?php'.$routesData);
    }
}