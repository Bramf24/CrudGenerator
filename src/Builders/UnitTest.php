<?php namespace Bramf\CrudGenerator\Builders;

use Symfony\Component\Console\Output\ConsoleOutput;

class UnitTest{
    public function __construct(
        private array $params
    ){
        $this->output = new ConsoleOutput();
    }

    public function build(){
        $this->buildParams['ParamModel'] = $this->params['model_name'];
        $this->buildParams['ParamPostUrl'] = '/api/'.str_replace('_','/',$this->params['model_name']);
        $this->buildParams['ParamGetAllUrl'] = '/api/'.str_replace('_','/',$this->params['model_name']);
        $this->buildParams['ParamGetUrl'] = '/api/'.str_replace('_','/',$this->params['model_name']).'/id';
        $this->buildParams['ParamUpdateUrl'] = '/api/'.str_replace('_','/',$this->params['model_name']).'/id';
        $this->buildParams['ParamDeleteUrl'] = '/api/'.str_replace('_','/',$this->params['model_name']).'/id';
        $template = file_get_contents(base_path().'/vendor/bramf/crud-generator/src/Templates/Tests/UnitTest.php');
        foreach($this->buildParams as $param => $value){
            $template = str_replace($param,$value,$template);
        }
        file_put_contents(base_path().'/tests/'.$this->buildParams['ParamModel'].'Test.php',$template);
        $this->output->writeln('<info>Test '.$this->buildParams['ParamModel'].' created successfully</info>');
    }
}