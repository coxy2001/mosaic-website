<?php

namespace Mosaic\Website\Tasks;

use Exception;
use Mosaic\Website\Model\Company;
use SilverStripe\Dev\BuildTask;

class RankCompaniesPETask extends BuildTask
{
    private static $segment = "RankCompaniesPETask";

    /**
     * Assign ROArank PErank and Rank
     *
     * @return void
     */
    // TODO: Error handling
    public function run($request)
    {
        try {
            $this->addPeRank();
        } catch (Exception $e) {
            echo "\n\nError while assigning ROA and PE rank to company table\n" . $e->getMessage() . "\n\n";
        }
    }

    // Get all companies in Company table ordered by PE
    // Use the order to asign a PE rank
    // Use the ROA rank to give an overall rank
    private function addPeRank()
    {
        echo "Adding PE rank and overall rank\n";
        $companiesPE = Company::get()->sort("AbsoluteValuePE", "ASC")->chunkedFetch();

        $counter = 1;
        foreach ($companiesPE as $company) {
            $rank = $counter + $company->RankROA;
            $company->RankPE = $counter;
            $company->Rank = $rank;
            $company->write();
            $counter++;
        }
        echo "Done\n";
    }
}
