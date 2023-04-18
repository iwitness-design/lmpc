<?php
/**
 * Gb Block register Articles By Category block.
 *
 * @since 1.0.0
 * @package gbblock
 */

defined( 'ABSPATH' ) || die;

/**
 * Class GbBlocl single_article_block
 */
if ( ! class_exists( 'GbBlock_Staff_Block' ) ) {

	/**
	 * Class GbBlock_Staff_Block
	 */
	class GbBlock_Staff_Block {


		/**
		 * Load on plugin load.
		 *
		 * @since 1.0.0
		 */
		public function load() {

			/**
			 *  Action to register dynamic block.
			 */
			add_action( 'init', array( $this, 'gbblock_register_single_article_block' ) );

			/**
			 * Register new rest route for post list by category.
			 */
			// add_action( 'rest_api_init', array( $this, 'gbblock_posts_by_category_register_api_endpoints' ) );

		}

		/**
		 * Register GbBlock Articles By Category block.
		 *
		 * @since 1.0.0
		 */
		public function gbblock_register_single_article_block() {

			register_block_type(
				'lmpc/staff',
				array(
					'attributes'      => array(
						'postsPerPage' => array(
              'type' => 'number',
              'default' => 5
            )
					),
					'render_callback' => array( $this, 'gbblock_staff_block_render_callback' ),
				)
			);

		}

		/**
		 * Render gbblock Articles by Category block.
		 *
		 * @param attributes $attributes block attributes.
		 *
		 * @return string $html
		 * @since 1.0.0
		 */
		public function gbblock_staff_block_render_callback( $attributes ) {

			$posts_per_page = absint( $attributes['postsPerPage'] );

      $query = new WP_Query(array(
        'post_type' => 'cp_staff',
        'posts_per_page' => $posts_per_page
      ));

			ob_start();

      while( $query->have_posts() ) :
        $query->the_post();

        ?>
        <h1><?php echo $query->get_the_title() ?></h1>
        <?php
      endwhile;
			
			$html = ob_get_contents();
			ob_end_clean();
      
			return $html;
		}

		/**
		 * Register custom api endpoints to fetch posts by categoris.
		 *
		 * @since 1.0.0
		 */
		public function gbblock_posts_by_category_register_api_endpoints() {
			register_rest_route(
				'gbblock_api',
				'/request/get_posts',
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'gbblock_get_posts_by_terms' ),
					'permission_callback' => '__return_true',
				)
			);

			register_rest_route(
				'gbblock_api',
				'/request/get_terms',
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'gbblock_get_all_termss' ),
					'permission_callback' => '__return_true',
				)
			);

		}

		/**
		 * List of posts by categories.
		 *
		 * @param data $data rest API parameter.
		 *
		 * @since 1.0.0
		 */
		public function gbblock_get_posts_by_terms( $data ) {

			$cat_ID = $data['cat_id'];

			$result  = array();
			$args    = array(
				'posts_per_page' => 99,
				'category'       => $cat_ID,
				'orderby'        => 'post_date',
				'order'          => 'DESC',
				'post_type'      => GBBLOCK_CONTENT_TYPE,
				'post_status'    => 'publish',
			);
			$myposts = new WP_Query( $args );

			if ( ! empty( $myposts ) && 0 < $myposts->found_posts ) {
				foreach ( $myposts->posts as $key => $myposts ) {
					$result[ $key ]['id']    = $myposts->ID;
					$result[ $key ]['title'] = html_entity_decode( $myposts->post_title );
				}
			}
			return new WP_REST_Response( $result, 200 );
		}

		/**
		 * List of categories.
		 *
		 * @since 1.0.0
		 */
		public function gbblock_get_all_termss() {
			$result = array();

			$term_args  = array(
				'taxonomy'   => GBBLOCK_CONTENT_TAXONOMY,
				'hide_empty' => false,
				'order'      => 'ASC',
			);
			$terms_list = get_terms( $term_args );
			if ( ! empty( $terms_list ) && ! is_wp_error( $terms_list ) ) {
				foreach ( $terms_list as $key => $terms_list ) {
					$result[ $key ]['id']    = $terms_list->term_id;
					$result[ $key ]['title'] = html_entity_decode( $terms_list->name );
				}
			}
			return new WP_REST_Response( $result, 200 );
		}

	}

}
