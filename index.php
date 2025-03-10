<?php
/**
 * Plugin Name: LastFM Played for WordPress
 * Plugin URI: https://onemaggie.com
 * Description: Clean and simple recently played Last.FM Plugin for WordPress
 * Version: 0.99.2
 * Author: Maggie Cabrera
 * Author URI: https://onemaggie.com
 * License: MIT
 *
 * @package LastFM_Block
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include the widget class.
require_once plugin_dir_path( __FILE__ ) . 'includes/class-lastfm-widget.php';

/**
 * Initialize the widget
 */
function lastfm_load_widget() {
	register_widget( 'LastFM_Widget' );
}
add_action( 'widgets_init', 'lastfm_load_widget' );

/**
 * Enqueue styles for both widget and block
 */
function lastfm_style() {
	wp_enqueue_style( 'lastfm_style', plugins_url( 'style.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'lastfm_style' );
add_action( 'enqueue_block_editor_assets', 'lastfm_style' );

/**
 * Initialize the block
 */
function lastfm_block_init() {
	// Register the block using the metadata loaded from the `block.json` file.
	register_block_type( 
		__DIR__ . '/blocks/lastfm-block',
		array(
			'render_callback' => 'render_lastfm_block',
		)
	);
}
add_action( 'init', 'lastfm_block_init' );

// Include the block render function.
require_once plugin_dir_path( __FILE__ ) . 'blocks/lastfm-block/render.php';
