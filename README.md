# CrudGenerator
Package for generate crud controller,model and routes

Install:
- Run composer require bramf/crud-generator:dev-master
- Add "SWAGGER_VERSION=3.0" to your .env file
- Register commands in app\Console\Kernel.php:
-- \App\Console\Commands\GenerateSwagger::class,
-- \App\Console\Commands\MakeControllerCrud::class
