<?php

defined( 'ABSPATH' ) or die();

class Admin_Expert_Mode_Test extends WP_UnitTestCase {

	protected static $admin_options_name = 'c2c_admin_expert_mode';
	protected static $disable_query_key = 'disable-admin-expert-mode';
	protected static $field_name = 'admin_expert_mode';
	protected static $style_handle = 'c2c_AdminExpertMode_admin';
	protected static $transient = 'aem_activated';
	protected $user_id = 0;

	public function setUp() {
		parent::setUp();

		$this->user_id = $this->factory->user->create( array( 'role' => 'editor' ) );
		wp_set_current_user( $this->user_id );
	}

	public function tearDown() {
		parent::tearDown();

		c2c_AdminExpertMode::reset();

		delete_transient( self::$transient );
		delete_user_option( $this->user_id, self::$admin_options_name );
		$this->user_id = 0;
		unset( $_GET[ self::$disable_query_key ] );
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
		$this->assertEquals( '2.9', c2c_AdminExpertMode::version() );
	}

	public function test_hooks_plugins_loaded() {
		$this->assertEquals( 10, has_action( 'plugins_loaded', array( 'c2c_AdminExpertMode', 'init' ) ) );
	}

	/**
	 * @dataProvider get_default_hooks
	 */
	public function test_default_hooks( $hook_type, $hook, $function, $priority, $class_method = true ) {
		$callback = $class_method ? array( 'c2c_AdminExpertMode', $function ) : $function;

		$prio = $hook_type === 'action' ?
			has_action( $hook, $callback ) :
			has_filter( $hook, $callback );

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
	 * reset()
	 */

	public function test_reset() {
		// This assigns a value to a user which then gets cached.
		update_user_option( $this->user_id, self::$admin_options_name, true );
		$options = c2c_AdminExpertMode::get_options();

		$this->assertTrue( $options[ self::$field_name ] );

		// Change value.
		update_user_option( $this->user_id, self::$admin_options_name, false );
		c2c_AdminExpertMode::reset();
		$options = c2c_AdminExpertMode::get_options();

		$this->assertNotTrue( $options[ self::$field_name ] );
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

	public function test_query_key_disables_is_admin_expert_mode() {
		$this->test_user_option_enables_is_admin_expert_mode_active();

		$_GET[ self::$disable_query_key ] = '1';

		$this->assertFalse( c2c_AdminExpertMode::is_admin_expert_mode_active() );
	}

	/*
	 * filter: c2c_admin_expert_mode
	 */

	public function test_filter_cc2c_admin_expert_mode_enables_mode() {
		add_filter( 'c2c_admin_expert_mode', '__return_true' );

		$this->assertTrue( c2c_AdminExpertMode::is_admin_expert_mode_active() );
	}

	public function test_filter_cc2c_admin_expert_mode_disables_mode() {
		add_filter( 'c2c_admin_expert_mode', '__return_false' );

		update_user_option( $this->user_id, self::$admin_options_name, true );

		$this->assertFalse( c2c_AdminExpertMode::is_admin_expert_mode_active() );
	}

	/*
	 * filter: c2c_admin_expert_mode_default
	 */

	public function test_filter_c2c_admin_expert_mode_default_true_enables_mode() {
		add_filter( 'c2c_admin_expert_mode_default', '__return_true' );

		$this->assertTrue( c2c_AdminExpertMode::is_admin_expert_mode_active() );
	}

	public function test_filter_c2c_admin_expert_mode_default_false_leaves_mode_disabled() {
		add_filter( 'c2c_admin_expert_mode_default', '__return_false' );

		$this->assertFalse( c2c_AdminExpertMode::is_admin_expert_mode_active() );
	}

	public function test_filter_c2c_admin_expert_mode_default_true_does_not_override_user_setting() {
		add_filter( 'c2c_admin_expert_mode_default', '__return_true' );

		update_user_option( $this->user_id, self::$admin_options_name, true );

		$this->assertTrue( c2c_AdminExpertMode::is_admin_expert_mode_active() );
	}

	public function test_filter_c2c_admin_expert_mode_default_false_does_not_override_user_setting() {
		add_filter( 'c2c_admin_expert_mode_default', '__return_false' );

		update_user_option( $this->user_id, self::$admin_options_name, true );

		$this->assertTrue( c2c_AdminExpertMode::is_admin_expert_mode_active() );
	}

	/*
	 * display_activation_notice()
	 */

	public function test_display_activation_notice_nothing_display_when_plugin_not_just_activated() {
		$this->expectOutputRegex( '/^$/', c2c_AdminExpertMode::display_activation_notice() );
	}

	public function test_display_activation_notice_displays_when_plugin_just_activated() {
		$this->test_plugin_activated();

		$this->expectOutputRegex( "/^<div id='message' class='updated fade'><p>.+<\/p><\/div>$/", c2c_AdminExpertMode::display_activation_notice() );
	}

	public function test_display_activation_notice_when_inactive() {
		$this->test_plugin_activated();

		$expected = '<strong>NOTE:</strong> You must enable expert mode for yourself (in your <a href="http://example.org/wp-admin/profile.php" title="Profile">profile</a>) for it to take effect. Other admin users must do the same for themselves as well. (See the readme.txt for more advanced controls.)';

		$this->expectOutputRegex(
			'|' . preg_quote( $expected ) . '|', c2c_AdminExpertMode::display_activation_notice() );
	}

	public function test_display_activation_notice_when_active() {
		$this->test_plugin_activated();
		update_user_option( $this->user_id, self::$admin_options_name, true );

		$expected = 'Expert mode is now enabled for you since you had it previously enabled. You can disable it in your <a href="http://example.org/wp-admin/profile.php" title="Profile">profile</a>. Reminder: other admins must separately enable expert mode for themselves via their own profiles. (See the readme.txt for more advanced controls.)';

		$this->expectOutputRegex( '|' . preg_quote( $expected ) . '|', c2c_AdminExpertMode::display_activation_notice() );
	}

	/*
	 * register_styles()
	 */

	public function test_register_styles() {
		$this->assertFalse( wp_style_is( self::$style_handle, 'registered' ) );

		c2c_AdminExpertMode::register_styles();

		$this->assertTrue( wp_style_is( self::$style_handle, 'registered' ) );

		// Cleanup.
		wp_deregister_style( self::$style_handle );
		$this->assertFalse( wp_style_is( self::$style_handle, 'registered' ) );
	}

	/*
	 * enqueue_admin_css()
	 */

	public function test_enqueue_admin_css() {
		c2c_AdminExpertMode::register_styles();
		c2c_AdminExpertMode::enqueue_admin_css();

		$this->assertTrue( wp_style_is( self::$style_handle, 'registered' ) );
		$this->assertFalse( wp_style_is( self::$style_handle, 'enqueued' ) );

		c2c_AdminExpertMode::reset();
		update_user_option( $this->user_id, self::$admin_options_name, true );
		$this->assertTrue( c2c_AdminExpertMode::is_admin_expert_mode_active() );
		c2c_AdminExpertMode::enqueue_admin_css();

		$this->assertTrue( wp_style_is( self::$style_handle, 'enqueued' ) );

		// Cleanup.
		wp_deregister_style( self::$style_handle );
		$this->assertFalse( wp_style_is( self::$style_handle, 'registered' ) );
		wp_dequeue_style( self::$style_handle );
		$this->assertFalse( wp_style_is( self::$style_handle, 'enqueued' ) );
	}

	/*
	 * show_option()
	 */

	public function test_show_option_with_setting_disabled() {
		$expected = '<tr><th scope="row"></th><td><label for="admin_expert_mode"><input type="checkbox" id="admin_expert_mode" name="admin_expert_mode" value="1"></label></td></tr>' . "\n";

		$this->expectOutputRegex(
			'|^' . preg_quote( $expected ) . '$|',
			c2c_AdminExpertMode::show_option( wp_get_current_user() )
		);
	}

	public function test_show_option_with_setting_enabled() {
		update_user_option( $this->user_id, self::$admin_options_name, true );

		$expected = '<tr><th scope="row"></th><td><label for="admin_expert_mode"><input type="checkbox" id="admin_expert_mode" name="admin_expert_mode" value="1" checked=\'checked\'></label></td></tr>' . "\n";

		$this->expectOutputRegex(
			'|^' . preg_quote( $expected ) . '$|',
			c2c_AdminExpertMode::show_option( wp_get_current_user() )
		);
	}

	/*
	 * get_options()
	 */

	public function test_get_options_with_invalid_user() {
		$this->assertEquals( array( 'admin_expert_mode' => false ), c2c_AdminExpertMode::get_options( 99999 ) );
	}

	public function test_get_options_default_implied_current_user() {
		update_user_option( $this->user_id, self::$admin_options_name, true );

		$this->assertEquals( array( 'admin_expert_mode' => true ), c2c_AdminExpertMode::get_options() );
	}

	/*
	 * maybe_save_options()
	 */

	public function test_maybe_save_options_called_by_user_without_privilege() {
		$_POST[ self::$field_name ] = '1';

		$user_id = $this->factory->user->create( array( 'role' => 'subscriber' ) );
		wp_set_current_user( $user_id );

		c2c_AdminExpertMode::maybe_save_options( $user_id );

		$this->assertFalse( get_user_option( $user_id, self::$admin_options_name ) );
	}

	public function test_maybe_save_options_saves_setting_privileged_self() {
		$_POST[ self::$field_name ] = '1';

		c2c_AdminExpertMode::maybe_save_options( $this->user_id );

		$this->assertEquals( array( self::$field_name => true ), get_user_option( self::$admin_options_name, $this->user_id ) );
	}

	public function test_maybe_save_options_saves_setting_being_enabled() {
		$_POST[ self::$field_name ] = '1';

		$user_id = $this->factory->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $user_id );

		c2c_AdminExpertMode::maybe_save_options( $this->user_id );

		$this->assertEquals( array( self::$field_name => true ), get_user_option( self::$admin_options_name, $this->user_id ) );
	}

}
