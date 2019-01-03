<?php
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/screen.php' );
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
/**
 * A Table to list the users to export in the admin area
 * @since 1.0
 */
class ExportUserList extends WP_List_Table
{
    public $items;


    function __construct()
    {
        parent::__construct( array(
            'singular' => 'wp_list_text_update',
            'plural' => 'wp_list_available_updates',
            'ajax' => false
        ));

    }


    /**
     * get the users from the main class, to edit the args
     * use the main export
     * @return array user objects
     */
    function get_users()
    {
        $user_export = new Arpas_Export_Users();
        return $user_export->ieu_get_users();
    }


    /**
     * Prepare the table and assign items to the table
     * @since 1.0
     */
    function prepare_items()
    {
        // sort the users by email
        $users = $this->filter_get_user( $this->get_users() );
        usort($users, array($this, 'reorder_columns'));
        $this->items = $users;

        $columns = $this->get_columns(); // Register Columns
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);
    }

    /**
     * reduces the WP_User object into an array
     *
     * @param object $user_object_array
     * @return array $users
     */
    function filter_get_user( $user_object_array )
    {
        $users = array_map(function($item) {
            $user['ID'] = $item->data->ID;
            $user['user_email'] = $item->data->user_email;
            return $user;
        }, $user_object_array);

        return $users;
    }


    /**
     * define sortable columns
     * @since 1.0
     */
    function get_sortable_columns()
    {
        return $sortable = array(
            'email' => 'Email'
        );
    }


    /**
     * sort columns
     * @since 1.1
     */
    function reorder_columns( $a, $b )
    {
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : '';
        $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
        $result = strcmp( $a['user_email'], $b['user_email'] );
        return ( $order === 'asc' ) ? $result : -$result;
    }


    /**
     * define the columns that are in the table
     * @since 1.0
     * @return array $columns
     */
    function get_columns()
    {
        return $columns = array(
            'fullname' => __( 'Full Name' ),
            'email' => __( 'Email' ),
            'company' => __( 'company' ),
            'pfco' => __( 'pfco' ),
            'membership' => __( 'membership' ),
        );
    }


    /**
     * column email callback
     * @since 1.0
     * @param obj $item
     * @return string
     */
    function column_email( $item ) 
    {
        return $item['user_email'];
    }


    /**
     * column fullname callback
     * @since 1.0
     * @param obj $item
     * @return string
     */
    protected function column_fullname( $item ) 
    {
        $first = get_user_meta($item['ID'], 'first_name', true);
        $last = get_user_meta($item['ID'], 'last_name', true);
        return $first . ' ' . $last;
    }


    /**
     * column company callback
     * @since 1.0
     * @param obj $item
     * @return string
     */
    function column_company( $item ) 
    {
        return get_user_meta($item['ID'], 'operator_name', true);
    }


    /**
     * column pfco callback
     * @since 1.0
     * @param obj $item
     * @return string
     */
    function column_pfco( $item ) 
    {
        return get_user_meta($item['ID'], 'operator_pfco_number', true);
    }


    /**
     * column company callback
     * @since 1.0
     * @param obj $item
     * @return string
     */
    function column_membership( $item ) 
    {
        // echo '<pre>'; print_r($item); echo '</pre>';
        // preg_match("/(\"[a-zA-Z]*\")/", $item->membership, $output);
        // return str_replace('"', '', $output[0] );
        return get_user_meta($item['ID'], 'membership_number', true);;
    }

}
