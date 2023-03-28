<?php namespace Bramf\CrudGenerator\Builders;

use Illuminate\Support\Str;
use Symfony\Component\Console\Output\ConsoleOutput;

class Service{
    public function __construct(
        private array $params
    ){
        $this->buildParams['ParamService'] = Str::ucfirst($this->params['service_name']);
        $this->buildParams['ParamServiceLower'] = Str::lower($this->params['service_name']);
        $this->buildParams['ParamServiceLowerPlural'] = Str::plural($this->buildParams['ParamServiceLower']);
        $this->output = new ConsoleOutput();
        $this->serviceDir = base_path().'/app/Services';
    }

    /**
     * Build service file
     */
    public function build(){
        if(!file_exists($this->serviceDir) && !is_dir($this->serviceDir)){
            mkdir($this->serviceDir);
        }
        if(!file_exists($this->serviceDir.'/'.$this->buildParams['ParamService'].'.php')){
            $template = file_get_contents(base_path().'/vendor/bramf/crud-generator/src/Templates/Services/Api.php');
            foreach(array_reverse($this->buildParams) as $param => $value){
                $template = str_replace($param,$value,$template);
            }
            file_put_contents($this->serviceDir.'/'.$this->buildParams['ParamService'].'.php',$template);
        }
        $this->output->writeln('<info>Service '.$this->buildParams['ParamService'].' created successfully</info>');
    }
}