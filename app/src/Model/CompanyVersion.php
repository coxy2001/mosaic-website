<?php

namespace Mosaic\Website\Model;

use SilverStripe\ORM\DataObject;

class CompanyVersion extends DataObject
{
    // Fields should be the same as Company table
    private static $db = Company::DB_FIELDS;

    // Every company in this table belongs to a set based on version (When it was added)
    // So each company has the id of the set/version it belongs to.
    private static $has_one = [
        "CompanyList" => CompanyList::class
    ];

    private static $table_name = 'CompanyVersion';
    private static $singular_name = 'Company Version';
    private static $plural_name = 'Company Versions';

    private static $summary_fields = [
        "Rank",
        "Ticker",
        "Name",
        "Exchange",
        "Sector",
        "MarketCap",
        "Price",
        "ROA",
        "PE",
    ];

    private static $searchable_fields = [
        "Ticker",
        "Name",
        "Flag",
        "Exchange",
        "Sector",
    ];

    // Methods for nice formatting

    private function shortenNumber($num)
    {
        $units = ['', 'K', 'M', 'B', 'T', 'Q', 'Qu', 'S'];
        for ($i = 0; $num >= 1000; $i++) {
            $num /= 1000;
        }
        return round($num, 2) . $units[$i];
    }

    public function getMarketCapFormatted()
    {
        return self::shortenNumber($this->MarketCap);
    }

    public function getPriceFormatted()
    {
        return self::shortenNumber($this->Price);
    }

    public function canView($member = null)
    {
        return true;
    }
}
