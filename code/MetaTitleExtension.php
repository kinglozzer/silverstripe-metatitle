<?php

namespace Kinglozzer\SilverStripeMetaTitle;

use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\View\ArrayData;
use SilverStripe\View\HTML;
use SilverStripe\View\SSViewer;

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
     * @config
     * @var string
     */
    private static $title_format = '$MetaTitle &raquo; $SiteConfig.Title';

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

    /**
     * Replace the <title> tag (if present) with the format provided in the title_format
     * config setting. Will fall back to 'Title' if 'MetaTitle' is empty
     *
     * @param string &$tags
     */
    public function MetaTags(&$tags)
    {
        // Only attempt to replace <title> tag if it has been included, as it won't
        // be included if called via $MetaTags(false)
        if (preg_match("/<title>(.+)<\/title>/i", $tags)) {
            $format = Config::inst()->get(static::class, 'title_format');

            $data = ArrayData::create([
                'MetaTitle' => $this->owner->MetaTitle ? $this->owner->obj('MetaTitle') : $this->owner->obj('Title')
            ]);

            $newTitleTag = HTML::createTag('title', [], SSViewer::execute_string($format, $data));
            $tags = preg_replace("/<title>(.+)<\/title>/i", $newTitleTag, $tags);
        }
    }
}
