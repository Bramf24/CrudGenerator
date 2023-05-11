<?php namespace Bramf\CrudGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Bramf\CrudGenerator\Builders\Controller;
use Bramf\CrudGenerator\Builders\Router;
use Bramf\CrudGenerator\Builders\Model;
use Bramf\CrudGenerator\Builders\ModelFactory;
use Bramf\CrudGenerator\Builders\UnitTest;
use Symfony\Component\Process\Process;

class CrudMakeTableCommand extends Command{
    const EXCEPTION_TABLES = [
        'users','crud_route_groups','migrations'
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud:table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CRUD controller,model and routes for all tables and generate open api annotations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->params = [];
        $this->tables = [];
    }

    /**
     * prepare params for crud
     */
    private function prepareParams($table){
        $controllerName = implode('',array_map(function($part){
            return Str::ucfirst(Str::singular($part));
        },explode("_",$table->table_name)));
        $params['controller_name'] = $controllerName.'Controller';
        $params['model_name'] = $controllerName;
        $params['crud_url'] = '/api/'.str_replace('_','/',Str::singular($table->table_name));
        $params['table_name'] = $table->table_name;
        $params['author'] = env('PACKAGE_AUTHOR');
        return $params;
    }

    /**
     * generate crud controller,model and routes
     */
    private function crud(){
        DB::table('crud_route_groups')->truncate();
        foreach($this->tables as $table){
            $params = $this->prepareParams($table);
            $this->line('CRUD for '.$table->table_name);
            (new Controller($params))->build();
            (new Router($params))->build();
            (new Model($params))->build();
            (new ModelFactory($params))->build();
            (new UnitTest($params))->build();
        }
        $this->call('make:swagger');
        $this->info('OpenApi annotations created successfully');
        $this->newLine();
    }

    /**
     * get all tables names, excluding exception tables
     */
    private function getTableNames(){
        $exceptions = array_merge(
            self::EXCEPTION_TABLES,
            explode(",",str_replace(' ','',$this->params['exceptions']))
        );
        $exceptions = array_filter($exceptions);
        $this->tables = DB::table('information_schema.tables')->select([
            'table_name'
        ])->where('table_schema','public')->whereNotIn('table_name',$exceptions)->get();
    }

    /**
     * Asking user for set parameters
     */
    private function input(){
        $this->info('Set table names, that be excluded from CRUD generation');
        $this->params['exceptions'] = $this->ask('Excluded table names');
    }

    /**
     * run all generated tests
     */
    private function runTests(){
        $process = new Process(['./vendor/bin/phpunit']);
        $process->start();
        foreach($process as $type => $data){
            if($process::OUT !== $type){
                $this->error($data);
                continue;
            }
            $this->info($data);
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->input();
        $this->getTableNames();
        $this->crud();
        $this->runTests();
    }
}