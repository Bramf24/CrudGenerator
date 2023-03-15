
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

`SWAGGER_VERSION=3.0`

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
    $app->register(Flipbox\LumenGenerator\LumenGeneratorServiceProvider::class);
    $app->register(Bramf\CrudGenerator\CrudGeneratorServiceProvider::class);
```
