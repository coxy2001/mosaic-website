<?php
    namespace Mosaic\Website\Cron;
    // require 'vendor/autoload.php';
    // use GuzzleHttp\Client;
    // use Mosaic\Website\Model\Company;

    // // Constants for column names
    // define('NAME', 'name_trans');
    // define('STOCK_SYMBOL', 'stock_symbol');
    // define('STOCK_EXCHANGE', 'exchange_trans');
    // define('SECTOR', 'sector_trans');
    // define('ROA', 'aroapct');
    // define('PE', 'eq_pe_ratio');
    // define('PRICE', 'last');
    // define('MARKET_CAP', 'eq_market_cap');
    // define('FREE_CASH_FLOW', 'a1fcf');
    // define('EARNINGS_YIELD', 'yield');
    // define('VIEWDATA', 'viewData');
    // define('LINK', 'link');
    // define('FLAG', 'flag');
    // define("FEATURES", [NAME, STOCK_SYMBOL, STOCK_EXCHANGE, SECTOR, ROA, PRICE, MARKET_CAP, FREE_CASH_FLOW, EARNINGS_YIELD, VIEWDATA]);

    // $features = [NAME, STOCK_SYMBOL, STOCK_EXCHANGE];

    // define('BASE_INVESTING_URL', 'https://www.investing.com/');
    // define('SCREENER_PATH', 'stock-screener/Service/SearchStocks');
    // define('FULL_URL', 'https://www.investing.com/stock-screener/Service/SearchStocks');
    // define('TIMEOUT', 15);
    // define('COUNTRY', 5);

    // // Get data
    
    // function getScreenerRequestOptions($pn, $ex) {
    //     return [
    //         'headers' => getScreenerHeaders(),
    //         'form_params' => getScreenerBody($pn, $ex),
    //     ];
    // }

    // function getScreenerBody($pn, $ex) {
    //     return [
    //         'country[]' => COUNTRY,
    //         'exchange[]' => $ex,
    //         'pn' => $pn,
    //         'order[col]' => 'eq_market_cap',
    //         'order[dir]' => 'd'
    //     ];
    // }

    // function getScreenerHeaders() {
    //     return [
    //         'accept' => 'application/json, text/javascript, */*; q=0.01',
    //         'accept-language' => 'en-US,en;q=0.9',
    //         'content-type' => 'application/x-www-form-urlencoded',
    //         'x-requested-with' => 'XMLHttpRequest'
    //     ];
    // }

    // function extractAndSave($c) {
    //     $extracted = extractFeatures($c);
    //     // var_dump($extracted);
    //     writeToDB($extracted);
    // }

    // function extractFeatures($c) {
    //     $extracted = array();
    //     // TODO check features
    //     foreach(FEATURES as $feature) {
    //         if (strcmp($feature, VIEWDATA) == 0) {
    //             $countryDetails = $c[$feature];
    //             $extracted += [FLAG => $countryDetails[FLAG]];
    //             $extracted += [LINK => (BASE_INVESTING_URL . $countryDetails[LINK])];
    //         }
    //         else {
    //             $extracted += [$feature => $c[$feature]];
    //         }
    //     }
    //     return $extracted;
    // }

    // function writeToDB($extracted) {
    //     $company = Company::create();
    //     $company->Ticker = $extracted[STOCK_SYMBOL];
    //     $company->Name = $extracted[NAME];
    //     $company->Description = $extracted[STOCK_EXCHANGE];
    //     $company->Rank = 0;
    //     $company->Sector = $extracted[SECTOR];
    //     $company->MarketCapt = $extracted[MARKET_CAP];
    //     $company->Price = $extracted[PRICE];
    //     $company->ROC = 0;
    //     $company->ROA = $extracted[ROA];
    //     $company->PE = $extracted[PE];
    //     $company->EarningsYield = $extracted[EARNINGS_YIELD];
    //     $company->Link = $extracted[LINK];
    // }

    // // Variables to be used
    // $pageNumber = 1;
    // $exchangeNumber = 50;

    // $client = new Client([
    //     'base_uri' => BASE_INVESTING_URL,
    //     'timeout' => TIMEOUT
    // ]);

    // $response = $client->request('POST', SCREENER_PATH, getScreenerRequestOptions($pageNumber, $exchangeNumber));
    // // echo $response->getBody();
    // $j = json_decode($response->getBody(), true);
    // // var_dump($j);
    // $totalCount = $j['totalCount'];
    // echo "count from total count: ";
    // echo $totalCount;
    // $hits = $j['hits'];
    // echo "\ncount of hits list: ";
    // echo count($hits);
    // echo "\n";
    // // var_dump($hits);

    // $extracted = array();

    // // for($i = 0; $i < count($hits); $i++)
    // //     $company = $hits[$i];
    //     $i = 0;
    // foreach($hits as $c) {
    //     // echo $company[NAME] . "\n";
    //     // var_dump($company);
    //     $companyLine = '';
    //     extractAndSave($c);
    //     // foreach (FEATURES as $feature){
    //     //     // TODO check if feature is present
    //     //     if (strcmp($feature, VIEWDATA) == 0) {
    //     //         $countryDetails = $c[$feature];
    //     //         $companyLine = $companyLine . $countryDetails[LINK] . " " . $countryDetails[FLAG] . " ";
    //     //     }
    //     //     else {
    //     //         $companyLine = $companyLine . $c[$feature] . " ";
    //     //     }
    //     // }
    //     // echo $companyLine;
    //     // array_push($extracted, $companyLine);
    //     echo $i . "\n";
    //     $i++;
    //     if($i > 1) {
    //         break;
    //     }
    // }
    // // foreach ($extracted as $line) {
    // //     echo 
    // // }
    //         // TODO check if feature is present
    //     //     if (strcmp($feature, VIEWDATA)) {
    //     //         $countryDetails = $company[$feature];
    //     //         $companyLine = $companyLine . $countryDetails[LINK] . " " . $countryDetails[FLAG] . " ";
    //     //     }
    //     //     else {
    //     //         $companyLine = $companyLine . $company[$feature] . " ";
    //     //     }
    //     // echo $companyLine . "\n";

    // // Manipulate JSON

    // // Extract Desired Columns

    // // Store data
?>