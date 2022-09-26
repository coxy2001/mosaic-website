<?php
namespace Mosaic\Website\Cron;
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
    const ROA = 'aroapct';
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

    const FEATURES = [self::NAME, self::STOCK_SYMBOL, self::STOCK_EXCHANGE, self::SECTOR, self::PE, self::ROA, self::PRICE,
        self::MARKET_CAP, self::FREE_CASH_FLOW, self::DIVIDENDS_YIELD, self::VIEWDATA, self::CURRENT_RATIO, self::PRICE_TO_BOOK,
        self::EPS, self::PARENT_PAIR_ID];

    public static function getClient() {
        return new Client([
            'timeout' => self::TIMEOUT
        ]);
    }

    public static function requestScreener($pageNumber, $exchangeNumber, $client) {
        $response = $client->request('POST',  (self::BASE_INVESTING_URL . self::SCREENER_PATH), self::getScreenerRequestOptions($pageNumber, $exchangeNumber));
        return $response;
    }

    public static function requestStockPage($url, $page, $client) {
        $url = self::addPageToUrl($url, $page);
        $response = $client->request('GET', $url);
        return $response;
    }

    static function addPageToUrl($url, $page) {
        if (strcmp($page, self::INCOME_STATEMENT) != 0 || strcmp($page, self::BALANCE_SHEET) != 0) {
            // TODO return / error
        }
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