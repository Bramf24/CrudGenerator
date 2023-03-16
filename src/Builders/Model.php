<?php namespace Bramf\CrudGenerator\Builders;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Support\Str;

class Model{
    /**
     * Table column's exception list
     */
    const MODEL_FIELD_EXCEPTIONS = [
        'id','created_at','updated_at'
    ];

    /**
     * Array of laravel validation types for model rules
     */
    const VALIDATION_TYPES = [
        'bigint' => 'integer',
        'boolean' => 'boolean',
        'character' => 'string',
        'character varying' => 'string',
        'smallint' => 'integer',
        'integer' => 'integer',
        'double precision' => 'numeric',
        'smallint' => 'integer',
        'text' => 'string',
        'uuid' => 'string'
    ];

    public function __construct(
        private array $params
    ){
        // get column data of table
        $this->schema = DB::table('information_schema.columns')->where([
            'table_schema' => 'public',
            'table_name' => $this->params['table_name']
        ])->get([
            'column_name','udt_name','is_nullable',
            'column_default','character_maximum_length'
        ]);
        // get foreign keys data of table
        $this->foreignKeys = DB::table('information_schema.table_constraints AS tc')
        ->join('information_schema.key_column_usage AS kcu',function(\Illuminate\Database\Query\JoinClause $join){
            $join->on([
                ['tc.constraint_name','=','kcu.constraint_name'],
                ['tc.table_schema','=','kcu.table_schema']
            ]);
        })
        ->join('information_schema.constraint_column_usage AS ccu',function(\Illuminate\Database\Query\JoinClause $join){
            $join->on([
                ['ccu.constraint_name','=','tc.constraint_name'],
                ['ccu.table_schema','=','tc.table_schema']
            ]);
        })
        ->where([
            'tc.constraint_type' => 'FOREIGN KEY',
            'tc.table_name' => $this->params['table_name']
        ])
        ->get([
            'kcu.column_name','ccu.table_name AS foreign_table_name',
            'ccu.column_name AS foreign_column_name'
        ]);
        $this->fields = [];
        $this->buildParams['ParamTable'] = $this->params['table_name'];
        $this->buildParams['ParamModel'] = $this->params['model_name'];
        $this->buildParams['ParamAuthor'] = $this->params['author'];
        $this->output = new ConsoleOutput();
    }

    /**
     * Build model file
     */
    public function build(){
        $this->getModelFields();
        $template = file_get_contents(base_path().'/vendor/bramf/crud-generator/src/Templates/Models/Model.php');
        foreach($this->buildParams as $param => $value){
            $template = str_replace($param,$value,$template);
        }
        $template = str_replace('#RULES',$this->generateRules(),$template);
        $template = str_replace('#PROPERTIES',$this->generateProperties(),$template);
        $template = str_replace('#FILLABLE',$this->fillable(array_keys($this->fields)),$template);
        file_put_contents(base_path().'/app/Models/'.$this->buildParams['ParamModel'].'.php',$template);
        $this->output->writeln('<info>Model '.$this->buildParams['ParamModel'].' created successfully</info>');
    }

    /**
     * Generate validation rules and add foreign key data from foreign keys of table
     */
    private function foreign(){
        foreach($this->foreignKeys as $foreign){
            $this->fields[$foreign->column_name]['rules']['required'] = 'required';
            $this->fields[$foreign->column_name]['rules']['exists'] = 'exists:'.$foreign->foreign_table_name.','.$foreign->foreign_column_name;
            $this->fields[$foreign->column_name]['foreign'] = true;
            $this->fields[$foreign->column_name]['foreign_table'] = $foreign->foreign_table_name;
            $this->fields[$foreign->column_name]['foreign_table_column'] = $foreign->foreign_column_name;
        }
    }

    /**
     * Generate validation rules for model
     */
    private function buildRules($schema,$type){
        if($schema->is_nullable == 'NO' && is_null($schema->column_default)){
            $this->fields[$schema->column_name]['rules']['required'] = 'required';
        }
        if($type == 'string' && !is_null($schema->character_maximum_length)){
            $this->fields[$schema->column_name]['rules']['max'] = 'max:'.$schema->character_maximum_length;
        }
        if($schema->udt_name == 'int2'){
            $this->fields[$schema->column_name]['rules']['digits'] = 'digits:1';
        }
        $this->fields[$schema->column_name]['rules']['type'] = self::VALIDATION_TYPES[$type] ?? $type;
    }

    /**
     * Get model fields from migration
     */
    private function getModelFields(){
        foreach($this->schema as $schema){
            if(in_array($schema->column_name,self::MODEL_FIELD_EXCEPTIONS)) continue;
            $type = Schema::getColumnType($this->params['table_name'],$schema->column_name);
            $this->fields[$schema->column_name] = [
                'name' => $schema->column_name,
                'type' => $type,
                'udt' => $schema->udt_name,
                'foreign' => false,
                'rules' => []
            ];
            $this->buildRules($schema,$type);
        }
        $this->foreign();
    }

    /**
     * generate 'protected static $rules' array for model
     */
    private function generateRules(){
        $output = [];
        foreach($this->fields as $field){
            $output[] = '       "'.$field['name'].'" => "'.implode("|",$field['rules']).'",';
        }
        return join("\n",$output);
    }

    /**
     * Transform keys to string array
     */
    private function fillable($keys){
        $result = [];
        foreach($keys as $key){
            $result[] = '"'.$key.'"';
        }
        return '        '.join(",",$result);
    }

    private function generateProperties(){
        $output = [];
        foreach($this->fields as $field){
            $template = file_get_contents(base_path().'/vendor/bramf/crud-generator/src/Templates/Models/ModelProperty.template');
            $description = Str::snake($field['name'],' ');
            if($field['foreign']){
                $description = $field['name'].' is related to '.$field['foreign_table'].'.'.$field['foreign_table_column'];
            }
            $template = str_replace('ParamUdt',$field['udt'],$template);
            $template = str_replace('ParamName',$field['name'],$template);
            $template = str_replace('ParamDescription',$description,$template);
            $template = str_replace('ParamType',$field['rules']['type'],$template);
            $output[] = $template;
        }
        return join("\n",$output);
    }
}