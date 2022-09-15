<?php
namespace Mosaic\Website\Cron;
use Exception;
use GuzzleHttp\Client;

class CompanyGetter 
{
    const BASE_INVESTING_URL = 'https://www.investing.com/';
    const SCREENER_PATH = 'stock-screener/Service/SearchStocks';
    const INCOME_STATEMENT = '-income-statement';
    const BALANCE_SHEET = '-balance-sheet';
    const COUNTRY = 5;
    
    public static function getAll() {
        $pageNumber = 1;
        $exchangeNumber = 50;

        $client = RequestBuilder::getClient();


        $response = $client->send(RequestBuilder::getScreenerRequest($pageNumber, $exchangeNumber));

        $j = json_decode($response->getBody(), true);
        $totalCount = $j['totalCount'];
        echo "count from total count: ";
        echo $totalCount;
        $hits = $j['hits'];
        echo "\ncount of hits list: ";
        echo count($hits);
        echo "\n";

        $i = 1;
        foreach($hits as $c) {
            extractAndSave($c);
            echo $i . "\n";
            $i++;
        }
    }    
}