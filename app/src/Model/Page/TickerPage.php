<?php

namespace Mosaic\Website\Model\Page;

use Mosaic\Website\Controller\TickerPageController;
use Mosaic\Website\Model\Company;
use Mosaic\Website\Model\TopCompanies;
use SilverStripe\ORM\Filters\WithinRangeFilter;

class TickerPage extends \Page
{
    private static $page_name = "TickerPage";
    private static $controller_name = TickerPageController::class;
    private static $description = "Displays tickers";

    public function getCompanies()
    {
        return Company::get();
    }

    public function getTopCompanies()
    {
        $year = array_key_exists("year", $_GET) ? $_GET["year"] : 2022;
        $month = array_key_exists("month", $_GET) ? $_GET["month"] : 8;
        $nextMonth = $month + 1;

        $dateRange = WithinRangeFilter::create();
        $dateRange->setMin("'$year-$month-01'");
        $dateRange->setMax("'$year-$nextMonth-01'");

        $topCompany = TopCompanies::get()
            ->filter(["LastEdited:WithinRange" => $dateRange])
            ->first();

        if ($topCompany)
            return $topCompany->Companies;

        return null;
    }
}
