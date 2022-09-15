<?php
namespace Mosaic\Website\Cron;

use Exception;

class ListCompanyExtractor
{
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

    const FEATURES = [self::NAME, self::STOCK_SYMBOL, self::STOCK_EXCHANGE, self::SECTOR, self::PE, self::ROA, self::PRICE, self::MARKET_CAP, self::FREE_CASH_FLOW, self::EARNINGS_YIELD, self::VIEWDATA];
    
    public static function extractStocks($json) {
        $hits = $json['hits'];
        $companies = array();
        foreach($hits as $company) {
            array_push($companies, self::extractFeatures($company));
        }
        // RETURN list of companies
        return $companies;
    }

    function extractFeatures($company) {
        $extracted = array();
        $missing = array();
        // TODO check features
        foreach(self::FEATURES as $feature) {
            // TODO check certain features non null and allow some to be null
            try {
                if (strcmp($feature, self::VIEWDATA) == 0) {
                    $countryDetails = $company[$feature];
                    $extracted += [self::FLAG => $countryDetails[self::FLAG]];
                    $extracted += [self::LINK => (RequestBuilder::BASE_INVESTING_URL . $countryDetails[self::LINK])];
                }
                else {
                    $extracted += [$feature => $company[$feature] ?? null];
                }
            } catch(Exception $e) {
                $missing += $feature;
            }
        }
        // TODO: check what features were actually missing here
        // TODO: up with missing feature code here
        if (count($missing) != 0) {
            return $missing;
        }
        return $extracted;
    }
    // function extractAndSave($c) {
    //     $extracted = extractFeatures($c);
    //     if(count($extracted) != count(FEATURES) + 1) {
    //         echo "\n Missing Features Not Writing to DB \n";
    //         var_dump($extracted);
    //         return;
    //     }
    //     writeToDB($extracted);
    // }
}