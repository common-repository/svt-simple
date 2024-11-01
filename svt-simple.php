<?php

/**
 *
 * @link              https://www.business-fotos-koeln.de/detlef
 * @since             1.0.0
 * @package           Svt-simple
 *
 * @wordpress-plugin
 * Plugin Name:       SVT-Simple
 * Plugin URI:        https://www.business-fotos-koeln.de/svt-simple
 * Description:       The perfect PlugIn to display Google Street View Panos in Wordpress.
 * Version:           1.0.1
 * Author:            Detlef Beyer / business-fotos-koeln.de
 * Author URI:        https://www.business-fotos-koeln.de/detlef
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       svt-simple
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'SVT_SIMPLE_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-svt-simple-activator.php
 */
function activate_svt_simple() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-svt-simple-activator.php';
	Svt_simple_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-svt-simple-deactivator.php
 */
function deactivate_svt_simple() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-svt-simple-deactivator.php';
	Svt_simple_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_svt_simple' );
register_deactivation_hook( __FILE__, 'deactivate_svt_simple' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-svt-simple.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_svt_simple() {

	$plugin = new Svt_simple();
	$plugin->run();

}
run_svt_simple();

