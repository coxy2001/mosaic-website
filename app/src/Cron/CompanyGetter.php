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
        $exchangeNumber = 50;
        try {
            $client = RequestBuilder::getClient();

            $response = RequestBuilder::requestScreener($pageNumber, $exchangeNumber, $client);
            $j = json_decode($response->getBody(), true);

            $totalCount = $j['totalCount'];
            echo "count from total count: ";
            echo $totalCount . "\n";
            $hits = $j['hits'];

        }
        catch(Exception $e) {
            echo "\nEror recieveing response from investing.com: " . $e->getMessage() . "\n";
            echo $e->getMessage() . "\n";
        }

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
        return $companies;
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
            "Description" => ($extracted[RequestBuilder::STOCK_EXCHANGE] . $extracted[RequestBuilder::FLAG]),
            "Rank" => 0,
            "Sector" => $extracted[RequestBuilder::SECTOR],
            "MarketCap" => $extracted[RequestBuilder::MARKET_CAP],
            "Price" => $extracted[RequestBuilder::PRICE],
            "ROC" => 0,
            "ROA" => $extracted[RequestBuilder::ROA],
            "PE" => $extracted[RequestBuilder::PE],
            "AbsoluteValuePE" => abs($extracted[RequestBuilder::PE]),
            "EarningsYield" => $extracted[RequestBuilder::EARNINGS_YIELD],
            "Link" => $extracted[RequestBuilder::LINK],
            "CustomCalculation" => $extracted[RequestBuilder::CUSTOM_CALC],
        ])->write();
    }
}