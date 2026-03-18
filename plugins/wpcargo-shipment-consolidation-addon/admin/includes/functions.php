<?php
if(!defined('ABSPATH')){
    exit; //Exit if accessed directly
}
function wpcshcon_is_admin(){
    $admin_roles  = apply_filters( 'wpcshcon_admin_roles', array( 'administrator' ) );
    $current_user = wp_get_current_user();
    if( array_intersect( $current_user->roles, $admin_roles ) ){
        return true;
    }
    return false;
}
function wpcshcon_get_user_fullname( $userID = null ){
    if( $userID ){
        $user_info = get_userdata( $userID );
    }else{
        $user_info = wp_get_current_user();
    }
    $user_fullname = '';
    if( !empty( $user_info->first_name ) || !empty( $user_info->last_name ) ){
        $user_fullname = $user_info->first_name.' '.$user_info->last_name;
    }else{
        $user_fullname = $user_info->display_name;
    }
    return $user_fullname;
}
function wpcshcon_whs_assign_field(){
    $wpcshcon_whs_assign_field  = get_option( 'wpcshcon_whs_assign_field' );
    if( empty( $wpcshcon_whs_assign_field ) ){
        $wpcshcon_whs_assign_field = array(
            'name' => '', 'phone' => '', 'address_1' => '', 'city' => '', 'postcode' => '', 'state' => '', 'country' => ''
        );
    }
    return $wpcshcon_whs_assign_field;
}
function wpcshcon_whs_set_shipper( $shipment_id = 0 ){
    $whs_assign_field   = wpcshcon_whs_assign_field();
    $whs_address        = get_option( 'wpcshcon_warehouse_address' );
    $whs_name           = get_option( 'wpcshcon_warehouse_name' );
    // Check if shipment ID is passed
    if( !(int)$shipment_id ){
        return false;
    }
    // Check is settings value is set and in array format
    if( empty( $whs_assign_field ) || !is_array( $whs_assign_field ) || empty( $whs_address ) || !is_array( $whs_address ) ){
        return false;
    }
    foreach ( $whs_assign_field as $field_key => $meta_key ) {  
        // Save the shipper Name with the warehouse name
        if( $field_key == 'name' ){
            update_post_meta( $shipment_id, $meta_key, sanitize_text_field( $whs_name ) );
            continue;
        }
        // Check if the key exist in the warehouse address settings
        if( !array_key_exists( $field_key, $whs_address ) ){
            continue;
        }
        update_post_meta( $shipment_id, $meta_key, sanitize_text_field( $whs_address[$field_key] ) );
    }
}
function wpcshcon_dashboard_allowed_roles(){
    $allowed_roles = array(
        'administrator', 'wpcargo_client'
    );
    return apply_filters( 'wpcshcon_dashboard_allowed_roles', $allowed_roles );
}
function wpcshcon_weight_unit(){
    return !empty($options['wpc_mp_weight_unit']) ? $options['wpc_mp_weight_unit'] : 'kg';  
}
function wpcshcon_dimension_unit(){
    return !empty($options['wpc_mp_dimension_unit']) ? $options['wpc_mp_dimension_unit'] : 'cm';
}
function wpcshcon_gen_consolidation_code( ){
    global $wpdb;
    $prefix             = ( get_option( 'wpcshcon_prefix' ) ) ? get_option( 'wpcshcon_prefix' ) : '' ;
    $consolidation_code = $prefix.str_pad( wp_rand( 0, 999999 ), 6, "0", STR_PAD_LEFT );
    $sql                = "SELECT COUNT(*) FROM {$wpdb->prefix}usermeta WHERE `meta_key` LIKE 'consolidation_code' AND `meta_value` LIKE '$consolidation_code'";
    $result             = $wpdb->get_var( $sql );
    if( $result ){
        $consolidation_code = wpcshcon_gen_consolidation_code();
    }
    return $consolidation_code;
}
function wpcshcon_get_user_consolidation_code( $userID ){
    return get_user_meta( $userID, 'consolidation_code', true );
}
function wpcshcon_status(){
    $status = array(
        'pending-quotation'     => __('Pending Quotation', 'wpc-shipment-consoldation' ),
        'pending-confirmation'  => __('Pending Confirmation', 'wpc-shipment-consoldation' ),
        'in-queue'              => __('In Queue', 'wpc-shipment-consoldation' ),
        'ready'                 => __('Ready', 'wpc-shipment-consoldation' ),
        'processing'            => __('Processing', 'wpc-shipment-consoldation' ),
        'not-allowed'           => __('Not Allowed', 'wpc-shipment-consoldation' ),
        'on-hold'               => __('On-Hold', 'wpc-shipment-consoldation' ),
        'custom-declaration'    => __('Custom Declaration', 'wpc-shipment-consoldation' ),
        'shipped'               => __('Shipped', 'wpc-shipment-consoldation' ),
        'delivered'             => __('Delivered', 'wpc-shipment-consoldation' ),
        'approved'              => __('Approved', 'wpc-shipment-consoldation' )
    );
    $status = apply_filters( 'wcpshcon_status', $status );
    return $status;
}
function get_shipment_consolidation( $shipmentID ){
    global $wpdb;
    $prefix 	= $wpdb->prefix;
    $sql    = "SELECT tbl1.ID FROM `{$prefix}posts` AS tbl1 INNER JOIN `{$prefix}postmeta` AS tbl2 ON tbl1.ID = tbl2.post_id WHERE tbl1.post_type LIKE 'wpcargo_shipment' AND tbl1.post_status LIKE 'publish' AND tbl2.meta_key LIKE 'wpcshcon_shipments' AND tbl2.meta_value LIKE '%:\"{$shipmentID}\";%'";
    $consolidateID 	= $wpdb->get_var( $sql );
    return $consolidateID;
}
function wpcshcon_additional_fees(){
    $additional_fees = array(
        'insurance'			=> __('Insurance', 'wpc-shipment-consoldation' ),
        'extra_packaging'	=> __('Extra Packaging', 'wpc-shipment-consoldation' ),
        'fragile_sticker'	=> __('Fragile Sticker', 'wpc-shipment-consoldation' ),
        'storage'			=> __('Storage', 'wpc-shipment-consoldation' ),
        'ship_address'		=> __('Ship to Address', 'wpc-shipment-consoldation' ),
        'urgent_fees'		=> __('Urgent Fees', 'wpc-shipment-consoldation' ),
        'smart_pack'		=> __('Smart Pack', 'wpc-shipment-consoldation' ),
        'dangerous_item'    => __('Dangerous Item', 'wpc-shipment-consoldation' ),
        'picture'			=> __('Picture', 'wpc-shipment-consoldation' ),
    );
    $additional_fees = apply_filters( 'wpcshcon_additional_fees', $additional_fees );
    return $additional_fees;
}
function wpcshcon_get_consolidation_cost( $consolidateID ){
    $shipments              = maybe_unserialize( get_post_meta( $consolidateID, 'wpcshcon_shipments', true ) );
    $shipment_fee           = 0;
    if( !empty( $shipments ) ){
        foreach ($shipments as $shipmentID ) {
            $_shipmentCost = get_post_meta( $consolidateID, 'shipments_cost_'.$shipmentID, true ) ? get_post_meta( $consolidateID, 'shipments_cost_'.$shipmentID, true ) : 0 ;
            $shipment_fee  = $shipment_fee + $_shipmentCost;
        }
    }
    $shipping_method        = get_post_meta( $consolidateID, 'wpcshcon_shipping_method', true );
    $method_meta            = 'cost_'.wpcshcon_text_to_meta( $shipping_method );
    $shipping_cost          = get_post_meta( $consolidateID, $method_meta, true ) ? get_post_meta( $consolidateID, $method_meta, true ) : 0 ;
    $wpcshcon_tax_fee       = get_post_meta( $consolidateID, 'wpcshcon_tax_fee', true ) ? get_post_meta( $consolidateID, 'wpcshcon_tax_fee', true ) : 0;
    $addod_fees             = $wpcshcon_tax_fee + $shipping_cost;
    // Addon and Fees
	$total_cost = 0;
	$additional_fees = 0;
	foreach( wpcshcon_additional_fees() as $key => $label ){
		$additional_fees += wpcshcon_get_meta_cost( $consolidateID, 'wpcshcon_'.$key.'_cost' );
	}
	$total_cost = $addod_fees + $additional_fees + $shipment_fee;
    return wpcshcon_format_number( $total_cost );
}
function wpcshcon_format_number( $value ){
    return number_format((float)$value, 2, '.', '');
}
function wpcshcon_get_meta_cost( $postID = 0, $key = '' ){
    $cost = 0;
    $meta_cost = get_post_meta( $postID, $key, true );
    if( $meta_cost ){
        $cost = $meta_cost;
    }
    return wpcshcon_format_number( $cost );
}
function wpcshcon_is_consolidation( $postID ){
    global $wpdb;
    $sql = "SELECT tbl1.ID FROM `{$wpdb->prefix}posts` tbl1 INNER JOIN `{$wpdb->prefix}postmeta` tbl2 ON tbl1.ID = tbl2.post_id WHERE tbl1.ID = {$postID} AND tbl1.post_type LIKE 'wpcargo_shipment' AND tbl1.post_status LIKE 'publish' AND tbl2.meta_key LIKE 'wpc_shipment_type' AND tbl2.meta_value LIKE 'consolidation'";
    $result = $wpdb->get_var($sql);
    return $result;
}
function wpcshcon_get_consolidation_order( $consolidationID ){
    global $wpdb;
    $prefix 	= $wpdb->prefix;
    $consolidate_metakey = __( 'ID.', 'wpc-shipment-consoldation' );
    $sql = "SELECT tbl2.order_id FROM `{$prefix}woocommerce_order_itemmeta` AS tbl1 RIGHT JOIN `{$prefix}woocommerce_order_items` AS tbl2 ON tbl1.order_item_id = tbl2.order_item_id WHERE tbl1.meta_key LIKE '%".$consolidate_metakey."%' AND tbl1.meta_value = '{$consolidationID}' GROUP BY tbl2.order_id LIMIT 1";
    $orderID 	= $wpdb->get_var( $sql );
    return $orderID;
}
function wpcshcon_get_order_consolidation( $order ){
    global $wpdb;
    $consolidations = array();
    $prefix 	= $wpdb->prefix;
    $consolidate_metakey = __( 'ID.', 'wpc-shipment-consoldation' );
    $sql = "SELECT tbl2.meta_value FROM `{$prefix}woocommerce_order_items` as tbl1 INNER JOIN `{$prefix}woocommerce_order_itemmeta` AS tbl2 ON tbl1.order_item_id = tbl2.order_item_id WHERE tbl1.order_id = {$order} AND tbl2.meta_key LIKE '%".$consolidate_metakey."%' GROUP BY tbl1.order_item_id";
    $results	= $wpdb->get_col( $sql );
    if(  $results ){
        $consolidations = $results;
    }
    return $results;
}
function wpcshcon_get_shipment_count( $status, $authorID = null ){
    global $wpdb;
    if( !$authorID ){
        $authorID   = get_current_user_id();
    }
    $sql = "SELECT m.ID
    FROM {$wpdb->prefix}posts m
    INNER JOIN {$wpdb->prefix}postmeta m1
    ON ( m.ID = m1.post_id )
    INNER JOIN {$wpdb->prefix}postmeta m2
    ON ( m.ID = m2.post_id )
    INNER JOIN {$wpdb->prefix}postmeta m3
    ON ( m.ID = m3.post_id )
    WHERE
    m.post_type = 'wpcargo_shipment'
    AND m.post_status = 'publish'
    AND ( m1.meta_key like 'wpcargo_status' AND m1.meta_value LIKE '{$status}' )
    AND ( m2.meta_key like 'registered_shipper' AND m2.meta_value LIKE '{$authorID}' )
    AND ( m3.meta_key like 'wpc_shipment_type' AND m3.meta_value LIKE 'standard' )
    GROUP BY m.ID
    ORDER BY m.post_date
    DESC";
    $results = $wpdb->get_col( $sql );
    return count( $results );
}
function wpcshcon_get_user_last_wooorder( $userID ){
    global $wpdb;
    $sql = "SELECT tbl1.ID FROM `{$wpdb->prefix}posts` tbl1 INNER JOIN `{$wpdb->prefix}postmeta` tbl2 ON tbl1.ID = tbl2.post_id WHERE tbl1.post_type LIKE 'shop_order' AND tbl2.meta_key LIKE '_customer_user' AND tbl2.meta_value = {$userID}  ORDER BY tbl1.ID DESC LIMIT 1";
    $result = $wpdb->get_var($sql);
    return $result;
}
function get_shipment_storage_left( $shipmentID ){
    $post_date  = get_the_date( 'Y-m-d', $shipmentID );
    $current_date   = current_time( 'Y-m-d' );
    $post_date = date_create($post_date);
    $current_date = date_create($current_date);
    $datediff  = date_diff($post_date,$current_date);
    $day_consume = $datediff->format( '%d' );
    $remaining_days = PQ_SHIPMENT_CONSOLIDATION_STORAGE - $day_consume;
    return $remaining_days;
}
function wpcshcon_get_consolidated_shipments( $authorID = '', $shipmentType = 'standard', $limit = '', $awaiting = false ){
    global $wpdb;
    $default_status     = get_option( 'wpcshcon_default_status' );
    $awaiting_status    = wpcshcon_awaiting_status();
    if( !empty($limit) ){
        $limit = ' LIMIT '.$limit." ";
    }
    $sql = "SELECT m.ID ";
    $sql .= "FROM {$wpdb->prefix}posts m ";
    $sql .= "INNER JOIN {$wpdb->prefix}postmeta m1 ";
    $sql .= "ON ( m.ID = m1.post_id ) ";
    $sql .= "INNER JOIN {$wpdb->prefix}postmeta m2 ";
    $sql .= "ON ( m.ID = m2.post_id ) ";
    $sql .= "INNER JOIN {$wpdb->prefix}postmeta m3 ";
    $sql .= "ON ( m.ID = m3.post_id ) ";
    $sql .= "WHERE ";
    $sql .= "m.post_type = 'wpcargo_shipment' ";
    $sql .= "AND m.post_status = 'publish' ";
    $sql .= "AND ( m1.meta_key like 'wpc_shipment_type' AND m1.meta_value LIKE '{$shipmentType}' ) ";
    $sql .= "AND ( m2.meta_key like 'registered_shipper' AND m2.meta_value LIKE '{$authorID}' ) ";
    if( $awaiting ){
        if( $default_status ){
            $awaiting_status = "('".$awaiting_status."','".$default_status."')";
        }else{
            $awaiting_status = "('".$awaiting_status."')";
        }
        $sql .= "AND ( m3.meta_key like 'wpcargo_status' AND m3.meta_value IN {$awaiting_status} ) ";
    }
    $sql .= "GROUP BY m.ID ";
    $sql .= "ORDER BY m.post_date ";
    $sql .= "DESC";
    $sql .= $limit;
    $results = $wpdb->get_col( $sql );
    return $results;
}
function wpcshcon_shipment_weight( $post_id ){
    $packages       = maybe_unserialize( get_post_meta( $post_id, 'wpc-multiple-package', true) );
    $total_weight   = 0;
    if( !empty($packages) ){
        foreach ( $packages as $package ) {
            if( array_key_exists( 'wpc-pm-weight', $package ) ){
                $weight_unit  = is_numeric( $package['wpc-pm-weight'] ) ? $package['wpc-pm-weight'] : 0 ;
                $total_weight += $weight_unit;
            }
        }
    }
    return $total_weight;
}
function wpcshcon_get_book( ){
    global $wpdb;
    $prefix 	= $wpdb->prefix;
    $sql = "SELECT `post_id` FROM `{$prefix}posts` AS tbl1, `{$prefix}postmeta` AS tbl2 WHERE tbl1.ID = tbl2.post_id AND tbl1.post_author = ".get_current_user_id()." AND tbl1.post_type LIKE 'wpc_address_book' AND tbl2.meta_key LIKE 'book' AND tbl2.meta_value LIKE 'receiver'";
	$result = $wpdb->get_col( $sql );
	return $result;
}
function wpcshcon_get_ready_shipment( $authorID = '' ){
    global $wpdb;
    $ready_status       = get_option( 'wpcshcon_ready_status' );
    $sql_author         = '';
    $ready_consolidate  = '';
    if( !empty( $authorID ) ){
        $sql_author = " AND ( ( m3.meta_key LIKE 'registered_shipper' AND m3.meta_value LIKE '".$authorID."' ) OR ( m3.meta_key LIKE 'registered_receiver' AND m3.meta_value LIKE '".$authorID."' ) )" ;
    }
    if( !empty( $ready_status ) ){
        $ready_consolidate = " AND ( m2.meta_key LIKE 'wpcargo_status' AND m2.meta_value LIKE '".$ready_status."' ) " ;
    }
    $sql = "SELECT m.ID
    FROM {$wpdb->prefix}posts m
    INNER JOIN {$wpdb->prefix}postmeta m1
    ON ( m.ID = m1.post_id )
    INNER JOIN {$wpdb->prefix}postmeta m2
    ON ( m.ID = m2.post_id )
    INNER JOIN {$wpdb->prefix}postmeta m3
    ON ( m.ID = m3.post_id )
    WHERE
    m.post_type = 'wpcargo_shipment'
    AND m.post_status = 'publish'
    AND ( m1.meta_key like 'wpc_shipment_type' AND m1.meta_value LIKE 'standard' )
    ".$ready_consolidate."
    ".$sql_author."
    GROUP BY m.ID
    ORDER BY m.post_date
    DESC";
    $result = $wpdb->get_col( $sql );
	return $result;
}
function wpcshcon_get_option( $key = '', $defualt = '' ){
    $option = $defualt;
    if( !empty( get_option( $key ) ) ){
        $option = get_option( $key );
    }
    return $option;
}
function wpcshcon_pagination( $pagelink, $numpages, $paged ){
	$pagination_args = array(
        'base' => $pagelink . '%_%',
        'format' => 'page/%#%',
        'total' => $numpages,
        'current' => $paged,
        'show_all' => false,
        'end_size' => 1,
        'mid_size' => 4,
        'prev_next' => true,
        'prev_text' => '&laquo;',
        'next_text' => '&raquo;',
        'type' => 'plain',
        'add_args' => false,
        'add_fragment' => ''
    );
    $paginate_links  = paginate_links($pagination_args);
    if ($paginate_links) {
        echo "<nav class='wpcargo-custom-pagination'>";
        echo "<span class='page-numbers page-num'>".__('Page', 'wpc-shipment-consoldation' ).$paged. " ".__('of', 'wpc-shipment-consoldation' )." " . $numpages . "</span> ";
        echo $paginate_links;
        echo "</nav>";
    }
}
function wpcshcon_awaiting_status(){
    return apply_filters( 'wpcshcon_awaiting_status', __( 'Awaiting Payments', 'wpc-shipment-consoldation' ) );
}
function wpcshcon_shipping_methods(){
    $methods            = wpcshcon_default_shipping_method();
    $shipping_methods   = get_option( 'wpcshcon_shipping_methods' );
    $shipping_methods   = array_filter( array_map( 'trim', explode(',', $shipping_methods ) ) );
    if( !empty( $shipping_methods ) ){
        $methods = $shipping_methods;
    }
    return $methods;
}  
function wpcshcon_default_shipping_method(){
    $methods = array(
        __('DHL Express', 'wpc-shipment-consoldation' ),
        __('Aramex Economy','wpc-shipment-consoldation' ),
        __('UPS Express','wpc-shipment-consoldation' ),
        __('UPS Economy','wpc-shipment-consoldation' ),
        __('Less Expensive Method','wpc-shipment-consoldation' )
    );
    $methods = apply_filters( 'wpcshcon_default_shipping_method', $methods );
    return $methods;
}
function wpcshcon_package_options(){
    $options            = array();
    $package_options   = get_option( 'wpcshcon_package_options' );
    $package_options   = array_filter( array_map( 'trim', explode(',', $package_options ) ) );
    if( !empty( $package_options ) ){
        $options = $package_options;
    }
    return $options;
}
function wpcshcon_text_to_meta( $string = '' ){
    $string = preg_replace('/[^a-zA-Z]/', ' ', $string);
    $string = preg_replace('/\s+/', '_', trim( $string) );
    return strtolower( $string );
}
function wpc_shipment_consolidation_get_frontend_page(){
	global $wpdb;
	$sql 			= "SELECT `ID` FROM {$wpdb->prefix}posts WHERE `post_content` LIKE '%[wpcshcon_dashboard]%' AND `post_status` LIKE 'publish' LIMIT 1";
	$shortcode_id 	= $wpdb->get_var( $sql );
	if( ! $shortcode_id ){
		// Create post object
		$shipment_consolidation = array(
			'post_title'    => wp_strip_all_tags( __('Consolidated Shipments', 'wpc-import-export') ),
			'post_content'  => '[wpcshcon_dashboard]',
			'post_status'   => 'publish',
			'post_type'   	=> 'page',
		);	
		// Insert the post into the database
		$shortcode_id = wp_insert_post( $shipment_consolidation );		
	}
	if( $shortcode_id ){
		update_post_meta( $shortcode_id, '_wp_page_template', 'dashboard.php');
	}
	return $shortcode_id;
}
function wpc_consolidate_get_frontend_page(){
	global $wpdb;
	$sql 			= "SELECT `ID` FROM {$wpdb->prefix}posts WHERE `post_content` LIKE '%[wpcshcon_consolidate_shipments]%' AND `post_status` LIKE 'publish' LIMIT 1";
	$shortcode_id 	= $wpdb->get_var( $sql );
	if( ! $shortcode_id ){
		// Create post object
		$consolidate_shipment = array(
			'post_title'    => wp_strip_all_tags( __('Consolidate', 'wpc-import-export') ),
			'post_content'  => '[wpcshcon_consolidate_shipments]',
			'post_status'   => 'publish',
			'post_type'   	=> 'page',
		);	
		// Insert the post into the database
		$shortcode_id = wp_insert_post( $consolidate_shipment );		
	}
	if( $shortcode_id ){
		update_post_meta( $shortcode_id, '_wp_page_template', 'dashboard.php');
	}
	return $shortcode_id;
}
//** Wpcargo Filters and Hooks */
function wpcshcon_get_products(){
    global $wpdb;
    $sql        = "SELECT `ID` FROM {$wpdb->prefix}posts WHERE `post_status` LIKE 'publish' AND `post_type` LIKE 'product'";
    $results    = $wpdb->get_col($sql);
    return $results;
}
add_filter('wpcargo_status_option', function( $status ){
    $status[] = wpcshcon_awaiting_status();
    return $status;
},10,1);
// Noticess Hook
add_action( 'admin_notices', 'wpcshcon_plugin_settings_notice' );
function wpcshcon_plugin_settings_notice() {
    if( !get_option( 'wpcshcon_product' ) ){
        ?>
        <div class="notice notice-error">
            <p><?php _e( 'Please Setup Shipment Consolidation Settings for Select Product for Shipment Consolidation Payment', 'wpc-shipment-consoldation' ); ?> <a href="<?php echo admin_url('admin.php?page=wpcshcon-settings'); ?>"><?php _e('Here', 'wpc-shipment-consoldation' ); ?></a></p>
        </div>
        <?php
    }
}
add_action( 'show_user_profile', 'wpcshcon_consolidation_code_profile_fields' );
add_action( 'edit_user_profile', 'wpcshcon_consolidation_code_profile_fields' );
function wpcshcon_consolidation_code_profile_fields( $user ) {
    $current_user   = wp_get_current_user();
    $user_roles     = $current_user->roles;
    if( in_array( 'administrator', $user_roles ) ){
        ?>
        <h3><?php _e('Storage Code', 'wpc-shipment-consoldation' ); ?></h3>
        <table class="form-table">
            <tr>
                <th><label for="consolidation_code"><?php _e('Code', 'wpc-shipment-consoldation' ); ?></label></th>
                <td>
                    <input type="text" name="consolidation_code" id="consolidation_code" value="<?php echo wpcshcon_get_user_consolidation_code( $user->ID ); ?>" class="regular-text" />
                </td>
            </tr>
        </table>
        <?php
    }
}
add_action( 'personal_options_update', 'wpcshcon_save_consolidation_code_profile_fields' );
add_action( 'edit_user_profile_update', 'wpcshcon_save_consolidation_code_profile_fields' );
function wpcshcon_save_consolidation_code_profile_fields( $user_id ) {
    $current_user   = wp_get_current_user();
    $user_roles     = $current_user->roles;
	if ( !current_user_can( 'edit_user', $user_id ) ){
		return false;
    }
    if( in_array( 'administrator', $user_roles ) ){
        /* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
        update_usermeta( $user_id, 'consolidation_code', sanitize_text_field( $_POST['consolidation_code'] ) );
    }
}
//** Ajax Hook
add_action( 'wp_ajax_wpcshcon_update_profile', 'wpcshcon_update_profile_callback' );
add_action( 'wp_ajax_nopriv_wpcshcon_update_profile', 'wpcshcon_update_profile_callback' );
function wpcshcon_update_profile_callback(){
    $userID = get_current_user_id();
    check_ajax_referer( 'update_profile_nonce', 'wpnonce' );
    if( !empty( $_POST ) ){
        foreach ($_POST as $key => $value) {
            if( $key == 'action' || $key == 'wpnonce' ){
                continue;
            }
            update_user_meta( $userID, $key, sanitize_text_field( $value ) );
        }
    }
    wp_die( );
}
function wpcshcon_upload_avatar_callback(){
	$upload_dir       = wp_upload_dir();
	// @new
	$upload_path      = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;
	$img = $_POST['imageData'];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$decoded          = base64_decode($img) ;
	$filename         = get_current_user_id().'.png';
	$hashed_filename  = md5( $filename . microtime() ) . '_' . $filename;
	// @new
	$image_upload     = file_put_contents( $upload_path . $hashed_filename, $decoded );
	//HANDLE UPLOADED FILE
	if( !function_exists( 'wp_handle_sideload' ) ) {
	  require_once( ABSPATH . 'wp-admin/includes/file.php' );
	}
	// Without that I'm getting a debug error!?
	if( !function_exists( 'wp_get_current_user' ) ) {
	  require_once( ABSPATH . 'wp-includes/pluggable.php' );
	}
	// @new
	$file             = array();
	$file['error']    = '';
	$file['tmp_name'] = $upload_path . $hashed_filename;
	$file['name']     = $hashed_filename;
	$file['type']     = 'image/png';
	$file['size']     = filesize( $upload_path . $hashed_filename );
	// upload file to server
	// @new use $file instead of $image_upload
	$file_return      = wp_handle_sideload( $file, array( 'test_form' => false ) );
	$filename = $file_return['file'];
	$attachment = array(
	 'post_mime_type' => $file_return['type'],
	 'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
	 'post_content' => '',
	 'post_status' => 'inherit',
	 'guid' => $wp_upload_dir['url'] . '/' . basename($filename)
	 );
	$attach_id = wp_insert_attachment( $attachment, $filename );
	require_once(ABSPATH . 'wp-admin/includes/image.php');
	$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
	wp_update_attachment_metadata( $attach_id, $attach_data );
	$avatar_url = wp_get_attachment_url( $attach_id );
	update_user_meta( get_current_user_id(), 'wpcargo_user_avatar', $avatar_url );
	echo '<img alt="" src="'.$avatar_url.'" srcset="'.$avatar_url.'" class="avatar avatar-128 photo photo-inner" height="128" width="128">';
	wp_die();
}
add_action( 'wp_ajax_wpcshcon_upload_avatar', 'wpcshcon_upload_avatar_callback' );
add_action( 'wp_ajax_nopriv_wpcshcon_upload_avatar', 'wpcshcon_upload_avatar_callback' );
function wpcshcon_override_avatar ($avatar_html, $userid, $size, $default, $alt) {
	if( is_admin() ){
		$avatar_size = '32';
	}else{
		$avatar_size = '128';
	}
	$wpcdm_user_avatar = get_user_meta( $userid, 'wpcargo_user_avatar', true );
	if( $wpcdm_user_avatar ){
		$avatar_html = '<img alt="" src="'.$wpcdm_user_avatar.'" srcset="'.$wpcdm_user_avatar.'" class="avatar avatar-'.$avatar_size.' photo photo-inner" height="'.$avatar_size.'" width="'.$avatar_size.'">';
	}
   	return $avatar_html;
}
add_filter ('get_avatar', 'wpcshcon_override_avatar', 10, 5);
function wpcshcon_user_avatar( $size = 128 ){
	$current_user = wp_get_current_user();
	echo get_avatar( $current_user->ID, 128, '', '', array( 'class'=> 'photo-inner' ) );
}
add_action('manage_users_columns','wpcshcon_consolidation_code_table_header');
function wpcshcon_consolidation_code_table_header($column_headers) {
    $column_headers['consolidation_code'] = __('Storage Code', 'wpc-shipment-consoldation' );
    return $column_headers;
}
add_action('manage_users_custom_column', 'wpcshcon_consolidation_code_table_data', 10, 3);
function wpcshcon_consolidation_code_table_data($value, $column_name, $user_id) {
  if ( 'consolidation_code' == $column_name ) {
    $value = get_user_meta( $user_id, 'consolidation_code', true );
  }
  return $value;
}
function wpcshcon_plugin_loaded_callback(){
    add_action( 'wpcfe_before_personal_information', 'wpcshcon_profile_warehouse_address' );
}
function wpcshcon_profile_warehouse_address( $user_id ){
    $whs_name  			= get_option( 'wpcshcon_warehouse_name' );
    $warehouse_address  = get_option( 'wpcshcon_warehouse_address');
    $consolidation_code = get_user_meta( $user_id, 'consolidation_code', true );
    ?>
    <div id="warehouse-info" class="my-4">
        <h4><?php _e("Warehouse Address", 'wpc-shipment-consoldation' ); ?></h4>
        <address style="padding:12px 12px 0;color:#636363;border:1px solid #e5e5e5;">
            <?php
            if( !$consolidation_code ){
                ?>
                <div class="alert alert-danger" role="alert">
                    <?php _e("You don't have Storage Code for your warehouse, Please contact our support.", 'wpc-shipment-consoldation' ); ?>
                </div>
                <?php
            }
            if( $whs_name ){
                echo '<strong class="mr-4">'.apply_filters( 'wpcshcon_consolidation_whs_name', __('Warehouse Name', 'wpc-shipment-consoldation' ) ).':</strong> '.$whs_name.'<br/>';
            }
            echo '<strong class="mr-4">'.apply_filters( 'wpcshcon_consolidation_code_address', __('Storage Code', 'wpc-shipment-consoldation' ) ).':</strong> '.trim( $consolidation_code ).'<br/>';
            foreach ( $warehouse_address as $key => $value ) {
                if( $key == 'city' ){
                    echo '<strong class="mr-4">'.apply_filters( 'wpcshcon_consolidation_code_address', __('Address 2', 'wpc-shipment-consoldation' ) ).':</strong> '.trim( $consolidation_code ).'<br/>';
                }
                echo '<strong class="mr-4">'.ucfirst( str_replace('_', ' ', $key ) ).':</strong> '.trim( $value ).'<br/>';
            }
            ?>
        </address>
    </div>
    <?php
}
function wpcshcon_email_package_content( $post_id ){
    ob_start();
    $filename               = 'mail-item-package.tpl.php';
    $custom_template_path   = get_stylesheet_directory().'/shipment-consolidation/'.$filename ; 
    $package_list       = maybe_unserialize( get_post_meta( $post_id, 'wpc-multiple-package', true) );
    $options            = get_option( 'wpc_mp_settings' );
    $dimension_unit     = !empty($options['wpc_mp_dimension_unit']) ? $options['wpc_mp_dimension_unit'] : 'cm';
    $weight_unit        = !empty($options['wpc_mp_weight_unit']) ? $options['wpc_mp_weight_unit'] : 'kg';
    if( file_exists( $custom_template_path ) ){
        $template_path = $custom_template_path;
    }else{
        $template_path  = include_once( PQ_SHIPMENT_CONSOLIDATION_PATH.'/admin/templates/'.$filename );
    }
    include_once( $template_path );
    return ob_get_clean();
}
function wpcshcon_email_content( $post_id ){
    ob_start();
    $filename               = 'mail-message.tpl.php';
    $custom_template_path   = get_stylesheet_directory().'/shipment-consolidation/'.$filename ;    
    $shipper_shortcode      = get_option('shipper_column') ?  get_option('shipper_column') : 'wpcargo_shipper_name' ;
    $store_location         = get_post_meta( $post_id, 'wpcshcon_store', true );
    if( file_exists( $custom_template_path ) ){
        $template_path = $custom_template_path;
    }else{
        $template_path  = PQ_SHIPMENT_CONSOLIDATION_PATH.'/admin/templates/'.$filename;
    }
    include_once( $template_path );
    return ob_get_clean();
}
function wpcshcon_consolidated_email_content( $post_id ){
    ob_start();
    $filename               = 'mail-message-consolidated.tpl.php';
    $custom_template_path   = get_stylesheet_directory().'/shipment-consolidation/'.$filename ;    
    $shipper_shortcode      = get_option('shipper_column') ?  get_option('shipper_column') : 'wpcargo_shipper_name' ;
    $store_location         = get_post_meta( $post_id, 'wpcshcon_store', true );
    $cost                   = get_woocommerce_currency_symbol().wpcshcon_get_consolidation_cost( $post_id );
    $shipments              = maybe_unserialize( get_post_meta( $post_id, 'wpcshcon_shipments', true ) );
    $total_weight           = 0;
    if( !empty( $shipments ) ){
        foreach( $shipments as $shipment ){
            $total_weight = wpcshcon_shipment_weight( $shipment );
        }
    }
    $total_weight = $total_weight.wpcshcon_weight_unit();
    if( file_exists( $custom_template_path ) ){
        $template_path = $custom_template_path;
    }else{
        $template_path  = PQ_SHIPMENT_CONSOLIDATION_PATH.'/admin/templates/'.$filename;
    }
    include_once( $template_path );
    return ob_get_clean();
}
function wpcshcon_include_template( $file_name ){
    $custom_template_path   = get_stylesheet_directory().'/wpcargo/wpcargo-shipment-consolidation-addon/'.$file_name.'.php';
    if( file_exists( $custom_template_path ) ){
        $template_path = $custom_template_path;
    }else{
        $template_path  = PQ_SHIPMENT_CONSOLIDATION_PATH.'templates/'.$file_name.'.php';
    }
	return $template_path;
}
function wpcshcon_send_item_notification_email( ){
    global $wpcargo;
    $user_id        = get_current_user_id();
    $user_name      = $wpcargo->user_fullname($user_id);
    $post_id        = $_POST['postID'];
    $shipment_type  = $_POST['shipmentType'];
    $str_find       = array_keys( wpcargo_email_shortcodes_list() );
    $str_replce     = wpcargo_email_replace_shortcodes_list( $post_id );
    $mail_domain    = !empty( trim( get_option('wpcargo_admin_mail_domain') ) ) ? get_option('wpcargo_admin_mail_domain') : get_option( 'admin_email' ) ;
    
    if( $shipment_type == 'standard' ){
        $mail_content   = wpcshcon_email_content( $post_id );
        $mail_content   .= wpcshcon_email_package_content( $post_id );
        $subject        = apply_filters( 'wpcshcon_item_email_subject', __('Item Notification', 'wpc-shipment-consoldation' ).' - '.get_bloginfo('name') );
    }else{
        $mail_content   = wpcshcon_consolidated_email_content( $post_id );
        $subject        = apply_filters( 'wpcshcon_consolidated_email_subject', __('Consolidate Notification', 'wpc-shipment-consoldation' ).' - '.get_bloginfo('name') );
    }
    
    $mail_footer    = $wpcargo->client_mail_footer;
    $headers        = array();
    $headers[]      = 'From: ' . get_bloginfo('name') .' <'.$mail_domain.'>';
    $headers[]      = 'Bcc: '.get_option( 'admin_email' )."\r\n"; 
    $send_to        = str_replace($str_find, $str_replce, $wpcargo->client_mail_to );
    $message        = str_replace($str_find, $str_replce, wpcargo_email_body_container( $mail_content, $mail_footer ) ); 
    $email_sent     = wp_mail( $send_to, $subject, $message, $headers );
    if( $email_sent  ){
        $wpcshcon_item_notification = maybe_unserialize( get_post_meta( $post_id, 'wpcshcon_item_notification', true ) );
        $wpcshcon_item_notification = !empty( $wpcshcon_item_notification ) ? $wpcshcon_item_notification : array() ;
        $wpcshcon_item_notification[] = array(
            current_time('mysql'),
            $user_name,
            __('send email notification ', 'wpc-shipment-consoldation' )
        );
        update_post_meta( $post_id, 'wpcshcon_item_notification', maybe_serialize( $wpcshcon_item_notification ) );
    }
    wp_die( $email_sent );
}
add_action( 'wp_ajax_notify_item_customer', 'wpcshcon_send_item_notification_email' );
add_action( 'wp_ajax_nopriv_notify_item_customer', 'wpcshcon_send_item_notification_email' );
/*
 * Language Translation for the Ecncrypted Files
 */
function wpcshcon_activate_license_message(){
    return __( 'WPCargo Shipment Consolidation Add on plugin need License, Please activate your license key', 'wpc-shipment-consoldation' ).' <a href="'.admin_url().'admin.php?page=wptaskforce-helper" title="WPCargo license page">'.__('here', 'wpc-shipment-consoldation' ).'</a>.';
}
function wpcshcon_no_access_message(){
    return __('Sorry you are not allowed to access this Consolidation', 'wpc-shipment-consoldation' );
}
function wpcshcon_ready_label(){
    return __('Ready', 'wpc-shipment-consoldation' );
}
function wpcshcon_processing_label(){
    return __('Processing', 'wpc-shipment-consoldation' );
}
function wpcshcon_on_hold_label(){
    return __('On Hold', 'wpc-shipment-consoldation' );
}
function wpcshcon_consolidation_nos_label(){
    return __('Consolidation Numbers:', 'wpc-shipment-consoldation' );
}
function wpcshcon_consolidation_no_label(){
    return __('Consolidate No.', 'wpc-shipment-consoldation' );
}
function wpcshcon_consolidation_id_label(){
    return __('ID.', 'wpc-shipment-consoldation' );
}
function wpcshcon_license_plugin_dependent_message(){
    return __('This plugin requires', 'wpc-shipment-consoldation').' <a href="http://wpcargo.com/" target="_blank">WPTaskForce License Helper</a> '.__('plugin to be active!', 'wpc-shipment-consoldation' );
}
function wpcshcon_wpcargo_plugin_dependent_message(){
    return __('This plugin requires', 'wpc-shipment-consoldation').' <a href="https://wordpress.org/plugins/wpcargo/" target="_blank">WPCargo</a> '.__('plugin to be active!', 'wpc-shipment-consoldation' );
}
function wpcshcon_client_account_plugin_dependent_message(){
    return __('This plugin requires', 'wpc-shipment-consoldation').' <strong>WPCargo Client Accounts Add-ons</strong> '.__('plugin to be active!', 'wpc-shipment-consoldation' );
}
function wpcshcon_custom_field_plugin_dependent_message(){
    return __('This plugin requires', 'wpc-shipment-consoldation').' <strong>WPCargo Custom Field Add-ons</strong> '.__('plugin to be active!', 'wpc-shipment-consoldation' );
}
function wpcshcon_woocommerce_plugin_dependent_message(){
    return __('This plugin requires', 'wpc-shipment-consoldation').' <strong>Woocommerce</strong> '.__('plugin to be active!', 'wpc-shipment-consoldation' );
}
function wpcshcon_cheating_plugin_dependent_message(){
    return __('Cheating, uh?', 'wpc-shipment-consoldation');
}