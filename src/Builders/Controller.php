<?php namespace Bramf\CrudGenerator\Builders;

use Illuminate\Support\Str;
use Symfony\Component\Console\Output\ConsoleOutput;

class Controller{
    public function __construct(
        private array $params
    ){
        $this->buildParams['ParamController'] = $this->params['controller_name'];
        $this->buildParams['ParamModelLower'] = Str::lower($this->params['model_name']);
        $this->buildParams['ParamModelSnake'] = Str::snake($this->params['model_name'],' ');
        $this->buildParams['ParamModel'] = $this->params['model_name'];
        $this->buildParams['ParamUrl'] = $this->params['crud_url'];
        $this->output = new ConsoleOutput();
        $this->controllerCrudDir = base_path().'/app/Http/Controllers/Crud';
    }

    /**
     * Build controller file
     */
    public function build(){
        $template = file_get_contents(base_path().'/vendor/bramf/crud-generator/src/Templates/Controllers/Api.php');
        foreach($this->buildParams as $param => $value){
            $template = str_replace($param,$value,$template);
        }
        if(!file_exists($this->controllerCrudDir) && !is_dir($this->controllerCrudDir)){
            mkdir($this->controllerCrudDir);
        }
        file_put_contents(base_path().'/app/Http/Controllers/Crud/'.$this->buildParams['ParamController'].'.php',$template);
        $this->output->writeln('<info>Controller '.$this->buildParams['ParamController'].' created successfully</info>');
    }
}