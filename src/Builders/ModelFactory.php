<?php namespace Bramf\CrudGenerator\Builders;

use Bramf\CrudGenerator\Builders\Model;
use Faker\Factory as Faker;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Support\Facades\DB;

class ModelFactory{
    public function __construct(
        private array $params
    ){
        $this->modelFields = (new Model($this->params))->getFields();
        $this->faker = Faker::create();
        $this->output = new ConsoleOutput();
    }

    /**
     * generate data of foreign column for definitions method of model's factory
     */
    private function generateDefinitionsForeign($columnData){
        $foreignModel = DB::table($columnData['foreign_table'])->first();
        return '            "'.$columnData['name'].'" => '.($foreignModel->id ?? 1).',';
    } 

    /**
     * generate data for definitions method of model's factory
     */
    private function generateDefinitions(){
        $output = [];
        foreach($this->modelFields as $name => $data){
            if($data['foreign']){
                $output[] = $this->generateDefinitionsForeign($data);
                continue;
            }
            if($data['nullable'] == 'YES'){
                $output[] = '            "'.$name.'" => null,';
                continue;
            }
            $output[] = match($data['type']){
                'bigint' => '            "'.$name.'" => $this->faker->numberBetween(1,'.($data['maxlength'] ?? 10).'),',
                'boolean' => '            "'.$name.'" => $this->faker->boolean,',
                'character' => '            "'.$name.'" => $this->faker->regexify(\'[A-Za-z]{'.($data['maxlength'] ?? 1).'}\'),',
                'character varying' => '            "'.$name.'" => $this->faker->regexify(\'[A-Za-z]{'.($data['maxlength'] ?? 1).'}\'),',
                'smallint' => '            "'.$name.'" => $this->faker->numberBetween(1,'.($data['maxlength'] ?? 10).'),',
                'integer' => '            "'.$name.'" => $this->faker->numberBetween(1,'.($data['maxlength'] ?? 10).'),',
                'double precision' => '            "'.$name.'" => $this->faker->numberBetween(1,'.($data['maxlength'] ?? 10).'),',
                'smallint' => '            "'.$name.'" => $this->faker->numberBetween(1,'.($data['maxlength'] ?? 10).'),',
                'text' => '            "'.$name.'" => $this->faker->regexify(\'[A-Za-z]{'.($data['maxlength'] ?? 1).'}\'),',
                'uuid' => '            "'.$name.'" => $this->faker->regexify(\'[A-Za-z]{'.($data['maxlength'] ?? 1).'}\'),',
                'string' => '            "'.$name.'" => $this->faker->regexify(\'[A-Za-z]{'.($data['maxlength'] ?? 1).'}\'),',
                'timestamp' => '            "'.$name.'" => $this->faker->dateTime(),',
                'datetime' => '            "'.$name.'" => $this->faker->dateTime(),',
                'float' => '            "'.$name.'" => $this->faker->numberBetween(1,'.($data['maxlength'] ?? 10).'),',
                default => '            "'.$name.'" => null,'
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