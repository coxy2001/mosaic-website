<?php

namespace Mosaic\Website\Model;

class TemporaryCompany extends Company
{
    private static $db = self::DB_FIELDS;

    private static $table_name = 'TemporaryCompany';
    private static $singular_name = 'Temporary Company';
    private static $plural_name = 'Temporary Companies';
}
