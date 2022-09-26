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

    // TODO: Check for ratios tab first!

    public static function getROA($companyUrl, $client) {
        $response = RequestBuilder::requestStockPage($companyUrl, self::INCOME_STATEMENT, $client);
        $xpath = self::getXPath($response->getBody());
        $totalIncome = self::getIncome($xpath);

        $response = RequestBuilder::requestStockPage($companyUrl, self::BALANCE_SHEET, $client);
        $xpath = self::getXPath($response->getBody());
        $assets = self::getTotalAssets($xpath);

        // TODO: investigate floats
        return $totalIncome / $assets * 100;
    }

    public static function getPE($companyUrl, $price, $client) {
        $response = RequestBuilder::requestStockPage($companyUrl, self::INCOME_STATEMENT, $client);
        $xpath = self::getXPath($response->getBody());
        $totalEPS = self::getEPS($xpath);
        
        return $price / $totalEPS;
    }

    static function getIncome($xpath) {
        $resultIncome = $xpath->evaluate('//parent::span[text()="Net Income"]');
        $incomeVals = self::getRowVals(self::getTR($resultIncome));

        if($incomeVals[0] == self::NET_INCOME && sizeof($incomeVals) == 5) {
            // TODO: try catch to check for number vals 
            $totalIncome = intVal($incomeVals[1]) + intVal($incomeVals[2]) + intVal($incomeVals[3]) + intVal($incomeVals[4]);
        }

        return $totalIncome;
    }

    static function getEPS($xpath) {
        $resultEPS = $xpath->evaluate('//parent::span[text()="Diluted EPS Excluding Extraordinary Items"]');
        $epsVals = self::getRowVals(self::getTR($resultEPS));

        if($epsVals[0] == self::EPS && sizeof($epsVals) == 5) {
            // TODO: try catch to check for number vals 
            // TODO: watch out for floats!
            $totalEPS = floatval($epsVals[1]) + floatval($epsVals[2]) + floatval($epsVals[3]) + floatval($epsVals[4]);
        }
        return $totalEPS;
    }

    static function getTotalAssets($xpath) {
        $resultAssets = $xpath->evaluate('//parent::span[text()="Total Assets"]');
    
        
        $assetVals = self::getRowVals(self::getTR($resultAssets));
    
        if(strcmp($assetVals[0],self::TOTAL_ASSETS) && count($assetVals) >= 2) {
            try {
                // TODO try catch to check for numbers vals
                $assets = intVal($assetVals[1]);
            }
            catch(Exception $e){
                throw new Exception('Assets not an integer');
            }
        }
        else {
            throw new Exception('Could not find total assets');
        }

        return $assets;
    }

    static function getXPath($html) {
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        $xpath = new DOMXPath($doc);
        libxml_clear_errors();
        return $xpath;
    }

    static function getTR($result) {
        if (sizeof($result) == 1) {
            $result = $result[0];
        }
        else {
            throw new Exception('Page returned no results');
        }
        $TD = $result->parentNode;
        $TR = $TD->parentNode;
        return $TR;
    }
    static function getRowVals($TR) {
        $textContent = $TR->textContent;
        $textContent = trim($textContent);
        $textList = preg_split('/\r\n|\r|\n/', $textContent);
        return $textList;
    }
}