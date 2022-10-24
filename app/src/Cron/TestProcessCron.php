<?php

namespace Mosaic\Website\Cron;

use Mosaic\Website\Tasks\DeleteCompaniesTask;
use Mosaic\Website\Tasks\RankCompaniesPETask;
use Mosaic\Website\Tasks\RankCompaniesROATask;
use Mosaic\Website\Tasks\UpdateCompaniesTestTask;
use Mosaic\Website\Tasks\VersionCompaniesTask;
use SilverStripe\CronTask\Interfaces\CronTask;

class TestProcessCron implements CronTask
{
    private const PAGE_LIMIT = -1;
    // private const EXCHANGES = ["2", "3", "18", "20", "83", "104", "88", "51", "26", "22"];    
    // private const EXCHANGES = ["51"];
    private const EXCHANGES = null;


    /**
     * Run this task every 5 minutes
     *
     * @return string
     */
    public function getSchedule()
    {
        return "* * * * *";
    }

    public function process()
    {
        DeleteCompaniesTask::create()->run(null);
        UpdateCompaniesTestTask::create()->setLimits(self::PAGE_LIMIT, self::EXCHANGES)->run(null);
        RankCompaniesROATask::create()->run(null);
        RankCompaniesPETask::create()->run(null);
        VersionCompaniesTask::create()->run(null);
    }
}
