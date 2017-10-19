<?php

namespace Enigma\Status\Commands;

use Enigma\Status\Controllers\StatusController;
use Illuminate\Console\Command;

class Generate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the Server Status functionality';

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
        $this->info(StatusController::generateServerStatus());
    }
}
