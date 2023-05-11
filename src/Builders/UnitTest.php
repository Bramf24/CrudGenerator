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
        $this->buildParams['ParamUrl'] = '/api/'.str_replace('_','/',$this->params['table_name']);
        $this->buildParams['ParamUrlId'] = '/api/'.str_replace('_','/',$this->params['table_name']).'/id';
        $template = file_get_contents(base_path().'/vendor/bramf/crud-generator/src/Templates/Tests/UnitTest.php');
        foreach($this->buildParams as $param => $value){
            $template = str_replace($param,$value,$template);
        }
        file_put_contents(base_path().'/tests/'.$this->buildParams['ParamModel'].'Test.php',$template);
        $this->output->writeln('<info>Test '.$this->buildParams['ParamModel'].' created successfully</info>');
    }
}