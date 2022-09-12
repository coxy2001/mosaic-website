<?php

namespace Mosaic\Website\Model\Page;

use Mosaic\Website\Controller\TickerPageController;
use Mosaic\Website\Model\Company;
use Mosaic\Website\Model\TopCompanies;
use SilverStripe\Control\Controller;

class TickerPage extends \Page
{
    private static $page_name = "TickerPage";
    private static $controller_name = TickerPageController::class;
    private static $description = "Displays tickers";

    public function getCompanies()
    {
        return Company::get()->filter("ClassName", Company::class);
    }

    public function getTopCompanies()
    {
        $request = Controller::curr()->getRequest();
        $listID = $request->getVar("list");

        $topCompanies = $listID ? TopCompanies::get_by_id($listID) : TopCompanies::get()->last();

        return $topCompanies ? $topCompanies->getPaginatedList() : null;
    }

    public function getHistoryOptions()
    {
        return TopCompanies::get();
    }
}
