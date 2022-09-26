<?php

namespace Mosaic\Website\Cron;

use Mosaic\Website\Model\Company;
use phpDocumentor\Reflection\Types\Null_;
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
        echo "Update Companies Task Running \n";
        $allCompanies = CompanyGetter::getAll();
    }
}
