<?php namespace Bramf\CrudGenerator\Builders;

use Illuminate\Support\Str;
use Symfony\Component\Console\Output\ConsoleOutput;

class ServiceController{
    public function __construct(
        private array $params
    ){
        $this->buildParams['ParamController'] = Str::ucfirst($this->params['controller_name']);
        $this->buildParams['ParamControllerLower'] = Str::lower($this->params['controller_name']);
        $this->buildParams['ParamControllerLowerPlural'] = Str::plural($this->buildParams['ParamControllerLower']);
        $this->output = new ConsoleOutput();
        $this->controllerServiceDir = base_path().'/app/Http/Controllers/Services';
    }

    /**
     * Build controller file
     */
    public function build(){
        if(!file_exists($this->controllerServiceDir) && !is_dir($this->controllerServiceDir)){
            mkdir($this->controllerServiceDir);
        }
        if(!file_exists($this->controllerServiceDir.'/'.$this->buildParams['ParamController'].'Controller.php')){
            $template = file_get_contents(base_path().'/vendor/bramf/crud-generator/src/Templates/Controllers/Service.php');
            foreach(array_reverse($this->buildParams) as $param => $value){
                $template = str_replace($param,$value,$template);
            }
            file_put_contents($this->controllerServiceDir.'/'.$this->buildParams['ParamController'].'Controller.php',$template);
        }
        $this->output->writeln('<info>Service controller '.$this->buildParams['ParamController'].' created successfully</info>');
    }
}