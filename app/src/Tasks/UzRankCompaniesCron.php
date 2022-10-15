<?php

namespace Mosaic\Website\Tasks;

use Exception;
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
     * Assign ROArank PErank and Rank
     *
     * @return void
     */
    // TODO: Error handling
    public function process()
    {
        try {
            $this->addRoaRank();
            $this->addPeRank();
        }
        catch(Exception $e) {
            echo "\n\nError while assigning ROA and PE rank to company table\n" . $e->getMessage() . "\n\n";
        }

    }

    // Get all companies in Company table ordered by ROA
    // Use the order to assign an ROA rank and update the entry 
    private function addRoaRank() {
        echo "Rank Companies Task Running \n";
        echo "Getting Companies Sorted by ROA\n";
        $companiesROA = Company::get()->sort("ROA", "DESC")->chunkedFetch();

        echo "Adding ROA rank\n";
        $counter = 1;
        foreach($companiesROA as $company) {
            $company->RankROA = $counter;
            $company->write();
            $counter++;
        }
        echo "Done\n";
    }

    // Get all companies in Company table ordered by PE
    // Use the order to asign a PE rank
    // Use the ROA rank to give an overall rank
    private function addPeRank() {
        echo "Adding PE rank and overall rank\n";
        $companiesPE = Company::get()->sort("AbsoluteValuePE", "ASC")->chunkedFetch();

        $counter = 1;
        foreach($companiesPE as $company) {
            $rank = $counter + $company->RankROA;
            $company->RankPE = $counter;
            $company->Rank = $rank;
            $company->write();
            $counter++;
        }
        echo "Done\n";
    }
}
