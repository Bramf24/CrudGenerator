<?php namespace Bramf\CrudGenerator\Console;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class SwaggerMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:swagger';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate json file with OpenApi specification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $process = Process::fromShellCommandline(base_path().'/vendor/bin/openapi app -o ./public/swagger.json');
        $process->run();
        dump($process->getOutput());
    }
}
