<?php

namespace Ticker\Admin;

use SilverStripe\Admin\ModelAdmin;
use Ticker\Model\Company;

class CompanyAdmin extends ModelAdmin
{
    private static $managed_models = [
        Company::class
    ];

    private static $url_segment = 'company';
    private static $menu_title = 'Companies';
}
