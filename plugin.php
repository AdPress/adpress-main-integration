<?php
/*
  Plugin Name: Deploy Integration Plugin 
  Plugin URI: http://wpadpress.com
  Description: Integration Plugin for Deployment 
  Author: Abid Omar
  Version: 1.0.0
 */


// Don't load directly
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}

///
// Validation Functions
///
add_action( 'init', 'wp_adpress_license_validator_post' );
function wp_adpress_license_validator_post() {	
	if ( isset( $_POST['adpress_validator'] ) && isset( $_POST['envato_username'] ) && isset( $_POST['envato_key'] ) ) {
		$envato_username = $_POST['envato_username'];
		$envato_key = $_POST['envato_key'];

		$request = wp_remote_get( 'http://marketplace.envato.com/api/edge/omarabid/1xxpthnit66sjq3bxvl76ly3j0r79syd/verify-purchase:' . $envato_key . '.json' );

		// Check that the response is valid
		if ( is_wp_error( $request ) ) {
			echo 0;
			exit;
		}

		// Decode the response
		$result = json_decode( $request['body'], ARRAY_A );

		// Check the license
		if ( isset( $result['verify-purchase'] ) ) {
			$buyer = $result['verify-purchase']['buyer'];
			if ( strtolower( $buyer ) === strtolower( $envato_username ) ) {
				echo 1;
				exit;
			}
			echo 0;
			exit;
		}
		echo 0;
		exit;
	}
}

add_action( 'init', 'wp_adpress_update_validator' );
function wp_adpress_update_validator() {

}

///
// Remove Emojis
///
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );   
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );     
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );


///
// Pages Categories and Tags
///
require_once( 'page-cat.php' );
