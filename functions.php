<?php
/**
 * Church Plugins - Default Theme Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Church Plugins - Default Theme
 * @since 1.0.0
 */

/**
 * Return base instance of Church functionality
 * 
 * @return cp\Init
 * @since  1.0.0
 *
 */
include_once( 'vendor/autoload.php' );
function cp() {
    return Church\Init::get_instance();
}
cp();
/**
 * Define Constants
 */
define( 'CHILD_THEME_CHURCH_PLUGINS_DEFAULT_THEME_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'church-plugins-default-theme-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_CHURCH_PLUGINS_DEFAULT_THEME_VERSION, 'all' );
    wp_enqueue_style( 'leafletcss', get_stylesheet_directory_uri() . '/assets/css/leaflet.css', array(), CHILD_THEME_CHURCH_PLUGINS_DEFAULT_THEME_VERSION, 'all' );

    wp_enqueue_script( 'leafletjs', get_stylesheet_directory_uri() . '/assets/js/leaflet.js', array(), CHILD_THEME_CHURCH_PLUGINS_DEFAULT_THEME_VERSION, 'all' );


}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

require_once trailingslashit( get_stylesheet_directory() )  . 'includes/class-base.php';

$base = new Base();
$base->gbblock_init();

add_action( 'rest_api_init', 'lmpc_register_staff_title_meta_field' );

function lmpc_register_staff_title_meta_field() {
    register_rest_field( 'cp_staff',
        'staff_title',
        array(
            'get_callback'    => 'lmpc_get_custom_staff_title_meta_field',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

function lmpc_get_custom_staff_title_meta_field( $object, $field_name, $request ) {
    return get_post_meta( $object['id'], 'title', true );
}



function inspect_styles() {
  global $wp_styles;
  // print_r($wp_styles->queue);
}
add_action( 'wp_print_styles', 'inspect_styles' );


function lmpc_apply_exclude_filters_to_query_loop( $query, $block, $page ) {

  // var_dump( $query, $block );

  foreach( $block->context['query'] as $key => $terms ) {
    if( str_ends_with( $key, '_exclude' ) ) {
      $taxonomy = substr( $key, 0, -8 );

      if( ! is_taxonomy_viewable( $taxonomy ) || empty( $terms ) ) {
        continue;
      }

      if( !isset( $query['tax_query'] ) ) {
        $query['tax_query'] = array();
      }

      array_push( $query['tax_query'], array(
        'taxonomy' => $taxonomy,
        'terms' => array_filter( array_map( 'intval', $terms ) ),
        'include_children' => false,
        'operator' => 'NOT IN'
      ) );
    }
  }


  return $query;
}

add_filter( 'query_loop_block_query_vars', 'lmpc_apply_exclude_filters_to_query_loop', 10, 3);

// add_action( 'quick_edit_custom_box', 'lmpc_quickedit_series', 10, 3 );
// add_action( 'manage_pages_custom_column', 'lmpc_populate_quickedit_series', 10, 2 );
// add_action( 'save_post', 'lmpc_save_quickedit_series' );

// function lmpc_populate_quickedit_series( $column_name, $post_id ) {
//   if( $column_name != 'item_type' ) {
//     return;
//   }

//   $post = get_post( $post_id );

//   $meta = get_post_meta( $post_id );

//   echo "My Custom Column";
// }

// function lmpc_quickedit_series( $column, $post_type, $taxonomy ) {
//   if( $column != 'item_type' || $post_type != 'cpl_item' ) {
//     return;
//   } 
  
  
// }

// function lmpc_save_quickedit_series() {
//   if( !wp_verify_nonce( $_POST[ '_inline_edit' ], 'inlineeditnonce' ) ) {
//     return;
//   }

  
// }

add_action('admin_init', function () {
    // Redirect any user trying to access comments page
    global $pagenow;
     
    if ($pagenow === 'edit-comments.php') {
        wp_safe_redirect(admin_url());
        exit;
    }
 
    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
 
    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});
 
// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
 
// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);
 
// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});
 
// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});


function lmpc_cpl_item_sort_order( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }

    $screen = get_current_screen();
    if ( $screen && 'edit-cpl_item' === $screen->id && ! isset( $_GET['orderby'] ) ) {
        $query->set( 'orderby', 'date' );
        $query->set( 'order', 'desc' );
    }
}

add_action( 'pre_get_posts', 'lmpc_cpl_item_sort_order' );
