<?php

namespace Mosaic\Website\Cron;

use Exception;
use Mosaic\Website\Model\Company;
use Mosaic\Website\Model\CompanyVersion;
use Mosaic\Website\Model\TemporaryCompany;
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
        // $companies = Company::get()->filter("ClassName", Company::class)->sort("Rank")->limit($limit);
        $companies = Company::get()->sort("Rank")->limit($limit);
        
        $counter = 0;
        // foreach ($companies as $company) {
        //     // TODO: does catch get triggered if roa not in values
        //     try {
        //         $values = $company->toMap();
        //         unset($values["ID"]);
        //         $values["Rank"] = $counter;

        //         $newCompany = Company::create();
        //         $newCompany->update($values)->write();
        //     }
        //     catch (Exception $e) {
        //         echo "\n Error while copying from temp companies to companies\n" . $e->getMessage() . "\n";
        //     }
        //     $counter++;
        // }
        // $companies = Company::get()->filter("ClassName", Company::class)->sort("Rank")->limit($limit);
        $count = count($companies);
        echo "Retrived: " . $count . " from the database, sorted by rank\n";

        if ($count == 0) {
            echo "No entries available to put into top companies. Aborting... \n";
            return;
        }

        $list = TopCompanies::create();
        $list->Name = date("Y F, d");
        $list->Year = "2022";
        $listID = $list->write();

        foreach ($companies as $company) {
            $this->addCompanyToList($company, $listID, $counter);
            $counter += 1;
        }
    }

    public function addCompanyToList($company, $listID, $rank)
    {
        $values = $company->toMap();
        $values["TopCompaniesID"] = $listID;
        unset($values["ID"]);
        $values["Rank"] = $rank;

        $version = CompanyVersion::create();
        return $version->update($values)->write();
    }
}
