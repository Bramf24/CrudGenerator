<?php namespace Bramf\CrudGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Bramf\CrudGenerator\Exceptions\CommandException;
use Bramf\CrudGenerator\Builders\Controller;
use Bramf\CrudGenerator\Builders\Router;

class CrudMakeCommand extends Command
{
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

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create controller with CRUD operations and OpeApi annotations.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->params = [];
    }

    /**
     * Create routes for CRUD
     */
    private function routesBuild(){
        $routesPath = base_path().'/routes/crud.php';
        if(!File::exists($routesPath)){
            file_put_contents($routesPath,'<?php');
        }
        $data[] = "\n";
        $data[] = '/**
* '.$this->params['controller_name'].' routes
*/
$router->group(["prefix"=>"'.ltrim($this->params['crud_url'],'/').'"],function() use($router){
    // CRUD
    $router->post("/","'.$this->params['controller_name'].'@create");
    $router->get("/","'.$this->params['controller_name'].'@all");
    $router->get("/{id}","'.$this->params['controller_name'].'@get");
    $router->put("/{id}","'.$this->params['controller_name'].'@update");
    $router->delete("/{id}","'.$this->params['controller_name'].'@delete");
});';
        file_put_contents($routesPath,file_get_contents($routesPath).join("\n",$data));
    }

    /**
     * Get model fields from migration
     */
    private function getModelFields(){
        $tableSchema = DB::table('information_schema.columns')->where([
            'table_schema' => 'public',
            'table_name' => $this->params['table_name']
        ])->get([
            'column_name','udt_name','is_nullable',
            'column_default','character_maximum_length'
        ]);
        $tableForeign = DB::table('information_schema.table_constraints AS tc')
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
        $fields = [];
        foreach($tableSchema as $schema){
            if(!in_array($schema->column_name,self::MODEL_FIELD_EXCEPTIONS)){
                $type = Schema::getColumnType($this->params['table_name'],$schema->column_name);
                $fields[$schema->column_name] = [
                    'name' => $schema->column_name,
                    'type' => $type,
                    'udt' => $schema->udt_name,
                    'foreign' => false,
                    'rules' => []
                ];
                if($schema->is_nullable == 'NO' && is_null($schema->column_default)){
                    $fields[$schema->column_name]['rules']['required'] = 'required';
                }
                $fields[$schema->column_name]['rules']['type'] = self::VALIDATION_TYPES[$type] ?? $type;
                if($type == 'string' && !is_null($schema->character_maximum_length)){
                    $fields[$schema->column_name]['rules']['max'] = 'max:'.$schema->character_maximum_length;
                }
                if($schema->udt_name == 'int2'){
                    $fields[$schema->column_name]['rules']['digits'] = 'digits:1';
                }
            }
        }
        foreach($tableForeign as $foreign){
            $fields[$foreign->column_name]['rules']['required'] = 'required';
            $fields[$foreign->column_name]['rules']['exists'] = 'exists:'.$foreign->foreign_table_name.','.$foreign->foreign_column_name;
            $fields[$foreign->column_name]['foreign'] = true;
            $fields[$foreign->column_name]['foreign_table'] = $foreign->foreign_table_name;
            $fields[$foreign->column_name]['foreign_table_column'] = $foreign->foreign_column_name;
        }
        return $fields;
    }

    /**
     * Create model for CRUD from migrations with filenames match "*MODEL_NAME*"
     */
    private function modelBuild(){
        // if(File::exists(base_path().'/app/Models/'.$this->params['model_name'].'.php')){
        //     $this->warn('Cannot create model with name '.$this->params['model_name'].', because it already exists!');
        //     return false;
        // }
        $modelFields = $this->getModelFields();
        $data = ['<?php namespace App\Models;'];
        $data[] = '';
        $data[] = 'use Illuminate\Database\Eloquent\Model;';
        $data[] = 'use \Awobaz\Compoships\Compoships;';
        $data[] = '';
        $data[] = '/**
* Class '.$this->params['model_name'].'
* 
* @package App\Models
* 
* @author Bramf
* 
* @OA\Schema(
*   title="'.$this->params['model_name'].' model",
*   description="'.$this->params['model_name'].' model"
* )
*/';
        $data[] = 'class '.$this->params['model_name'].' extends Model';
        $data[] = '{';
        $data[] = 'use Compoships;';
        $data[] = '';
        $data[] = 'protected $table = "'.$this->params['table_name'].'";';
        $data[] = '';
        $data[] = '/**
* Validation rules
*/';
        $data[] = 'public static $rules = [';
        foreach($modelFields as $field){
            $data[] = ' "'.$field['name'].'" => "'.implode("|",$field['rules']).'",';
        }
        $data[] = '];';
        $data[] = '';
        foreach($modelFields as $field){
            $description = Str::snake($field['name'],' ');
            if($field['foreign']) 
                $description = $field['name'].' is related to '.$field['foreign_table'].'.'.$field['foreign_table_column'];
            $data[] = '/**
* @OA\Property(
*   format="'.$field['udt'].'",
*   title="'.$field['name'].'",
*   description="'.$description.'"
* )
* 
* @var '.$field['rules']['type'].'
* 
*/
private $'.$field['name'].';';
        }
        $data[] = '';
        $data[] = '/**
* The attributes that are mass assignable.
*
* @var string[]
*/';
        $data[] = 'protected $fillable = [';
        $data[] = $this->fillable(array_keys($modelFields));
        $data[] = '];';
        $data[] = '}';
        file_put_contents(base_path().'/app/Models/'.$this->params['model_name'].'.php',join("\n",$data));
    }

    /**
     * Transform keys to string array
     */
    private function fillable($keys){
        $result = [];
        foreach($keys as $key){
            $result[] = '"'.$key.'"';
        }
        return join(", ",$result);
    }

    /**
     * Transform input data to required format
     */
    private function prepareData(){
        $this->params['crud_url'] = '/'.trim($this->params['crud_url'],'/');
    }

    /**
     * Validate command options
     */
    private function validate(){
        $validator = Validator::make($this->params,[
            'controller_name' => ['required'],
            'crud_url' => ['required'],
            'model_name' => ['required'],
            'table_name' => ['required']
        ]);
        if($validator->fails()){
            foreach($validator->errors()->all() as $error){
                $this->error($error);
            }
            throw new CommandException('Validation failed');
        }
        if(File::exists(base_path().'/app/Http/Controllers/'.$this->params['controller_name'].'.php')){
            throw new CommandException('Controller with name '.$this->params['controller_name'].' already exists!');
        }
    }

    /**
     * Asking user for set parameters
     */
    private function input(){
        $this->params['controller_name'] = $this->ask('Controller name');
        $this->params['crud_url'] = $this->ask('CRUD url');
        $this->params['model_name'] = $this->ask('Model name');
        $this->params['table_name'] = $this->ask('Table name');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->input();
        $this->validate();
        $this->prepareData();
        (new Controller($this->params))->build();
        (new Router($this->params))->build();
        // $this->routesBuild();
        // $this->modelBuild();
        // $this->call('make:swagger');
        $this->info('Controller, model and routes successfully created!');
    }
}
