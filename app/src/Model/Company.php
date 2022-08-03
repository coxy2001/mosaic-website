<?php

namespace Mosiac\Website\Model;

use SilverStripe\ORM\ArrayList;
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
        $versions = $this->Versions()->limit(8)->sort("Version ASC");
        $historyData = ArrayList::create();
        foreach ($versions as $data) {
            $historyData->push([
                "LastEdited" => date("d/F/Y", strtotime($data->LastEdited)),
                "MarketCap" => number_format($data->MarketCap),
                "Price" => $data->Price
            ]);
        }
        return $historyData;
    }
}
