<?php
/**
 * Ask Media register Image block.
 *
 * @since 1.0.0
 * @package gbblock
 */

defined( 'ABSPATH' ) || die;

/**
 * Class GbBlock Image_Block
 */
if ( ! class_exists( 'GbBlock_Image_Block' ) ) {

	/**
	 * Class GbBlock_Image_Block
	 */
	class GbBlock_Image_Block {


		/**
		 * Load on plugin load.
		 *
		 * @since 1.0.0
		 */
		public function load() {

			/**
			 *  Action to register dynamic block.
			 */
			add_action( 'init', array( $this, 'gbblock_register_image_block' ) );

		}

		/**
		 * Register Gb Block Image block.
		 *
		 * @since 1.0.0
		 */
		public function gbblock_register_image_block() {
			register_block_type(
				'gbblock/image',
				array(
					'attributes'      => array(
						'gbblockImage' => array(
							'type'    => 'string',
							'default' => '',
						),
						'caption'       => array(
							'type'    => 'string',
							'default' => '',
						),
					),
					'render_callback' => array( $this, 'gbblock_image_block_render_callback' ),
				)
			);

		}

		/**
		 * Render Gbblock Image block.
		 *
		 * @param attributes $attributes block attributes.
		 *
		 * @return string $html
		 * @since 1.0.0
		 */
		public function gbblock_image_block_render_callback( $attributes ) {

			$gbblock_image = isset( $attributes['gbblockImage'] ) && ! empty( $attributes['gbblockImage'] ) ? $attributes['gbblockImage'] : '';
			$image_caption  = isset( $attributes['caption'] ) && ! empty( $attributes['caption'] ) ? $attributes['caption'] : '';

			ob_start();
			if ( ! empty( $gbblock_image ) ) {
				?>
					<div class="gbblock-image-inner">
						<img alt="<?php echo esc_attr( $image_caption ); ?>" src="<?php echo esc_url( $gbblock_image ); ?>" class="gbblock-image"  />
						<span class="image-caption"><?php echo esc_html( $image_caption ); ?></span>
					</div>
					<?php
			} else {
				if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
					?>
						<div class="select-image"></div>
					<?php
				}
			}

			$html = ob_get_clean();
			return $html;

		}
	}

}
