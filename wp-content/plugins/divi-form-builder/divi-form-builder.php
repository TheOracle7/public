<?php
/*
Plugin Name: Divi Form Builder
Plugin URI:  https://diviengine.com
Description: Create complex forms with the Divi Builder
Version:     2.3.1
Author:      Divi Engine
Author URI:  https://diviengine.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: divi-form-builder
Domain Path: /languages
@author      diviengine.com
@copyright   2020 diviengine.com

I pray that you bless the people who interact and who own this website - I pray the blessing to be one that goes beyond worldly treasures but understanding the deep love you have for them. In Jesus name, Amen

John 14:6
I am the way, and the truth, and the life. No one comes to the Father except through me.
*/



if (! defined('ABSPATH')) exit; 

define('DE_FB_VERSION', '2.3.1');

define('DE_FB_AUTHOR', 'Divi Engine');
define('DE_FB_PATH',   plugin_dir_path(__FILE__));
define('DE_FB_URL',    plugins_url('', __FILE__));
define('DE_FB_PRODUCT_ID', 'WP-DE-FB');
define('DE_FB_INSTANCE', str_replace(array ("https://" , "http://"), "", home_url()));
define('DE_FB_PRODUCT_URL', 'https://diviengine.com/product/divi-form-builder/');
define('DE_FB_APP_API_URL', 'https://diviengine.com/index.php');
define('DE_FB_P', 'd_e');
// define('DE_FB_P', 'm_a');


register_activation_hook( __FILE__, 'divi_form_builder_plugin_activate' );

function divi_form_builder_plugin_activate() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    // Create plugs table
    $sql_form = "CREATE TABLE `{$wpdb->prefix}de_contact_forms` (
        id MEDIUMINT NOT NULL AUTO_INCREMENT,
        post_id MEDIUMINT,
        form_no varchar(36),
        form_name varchar(100),
        PRIMARY KEY (id)
    ) $charset_collate;";

    $sql_form_entry = "CREATE TABLE `{$wpdb->prefix}de_contact_form_entries` (
        id MEDIUMINT NOT NULL AUTO_INCREMENT,
        form_id MEDIUMINT,
        form_entry longtext,
        insert_date varchar(19),
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    dbDelta( $sql_form );
    dbDelta( $sql_form_entry );
}

add_action( 'admin_init', 'divi_form_builder_table_modify', 10 );

function divi_form_builder_table_modify() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    // Create plugs table
    $sql_form = "CREATE TABLE `{$wpdb->prefix}de_contact_forms` (
        id MEDIUMINT NOT NULL AUTO_INCREMENT,
        post_id MEDIUMINT,
        form_no varchar(36),
        form_name varchar(100),
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    dbDelta( $sql_form );
}

//include_once('titan-framework/titan-framework-embedder.php');

/**
 * Force load Contact Form module styles.
 *
 * @return array
 */
function divi_form_builder_dynamic_css_assets( $modules ) {
    array_push( $modules, 'et_pb_contact_form' );
    return $modules;
}
add_filter( 'et_required_module_assets', 'divi_form_builder_dynamic_css_assets', 99 );

/**
 * Force load Contact Form module styles above the fold.
 *
 * @return array
 */
function divi_form_builder_dynamic_css_assets_atf( $atf_modules ) {
    array_push( $atf_modules, 'et_pb_contact_form' );
    return $atf_modules;
}
add_filter( 'et_dynamic_assets_modules_atf', 'divi_form_builder_dynamic_css_assets_atf', 20 );

include(DE_FB_PATH . '/includes/classes/class.wooslt.php');
include(DE_FB_PATH . '/includes/classes/class.licence.php');
include(DE_FB_PATH . '/includes/classes/class.options.php');
include(DE_FB_PATH . '/includes/classes/class.updater.php');
//include(DE_FB_PATH . '/includes/classes/init.class.php');

require_once dirname( __FILE__ ) .'/functions.php';


if ( ! function_exists( 'diviengineformbuilder_initialize_extension' ) ):
/**
 * Creates the extension's main class instance.
 *
 * @since 1.0.0
 */
function diviengineformbuilder_initialize_extension() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/DiviFormBuilder.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/ajaxcalls/post_ajax.php';
}
add_action( 'divi_extensions_init', 'diviengineformbuilder_initialize_extension' );
endif;


function divi_form_builder_custom_css() {

	// wp_enqueue_style( 'form-builder-custom-style', DE_FB_URL . '/styles/custom-style.min.css' , array(), DE_FB_VERSION, 'all' );

    wp_register_script( 'divi-form-builder-select2-js', plugins_url('/js/select2.min.js', __FILE__ ), array( 'jquery' ), DE_FB_VERSION );
    wp_register_style( 'divi-form-builder-select2-css', plugins_url( '/css/select2.min.css' , __FILE__ ), array(), DE_FB_VERSION, 'all' );
    wp_enqueue_script( 'df-multistep', DE_FB_URL . '/js/multistep-admin.min.js' , array( 'jquery' ), DE_FB_VERSION );
}
add_action( 'wp_enqueue_scripts', 'divi_form_builder_custom_css', 999999999 );

function enqueue_divi_engine_admin_js() {
    if ( is_customize_preview() ) {
    } else {
        wp_enqueue_style( 'divi-engine-style', DE_FB_URL . '/css/divi-engine.min.css' , array(), DE_FB_VERSION, 'all' );
        wp_enqueue_script( 'divi-form-builder-select2-js', DE_FB_URL . '/js/select2.min.js' , array( 'jquery' ), DE_FB_VERSION );
        wp_enqueue_style( 'divi-form-builder-select2-css', DE_FB_URL . '/css/select2.min.css' , array(), DE_FB_VERSION, 'all' );   
    }
}
add_action( 'admin_enqueue_scripts', 'enqueue_divi_engine_admin_js', 999999999 );



if ( !function_exists('divi_form_builder_dynamic_css_assets')) {
    /**
     * Force load Contact Form module styles.
     *
     * @return array
     */
    function divi_form_builder_dynamic_css_assets( $modules ) {
        array_push( $modules, 'et_pb_contact_form' );
        return $modules;
    }
    add_filter( 'et_required_module_assets', 'divi_form_builder_dynamic_css_assets', 99 );
}

if ( !function_exists('divi_form_builder_dynamic_css_assets_atf')) {
    /**
     * Force load Contact Form module styles above the fold.
     *
     * @return array
     */
    function divi_form_builder_dynamic_css_assets_atf( $atf_modules ) {
        array_push( $atf_modules, 'et_pb_contact_form' );
        return $atf_modules;
    }
    add_filter( 'et_dynamic_assets_modules_atf', 'divi_form_builder_dynamic_css_assets_atf', 20 );
}

// Enqueue Scripts with yarn star


function form_builder_dev_enqueue_styles() {
	wp_enqueue_style( 'dev-style', DE_FB_URL . '/includes/modules/FormField/style.css' , array(), DE_FB_VERSION, 'all' );
}

if ( defined( 'DIVIENGINEFORMBUILDER_DEBUG' ) && true === DIVIENGINEFORMBUILDER_DEBUG ) {
	add_action( 'wp_enqueue_scripts', 'form_builder_dev_enqueue_styles' );
}

// function de_license_css() {
//     $license = DE_FB_LICENSE; 
//     if ($license == 'no') {
//         wp_enqueue_style('de-debug-css', plugin_dir_url( __FILE__ ) . "css/debug.min.css", '', '1.0.0');
//         wp_enqueue_script('de-debug-js', plugin_dir_url( __FILE__ ) . "js/debug.min.js", array('jquery'), '1.0.0');
//     }
// }
// add_action('admin_footer', 'de_license_css');
// add_action('wp_footer', 'de_license_css');

global $DE_FB;
$DE_FB = new DE_FB();