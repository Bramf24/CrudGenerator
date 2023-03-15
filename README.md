
# Crud Generator

Lumen package for generate crud controller,model and routes
## Installation

Run command below to install package:

```bash
composer require bramf/crud-generator:dev-master
```
## Environment Variables

After installing package change database connection settings and put SWAGGER_VERSION variable to your .env file:

`DB_CONNECTION=YOUR_DB_TYPE[for example mysql,pgsql]`\
`DB_HOST=DATABASE_HOST`\
`DB_PORT=DATABASE_PORT`\
`DB_DATABASE=DATABASE_NAME`\
`DB_USERNAME=DATABASE_USERNAME`\
`DB_PASSWORD=DATABASE_PASSWORD`

`SWAGGER_VERSION=3.0`\
`AUTHOR=YOUR_NAME`

## Configuration

Add CrudGeneratorProvider to providers section in bootstrap/app.php:

```php
/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/
// $app->register(App\Providers\AppServiceProvider::class);
// $app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);
$app->register(Flipbox\LumenGenerator\LumenGeneratorServiceProvider::class);
$app->register(Bramf\CrudGenerator\CrudGeneratorServiceProvider::class);
```

Uncomment the $app->withEloquent() and $app->withFacades() call in your bootstrap/app.php:

```php
/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

$app->withFacades();

$app->withEloquent();
```

## Usage

Before use command you need to create and run migration, that creates table for CRUD operations, for example:
```bash
php artisan make:migration create_tests_table
```

```bash
php artisan migrate
```
## Commands
```bash
php artisan make:crud
```
Command will ask you required parameters to make CRUD:

`Controller name:`\
`>`\
`CRUD url:`\
`>`\
`Model name:`\
`>`\
`Table name:`\
`>`

- Controller name: name of controller for CRUD operations.
- CRUD url: route for CRUD operations. For example, value api/test will generate routes like this:
```php
/**
* Controller routes
*/
$router->group(["prefix"=>"api/test"],function() use($router){
    // CRUD
    $router->post("/","Controller@create");
    $router->get("/","Controller@all");
    $router->get("/{id}","Controller@get");
    $router->put("/{id}","Controller@update");
    $router->delete("/{id}","Controller@delete");
});
```
- Model name: name of model for CRUD operations.
- Table name: name of table for CRUD operations.
