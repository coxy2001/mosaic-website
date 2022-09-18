<?php

namespace Mosaic\Website\Extension;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\ORM\DataExtension;

class SiteConfigExtension extends DataExtension
{
    private static $db = [
        "Disclaimer" => "HTMLText",
    ];

    private static $has_one = [
        "Logo" => Image::class,
        "FooterLogo" => Image::class,
        "DataLogo" => Image::class,
    ];

    private static $owns = [
        "Logo",
        "FooterLogo",
        "DataLogo",
    ];

    public function updateCMSFields($fields)
    {
        $fields->addFieldsToTab(
            "Root.Main",
            [
                UploadField::create("Logo", "Main Logo"),
                UploadField::create("FooterLogo", "Footer Logo")->setDescription("Optional, will use Main Logo if empty"),
                UploadField::create("DataLogo", "Data Source Logo"),
                HTMLEditorField::create("Disclaimer"),
            ]
        );
    }
}
