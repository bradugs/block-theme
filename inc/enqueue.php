<?php
/**
 * Enqueue scripts and styles.
 *
 * @package Tangent
 */

namespace Tangent\Enqueue;

/**
 * Enqueue the `global.css` file.
 *
 * The `global.css` file is enqueued on all pages and is used for global styles.
 *
 * @return void
 */
function global_theme_styles() {
	$theme_version = wp_get_theme()->get( 'Version' );
	$css_version   = $theme_version . '.' . filemtime( get_template_directory() . '/css/global.css' );

	wp_enqueue_style( 'tangent-global-theme-style', get_template_directory_uri() . '/css/global.css', array(), $css_version );
}
add_action( 'wp_enqueue_scripts', 'Tangent\Enqueue\global_theme_styles' );

/**
 * Enqueue the `scripts.js` file and associated dependencies.
 *
 * The `scripts.js` file is enqueued on all pages and is used for global scripts.
 *
 * @return void
 */
function front_end_scripts() {

	$asset_file   = include get_template_directory() . '/js/scripts.asset.php';
	$dependencies = $asset_file['dependencies'];

	wp_enqueue_script( 'tangent-front-end-scripts', get_template_directory_uri() . '/js/scripts.js', $dependencies, $asset_file['version'], true );
}
add_action( 'wp_enqueue_scripts', 'Tangent\Enqueue\front_end_scripts' );

/**
 * Enqueue editor specific modifications in the Post Editor, Site Editor,
 * and Widgets Editor.
 *
 * This function enqueues the `editor.js` file found in `/src/js` and
 * adds extra dependencies depending on the current screen (post, widgets, site-editor).
 *
 * @return void
 */
function enqueue_editor_modifications() {
	$asset_file   = include get_template_directory() . '/js/editor.asset.php';
	$dependencies = $asset_file['dependencies'];

	// Add extra dependencies depending on the current screen.
	$screen = get_current_screen();
	switch ( $screen->base ) {
		case 'post':
			$dependencies[] = 'wp-edit-post';
			break;

		case 'widgets':
			$dependencies[] = 'wp-edit-widgets';
			break;

		case 'site-editor':
			$dependencies[] = 'wp-edit-site';
			break;
	}

	wp_enqueue_script( 'tangent-editor-modifications', get_template_directory_uri() . '/js/editor.js', $dependencies, $asset_file['version'], true );
}

add_action( 'enqueue_block_editor_assets', 'Tangent\Enqueue\enqueue_editor_modifications' );

/**
 * Enqueue the `comment-reply` script if threaded comments are enabled.
 *
 * @return void
 */
function enqueue_threaded_comment_reply() {
	if ( get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'comment_form_before', 'Tangent\Enqueue\enqueue_threaded_comment_reply' );

/**
 * Enqueue the `editor.css` file in the Block Editor.
 *
 * @return void
 */
function add_editor_styles() {
	add_editor_style( 'css/editor.css' );
}
add_action( 'after_setup_theme', 'Tangent\Enqueue\add_editor_styles' );
