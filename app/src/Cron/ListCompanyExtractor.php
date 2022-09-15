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

    const FEATURES = [NAME, STOCK_SYMBOL, STOCK_EXCHANGE, SECTOR, PE, ROA, PRICE, MARKET_CAP, FREE_CASH_FLOW, EARNINGS_YIELD, VIEWDATA];
    
    public function extractStocks($json) {
        $hits = $json['hits'];
        echo "\ncount of hits list: ";
        echo count($hits);
        echo "\n";

        $i = 1;
        foreach($hits as $company) {
            extractFeatures($company);
            echo $i . "\n";
            $i++;
            // if($i > 1) {
            //     break;
            // }
        }
        // RETURN list of companies
    }

    function extractFeatures($company) {
        $extracted = array();
        $missing = array();
        // TODO check features
        foreach(FEATURES as $feature) {
            // TODO check certain features non null and allow some to be null
            try {
                if (strcmp($feature, VIEWDATA) == 0) {
                    $countryDetails = $company[$feature];
                    $extracted += [FLAG => $countryDetails[FLAG]];
                    $extracted += [LINK => (BASE_INVESTING_URL . $countryDetails[LINK])];
                }
                else {
                    $extracted += [$feature => $company[$feature] ?? null];
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
}