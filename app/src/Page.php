<?php

namespace {

    use SilverStripe\CMS\Model\SiteTree;
    use Ticker\Model\Company;

    class Page extends SiteTree
    {
        private static $db = [];

        private static $has_one = [];

        public function getCompanies()
        {
            return Company::get();
        }
    }
}
