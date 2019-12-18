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


	public static function get_default_hooks() {
		return array(
			array( 'action', 'admin_init',               'register_styles',           10 ),
			array( 'action', 'admin_notices',            'display_activation_notice', 10 ),
			array( 'action', 'personal_options',         'show_option',               10 ),
			array( 'action', 'personal_options_update',  'maybe_save_options',        10 ),
			array( 'action', 'edit_user_profile_update', 'maybe_save_options',        10 ),
			array( 'action', 'admin_enqueue_scripts',    'enqueue_admin_css',         10 ),
		);
	}



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

	/**
	 * @dataProvider get_default_hooks
	 */
	public function test_default_hooks( $hook_type, $hook, $function, $priority ) {
		$prio = $hook_type === 'action' ?
			has_action( $hook, array( 'c2c_AdminExpertMode', $function ) ) :
			has_filter( $hook, array( 'c2c_AdminExpertMode', $function ) );
		$this->assertNotFalse( $prio );
		if ( $priority ) {
			$this->assertEquals( $priority, $prio );
		}
	}

}
