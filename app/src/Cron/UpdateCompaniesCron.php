<?php

namespace Mosaic\Website\Cron;

use DOMDocument;
use DOMXPath;
use Exception;
use GuzzleHttp\Client;
use Mosaic\Website\Model\Company;
use Mosaic\Website\Model\CompanyVersion;
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
 const INCOME_STATEMENT = '-income-statement';
 const BALANCE_SHEET = '-balance-sheet';
 const TIMEOUT = 15;
 const COUNTRY = 5;
 
class UpdateCompaniesCron implements CronTask
{
    /**
     * Run this task every 5 minutes
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
        echo "this is my crontask \n";
        // Variables to be used
        $pageNumber = 1;
        $exchangeNumber = 50;

        $client = new Client([
            'base_uri' => BASE_INVESTING_URL,
            'timeout' => TIMEOUT
        ]);

        scrape($client, "/equities/linde-plc-income-statement?cid=942017");
        
        return;

        $response = $client->request('POST', SCREENER_PATH, getScreenerRequestOptions($pageNumber, $exchangeNumber));
        // $response = $client->request('POST', SCREENER_PATH);
        // echo $response->getBody();
        $j = json_decode($response->getBody(), true);
        // var_dump($j);
        $totalCount = $j['totalCount'];
        echo "count from total count: ";
        echo $totalCount;
        $hits = $j['hits'];
        echo "\ncount of hits list: ";
        echo count($hits);
        echo "\n";

        $i = 1;
        foreach($hits as $c) {
            extractAndSave($c);
            echo $i . "\n";
            $i++;
            // if($i > 1) {
            //     break;
            // }
        }
    }
}
function addCompanyToList($company, $listID) {
        $version = CompanyVersion::create();
        $values = $company->toMap();
        $values["TopCompaniesID"] = $listID;
        unset($values["ID"]);
        $version->update($values);
        return $version->write();
    }


function getScreenerRequestOptions($pn, $ex) {
    return [
        'headers' => getScreenerHeaders(),
        'form_params' => getScreenerBody($pn, $ex),
    ];
}

function getScreenerBody($pn, $ex) {
    return [
        'country[]' => COUNTRY,
        'exchange[]' => $ex,
        'pn' => $pn,
        'order[col]' => 'eq_market_cap',
        'order[dir]' => 'd'
    ];
}

function getScreenerHeaders() {
    return [
        'accept' => 'application/json, text/javascript, */*; q=0.01',
        'accept-language' => 'en-US,en;q=0.9',
        'content-type' => 'application/x-www-form-urlencoded',
        'x-requested-with' => 'XMLHttpRequest'
    ];
}

function extractAndSave($c) {
    $extracted = extractFeatures($c);
    if(count($extracted) != count(FEATURES) + 1) {
        echo "\n Missing Features Not Writing to DB \n";
        var_dump($extracted);
        return;
    }
    writeToDB($extracted);
}

function extractFeatures($c) {
    $extracted = array();
    $missing = array();
    // TODO check features
    foreach(FEATURES as $feature) {
        // TODO check certain features non null and allow some to be null
        try {
            if (strcmp($feature, VIEWDATA) == 0) {
                $countryDetails = $c[$feature];
                $extracted += [FLAG => $countryDetails[FLAG]];
                $extracted += [LINK => (BASE_INVESTING_URL . $countryDetails[LINK])];
            }
            else {
                $extracted += [$feature => $c[$feature] ?? null];
            }
        } catch(Exception $e) {
            $missing += $feature;
        }
    }
    if (count($missing) != 0) {
        return $missing;
    }
    return $extracted;
}

function writeToDB($extracted) {
    // TODO write null not 0
    $company = Company::create();
    $company->Ticker = $extracted[STOCK_SYMBOL];
    $company->Name = $extracted[NAME];
    $company->Description = ($extracted[STOCK_EXCHANGE] . $extracted[FLAG]);
    $company->Rank = 0;
    $company->Sector = $extracted[SECTOR];
    $company->MarketCapt = $extracted[MARKET_CAP];
    $company->Price = $extracted[PRICE];
    $company->ROC = 0;
    $company->ROA = $extracted[ROA];
    $company->PE = $extracted[PE];
    $company->EarningsYield = $extracted[EARNINGS_YIELD];
    $company->Link = $extracted[LINK];
    $company->write();
    echo "Successful db write ";
}

function scrape($client, $url) {
    $response = $client->request('GET', $url);
    $html = $response->getBody();
    // var_dump($html);
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML($html);
    // echo $doc->saveHTML();
    $xpath = new DOMXPath($doc);
    // var_dump($xpath);
    libxml_clear_errors();

    $extracted = $xpath->evaluate('//parent::span[text()="Total Revenue"]');
    foreach($extracted as $extraction) {
        var_dump($extraction->parentNode->parentNode);
    }
}

function addPageToUrl($url, $page) {
    $stringParts = explode('?', $url);
}

function processBalanceSheet() {

}

function processIncomeStatement($client, $url) {

}