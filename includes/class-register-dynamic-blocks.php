<?php
/**
 * Gb Block register dynamic blocks.
 *
 * @since 1.0.0
 * @package ǧbblock
 */

defined( 'ABSPATH' ) || die;

/**
 * Class Register_Dynamic_Blocks
 */
if ( ! class_exists( 'Register_Dynamic_Blocks' ) ) {

	/**
	 * Class Register_Dynamic_Blocks
	 */
	class Register_Dynamic_Blocks {

		/**
		 * Load on plugin load.
		 *
		 * @since 1.0.0
		 */
		public function load() {

			/**
			 * Filter for register new categories for custom block.
			 *
			 * @since 1.0.0
			 */
			add_filter( 'block_categories_all', array( $this, 'gbblock_custom_block_category' ), 10, 1 );

			/**
			 * Image Block.
			 *
			 * @since 1.0.0
			 */
			$this->gbblock_image_block();

			/**
			 *  Single Article block.
			 *
			 * @since 1.0.0
			 */
			$this->gbblock_single_article_block();

			/**
			 *  Latest Message block.
			 *
			 * @since 1.0.0
			 */
			$this->gbblock_letest_message_block();
		}

		/**
		 * Register new category for custom block.
		 *
		 * @param array $categories Category array.
		 *
		 * @return array
		 * @since 1.0.0
		 */
		public function gbblock_custom_block_category( $categories ) {
			return array_merge(
				array(
					array(
						'slug'  => 'gbblock-blocks',
						'title' => __( 'Gb Blocks', 'gbblock' ),
						'icon'  => 'welcome-add-page',
					),
				),
				$categories
			);
		}


		/**
		 * Image block.
		 *
		 * @since 1.0.0
		 */
		public function gbblock_image_block() {
			require_once  get_stylesheet_directory() . '/includes/blocks/class-gbblock-image.php';
			$gbblock_image_block = new GbBlock_Image_Block();
			$gbblock_image_block->load();
		}

		/**
		 * Single Article block.
		 *
		 * @since 1.0.0
		 */
		public function gbblock_single_article_block() {
			require_once  get_stylesheet_directory() . '/includes/blocks/class-gbblock-single-article.php';
			$gbblock_single_article_block = new GbBlock_Single_Article_Block();
			$gbblock_single_article_block->load();
		}

		/**
		 * Latest Message block.
		 *
		 * @since 1.0.0
		 */
		public function gbblock_letest_message_block() {
			require_once  get_stylesheet_directory() . '/includes/blocks/class-gbblock-latest-message.php';
			$gbblock_latest_message_block = new GbBlock_Latest_Message_Block();
			$gbblock_latest_message_block->load();
		}

	}

}
