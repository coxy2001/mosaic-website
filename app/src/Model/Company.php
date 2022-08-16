<?php

namespace Mosaic\Website\Model;

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

    private function formatDate($date)
    {
        return date("d F, Y", strtotime($date));
    }

    private function displayCompany($data)
    {
        if ($data == null)
            return null;

        return ArrayData::create([
            "Ticker" => $data->Ticker,
            "Name" => $data->Name,
            "Sector" => $data->Sector,
            "Date" => $this->formatDate($data->LastEdited),
            "MarketCap" => number_format($data->MarketCap),
            "Price" => number_format($data->Price, 2)
        ]);
    }

    private function displayCompanyList($data)
    {
        if ($data == null)
            return null;

        return ArrayList::create(array_map(
            "self::displayCompany",
            iterator_to_array($data)
        ));
    }

    public function getDate()
    {
        return $this->formatDate($this->LastEdited);
    }

    public function getHistory()
    {
        $versions = $this->Versions()->limit(8)->sort("Version ASC");
        return $this->displayCompanyList($versions);
    }

    public function getOldData()
    {
        $year = array_key_exists("year", $_GET) ? $_GET["year"] : 2022;
        $month = array_key_exists("month", $_GET) ? $_GET["month"] : 8;
        $nextMonth = $month + 1;
        $versions = $this->Versions("LastEdited >= '$year-$month-01' AND LastEdited < '$year-$nextMonth-01'");
        return $this->displayCompany($versions->first());
    }

    public function versionByDate($year, $month)
    {
        $nextMonth = $month + 1;
        $versions = $this->Versions("LastEdited >= '$year-$month-01' AND LastEdited < '$year-$nextMonth-01'");
        return $this->displayCompany($versions->first());
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
