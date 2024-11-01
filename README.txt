=== SVT Simple ===
Contributors: pojamapeoples
Donate link: https://paypal.me/pojamapeoples
Tags: svt, google, maps, google maps, street, view, street view, streetview, panorama, photosphere, 360, VR
Requires at least: 4.0.0
Tested up to: 5.0.0
Requires PHP: 5.2
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Plugin URI: https://www.business-fotos-koeln.de/svt-simple/
Author URI: https://www.business-fotos-koeln.de/detlef/

Use the SVT Simple Plugin to embed any Street View panorama from Google into your Wordpress site. Add the pano as an interactive element (with autorotation) or as a static image.


== Description ==
Important: if you use the PlugIn with Wordpress version 5.0.x you have to use the classic editor. Currently there is no support for the new Block Editor. I still wait for a useful documentation to integrate the PlugIn into the Block Editor. If you use the classic editor the PlugIn works fine with Wordpress 5.0

SVT Simple is the perfect solution to add any Google Street View Panorama to your posts and pages. SVT Simple is very easy to use and should add a minimal footprint to your Wordpress site. The PlugIn was developed by a Street View Trusted photographer and should help others to improve the use of those beautiful panoramas.

Instead of entering the shortcode manually, SVT Simple offers an interactive map to find the panorama you are looking for. The easy to use drag & drop interface allows you to enter all options and will generate the ready to use shortcode for you. Therefor SVT Simple adds an icon next to the "Add Media" button and above the WP default editor. Press this icon to open a dialog with the interactive map and a search box. You can search for an address or use the map to navigate to the panorama you like to integrate into your site.

SVT Simple offers two options to embed any Street View Pano into your Wordpress site:
* as an interactive panorama with a size you specify
* as a static image with a maximum size of 640x640 pixel (that's a limitation by Google)

Since it’s released under the GPL, you can use it free of charge on your personal or commercial site - but if you want you can support my efforts by donating with PayPal https://paypal.me/pojamapeoples

**KEY FEATURES**
* Minimal footage for the plugin
* Easily embed any Google Street View panorama into your posts or pages
* Search for an address or object to locate a panorama
* Interactive Drag & Drop interface to select the panorama and orientation
* Autorotation as an option to add a smooth rotation effect to the panoramas
* Static Images created from the panorama in any size (up to 640px). With a Lightbox attached that will open the interactive panorama
* Based on the latest Google API
* Many options to fine-tune the integration of the plugin into you Wordpress site
* Multi language support

The interactive panorama can be set to autorotate. This is a very attractive way to present your panorama. Even when the autorotation is on, the user still can interact with the panorama.

The static image comes with a Lightbox that opens if the user clicks the image. You can give the Lightbox any dimension you like.

SVT Simple has a small footprint and offers several options to optimise the way it will be integrated into your site.

SVT Simple supports multiple languages and comes with an English and German language file. More will be added later.

The Lightbox part was created using the wonderful Featherlight library. See http://noelboss.github.io/featherlight/ for more info about it. Because of this library SVT Simple requires jQuery version 1.7.0 or higher. If you switch off the static image option on the settings page no jQuery will be needed.

The SVT Simple plugin is in no way related to Google itself. It was made by an independent developer (myself) and I'm not related to Google in any way.

If you have suggestions for a new add-on, feel free to email me at d.beyer@business-fotos-koeln.de.

== Frequently Asked Questions ==

**Q: Do I need a Google API key to use the plugin?
Yes! That's the only way to include the Google Maps API into you site. It is easy to get one here https://developers.google.com/maps/documentation/javascript/get-api-key

**Q: Can I add multiple panoramas to a single post or page?
Yes. You can add as many panoramas as you like with SVT Simple. The more Google Street View stuff you put into your pages the more it will slow down the loading of your page. The static images may help because they load parts of the needed images only on demand.

**Q: Can I use the shortcode within other plugins like a slider?
Yes. This should work. I tested it for example with the Slider Revolution plugin without an issue. But be aware that this may generate a slow down of you site if there are several panos to be loaded. Generate the shortcode in a post or page and copy the resulting shortcode to your target area.

**Q: Will the plugin work with Fusion Builder themes?
Yes. But you have to manually add the shortcode to the page/post. A full integration into Fusion Builder will be added to future version.

**Q: There are several options on the settings page and I don't understand in detail what they do?
All options help to reduce the footprint of the plugin. If the panos don't show up on your pages or if you have doubts about using them: switch them off. That's the easiest way to ensure the plugin will work.

**Q: How much will Google charge for using the Maps API?
You will find a link to a calculator on the SVT Simple Options page. Currently Google offers a relatively high amount of free calls to the API. The SVT Simple plugin itself does not charge anything.

== Installation ==

1. Unpack the ZIP file
2. Upload the folder 'svt-simple' to the '/wp-content/plugins/' directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Enter a valid Google Maps API Key in the "SVT Simple Options" page in your settings menu


== Screenshots ==

1. Use an interactive map to select the panorama. Just drop the pegman icon on the blue dot that represents your panorama.
2. SVT Simple will create a shortcode that embeds an interactive Google Street View panorama into your pages and posts.
3. Search for an address or object and navigate to the panorama you'd like to include
4. There is a bunch of options to optimise the integration of the panos into your site
5. To open the interactive map click an the new icon on the left of the *add media* button

== Changelog ==

= 1.0.0 =
First release

= 1.0.1 =
Update checked with Wordpress 5.0. Only minor changes.