<?php

namespace Mosaic\Website\Tasks;

use Exception;
use Mosaic\Website\Model\Company;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;

class DeleteCompaniesTask extends BuildTask
{
    private static $segment = "DeleteCompaniesTask";

    /**
     * Deletes everything in the Company Table
     *
     * @return void
     */
    public function run($request)
    {
        try {
            echo "Delete Companies Task Running \n";
            $alter = DB::query("DELETE FROM `Company`");
            $alter = DB::query("ALTER TABLE `Company` AUTO_INCREMENT = 0");
            echo "Done!\n\n";
        } catch (Exception $e) {
            echo "\n\nError while clearing Company Table\n" . $e->getMessage() . "\n\n";
        }
    }
}
