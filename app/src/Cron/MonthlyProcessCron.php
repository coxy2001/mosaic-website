<?php

namespace Mosaic\Website\Cron;

use Mosaic\Website\Tasks\DeleteCompaniesTask;
use Mosaic\Website\Tasks\RankCompaniesPETask;
use Mosaic\Website\Tasks\RankCompaniesROATask;
use Mosaic\Website\Tasks\UpdateCompaniesTask;
use Mosaic\Website\Tasks\VersionCompaniesTask;
use SilverStripe\CronTask\Interfaces\CronTask;

class MonthlyProcessCron implements CronTask
{
    /**
     * Run this task every 5 minutes
     *
     * @return string
     */
    public function getSchedule()
    {
        return null;
    }

    public function process()
    {
        DeleteCompaniesTask::create()->run(null);
        UpdateCompaniesTask::create()->run(null);
        RankCompaniesROATask::create()->run(null);
        RankCompaniesPETask::create()->run(null);
        VersionCompaniesTask::create()->run(null);
    }
}
