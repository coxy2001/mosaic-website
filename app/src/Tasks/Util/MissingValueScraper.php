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

    // Uses the base url of a stock to get get the ROA
    // If the ratio page exists use that to get the data
    // If ratio page exists but contained no data abort
    // If ratio page does not exist try to calculate ROA manually
    public static function getROA($companyUrl, $client)
    {
        $xpathRatioPage = null;
        // Try ratios page
        try {
            $xpathRatioPage = self::useRatioPage($companyUrl, $client);
            $ROA = self::extractROA($xpathRatioPage);
            return $ROA;
        } catch (EmptyDataException $e) {
            throw new Exception("Ratios page existed but had no usable data: " . $e->getMessage() . "aborting now...\n");
        } catch (Exception $e) {
            echo ("Could not get ROA from ratio page\n" . $e->getMessage() . "Now trying calculation\n");
        }
        // Get income statement html
        $response = RequestBuilder::requestStockPage($companyUrl, self::INCOME_STATEMENT, $client);
        // Transform html into a navigatable object
        $xpath = self::getXPath($response->getBody());

        // Abort if stock is too old
        if (!self::dateOk($xpath)) {
            throw new Exception("Stock is too old, aborting...\n");
        }
        // Extract the total income
        $totalIncome = self::getIncome($xpath);

        // Get balance sheet html
        $response = RequestBuilder::requestStockPage($companyUrl, self::BALANCE_SHEET, $client);
        // Transofrm html
        $xpath = self::getXPath($response->getBody());
        // Extract Assets
        $assets = self::getTotalAssets($xpath);

        print("Done\n\n");

        // Return ROA
        return $totalIncome / $assets * 100;
    }


    // Use the base url of a stock to get the PE
    // If the ratio page exists use that to get the data
    // If ratio page exists but contained no data abort
    // If ratio page does not exist try to calculate PE manually
    public static function getPE($companyUrl, $price, $client)
    {
        $xpathRatioPage = null;
        // Try ratios page
        try {
            $xpathRatioPage = self::useRatioPage($companyUrl, $client);
            $PE = self::extractPE($xpathRatioPage);
            return $PE;
        } catch (EmptyDataException $e) {
            throw new Exception("Ratios page existed but had no usable data: " . $e->getMessage() . "aborting now...\n");
        } catch (Exception $e) {
            echo ("Could not get PE from ratio page " . $e->getMessage() . "Now trying calculation\n");
        }
        // Get income statement html
        $response = RequestBuilder::requestStockPage($companyUrl, self::INCOME_STATEMENT, $client);
        // transform html
        $xpath = self::getXPath($response->getBody());

        // Abort if stock is too old
        if (!self::dateOk($xpath)) {
            throw new Exception("Stock is too old, aborting...\n");
        }

        // Get total eps
        $totalEPS = self::getEPS($xpath);

        print("Done\n\n");

        // Return PE
        return $price / $totalEPS;
    }

    // Function for getting the ratio page
    // Converts the HTML and returns an XML object (xpath)
    public static function useRatioPage($companyUrl, $client)
    {
        $response  = RequestBuilder::requestStockPage($companyUrl, self::RATIOS, $client);
        $xpath = self::getXPath($response->getBody());
        return $xpath;
    }

    // Searches for ROA in the xpath and checks its an appropriate value
    private static function extractROA($xpath)
    {
        $resultROA = $xpath->evaluate('//parent::span[text()="Return on Assets "]');
        $ROAvals = self::checkMultipleResults($resultROA, self::ROA_TTM);

        if (strcmp($ROAvals[0], self::ROA_TTM) == 0 && sizeof($ROAvals) > 1) {
            $percent = explode("%", $ROAvals[1]);
            $ROA = $percent[0];
            if (self::checkFloat($ROA)) {
                return (float)$ROA;
            } else {
                throw new EmptyDataException("ROA was not a Double\n");
            }
        }
        else {
            throw new Exception("Could not find ROA in ratios tab\n");
        }
    }

    // Searches for PE in the xpath and checks its an appropriate value
    private static function extractPE($xpath)
    {
        $resultPE = $xpath->evaluate('//parent::span[text()="' . self::PE . '"]');
        $PEvals = self::checkMultipleResults($resultPE, self::PE_TTM);

        if (strcmp($PEvals[0], self::PE_TTM) == 0 && sizeof($PEvals) > 1) {
            $PE = ($PEvals[1]);
            if (self::checkFloat($PE)) {
                return (float)$PE;
            } else {
                throw new EmptyDataException("PE was not a Double\n");
            }
        }
        else {
            throw new Exception("Could not find ROA in ratios tab\n");
        }
    }

    // Extracts the date on the page using the xpath object
    // Returns true if the date is within 2 years of the current date
    // Returns false otherwise
    private static function dateOk($xpath)
    {
        echo "----- CHECKING THE DATE -----\n";
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
            $currentYear = date('Y');
            $currentYear = intval($currentYear);
            echo ("Year: " . $firstYear . "\n" . "Current Year: " . $currentYear . "\n");
            if ($currentYear > $firstYear + 1) {
                return false;
            }
            return true;
        }
    }

    // xpath always returns a list of results
    // this is used to confirm which item in the list is the one we're looking for
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
                $totalIncome = 0;
                $output = "";
                for ($i = 1; $i < count($incomeVals); $i++) {
                    if (self::checkFloat($incomeVals[$i])) {
                        $totalIncome += floatval($incomeVals[$i]);
                    }
                    $output = $output . $incomeVals[$i] . ", ";
                }
                if ($totalIncome == 0) {
                    throw new Exception("Income was zero\nNet Income vals: " . $output . "\n");
                }
            } catch (Exception $e) {
                throw new Exception("No value for income found\n" . $e->getMessage());
            }
        } else {
            throw new Exception("Could not find all income values\n");
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
            try {
                $totalEPS = 0;
                $output = "";
                for ($i = 1; $i < count($epsVals); $i++) {
                    if (self::checkFloat($epsVals[$i])) {
                        $totalEPS = floatval($epsVals[$i]);
                    }
                    $output = $output . $epsVals[$i] . ", ";
                }
                if ($totalEPS == 0) {
                    throw new Exception("EPS is 0.\nEPS vals: " . $output . "\n");
                }
            } catch (Exception $e) {
                throw new Exception("No EPS values were numbers\n" . $e->getMessage());
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
            $assets = 0;
            try{
                $output = "";
                // Get first available value for assets
                for ($i = 1; $i < count($assetVals); $i++) {
                    if (self::checkFloat($assetVals[$i])) {
                        $assets = floatval($assetVals[$i]);
                        break;
                    }
                    $output = $output . $assetVals[$i] . ", ";
                }
                if ($assets == 0) {
                    throw new Exception("Assets was 0 was zero\nAsset vals: " .  $output . "\n");
                }
            } catch (Exception $e) {
                throw new Exception("No value for assets found\n" . $e->getMessage());
            }
        } else {
            throw new Exception("Could not find total assets\n");
        }
        return $assets;
    }

    // Functions used to check the data type of numbers

    private static function checkFloat($extractedNumber)
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
