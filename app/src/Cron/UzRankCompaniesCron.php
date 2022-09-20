<?php

namespace Mosaic\Website\Cron;

use Mosaic\Website\Model\Company;
use SilverStripe\CronTask\Interfaces\CronTask;

class UzRankCompaniesCron implements CronTask
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

    /**
     * Update company data
     *
     * @return void
     */
    // TODO: Error handling
    public function process()
    {
        echo "Rank Companies Task Running \n";
        echo "Getting Companies Sorted by ROA\n";
        $companiesROA = Company::get()->sort('AbsoluteValueROA')->chunkedFetch();

        $counter = 1;
        echo "Adding ROA rank\n";
        foreach($companiesROA as $company) {
            $company->RankROA = $counter;
            $company->write();
            $counter++;
        }
        echo "Done\n";

        $counter = 1;
        echo "Adding PE rank and overall rank\n";
        $companiesPE = Company::get()->sort('AbsoluteValuePE')->chunkedFetch();
        foreach($companiesPE as $company) {
            $PErank = $counter;
            // TODO: check this exists before accessing
            $ROArank = $company->RankROA;
            $rank = $PErank + $ROArank;
            
            $company->RankPE = $PErank;
            $company->Rank = $rank;

            $company->write();

            $counter++;
        }
        echo "Done\n";



    }
}
