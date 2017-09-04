<?php
/**
 * Admin setup
 */

class IntiExportUsersAdmin
{
    function __construct()
    {
        // Initialise actions
        add_action('admin_menu', array($this, 'options_page')); // options page
        add_action('admin_init', array($this, 'options_init')); // settings
    }

    /**
     * Add a menu item called export users
     *
     * @since 1.0
     * @params for add_menu_page: 
     * string $page_title, string $menu_title, string $capability, 
     * string $menu_slug, callable $function = '', string $icon_url = '', int $position = null
     */
    
    function options_page()
    {
        add_menu_page(
            'Export Users',
            'EXPORT USERS',
            'manage_options',
            'inti_export_users',
            array($this, 'export_user_list'),
            'dashicons-download',
            33
        );
    }
    
    /**
     * register settings
     *
     * @since 1.0
     */
    function options_init()
    {
        register_setting(
            'export_users_settings', // Option group
            'registered_qcard_users', // Option Name
            array() // Callback
        );

        add_settings_section(
            'registered_qcard_users_section', // ID
            ' ', // Title
            array($this, 'get_users'), // Callback
            'inti_export_users' // Page
        );
    }


    /**
     * export user list
     * @since 1.0
     */
    function export_user_list()
    {
        if ( !current_user_can( 'manage_options' ) ) return;
        $export_user_list = new ExportUserList();
        $export_user_list->prepare_items();
        include IEU_PLUGIN_ABS_FOLDER . '/admin/views/user_list.php';
    }
}
