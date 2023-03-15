<?php namespace Bramf\CrudGenerator;

use Illuminate\Support\ServiceProvider;

class CrudGeneratorServiceProvider extends ServiceProvider{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'SwaggerMake' => 'command.swagger.make',
        'CrudMake' => 'command.crud.make'
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
        if(file_exists(__DIR__.'/routes/crud.php')){
            Route::group([
                'namespace' => 'App\Http\Controllers'
            ], function($router){
                require __DIR__.'/routes/crud.php';
            });
        }
    }
}