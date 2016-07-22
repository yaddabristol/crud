<?php

namespace Yaddabristol\Crud\Commands;

use Illuminate\Console\Command;

class MigrationGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:post-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes a base migration for new content types';

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
        //
    }
}
