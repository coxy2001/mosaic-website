<?php

namespace Mosaic\Website\Tasks\Util;

use Exception;

class ListCompanyExtractor
{
    // Extracts stock data from Investing.com JSON response
    public static function extractStocks($json, $client)
    {
        // hits is where the results are
        $hits = $json['hits'];
        $companies = array();
        $i = 0;
        foreach ($hits as $company) {
            $i += 1;
            try {
                // For each company get all the values we care about
                $extracted = self::extractFeatures($company, $client);
                if (is_null($extracted)) {
                    continue;
                }
                array_push($companies, $extracted);
            } catch (Exception $e) {
                echo "\nSkipping company: " . $e->getMessage() . "\n\n";
            }
        }
        // RETURN list of companies
        return $companies;
    }

    // Extract key features
    // Returns an associative array of features and values
    static function extractFeatures($company, $client)
    {
        $extracted = array();
        $missing = array();

        // Go through all the key features we want to grab
        foreach (RequestBuilder::FEATURES as $feature) {
            // TODO check certain features non null and allow some to be null

            // Get all the features
            // If a feature is missing add it to the missing list to be handled later
            try {
                // Skips if stock exists in another country (ppid)
                if (strcmp($feature, RequestBuilder::PARENT_PAIR_ID) == 0) {
                    if (!array_key_exists($feature, $company)) {
                        array_push($missing, $feature);
                        continue;
                    } else {
                        $pID = $company[$feature];
                        if (intval($pID) != 0) {
                            // TODO: throw exception of custom type here instead of returning
                            // echo "PID: " . $pID . "\n";
                            return;
                        }
                    }
                }
                // Extract flag and link, which is under the VIEWDATA tag
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
                    } else {
                        $extracted += [RequestBuilder::FLAG => $countryDetails[RequestBuilder::FLAG]];
                    }
                    if (!array_key_exists(RequestBuilder::LINK, $countryDetails)) {
                        array_push($missing, RequestBuilder::LINK);
                    } else {
                        $extracted += [RequestBuilder::LINK => (RequestBuilder::BASE_INVESTING_URL . $countryDetails[RequestBuilder::LINK])];
                    }
                }
                // Get all the other features
                else {
                    if (array_key_exists($feature, $company)) {
                        if (strcmp($feature, RequestBuilder::ROA) == 0) {
                            self::checkNull($company[$feature], RequestBuilder::ROA);
                        } else if (strcmp($feature, RequestBuilder::PE) == 0) {
                            self::checkNull($company[$feature], RequestBuilder::PE);
                        }

                        $extracted += [$feature => $company[$feature]];
                    } else {
                        array_push($missing, $feature);
                    }
                }
            } catch (Exception $e) {
                array_push($missing, $feature);
            }
        }
        $extracted += [RequestBuilder::CUSTOM_CALC => false];
        if (count($missing) != 0) {
            // echo "Fixing missing features \n";
            try {
                // Check for required features
                if (in_array(RequestBuilder::NAME, $missing) || in_array(RequestBuilder::STOCK_SYMBOL, $missing) || in_array(RequestBuilder::STOCK_EXCHANGE, $missing) || in_array(RequestBuilder::LINK, $missing) || in_array(RequestBuilder::PRICE, $missing)) {
                    throw new Exception("Missing required stock features\n");
                } else {
                    // If feature not required just leave it blank
                    foreach ($missing as $feature) {
                        $extracted += [$feature => null];
                    }
                }
                // If ROA missing try to find it using the missing value scraper and set the Custom Calc property
                if (in_array(RequestBuilder::ROA, $missing)) {
                    $ROA = MissingValueScraper::getROA($extracted[RequestBuilder::LINK], $client);
                    $extracted[RequestBuilder::ROA] = $ROA;
                    $extracted[RequestBuilder::CUSTOM_CALC] = true;
                    self::checkNull($ROA, RequestBuilder::ROA);
                }
                // If PE missing try to find it using the missing value scraper and set the Custom Calc property
                if (in_array(RequestBuilder::PE, $missing)) {
                    $PE = MissingValueScraper::getPE($extracted[RequestBuilder::LINK], $extracted[RequestBuilder::PRICE], $client);
                    $extracted[RequestBuilder::PE] = $PE;
                    $extracted[RequestBuilder::CUSTOM_CALC] = true;
                    self::checkNull($PE, RequestBuilder::PE);
                }
            }
            // Catch the error if ROA/PE cannot be calculated
            catch (Exception $e) {
                throw new Exception("ROA or PE could not be calculated! \nReason: " . $e->getMessage() . "Stock: " . ($extracted[RequestBuilder::LINK] ?? null . "\n"));
            }
        }
        self::checkNullEnd($extracted[RequestBuilder::ROA], $extracted[RequestBuilder::PE]);
        // Return the extracted values as an array.
        return $extracted;
    }

    private static function checkNull($value, $valueName = " ")
    {
        if (is_null($value)) {
            throw new Exception($valueName . " was null.\n");
        } else if ($value == 0) {
            throw new Exception($valueName . " was 0.\n");
        }
    }
    private static function checkNullEnd($ROA, $PE)
    {
        try {
            self::checkNull($ROA);
            self::checkNull($PE);
        } catch (Exception $e) {
            throw new Exception("ROA or PE was still null or zero by the end\n");
        }
    }
}
