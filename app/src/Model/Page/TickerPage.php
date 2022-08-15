<?php

namespace Mosaic\Website\Model\Page;

use Mosaic\Website\Controller\TickerPageController;
use Mosaic\Website\Model\Company;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\GridField\GridField;

class TickerPage extends \Page
{
    private static $page_name = "TickerPage";
    private static $controller_name = TickerPageController::class;
    private static $description = "Displays tickers";

    public function getCompanies()
    {
        return Company::get();
    }

    public function getDataList()
    {
        $fields = FieldList::create(($this->getGridField()));
        $form = Form::create();
        $form->setFields($fields);

        return $form;
    }

    public function getGridField(): GridField
    {
        $field = GridField::create(
            "Ticker List",
            "Ticker List",
            $this->getCompanies()
        );

        return $field;
    }
}
