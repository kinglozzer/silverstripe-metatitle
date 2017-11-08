<?php

namespace Kinglozzer\SilverStripeMetaTitle\Tests;

use Kinglozzer\SilverStripeMetaTitle\MetaTitleExtension;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;
use SilverStripe\i18n\Messages\MessageProvider;
use SilverStripe\i18n\Tests\i18nTestManifest;
use SilverStripe\i18n\i18n;

class MetaTitleExtensionTest extends SapphireTest
{
    use i18nTestManifest;

    /**
     * @var array
     */
    protected $extraDataObjects = [
        MetaTitleExtensionTest_DataObject::class
    ];

    protected function setUp()
    {
        parent::setUp();
        $this->setupManifest();
    }

    protected function tearDown()
    {
        $this->tearDownManifest();
        parent::tearDown();
    }

    public function testUpdateCMSFields()
    {
        // Add custom translation for testing
        $provider = Injector::inst()->get(MessageProvider::class);
        $provider->getTranslator()->addResource(
            'array',
            [ SiteTree::class.'.METATITLEHELP' => 'TRANS-EN Meta Title Help' ],
            'en'
        );

        $siteTree = new SiteTree();
        $fields = $siteTree->getCMSFields();
        $metaTitleField = $fields->dataFieldByName('MetaTitle');
        $this->assertNotNull($metaTitleField);
        $this->assertEquals('TRANS-EN Meta Title Help', $metaTitleField->RightTitle());
    }

    public function testUpdateFieldLabels()
    {
        // Add custom translation for testing
        $provider = Injector::inst()->get(MessageProvider::class);
        $provider->getTranslator()->addResource(
            'array',
            [ SiteTree::class.'.METATITLE' => 'TRANS-EN Meta Title' ],
            'en'
        );

        $siteTree = new SiteTree();
        $this->assertEquals('TRANS-EN Meta Title', $siteTree->fieldLabel('MetaTitle'));

        // Set different locale, clear field label cache
        i18n::set_locale('de_DE');

        // Add custom translation for testing
        $provider = Injector::inst()->get(MessageProvider::class);
        $provider->getTranslator()->addResource(
            'array',
            [ SiteTree::class.'.METATITLE' => 'TRANS-DE Meta Title' ],
            'de_DE'
        );
        $this->assertEquals('TRANS-DE Meta Title', $siteTree->fieldLabel('MetaTitle'));
    }

    public function testDataIntegrity()
    {
        $siteTree = new SiteTree();
        $siteTree->MetaTitle = 'Custom meta title';
        $siteTree->write();

        $siteTreeTest = SiteTree::get()->byID($siteTree->ID);
        $this->assertEquals('Custom meta title', $siteTreeTest->MetaTitle);

        $obj = new MetaTitleExtensionTest_DataObject();
        $obj->MetaTitle = 'Custom DO meta title';
        $obj->write();

        $objTest = MetaTitleExtensionTest_DataObject::get()->byID($obj->ID);
        $this->assertEquals('Custom DO meta title', $objTest->MetaTitle);
    }

    public function testMetaTags()
    {
        $format = '$MetaTitle - Site Name';
        Config::inst()->update(MetaTitleExtension::class, 'title_format', $format);

        $siteTree = new SiteTree();
        $siteTree->Title = 'Page title';

        $tags = $siteTree->MetaTags();
        $result = preg_match("/<title>(.+)<\/title>/i", $tags, $matches);
        $this->assertEquals(1, $result, 'Meta title tag not found');
        $this->assertEquals('Page title - Site Name', $matches[1], 'Meta title incorrect');

        $siteTree->MetaTitle = 'Page meta title';
        $tags = $siteTree->MetaTags();
        $result = preg_match("/<title>(.+)<\/title>/i", $tags, $matches);
        $this->assertEquals(1, $result, 'Meta title tag not found');
        $this->assertEquals('Page meta title - Site Name', $matches[1], 'Meta title incorrect');
    }
}

class MetaTitleExtensionTest_DataObject extends DataObject implements TestOnly
{
    private static $db = [
        'MetaDescription' => 'Text',
        'URLSegment' => 'Varchar(100)'
    ];

    private static $extensions = [
        MetaTitleExtension::class
    ];

    private static $table_name = 'MetaTitleExtensionTest_DataObject';
}
