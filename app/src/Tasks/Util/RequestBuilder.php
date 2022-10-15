<?php
namespace Mosaic\Website\Tasks\Util;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
class RequestBuilder {
    const INCOME_STATEMENT = '-income-statement';
    const BALANCE_SHEET = '-balance-sheet';
    const BASE_INVESTING_URL = 'https://www.investing.com';
    const SCREENER_PATH = '/stock-screener/Service/SearchStocks';
    const TIMEOUT = 15;
    const COUNTRY = 5;

    // Constants for obtaining values from Investing.com 
    // consider ttm pe? ttmpehigh, ttmpelow
    // consider ttm roa? ttmroapct
    const NAME = 'name_trans';
    const STOCK_SYMBOL = 'stock_symbol';
    const STOCK_EXCHANGE = 'exchange_trans';
    const SECTOR = 'sector_trans';
    // const ROA = 'aroapct';
    const ROA = 'ttmroapct';
    const PE = 'eq_pe_ratio';
    const PRICE = 'last';
    const MARKET_CAP = 'eq_market_cap';
    // const MARKET_CAP = 'mktcap';
    const VIEWDATA = 'viewData';
    const LINK = 'link';
    const FLAG = 'flag';
    const CUSTOM_CALC = 'custom_calculation';
    // const FREE_CASH_FLOW = 'a1fcf';
    const FREE_CASH_FLOW = 'ttmfcf';
    const DIVIDENDS_YIELD = 'yield_us';

    const CURRENT_RATIO = 'acurratio';
    // const CURRENT_RATIO = 'qcurratio';
    // const PRICE_TO_BOOK = 'aprice2bk';
    // const PRICE_TO_BOOK = 'price2bk';
    const PRICE_TO_BOOK = 'price2bk_us';
    const EPS = 'eq_eps';
    const PARENT_PAIR_ID = 'parent_pair_ID';

    // List of features to extract
    const FEATURES = [self::NAME, self::STOCK_SYMBOL, self::STOCK_EXCHANGE, self::SECTOR, self::PE, self::ROA, self::PRICE,
        self::MARKET_CAP, self::FREE_CASH_FLOW, self::DIVIDENDS_YIELD, self::VIEWDATA, self::CURRENT_RATIO, self::PRICE_TO_BOOK,
        self::EPS, self::PARENT_PAIR_ID];

    // Client object for making requests
    public static function getClient() {
        return new Client([
            'timeout' => self::TIMEOUT
        ]);
    }

    // Requests data for 50 stocks based on the page number. Can filter by exchange
    // Returns JSON
    public static function requestScreener($pageNumber, $exchangeNumber, $client) {
        $response = $client->request('POST',  (self::BASE_INVESTING_URL . self::SCREENER_PATH), self::getScreenerRequestOptions($pageNumber, $exchangeNumber));
        return $response;
    }

    // Gets the HTML of a specified stock page on Investing.com 
    // Returns HTML
    public static function requestStockPage($url, $page, $client) {
        // Insert the page time (income statement or balance sheet)
        $url = self::addPageToUrl($url, $page);
        // Get the HTML
        $response = $client->request('GET', $url);
        return $response;
    }

    // Inserts a given page into the url
    static function addPageToUrl($url, $page) {
        $stringParts = explode('?', $url);
        $p1 = $stringParts[0];
        if (sizeof($stringParts) > 1) {
            $p2 = '?' . $stringParts[1];
        }
        else {
            $p2 = '';
        }
        return $p1 . $page . $p2;
    }
    
    // Properties for screener post request
    static function getScreenerRequestOptions($pagenumber, $exchange) {
        return [
            'headers' => self::getScreenerHeaders(),
            'form_params' => self::getScreenerBody($pagenumber, $exchange),
        ];
    }
    
    static function getScreenerBody($pn, $ex) {
        return [
            'country[]' => self::COUNTRY,
            'exchange[]' => $ex,
            'pn' => $pn,
            'order[col]' => 'eq_market_cap',
            'order[dir]' => 'd'
        ];
    }
    
    static function getScreenerHeaders() {
        return [
            'accept' => 'application/json, text/javascript, */*; q=0.01',
            'accept-language' => 'en-US,en;q=0.9',
            'content-type' => 'application/x-www-form-urlencoded',
            'x-requested-with' => 'XMLHttpRequest'
        ];
    }
}