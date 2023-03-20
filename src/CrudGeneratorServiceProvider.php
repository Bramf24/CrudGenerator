<?php namespace Bramf\CrudGenerator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class CrudGeneratorServiceProvider extends ServiceProvider{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'SwaggerMake' => 'command.swagger.make',
        'CrudMake' => 'command.crud.make',
        'CrudRoute' => 'command.crud.route',
        'CrudMakeTable' => 'command.crud.make.table'
    ];

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerCommands($this->commands);
    }

    /**
     * Register the given commands.
     *
     * @param array $commands
     */
    protected function registerCommands(array $commands)
    {
        foreach (array_keys($commands) as $command) {
            $method = "register{$command}Command";

            call_user_func_array([$this, $method], []);
        }

        $this->commands(array_values($commands));
    }

    /**
     * Register the command.
     */
    protected function registerSwaggerMakeCommand()
    {
        $this->app->singleton('command.swagger.make', function ($app) {
            return new Console\SwaggerMakeCommand();
        });
    }

    /**
     * Register the command.
     */
    protected function registerCrudMakeCommand()
    {
        $this->app->singleton('command.crud.make', function ($app) {
            return new Console\CrudMakeCommand();
        });
    }

    /**
     * Register the command.
     */
    protected function registerCrudRouteCommand()
    {
        $this->app->singleton('command.crud.route', function ($app) {
            return new Console\CrudRouteCommand();
        });
    }

    /**
     * Register the command.
     */
    protected function registerCrudMakeTableCommand()
    {
        $this->app->singleton('command.crud.make.table', function ($app) {
            return new Console\CrudMakeTableCommand();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array_values($this->commands);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if(!file_exists(base_path().'/routes/crud/routes.php')){
            mkdir(base_path().'/routes/crud',0755,true);
            $file = fopen(base_path().'/routes/crud/routes.php','w');
            fclose($file);
        }
        $groupOptions = ['namespace' => 'App\Http\Controllers'];
        if(!empty(env('JWT_SECRET'))) $groupOptions['middleware'] = 'auth:api';
        Route::group($groupOptions, function($router){
            require base_path().'/routes/crud/routes.php';
        });
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
}