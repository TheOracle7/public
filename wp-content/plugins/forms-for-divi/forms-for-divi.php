<?php
/**
 * Plugin Name:     Divi Form Builder With Material Design
 * Plugin URI:      https://wptools.app/wordpress-plugin/divi-form-builder-with-material-design/
 * Description:     Create form using divi builder. Integrated with material design styles, spam protection, email notification, database saves & more.
 * Author:          wpt00ls
 * Author URI:      https://wptools.app/wordpress-plugin/divi-form-builder-with-material-design/?utm_source=plugin&utm_medium=main&utm_campaign=divi-forms&utm_content=author-uri
 * Text Domain:     forms-for-divi
 * Domain Path:     /languages
 * Version:         8.4.0
 *
  *
 * @package         Forms_For_Divi_Premium
 */

define( 'WPT_FFD_DEBUG', false );

require_once __DIR__ . '/freemius.php';
require_once __DIR__ . '/vendor/autoload.php';

$loader = \WPT\DiviForms\Loader::getInstance();

$loader['plugin_name']    = 'Divi Form Builder With Material Design';
$loader['plugin_version'] = '8.4.0';
$loader['plugin_dir']     = __DIR__;
$loader['plugin_slug']    = basename( __DIR__ );
$loader['plugin_url']     = plugins_url( '/' . $loader['plugin_slug'] );
$loader['plugin_file']    = __FILE__;

$loader->run();
