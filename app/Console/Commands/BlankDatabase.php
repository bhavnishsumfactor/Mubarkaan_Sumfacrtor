<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\DatabaseRestore;

class BlankDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blankDatabase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Blank Database';

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
        return DatabaseRestore::blankDataBaseInstall();
    }
}
