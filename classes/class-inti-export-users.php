<?php

/**
 * Intimation Export Users
 *
 * @since 0.1
 */

class Inti_Export_Users
{

    private static $instance = null;
    
    protected $directory;
    
    function __construct()
    {
        // Activation
        //register_activation_hook( IEU_PLUGIN_BASENAME, array($this, 'psa_archive_posts_on_activation') );

        // load admin scripts and styles
        add_action( 'admin_enqueue_scripts', array($this, 'ieu_scripts_and_styles') );

        // AJAX
        add_action( 'wp_ajax_nopriv_ieu_export', array($this, 'ieu_export') );
        add_action( 'wp_ajax_ieu_export', array($this, 'ieu_export') );

        // Deactivation
        // register_deactivation_hook( IEU_PLUGIN_BASENAME, array($this, 'psa_archive_posts_on_deactivation') );

        // set the directory value for use
        $this->set_directory_value();

        
    }


    /**
	 * Sets up the main instance
     *
     * @since 0.1
     */
    public static function instance( $file = '', $version = '0.1')
    {
        if ( is_null(self::$instance) ) 
        {
            self::$instance = new self( $file, $version );
        }

        return self::$instance;
    }


    /**
     * Set the directory
     *
     * @since 0.1
     */
    public function set_directory_value(){
        $this->directory = plugins_url() . '/' . IEU_PLUGIN_BASE_FOLDER;
    }


    /**
     * Intimation Get Users
     *
     * @since 0.1
     */
    public function ieu_get_users() 
    {
        global $wpdb;

        $users = $wpdb->get_results( 
            "
            SELECT users.ID, users.user_login, users.user_email, users.display_name, meta.meta_value AS role 
            FROM $wpdb->users AS users 
            LEFT JOIN $wpdb->usermeta AS meta ON users.ID = meta.user_id 
            WHERE meta.meta_key = 'wp_capabilities' 
            "
        );

        preg_match("/(\"[a-zA-Z]*\")/", $users[0]->role, $output);
        $users[0]->role = str_replace('"', '', $output[0] );

        return $users;
    }


    /**
     * handle the export
     *
     * @since 0.1
     */
    public function ieu_export()
    {
        // Security check.
        check_ajax_referer( 'ieu_export', 'nonce' );

        // create the headers and set the content type
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=users.csv');

        $url = ABSPATH . 'users.csv';

        // open a file for writing
        $file = fopen( $url, 'w');        

        // varibles to pass into the fputcsv
        $handle = $file;
        $fields = $this->ieu_get_users();
        $delimiter = ',';

        // output the column headings
        fputcsv( $file, array('ID', 'Name', 'Email', 'Role') ); // could do with making this dynamic

        foreach ($fields as $field) {
            $field = array( $field->ID, $field->user_login, $field->user_email, $field->role );
            fputcsv( $handle, $field, $delimiter );
        }

        fclose($file);
        
        // $data['file'] = $file; // For testing only
        // $data['url'] = $url; // For testing only 
        wp_send_json_success( $data );   
        exit;     
    }

    /**
     * Import scripts and styles
     *
     * @since 0.1
     */
    public function ieu_scripts_and_styles()
    {
        wp_enqueue_script( 
            'ieu-scripts',
            $this->directory . '/assets/js/ieu-scripts.js', 
            array('jquery')
        );

        $data = array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'ieu_export' )
        );
        wp_localize_script( 'ieu-scripts', 'ieu_export', $data );

        wp_enqueue_style( 
            'ieu-styles', 
            $this->directory . '/assets/css/ieu_export_users.css'
        );
    }
}
