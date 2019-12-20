<?php

defined( 'ABSPATH' ) or die();

class Admin_Expert_Mode_Test extends WP_UnitTestCase {

	protected static $admin_options_name = 'c2c_admin_expert_mode';
	protected static $transient = 'aem_activated';
	protected $user_id = 0;

	public function setUp() {
		parent::setUp();

		$this->user_id = $this->factory->user->create( array( 'role' => 'editor' ) );
		wp_set_current_user( $this->user_id );
	}

	public function tearDown() {
		parent::tearDown();

		delete_transient( self::$transient );
		delete_user_option( $this->user_id, self::$admin_options_name );
		$this->user_id = 0;
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

	public function test_plugin_activated() {
		$this->assertFalse( get_transient( self::$transient ) );

		c2c_AdminExpertMode::plugin_activated();

		$this->assertEquals( 'show', get_transient( self::$transient ) );
	}

	/*
	 * is_admin_expert_mode_active()
	 */

	public function test_default_value_for_is_admin_expert_mode_active() {
		$this->assertFalse( c2c_AdminExpertMode::is_admin_expert_mode_active() );
	}

	public function test_user_option_enables_is_admin_expert_mode_active() {
		update_user_option( $this->user_id, self::$admin_options_name, true );

		$this->assertTrue( c2c_AdminExpertMode::is_admin_expert_mode_active() );
	}

}
