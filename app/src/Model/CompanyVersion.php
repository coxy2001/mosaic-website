<?php

namespace Mosaic\Website\Model;

use SilverStripe\ORM\DataObject;

class CompanyVersion extends DataObject
{
    private static $db = Company::DB_FIELDS;

    private static $has_one = [
        "CompanyList" => CompanyList::class
    ];

    private static $table_name = 'CompanyVersion';
    private static $singular_name = 'Company Version';
    private static $plural_name = 'Company Versions';
}
