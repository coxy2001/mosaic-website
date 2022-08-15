<?php

namespace Mosaic\Website\Admin;

use SilverStripe\Admin\ModelAdmin;
use Mosaic\Website\Model\Company;

class CompanyAdmin extends ModelAdmin
{
    private static $managed_models = [
        Company::class
    ];

    private static $url_segment = 'company';
    private static $menu_title = 'Companies';
}
