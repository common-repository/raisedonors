<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://raisedonors.com/
 * @since      1.0.0
 *
 * @package    Raise_Donors
 * @subpackage Raise_Donors/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Raise_Donors
 * @subpackage Raise_Donors/public
 * @author     RaiseDonors, LLC <info@raisedonors.com>
 */
class Raise_Donors_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Raise_Donors_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Raise_Donors_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/raise-donors-public.css', array(),
			$this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Raise_Donors_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Raise_Donors_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/raise-donors-public.js',
			array( 'jquery' ), $this->version, false );

	}


	// Register Custom Post Type
	public function campaigns_post_type() {

		$labels = [
			'name'                  => _x( 'Campaigns', 'Post Type General Name', $this->plugin_name ),
			'singular_name'         => _x( 'Campaign', 'Post Type Singular Name', $this->plugin_name ),
			'menu_name'             => __( 'Post Types', $this->plugin_name ),
			'name_admin_bar'        => __( 'Post Type', $this->plugin_name ),
			'archives'              => __( 'Item Archives', $this->plugin_name ),
			'attributes'            => __( 'Item Attributes', $this->plugin_name ),
			'parent_item_colon'     => __( 'Parent Item:', $this->plugin_name ),
			'all_items'             => __( 'All Items', $this->plugin_name ),
			'add_new_item'          => __( 'Add New Item', $this->plugin_name ),
			'add_new'               => __( 'Add New', $this->plugin_name ),
			'new_item'              => __( 'New Item', $this->plugin_name ),
			'edit_item'             => __( 'Edit Item', $this->plugin_name ),
			'update_item'           => __( 'Update Item', $this->plugin_name ),
			'view_item'             => __( 'View Item', $this->plugin_name ),
			'view_items'            => __( 'View Items', $this->plugin_name ),
			'search_items'          => __( 'Search Item', $this->plugin_name ),
			'not_found'             => __( 'Not found', $this->plugin_name ),
			'not_found_in_trash'    => __( 'Not found in Trash', $this->plugin_name ),
			'featured_image'        => __( 'Featured Image', $this->plugin_name ),
			'set_featured_image'    => __( 'Set featured image', $this->plugin_name ),
			'remove_featured_image' => __( 'Remove featured image', $this->plugin_name ),
			'use_featured_image'    => __( 'Use as featured image', $this->plugin_name ),
			'insert_into_item'      => __( 'Insert into item', $this->plugin_name ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', $this->plugin_name ),
			'items_list'            => __( 'Items list', $this->plugin_name ),
			'items_list_navigation' => __( 'Items list navigation', $this->plugin_name ),
			'filter_items_list'     => __( 'Filter items list', $this->plugin_name ),
		];
		$args   = [
			'label'               => __( 'Campaign', $this->plugin_name ),
			'description'         => __( 'Post Type Description', $this->plugin_name ),
			'labels'              => $labels,
			'supports'            => false,
			'taxonomies'          => [],
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => false,
			'show_in_menu'        => false,
			'menu_position'       => 5,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => false,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'rewrite'             => false,
			'capability_type'     => 'page',
			'show_in_rest'        => false,
		];
		register_post_type( 'rd_campaign', $args );
	}
}
