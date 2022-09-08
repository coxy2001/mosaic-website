<?php

namespace Mosaic\Website\Model;

use SilverStripe\ORM\DataObject;

class Company extends DataObject
{
    private static $db = [
        "Ticker" => "Varchar(6)",
        "Name" => "Varchar",
        "Description" => "Text",
        "Rank" => "Int",
        "Sector" => "Varchar",
        "MarketCap" => "BigInt",
        "Price" => "Decimal",
        "ROC" => "Decimal",
        "EarningsYield" => "Decimal",
        "Link" => "Varchar"
    ];

    private static $table_name = 'Company';
    private static $singular_name = 'Company';
    private static $plural_name = 'Companies';

    private static $summary_fields = [
        "Rank",
        "Ticker",
        "Name",
        "Sector",
        "Price",
        "ROC",
        "Link"
    ];

    private static $searchable_fields = [
        "Ticker",
        "Name",
        "Sector"
    ];

    public function getDate()
    {
        return date("d F, Y", strtotime($this->LastEdited));
    }

    public function canView($member = null)
    {
        return True;
    }

    // public function canEdit($member = null)
    // {
    // }

    // public function canCreate($member = null, $context = [])
    // {
    // }

    // public function canDelete($member = null)
    // {
    // }
}
