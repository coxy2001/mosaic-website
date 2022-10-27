<?php

namespace Mosaic\Website\Tasks\Util;

use Exception;
use Mosaic\Website\Model\Company;

class CompanyGetter
{
    // Gets all the stock data into the company database
    public static function getAll($pageLimit = -1, $exchanges = null)
    {
        $client = RequestBuilder::getClient();
        if (is_null($exchanges)) {
            print("Getting All Exchanges\n");
            $exchanges = self::getAllExchanges($client);
            print("Done\n");
        }

        echo ("Exchanges: " . json_encode($exchanges) . "\n");
        $overallCount = 0;
        $overallSuccessCount = 0;
        $exchangeLoopCount = 1;

        foreach ($exchanges as $exchangeNumber) {
            $pageNumber = 1;
            print("Getting Exchange " . $exchangeNumber . " (" . $exchangeLoopCount . " of " . sizeof($exchanges) . ")" . "\n");
            $exchangeLoopCount++;
            try {
                // Generate and send the first request
                $response = RequestBuilder::requestScreener($pageNumber, $exchangeNumber, $client);
                $j = json_decode($response->getBody(), true);

                // Get the number of loops required for the country/exchange selection
                $totalCount = $j['totalCount'];
                $successCount = 0;
                echo "count from total count: ";
                echo $totalCount . "\n";
                if ($totalCount > RequestBuilder::EXCHANGE_LIMIT) {
                    echo ("Skipping exchange: " . $exchangeNumber . " count too large!\n");
                    continue;
                }
                if (!array_key_exists('hits', $j)) {
                    throw new Exception("Stocks list not found in response!\n");
                }
                $hits = $j['hits'];
                $iterations = ceil($totalCount / count($hits));
                echo "Iterations for this batch: " . ($iterations) . "\n";
            } catch (Exception $e) {
                echo "\nEror recieveing response from investing.com: " . $e->getMessage() . "\n";
                echo $e->getMessage() . "\n";
            }
            $total = count($hits);
            for ($i = 0; $i < $iterations; $i++) {
                if (!is_null($pageLimit) && $i == $pageLimit) {
                    break;
                }
                try {
                    // Print current page
                    echo "\nPage: " . ($i + 1) . "/" . $iterations . "\n";

                    // Extract the stocks from the JSON
                    $companies = array();
                    echo "Extracting data\n";
                    $companies = ListCompanyExtractor::extractStocks($j, $client);

                    // echo "\nSkipped " . (count($hits) - count($companies)) . " companies\n";

                } catch (Exception $e) {
                    // TODO: How many timeouts to allow for
                    echo "\nEror extracting data from investing.com: " . $e->getMessage() . "\n";
                }
                try {
                    // Write the results to the database
                    echo "Writing to DB\n";
                    foreach ($companies as $company) {
                        self::writeToDB($company);
                        $successCount++;
                    }
                    echo "Done\n";
                    // echo $successCount . " Succesful Writes\n";
                } catch (Exception $e) {
                    echo "\nEror writing data to tempCompanies " . $e->getMessage() . "\n";
                }
                try {
                    // Get the next set of data, unless we're done then continue
                    if ($i + 1 == $iterations || $i + 1 == $pageLimit) {
                        break;
                    }
                    $pageNumber++;

                    echo "Sending Request to investing.com\n";
                    $response = RequestBuilder::requestScreener($pageNumber, $exchangeNumber, $client);
                    echo "Response Recieved from investing.com\n";

                    $j = json_decode($response->getBody(), true);
                    if (!array_key_exists('hits', $j)) {
                        throw new Exception("Stocks list not found in response!\n");
                    }
                    $hits = $j['hits'];
                    $total += count($hits);
                } catch (Exception $e) {
                    echo "\nEror recieveing response from investing.com: " . $e->getMessage() . "\n";
                    echo $e->getMessage() . "\n";
                }
            }
            echo "\n\nTotal: " . $total . "\n";
            echo "Successful Writes: " . $successCount . "\n\n";
            $overallCount = $overallCount + $total;
            $overallSuccessCount = $overallSuccessCount + $successCount;
        }
        echo "Overall Count: " . $overallCount . "\n";
        echo "Overall Successful Writes:" . $overallSuccessCount . "\n\n";
    }

    private static function getAllExchanges($client)
    {
        $exchanges = [];
        try {
            $response = RequestBuilder::requestScreener("0", ",", $client, RequestBuilder::COUNTRY_TO_GET_EXCHANGES);
            $j = json_decode($response->getBody(), true);
            if (array_key_exists("aggs", $j) && array_key_exists("exchangeAgg", $j["aggs"]) && array_key_exists("buckets", $j["aggs"]["exchangeAgg"])) {
                $exchangeList = $j["aggs"]["exchangeAgg"]["buckets"];
                foreach ($exchangeList as $exchange) {
                    $badExchange = false;
                    if (!array_key_exists("key", $exchange)) {
                        throw new Exception("Could not find exchange ID in listing");
                    }
                    $exchangeID = $exchange["key"];
                    foreach(RequestBuilder::BAD_EXCHANGES as $bad) {
                        if(strcmp($exchangeID, $bad) == 0) {
                            $badExchange = true;
                            break;
                        }
                    }
                    if(!$badExchange) {
                        array_push($exchanges, $exchangeID);
                    }
                    else {
                        echo("Skipping exchange: " . $exchangeID . "\n");
                    }
                }
            } else {
                throw new Exception("JSON did not contain exchange list\n" . json_encode($j) . "\n");
            }
            return $exchanges;
        } catch (Exception $e) {
            echo "\nError trying to get all exchanges: " . $e->getMessage() . "\n";
            return null;
        }
    }

    // Creates and writes a new entry for the company database
    private static function writeToDB($extracted)
    {
        $tempCompany = Company::create();
        $tempCompany->update([
            "Ticker" => $extracted[RequestBuilder::STOCK_SYMBOL],
            "Name" => $extracted[RequestBuilder::NAME],
            "Exchange" => $extracted[RequestBuilder::STOCK_EXCHANGE],
            "Sector" => $extracted[RequestBuilder::SECTOR],
            "MarketCap" => $extracted[RequestBuilder::MARKET_CAP],
            "Price" => $extracted[RequestBuilder::PRICE],
            "ROA" => $extracted[RequestBuilder::ROA],
            "PE" => $extracted[RequestBuilder::PE],
            "EPS" => $extracted[RequestBuilder::EPS],
            "AbsoluteValuePE" => abs($extracted[RequestBuilder::PE]),
            "DividendsYield" => $extracted[RequestBuilder::DIVIDENDS_YIELD],
            "Link" => $extracted[RequestBuilder::LINK],
            "Flag" => $extracted[RequestBuilder::FLAG],
            "FreeCashFlow" => $extracted[RequestBuilder::FREE_CASH_FLOW],
            "CurrentRatio" => $extracted[RequestBuilder::CURRENT_RATIO],
            "PriceToBook" => $extracted[RequestBuilder::PRICE_TO_BOOK],
            "CustomCalculation" => $extracted[RequestBuilder::CUSTOM_CALC],
        ])->write();
    }
}
