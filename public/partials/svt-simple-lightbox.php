<!DOCTYPE html> 
<?php

/**
 * The lightbox for the svt_simple plugin
 *
 *
 * @link       https://www.business-fotos-koeln.de/detlef
 * @since      1.0.0
 *
 * @package    Svt-simple
 * @subpackage Svt-simple/public/partials
 */

	$key = filter_var ( $_GET["key"], FILTER_SANITIZE_EMAIL);
?>
<html>
<head>
	<meta content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" name="viewport">
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $key ?>&callback=initMap"></script>
    <title>SVT Simple</title>
    <style type="text/css">
        html, body { margin: 0; padding: 0; width: 100%; height: 100%; background: #000; }
        #svt_simple_pano { width: 100%; height: 100%; }
    </style>
</head>
<body>
    <div id="svt_simple_pano"></div>
    
    
    <script type='text/javascript'>
		console.log("Start");
    
        function initMap() {
			var panoLocation = new google.maps.LatLng(<?php echo $_GET["lat"]; ?>, <?php echo $_GET["lon"]; ?>);
			console.log("LOC Lat:" + <?php echo $_GET["lat"]; ?> + ", Lon:" + <?php echo $_GET["lon"]; ?> + ", Zoom:" + <?php echo $_GET["theZoom"]; ?>);

			var panoramaOptions = {
				position: panoLocation,
				addressControl:false,
				fullscreenControl:false,
				enableCloseButton: false,
				zoom: <?php echo $_GET["theZoom"]; ?>,
				pov: {
					heading: <?php echo $_GET["theHeading"]; ?>,
					pitch:   <?php echo $_GET["thePitch"]; ?>
				}
			};
	
			var svt_simple_pano = new google.maps.StreetViewPanorama(document.getElementById('svt_simple_pano'), panoramaOptions);
	    }
    </script>
</body>
</html>