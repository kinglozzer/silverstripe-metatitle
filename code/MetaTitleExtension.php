<?php

namespace Kinglozzer\SilverStripeMetaTitle;

use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;

class MetaTitleExtension extends DataExtension
{
    /**
     * @config
     * @var array
     */
    private static $db = [
        'MetaTitle' => 'Varchar(255)'
    ];

    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        $metaFieldTitle = TextField::create('MetaTitle', $this->owner->fieldLabel('MetaTitle'))
            ->setRightTitle(_t(
                'SiteTree.METATITLEHELP',
                'Shown at the top of the browser window and used as the "linked text" by search engines.'
            ))
            ->addExtraClass('help');

        $fields->insertBefore($metaFieldTitle, 'MetaDescription');
    }

    /**
     * @param array &$labels
     */
    public function updateFieldLabels(&$labels)
    {
        $labels['MetaTitle'] = _t('SiteTree.METATITLE', 'Title');
    }
}
