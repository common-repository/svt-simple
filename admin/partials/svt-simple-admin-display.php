<?php
/**
 * The settings page for the admin.
 *
 *
 * @link       https://www.business-fotos-koeln.de/detlef
 * @since      1.0.0
 * @package    Svt-simple
 * @subpackage Svt-simple/includes
 * @author     Detlef Beyer <d.beyer@medienkonzepte.de>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) die;

	$options        = get_option($this->plugin_name);
	$google_api_key = ( isset( $options['google_api_key'] ) && ! empty( $options['google_api_key'] ) ) ? esc_attr( $options['google_api_key'] ) : 'a valid Google Maps API key';
	$image_logo_url = plugins_url('../img/icon.svg',__FILE__);
?>
	<script>
	// Callback only used for a simple test of the submitted API Key
    function gm_authFailure() { 
        let apiError = "<?php _e( 'No or invalid API key or daily query limit reached!', $this->plugin_name ); ?>";
        document.getElementById('svtstatus').innerHTML = '<span style="color: #ff0000">' + apiError + '</span>';
        <?php Svt_simple::get_instance()->log('gm_authFailure'); ?>
    }

	// just to test the API key
    function initMap() {
        var map;
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 7,
            center: { lat: 42.345, lng: 12.46 }
        });
    }
    </script>
    
	
	<div id="map" style="height: 1px; width: 1px;"></div>

    <h2><img src="<?php echo $image_logo_url ?>" width="60" height="60" style="vertical-align: middle" alt="SVT Simple - PlugIn" />&nbsp;SVT Simple - PlugIn <?php _e(' Settings', $this->plugin_name); ?></h2>
    <form method="post" name="cleanup_options" action="options.php">
    <?php
        //Grab the SVT options
        $options = get_option($this->plugin_name);
        $google_api_key = ( isset( $options['google_api_key'] ) && ! empty( $options['google_api_key'] ) ) ? esc_attr( $options['google_api_key'] ) : 'a valid Google Maps API key';
        $check_simple   = ( isset( $options['check_simple'] ) && ! empty( $options['check_simple'] ) ) ? 1 : 0;
        $load_async     = ( isset( $options['load_async'] ) && ! empty( $options['load_async'] ) ) ? 0 : 1;
        $switch_log     = ( isset( $options['switch_log'] ) && ! empty( $options['switch_log'] ) ) ? 1 : 0;
        $enable_image   = ( isset( $options['enable_image'] ) && ! empty( $options['enable_image'] ) ) ? 1 : 0;

		Svt_simple::get_instance()->log('admin page', [$options]);

        settings_fields($this->plugin_name);
        do_settings_sections($this->plugin_name);
    ?>

    <!-- Text -->
    <p><?php _e('You need a valid API key from Google to use the Google panoramas on your site', $this->plugin_name); ?>. <a href="https://developers.google.com/maps/documentation/streetview/get-api-key" target="_blank"><?php _e('Get an API key', $this->plugin_name); ?></a> </p>
    <p><?php _e('You have to enable the Maps JavaScript API, the Geocoding API and the Street View API in your', $this->plugin_name); ?> <a href="https://console.cloud.google.com/apis/library" target="_blank"><?php _e('Google API Library', $this->plugin_name); ?></a>.</p>
        <p><?php _e('If you like you can support my efforts and the development of the plugin by donating with ', $this->plugin_name); ?> <a href="https://paypal.me/pojamapeoples" target="_blank"><?php _e('PayPal', $this->plugin_name); ?></a>.</p>

	<hr style="width:80%">

	<div style="background-color: lightblue; padding: 10px; margin-right: 20px;">
    <fieldset>
        <h2><?php _e( 'Your Google Maps JS API Key.', $this->plugin_name ); ?></h2>
        <legend class="screen-reader-text">
            <span><?php _e( 'Your Google Maps JS API Key', $this->plugin_name ); ?></span>
        </legend>
        <input type="text" size="40" class="example_text" id="<?php echo $this->plugin_name; ?>-google_api_key" name="<?php echo $this->plugin_name; ?>[google_api_key]" value="<?php if( ! empty( $google_api_key ) ) echo $google_api_key; else echo 'a valid Google Maps API key'; ?>"/> <span style="font-weight: bold;" id="svtstatus"><?php _e( 'API Key seems to be OK', $this->plugin_name ); ?></span>
    </fieldset>
		
	<hr>

     <!-- Enable static images -->
    <fieldset>
        <legend class="screen-reader-text">
            <span><?php _e( 'Enable static preview images', $this->plugin_name ); ?></span>
        </legend>
        <label for="<?php echo $this->plugin_name; ?>-enable_image">
            <input type="checkbox" id="<?php echo $this->plugin_name; ?>-enable_image" name="<?php echo $this->plugin_name; ?>[enable_image]" value="1" <?php checked( $enable_image, 1 ); ?> />
            <span style="font-weight: bold;"><?php esc_attr_e('Enable static preview images', $this->plugin_name); ?></span>
        </label>
    </fieldset>
    <p><?php _e( 'If this option is on, you can insert static images of your panos into the posts and pages', $this->plugin_name ); ?>!</p>
    <p><?php _e( 'Each image opens a lightbox layer with the interactive panorama if clicked', $this->plugin_name ); ?>.</p>
    <p><?php _e( 'Therefore we have to load additional JS files. So use this option only if needed', $this->plugin_name ); ?>.</p>
    <p><?php _e( 'Static Street View images can be returned in any size up to 640 x 640 pixels', $this->plugin_name ); ?>.</p>
    <p><?php _e( 'Google Maps APIs Premium Plan customers can request images up to 2048 x 2048 pixels', $this->plugin_name ); ?>.</p>

	<hr style="width:50%">

   <!-- Checkbox load Maps on demand-->
    <fieldset>
        <legend class="screen-reader-text">
            <span><?php _e( 'Load Maps API only when needed', $this->plugin_name ); ?></span>
        </legend>
        <label for="<?php echo $this->plugin_name; ?>-check_simple">
            <input type="checkbox" id="<?php echo $this->plugin_name; ?>-check_simple" name="<?php echo $this->plugin_name; ?>[check_simple]" value="1" <?php checked( $check_simple, 1 ); ?> />
            <span style="font-weight: bold;"><?php esc_attr_e('Load Maps API only when needed', $this->plugin_name); ?></span>
        </label>
    </fieldset>
    <p><?php _e( 'Whenever you change any of the options on this page and you use a caching plugin: clear the cache', $this->plugin_name ); ?>!</p>
    <p><?php _e( 'Some themes will not support the dynamic load of the Google Maps API only on those posts where you used the shortcode', $this->plugin_name ); ?>. <?php _e( 'If the panos do not show up, uncheck this option', $this->plugin_name ); ?>:</p>

	<hr style="width:50%">

    <!-- Checkbox JS async -->
    <fieldset>
        <legend class="screen-reader-text">
            <span><?php _e( 'Load Javascript asynchronous', $this->plugin_name ); ?></span>
        </legend>
        <label for="<?php echo $this->plugin_name; ?>-load_async">
            <input type="checkbox" id="<?php echo $this->plugin_name; ?>-load_async" name="<?php echo $this->plugin_name; ?>[load_async]" value="1" <?php checked( $load_async, 1 ); ?> />
            <span style="font-weight: bold;"><?php esc_attr_e('Load Javascript asynchronous', $this->plugin_name); ?></span>
        </label>
    </fieldset>
    <p><?php _e( 'You can load parts of the needed scrips asynchronous. This may bring problems with the panos so use it with caution', $this->plugin_name ); ?>.</p>
    <p><?php _e( 'If the panos do not show up, uncheck this option', $this->plugin_name ); ?>.</p>

	<hr style="width:50%">

    <!-- Checkbox Logfile -->
    <fieldset>
        <legend class="screen-reader-text">
            <span><?php _e( 'Switch on Logfile', $this->plugin_name ); ?></span>
        </legend>
        <label for="<?php echo $this->plugin_name; ?>-switch_log">
            <input type="checkbox" id="<?php echo $this->plugin_name; ?>-switch_log" name="<?php echo $this->plugin_name; ?>[switch_log]" value="1" <?php checked( $switch_log, 1 ); ?> />
            <span style="font-weight: bold;"><?php esc_attr_e('Switch on the Logfile', $this->plugin_name); ?></span>
        </label>
    </fieldset>
    <p><?php _e( 'If you run into problems a logfile may be helpful', $this->plugin_name ); ?>. <?php _e( 'You will find the log in /wp-content/uploads/svtsimple-logs/', $this->plugin_name ); ?>.</p>

    <?php submit_button( __( 'Save all changes', $this->plugin_name ), 'primary','submit', TRUE ); ?>
    </div>
    </form>
    <hr>
    <p><?php _e('Google will give you some free traffic to use the maps API. Calcuate the costs with this', $this->plugin_name); ?>
    <a href="https://mapsplatformtransition.withgoogle.com/calculator" target="_blank"><?php _e('Google Pricing Calculator', $this->plugin_name); ?></a>.</p>
    <p><?php _e('More information about the pricing structure by Google can be found', $this->plugin_name); ?> <a href="https://developers.google.com/maps/premium/usage-limits" target="_blank"><?php _e('here', $this->plugin_name); ?></a></p>
	<hr>
	<p><?php _e('SVT Simple brought to you by', $this->plugin_name); ?> <a href="https://www.business-fotos-koeln.de/svt-simple/">www.business-fotos-koeln.de</a></p>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo($google_api_key); ?>&callback=initMap"></script>
