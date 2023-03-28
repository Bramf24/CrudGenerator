<?php namespace Bramf\CrudGenerator\Builders;

use Symfony\Component\Console\Output\ConsoleOutput;

class ServiceRouter{
    public function __construct(
        private array $params
    ){
        $this->routesPath = base_path().'/routes/services/routes.php';
        $this->output = new ConsoleOutput();
        $this->buildParams['ParamController'] = Str::ucfirst($this->params['controller_name']);
        $this->buildParams['ParamControllerLower'] = Str::lower($this->params['controller_name']);
    }

    /**
     * Build routes file
     */
    public function build(){
        $this->generateServiceRoutes();
        $this->output->writeln('<info>Routes for service '.$this->buildParams['ParamController'].' created successfully</info>');
    }

    /**
     * Generate service routes file
     */
    private function generateServiceRoutes(){
        $routesData = file_get_contents($this->routesPath);
        $template = file_get_contents(base_path().'/vendor/bramf/crud-generator/src/Templates/Routes/route-service.template');
        $template = str_replace('ParamControllerLower',$this->buildParams['ParamControllerLower'],$template);
        $template = str_replace('ParamController',$this->buildParams['ParamController'],$template);
        $routesData .= $template;
        file_put_contents($this->routesPath,$routesData);
    }
}