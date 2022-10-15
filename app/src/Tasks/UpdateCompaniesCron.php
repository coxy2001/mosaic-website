<?php

namespace Mosaic\Website\Tasks;

use Exception;
use Mosaic\Website\Model\Company;
use Mosaic\Website\Tasks\Util\CompanyGetter;
use SilverStripe\CronTask\Interfaces\CronTask;

class UpdateCompaniesCron implements CronTask
{
    /**
     * Run this task every 5 minutes
     *
     * @return string
     */
    public function getSchedule()
    {
        return "* * * * *";
    }

    /**
     * Update company data
     *
     * @return void
     */
    public function process()
    {
        try{
            // Abort if there are already entries in Company
            echo "Update Companies Task Running \n";
            $companies = Company::get();
            if($companies->count() != 0) {
                throw new Exception("Company table should be empty before getting new ones");
            }
            // Add data into Company table
            CompanyGetter::getAll();
        }
        catch(Exception $e) {
            echo "\n\nError while getting new Company data\n" . $e->getMessage() . "\n\n";
        }
    }
}
