<?php

namespace Mosaic\Website\Cron;

use Mosaic\Website\Model\Company;
use phpDocumentor\Reflection\Types\Null_;
use SilverStripe\CronTask\Interfaces\CronTask;

class UaDeleteCompaniesCron implements CronTask
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
        echo "Delete Companies Task Running \n";
        // TODO: way to just drop the table and add it again?
        $companies = Company::get();
        foreach($companies as $company) {
            $company->delete();
        }
        echo "Done!\n";

    }
}
