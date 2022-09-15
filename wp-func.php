<?php
register_activation_hook(__FILE__, ['ClassName', 'activate']);

load_plugin_textdomain('own_plg_booking', false, plugin_basename(dirname(__FILE__ ) ).'/languages');
require_once (plugin_dir_path(__FILE__).'setup.php');

/* register_post_type */
function booking_register_post_type() {
    register_post_type('own_booking', $args);
}
add_action('init', 'booking_register_post_type');

/* Add custom column to post list */
function add_own_columns($columns) {
    global $typenow;
    if($typenow == 'booking') {

    }
    return $columns;
}
add_filter('manage_posts_columns' , 'add_own_columns');

/* Display custom column */
// add_action(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1)
add_action('manage_posts_custom_column' , 'display_own_columns', 10, 2);
function display_own_columns($column, $post_id) {
    global $typenow;
    if($typenow=='own_booking') {

    }
}

/* add css head  */
function own_column_width() {
}
add_action('admin_head', 'own_column_width');


add_action ('add_meta_boxes', 'own_booking_add_metaboxes');
function own_booking_add_metaboxes() {
		// add_meta_box($id:string,$title:string,$callback:callable,$screen:string|array|WP_Screen|null,$context:string,$priority:string,$callback_args:array|null )
		add_meta_box('own_booking_metabox_id', __('Order Metabox'), 'own_booking_callback', 'own_booking', 'normal', 'high');
}
function own_booking_callback($post, $metabox) {
    wp_nonce_field( basename( __FILE__ ), '_own_booking_nonce');
?>
    <input type="hidden" name="hidden-flag" value="1" />
<?php
}

add_action('save_post', 'own_booking_add_metaboxes_save');
function own_booking_add_metaboxes_save($post_id)
{		// Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ '_own_booking_nonce' ] ) && wp_verify_nonce( $_POST[ '_own_booking_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce)
    {
        return;
    }
    $post_type = get_post_type($post_id);
    $post_type_allow = array('own_booking');
    $post_status = get_post_status($post_id);
    if (in_array($post_type, $post_type_allow) && "auto-draft" != $post_status )
    {
        own_save_meta($post_id, 'customer', 'object');
        //own_save_meta($post_id, 'paypal_transaction', 'object');
    }
    return $post_id;
}