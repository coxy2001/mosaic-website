<?php

namespace Mosaic\Website\Model;

use SilverStripe\ORM\DataObject;

class Company extends DataObject
{
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
    ];

    private static $db = self::DB_FIELDS;

    private static $table_name = 'Company';
    private static $singular_name = 'Company';
    private static $plural_name = 'Companies';

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
}
