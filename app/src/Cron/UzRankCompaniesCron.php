<?php

namespace Mosaic\Website\Cron;

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
        $companiesROA = Company::get()->sort('ROA')->chunkedFetch();


        $counter = 1;
        echo "Adding ROA rank\n";
        foreach($companiesROA as $company) {
            $values = $company->toMap();
            if(!array_key_exists("RankROA",$values) || !array_key_exists("RankPE",$values) || !array_key_exists("Rank",$values)) {
                echo "\n\nCompany missing nessecary fields. Aborting rank ROA\n\n";
                continue;
            }
            $values["RankROA"] = $counter;
            $company->update($values)->write();
            $counter++;
        }
        echo "Done\n";
    }

    // Get all companies in Company table ordered by PE
    // Use the order to asign a PE rank
    // Use the ROA rank to give an overall rank
    private function addPeRank() {
        $counter = 1;
        echo "Adding PE rank and overall rank\n";

        $companiesPE = Company::get()->sort('AbsoluteValuePE')->chunkedFetch();

        foreach($companiesPE as $company) {
            $values = $company->toMap();
            $PErank = $counter;

            if(!array_key_exists("RankROA",$values) || !array_key_exists("RankPE",$values) || !array_key_exists("Rank",$values)) {
                echo "\n\nCompany missing nessecary fields. Aborting rank PE\n\n";
                continue;
            }

            $ROArank = $values["RankROA"];
            $rank = $PErank + $ROArank;
            
            $values["RankPE"] = $PErank;
            $values["Rank"] = $rank;

            $company->update($values)->write();

            $counter++;
        }
        echo "Done\n";
    }
}
