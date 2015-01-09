<?php

class MetaTitleExtensionTest extends SapphireTest {
	
	protected $extraDataObjects = array(
		'MetaTitleExtensionTest_DataObject'
	);

	public function setUp() {
		parent::setUp();

		$this->originalLocale = i18n::get_locale();
	}
	
	public function tearDown() {
		i18n::set_locale($this->originalLocale);
		DataObject::reset();
		
		parent::tearDown();
	}

	public function testUpdateCMSFields() {
		// Add custom translation for testing
		i18n::get_translator('core')->getAdapter()->addTranslation(array(
			'SiteTree.METATITLEHELP' => 'TRANS-EN Meta Title Help'
		), 'en');

		$siteTree = new SiteTree();
		$fields = $siteTree->getCMSFields();
		$metaTitleField = $fields->dataFieldByName('MetaTitle');
		$this->assertNotNull($metaTitleField);
		$this->assertEquals('TRANS-EN Meta Title Help', $metaTitleField->RightTitle());
	}

	public function testUpdateFieldLabels() {
		// Add custom translation for testing
		i18n::get_translator('core')->getAdapter()->addTranslation(array(
			'SiteTree.METATITLE' => 'TRANS-EN Meta Title'
		), 'en');

		$siteTree = new SiteTree();
		$labels = $siteTree->fieldLabels();

		$this->assertArrayHasKey('MetaTitle', $labels);
		$this->assertEquals('TRANS-EN Meta Title', $labels['MetaTitle']);

		// Set different locale, clear field label cache
		i18n::set_locale('de_DE');
		DataObject::reset();

		// Add custom translation for testing
		i18n::get_translator('core')->getAdapter()->addTranslation(array(
			'SiteTree.METATITLE' => 'TRANS-DE Meta Title'
		), 'de_DE');

		$labels = $siteTree->fieldLabels();
		$this->assertEquals('TRANS-DE Meta Title', $labels['MetaTitle']);
	}

	public function testDataIntegrity() {
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

	public function testFieldPosition() {
		Config::nest();
		Config::inst()->update('MetaTitleExtension', 'InsertBefore', 'MetaDescription');

		$testObject = new MetaTitleExtensionTest_DataObject();
		$fields = $testObject->getCMSFields();
		$descriptionPosition = $fields->fieldPosition('MetaDescription');
		$this->assertEquals($descriptionPosition - 1, $fields->fieldPosition('MetaTitle'));

		Config::inst()->update('MetaTitleExtension', 'InsertBefore', 'URLSegment');
		$fields = $testObject->getCMSFields();
		$urlSegmentPosition = $fields->fieldPosition('URLSegment');
		$this->assertEquals($urlSegmentPosition - 1, $fields->fieldPosition('MetaTitle'));

		Config::unnest();
	}

}

class MetaTitleExtensionTest_DataObject extends DataObject implements TestOnly {

	private static $db = array(
		'MetaDescription' => 'Text',
		'URLSegment' => 'Varchar(100)'
	);

	private static $extensions = array(
		'MetaTitleExtension'
	);

}