<?php namespace Bramf\CrudGenerator\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Bramf\CrudGenerator\Exceptions\CommandException;
use Illuminate\Support\Facades\Validator;

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
     * Validate command options
     */
    private function validate(){
        $validator = Validator::make($this->params,[
            'id_group' => ['required','numeric']
        ]);
        if($validator->fails()){
            foreach($validator->errors()->all() as $error){
                $this->error($error);
            }
            throw new CommandException('Validation failed');
        }
    }

    /**
     * Remove route group from 'crud_route_groups' table
     */
    private function removeGroup(){
        if($this->params['id_group'] == 0){
            DB::table('crud_route_groups')->truncate();
            $this->info('All groups removed successfully');
            return false;
        }
        DB::table('crud_route_groups')->where('id',$this->params['id_group'])->delete();
        $this->info('Group with id '.$this->params['id_group'].' removed successfully');
    }

    /**
     * Transform 'crud_route_groups' table data to array
     */
    private function getGroupsTable(){
        $groups = DB::table('crud_route_groups')->get(['id','group_name','controller_name']);
        $table = [];
        foreach($groups as $group){
            $table[] = [$group->id,$group->group_name,$group->controller_name];
        }
        return $table;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Type id of group you want to remove or type 0 to delete all groups');
        $this->table(
            ['id','group','controller'],
            $this->getGroupsTable()
        );
        $this->params['group_id'] = $this->ask('Id group');
        $this->validate();
        $this->removeGroup();
    }
}