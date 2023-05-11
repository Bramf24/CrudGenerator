<?php namespace Bramf\CrudGenerator\Builders;

use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Support\Str;
use Bramf\CrudGenerator\Builders\Model;

class UnitTest{
    public function __construct(
        private array $params
    ){
        $this->output = new ConsoleOutput();
        $this->modelFields = (new Model($this->params))->getFields();
    }

    private function generateRequestData(){
        $output = [];
        foreach($this->modelFields as $name => $data){
            $output[] = match($data['type']){
                'bigint' => '            "'.$name.'" => rand(1,9),',
                'boolean' => '            "'.$name.'" => true,',
                'character' => '            "'.$name.'" => \Illuminate\Support\Str::random(20),',
                'character varying' => '            "'.$name.'" => \Illuminate\Support\Str::random(20),',
                'smallint' => '            "'.$name.'" => rand(1,9),',
                'integer' => '            "'.$name.'" => rand(1,9),',
                'double precision' => '            "'.$name.'" => rand(0,9),',
                'smallint' => '            "'.$name.'" => rand(1,9),',
                'text' => '            "'.$name.'" => \Illuminate\Support\Str::random(20),',
                'uuid' => '            "'.$name.'" => \Illuminate\Support\Str::random(20),',
                'string' => '            "'.$name.'" => \Illuminate\Support\Str::random(20),',
                'timestamp' => '            "'.$name.'" => \Carbon\Carbon::now(),',
                default => '            "'.$name.'" => null,'
            };
        }
        return join("\n",$output);
    }

    public function build(){
        $this->buildParams['ParamModel'] = $this->params['model_name'];
        $this->buildParams['ParamUrl'] = '/api/'.str_replace('_','/',Str::singular($this->params['table_name']));
        $this->buildParams['ParamUrlId'] = '/api/'.str_replace('_','/',Str::singular($this->params['table_name'])).'/id';
        $this->buildParams['ParamTableNameSingle'] = Str::singular($this->params['table_name']);
        $this->buildParams['ParamTableName'] = $this->params['table_name'];
        $this->buildParams['ParamAuthHeader'] = '';
        if(!empty(env('JWT_SECRET'))) $this->buildParams['ParamAuthHeader'] = '"Authorization" => "Bearer ".test_token()';
        $this->buildParams['ParamResponseFields'] = '"'.implode('","',array_keys($this->modelFields)).'"';
        $template = file_get_contents(base_path().'/vendor/bramf/crud-generator/src/Templates/Tests/UnitTest.php');
        foreach($this->buildParams as $param => $value){
            $template = str_replace($param,$value,$template);
        }
        $template = str_replace('#ParamRequest',$this->generateRequestData(),$template);
        file_put_contents(base_path().'/tests/'.$this->buildParams['ParamModel'].'Test.php',$template);
        $this->output->writeln('<info>Test '.$this->buildParams['ParamModel'].' created successfully</info>');
    }
}