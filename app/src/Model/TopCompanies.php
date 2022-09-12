<?php

namespace Mosaic\Website\Model;

use SilverStripe\Control\Controller;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\PaginatedList;

class TopCompanies extends DataObject
{
    private static $db = [
        "Name" => "Varchar",
        "Year" => "Varchar",
    ];

    private static $has_many = [
        "Companies" => CompanyVersion::class
    ];

    private static $owns = [
        "Companies"
    ];

    private static $table_name = "TopCompanies";
    private static $singular_name = "Top Companies List";
    private static $plural_name = "Top Companies List's";

    private static $summary_fields = [
        "Name",
        "Year",
        "LastEdited",
    ];

    private static $searchable_fields = [
        "Name",
        "Year",
        "LastEdited",
    ];

    public function getPaginatedList()
    {
        $request = Controller::curr()->getRequest();

        $paginatedList = PaginatedList::create(
            $this->Companies()->Sort("Rank", "ASC"),
            $request
        )
            ->setPageLength($request->getVar("length") ?: 50)
            ->setPaginationGetVar('p');

        // if ($request->isAjax()) {
        //     return $this->customise($paginatedList)
        //         ->renderWith('Includes/PropertySold');
        // }

        return $paginatedList;
    }
}
