<?php
 /*
   Plugin Name: DC Forms
   Plugin URI: http://docscloud.io
   description: A plugin for docscloud forms embed in wordpress
   Version: 1.0.0
   Author: Rise & Inspire Techlabs LLP
   Author URI: #
 */

// Create a new table
function docscloud_table(){
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();
	$tablename = $wpdb->prefix."docscloud";
	$sql = "CREATE TABLE $tablename (
	  id mediumint(11) NOT NULL AUTO_INCREMENT,
	  auth_id varchar(255) NOT NULL,
	  auth_token varchar(255) NOT NULL,
	  PRIMARY KEY  (id)
	) $charset_collate;";

	$tablename1 = $wpdb->prefix."dcforms";
	$sql1 = "CREATE TABLE $tablename1 (
	id mediumint(11) NOT NULL AUTO_INCREMENT,
	form_id mediumint(11) NOT NULL,
	form_name varchar(255) NOT NULL,
	form_code varchar(255) NOT NULL,
	form_url varchar(255) NOT NULL,
	form_embded_code text NOT NULL,
	short_code varchar(255) NULL,
	PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	dbDelta( $sql1 );
	
}
register_activation_hook( __FILE__, 'docscloud_table' );

// Add menu
function docscloud_menu_n() {

    add_menu_page("Docscloud", "Docscloud","manage_options", "myplugin", "docscloud_displayList_n",plugins_url('/docsclouds/img/icon.png'));
    add_submenu_page("myplugin","Auth List", "Auth List","manage_options", "authdetails", "docscloud_displayList_n");
    add_submenu_page("myplugin","Add Auth Details", "Add Auth Details","manage_options", "addauth", "docscloud_addEntry_n");
	add_submenu_page("myplugin","Form List", "Form List","manage_options", "formlist", "docscloud_formList_n");
   	
}

add_action("admin_menu", "docscloud_menu_n");
if (!function_exists('docscloud_displayList_n')) {
	function docscloud_displayList_n(){
		include "displaylist.php";
	}
}

if (!function_exists('docscloud_addEntry_n')) {
	function docscloud_addEntry_n(){
		include "addentry.php";
	}
}

if (!function_exists('docscloud_formList_n')) {
	function docscloud_formList_n(){
		include "viewformlist.php";
	}
}

if (!function_exists('docscloud_getFormList_func')) {
	function docscloud_getFormList_func( $atts ) {

		$url = 'https://app.docscloud.io/f/embed/'.$atts['form_code'];
		wp_enqueue_style( 'wpdocs-bootstrap-style', 'https://app.docscloud.io/css/bootstrap.min.css' );
		$embed_url = '<div id="wrapper" class="embed-responsive embed-responsive-1by1" style="width: 100%; height: 100%; min-height: 100vh;"><embed class="embed-responsive-item" src="'.$url.'" style="width: 100%; height: 100%; min-height: 100vh;"></embed></div>';
		return $embed_url;
	}
}
add_shortcode( 'docscloud_form', 'docscloud_getFormList_func' );