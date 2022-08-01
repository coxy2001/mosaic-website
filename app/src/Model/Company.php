<?php

namespace Ticker\Model;

use SilverStripe\ORM\DataObject;
use SilverStripe\Versioned\Versioned;

class Company extends DataObject
{
    private static $db = [
        "Ticker" => "Varchar(6)",
        "Name" => "Varchar",
        "Description" => "Text",
        "Sector" => "Varchar",
        "MarketCap" => "BigInt",
        "Price" => "Decimal",
        "ROC" => "Decimal",
        "EarningsYield" => "Decimal",
    ];

    private static $table_name = 'Company';
    private static $singular_name = 'Company';
    private static $plural_name = 'Companies';

    private static $extensions = [
        Versioned::class . '.versioned'
    ];

    private static $summary_fields = [
        "Ticker",
        "Name",
        "Description",
        "Price",
        "ROC"
    ];

    private static $searchable_fields = [
        "Ticker",
        "Name",
        "Description"
    ];

    public function getHistory()
    {
        return $this->Versions()->limit(8)->sort("Version ASC");
    }
}
