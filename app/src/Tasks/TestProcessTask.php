<?php

namespace Mosaic\Website\Tasks;

use Mosaic\Website\Tasks\DeleteCompaniesTask;
use Mosaic\Website\Tasks\RankCompaniesPETask;
use Mosaic\Website\Tasks\RankCompaniesROATask;
use Mosaic\Website\Tasks\UpdateCompaniesTestTask;
use Mosaic\Website\Tasks\VersionCompaniesTask;
use SilverStripe\Dev\BuildTask;

class TestProcessTask extends BuildTask
{
    private const PAGE_LIMIT = 4;
    private const EXCHANGES = ["2", "3", "18", "20", "83", "104", "88", "51", "26", "22"];

    private static $segment = "TestProcessTask";

    /**
     * Test full process with a limited set of exchanges and a page limit
     *
     * @return void
     */
    public function run($request)
    {
        DeleteCompaniesTask::create()->run(null);
        UpdateCompaniesTestTask::create()->setLimits(self::PAGE_LIMIT, self::EXCHANGES)->run(null);
        RankCompaniesROATask::create()->run(null);
        RankCompaniesPETask::create()->run(null);
        VersionCompaniesTask::create()->run(null);
    }
}
