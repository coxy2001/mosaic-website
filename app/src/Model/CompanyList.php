<?php

namespace Mosaic\Website\Model;

use DateTime;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\PaginatedList;

class CompanyList extends DataObject
{
    public const DEFAULT_LENGTH = 30;
    public const DEFAULT_SORT = "Rank";
    public const DEFAULT_DIRECTION = "ASC";

    private static $db = [
        "Month" => "Int",
        "Year" => "Int",
    ];

    private static $has_many = [
        "Companies" => CompanyVersion::class
    ];

    private static $owns = [
        "Companies"
    ];

    private static $table_name = "CompanyList";
    private static $singular_name = "Company List";
    private static $plural_name = "Company List's";

    private static $summary_fields = [
        "Year",
        "Month",
    ];

    private static $searchable_fields = [
        "Year",
        "Month",
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

    public function getPaginatedList(HTTPRequest $request)
    {
        // Sort list by the 'sort' and 'direction' get arguments using DEFAULT_SORT and DEFAULT_DIRECTION as fallbacks
        $sort = [$request->getVar("sort") ?: self::DEFAULT_SORT => $request->getVar("direction") ?: self::DEFAULT_DIRECTION];
        // If there is a sort column given and it's not the default sort, add the default sort and direction as a secondary sort
        if ($request->getVar("sort") && $request->getVar("sort") !== self::DEFAULT_SORT)
            $sort[self::DEFAULT_SORT] = self::DEFAULT_DIRECTION;

        $filter = [];
        if ($request->getVar("countries")) {
            $filter["Flag"] = explode(',', $request->getVar("countries"));
        }
        if ($request->getVar("sectors")) {
            $filter["Sector"] = explode(',', $request->getVar("sectors"));
        }

        $paginatedList = PaginatedList::create(
            $this->Companies()->sort($sort)->filter($filter),
            $request
        )
            ->setPageLength($request->getVar("length") ?: self::DEFAULT_LENGTH)
            ->setPaginationGetVar('p');

        return $paginatedList;
    }

    public function getCSV()
    {
        $stream = fopen("php://output", 'w');
        fputcsv($stream, self::$csv_headers);

        $list = $this->Companies()->sort(self::DEFAULT_SORT, self::DEFAULT_DIRECTION);
        foreach ($list as $company) {
            fputcsv($stream, $company->getXMLValues(self::$csv_headers));
        }
    }

    public function getName()
    {
        if ($this->Year && $this->Month) {
            $dateObj = DateTime::createFromFormat('!m', $this->Month);
            $month = $dateObj->format('F');
            $year = $this->Year;
            return  "$year, $month";
        } else return "No Name";
    }
}
