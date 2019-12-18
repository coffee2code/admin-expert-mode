<?php

defined( 'ABSPATH' ) or die();

class Admin_Expert_Mode_Test extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
	}


	//
	//
	// HELPER FUNCTIONS
	//
	//


	//
	//
	// TESTS
	//
	//


	public function test_class_exists() {
		$this->assertTrue( class_exists( 'c2c_AdminExpertMode' ) );
	}

	public function test_get_version() {
		$this->assertEquals( '2.5', c2c_AdminExpertMode::version() );
	}

	public function test_hooks_plugins_loaded() {
		$this->assertEquals( 10, has_action( 'plugins_loaded', array( 'c2c_AdminExpertMode', 'init' ) ) );
	}

}
