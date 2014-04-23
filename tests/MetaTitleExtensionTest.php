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

}

class MetaTitleExtensionTest_DataObject extends DataObject implements TestOnly {

	private static $extensions = array(
		'MetaTitleExtension'
	);

}