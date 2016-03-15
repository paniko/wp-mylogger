<?php
/**
 * @package   WP My Logger
 * @author    Nicolò Palmigiano
 * @copyright 2015 PaNiko
 *
 * @wordpress-plugin
 * Plugin Name: My Logger
 * Description: Implements logger based on log4php
 * Text Domain: wp-mylogger-locale
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-wp-mylogger.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'My Logger', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'My Logger', 'deactivate' ) );

My_Logger::get_instance();