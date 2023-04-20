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

add_action( 'rest_api_init', 'lmpc_register_custom_meta_field' );

function lmpc_register_custom_meta_field() {
    register_rest_field( 'cp_staff',
        'staff_title',
        array(
            'get_callback'    => 'lmpc_get_custom_meta_field',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

function lmpc_get_custom_meta_field( $object, $field_name, $request ) {
    return get_post_meta( $object['id'], 'title', true );
}