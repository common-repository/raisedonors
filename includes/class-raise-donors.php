<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://raisedonors.com/
 * @since      1.0.0
 *
 * @package    Raise_Donors
 * @subpackage Raise_Donors/includes
 */

use Elementor\Plugin;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Raise_Donors
 * @subpackage Raise_Donors/includes
 * @author     RaiseDonors, LLC <info@raisedonors.com>
 */
class Raise_Donors {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Raise_Donors_Loader $loader Maintains and registers all hooks for the plugin.
	 */

	protected $loader;
	/**
	 * The loader that's responsible for maintaining and registering all shortcodes that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Raise_Donors_Shortcodes $shortcodes Maintains and registers all shortcodes for the plugin.
	 */
	protected $shortcodes;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'RAISE_DONORS_VERSION' ) ) {
			$this->version = RAISE_DONORS_VERSION;
		} else {
			$this->version = '1.0.2';
		}
		$this->plugin_name = 'raise-donors';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Raise_Donors_Loader. Orchestrates the hooks of the plugin.
	 * - Raise_Donors_i18n. Defines internationalization functionality.
	 * - Raise_Donors_Admin. Defines all hooks for the admin area.
	 * - Raise_Donors_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-raise-donors-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-raise-donors-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-raise-donors-connection.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'gutenberg-block/init.php';


		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-raise-donors-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-raise-donors-settings.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-raise-donors-public.php';

		/**
		 * The class responsible for defining all shortcodes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-raise-donors-shortcodes.php';

		$this->loader     = new Raise_Donors_Loader();
		$this->shortcodes = new Raise_Donors_Shortcodes();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Raise_Donors_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Raise_Donors_i18n( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin          = new Raise_Donors_Admin( $this->get_plugin_name(), $this->get_version() );
		$raise_donors_settings = new Raise_Donors_Settings( $this->get_plugin_name(), $this->get_version() );

		$settings = Raise_Donors_Settings::getSettings();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'rest_api_init', $plugin_admin, 'rest_api_init' );
		if ( $settings['license_key_1'] && $settings['organization_key_0'] ) {
			$this->loader->add_filter( 'mce_buttons', $plugin_admin, 'mce_buttons' );
		}
		$this->loader->add_filter( 'mce_external_plugins', $plugin_admin, 'mce_external_plugins' );

		$this->loader->add_filter('plugin_action_links_raise-donors/raise-donors.php',$raise_donors_settings,'add_plugin_page_settings_link');

		if ( is_admin() ) {
			$this->loader->add_action( 'admin_menu', $raise_donors_settings, 'raise_donors_settings_add_plugin_page' );
			$this->loader->add_action( 'admin_init', $raise_donors_settings, 'raise_donors_settings_page_init' );
			if ( ! $settings['license_key_1'] || ! $settings['organization_key_0'] ) {
				$this->loader->add_action( 'admin_notices', $raise_donors_settings, 'raise_donors_admin_notice_init' );
			}
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$this->shortcodes->load();
		$plugin_public = new Raise_Donors_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'campaigns_post_type', 0 );

		add_action( 'elementor/widgets/widgets_registered', function () {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-raise-donors-elementor-widget.php';

			$widget = new Raise_Donors_Elementor_Widget();

			Plugin::instance()->widgets_manager->register_widget_type( $widget );
		} );
		add_action( 'elementor/editor/wp_head', function () {
      wp_enqueue_script( 'raise-donors-elementor', plugin_dir_url( __FILE__ ) . '../admin/js/raise-donors-elementor.js', [ 'jquery' ], $this->version, true );
      wp_enqueue_style( 'raise-donors-elementor', plugin_dir_url( __FILE__ ) . '../admin/css/raise-donors-elementor.css');
		} );


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Raise_Donors_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

}
