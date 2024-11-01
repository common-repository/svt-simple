<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @link       https://www.business-fotos-koeln.de/detlef
 * @since      1.0.0
 * @package    Svt-simple
 * @subpackage Svt-simple/includes
 * @author     Detlef Beyer <d.beyer@medienkonzepte.de>
 */
class Svt_simple {

	protected $loader;
	protected $plugin_name;
	protected $version;
	protected $logger;
	protected $log_on = true;
	protected $basepath;
	protected static $instance = NULL;

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
		if ( defined( 'SVT_SIMPLE_VERSION' ) ) {
			$this->version = SVT_SIMPLE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'svt-simple';

		$this->load_dependencies();
		$this->set_locale();

		$this->start_logger();

		$this->define_admin_hooks();
		$this->define_public_hooks();

		$this->log('SVT Simple ' . $this->version . ' initialized');
	}

     /**
     * Access plugin instance.
     *
	 */
	public static function get_instance() {
		if ( NULL === self::$instance )
			self::$instance = new self;

		return self::$instance;
	}

	//Initalise Log file:
	private function start_logger() {
		$uploaddir = wp_upload_dir();
		$logdir = $uploaddir['basedir'].DIRECTORY_SEPARATOR.'svtsimple-logs';
		if (!file_exists($logdir)){
			wp_mkdir_p($logdir);
		}
		$this->logger = new KLogger\Logger($logdir, Psr\Log\LogLevel::DEBUG);	
	}
	
	// Logging, see https://github.com/katzgrau/KLogger
	function log($message, $context = array()) {
		if ($this->log_on) {
			if (!is_array($context)) $context = array($context);
			return $this->logger->log(Psr\Log\LogLevel::INFO, $message, $context);
		}
		
		return;
	}

	function logWarning($message, $context = array()) {
		if ($this->log_on) {
			if (!is_array($context)) $context = array($context);
			return $this->logger->log(Psr\Log\LogLevel::WARNING, $message, $context);
		}
		
		return;
	}

	function logError($message, $context = array()) {
		if ($this->log_on) {
			if (!is_array($context)) $context = array($context);
			return $this->logger->log(Psr\Log\LogLevel::ERROR, $message, $context);
		}
		
		return;
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Svt_simple_Loader. Orchestrates the hooks of the plugin.
	 * - Svt_simple_i18n. Defines internationalization functionality.
	 * - Svt_simple_Admin. Defines all hooks for the admin area.
	 * - Svt_simple_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		$basepath = plugin_dir_path(dirname( __FILE__ ));

		//Logging
		if (!interface_exists("\\Psr\\Log\\LoggerInterface")) {
			require_once $basepath . 'includes/logging/LoggerInterface.php';
		}
		if (!class_exists("\\Psr\\Log\\AbstractLogger")) {
			require_once $basepath . 'includes/logging/AbstractLogger.php';
		}
		if (!class_exists("\\Psr\\Log\\LogLevel")) {
			require_once $basepath . 'includes/logging/LogLevel.php';
		}
		if (!class_exists("\\KLogger\\Logger")) {
			require_once $basepath . 'includes/logging/logger.php';
		}


		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once $basepath . 'includes/class-svt-simple-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once $basepath . 'includes/class-svt-simple-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once $basepath . 'admin/class-svt-simple-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once $basepath . 'public/class-svt-simple-public.php';

		$this->loader = new Svt_simple_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Svt_simple_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Svt_simple_i18n();

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

		$plugin_admin = new Svt_simple_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Save/Update our plugin options
		$this->loader->add_action('admin_init', $plugin_admin, 'options_update');

		// Add menu item
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		// Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );

		//	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );


		// Get Google Maps API Key - is the option set? If not: show a warning 
		$options = get_option($this->plugin_name);
		$google_api_key = ( isset( $options['google_api_key'] ) && ! empty( $options['google_api_key'] ) ) ? esc_attr( $options['google_api_key'] ) : 'empty';
		$this->log_on   = ( isset( $options['switch_log'] ) && ! empty( $options['switch_log'] ) ) ? 1 : 0;

		if (strlen($google_api_key) < 20 || !$google_api_key) {
			$this->logWarning('define_admin_hooks - api key missing',$google_api_key);
			$this->loader->add_action( 'admin_notices', $plugin_admin, 'display_svt_error' );
		}
		
		// Pegman https://www.sitepoint.com/adding-a-media-button-to-the-content-editor/
		$this->loader->add_action('media_buttons', $plugin_admin, 'add_panorama_button', 99);
		$this->loader->add_action('media_upload_svtsimple', $plugin_admin, 'svtsimple_iframe'); // Call the new tab 'svtsimple' with wp_iframe.
		
		// Check for Fusion Builder
		$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'check_fusion_builder' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Svt_simple_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
		// This one is needed to load the Maps Script on demand
		$this->loader->add_filter( 'wp_enqueue_scripts', $plugin_public, 'conditionally_add_scripts_and_styles' );
		$this->loader->add_filter( 'script_loader_tag', $plugin_public, 'wp_det_add_async_attribute' , 10, 3);

		$this->loader->add_shortcode( 'addmypanorma', $plugin_public, 'wpdet_addsvpanorama', $priority = 10, $accepted_args = 12 );

	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}
	
}
