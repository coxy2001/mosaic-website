<?php

namespace Mosaic\Website\Model\Page;

use Mosaic\Website\Controller\TickerPageController;
use Mosaic\Website\Model\TopCompanies;
use SilverStripe\Control\Controller;

class TickerPage extends \Page
{
    private static $page_name = "TickerPage";
    private static $controller_name = TickerPageController::class;
    private static $description = "Displays tickers";

    public function getTopCompanies()
    {
        $request = Controller::curr()->getRequest();
        $listID = $request->getVar("list");
        return $listID ? TopCompanies::get_by_id($listID) : TopCompanies::get()->last();
    }

    public function getCompanies()
    {
        $topCompanies = $this->getTopCompanies();
        return $topCompanies ? $topCompanies->getPaginatedList() : null;
    }

    public function getHistoryOptions()
    {
        return TopCompanies::get();
    }
}
