<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       https://www.business-fotos-koeln.de/detlef
 * @since      1.0.0
 * @package    Svt-simple
 * @subpackage Svt-simple/admin
 * @author     Detlef Beyer <d.beyer@medienkonzepte.de>
 */
class Svt_simple_Admin {

	private $plugin_name;
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/svt-simple-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/svt-simple-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	// Optionen einbauen
	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/**
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 *
		 * @link https://codex.wordpress.org/Function_Reference/add_options_page
		 * add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '' )
		 */
		$myMenuName = __('SVT Simple Settings', $this->plugin_name);
		add_submenu_page( 'options-general.php', 'SVT Simple Options', $myMenuName, 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page') );

	}

	/**
	* Add settings action link to the plugins page.
	*
	* @since    1.0.0
	*/
	public function add_action_links( $links ) {

		/*
		*  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
		*/
	   $settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>',
	   );
	   return array_merge(  $settings_link, $links );

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_setup_page() {

		include_once( 'partials/' . $this->plugin_name . '-admin-display.php' );

	}

	/**
	 * Validate fields from admin area plugin settings form
	 * @param  mixed $input as field form settings form
	 * @return mixed as validated fields
	 */
	public function validate($input) {

		$valid = array();

		$valid['google_api_key'] = ( isset( $input['google_api_key'] ) && ! empty( $input['google_api_key'] ) ) ? esc_attr( $input['google_api_key'] ) : 'enter a valid key';
    	$valid['check_simple']   = ( isset( $input['check_simple'] ) && ! empty( $input['check_simple'] ) ) ? 1 : 0;
    	$valid['load_async']     = ( isset( $input['load_async'] ) && ! empty( $input['load_async'] ) ) ? 0 : 1;
    	$valid['switch_log']     = ( isset( $input['switch_log'] ) && ! empty( $input['switch_log'] ) ) ? 1 : 0;
    	$valid['enable_image']   = ( isset( $input['enable_image'] ) && ! empty( $input['enable_image'] ) ) ? 1 : 0;

		return $valid;

	}

	public function options_update() {

		register_setting( $this->plugin_name, $this->plugin_name, array( $this, 'validate' ) );

	}

	/**
	 * Show a warning in the admin interface if no API key has been configured
	 *
	 * @since    1.0.0
	 */
	public function display_svt_error() {
		Svt_simple::get_instance()->logWarning('display_svt_error - api key missing');
		$notice = __('SVT Simple Shortcode: Please configure your Google Maps API Key', $this->plugin_name);
?>
    <div class="error notice">
        <p><?php _e($notice . ' / <a href="options-general.php?page=' . $this->plugin_name . '">' . __( 'Settings' ) . '</a>', $this->plugin_name ); ?></p>
    </div>
<?php
	}

	// test for an active Fusion Builder PlugIn. This is for later use
	public function check_fusion_builder(){
	
		if( class_exists( 'FusionBuilder' ) ) {
			Svt_simple::get_instance()->log('FB is there');	
		} else {
			Svt_simple::get_instance()->log('FB is NOT there');			
		}
	
	}
	
	// the little icon on the right of the media button
	public function add_panorama_button() {

		global $post_ID, $temp_ID;
	
		$reference_ID                = (int) (0 == $post_ID ? $temp_ID : $post_ID);
		$media_upload_base_url       = "media-upload.php?post_id=" . $reference_ID;
		$svtsimple_upload_iframe_src = apply_filters('media_upload_base_url', $media_upload_base_url . "&amp;type=svtsimple");

		Svt_simple::get_instance()->log('add_panorama_button', [$reference_ID]);

		// https://codex.wordpress.org/Javascript_Reference/ThickBox
		echo "<a href='{$svtsimple_upload_iframe_src}&amp;tab=svtsimple&amp;TB_iframe=true&amp;width=600&amp;height=400' class='thickbox' title='SVT Simple Shortcode Generator'>" .
			"<img src='" . plugins_url('/img/icon.svg',__FILE__) . "' width='20' height='20' style='vertical-align: middle' alt='Add a SVT Panorama' /></a>\n";

	}

	// https://developer.wordpress.org/reference/functions/wp_iframe/
	public function svtsimple_iframe() {
		wp_iframe(array($this,'svt_pegman_dialog'));
	}

	// uses the featerlight lightbox: https://github.com/noelboss/featherlight
	// here is the content of the pegman dialog box
	public function svt_pegman_dialog() {
		$options        = get_option($this->plugin_name);
		$google_api_key = ( isset( $options['google_api_key'] ) && ! empty( $options['google_api_key'] ) ) ? esc_attr( $options['google_api_key'] ) : 'empty';
        $enable_image   = ( isset( $options['enable_image'] ) && ! empty( $options['enable_image'] ) ) ? 1 : 0;

		Svt_simple::get_instance()->log('svt_pegman_dialog', [$google_api_key,$enable_image]);
		
?>
		<div style="padding: 15px;">
			<h3 class="media-title"><?php _e('Select your SVT Panorama', $this->plugin_name); ?></h3>
			<ol>
				<li><?php _e('Search for an address or manually move to the location on the map', $this->plugin_name); ?>.</li>
				<li><?php _e('Drag the pegman', $this->plugin_name); ?> <img style="vertical-align:middle" src="<?php echo plugins_url('/img/pegman.png',__FILE__) ?>" width="16" height="20" /> icon <?php _e('onto a blue dot on the map that represents the panorama your are looking for', $this->plugin_name); ?>.</li>
				<li><?php _e('Select the panorama and orientation (you can walk through the tour and select one of its panoramas looking into the direction you like)', $this->plugin_name); ?>.</li>
				<?php if($enable_image) { ?>
				<li><?php _e('Choose the available options: *Static Image* or *SVT Street View Panorama*, width, height and autorotation on/off', $this->plugin_name); ?>.</li>
				<?php } ?>
				<li><?php _e('Click the *Generate Shortcode* button', $this->plugin_name); ?>.</li>
			</ol>
			<div style="background-color: lightblue; padding: 10px;">
				<div style="padding: 10px;">
					<label for="svt_address"><?php _e('Enter the address', $this->plugin_name); ?>:</label> <input id="svt_address" type="text" name="address" placeholder="<?php _e('Name / Location', $this->plugin_name); ?>" onKeyDown="if(event.keyCode==13) svt_search()" />&nbsp;<input type="submit" name="geocode_button" value="<?php _e('Search', $this->plugin_name); ?>" onclick="svt_search()"/>
				</div>
				<div id="svt_canvas" style="width: 100%; height: 300px"></div>
				<br/>
				<h3><?php _e('Options', $this->plugin_name); ?></h3>
				
				<?php if($enable_image) { ?>
				<select id="panoimage" name="panoimage" onchange="toggleOptions()">
					<option value="pano" selected="selected"><?php _e('SVT Street View Panorama', $this->plugin_name); ?></option>
					<option value="image"><?php _e('Static Image with Lightbox', $this->plugin_name); ?></option>
				</select>
				<?php } else { ?>
				<p><?php _e('SVT Street View Panorama', $this->plugin_name); ?></p>
				<?php } ?>

				<div style="margin-top: 10px;">
					<label for="width"><?php _e('Width', $this->plugin_name); ?>: </label> <input type="text" size="5" required maxlength="5" id="width" value="100%" onkeypress="return isNumberKeyPerc(event)" />
					<label for="height"><?php _e('Height', $this->plugin_name); ?>: </label> <input type="text" size="5" required maxlength="5" id="height" value="480" onkeypress="return isNumberKeyPerc(event)" /><br />
				</div>

				<div id="thumbsize" style="display: none;">
					<label for="width_thumb"><?php _e('Width Thumbnail', $this->plugin_name); ?>: </label> <input type="text" size="4" required maxlength="4" id="width_thumb" value="200" onkeypress="return isNumberKey(event)" />
					<label for="height_thumb"><?php _e('Height Thumbnail', $this->plugin_name); ?>: </label> <input type="text" size="4" required maxlength="4" id="height_thumb" value="125" onkeypress="return isNumberKey(event)" /><br />
				</div>

				<div id="autorotateopt" style="display: block;">
					<p><label for="svt_spin"><?php _e('Autorotate the panorama', $this->plugin_name); ?></label> <input type="checkbox" id="svt_spin" value="spinit" checked><?php _e('Spin', $this->plugin_name); ?><br></p>
				</div>

				<p><button onclick="svt_get_a_pano()"><?php _e('Generate Shortcode', $this->plugin_name); ?></button></p>
			</div>
		</div>

		<!-- Inline script loading due to use in a media uploader modal -->
		<script type="text/javascript" src="//maps.google.com/maps/api/js?key=<?php echo $google_api_key ?>"></script>
		<script type="text/javascript">
			// check input numbers only
			function isNumberKey(evt){
  				var charCode = (evt.which) ? evt.which : event.keyCode;
				return !(charCode > 31 && (charCode < 48 || charCode > 57) );
			}

			function isNumberKeyPerc(evt){
  				var charCode = (evt.which) ? evt.which : event.keyCode;
				return !(charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 37);
			}
			
			// Show/Hide the options
			function toggleOptions() {
				var e = document.getElementById('panoimage');
				if (e.options[e.selectedIndex].value == 'pano') {
					document.getElementById('autorotateopt').style.display = 'block';
					document.getElementById('thumbsize').style.display = 'none';
				}
				else {
					document.getElementById('autorotateopt').style.display = 'none';
					document.getElementById('thumbsize').style.display = 'block';
				}
			}
			
			var map;
			var geocoder;
			
			// draw the basic map
			function svt_initialize() {
				var center = new google.maps.LatLng(50, 7);
				var mapOptions = {
					center: center,
					zoom: 4,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					streetViewControl: true
			  	};
				geocoder = new google.maps.Geocoder();
				map      = new google.maps.Map(document.getElementById("svt_canvas"), mapOptions);
			}

			function svt_search() {
				var address = document.getElementById("svt_address").value;
				geocoder.geocode( { 'address': address}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						// found the address, set the map to the new loc
						map.setCenter(results[0].geometry.location);
						map.setZoom(18);
				  	} else {
						alert("<?php _e('The address search ran into an error: ', $this->plugin_name); ?>" + status);
				  	}
				});
			}			
			
			// generate the shortcode for the currect post
			function svt_get_a_pano() {
				var pano       = map.getStreetView();
				var pov        = pano.getPov();
				var doTwoWidth = false;
				
				if(document.getElementById('svt_spin').checked){
					var doSpin = "1";
				} else {
					var doSpin = "0";
				}

				try {
					if(document.getElementById('panoimage').options[document.getElementById('panoimage').selectedIndex].value == 'image'){
						var isImage = " image=\"1\"";
						var doTwoWidth = true;
					} else {
						var isImage = "";
					}
				}
				catch(err) {
					var isImage = "";
				}
				
				if (pos = pano.getPosition()) {
					var embedcode = "[addmypanorma ";

					if(doTwoWidth){
						// static image
						embedcode += " width=\"" + document.getElementById('width').value + "\"" +
							" height=\"" + document.getElementById('height').value + "\"" +
							" wthumb=\"" + document.getElementById('width_thumb').value + "\"" +
							" hthumb=\"" + document.getElementById('height_thumb').value + "\"" +
							" lat=\"" + pos.lat() + "\"" +
							" lon=\"" + pos.lng() + "\"" +
							" heading=\"" + pov.heading + "\"" +
							" pitch=\"" + pov.pitch + "\"" +
							" zoom=\"" + pov.zoom + "\"" + isImage + "]";
					} else {
						// pano
						embedcode += " width=\"" + document.getElementById('width').value + "\"" +
							" height=\"" + document.getElementById('height').value + "\"" +
							" lat=\"" + pos.lat() + "\"" +
							" lon=\"" + pos.lng() + "\"" +
							" spin=\"" + doSpin + "\"" +
							" heading=\"" + pov.heading + "\"" +
							" pitch=\"" + pov.pitch + "\"" +
							" zoom=\"" + pov.zoom + "\"" + isImage + "]";
					}
					
					top.send_to_editor(embedcode);
				} else {
					alert("<?php _e('Please use the yellow pegman to select a Streetview panorama!', $this->plugin_name); ?>");
				}
			}
			
			svt_initialize();
			
		</script>
<?php
	}
}
