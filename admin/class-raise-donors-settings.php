<?php

class Raise_Donors_Settings {
	private $raise_donors_settings_options;
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

	public function add_plugin_page_settings_link( $plugin_actions ) {
		$plugin_actions[] = '<a href="' .
		                    admin_url( 'options-general.php?page=raise-donors-settings' ) .
		                    '">' . __( 'Settings', $this->plugin_name ) . '</a>';


		return $plugin_actions;
	}

	public function raise_donors_settings_add_plugin_page() {
		add_options_page(
			__( 'RaiseDonors', $this->plugin_name ), // page_title
			__( 'RaiseDonors', $this->plugin_name ), // menu_title
			'manage_options', // capability
			'raise-donors-settings', // menu_slug
			array( $this, 'raise_donors_settings_create_admin_page' ) // function
		);
	}

	public function raise_donors_settings_create_admin_page() {
		$this->raise_donors_settings_options = static::getSettings(); ?>

      <div class="wrap">
        <h2><?php _e( 'RaiseDonors', $this->plugin_name ); ?></h2>
		  <?php settings_errors(); ?>

        <form method="post" action="options.php">
			<?php
			settings_fields( 'raise_donors_settings_option_group' );
			do_settings_sections( 'raise-donors-settings-admin' );
			submit_button();
			?>
        </form>
      </div>
	<?php }

	public function raise_donors_settings_page_init() {
		register_setting(
			'raise_donors_settings_option_group', // option_group
			'raise_donors_settings_option_name', // option_name
			array( $this, 'raise_donors_settings_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'raise_donors_settings_setting_section', // id
			__( 'Connect your RaiseDonors account.', $this->plugin_name ), // title
			array( $this, 'raise_donors_settings_section_info' ), // callback
			'raise-donors-settings-admin' // page
		);

		add_settings_field(
			'organization_key_0', // id
			__( 'Organization Key', $this->plugin_name ) .
			'<br><a href="https://help.raisedonors.com/hc/en-us/articles/360057988132-Where-Do-I-Find-My-Organization-Key-" target="_blank">' .
			__( 'How to find your organization key', $this->plugin_name ) . '</a>',
			array( $this, 'organization_key_0_callback' ), // callback
			'raise-donors-settings-admin', // page
			'raise_donors_settings_setting_section' // section
		);

		add_settings_field(
			'license_key_1', // id
			__( 'License Key', $this->plugin_name ) .
			'<br><a href="https://help.raisedonors.com/hc/en-us/articles/360058447791-Where-Do-I-Find-My-License-Key-" target="_blank">' .
			__( 'How to find your license key', $this->plugin_name ) . '</a>',
			array( $this, 'license_key_1_callback' ), // callback
			'raise-donors-settings-admin', // page
			'raise_donors_settings_setting_section' // section
		);
	}

	public function raise_donors_settings_sanitize( $input ) {
		$sanitary_values = array();
		if ( isset( $input['organization_key_0'] ) ) {
			$sanitary_values['organization_key_0'] = sanitize_text_field( $input['organization_key_0'] );
		}

		if ( isset( $input['license_key_1'] ) ) {
			$sanitary_values['license_key_1'] = sanitize_text_field( $input['license_key_1'] );
		}

		return $sanitary_values;
	}

	public function raise_donors_settings_section_info() {

	}

	public function organization_key_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="raise_donors_settings_option_name[organization_key_0]" id="organization_key_0" value="%s">',
			isset( $this->raise_donors_settings_options['organization_key_0'] ) ? esc_attr( $this->raise_donors_settings_options['organization_key_0'] ) : ''
		);
	}

	public function license_key_1_callback() {
		printf(
			'<input class="regular-text" type="text" name="raise_donors_settings_option_name[license_key_1]" id="license_key_1" value="%s">',
			isset( $this->raise_donors_settings_options['license_key_1'] ) ? esc_attr( $this->raise_donors_settings_options['license_key_1'] ) : ''
		);
	}

	public function raise_donors_admin_notice_init() {
		?>
      <div class="error notice">
        <p><?php _e( 'To use the RaiseDonors plugin, please provide your <a href="' . admin_url( 'options-general.php?page=raise-donors-settings' ) . '">API keys</a>!',
				$this->plugin_name ); ?></p>
      </div>
		<?php
	}

	static function getSettings() {
		return get_option( 'raise_donors_settings_option_name' );
	}

}

