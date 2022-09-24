<?php

namespace Mosaic\Website\Cron;

use Exception;
use Mosaic\Website\Model\Company;
use Mosaic\Website\Model\CompanyVersion;
use Mosaic\Website\Model\TopCompanies;
use SilverStripe\CronTask\Interfaces\CronTask;

class UpdateCompanies implements CronTask
{
    const TOP_COMPANY_LIMIT = 200;
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
        echo "Adding TopCompanies and Versioning\n";
        $this->bundleTopCompanies(self::TOP_COMPANY_LIMIT);
        echo "Done\n";
    }

    public function bundleTopCompanies($limit)
    {
        $list = TopCompanies::create();
        $list->Name = date("Y F, d");
        $list->Year = "2022";
        $listID = $list->write();

        $companies = Company::get()->filter("ClassName", Company::class)->sort("Rank")->limit($limit);
        echo "Retrived: " . count($companies) . " from the database, sorted by rank\n";
        foreach ($companies as $company)
            $this->addCompanyToList($company, $listID);
    }

    public function addCompanyToList($company, $listID)
    {
        $values = $company->toMap();
        $values["TopCompaniesID"] = $listID;
        unset($values["ID"]);

        $version = CompanyVersion::create();
        return $version->update($values)->write();
    }
}
