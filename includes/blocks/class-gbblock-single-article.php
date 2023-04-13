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
if ( ! class_exists( 'GbBlock_Single_Article_Block' ) ) {

	/**
	 * Class GbBlock_Single_Article_Block
	 */
	class GbBlock_Single_Article_Block {


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
			add_action( 'rest_api_init', array( $this, 'gbblock_posts_by_category_register_api_endpoints' ) );

		}

		/**
		 * Register GbBlock Articles By Category block.
		 *
		 * @since 1.0.0
		 */
		public function gbblock_register_single_article_block() {

			register_block_type(
				'gbblock/single-article',
				array(
					'attributes'      => array(
						'latestArticle'   => array(
							'type'    => 'boolean',
							'default' => true,
						),
						'displayCategory' => array(
							'type'    => 'boolean',
							'default' => true,
						),
						'displayDate'     => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'staticContent'   => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'categoryItem'    => array(
							'type'    => 'string',
							'default' => '0',
						),
						'postItem'        => array(
							'type'    => 'string',
							'default' => '',
						),
						'selectedOption'  => array(
							'type' => 'string',
						),
						'singlePostCat'   => array(
							'type'    => 'string',
							'default' => '',
						),
						'singlePostImage' => array(
							'type'    => 'string',
							'default' => '',
						),
						'singlePostTitle' => array(
							'type'    => 'string',
							'default' => '',
						),
						'singlePostDesc'  => array(
							'type'    => 'string',
							'default' => '',
						),
						'singlePostDate'  => array(
							'type'    => 'string',
							'default' => '',
						),
						'singlePostLink'  => array(
							'type'    => 'string',
							'default' => '',
						),
					),
					'render_callback' => array( $this, 'gbblock_single_article_block_render_callback' ),
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
		public function gbblock_single_article_block_render_callback( $attributes ) {

			$selected_post_id = isset( $attributes['postItem'] ) && ! empty( $attributes['postItem'] ) ? $attributes['postItem'] : '';
			$display_category = $attributes['displayCategory'];
			$display_date     = $attributes['displayDate'];
			$latest_article   = $attributes['latestArticle'];
			$static_content   = $attributes['staticContent'];

			ob_start();

			$taxonomy = GBBLOCK_CONTENT_TAXONOMY;
			if ( $latest_article ) {
				$args = array(
					'posts_per_page' => 1,
					'orderby'        => 'post_date',
					'order'          => 'DESC',
					'post_type'      => GBBLOCK_CONTENT_TYPE,
					'post_status'    => 'publish',
				);

				$latest_posts = new WP_Query( $args );

				if ( $latest_posts->have_posts() ) {
					while ( $latest_posts->have_posts() ) :
						$latest_posts->the_post();
						$post_id       = get_the_ID();
						$post_date_obj = get_post_datetime( $post_id );
						$post_date     = $post_date_obj->format( 'd-m-Y' );

						?>
						<div class="single-post-wrapper">
							<div class="single-post-main" id="post-<?php the_ID(); ?>">
								<div class="image-wrapper">
								<?php if ( has_post_thumbnail() ) { ?>
										<a href="<?php the_permalink(); ?>" data-content-id="<?php echo esc_attr( $post_id ); ?>" data-zone="center" data-provider-source="content" data-result-type="articleTiles">
											<?php the_post_thumbnail( 'medium' ); ?>
											<span class="screen-reader-text">Image Thumbnail</span>
										</a>
									<?php } ?>
								</div>
								<div class="content-wrapper">

									<?php
									if ( $display_category ) {
										$terms = wp_get_post_terms( $post_id, $taxonomy, array( 'fields' => 'all' ) );
										if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
											$term_array = array();
											foreach ( $terms as $term ) {
												if ( 0 === $term->parent ) {
													$term_array[] = $term->name;
												}
											}
											if ( ! empty( $term_array ) ) {
												if ( ( $key = array_search( 'Health', $term_array ) ) !== false ) {
													unset( $term_array[ $key ] );
												}
												if ( ( $key = array_search( 'Article', $term_array ) ) !== false ) {
													unset( $term_array[ $key ] );
												}
											}
										}
										$categories = ! empty( $term_array ) ? implode( ', ', $term_array ) : '';
										?>
											<div class="section-category-name"><?php echo esc_html( $categories ); ?></div>
											<?php
									}
									?>
									<h3 class="post-title">
										<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" data-content-id="<?php echo esc_attr( $post_id ); ?>" data-zone="center" data-provider-source="content" data-result-type="articleTiles">
										<?php the_title(); ?>
										</a>
									</h3>
									<div class="post-desc">
										<?php
										echo get_the_content();
										?>
									</div>
										<?php if ( $display_date ) { ?>
										<p class="post-date"><?php echo esc_html( $post_date ); ?></p>
									<?php } ?>
								</div>
							</div>
						</div>
							<?php
						endwhile;
				}
				wp_reset_postdata();
			} else {
				if ( $static_content ) {

					$single_post_title = isset( $attributes['singlePostTitle'] ) && ! empty( $attributes['singlePostTitle'] ) ? $attributes['singlePostTitle'] : '';
					$single_post_cat   = isset( $attributes['singlePostCat'] ) && ! empty( $attributes['singlePostCat'] ) ? $attributes['singlePostCat'] : '';
					$single_post_desc  = isset( $attributes['singlePostDesc'] ) && ! empty( $attributes['singlePostDesc'] ) ? $attributes['singlePostDesc'] : '';
					$single_post_date  = isset( $attributes['singlePostDate'] ) && ! empty( $attributes['singlePostDate'] ) ? $attributes['singlePostDate'] : '';
					$single_post_image = isset( $attributes['singlePostImage'] ) && ! empty( $attributes['singlePostImage'] ) ? $attributes['singlePostImage'] : '';
					$single_post_link  = isset( $attributes['singlePostLink'] ) && ! empty( $attributes['singlePostLink'] ) ? $attributes['singlePostLink'] : '';
					if ( ! empty( $single_post_title ) || ! empty( $single_post_cat ) || ! empty( $single_post_desc ) || ! empty( $single_post_date ) || ! empty( $single_post_image ) || ! empty( $single_post_link ) ) {
						?>
					<div class="single-post-wrapper">
						<div class="single-post-main" id="static-post">
							<div class="image-wrapper">
							<?php if ( ! empty( $single_post_image ) ) { ?>
									<a href="<?php echo esc_url( $single_post_link ); ?>" data-content-id="" data-zone="center" data-provider-source="content" data-result-type="articleTiles">
										<img width="800" height="415" src="<?php echo esc_url( $single_post_image ); ?>" alt="Thumbnail" loading="lazy" />
										<span class="screen-reader-text">Image Thumbnail</span>
									</a>
								<?php } else { ?>
									<a href="<?php echo esc_url( $single_post_link ); ?>" data-content-id="" data-zone="center" data-provider-source="content" data-result-type="articleTiles">
										<img width="800" height="415" src="/wp-content/plugins/gb-blocks/assets/images/single-article-thumb.jpg" alt="Thumbnail" loading="lazy" />
										<span class="screen-reader-text">Image Thumbnail</span>
									</a>
								<?php } ?>
							</div>
							<div class="content-wrapper">
								<div class="section-category-name">
								<?php
								if ( ! empty( $single_post_cat ) && $display_category ) {
									echo esc_html( $single_post_cat );
								}
								?>
								</div>
								<h3 class="post-title">
									<a href="<?php echo esc_url( $single_post_link ); ?>" title="<?php echo esc_attr( $single_post_title ); ?>" data-content-id="" data-zone="center" data-provider-source="content" data-result-type="articleTiles">
									<?php echo esc_html( $single_post_title ); ?>
									</a>
								</h3>
								<div class="post-desc">
									<?php echo esc_html( $single_post_desc ); ?>
								</div>
								<?php if ( $display_date ) { ?>
									<p class="post-date"><?php echo esc_html( $single_post_date ); ?></p>
								<?php } ?>
							</div>
						</div>
					</div>
							<?php
					}
				} else {
					if ( ! empty( $selected_post_id ) ) {
						$args  = array(
							'p'           => $selected_post_id,
							'post_type'   => GBBLOCK_CONTENT_TYPE,
							'post_status' => 'publish',
						);
						$query = new WP_Query( $args );
						if ( $query->have_posts() ) {
							$query->the_post();
							$post_id       = get_the_ID();
							$post_date_obj = get_post_datetime( $post_id );
							$post_date     = $post_date_obj->format( 'd-m-Y' );
							?>
							<div class="single-post-wrapper">
								<div class="single-post-main" id="post-<?php the_ID(); ?>">
									<div class="image-wrapper">
								<?php if ( has_post_thumbnail() ) { ?>
											<a href="<?php the_permalink(); ?>" data-content-id="<?php echo esc_attr( $post_id ); ?>" data-zone="center" data-provider-source="content" data-result-type="articleTiles">
												<?php the_post_thumbnail( 'large' ); ?>
												<span class="screen-reader-text">Image Thumbnail</span>
											</a>
										<?php } ?>
									</div>
									<div class="content-wrapper">
									<?php
									if ( $display_category ) {
										$terms = wp_get_post_terms( $post_id, $taxonomy, array( 'fields' => 'all' ) );
										if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
											$term_array = array();
											foreach ( $terms as $term ) {
												if ( 0 === $term->parent ) {
													$term_array[] = $term->name;
												}
											}
											if ( ! empty( $term_array ) ) {
												if ( ( $key = array_search( 'Health', $term_array ) ) !== false ) {
													unset( $term_array[ $key ] );
												}
												if ( ( $key = array_search( 'Article', $term_array ) ) !== false ) {
													unset( $term_array[ $key ] );
												}
											}
										}
										$categories = ! empty( $term_array ) ? implode( ', ', $term_array ) : '';
										?>
													<div class="section-category-name"><?php echo esc_html( $categories ); ?></div>
											<?php
									}
									?>
										<h3 class="post-title">
											<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" data-content-id="<?php echo esc_attr( $post_id ); ?>" data-zone="center" data-provider-source="content" data-result-type="articleTiles">
										<?php the_title(); ?>
											</a>
										</h3>
										<div class="post-desc">
										<?php
										echo get_the_content();
										?>
										</div>
										<?php if ( $display_date ) { ?>
											<p class="post-date"><?php echo esc_html( $post_date ); ?></p>
										<?php } ?>
									</div>
								</div>
							</div>

							<?php
							wp_reset_postdata();
						}
					}
				}
			}
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
