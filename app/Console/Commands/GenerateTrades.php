<?php

namespace App\Console\Commands;

use App\Models\Competition;
use App\Models\User;
use App\Services\CompetitionBotService;
use Illuminate\Console\Command;

class GenerateTrades extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:trades';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate trades (users with role BOT will trade automatically).';

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
    public function handle(CompetitionBotService $competitionBotService)
    {
        $competitionBotService->run();
    }
}
