<?php

namespace Mosaic\Website\Model;

class CompanyVersion extends Company
{
    private static $db = [];

    private static $has_one = [
        "TopCompanies" => TopCompanies::class
    ];

    private static $table_name = 'CompanyVersion';
    private static $singular_name = 'Company Version';
    private static $plural_name = 'Company Versions';
}
