<?php

/**
 * Plugin Name:     Mai Post Reviewers
 * Plugin URI:      https://maitheme.com
 * Description:     Mark a post as "Reviewed by" one or more people.
 * Version:         0.1.0
 *
 * Author:          Mike Hemberger
 * Author URI:      https://maitheme.com
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main Mai_Post_Reviewers Class.
 *
 * @since 0.1.0
 */
final class Mai_Post_Reviewers {

	/**
	 * @var Mai_Post_Reviewers The one true Mai_Post_Reviewers
	 * @since 0.1.0
	 */
	private static $instance;

	/**
	 * Main Mai_Post_Reviewers Instance.
	 *
	 * Insures that only one instance of Mai_Post_Reviewers exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since   0.1.0
	 * @static  var array $instance
	 * @uses    Mai_Post_Reviewers::setup_constants() Setup the constants needed.
	 * @uses    Mai_Post_Reviewers::includes() Include the required files.
	 * @uses    Mai_Post_Reviewers::setup() Activate, deactivate, etc.
	 * @see     post_reviewers_instance()
	 * @return  object | Mai_Post_Reviewers The one true Mai_Post_Reviewers
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			// Setup the setup
			self::$instance = new Mai_Post_Reviewers;
			// Methods
			self::$instance->setup_constants();
			self::$instance->hooks();
		}
		return self::$instance;
	}

	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since   0.1.0
	 * @access  protected
	 * @return  void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mai-post-reviewers' ), '1.0' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @since   0.1.0
	 * @access  protected
	 * @return  void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mai-post-reviewers' ), '1.0' );
	}

	/**
	 * Setup plugin constants.
	 *
	 * @access  private
	 * @since   0.1.0
	 * @return  void
	 */
	private function setup_constants() {

		// Plugin version.
		if ( ! defined( 'MAI_POST_REVIEWERS_VERSION' ) ) {
			define( 'MAI_POST_REVIEWERS_VERSION', '0.1.0' );
		}

		// Plugin Folder Path.
		if ( ! defined( 'MAI_POST_REVIEWERS_PLUGIN_DIR' ) ) {
			define( 'MAI_POST_REVIEWERS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Includes Path.
		if ( ! defined( 'MAI_POST_REVIEWERS_INCLUDES_DIR' ) ) {
			define( 'MAI_POST_REVIEWERS_INCLUDES_DIR', MAI_POST_REVIEWERS_PLUGIN_DIR . 'includes/' );
		}

		// Plugin Folder URL.
		if ( ! defined( 'MAI_POST_REVIEWERS_PLUGIN_URL' ) ) {
			define( 'MAI_POST_REVIEWERS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin Root File.
		if ( ! defined( 'MAI_POST_REVIEWERS_PLUGIN_FILE' ) ) {
			define( 'MAI_POST_REVIEWERS_PLUGIN_FILE', __FILE__ );
		}

		// Plugin Base Name
		if ( ! defined( 'MAI_POST_REVIEWERS_BASENAME' ) ) {
			define( 'MAI_POST_REVIEWERS_BASENAME', dirname( plugin_basename( __FILE__ ) ) );
		}

	}

	public function hooks() {

		// Include vendor libraries.
		require_once __DIR__ . '/vendor/autoload.php';

		// add_action( 'admin_init', array( $this, 'updater' ) );
		add_action( 'init', array( $this, 'register_content_types' ) );

		// Includes.
		foreach ( glob( MAI_POST_REVIEWERS_INCLUDES_DIR . '*.php' ) as $file ) { include $file; }

		register_activation_hook(   __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
	}

	/**
	 * Setup the updater.
	 *
	 * @uses    https://github.com/YahnisElsts/plugin-update-checker/
	 *
	 * @return  void
	 */
	public function updater() {
		// Bail if current user cannot manage plugins.
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}
		// Bail if plugin updater is not loaded.
		if ( ! class_exists( 'Puc_v4_Factory' ) ) {
			return;
		}
		// Setup the updater.
		$updater = Puc_v4_Factory::buildUpdateChecker( 'https://github.com/maithemewp/mai-post-reviewers/', __FILE__, 'mai-post-reviewers' );
	}

	/**
	 * Register custom content types.
	 *
	 * @since   0.1.0
	 *
	 * @return  void
	 */
	public function register_content_types() {

		/***********************
		 *  Custom Taxonomies  *
		 ***********************/

		register_taxonomy( 'reviewer', array( 'post' ), array(
			'hierarchical' => true,
			'labels'       => array(
				'name'                       => _x( 'Reviewers', 'Reviewer General Name',   'mai-post-reviewers' ),
				'singular_name'              => _x( 'Reviewer',  'Reviewer Singular Name',  'mai-post-reviewers' ),
				'menu_name'                  => __( 'Reviewers',                            'mai-post-reviewers' ),
				'all_items'                  => __( 'All Reviewers',                        'mai-post-reviewers' ),
				'parent_item'                => __( 'Parent Reviewer',                      'mai-post-reviewers' ),
				'parent_item_colon'          => __( 'Parent Reviewer:',                     'mai-post-reviewers' ),
				'new_item_name'              => __( 'New Reviewer Name',                    'mai-post-reviewers' ),
				'add_new_item'               => __( 'Add New Reviewer',                     'mai-post-reviewers' ),
				'edit_item'                  => __( 'Edit Reviewer',                        'mai-post-reviewers' ),
				'update_item'                => __( 'Update Reviewer',                      'mai-post-reviewers' ),
				'view_item'                  => __( 'View Reviewer',                        'mai-post-reviewers' ),
				'separate_items_with_commas' => __( 'Separate items with commas',           'mai-post-reviewers' ),
				'add_or_remove_items'        => __( 'Add or remove items',                  'mai-post-reviewers' ),
				'choose_from_most_used'      => __( 'Choose from the most used',            'mai-post-reviewers' ),
				'popular_items'              => __( 'Popular Reviewers',                    'mai-post-reviewers' ),
				'search_items'               => __( 'Search Reviewers',                     'mai-post-reviewers' ),
				'not_found'                  => __( 'Not Found',                            'mai-post-reviewers' ),
			),
			'meta_box_cb'       => null, // Hides metabox.
			'public'            => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_in_rest'      => true,
			'show_tagcloud'     => true,
			'show_ui'           => true,
		) );

	}

	public function activate() {
		$this->register_content_types();
		flush_rewrite_rules();
	}

}

/**
 * The main function for that returns Mai_Post_Reviewers
 *
 * The main function responsible for returning the one true Mai_Post_Reviewers
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $plugin = post_reviewers_instance(); ?>
 *
 * @access  private
 * @since   0.1.0
 *
 * @return  object|Mai_Post_Reviewers The one true Mai_Post_Reviewers Instance.
 */
function post_reviewers_instance() {
	return Mai_Post_Reviewers::instance();
}

// Get post_reviewers_instance Running.
post_reviewers_instance();
