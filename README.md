
# Crud Generator

Package for generate crud controller,model and routes
## Installation

Run command below to install package:

```bash
  composer require bramf/crud-generator:dev-master
```
## Environment Variables

After installing package put variables to your .env file:

`DB_CONNECTION=YOUR_DB_TYPE[for example mysql,pgsql]`\
`DB_HOST=DATABASE_HOST`\
`DB_PORT=DATABASE_PORT`\
`DB_DATABASE=DATABASE_NAME`\
`DB_USERNAME=DATABASE_USERNAME`\
`DB_PASSWORD=DATABASE_PASSWORD`

`SWAGGER_VERSION=3.0`

## Configuration

Add this code to app\Console\Kernel.php in ```protected $commands``` section:

```php
    protected $commands = [
        \App\Console\Commands\GenerateSwagger::class,
        \App\Console\Commands\MakeControllerCrud::class
    ];
```

