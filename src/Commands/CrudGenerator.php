<?php

namespace Yaddabristol\Crud\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CrudGenerator extends Command
{
    /**
     * The console command name
     *
     * @var string
     */
    protected $signature = 'make:crud';

    /**
     * The console command description
     *
     * @var string
     */
    protected $description = 'Make a new CRUD controller, views etc.';

    /**
     * The filesystem
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * @var Composer
     */
    private $composer;

    /**
     * Create a new command instance
     *
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
        $this->composer = app()['composer'];
    }

    /**
     * ERxecute the console command
     *
     * @return mixed
     */
    public function handle()
    {
        $this->name_singular = $this->ask('Singular name of objects that the CRUD controller will be managing?');
        $this->name_plural   = $this->ask("Plural name?", str_plural($this->name_singular));
        $this->model_name    = $this->ask('Model name?', $this->name_singular);
        $this->route         = $this->ask('Root route name?', strtolower($this->name_plural));
        $this->has_files     = $this->confirm('Do you need to upload files?', false);
        $this->views_dir     = $this->ask("Views directory?", strtolower($this->name_plural));

        $this->info('Ok then. How does this look?');

        $this->info('name_singular: ' . $this->name_singular);
        $this->info('name_plural: ' . $this->name_plural);
        $this->info('model_name: ' . $this->model_name);
        $this->info('route: ' . $this->route);
        $this->info('files: ' . ($this->has_files ? 'Yes' : 'No'));
        $this->info('views_dir: ' . $this->views_dir);

        $proceed = $this->confirm('Proceed?', true);

        if ($proceed) {
            $this->makeController();
        } else {
            $this->info('Ok then. Start again.');
        }
    }

    protected function makeController()
    {
        $code = $this->compileController();
        $path = $this->getControllerPath();

        $this->files->put($path, $code);

        $this->info('Controller created successfully.');
        $this->composer->dumpAutoloads();
    }

    /**
     * Get the path to the new controller file
     *
     * @return string
     */
    protected function getControllerPath()
    {
        return base_path() . '/app/Http/Controllers/' . $this->name_singular . 'Controller.php';
    }

    /**
     * Compile the controller stub
     *
     * @return string
     */
    protected function compileController()
    {
        $controller_path = $this->getControllerPath();

        if ($this->files->exists($controller_path)) {
            return $this->error('Controller ' . $this->name_singular . 'Controller.php already exists.');
        }

        $stub = $this->files->get(__DIR__ . '/../Stubs/Controller.stub');

        $stub = str_replace('{{model_name}}', $this->model_name, $stub);
        $stub = str_replace('{{controller_name}}', $this->name_singular . 'Controller', $stub);
        $stub = str_replace('{{views_dir}}', $this->views_dir, $stub);
        $stub = str_replace('{{name_singular}}', $this->name_singular, $stub);
        $stub = str_replace('{{name_plural}}', $this->name_plural, $stub);
        $stub = str_replace('{{route}}', $this->route, $stub);
        $stub = str_replace('{{files}}', ($this->has_files ? 'true' : 'false'), $stub);

        return $stub;
    }

}
