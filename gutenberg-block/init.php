<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * Assets enqueued:
 * 1. blocks.style.build.css - Frontend + Backend.
 * 2. blocks.build.js - Backend.
 * 3. blocks.editor.build.css - Backend.
 *
 * @uses  {wp-blocks} for block type registration & related functions.
 * @uses  {wp-element} for WP Element abstraction — structure of blocks.
 * @uses  {wp-i18n} to internationalize the block's text.
 * @uses  {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
function raise_donors_gutenberg_block() {
	wp_register_script(
		'raise-donors-gutenberg-block',
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ),
		[ 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-server-side-render' ],
		null,
		true
	);

	wp_register_style(
		'raise-donors-gutenberg-block-editor',
		plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ),
		[ 'wp-edit-blocks' ],
		null
	);

	wp_localize_script(
		'raise-donors-gutenberg-block-editor',
		'rdg', // Array containing dynamic data for a JS Global.
		[
			'pluginDirPath' => plugin_dir_path( __DIR__ ),
			'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
		]
	);

	/**
	 * Register Gutenberg block on server-side.
	 *
	 * Register the block on server-side to ensure that the block
	 * scripts and styles for both frontend and backend are
	 * enqueued when the editor loads.
	 *
	 * @link  https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
	 * @since 1.16.0
	 */
	register_block_type(
		'raise-donors/gutenberg-block', array(
			// Enqueue blocks.style.build.css on both frontend & backend.
			'style'           => 'raise-donors-gutenberg-block',
			// Enqueue blocks.build.js in the editor only.
			'editor_script'   => 'raise-donors-gutenberg-block',
			// Enqueue blocks.editor.build.css in the editor only.
			'editor_style'    => 'raise-donors-gutenberg-block-editor',
			'render_callback' => 'render_server_side_gutenberg_block',
		)
	);

	register_block_type(
		'raise-donors/server-side-gutenberg-block',
		[
			'attributes'      => [
				'selectedCampaign'    => [
					'type'    => 'object',
					'default' => [],
				],
				'selectedDisplayType' => [
					'type'    => 'string',
					'default' => 'full',
				],

			],
			'render_callback' => 'render_server_side_gutenberg_block',
		]
	);
}

// Hook: Block assets.
add_action( 'init', 'raise_donors_gutenberg_block' );

function render_server_side_gutenberg_block( $block_attributes, $content ) {
	return do_shortcode( '[raise-donors campaignId="' . $block_attributes['selectedCampaign']['value'] . '" display="' . $block_attributes['selectedDisplayType'] . '"]' );
}
