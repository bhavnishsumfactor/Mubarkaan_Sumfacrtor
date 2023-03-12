<?php

namespace App\Console\Commands;

use App\Models\DatabaseRestore;
use Illuminate\Console\Command;
use DB;

class RestoreDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restoreDatabase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh Database';

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
         return DatabaseRestore::reset();
       
    }
}
