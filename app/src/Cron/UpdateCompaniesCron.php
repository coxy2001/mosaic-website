<?php

namespace Mosaic\Website\Cron;

use Exception;
use GuzzleHttp\Client;
use Mosaic\Website\Model\Company;
use Mosaic\Website\Model\CompanyVersion;
use Mosaic\Website\Model\TopCompanies;
use SilverStripe\CronTask\Interfaces\CronTask;

// Constants for obtaining values from Investing.com
const NAME = 'name_trans';
const STOCK_SYMBOL = 'stock_symbol';
const STOCK_EXCHANGE = 'exchange_trans';
const SECTOR = 'sector_trans';
const ROA = 'aroapct';
const PE = 'eq_pe_ratio';
const PRICE = 'last';
const MARKET_CAP = 'eq_market_cap';
const FREE_CASH_FLOW = 'a1fcf';
const EARNINGS_YIELD = 'yield';
const VIEWDATA = 'viewData';
const LINK = 'link';
const FLAG = 'flag';

const FEATURES = [NAME, STOCK_SYMBOL, STOCK_EXCHANGE, SECTOR, PE, ROA, PRICE, MARKET_CAP, FREE_CASH_FLOW, EARNINGS_YIELD, VIEWDATA];

const BASE_INVESTING_URL = 'https://www.investing.com/';
const SCREENER_PATH = 'stock-screener/Service/SearchStocks';
const TIMEOUT = 15;
const COUNTRY = 5;

class UpdateCompaniesCron implements CronTask
{
    /**
     * Run this task every minute
     *
     * @return string
     */
    public function getSchedule()
    {
        return "* * * * *";
    }

    /**
     * Update company data
     *
     * @return void
     */
    public function process()
    {
        $pageNumber = 1;
        $exchangeNumber = 50;

        $client = new Client([
            'base_uri' => BASE_INVESTING_URL,
            'timeout' => TIMEOUT
        ]);

        $response = $client->request('POST', SCREENER_PATH, getScreenerRequestOptions($pageNumber, $exchangeNumber));
        $j = json_decode($response->getBody(), true);
        $totalCount = $j['totalCount'];
        $hits = $j['hits'];
        echo "count from total count: $totalCount <br>";
        echo "count of hits list: " . count($hits);

        foreach ($hits as $c) {
            extractAndSave($c);
        }

        $this->bundleTopCompanies(500);
    }

    public function bundleTopCompanies($limit)
    {
        $list = TopCompanies::create();
        $list->Name = date("Y F, d");
        $list->Year = "2022";
        $listID = $list->write();

        $companies = Company::get()->filter("ClassName", Company::class)->sort("Rank")->limit($limit);
        foreach ($companies as $company)
            $this->addCompanyToList($company, $listID);
    }

    public function addCompanyToList($company, $listID)
    {
        $values = $company->toMap();
        $values["TopCompaniesID"] = $listID;
        unset($values["ID"]);

        $version = CompanyVersion::create();
        return $version->update($values)->write();
    }
}

function getScreenerRequestOptions($pn, $ex)
{
    return [
        'headers' => getScreenerHeaders(),
        'form_params' => getScreenerBody($pn, $ex),
    ];
}

function getScreenerBody($pn, $ex)
{
    return [
        'country[]' => COUNTRY,
        'exchange[]' => $ex,
        'pn' => $pn,
        'order[col]' => 'eq_market_cap',
        'order[dir]' => 'd'
    ];
}

function getScreenerHeaders()
{
    return [
        'accept' => 'application/json, text/javascript, */*; q=0.01',
        'accept-language' => 'en-US,en;q=0.9',
        'content-type' => 'application/x-www-form-urlencoded',
        'x-requested-with' => 'XMLHttpRequest'
    ];
}

function extractAndSave($c)
{
    $extracted = extractFeatures($c);
    if (count($extracted) != count(FEATURES) + 1) {
        echo "\n Missing Features Not Writing to DB \n";
        var_dump($extracted);
        return;
    }
    writeToDB($extracted);
}

function extractFeatures($c)
{
    $extracted = [];
    $missing = [];

    foreach (FEATURES as $feature) {
        try {
            if (strcmp($feature, VIEWDATA) == 0) {
                $countryDetails = $c[$feature];
                $extracted += [FLAG => $countryDetails[FLAG]];
                $extracted += [LINK => (BASE_INVESTING_URL . $countryDetails[LINK])];
            } else {
                $extracted += [$feature => $c[$feature] ?? null];
            }
        } catch (Exception $e) {
            $missing += $feature;
        }
    }
    if (count($missing) != 0) {
        return $missing;
    }
    return $extracted;
}

function writeToDB($extracted)
{
    $company = Company::create();
    $company->update([
        "Ticker" => $extracted[STOCK_SYMBOL],
        "Name" => $extracted[NAME],
        "Description" => ($extracted[STOCK_EXCHANGE] . " : " . $extracted[FLAG]),
        "Rank" => 0,
        "Sector" => $extracted[SECTOR],
        "MarketCap" => $extracted[MARKET_CAP],
        "Price" => $extracted[PRICE],
        "ROC" => 0,
        "ROA" => $extracted[ROA],
        "PE" => $extracted[PE],
        "EarningsYield" => $extracted[EARNINGS_YIELD],
        "Link" => $extracted[LINK],
    ])->write();
}
