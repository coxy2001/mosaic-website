<?php
namespace Mosaic\Website\Cron;
use Exception;
use Mosaic\Website\Model\Company;

class CompanyGetter 
{   
    // Gets all the stock data into the company database
    public static function getAll() {
        $pageNumber = 1;
        $exchangeNumber = "2";
        try {
            // Generate and send the first request
            $client = RequestBuilder::getClient();
            $response = RequestBuilder::requestScreener($pageNumber, $exchangeNumber, $client);
            $j = json_decode($response->getBody(), true);

            // Get the number of loops required for the country/exchange selection
            $totalCount = $j['totalCount'];
            echo "count from total count: ";
            echo $totalCount . "\n";
            $hits = $j['hits'];
            $iterations = ceil($totalCount / count($hits));
            echo "Iterations for this batch: " . ($iterations) . "\n";

        }
        catch(Exception $e) {
            echo "\nEror recieveing response from investing.com: " . $e->getMessage() . "\n";
            echo $e->getMessage() . "\n";
        }
        $total = count($hits);
        for ($i = 0; $i < $iterations; $i++){
            if ($i == 4) {
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

            }
            catch (Exception $e) {
                // TODO: How many timeouts to allow for
                echo "\nEror extracting data from investing.com: " . $e->getMessage() . "\n";
            }
            try {
                // Write the results to the database
                $successCount = 0;
                echo "Writing to DB\n";
                foreach ($companies as $company) {
                    self::writeToDB($company);
                    $successCount++;
                }
                echo "Done\n";
                // echo $successCount . " Succesful Writes\n";
            }
            catch (Exception $e) {
                echo "\nEror writing data to tempCompanies " . $e->getMessage() . "\n";
            }
            try {
                // Get the next set of data, unless we're done then continue
                if ($i + 1 == $iterations) {
                    continue;
                }
                $pageNumber++;

                echo "Sending Request to investing.com\n";    
                $response = RequestBuilder::requestScreener($pageNumber, $exchangeNumber, $client);
                echo "Response Recieved from investing.com\n";

                $j = json_decode($response->getBody(), true); 
                $hits = $j['hits'];
                $total += count($hits);   
            }
            catch(Exception $e) {
                echo "\nEror recieveing response from investing.com: " . $e->getMessage() . "\n";
                echo $e->getMessage() . "\n";
            }
        }
        echo "\n\nTotal: " . $total . "\n\n";
    }    
    
    // Creates and writes a new entry for the company database
    static function writeToDB($extracted)
    {

        // TODO write null not 0

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