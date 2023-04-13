<?php
/**
 * Gb Block Base Class.
 *
 * @since 1.0.0
 * @package gbblock
 */

defined( 'ABSPATH' ) || die;

if ( ! class_exists( 'Base' ) ) {

	/**
	 * Base Class.
	 *
	 * @since 1.0.0
	 */
	class Base {

		/**
		 * Init function.
		 *
		 * @since 1.0.0
		 */
		public function gbblock_init() {
			
			add_filter( 'use_block_editor_for_post', array( $this, 'gbblock_maybe_load_gutenberg_for_post_type' ), 15, 2 );

			/**
			 * Enqueue Assets.
			 */
			$this->gbblock_include_assets();

			/**
			 * Register Dynamic Blocks.
			 */
			$this->gbblock_register_dynamic_blocks();

			
		}

		/**
		 * Load Gutenberg Block Editor for specif post type.
		 *
		 * @param bool   $can_edit
		 * @param object $post
		 *
		 * @return void
		 */
		public function gbblock_maybe_load_gutenberg_for_post_type( $can_edit, $post ) {
			$enable_for_post_types = array( 'post', 'page' );
			if ( in_array( $post->post_type, $enable_for_post_types, true ) ) {
					return true;
			}
			return false;
		}

		/**
		 * Register Dynamic Blocks.
		 *
		 * @since 1.0.0
		 */
		public function gbblock_register_dynamic_blocks() {

			require_once get_stylesheet_directory() . '/includes/class-register-dynamic-blocks.php';

			$gbblock_dynamic_blocks = new Register_Dynamic_Blocks();
			$gbblock_dynamic_blocks->load();

		}

		/**
		 * Enqueue Assets.
		 *
		 * @since 1.0.0
		 */
		public function gbblock_include_assets() {

			require_once get_stylesheet_directory() . '/includes/class-include-assets.php';

			$gbblock_assets = new Include_Assets();
			$gbblock_assets->load();

		}


	}

}
