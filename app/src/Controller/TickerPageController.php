<?php

namespace Mosaic\Website\Controller;

use Mosaic\Website\Model\CompanyList;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;

class TickerPageController extends \PageController
{
    private static $allowed_actions = [
        "csv"
    ];

    private static $tableHeaders = [
        "Rank" => "RANK",
        "Name" => "COMPANY NAME",
        "Ticker" => "TICKER",
        "Exchange" => "EXCHANGE",
        "Sector" => "SECTOR",
        "MarketCap" => "MARKET CAP",
        "Price" => "PRICE",
        "ROA" => "ROA",
        "PE" => "PE",
        "EPS" => "EPS",
        "FreeCashFlow" => "FREE CASH FLOW",
        "DividendsYield" => "DIVIDENDS YIELD",
        "CurrentRatio" => "CURRENT RATIO",
        "PriceToBook" => "PRICE TO BOOK",
    ];

    private static $lengthOptions = [10, 15, 20, 30, 50];

    private static function viewableArray(array $items): ArrayList
    {
        $list = ArrayList::create();
        foreach ($items as $key => $value) {
            $list->push(ArrayData::create([
                "Key" => $key,
                "Value" => $value
            ]));
        }
        return $list;
    }

    public function index(HTTPRequest $request)
    {
        $listID = $request->getVar("list");
        $companyList = $listID ? CompanyList::get_by_id($listID) : CompanyList::get()->last();

        return [
            "CompanyList" => $companyList,
            "Companies" => $companyList ? $companyList->getPaginatedList() : null,
            "TableHeaders" => self::viewableArray(self::$tableHeaders),
            "LengthOptions" => self::viewableArray(self::$lengthOptions),
            "HistoryOptions" => CompanyList::get(),
            "CurrentLength" => $request->getVar("length") ?: CompanyList::DEFAULT_LENGTH,
            "CurrentCountries" => $request->getVar("countries") ?: null,
            "CurrentSectors" => $request->getVar("sectors") ?: null,
            "CurrentSort" => $request->getVar("sort") ?: CompanyList::DEFAULT_SORT,
            "CurrentDirectionClass" => "grid__head--sorted-" . strtolower(
                $request->getVar("direction") ?: CompanyList::DEFAULT_DIRECTION
            ),
        ];
    }

    public function csv(HTTPRequest $request)
    {
        $listID = $request->getVar("list");
        $companyList = $listID ? CompanyList::get_by_id($listID) : CompanyList::get()->last();
        return $companyList ? $companyList->getCSV() : null;
    }
}
