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
        Route::group([
            'namespace' => 'App\Http\Controllers'
        ], function($router){
            require base_path().'/vendor/bramf/crud-generator/src/routes/crud.php';
        });
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }
}