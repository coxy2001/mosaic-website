<?php

namespace Mosaic\Website\Cron;

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
        echo "\n Update Companies Task Running \n";
        $allCompanies = CompanyGetter::getAll();
        foreach ($allCompanies as $company) {
            self::writeToDB($company);
        }
    }
    // TODO: put this code in model class? Find better way to write all at once?
    function writeToDB($extracted) {
        // TODO: write null not 0
        $company = Company::create();
        // TODO: loop using tomap for array keys
        $company->Ticker = $extracted[self::STOCK_SYMBOL];
        $company->Name = $extracted[self::NAME];
        $company->Description = ($extracted[self::STOCK_EXCHANGE] . $extracted[self::FLAG]);
        $company->Rank = 0;
        $company->Sector = $extracted[self::SECTOR];
        $company->MarketCap = $extracted[self::MARKET_CAP];
        $company->Price = $extracted[self::PRICE];
        $company->ROC = 0;
        $company->ROA = $extracted[self::ROA];
        $company->PE = $extracted[self::PE];
        $company->EarningsYield = $extracted[self::EARNINGS_YIELD];
        $company->Link = $extracted[self::LINK];
        $company->CustomCalculation = $extracted[self::CUSTOM_CALC];
        $company->write();
        echo "Successful db write ";
    }
    
    function addCompanyToList($company, $listID) {
        $version = CompanyVersion::create();
        $values = $company->toMap();
        $values["TopCompaniesID"] = $listID;
        unset($values["ID"]);
        $version->update($values);
        return $version->write();
    }
}