<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PingRemote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ping:remote';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if the system can reach the web service.';

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
     * @return int
     */
    public function handle()
    {
        exec('ping -c 2 http://pk_manager.test', $output, $status);
        var_dump($status);
    }
}
