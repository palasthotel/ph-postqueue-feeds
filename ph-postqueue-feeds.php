<?php

/**
 * RSS Feeds for PALASTHOTEL Postqueues
 *
 *
 * @wordpress-plugin
 * Plugin Name:       PALASTHOTEL Postqueue Feeds
 * Description:       RSS Feeds for PALASTHOTEL Postqueues
 * Version:           1.0
 * Author:            PALASHOTEL by Edward Bock
 * Author URI:        http://palasthotel.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ph-postqueue-feeds
 * Domain Path:       /languages
 */

/**
 * adds wordpress query vars
 */
function ph_postqueue_feeds_query_vars($vars){
	$vars[] = '__ph_postqueue_feed';
	$vars[] = '__ph_postqueue_feed_slug';
	return $vars;
}
add_filter("query_vars", "ph_postqueue_feeds_query_vars");

/** 
 *	Feed url
 */
function ph_postqueue_feeds_add_url() {
	$feed_url = get_site_option("ph-postqueue-feeds-url", "postqueue-feed");
	add_rewrite_rule(
		'^'.$feed_url.'/([^/]*)/?',
		'index.php?__ph_postqueue_feed=1&__ph_postqueue_feed_slug=$matches[1]',
		'top'
	);
}
add_action("init", "ph_postqueue_feeds_add_url" );

/**	
 * Parse Requests
 */
function ph_postqueue_feeds_parse_requests() {
	global $wp;
	if( isset($wp->query_vars['__ph_postqueue_feed']) 
		&& isset($wp->query_vars['__ph_postqueue_feed_slug']) 
		&& class_exists("PH_Postqueue_Store") )
	{
		ph_postqueue_feeds_render( sanitize_text_field($wp->query_vars['__ph_postqueue_feed_slug']) );
		exit;
	}
}
add_action("parse_request", "ph_postqueue_feeds_parse_requests");

/**
 * render postqueue as feed
 */
function ph_postqueue_feeds_render($slug){
	$posts = array();
	$store = new PH_Postqueue_Store();
	$posts = $store->get_queue_by_slug($slug);
	if(count($posts) > 0){
		$queue_name = $posts[0]->name;
		include dirname(__FILE__)."/feed.tpl.php";
	} else {
		global $wp_query;
	      $wp_query->is_404 = true;
	      $wp_query->is_single = false;
	      $wp_query->is_page = false;
	      include( get_query_template( '404' ) );
	}
	
}

/**
 * on activation flush rewrite rules
 */
function ph_postqueue_feeds_activate() {
	ph_postqueue_feeds_add_url();
	flush_rewrite_rules();
	update_site_option("ph-postqueue-feeds-url", "postqueue-feed");
}
register_activation_hook( __FILE__, 'ph_postqueue_feeds_activate' );

/**
 * on deactivation flush rewrite rules
 */
function ph_postqueue_feeds_deactivate() {
	delete_site_option("ph-postqueue-feeds-url");
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ph_postqueue_feeds_deactivate' );


?>