<?php

namespace Mosaic\Website\Cron;

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
        return "* * * * *";
    }


    public function process()
    {

    }
}
