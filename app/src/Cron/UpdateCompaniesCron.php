<?php

namespace Mosaic\Website\Cron;

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
        return "*/5 * * * *";
    }

    /**
     * Update company data
     *
     * @return void
     */
    public function process()
    {
    }
}
