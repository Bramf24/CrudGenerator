<?php namespace Bramf\CrudGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Bramf\CrudGenerator\Exceptions\CommandException;
use Bramf\CrudGenerator\Builders\Controller;
use Bramf\CrudGenerator\Builders\Router;
use Bramf\CrudGenerator\Builders\Model;

class CrudMakeCommand extends Command
{
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
        (new Model($this->params))->build();
        // $this->call('make:swagger');
        // $this->info('Controller, model and routes successfully created!');
    }
}
