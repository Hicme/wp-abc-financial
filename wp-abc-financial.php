<?php
/**
 * Plugin Name: WP ABC Financial Integration
 * Description: Allows you to integrate abc Financial crm with site.
 * Version: 1.0.0
 * Author: Hicme
 * Author URI: https://github.com/prosvitco-oleg
 * Text Domain: wpabcf
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

// Define plugin constants.
if ( ! defined( 'P_VERSION' ) ) {
  define( 'P_VERSION', '1.0.0' );
}

if ( ! defined( 'P_PATH' ) ) {
  define( 'P_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );
}

if ( ! defined( 'P_URL_FOLDER' ) ) {
  define( 'P_URL_FOLDER', plugin_dir_url( __FILE__ ) );
}

if( ! defined( 'REST_NAMESPASE' ) ) {
  define( 'REST_NAMESPASE', 'nuxt/v1' );
}

register_activation_hook(__FILE__, 'p_activate');

register_deactivation_hook( __FILE__, 'p_deactivate' );

include P_PATH . 'vendor/autoload.php';
include P_PATH . 'autoloader.php';
include P_PATH . 'includes/functions/functions.php';

wpabcf();

function p_activate()
{
  \system\Install::install_depencies();
}

function p_deactivate()
{

}
