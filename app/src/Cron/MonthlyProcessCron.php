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
    // This cron task executes once a month
    public function getSchedule()
    {
        return "* * * * *";
    }

    // Run all the tasks to get new data, rank it, and version it
    public function process()
    {
        DeleteCompaniesTask::create()->run(null);
        UpdateCompaniesTask::create()->run(null);
        RankCompaniesROATask::create()->run(null);
        RankCompaniesPETask::create()->run(null);
        VersionCompaniesTask::create()->run(null);
    }
}
