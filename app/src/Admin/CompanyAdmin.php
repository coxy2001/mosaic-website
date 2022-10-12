<?php

namespace Mosaic\Website\Admin;

use SilverStripe\Admin\ModelAdmin;
use Mosaic\Website\Model\Company;
use Mosaic\Website\Model\CompanyList;

class CompanyAdmin extends ModelAdmin
{
    private static $managed_models = [
        Company::class,
        CompanyList::class,
    ];

    private static $url_segment = 'company';
    private static $menu_title = 'Companies';
}
