<?php namespace Bramf\CrudGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CrudRouteCommand extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all or one route group from crud_route_groups table';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Type id of group you want to remove or type 0 to delete all groups');
        $groups = DB::table('crud_route_groups')->get(['id','group_name','controller_name']);
        $this->table(
            ['id','group','controller'],
            $groups->toArray()
        );
        $this->params['group_id'] = $this->ask('Id group');
    }
}