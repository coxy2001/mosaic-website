<?php
namespace Mosaic\Website\Cron;

use DOMDocument;
use DOMXPath;
use Exception;
class MissingValueScraper 
{
    const INCOME_STATEMENT = '-income-statement';
    const BALANCE_SHEET = '-balance-sheet';
    const EPS = 'Diluted EPS Excluding Extraordinary Items';
    const TOTAL_ASSETS = 'Total Assets';
    const NET_INCOME = 'Net Income';

    public static function getPE($companyUrl, $price) {
        $client = RequestBuilder::getClient();

        $response = $client->send(RequestBuilder::getStockPageRequest($companyUrl, self::INCOME_STATEMENT));
        $xpath = self::getXPath($response->getBody());
        $results = self::getIncomeAndEPS($xpath);

        // TODO: check these tags are present
        $totalEPS = $results['totalEPS'];
        
        return $price / $totalEPS;
    }

    public static function getROAandPE($companyUrl, $price) {
        $client = RequestBuilder::getClient();

        $response = $client->send(RequestBuilder::getStockPageRequest($companyUrl, self::INCOME_STATEMENT));
        $xpath = self::getXPath($response->getBody());
        $results = self::getIncomeAndEPS($xpath);
        // TODO: check these tags are present
        $totalIncome = $results['totalIncome'];
        $totalEPS = $results['totalEPS'];

        $response = $client->send(RequestBuilder::getStockPageRequest($companyUrl, self::BALANCE_SHEET));
        $xpath = self::getXPath($response->getBody());
        $assets = self::getTotalAssets($xpath);

        // TODO: investigate floats
        $ROA = $totalIncome / $assets * 100;
        $PE = $price / $totalEPS;

        return ['ROA'=>$ROA, 'PE'=>$PE];
    }

    function getIncomeAndEPS($xpath) {
        $resultIncome = $xpath->evaluate('//parent::span[text()="Net Income"]');
        var_dump($resultIncome);
        $resultEPS = $xpath->evaluate('//parent::span[text()="Diluted EPS Excluding Extraordinary Items"]');
        var_dump($resultEPS);

        $incomeVals = self::getRowVals(self::getTR($resultIncome));
        $epsVals = self::getRowVals(self::getTR($resultEPS));

        if($incomeVals[0] == self::NET_INCOME && sizeof($incomeVals) == 5) {
            // TODO: try catch to check for number vals 
            $totalIncome = intVal($incomeVals[1]) + intVal($incomeVals[2]) + intVal($incomeVals[3]) + intVal($incomeVals[4]);
        }
        if($epsVals[0] == self::EPS && sizeof($epsVals) == 5) {
            // TODO: try catch to check for number vals 
            // TODO: watch out for floats!
            $totalEPS = floatval($epsVals[1]) + floatval($epsVals[2]) + floatval($epsVals[3]) + floatval($epsVals[4]);
        }

        return ['totalIncome' => $totalIncome, 'totalEPS' => $totalEPS];
    }

    function getTotalAssets($xpath) {
        $resultAssets = $xpath->evaluate('//parent::span[text()="Total Assets"]');
        var_dump($resultAssets);
    
        
        $assetVals = self::getRowVals(self::getTR($resultAssets));
    
        if($assetVals[0] == self::TOTAL_ASSETS) {
            // TODO try catch to check for numbers vals
            $assets = intVal($assetVals[1]);
        }

        return $assets;
    }

    function getXPath($html) {
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        // echo $doc->saveHTML();
        $xpath = new DOMXPath($doc);
        // var_dump($xpath);
        libxml_clear_errors();
        return $xpath;
    }

    function getTR($result) {
        if (sizeof($result) == 1) {
            $result = $result[0];
        }
        $TD = $result->parentNode;
        $TR = $TD->parentNode;
        return $TR;
    }
    function getRowVals($TR) {
        $textContent = $TR->textContent;
        $textContent = trim($textContent);
        $textList = preg_split('/\r\n|\r|\n/', $textContent);
        return $textList;
    }
}