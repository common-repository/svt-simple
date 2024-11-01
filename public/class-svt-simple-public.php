<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @link       https://www.business-fotos-koeln.de/detlef
 * @since      1.0.0
 * @package    Svt-simple
 * @subpackage Svt-simple/public
 * @author     Detlef Beyer <d.beyer@medienkonzepte.de>
 */
class Svt_simple_Public {

	private $plugin_name;
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/*
        $options    = get_option($this->plugin_name);
        $enable_image = ( isset( $options['enable_image'] ) && ! empty( $options['enable_image'] ) ) ? 1 : 0;

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/svt-simple-public.css', array(), $this->version, 'all' );
		if($enable_image) {
			wp_enqueue_style( $this->plugin_name . 'featherlight', plugin_dir_url( __FILE__ ) . 'css/featherlight.min.css', array(), $this->version, 'all' );
		}
		*/
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

 		/*
        $options      = get_option($this->plugin_name);
        $enable_image = ( isset( $options['enable_image'] ) && ! empty( $options['enable_image'] ) ) ? 1 : 0;
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/svt-simple-public.js', array( 'jquery' ), $this->version, false );
		if($enable_image) {
			wp_enqueue_script( $this->plugin_name . 'featherlight', plugin_dir_url( __FILE__ ) . 'js/featherlight.min.js', array( 'jquery' ), $this->version, false );
		}
		*/
	}

	// load our script with the defer and async attributes
	public function wp_det_add_async_attribute( $tag, $handle, $src ) {
		// add async attribute 
        $options    = get_option($this->plugin_name);
        $load_async = ( isset( $options['load_async'] ) && ! empty( $options['load_async'] ) ) ? 0 : 1;

		$scripts_to_async = array($this->plugin_name . 'featherlight');
		if ( in_array( $handle, $scripts_to_async ) && $load_async ) {
			return str_replace(' src', ' async src', $tag);
		} else {
			return $tag;
		}

	}

	// enqueue only if needed by shortcode
	public function conditionally_add_scripts_and_styles( $content ) {
		global $post;
	
		$options        = get_option($this->plugin_name);
		$google_api_key = ( isset( $options['google_api_key'] ) && ! empty( $options['google_api_key'] ) ) ? esc_attr( $options['google_api_key'] ) : 'enter a valid api key';
		$check_simple   = ( isset( $options['check_simple'] ) && ! empty( $options['check_simple'] ) ) ? 1 : 0;
        $enable_image   = ( isset( $options['enable_image'] ) && ! empty( $options['enable_image'] ) ) ? 1 : 0;

		Svt_simple::get_instance()->log('conditionally_add_scripts_and_styles', [$google_api_key,$check_simple,$enable_image]);

		if(!$check_simple) {
			Svt_simple::get_instance()->log('do it the simple way');
			wp_enqueue_script('wpdet_jsgmaps', 'https://maps.google.com/maps/api/js?key=' . $google_api_key . '&callback=dET_initMap', array('jquery', $this->plugin_name), true);
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/svt-simple-public.css', array(), $this->version, 'all' );
			if($enable_image) {
				wp_enqueue_style( $this->plugin_name . 'featherlight', plugin_dir_url( __FILE__ ) . 'css/featherlight.min.css', array(), $this->version, 'all' );
			}
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/svt-simple-public.min.js', array( 'jquery' ), $this->version, false );
			if($enable_image) {
				wp_enqueue_script( $this->plugin_name . 'featherlight', plugin_dir_url( __FILE__ ) . 'js/featherlight.min.js', array( 'jquery' ), $this->version, false );
			}
		} else {
			if( isset($post) && ( has_shortcode( $post->post_content, 'addmypanorma') ) ) {
				wp_enqueue_script('wpdet_jsgmaps', 'https://maps.google.com/maps/api/js?key=' . $google_api_key . '&callback=dET_initMap', array('jquery', $this->plugin_name), true);
				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/svt-simple-public.css', array(), $this->version, 'all' );
				if($enable_image) {
					wp_enqueue_style( $this->plugin_name . 'featherlight', plugin_dir_url( __FILE__ ) . 'css/featherlight.min.css', array(), $this->version, 'all' );
				}
				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/svt-simple-public.min.js', array( 'jquery' ), $this->version, false );
				if($enable_image) {
					wp_enqueue_script( $this->plugin_name . 'featherlight', plugin_dir_url( __FILE__ ) . 'js/featherlight.min.js', array( 'jquery' ), $this->version, false );
				}
			}
		}
		
		return $content;
	}

	// values for width / height in px if not %
	private function wpdet_perc_if_not_px( $oldval ) {
		$newval = str_replace('px','',$oldval);
		if (substr_count($newval,'%') == 0)
			$newval .= 'px';
		
		return $newval;
	}
	
	// values for width / height only
	private function wpdet_no_perc_no_px( $oldval ) {
		$newval = str_replace('px','',$oldval);
		$newval = str_replace('%','',$newval);
		
		return $newval;
	}

	public function wpdet_addsvpanorama( $atts ) {
		// normalize attribute keys, lowercase
		$atts = array_change_key_case((array)$atts, CASE_LOWER);
 
		// override default attributes with user attributes
		$a = shortcode_atts(array(
			'width'   => '100%',
			'height'  => '400px',
			'wthumb'  => '200',
			'hthumb'  => '160',
			'lat'     => '50.9449599',
			'lon'     => '6.900115',
			'heading' => '180',
			'pitch'   => '0',
			'zoom'    => '1',
			'spin'    => '1',
			'panoid'  => '',
			'image'   => ''
		), $atts);
	
		$width   = (array_key_exists('width', $a)) ? $this->wpdet_perc_if_not_px($a['width']) : '100%';
		$height  = (array_key_exists('height', $a)) ? $this->wpdet_perc_if_not_px($a['height']) : '400px';
		$lat     = (array_key_exists('lat', $a)) ? $a['lat'] : '50.9449599';
		$lon     = (array_key_exists('lon', $a)) ? $a['lon'] : '6.900115';
		$heading = (array_key_exists('heading', $a)) ? $a['heading'] : '180';
		$pitch   = (array_key_exists('pitch', $a)) ? $a['pitch'] : '0';
		$zoom    = (array_key_exists('zoom', $a)) ? $a['zoom'] : '1';
		$spin    = (array_key_exists('spin', $a)) ? $a['spin'] : '1';
		$panoID  = (array_key_exists('panoid', $a)) ? $a['panoid'] : '';
		$doImage = (array_key_exists('image', $a)) ? $a['image'] : '0';
		$wthumb  = (array_key_exists('wthumb', $a)) ? $a['wthumb'] : '200';
		$hthumb  = (array_key_exists('hthumb', $a)) ? $a['hthumb'] : '160';
	
		if ($panoID != '') {
			$id = (string) rand();
		} else {
			$tempid = $lat . $lon;
			$id = str_replace('.','',$tempid);
		}

		Svt_simple::get_instance()->log('wpdet_addsvpanorama', [$atts]);
 
 		if($doImage != 0) {
 			// show only a statitc image
 			$options        = get_option($this->plugin_name);
			$google_api_key = ( isset( $options['google_api_key'] ) && ! empty( $options['google_api_key'] ) ) ? esc_attr( $options['google_api_key'] ) : 'enter a valid api key';

			$cleanWidth     = $this->wpdet_no_perc_no_px($width);
			$cleanHeight    = $this->wpdet_no_perc_no_px($height);
			
			ob_start();
?>		
<div class="svtsimple_image">
	<div id="<?php echo $id; ?>" class="svtsimple_single" style='width: <?php echo $wthumb; ?>px; height: <?php echo $hthumb; ?>px'>
		<a data-featherlight="iframe" data-featherlight-iframe-style="margin-bottom:0px" data-featherlight-iframe-width="<?php echo $cleanWidth; ?>" data-featherlight-iframe-height="<?php echo $cleanHeight; ?>" id="<?php echo $id; ?>_anchor" href="#">
			<img id="<?php echo $id; ?>_image" src='' width='<?php echo $wthumb; ?>px' height='<?php echo $hthumb; ?>px' alt='' />
		</a>
	</div>
</div>
<script type='text/javascript'>

	window.addEventListener("load", function load(event){
		window.removeEventListener("load", load, false); //remove listener, no longer needed
		dET_assignImage('<?php echo $lat; ?>', '<?php echo $lon; ?>', '<?php echo $cleanWidth; ?>', '<?php echo $cleanHeight; ?>', '<?php echo $id; ?>', <?php echo $pitch; ?>, <?php echo $heading; ?>, <?php echo $zoom; ?>, '<?php echo $google_api_key; ?>', '<?php echo plugin_dir_url( __FILE__ ) . 'partials/svt-simple-lightbox.php' ?>');
	},false);

</script>
<?php
			return ob_get_clean();
 		} else {
	 		// interactive panorama
			ob_start();
?>		
<div class="svtsimple_pano">
	<div id="<?php echo $id; ?>" class="svtsimple_single" style='width: <?php echo $width; ?>; height: <?php echo $height; ?>'></div>
	<script type='text/javascript'>
		<?php 
			if ($panoID != '') {
		?>
				window.addEventListener("load", function load(event){
					window.removeEventListener("load", load, false); //remove listener, no longer needed
					dET_assignIDPano('<?php echo $panoID; ?>', '<?php echo $id; ?>', <?php echo $pitch; ?>, <?php echo $heading; ?>, <?php echo $zoom; ?>, <?php echo $spin; ?>);					
				},false);
		<?php
			} else {
		?>
				window.addEventListener("load", function load(event){
					window.removeEventListener("load", load, false); //remove listener, no longer needed
					dET_assignLocPano('<?php echo $lat; ?>', '<?php echo $lon; ?>', '<?php echo $id; ?>', <?php echo $pitch; ?>, <?php echo $heading; ?>, <?php echo $zoom; ?>, <?php echo $spin; ?>);
				},false);
		<?php
			}
		?>
			window.addEventListener("load", function load(event){
    			window.removeEventListener("load", load, false); //remove listener, no longer needed
				dET_start_spin(<?php echo $id; ?>);
			},false);
	</script>
</div>
<?php
			return ob_get_clean();
 		}
	}
}


