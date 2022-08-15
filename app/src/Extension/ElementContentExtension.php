<?php

namespace Mosaic\Website\Extension;

use SilverStripe\ORM\DataExtension;

class ElementContentExtension extends DataExtension
{
    private static $element_class = "content";

    // Style variants example ["classname" => "Display Name"]
    // CSS methodology: http://getbem.com/introduction/
    private static $styles = [
        "content--style-1" => "Style 1",
        "content--style-2" => "Style 2",
    ];
}
