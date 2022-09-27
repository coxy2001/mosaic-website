<?php
namespace Mosaic\Website\Cron;

use Exception;

use function PHPUnit\Framework\isNull;

class ListCompanyExtractor
{   
    public static function extractStocks($json, $client) {
        $hits = $json['hits'];
        $companies = array();
        $i = 0;
        foreach($hits as $company) {
            $i += 1;
            try {
                $extracted = self::extractFeatures($company, $client);
                if (is_null($extracted)) {
                    throw new Exception("Extractor returned nothing");
                }
                array_push($companies, $extracted);
            }
            catch (Exception $e) {
                echo "\nSkipping company: " . $e->getMessage() . "\n";
            }
        }
        // RETURN list of companies
        return $companies;
    }

    static function extractFeatures($company, $client) {
        $extracted = array();
        $missing = array();
        // TODO check features
        foreach(RequestBuilder::FEATURES as $feature) {
            // TODO check certain features non null and allow some to be null
            try {
                // Skips if stock exists in another country (ppid)
                if (strcmp($feature, RequestBuilder::PARENT_PAIR_ID) == 0) {
                    if (!array_key_exists($feature, $company)) {
                        array_push($missing, $feature);
                        continue;
                    }
                    else {
                        $pID = $company[$feature];
                        if (intval($pID) != 0) {
                            // TODO: throw exception of custom type here instead of returning
                            // echo "PID: " . $pID . "\n";
                            // return;
                        }
                    }
                }
                else if (strcmp($feature, RequestBuilder::VIEWDATA) == 0) {
                    if (!array_key_exists($feature, $company)) {
                        array_push($missing, $feature);
                        array_push($missing, RequestBuilder::FLAG);
                        array_push($missing, RequestBuilder::LINK);
                        continue;
                    }
                    $countryDetails = $company[$feature];
                    if (!array_key_exists(RequestBuilder::FLAG, $countryDetails)) {
                        array_push($missing, RequestBuilder::FLAG);
                    }
                    else {
                        $extracted += [RequestBuilder::FLAG => $countryDetails[RequestBuilder::FLAG]];
                    }
                    if (!array_key_exists(RequestBuilder::LINK, $countryDetails)) {
                        array_push($missing, RequestBuilder::LINK);
                    }
                    else {
                        $extracted += [RequestBuilder::LINK => (RequestBuilder::BASE_INVESTING_URL . $countryDetails[RequestBuilder::LINK])];
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
        $extracted += [RequestBuilder::CUSTOM_CALC => null];
        if (count($missing) != 0) {
            echo "Fixing missing features \n";
            try {
                if (in_array(RequestBuilder::NAME, $missing) || in_array(RequestBuilder::STOCK_SYMBOL, $missing) || in_array(RequestBuilder::STOCK_EXCHANGE, $missing) || in_array(RequestBuilder::LINK, $missing) || in_array(RequestBuilder::PRICE, $missing)) {
                    throw new Exception('Missing required stock features');
                }
                else {
                    foreach ($missing as $feature) {
                        $extracted += [$feature => null];
                    }
                }
                if (in_array(RequestBuilder::ROA, $missing)) {
                    $ROA = MissingValueScraper::getROA($extracted[RequestBuilder::LINK], $client);
                    $extracted[RequestBuilder::ROA]= $ROA;
                    $extracted[RequestBuilder::CUSTOM_CALC] = true;
                }
                if (in_array(RequestBuilder::PE, $missing)) {
                    $PE = MissingValueScraper::getPE($extracted[RequestBuilder::LINK], $extracted[RequestBuilder::PRICE], $client);
                    $extracted[RequestBuilder::PE] = $PE;
                    $extracted[RequestBuilder::CUSTOM_CALC] = true;
                }
            }
            catch (Exception $e) {
                throw new Exception("ROA or PE could not be calculated! \nReason: " .$e->getMessage() . "\nStock: " . ($extracted[RequestBuilder::LINK] ?? null));
            }
        }
        else {
            $extracted[RequestBuilder::CUSTOM_CALC] = false;
        }
        return $extracted;
    }
}