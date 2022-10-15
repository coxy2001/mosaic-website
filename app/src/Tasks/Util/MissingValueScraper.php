<?php

namespace Mosaic\Website\Tasks\Util;

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
    const RATIOS = '-ratios';
    const ROA = 'Return on Assets ';
    const ROA_TTM = 'Return on Assets TTM';
    const PE = 'P/E Ratio ';
    const PE_TTM = 'P/E Ratio TTM';
    const PERIOD = 'Period Ending:';

    // TODO: Check for ratios tab first! Also maybe use EPS if available instead of grabbing it

    // Uses the base url of a stock to get get the ROA
    public static function getROA($companyUrl, $client)
    {
        $xpathRatioPage = null;
        try {
            $xpathRatioPage = self::useRatioPage($companyUrl, $client);
            $ROA = self::extractROA($xpathRatioPage);
            return $ROA;
        } catch (Exception $e) {
            echo ("Could not get ROA from ratio page\n" . $e->getMessage() . "Now trying calculation\n");
        }
        // Get income statement html
        $response = RequestBuilder::requestStockPage($companyUrl, self::INCOME_STATEMENT, $client);
        // Transform html into a navigatable object
        $xpath = self::getXPath($response->getBody());

        if (!self::dateOk($xpath)) {
            throw new Exception("Stock is too old, aborting...");
        }
        // Extract the total income
        $totalIncome = self::getIncome($xpath);

        // Get balance sheet html
        $response = RequestBuilder::requestStockPage($companyUrl, self::BALANCE_SHEET, $client);
        // Transofrm html
        $xpath = self::getXPath($response->getBody());
        // Extract Assets
        $assets = self::getTotalAssets($xpath);

        // TODO: investigate floats
        // Return ROA
        return $totalIncome / $assets * 100;
    }


    // Use the base url of a stock to get the PE
    public static function getPE($companyUrl, $price, $client)
    {
        $xpathRatioPage = null;
        try {
            $xpathRatioPage = self::useRatioPage($companyUrl, $client);
            $PE = self::extractPE($xpathRatioPage);
            return $PE;
        } catch (Exception $e) {
            echo ("Could not get PE from ratio page " . $e->getMessage() . "Now trying calculation\n");
        }
        // Get income statement html
        $response = RequestBuilder::requestStockPage($companyUrl, self::INCOME_STATEMENT, $client);
        // transform html
        $xpath = self::getXPath($response->getBody());

        if (!self::dateOk($xpath)) {
            throw new Exception("Stock is too old, aborting...");
        }

        // Get total eps
        $totalEPS = self::getEPS($xpath);

        // Return PE
        return $price / $totalEPS;
    }

    public static function useRatioPage($companyUrl, $client)
    {
        $response  = RequestBuilder::requestStockPage($companyUrl, self::RATIOS, $client);
        $xpath = self::getXPath($response->getBody());
        return $xpath;
    }

    private static function extractROA($xpath)
    {
        $resultROA = $xpath->evaluate('//parent::span[text()="Return on Assets "]');
        $ROAvals = self::checkMultipleResults($resultROA, self::ROA_TTM);

        if (strcmp($ROAvals[0], self::ROA_TTM) == 0 && sizeof($ROAvals) > 1) {
            try {
                $percent = explode("%", $ROAvals[1]);
                $ROA = $percent[0];
                // $ROA =("-a");
                if (self::checkDouble($ROA)) {
                    return (float)$ROA;
                } else {
                    throw new Exception("Value was not a Double\n");
                }
            } catch (Exception $e) {
                throw new Exception("Could not find ROA in ratios tab\n" . $e->getMessage());
                // return null;
            }
        }
    }

    private static function extractPE($xpath)
    {
        $resultPE = $xpath->evaluate('//parent::span[text()="' . self::PE . '"]');
        $PEvals = self::checkMultipleResults($resultPE, self::PE_TTM);

        if (strcmp($PEvals[0], self::PE_TTM) == 0 && sizeof($PEvals) > 1) {
            try {
                $PE = ($PEvals[1]);
                // $PE = ("-a");
                if (self::checkDouble($PE)) {
                    return (float)$PE;
                } else {
                    throw new Exception("Value was not a Double\n");
                }
                return $PE;
            } catch (Exception $e) {
                throw new Exception("Could not find ROA in ratios tab\n" . $e->getMessage());
                // return null;
            }
        }
    }

    private static function dateOk($xpath)
    {
        $results = $xpath->evaluate('//parent::span[text()="' . self::PERIOD . '"]');
        $dates = self::checkMultipleResults($results, self::PERIOD);

        if (strcmp($dates[0], self::PERIOD) == 0 && sizeof($dates) > 1) {
            $firstDate = $dates[1];
            $firstYear =  substr(trim($firstDate), 0, 4);
            if (!self::checkInt($firstYear)) {
                throw new Exception("Year was not an int");
            }
            $firstYear = intval($firstYear);

            date_default_timezone_set('America/Los_Angeles');
            $currentYear = date('y');
            $currentYear = intval($currentYear);

            if ($currentYear > $firstYear + 1) {
                return false;
            }
            return true;
        }
    }

    private static function checkMultipleResults($results, $search)
    {
        foreach ($results as $result) {
            $ROAvals = self::getRowVals(self::getTR($result));
            if (strcmp($ROAvals[0], $search) == 0) {
                return $ROAvals;
            }
        }
        throw new Exception("No results found for " . $search . "\n");
    }

    // Extracts income from income statement
    // HTML in the form
    // Net Income valueQ1 valueQ2 valueQ3 valueQ4
    private static function getIncome($xpath)
    {
        // Search for the text Net Income
        $resultIncome = $xpath->evaluate('//parent::span[text()="' . self::NET_INCOME . '"]');
        // Extract the net income row
        $incomeVals = self::checkMultipleResults($resultIncome, self::NET_INCOME);

        // Add the values together if they are all present
        if (strcmp($incomeVals[0], self::NET_INCOME) == 0 && sizeof($incomeVals) == 5) {
            try {
                for ($i = 1; $i < count($incomeVals); $i++) {
                    if (!self::checkInt($incomeVals[$i])) {
                        throw new Exception("Value " . $i . " was not an int\n");
                    }
                }
                $totalIncome = intVal($incomeVals[1]) + intVal($incomeVals[2]) + intVal($incomeVals[3]) + intVal($incomeVals[4]);
            } catch (Exception $e) {
                throw new Exception("Not all income values were numbers\n" . $e->getMessage());
            }
        } else {
            throw new Exception("Could not find all income values");
        }
        // Return Total Income
        return $totalIncome;
    }

    // Extracts EPS from Income Statement
    private static function getEPS($xpath)
    {
        // Search for EPS in html object
        $resultEPS = $xpath->evaluate('//parent::span[text()="' . self::EPS . '"]');
        // Get EPS values
        $epsVals = self::checkMultipleResults($resultEPS, self::EPS);

        // Add all the values together if they are available
        if (strcmp($epsVals[0], self::EPS) == 0 && sizeof($epsVals) == 5) {
            // TODO: watch out for floats!
            try {
                for ($i = 1; $i < count($epsVals); $i++) {
                    if (!self::checkDouble($epsVals[$i])) {
                        throw new Exception("Value " . $i . " was not an int\n");
                    }
                }
                $totalEPS = floatval($epsVals[1]) + floatval($epsVals[2]) + floatval($epsVals[3]) + floatval($epsVals[4]);
            } catch (Exception $e) {
                throw new Exception("Not all EPS values were numbers\n" . $e->getMessage());
            }
        } else {
            throw new Exception("Could not find all EPS values\n");
        }
        if ($totalEPS == 0) {
            throw new Exception("No EPS values\n");
        }
        return $totalEPS;
    }

    // Extract assets from the balance sheet
    private static function getTotalAssets($xpath)
    {
        // Search through the object for Total Assets
        $resultAssets = $xpath->evaluate('//parent::span[text()="' . self::TOTAL_ASSETS . '"]');
        // Get the asset values
        $assetVals = self::checkMultipleResults($resultAssets, self::TOTAL_ASSETS);

        // Use the first value for assets if its available
        if (strcmp($assetVals[0], self::TOTAL_ASSETS) == 0 && count($assetVals) >= 2) {
            if (!self::checkInt($assetVals[1])) {
                throw new Exception("Assets not an integer\n");
            }
            $assets = intVal($assetVals[1]);
        } else {
            throw new Exception("Could not find total assets\n");
        }

        if ($assets == 0) {
            throw new Exception("Assets was zero\n");
        }
        return $assets;
    }

    private static function checkDouble($extractedNumber)
    {
        $val = (float)$extractedNumber;
        if (strcmp(strval($val), $extractedNumber) != 0) {
            return false;
        }
        return true;
    }

    private static function checkInt($extractedNumber)
    {
        $val = (int)$extractedNumber;
        if (strcmp(strval($val), $extractedNumber) != 0) {
            return false;
        }
        return true;
    }

    // Converts the html into an object that can be navigated
    private static function getXPath($html)
    {
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($html);
        $xpath = new DOMXPath($doc);
        libxml_clear_errors();
        return $xpath;
    }

    // Returns the row based on the result from a search string
    private static function getTR($result)
    {
        // Navigate to the table row
        $TD = $result->parentNode;
        $TR = $TD->parentNode;
        return $TR;
    }

    // Extracts the values out of a row
    private static function getRowVals($TR)
    {
        // Get the text content
        $textContent = $TR->textContent;
        // Remove gaps and split
        $textContent = trim($textContent);
        $textList = preg_split('/\r\n|\r|\n/', $textContent);
        // Return the data
        return $textList;
    }
}
