<?php

namespace Mosaic\Website\Model\Page;

use Mosaic\Website\Controller\TickerPageController;
use Mosaic\Website\Model\Company;
use Mosaic\Website\Model\TopCompanies;

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
        // $listID = array_key_exists("list", $_GET) ? $_GET["list"] : 1;

        $topCompany = TopCompanies::get()->first();

        return $topCompany ? $topCompany->getPaginatedList() : null;
    }
}
