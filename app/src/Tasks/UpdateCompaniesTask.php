<?php

namespace Mosaic\Website\Tasks;

use Exception;
use Mosaic\Website\Model\Company;
use Mosaic\Website\Tasks\Util\CompanyGetter;
use SilverStripe\Dev\BuildTask;

class UpdateCompaniesTask extends BuildTask
{
    private static $segment = "UpdateCompaniesTask";

    /**
     * Update company data
     *
     * @return void
     */
    public function run($request)
    {
        try {
            // Abort if there are already entries in Company
            echo "Update Companies Task Running \n";
            $companies = Company::get();
            if ($companies->count() != 0) {
                throw new Exception("Company table should be empty before getting new ones");
            }
            // Add data into Company table
            CompanyGetter::getAll();
        } catch (Exception $e) {
            echo "\n\nError while getting new Company data\n" . $e->getMessage() . "\n\n";
        }
    }
}
