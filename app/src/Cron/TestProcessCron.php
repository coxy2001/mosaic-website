<?php

namespace Mosaic\Website\Cron;

use Mosaic\Website\Tasks\DeleteCompaniesTask;
use Mosaic\Website\Tasks\RankCompaniesPETask;
use Mosaic\Website\Tasks\RankCompaniesROATask;
use Mosaic\Website\Tasks\UpdateCompaniesTask;
use Mosaic\Website\Tasks\VersionCompaniesTask;
use SilverStripe\CronTask\Interfaces\CronTask;

class TestProcessCron implements CronTask
{
    private const PAGE_LIMIT = 4;
    private const EXCHANGES = ["2","83"];

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
        UpdateCompaniesTask::create()->setLimits(self::PAGE_LIMIT, self::EXCHANGES)->run(null);
        RankCompaniesROATask::create()->run(null);
        RankCompaniesPETask::create()->run(null);
        VersionCompaniesTask::create()->run(null);
    }
}
