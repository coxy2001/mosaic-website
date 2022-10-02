<?php

namespace Mosaic\Website\Model\Page;

use Mosaic\Website\Controller\TickerPageController;
use Mosaic\Website\Model\CompanyList;
use SilverStripe\Control\Controller;

class TickerPage extends \Page
{
    private static $page_name = "TickerPage";
    private static $controller_name = TickerPageController::class;
    private static $description = "Displays tickers";

    public function getCompanyList()
    {
        $request = Controller::curr()->getRequest();
        $listID = $request->getVar("list");
        return $listID ? CompanyList::get_by_id($listID) : CompanyList::get()->last();
    }

    public function getCompanies()
    {
        $CompanyList = $this->getCompanyList();
        return $CompanyList ? $CompanyList->getPaginatedList() : null;
    }

    public function getHistoryOptions()
    {
        return CompanyList::get();
    }
}
