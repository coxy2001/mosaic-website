<?php

namespace Mosaic\Website\Model;

use SilverStripe\ORM\DataObject;

class Company extends DataObject
{
    protected const DB_FIELDS = [
        "Ticker" => "Varchar(6)",
        "Name" => "Varchar",
        "Ticker" => "Varchar",
        "Exchange" => "Varchar",
        "Description" => "Text",
        "Rank" => "Int",
        "RankROA" => "Int",
        "RankPE" => "Int",
        "Sector" => "Varchar",
        "MarketCap" => "BigInt",
        "Price" => "Decimal",
        "ROA" => "Decimal",
        "PE" => "Decimal",
        "AbsoluteValuePE" => "Decimal",
        "FreeCashFlow" => "Int",
        "DividendsYield" => "Decimal",
        "EarningsPerShare" => "Int",
        "Link" => "Varchar",
        "Flag" => "Varchar",
        "CustomCalculation" => "Boolean",
        "CurrentRatio" => "Decimal",
        "CashHoldings" => "Int",
        "PriceToBook" => "Decimal",
        "5yrPE" => "Int",
    ];
    private static $db = self::DB_FIELDS;

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
        "Sector",
        "Link",
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
