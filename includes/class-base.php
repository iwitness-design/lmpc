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

			require_once get_stylesheet_directory() . '/includes/class-include-block-assets.php';

			$gbblock_assets = new Include_Block_Assets();
			$gbblock_assets->load();

		}


	}

}
