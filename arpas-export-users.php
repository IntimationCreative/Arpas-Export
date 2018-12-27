<?php

/**
 * Plugin Name: Intimation Arpas Export Users
 * Plugin URI: http://intimation.uk
 * Description: Export Users in CSV
 * Version: 1.0
 * Author: Paul Spence - Intimation
 * Author URI: http://intimation.uk
 * License: GPL
 */

 /**
 * Include the main plugin class.
 *
 * @since 1.0
 */
include_once plugin_dir_path( __FILE__ ) . 'classes/class-arpas-export-users.php';
include_once plugin_dir_path( __FILE__ ) . 'admin/admin.php';

// define the path to plugin file (export-users/export-users.php)
define( 'IEU_PLUGIN_BASENAME', basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );

define( 'IEU_PLUGIN_BASE_FOLDER', basename( dirname( __FILE__ ) ) ); // define the plugin base folder
define( 'IEU_PLUGIN_ABS_FOLDER', dirname( __FILE__ ) ); //define abs path to plugin folder
define( 'IEU_SITE_URL', get_site_url() );


/**
 * Loads the whole plugin.
 *
 * @since 1.0
 * @return arpas_export_users
 */
function arpas_export_users() 
{
	$instance = Arpas_Export_Users::instance( __FILE__, '1.0' );

	return $instance;
}

arpas_export_users();