<?php

namespace Mosaic\Website\Model;

use DateTime;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\PaginatedList;

class CompanyList extends DataObject
{
    // Default layout for sending to front end
    public const DEFAULT_LENGTH = 30;
    public const DEFAULT_SORT = "Rank";
    public const DEFAULT_DIRECTION = "ASC";

    private static $db = [
        "Month" => "Int",
        "Year" => "Int",
    ];

    // Each entry corresponds to a set of Companies in CompanyVersion table
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

    // Headers for writing out to CSV
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

    // Method for getting a complete set of stock data in paged chunks
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
        if ($request->getVar("custom")) {
            $filter["CustomCalculation"] = $request->getVar("custom");
        }

        $paginatedList = PaginatedList::create(
            $this->Companies()->sort($sort)->filter($filter),
            $request
        )
            ->setPageLength($request->getVar("length") ?: self::DEFAULT_LENGTH)
            ->setPaginationGetVar('p');

        return $paginatedList;
    }

    // Method for writing data out to a CSV
    public function getCSV()
    {
        $stream = fopen("php://output", 'w');
        fputcsv($stream, self::$csv_headers);

        $list = $this->Companies()->sort(self::DEFAULT_SORT, self::DEFAULT_DIRECTION);
        foreach ($list as $company) {
            fputcsv($stream, $company->getXMLValues(self::$csv_headers));
        }
    }

    // Method for getting the name of an entry based on the date of its creation.
    public function getName()
    {
        if ($this->Year && $this->Month) {
            $dateObj = DateTime::createFromFormat('!m', $this->Month);
            $month = $dateObj->format('F');
            $year = $this->Year;
            return  "$year, $month";
        } else return "No Name";
    }

    public function canView($member = null)
    {
        return true;
    }
}
