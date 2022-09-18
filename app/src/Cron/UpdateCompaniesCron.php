<?php

namespace Mosaic\Website\Cron;

use Exception;
use Mosaic\Website\Model\Company;
use Mosaic\Website\Model\CompanyVersion;
use SilverStripe\CronTask\Interfaces\CronTask;

class UpdateCompaniesCron implements CronTask
{
    // Constants for obtaining values from Investing.com 
    const NAME = 'name_trans';
    const STOCK_SYMBOL = 'stock_symbol';
    const STOCK_EXCHANGE = 'exchange_trans';
    const SECTOR = 'sector_trans';
    const ROA = 'aroapct';
    const PE = 'eq_pe_ratio';
    const PRICE = 'last';
    const MARKET_CAP = 'eq_market_cap';
    const FREE_CASH_FLOW = 'a1fcf';
    const EARNINGS_YIELD = 'yield';
    const VIEWDATA = 'viewData';
    const LINK = 'link';
    const FLAG = 'flag';
    const CUSTOM_CALC = 'custom_calculation';

    const FEATURES = [self::NAME, self::STOCK_SYMBOL, self::STOCK_EXCHANGE, self::SECTOR, self::PE, self::ROA, self::PRICE, self::MARKET_CAP, self::FREE_CASH_FLOW, self::EARNINGS_YIELD, self::VIEWDATA, self::CUSTOM_CALC];

    const BASE_INVESTING_URL = 'https://www.investing.com/';
    const SCREENER_PATH = 'stock-screener/Service/SearchStocks';
    const TIMEOUT = 15;
    const COUNTRY = 5;
    /**
     * Run this task every 5 minutes
     *
     * @return string
     */
    public function getSchedule()
    {
        return "* * * * *";
    }

    /**
     * Update company data
     *
     * @return void
     */
    public function process()
    {
        echo "Update Companies Task Running \n";
        $allCompanies = CompanyGetter::getAll();
        $successCount = 0;
        // var_dump($allCompanies);
        foreach ($allCompanies as $company) {
            $this->writeToDB($company);
            $successCount++;
        }
        echo $successCount . " Succesful Writes\n";
    }

    // TODO: put this code in model class? Find better way to write all at once?
    function writeToDB($extracted)
    {
        // TODO write null not 0
        $company = Company::create();
        $company->update([
            "Ticker" => $extracted[self::STOCK_SYMBOL],
            "Name" => $extracted[self::NAME],
            "Description" => ($extracted[self::STOCK_EXCHANGE] . $extracted[self::FLAG]),
            "Rank" => 0,
            "Sector" => $extracted[self::SECTOR],
            "MarketCap" => $extracted[self::MARKET_CAP],
            "Price" => $extracted[self::PRICE],
            "ROC" => 0,
            "ROA" => $extracted[self::ROA],
            "PE" => $extracted[self::PE],
            "EarningsYield" => $extracted[self::EARNINGS_YIELD],
            "Link" => $extracted[self::LINK],
            "CustomCalculation" => $extracted[self::CUSTOM_CALC],
        ])->write();
    }

    function addCompanyToList($company, $listID)
    {
        $version = CompanyVersion::create();
        $values = $company->toMap();
        $values["TopCompaniesID"] = $listID;
        unset($values["ID"]);
        $version->update($values);
        return $version->write();
    }
}
