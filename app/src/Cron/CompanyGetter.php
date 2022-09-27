<?php
namespace Mosaic\Website\Cron;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Mosaic\Website\Model\Company;

class CompanyGetter 
{   
    public static function getAll() {
        $pageNumber = 1;
        $exchangeNumber = 2;
        try {
            $client = RequestBuilder::getClient();

            $response = RequestBuilder::requestScreener($pageNumber, $exchangeNumber, $client);
            $j = json_decode($response->getBody(), true);

            $totalCount = $j['totalCount'];
            echo "count from total count: ";
            echo $totalCount . "\n";
            $hits = $j['hits'];

            $iterations = ceil($totalCount / count($hits));

            echo "Iterations for this exchange: " . ($iterations) . "\n";

        }
        catch(Exception $e) {
            echo "\nEror recieveing response from investing.com: " . $e->getMessage() . "\n";
            echo $e->getMessage() . "\n";
        }
        $total = count($hits);
        for ($i = 0; $i < $iterations; $i++){
            try {
                $companies = array();
                // // TODO loop exchanges and pages feeding extractStocks new json ($j)
                $companies = ListCompanyExtractor::extractStocks($j, $client);

                echo "\nSkipped " . (count($hits) - count($companies)) . " companies\n";

            }
            catch (Exception $e) {
                echo "\nEror extracting data from investing.com: " . $e->getMessage() . "\n";
            }
            try {
                $successCount = 0;
                // var_dump($allCompanies);
                foreach ($companies as $company) {
                    self::writeToDB($company);
                    $successCount++;
                }
                echo $successCount . " Succesful Writes\n";
            }
            catch (Exception $e) {
                echo "\nEror writing data to tempCompanies " . $e->getMessage() . "\n";
            }
            try {
                if ($i + 1 == $iterations) {
                    continue;
                }
                $pageNumber++;    
                $response = RequestBuilder::requestScreener($pageNumber, $exchangeNumber, $client);
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
    
    static function writeToDB($extracted)
    {
        // TODO write null not 0

        // $link = $extracted[self::LINK];
        // $company = Company::get()->filter("Link", $link)->first();
        // if($company == null) {
        //     $company = Company::create();
        // }

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