<?php 
/**
 * Admin Setup
 * @since 1.0
 */

include_once plugin_dir_path( __FILE__ ) . '/classes/class-ieu-admin.php';
include_once plugin_dir_path( __FILE__ ) . '/classes/class-ieu-listtable.php';

$admin_settings = new IntiExportUsersAdmin(); // start admin