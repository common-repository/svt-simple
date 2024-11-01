<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.business-fotos-koeln.de/detlef
 * @since      1.0.0
 * @package    Svt-simple
 * @subpackage Svt-simple/includes
 * @author     Detlef Beyer <d.beyer@medienkonzepte.de>
 */
class Svt_simple_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
	
		load_plugin_textdomain(
			'svt-simple',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/'
		);
	}
}
