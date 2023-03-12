<?php

namespace YoKartInstall\Installer\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;


use YoKartInstall\Installer\Helpers\DatabaseManager;

class DatabaseController extends Controller
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    /**
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * Migrate and seed the database.
     *
     * @return \Illuminate\View\View
     */
    public function database(Request $request, $status)
    {              
        $response = $this->databaseManager->migrateAndSeed($status);
        return redirect()->route('Installer::final')
                         ->with(['message' => $response]);
    }
    
    
}
