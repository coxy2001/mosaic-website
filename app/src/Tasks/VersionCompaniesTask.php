<?php

namespace Mosaic\Website\Tasks;

use Exception;
use Mosaic\Website\Model\Company;
use Mosaic\Website\Model\CompanyVersion;
use Mosaic\Website\Model\TemporaryCompany;
use Mosaic\Website\Model\CompanyList;
use SilverStripe\CronTask\Interfaces\CronTask;

class VersionCompaniesTask implements CronTask
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
     * Create new CompanyList entry
     * Give top 200 rank of Company the version
     * Write the companies to CompanyVersion
     *
     * @return void
     */
    public function process()
    {
        try {
            echo "Adding CompanyList and Versioning\n";
            $this->bundleCompanyList();
            echo "Done\n";
        }
        catch(Exception $e) {
            echo "\n\nError while adding top companies\n" . $e->getMessage() . "\n\n";
        }
    }

    // Creates a new entry in top companies
    // Takes all the stocks currently in Company table 
    // Assigns them a version and puts the top 200 into the CompanyVersion table
    public function bundleCompanyList()
    {
        // Get all the companies currently in the Company table ordered by Rank
        $companies = Company::get()->sort("Rank")->limit(self::TOP_COMPANY_LIMIT);

        $count = $companies->count();;
        echo "Retrived: " . $count . " from the database, sorted by rank\n";

        // Don't create a new CompanyList entry if data failed to get into Company table
        if ($count == 0) {
            echo "No entries available to put into top companies. Aborting... \n";
            return;
        }

        // Create new entry in CompanyList table
        $list = CompanyList::create();
        $list->Month = date("m");
        $list->Year = date("Y");
        $listID = $list->write();

        // Set counter for relabling the Rank in an incremental fashion
        $counter = 1;
        // For each company in Company table
        // Relable rank and put top 200 in CompanyList table
        foreach ($companies as $company) {
            $this->addCompanyToList($company, $listID, $counter);
            $counter++;
        }
    }

    // Adds the company to the CompanyVersionTable complete with version number
    public function addCompanyToList($company, $listID, $rank)
    {
        // Get the values of the current company
        $values = $company->toMap();

        // Unset the ID as we want this to be done when the object is written to the database
        unset($values["ID"]);
        // Set the top companies ID
        $values["CompanyListID"] = $listID;
        // Set the rank (Important for this rank to be incremental)
        $values["Rank"] = $rank;

        // Create the CompanyVersion entry and write to the CompanyVersion table
        $version = CompanyVersion::create();
        return $version->update($values)->write();
    }
}
