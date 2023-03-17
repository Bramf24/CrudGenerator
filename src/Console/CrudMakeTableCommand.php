<?php namespace Bramf\CrudGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
     * get all tables names, excluding exception tables
     */
    private function getTableNames(){
        $exceptions = array_merge(
            self::EXCEPTION_TABLES,
            explode(",",str_replace(' ','',$this->params['exceptions']))
        );
        $exceptions = array_filter($exceptions);
        dump($exceptions);
        $this->tables = DB::table('information_schema.tables')->select([
            'table_name'
        ])->where('table_schema','public')->whereNotIn('table_name',$exceptions)->get()->toArray();
    }

    /**
     * Asking user for set parameters
     */
    private function input(){
        $this->info('Set table names, that be excluded from CRUD generation');
        $this->params['exceptions'] = $this->ask('Excluded table names');
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
        dump($this->tables);
    }
}