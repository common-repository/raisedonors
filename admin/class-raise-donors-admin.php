<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://raisedonors.com/
 * @since      1.0.0
 *
 * @package    Raise_Donors
 * @subpackage Raise_Donors/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Raise_Donors
 * @subpackage Raise_Donors/admin
 * @author     RaiseDonors, LLC <info@raisedonors.com>
 */
class Raise_Donors_Admin {

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
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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
		add_editor_style( plugin_dir_url( __FILE__ ) . 'css/raise-donors-editor.css' );
		wp_register_style( 'select2', plugin_dir_url( __FILE__ ) .'library/select2/css/select2.min.css', [],
			'4.0.13' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/raise-donors-admin.css', [ 'select2' ],
			$this->version, 'all' );


	}

	/**
	 * Register the JavaScript for the admin area.
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
		/**
		 * <link href="" rel="stylesheet" />
		 * <script src=""></script>
		 */
		wp_register_script( 'select2', plugin_dir_url( __FILE__ ) .'library/select2/js/select2.min.js',
			[ 'jquery' ], '4.0.13', false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/raise-donors-admin.js',
			array( 'jquery', 'select2' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'rd_shortcodes_ui', array(
			'I10n'          => array(
				'insert'                                  => __( 'Insert', $this->plugin_name ),
				'cancel'                                  => __( 'Cancel', $this->plugin_name ),
				'shortcode_ui_button_tooltip'             => __( 'Generate RaiseDonors Donation Form',
					$this->plugin_name ),
				'popup_title'                             => __( 'Insert RaiseDonors Shortcode', $this->plugin_name ),
				'popup_select_campaign_title'             => __( 'Select Donation Page', $this->plugin_name ),
				'popup_select_campaign_display_type'      => __( 'Select display style', $this->plugin_name ),
				'popup_select_campaign_display_type_full' => __( 'Show Page Content + Donation Form', $this->plugin_name ),
				'popup_select_campaign_display_type_form' => __( 'Show Donation Form Only', $this->plugin_name ),
				'popup_submit'                            => __( 'Insert', $this->plugin_name ),
				'error_loading_shortcode_preview'         => __( 'There was an error in generating the preview',
					$this->plugin_name ),
			),
			'rest_endpoint' => get_rest_url( null, 'raise-donors/v1/campaigns' ),
			'nonce'         => wp_create_nonce( 'wp_rest' )
		) );

		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );

	}


	public function mce_buttons( $buttons ) {
		array_push( $buttons, 'rd_shortcodes' );

		return $buttons;
	}

	public function mce_external_plugins( $plugin_array ) {
		return array_merge( $plugin_array, array(
			'rd_shortcodes' => plugin_dir_url( __FILE__ ) . 'js/raise-donors-mce.js',
		) );
	}

	public function rest_api_init() {
		register_rest_route( 'raise-donors/v1', '/campaigns', [
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_campaigns' ],
			'permission_callback' => function () {
				return current_user_can( 'edit_others_posts' );
			}
		] );

		register_rest_route( 'raise-donors/v1', '/campaign/(?P<id>\d+)', [
			'methods'             => 'GET',
			'callback'            => [ $this, 'get_campaign' ],
			'permission_callback' => function () {
				return current_user_can( 'edit_others_posts' );
			},
			'args'                => [
				'id' => [
					'validate_callback' => function ( $param, $request, $key ) {
						return is_numeric( $param );
					}
				],
			],
		] );
	}

	public function get_campaigns($request) {
		$more = false;
		if ( $request['term'] ) {
			$result = Raise_Donors_Connection::campaignsSearch( $request['term'] );
		} else {
			$page = $request['page'];

			if ( ! $page ) {
				$page = 1;
			}
			$result = Raise_Donors_Connection::campaigns( $page );
			if ( count( $result ) == 25 ) {
				$more = true;
			}
		}
		$data = [];
		foreach ( $result as $item ) {
			$data[] = [
				'id'             => $item['campaignId'],
				'campaignId'     => $item['campaignId'],
				'publicTitle'    => $item['publicTitle'],
				'text'           => $item['publicTitle'],
				'internalTitle'  => $item['internalTitle'],
				'motivationCode' => $item['motivationCode'],
				'sourceCode'     => $item['sourceCode']
			];
		}

		return [
			"results"    => $data,
			"pagination" => [
				"more" => $more
			]
		];
	}

	public function get_campaign( $data ) {
		$args = array(
			'meta_key'   => 'raise_donors_campaign_id',
			'meta_value' => $data['id'],
			'post_type'  => 'rd_campaign'
		);

		$query = new WP_Query( $args );
		if ( $query->post_count ) {
			$current_post = $query->post;
		} else {
			$current_post = Raise_Donors_Shortcodes::getCampaign( $data['id'] );
		}
		return json_decode(get_post_meta($current_post->ID,'raise_donors_data',true));
	}

}
