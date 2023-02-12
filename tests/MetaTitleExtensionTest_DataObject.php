<?php

namespace Kinglozzer\SilverStripeMetaTitle\Tests;

use Kinglozzer\SilverStripeMetaTitle\MetaTitleExtension;
use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;

class MetaTitleExtensionTest_DataObject extends DataObject implements TestOnly
{
    private static $db = [
        'MetaDescription' => 'Text',
        'URLSegment' => 'Varchar(100)'
    ];

    private static $extensions = [
        MetaTitleExtension::class
    ];

    private static $table_name = 'metatitleextensiontest_dataobject';
}
