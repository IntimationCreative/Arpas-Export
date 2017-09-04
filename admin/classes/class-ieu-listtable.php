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
     * Prepare the table and assign items to the table
     * @since 1.0
     */
    function prepare_items()
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
        $this->items = $users;

        // Register Columns
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
    }

    /**
     * define sortable columns
     * @since 1.0
     */
    function get_sortable_columns()
    {
        return $sortable = array(
            'col_name' => 'username'
        );
    }

    /**
     * define the columns that are in the table
     * @since 1.0
     * @return array $columns
     */
    function get_columns()
    {
        return $columns = array(
            'username' => __( 'Username' ),
            'email' => __( 'Email' ),
            'role' => __( 'Role' ),
			'posts' => __( 'Posts' )
        );
    }

	/**
	 * column name callback
     * @since 1.0
	 * @param obj $item
	 * @return string
	 */
    protected function column_username( $item ) 
    {
        // echo '<pre>'; print_r($item); echo '</pre>';
		return $item->display_name;
	}

	/**
	 * column email callback
     * @since 1.0
	 * @param obj $item
	 * @return string
	 */
    function column_email( $item ) 
    {
        return $item->user_email;
	}

	/**
	 * column role callback
     * @since 1.0
	 * @param obj $item
	 * @return string
	 */
    function column_role( $item ) 
    {
        preg_match("/(\"[a-zA-Z]*\")/", $item->role, $output);
		return str_replace('"', '', $output[0] );
    }

    /**
     * column posts callback
     * @since 0.1
     * @return string
     */
    function column_posts( $item ) 
    {
        global $wpdb;        
        $users = $wpdb->get_results( 
            "
            SELECT COUNT(posts.ID) AS posts 
            FROM $wpdb->posts AS posts
            WHERE posts.post_author = $item->ID 
            "
        );
        // echo '<pre>'; print_r($users); echo '</pre>';
        return $users[0]->posts;
    }
}
