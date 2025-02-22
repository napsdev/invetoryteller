<?php
namespace App\Controllers;
use App\Models\MigrateModel;

class MigrateController
{
    public function migrate()
    {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $migrate = new MigrateModel();
            $migrate->migrate();
        }
    }
}