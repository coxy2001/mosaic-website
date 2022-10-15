<?php

namespace Mosaic\Website\Tasks;

use Exception;
use Mosaic\Website\Model\Company;
use SilverStripe\CronTask\Interfaces\CronTask;

class DeleteCompaniesTask implements CronTask
{
    /**
     * 
     *
     * @return string
     */
    public function getSchedule()
    {
        return "* * * * *";
    }

    /**
     * Deletes everything in the Company Table
     *
     * @return void
     */
    public function process()
    {
        try{
            echo "Delete Companies Task Running \n";
            // TODO: way to just drop the table and add it again?
            $companies = Company::get();
            foreach($companies as $company) {
                $company->delete();
            }
            echo "Done!\n";
        }
        catch(Exception $e) {
            echo "\n\nError while clearing Company Table\n" . $e->getMessage() . "\n\n";
        }

    }
}
