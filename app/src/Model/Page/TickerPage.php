<?php

namespace Mosaic\Website\Model\Page;

use Mosaic\Website\Controller\TickerPageController;
use Mosaic\Website\Model\Company;
use SilverStripe\ORM\ArrayList;

class TickerPage extends \Page
{
    private static $page_name = "TickerPage";
    private static $controller_name = TickerPageController::class;
    private static $description = "Displays tickers";

    public function getCompanies()
    {
        return Company::get();
    }

    public function getCompaniesByDate($year = 2022, $month = 8)
    {
        $companies = [];
        foreach (Company::get() as $company) {
            array_push($companies, $company->versionByDate($year, $month));
        }
        return ArrayList::create($companies);
    }
}
