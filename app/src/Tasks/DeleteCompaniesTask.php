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
            // TODO: way to just drop the table and add it again?
            $companies = Company::get();
            foreach ($companies as $company) {
                $company->delete();
            }
            echo "Finished deleting, resetting increment... \n";
            $alter = DB::query('ALTER TABLE `Company` AUTO_INCREMENT =0')->value();
            echo "Done!\n\n";
        } catch (Exception $e) {
            echo "\n\nError while clearing Company Table\n" . $e->getMessage() . "\n\n";
        }
    }
}
