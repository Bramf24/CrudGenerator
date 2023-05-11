<?php namespace Bramf\CrudGenerator\Builders;

use Bramf\CrudGenerator\Builders\Model;
use Faker\Factory as Faker;
use Symfony\Component\Console\Output\ConsoleOutput;

class ModelFactory{
    public function __construct(
        private array $params
    ){
        $this->modelFields = (new Model($this->params))->getFields();
        $this->faker = Faker::create();
        $this->output = new ConsoleOutput();
    }

    /**
     * generate data for definitions method of model's factory
     */
    private function generateDefinitions(){
        $output = [];
        foreach($this->modelFields as $name => $data){
            $output[] = match($data['type']){
                'character' => '            "'.$name.'" => $this->faker->word,',
                'character varying' => '            "'.$name.'" => $this->faker->word,',
                default => '            "'.$name.'" => $this->faker->word,'
            };
        }
        return join("\n",$output);
    }

    public function build(){
        $this->buildParams['ParamModel'] = $this->params['model_name'];
        $template = file_get_contents(base_path().'/vendor/bramf/crud-generator/src/Templates/ModelFactories/Crud.php');
        foreach($this->buildParams as $param => $value){
            $template = str_replace($param,$value,$template);
        }
        $template = str_replace('#ParamDefinitions',$this->generateDefinitions(),$template);
        file_put_contents(base_path().'/database/factories/'.$this->buildParams['ParamModel'].'Factory.php',$template);
        $this->output->writeln('<info>Model factory '.$this->buildParams['ParamModel'].' created successfully</info>');
    }
}