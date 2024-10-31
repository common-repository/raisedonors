<?php

/**
 * @link              https://raisedonors.com/
 * @since             1.0.0
 * @package           Raise_Donors
 *
 * @wordpress-plugin
 * Plugin Name:       RaiseDonors
 * Plugin URI:        https://help.raisedonors.com/hc/en-us/sections/360012433992-Wordpress
 * Description:       Easily embed your RaiseDonors donation forms with just a few clicks.
 * Version:           1.0.9
 * Author:            RaiseDonors
 * Author URI:        https://raisedonors.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       raise-donors
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'RAISE_DONORS_VERSION', '1.0.9' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-raise-donors-activator.php
 */
function activate_raise_donors() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-raise-donors-activator.php';
	Raise_Donors_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-raise-donors-deactivator.php
 */
function deactivate_raise_donors() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-raise-donors-deactivator.php';
	Raise_Donors_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_raise_donors' );
register_deactivation_hook( __FILE__, 'deactivate_raise_donors' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-raise-donors.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_raise_donors() {

	$plugin = new Raise_Donors();
	$plugin->run();

}
run_raise_donors();

