<?php

namespace Mosaic\Website\Cron;

use Mosaic\Website\Model\Company;
use phpDocumentor\Reflection\Types\Null_;
use SilverStripe\CronTask\Interfaces\CronTask;

class VbDeleteCompaniesCron implements CronTask
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
        echo "Delete Companies Task Running \n";
        // TODO: way to just drop the table and add it again?
        $companies = Company::get();
        foreach($companies as $company) {
            $company->delete();
        }
        echo "Done!\n";

    }
}
