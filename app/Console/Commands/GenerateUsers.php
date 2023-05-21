<?php

namespace App\Console\Commands;

use App\Services\UserService;
use Illuminate\Console\Command;

class GenerateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:users {count=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate users (bots).';

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
        for ($i=0; $i < $this->argument('count'); $i++) {
            UserService::create();
        }
    }
}
