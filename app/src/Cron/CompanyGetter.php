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


            $response = $client->send(RequestBuilder::getScreenerRequest($pageNumber, $exchangeNumber));

            $j = json_decode($response->getBody(), true);
            var_dump($j);
            // $totalCount = $j['totalCount'];
            // echo "count from total count: ";
            // echo $totalCount;
            // $hits = $j['hits'];
            // echo "\ncount of hits list: ";
            // echo count($hits);
            // echo "\n";

        }
        catch(Exception $e) {
            // echo "\nEror recieveing response from investing.com: " . $e->getMessage() + "\n";
            var_dump($j);
        }


        $companies = array();
        // // TODO loop exchanges and pages feeding extractStocks new json ($j)
        // array_push($companies, ListCompanyExtractor::extractStocks($j));

        // echo "\nSkipped " . (count($hits) - count($companies) . "companies\n");
        return $companies;
    }    
}