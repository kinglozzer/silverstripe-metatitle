<?php

class MetaTitleExtension extends DataExtension
{

    private static $db = array(
        'MetaTitle' => 'Varchar(255)'
    );

    public function updateCMSFields(FieldList $fields)
    {
        $metaFieldTitle = new TextField("MetaTitle", $this->owner->fieldLabel('MetaTitle'));
        $metaFieldTitle->setRightTitle(
                _t(
                    'SiteTree.METATITLEHELP',
                    'Shown at the top of the browser window and used as the "linked text" by search engines.'
                )
            )->addExtraClass('help');

        $fields->insertBefore($metaFieldTitle, Config::inst()->get('MetaTitleExtension', 'InsertBefore'));

        return $fields;
    }

    public function updateFieldLabels(&$labels)
    {
        $labels['MetaTitle'] = _t('SiteTree.METATITLE', "Title");
    }
}
