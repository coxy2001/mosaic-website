<?php

namespace Mosaic\Website\Model;

use SilverStripe\ORM\DataObject;

class Company extends DataObject
{
    // TODO: bad to have this public?
    public const DB_FIELDS = [
        "Ticker" => "Varchar(6)",
        "Name" => "Varchar",
        "Exchange" => "Varchar",
        "Rank" => "Int",
        "RankROA" => "Int",
        "RankPE" => "Int",
        "Sector" => "Varchar",
        "MarketCap" => "BigInt",
        "Price" => "Decimal",
        "ROA" => "Decimal",
        "PE" => "Decimal",
        "EPS" => "Decimal",
        "AbsoluteValuePE" => "Decimal",
        "FreeCashFlow" => "Int",
        "DividendsYield" => "Decimal",
        "Link" => "Varchar",
        "Flag" => "Varchar",
        "CustomCalculation" => "Boolean",
        "CurrentRatio" => "Decimal",
        "PriceToBook" => "Decimal",
        // "CashHoldings" => "Int",
        // "5yrPE" => "Int",
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
