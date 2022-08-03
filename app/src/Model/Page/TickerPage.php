<?php

namespace Mosiac\Website\Model\Page;

use Mosiac\Website\Controller\TickerPageController;
use Mosiac\Website\Model\Company;

class TickerPage extends \Page
{
    private static $page_name = "TickerPage";
    private static $controller_name = TickerPageController::class;
    private static $description = "Displays tickers";

    public function getCompanies()
    {
        return Company::get();
    }
}
