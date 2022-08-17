<?php

namespace Mosaic\Website\Model;

use SilverStripe\ORM\DataObject;

class TopCompanies extends DataObject
{
    private static $db = [];

    private static $many_many = [
        "Companies" => CompanyVersion::class
    ];

    private static $table_name = "TopCompanies";
    private static $singular_name = "Top Companies List";
    private static $plural_name = "Top Companies List's";

    private static $summary_fields = [
        "LastEdited",
    ];

    private static $searchable_fields = [
        "LastEdited",
    ];
}
