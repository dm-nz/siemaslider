<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://lotus.kg
 * @since             0.1.0
 * @package           Siemaslider
 *
 * @wordpress-plugin
 * Plugin Name:       Siema Slider
 * Plugin URI:        https://github.com/dm-nz/siemaslider
 * Description:       Displays a gallery as a slider. Powered by Siema carousel.
 * Version:           0.1.0
 * Author:            Lotus
 * Author URI:        http://lotus.kg
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       siemaslider
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently pligin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '0.1.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-siemaslider-activator.php
 */
function activate_siemaslider() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-siemaslider-activator.php';
	Siemaslider_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-siemaslider-deactivator.php
 */
function deactivate_siemaslider() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-siemaslider-deactivator.php';
	Siemaslider_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_siemaslider' );
register_deactivation_hook( __FILE__, 'deactivate_siemaslider' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-siemaslider.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
function run_siemaslider() {

	$plugin = new Siemaslider();
	$plugin->run();

}
run_siemaslider();
