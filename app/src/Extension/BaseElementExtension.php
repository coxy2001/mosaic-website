<?php

namespace Mosaic\Website\Extension;

use SilverStripe\ORM\DataExtension;

class BaseElementExtension extends DataExtension
{
    public function getElementClass()
    {
        return $this->owner->config()->get("element_class");
    }
}
