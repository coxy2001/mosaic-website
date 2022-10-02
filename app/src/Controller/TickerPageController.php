<?php

namespace Mosaic\Website\Controller;

use Mosaic\Website\Model\CompanyList;
use SilverStripe\Control\Controller;

class TickerPageController extends \PageController
{
    private static $allowed_actions = [
        "csv"
    ];

    public function csv()
    {
        $request = Controller::curr()->getRequest();
        $listID = $request->getVar("list");

        $CompanyList = $listID ? CompanyList::get_by_id($listID) : CompanyList::get()->last();

        return $CompanyList ? $CompanyList->getCSV() : null;
    }
}
