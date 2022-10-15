<?php

namespace Mosaic\Website\Tasks;

use Exception;
use Mosaic\Website\Model\Company;
use SilverStripe\Dev\BuildTask;

class RankCompaniesROATask extends BuildTask
{
    private static $segment = "RankCompaniesROATask";
    /**
     * Assign ROArank PErank and Rank
     *
     * @return void
     */
    // TODO: Error handling
    public function run($request)
    {
        try {
            $this->addRoaRank();
        } catch (Exception $e) {
            echo "\n\nError while assigning ROA and PE rank to company table\n" . $e->getMessage() . "\n\n";
        }
    }

    // Get all companies in Company table ordered by ROA
    // Use the order to assign an ROA rank and update the entry 
    private function addRoaRank()
    {
        echo "Rank Companies Task Running \n";
        echo "Getting Companies Sorted by ROA\n";
        $companiesROA = Company::get()->sort("ROA", "DESC")->chunkedFetch();

        echo "Adding ROA rank\n";
        $counter = 1;
        foreach ($companiesROA as $company) {
            $company->RankROA = $counter;
            $company->write();
            $counter++;
        }
        echo "Done\n";
    }
}
