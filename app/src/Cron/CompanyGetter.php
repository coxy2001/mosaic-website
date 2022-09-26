<?php
namespace Mosaic\Website\Cron;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class CompanyGetter 
{
    const COUNTRY = 5;
    
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
        return $companies;
    }    
}