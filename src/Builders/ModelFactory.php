<?php namespace Bramf\CrudGenerator\Builders;

use Bramf\CrudGenerator\Builders\Model;

class ModelFactory{
    public function __construct(
        private array $params
    ){
        $this->modelFields = (new Model($this->params))->getFields();
    }

    /**
     * generate data for definitions method of model's factory
     */
    private function generateDefinitions(){
        $definitions = [];
        foreach($this->modelFields as $name => $data){
            $definitions[$name] = match($data['type']){
                'character' => $this->faker->word,
                'character varying' => $this->faker->word,
                default => $this->faker->word
            };
        }
        return join(",",$definitions);
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