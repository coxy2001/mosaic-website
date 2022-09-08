<?php
    namespace Mosaic\Website\Cron;
    require 'vendor/autoload.php';
    use GuzzleHttp\Client;

    // Constants for column names
    define('NAME', 'name_trans');
    define('STOCK_SYMBOL', 'stock_symbol');
    define('STOCK_EXCHANGE', 'exchange_trans');
    define('SECTOR', 'sector_trans');
    define('ROA', 'aroapct');
    define('PE', 'eq_pe_ratio');
    define('PRICE', 'last');
    define('MARKET_CAP', 'eq_market_cap');
    define('FREE_CASH_FLOW', 'a1fcf');
    define('EARNINGS_YIELD', 'yield');
    define('LINK', 'viewData.link');
    define('FLAG', 'viewData.flag');
    define("features", [NAME, STOCK_SYMBOL, STOCK_EXCHANGE, SECTOR, ROA, PRICE, MARKET_CAP, FREE_CASH_FLOW, EARNINGS_YIELD, LINK, FLAG]);

    define('BASE_INVESTING_URL', 'https://www.investing.com/');
    define('SCREENER_PATH', 'stock-screener/Service/SearchStocks');
    define('FULL_URL', 'https://www.investing.com/stock-screener/Service/SearchStocks');
    define('TIMEOUT', 15);
    define('COUNTRY', 5);

    // Get data
    
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

    // Variables to be used
    $pageNumber = 1;
    $exchangeNumber = 50;

    $client = new Client([
        'base_uri' => BASE_INVESTING_URL,
        'timeout' => TIMEOUT
    ]);

    $response = $client->request('POST', SCREENER_PATH, getScreenerRequestOptions($pageNumber, $exchangeNumber));
    echo $response->getBody();

    // Manipulate JSON

    // Extract Desired Columns

    // Store data
?>