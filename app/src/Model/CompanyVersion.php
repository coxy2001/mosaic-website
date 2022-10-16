<?php

namespace Mosaic\Website\Model;

use SilverStripe\ORM\DataObject;

class CompanyVersion extends DataObject
{
    private static $db = Company::DB_FIELDS;

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
        "ROC",
        "PE",
    ];

    private static $searchable_fields = [
        "Ticker",
        "Name",
        "Sector",
    ];

    private function shortenNumber($num)
    {
        $units = ['', 'K', 'M', 'B', 'T'];
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
}
