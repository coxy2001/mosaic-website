<?php

namespace Mosiac\Website\Admin;

use SilverStripe\Admin\ModelAdmin;
use Mosiac\Website\Model\Company;

class CompanyAdmin extends ModelAdmin
{
    private static $managed_models = [
        Company::class
    ];

    private static $url_segment = 'company';
    private static $menu_title = 'Companies';
}
