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
    const CUSTOM_CALC = 'custom_calculation';

    const FEATURES = [self::NAME, self::STOCK_SYMBOL, self::STOCK_EXCHANGE, self::SECTOR, self::PE, self::ROA, self::PRICE, self::MARKET_CAP, self::FREE_CASH_FLOW, self::EARNINGS_YIELD, self::VIEWDATA];
    
    public static function extractStocks($json, $client) {
        $hits = $json['hits'];
        $companies = array();
        $i = 0;
        foreach($hits as $company) {
            $i += 1;
            try {
                array_push($companies, self::extractFeatures($company, $client));
            }
            catch (Exception $e) {
                echo "\nSkipping company: " . $e->getMessage() . "\n";
            }
            // if ($i == 10) {
            //     break;
            // }
        }
        // RETURN list of companies
        return $companies;
    }

    static function extractFeatures($company, $client) {
        $extracted = array();
        $missing = array();
        // TODO check features
        foreach(self::FEATURES as $feature) {
            // TODO check certain features non null and allow some to be null
            try {
                if (strcmp($feature, self::VIEWDATA) == 0) {
                    if (!array_key_exists($feature, $company)) {
                        array_push($missing, $feature);
                        array_push($missing, self::FLAG);
                        array_push($missing, self::LINK);
                        return;
                    }
                    $countryDetails = $company[$feature];
                    if (!array_key_exists(self::FLAG, $countryDetails)) {
                        array_push($missing, self::FLAG);
                    }
                    else {
                        $extracted += [self::FLAG => $countryDetails[self::FLAG]];
                    }
                    if (!array_key_exists(self::LINK, $countryDetails)) {
                        array_push($missing, self::LINK);
                    }
                    else {
                        $extracted += [self::LINK => (RequestBuilder::BASE_INVESTING_URL . $countryDetails[self::LINK])];
                    }
                }
                else {
                    if (array_key_exists($feature, $company)) {
                        $extracted += [$feature => $company[$feature]];
                    }
                    else {
                        array_push($missing, $feature);
                    }
                }
            } catch(Exception $e) {
                array_push($missing, $feature);
            }
        }
        $extracted += [self::CUSTOM_CALC => null];
        if (count($missing) != 0) {
            echo "Fixing missing features \n";
            try {
                if (in_array(self::NAME, $missing) || in_array(self::STOCK_SYMBOL, $missing) || in_array(self::STOCK_EXCHANGE, $missing) || in_array(self::LINK, $missing) || in_array(self::PRICE, $missing)) {
                    throw new Exception('Missing required stock features');
                }
                else {
                    foreach ($missing as $feature) {
                        $extracted += [$feature => null];
                    }
                }
                if (in_array(self::ROA, $missing)) {
                    $ROA = MissingValueScraper::getROA($extracted[self::LINK], $client);
                    $extracted[self::ROA]= $ROA;
                    $extracted[self::CUSTOM_CALC] = true;
                }
                if (in_array(self::PE, $missing)) {
                    $PE = MissingValueScraper::getPE($extracted[self::LINK], $extracted[self::PRICE], $client);
                    $extracted[self::PE] = $PE;
                    $extracted[self::CUSTOM_CALC] = true;
                }
            }
            catch (Exception $e) {
                throw new Exception('ROA or PE could not be calculated! Reason: '.$e->getMessage());
            }
        }
        else {
            $extracted[self::CUSTOM_CALC] = false;
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