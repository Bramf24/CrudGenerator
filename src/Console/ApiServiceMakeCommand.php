<?php namespace Bramf\CrudGenerator\Console;

use Illuminate\Console\Command;
use Bramf\CrudGenerator\Builders\ServiceController;

class ApiServiceMakeCommand extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:service:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create controller,service and routes for all external services with OpeApi annotations.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->services = config('services.bramf');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach($this->services as $name => $data){
            (new ServiceController(['controller_name'=>$name]))->build();
        }
    }
}