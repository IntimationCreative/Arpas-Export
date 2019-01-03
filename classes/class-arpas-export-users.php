<?php

/**
 * Remote Users
 *
 * @since 1.0
 */

class Arpas_Export_Users
{

    private static $instance = null;
    
    protected $directory;
    
    function __construct()
    {
        // Activation
        //register_activation_hook( IEU_PLUGIN_BASENAME, array($this, 'psa_archive_posts_on_activation') );

        // load admin scripts and styles
        add_action( 'admin_enqueue_scripts', array($this, 'ieu_scripts_and_styles') );

        // export all
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
     * @since 1.0
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
     * @since 1.0
     */
    public function set_directory_value(){
        $this->directory = plugins_url() . '/' . IEU_PLUGIN_BASE_FOLDER;
    }


    /**
     * handle the export
     *
     * @since 1.0
     */
    public function ieu_export()
    {
        // Security check.
        $security = check_ajax_referer( 'ieu_export', 'security' );

        // create the headers and set the content type
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=arpas-users.csv');

        // if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
        //     $filepath = IEU_PLUGIN_ABS_FOLDER . '/arpas-users.csv';
        //     $local = true;
        // } else {
        //     $filepath = IEU_SITE_URL  . '/arpas-users.csv';
        //     $local = false;
        // }

        $filepath = IEU_PLUGIN_ABS_FOLDER . '/arpas-users.csv';

        // open a file for writing
        $file = fopen( $filepath, 'w+');

        // varibles to pass into the fputcsv
        $handle = $file;
        $fields = $this->ieu_get_users($_POST['type']);
        $delimiter = ',';

        // output the column headings
        // Username,Company Nam, First Name, Last Name, Email, Address1, Address2, City, Zipcode, Phone, PFCO Number, PFCO Expiry Date, Company Name,        
        fputcsv( $file, array('Membership Id', 'First Name', 'Last Name', 'Email', 'Company', 'Address1', 'Address2', 'City', 'Post Code', 'Phone', 'PfCo') );

        foreach ($fields as $field) {
            //First and Last Name
            $first = get_user_meta($field->ID, 'first_name', true);
            $last = get_user_meta($field->ID, 'last_name', true);
            $company = get_user_meta($field->ID, 'operator_name', true);
            $address1 = get_user_meta($field->ID, 'operator_address_line_1', true);
            $address2 = get_user_meta($field->ID, 'operator_address_line_2', true);
            $city = get_user_meta($field->ID, 'operator_town_city', true);
            $postcode = get_user_meta($field->ID, 'operator_postcode', true);
            $phone = get_user_meta($field->ID, 'operator_phone', true);
            $pfco = get_user_meta($field->ID, 'operator_pfco_number', true);
            $membership = get_user_meta($field->ID, 'membership_number', true);
            
            // define the values to go into the field var for the current line
            $field = array( $membership, $first, $last, $field->user_email, $company, $address1, $address2, $city, $postcode, $phone, $pfco );
            $success = fputcsv( $handle, $field, $delimiter );
        }

        fclose($file);

        $outputLink = site_url( '/wp-content/plugins/arpas-export-users/arpas-users.csv' );

        $data = array(
            'security' => $security,
            'filepath' => $filepath,
            'success' => $success,
            'link' => $outputLink,
            'url' => IEU_SITE_URL,
            'server_remote' => $_SERVER['REMOTE_ADDR'],
            'server_adr' => $_SERVER['SERVER_ADDR'],
        );

        wp_send_json_success( $data );
    }

    public function ieu_get_users($type = 'all')
    {   
        if ($type === 'all') {
            return get_users();
        }
        $args = array(
            'role' => 'inactive_user',
        );
        return get_users($args);
    }


    /**
     * Import scripts and styles
     *
     * @since 1.0
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
