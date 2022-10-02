<?php

namespace Mosaic\Website\Model;

use SilverStripe\Control\Controller;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\PaginatedList;

class CompanyList extends DataObject
{
    private static $db = [
        "Name" => "Varchar",
        "Month" => "Varchar",
        "Year" => "Varchar",
    ];

    private static $has_many = [
        "Companies" => CompanyVersion::class
    ];

    private static $owns = [
        "Companies"
    ];

    private static $table_name = "CompanyList";
    private static $singular_name = "Top Companies List";
    private static $plural_name = "Top Companies List's";

    private static $summary_fields = [
        "Name",
        "Month",
        "Year",
        "LastEdited",
    ];

    private static $searchable_fields = [
        "Name",
        "Month",
        "Year",
        "LastEdited",
    ];

    private static $csv_headers = [
        "Rank",
        "Ticker",
        "Name",
        "Exchange",
        "Sector",
        "MarketCap",
        "Price",
        "ROA",
        "PE",
        "EPS",
        "FreeCashFlow",
        "DividendsYield",
        "CurrentRatio",
        "PriceToBook",
        "CustomCalculation",
        "Flag",
        "Link",
    ];

    public function getPaginatedList()
    {
        $request = Controller::curr()->getRequest();

        $sort = [$request->getVar("sort") ?: "Rank" => $request->getVar("direction") ?: "ASC"];
        if ($request->getVar("sort") && $request->getVar("sort") !== "Rank")
            $sort["Rank"] = "ASC";

        $filter = [];
        if ($request->getVar("countries")) {
            $filter["Sector"] = explode(',', $request->getVar("countries"));
        }

        $paginatedList = PaginatedList::create(
            $this->Companies()->sort($sort)->filter($filter),
            $request
        )
            ->setPageLength($request->getVar("length") ?: 50)
            ->setPaginationGetVar('p');

        return $paginatedList;
    }

    public function getCSV()
    {
        $stream = fopen("php://output", 'w');
        fputcsv($stream, self::$csv_headers);

        $list = $this->Companies()->sort("Rank");
        foreach ($list as $company) {
            fputcsv($stream, $company->getXMLValues(self::$csv_headers));
        }
    }
}
