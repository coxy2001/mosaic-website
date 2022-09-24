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


            // $response = $client->send(RequestBuilder::getScreenerRequest($pageNumber, $exchangeNumber));
            // $response = $client->send(new Request('GET', 'https://www.thunderclient.com/welcome'));
            // $response = $client->send(new Request())

            $response = RequestBuilder::requestScreener($pageNumber, $exchangeNumber, $client);
            $j = json_decode($response->getBody(), true);
            // var_dump($j);
            

            $totalCount = $j['totalCount'];
            echo "count from total count: ";
            echo $totalCount . "\n";
            $hits = $j['hits'];
            echo "count of hits list: ";
            echo count($hits) . "\n";

            for($i = 0; $i < 50; $i++) {
                echo $hits[$i]['stock_symbol'] . "\n";
            }

            echo $j['isEU'] . "\n";
            echo $j['pageNumber'] . "\n";
            echo $j['totalCount'] . "\n";

        }
        catch(Exception $e) {
            echo "\nEror recieveing response from investing.com: " . $e->getMessage() . "\n";
            // var_dump($j);
            echo $e->getMessage() . "\n";
            // echo $e->getTraceAsString() . "\n";
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