<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Astra
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header(); ?>
<div class="cp-staff-detail">

  <?php if( have_posts() ): ?>
    <?php while( have_posts() ): the_post() ?>
      <h1><?php the_title() ?></h1>

      <p><?php echo the_content() ?></p>

      <?php the_post_navigation( array(
        "prev_text" => "← " . __( "Previous Staff", "lmpc" ),
        "next_text" => __( "Next Staff", "lmpc" ) . " →"
      ) ) ?>
    <?php endwhile; ?>
  <?php endif; ?>

</div>
<?php get_footer(); ?>
