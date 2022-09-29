<?php
namespace Mosaic\Website\Cron;

use DOMDocument;
use DOMXPath;
use Exception;
class MissingValueScraper 
{
    // Constant values 
    const INCOME_STATEMENT = '-income-statement';
    const BALANCE_SHEET = '-balance-sheet';
    const EPS = 'Diluted EPS Excluding Extraordinary Items';
    const TOTAL_ASSETS = 'Total Assets';
    const NET_INCOME = 'Net Income';

    // TODO: Check for ratios tab first! Also maybe use EPS if available instead of grabbing it

    // Uses the base url of a stock to get get the ROA
    public static function getROA($companyUrl, $client) {
        // Get income statement html
        $response = RequestBuilder::requestStockPage($companyUrl, self::INCOME_STATEMENT, $client);
        // Transform html into a navigatable object
        $xpath = self::getXPath($response->getBody());
        // Extract the total income
        $totalIncome = self::getIncome($xpath);

        // Get balance sheet html
        $response = RequestBuilder::requestStockPage($companyUrl, self::BALANCE_SHEET, $client);
        // Transofrm html
        $xpath = self::getXPath($response->getBody());
        // Extract Assets
        $assets = self::getTotalAssets($xpath);

        // TODO: is this the best idea?
        if ($assets == 0) {
            return 0;
        }

        // TODO: investigate floats
        // Return ROA
        return $totalIncome / $assets * 100;
    }


    // Use the base url of a stock to get the PE
    public static function getPE($companyUrl, $price, $client) {
        // Get income statement html
        $response = RequestBuilder::requestStockPage($companyUrl, self::INCOME_STATEMENT, $client);
        // transform html
        $xpath = self::getXPath($response->getBody());
        // Get total eps
        $totalEPS = self::getEPS($xpath);
        
        // Return PE
        return $price / $totalEPS;
    }

    // Extracts income from income statement
    // HTML in the form
    // Net Income valueQ1 valueQ2 valueQ3 valueQ4
    static function getIncome($xpath) {
        // Search for the text Net Income
        $resultIncome = $xpath->evaluate('//parent::span[text()="Net Income"]');
        // Extract the net income row
        $incomeVals = self::getRowVals(self::getTR($resultIncome));

        // Add the values together if they are all present
        if(strcmp($incomeVals[0], self::NET_INCOME) == 0 && sizeof($incomeVals) == 5) {
            try { 
                $totalIncome = intVal($incomeVals[1]) + intVal($incomeVals[2]) + intVal($incomeVals[3]) + intVal($incomeVals[4]);
            }
            catch(Exception $e) {
                throw new Exception("Not all income values were numbers\n" . $e->getMessage());
            }
        }
        else {
            throw new Exception("Could not find all income values");
        }
        // Return Total Income
        return $totalIncome;
    }

    // Extracts EPS from Income Statement
    static function getEPS($xpath) {
        // Search for EPS in html object
        $resultEPS = $xpath->evaluate('//parent::span[text()="Diluted EPS Excluding Extraordinary Items"]');
        // Get EPS values
        $epsVals = self::getRowVals(self::getTR($resultEPS));

        // Add all the values together if they are available
        if(strcmp($epsVals[0], self::EPS) == 0 && sizeof($epsVals) == 5) {
            // TODO: watch out for floats!
            try {
                $totalEPS = floatval($epsVals[1]) + floatval($epsVals[2]) + floatval($epsVals[3]) + floatval($epsVals[4]);
            }
            catch(Exception $e) {
                throw new Exception("Not all EPS values were numbers\n" . $e->getMessage());
            }
        }
        else {
            throw new Exception("Could not find all EPS values");
        }
        return $totalEPS;
    }

    // Extract assets from the balance sheet
    static function getTotalAssets($xpath) {
        // Search through the object for Total Assets
        $resultAssets = $xpath->evaluate('//parent::span[text()="Total Assets"]');
        // Get the asset values
        $assetVals = self::getRowVals(self::getTR($resultAssets));
    
        // Use the first value for assets if its available
        if(strcmp($assetVals[0],self::TOTAL_ASSETS) == 0 && count($assetVals) >= 2) {
            try {
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

    // Converts the html into an object that can be navigated
    static function getXPath($html) {
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        $xpath = new DOMXPath($doc);
        libxml_clear_errors();
        return $xpath;
    }

    // Returns the row based on the result from a search string
    static function getTR($result) {
        // Just use the closest result to the search (first if multiple)
        if (sizeof($result) >= 1) {
            $result = $result[0];
        }
        else {
            throw new Exception('Page returned no results');
        }
        // Navigate to the table row
        $TD = $result->parentNode;
        $TR = $TD->parentNode;
        return $TR;
    }

    // Extracts the values out of a row
    static function getRowVals($TR) {
        // Get the text content
        $textContent = $TR->textContent;
        // Remove gaps and split
        $textContent = trim($textContent);
        $textList = preg_split('/\r\n|\r|\n/', $textContent);
        // Return the data
        return $textList;
    }
}