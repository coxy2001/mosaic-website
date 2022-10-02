<?php

namespace Mosaic\Website\Controller;

use Mosaic\Website\Model\TopCompanies;
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

        $topCompanies = $listID ? TopCompanies::get_by_id($listID) : TopCompanies::get()->last();

        return $topCompanies ? $topCompanies->getCSV() : null;
    }
}
