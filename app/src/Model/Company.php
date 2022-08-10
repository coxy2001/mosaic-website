<?php

namespace Mosiac\Website\Model;

use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\Versioned\Versioned;
use SilverStripe\View\ArrayData;

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
        "Sector",
        "Price",
        "ROC"
    ];

    private static $searchable_fields = [
        "Ticker",
        "Name",
        "Sector"
    ];

    public function getHistory()
    {
        $versions = $this->Versions()->limit(8)->sort("Version ASC");
        $historyData = ArrayList::create();
        foreach ($versions as $data) {
            $historyData->push([
                "Date" => date("d/F/Y", strtotime($data->LastEdited)),
                "MarketCap" => number_format($data->MarketCap),
                "Price" => $data->Price
            ]);
        }
        return $historyData;
    }

    public function getOldData()
    {
        $data = $this->Versions("LastEdited >= '2022-07-31' AND LastEdited < '2022-08-01'")->first();
        $historyData = [
            "Date" => date("d/F/Y", strtotime($data->LastEdited)),
            "MarketCap" => number_format($data->MarketCap),
            "Price" => $data->Price
        ];
        return ArrayData::create($historyData);
    }

    public function getDate()
    {
        return date("d/F/Y", strtotime($this->LastEdited));
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
