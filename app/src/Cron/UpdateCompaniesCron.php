<?php

namespace Mosaic\Website\Cron;
require 'vendor/autoload.php';
use GuzzleHttp\Client;
use Mosaic\Website\Model\Company;
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

 const FEATURES = [NAME, STOCK_SYMBOL, STOCK_EXCHANGE, SECTOR, ROA, PRICE, MARKET_CAP, FREE_CASH_FLOW, EARNINGS_YIELD, VIEWDATA];

 const BASE_INVESTING_URL = 'https://www.investing.com/';
 const SCREENER_PATH = 'stock-screener/Service/SearchStocks';
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
        // var_dump($hits);

        $extracted = array();

        // for($i = 0; $i < count($hits); $i++)
        //     $company = $hits[$i];
            $i = 0;
        foreach($hits as $c) {
            // echo $company[NAME] . "\n";
            // var_dump($company);
            $companyLine = '';

            extractAndSave($c);

            // foreach (FEATURES as $feature){
            //     // TODO check if feature is present
            //     if (strcmp($feature, VIEWDATA) == 0) {
            //         $countryDetails = $c[$feature];
            //         $companyLine = $companyLine . $countryDetails[LINK] . " " . $countryDetails[FLAG] . " ";
            //     }
            //     else {
            //         $companyLine = $companyLine . $c[$feature] . " ";
            //     }
            // }
            // echo $companyLine;
            // array_push($extracted, $companyLine);
            echo $i . "\n";
            $i++;
            if($i > 1) {
                break;
            }
        }
    }
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
    // var_dump($extracted);
    writeToDB($extracted);
}

function extractFeatures($c) {
    $extracted = array();
    // TODO check features
    foreach(FEATURES as $feature) {
        if (strcmp($feature, VIEWDATA) == 0) {
            $countryDetails = $c[$feature];
            $extracted += [FLAG => $countryDetails[FLAG]];
            $extracted += [LINK => (BASE_INVESTING_URL . $countryDetails[LINK])];
        }
        else {
            $extracted += [$feature => $c[$feature]];
        }
    }
    return $extracted;
}

function writeToDB($extracted) {
    $company = Company::create();
    $company->Ticker = $extracted[STOCK_SYMBOL];
    $company->Name = $extracted[NAME];
    $company->Description = ($extracted[STOCK_EXCHANGE] . $extracted[FLAG]);
    // $company->Rank = 0;
    // $company->Sector = $extracted[SECTOR];
    // $company->MarketCapt = $extracted[MARKET_CAP];
    // $company->Price = $extracted[PRICE];
    // $company->ROC = 0;
    // $company->ROA = $extracted[ROA];
    // $company->PE = $extracted[PE];
    // $company->EarningsYield = $extracted[EARNINGS_YIELD];
    $company->Link = $extracted[LINK];
    $company->write();
}
