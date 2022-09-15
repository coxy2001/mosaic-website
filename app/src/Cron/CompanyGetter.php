<?php
namespace Mosaic\Website\Cron;
use Exception;
use GuzzleHttp\Client;

class CompanyGetter 
{
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

        $companies = array();
        // TODO loop exchanges and pages feeding extractStocks new json ($j)
        array_push($companies, ListCompanyExtractor::extractStocks($j));
        return $companies;
    }    
}