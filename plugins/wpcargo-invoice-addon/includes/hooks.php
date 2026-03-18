<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
// Table Header
function wpcinvoice_table_header_invoice(){
    ?><th class="wpcinv_header-invoice"><?php echo wpcinvoice_number_label(); ?></th><?php
}
function wpcinvoice_table_header_shipment(){
    ?><th class="wpcinv_header-shipment"><?php echo wpcinvoice_shipment_label(); ?></th><?php
}
function wpcinvoice_table_header_shipment_type(){
    ?><th class="wpcinv_header-shipment-type"><?php echo wpcinvoice_shipment_type_label(); ?></th><?php
}
function wpcinvoice_table_header_orderno(){
    ?><th class="wpcinv_header-orderno"><?php echo wpcinvoice_order_no_label(); ?></th><?php
}
function wpcinvoice_table_header_status(){
    ?><th class="wpcinv_header-status"><?php echo wpcinvoice_status_label(); ?></th><?php
}
function wpcinvoice_table_header_update(){
    ?><th class="wpcinv_header-update text-center"><?php echo __('Update', 'wpcargo-invoice'); ?></th><?php
}
function wpcinvoice_table_header_print(){
    $print_options = wpcfe_print_options();
    if( empty( $print_options ) ) return false;
    ?><th class="wpcinv_header-print text-center"><?php echo __('Print', 'wpcargo-invoice'); ?></th><?php
}
// Table Data
function wpcinvoice_table_data_invoice( $shipment_id, $invoice_id ){
    $invoice_id     = wpcinvoice_number( $shipment_id );
    $invoice_number = $invoice_id ? get_the_title( $invoice_id ) : '';
    ?><td class="wpcinv_data-invoice font-weight-bold"><?php echo $invoice_number; ?></td><?php
}
function wpcinvoice_table_data_shipment( $shipment_id, $invoice_id ){
    $page_url       = get_the_permalink( wpcfe_admin_page() );
    $ship_number    = get_the_title( $shipment_id );
    ?><td class="wpcinv_data-shipment"><a href="<?php echo $page_url; ?>?wpcfe=track&num=<?php echo $ship_number; ?>" target="_blank" class="text-primary"><?php echo $ship_number; ?></a></td><?php
}
function wpcinvoice_table_data_shipment_type( $shipment_id, $invoice_id ){
    $shipment_type  = wpcfe_get_shipment_type( $shipment_id  );
    ?><td class="wpcinv_data-shipment"><?php echo $shipment_type; ?></td><?php
}
function wpcinvoice_table_data_orderno( $shipment_id, $invoice_id ){
    $order_id = wpcinvoice_get_invoice_order( $shipment_id );
    ?>
    <td class="wpcinv_data-orderno">
        <?php echo $order_id ? '#'.$order_id : ''; ?><br/>
        <?php echo $order_id ? wpcinvoice_get_order_data( $order_id )->status : ''; ?>
    </td>
    <?php
}
function wpcinvoice_table_data_status( $shipment_id, $invoice_id ){
    $invoice_id     = wpcinvoice_number( $shipment_id );
    $invoice_num    = get_the_title( $invoice_id );
    ?>
    <td class="wpcinv_data-status">
        <a href="#" data-id="<?php echo $invoice_id; ?>" data-sid="<?php echo $shipment_id; ?>" data-number="<?php echo $invoice_num; ?>" class="wpcinvoice-update btn btn-info btn-sm py-1 px-2 mr-2"data-toggle="modal" data-target="#invoiceUpdateModal" title="<?php echo __('Edit', 'wpcargo-invoice'); ?>"><i class="fa fa-edit text-white"></i></a>
        <?php echo wpcinvoice_status( $invoice_id ); ?> 
    </td>
    <?php
}
function wpcinvoice_table_data_update( $shipment_id, $invoice_id ){
    $page_url       = get_the_permalink( wpcinvoice_dashboard_page() );
    ?><td class="wpcinv_data-update text-center"><a href="<?php echo $page_url; ?>?wpcinvoice=update&id=<?php echo $shipment_id; ?>" class="text-primary" title="<?php echo __('Update', 'wpcargo-invoice'); ?>"><i class="fa fa-edit text-info"></i></a></td><?php
}
function wpcinvoice_table_data_print( $shipment_id, $invoice_id ){
    $print_options = wpcfe_print_options();
    if( empty( $print_options ) ) return false;  
    // wpci-paid
    $status  = get_post_meta( $invoice_id, '__wpcinvoice_status', true );
    if( $status != 'wpci-paid' ){
        unset( $print_options['waybill'] );
    }
    ?>
    <td class="text-center print-shipment">
        <div class="dropdown" style="display:inline-block !important;">
            <!--Trigger-->
            <button class="btn btn-default btn-sm dropdown-toggle m-0 py-1 px-2" type="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false"><i class="fa fa-print"></i></button>
            <!--Menu-->
            <div class="dropdown-menu dropdown-primary">
                <?php foreach( $print_options as $print_key => $print_label ): ?>
                    <a class="dropdown-item print-<?php echo $print_key; ?> py-1" data-id="<?php echo $shipment_id; ?>" data-type="<?php echo $print_key; ?>" data-status="<?php echo $status; ?>" href="#"><?php echo $print_label; ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </td>
    <?php
}
function wpcinvoice_package_fields_callback( $fields ){   
    if( wpcinvoice_include_packages_cost() ){
        $currency = !empty( wpcinvoice_currency() )? '('.wpcinvoice_currency().')' : '';
        $fields[wpcinvoice_unit_price_key()] = array(
            'label' => __('Shipping Cost '.$currency, 'wpcargo-invoice'),
            'field' => 'number',
            'required' => '',
            'options' => array()
        );
        $fields[wpcinvoice_unit_amount_key()] = array(
            'label' => __('Cost '.$currency, 'wpcargo-invoice'),
            'field' => 'number',
            'required' => '',
            'options' => array()
        );
    }
    return $fields;
}
function wpcinvoice_shipment_sections_callback( $formatted_section ){
    $exclude_sections = array(
        'shipper_info',
        'shipment_info'
    );
    foreach( $exclude_sections as $section ){
        if( array_key_exists( $section , $formatted_section ) ){
            unset( $formatted_section[$section] );
        }
    }
    return $formatted_section;
}
function after_wpcinvoice_shipment_form_fields_callback( $shipment_id ){
    $wpcargo_settings = !empty( get_option('wpc_mp_settings') ) ? get_option('wpc_mp_settings') : array();
    if( !array_key_exists( 'wpc_mp_enable_admin', $wpcargo_settings ) ){
        return false;
    }
    $user_roles = wpcfe_current_user_role();
    if( !( in_array( 'cargo_agent', (array)$user_roles ) ) && !( in_array( 'wpcargo_driver', (array)$user_roles ) ) ){
        $shipment       = new stdClass();
        $shipment->ID   = $shipment_id;
        $template = wpcinvoice_locate_template( 'multiple-package.tpl' );
        require_once( $template );
    }
}
function wpcinvoice_after_package_details_callback( $shipment ){
    global $post, $wpcargo;
    if( !empty( $shipment ) && wpcinvoice_dashboard_page() == $post->ID ){
        $current_user   = wp_get_current_user();
        $user_roles     = $current_user->roles;
        $colspan        = count( wpcargo_package_fields() ) - 1;
        $pkg_totals     = wpcinvoice_get_total_value( $shipment->ID );        
        $form_control   = is_admin() ? '' : 'form-control';
        $text_color     = '';
        
        if( array_intersect( array( 'administrator', 'wpcargo_employee' ), $user_roles) ){
            if( !empty( wpcinvoice_total_fields() ) ){
                foreach( wpcinvoice_total_fields() as $field_key => $fields ){
                    $value = array_key_exists( $field_key, $pkg_totals )? $pkg_totals[$field_key] : 0;
                    $readonly = ( $fields['readonly'] ) ? 'readonly' : '';
                    $input_id = $field_key;
                    if( $field_key == 'total' ){
                        $text_color = ' text-danger';
                    }
                    ?>
                    <tr class="total-detail">
                        <td class="label <?php echo $text_color; ?>" colspan="<?php echo $colspan; ?>" align="right" style="vertical-align: middle;"><strong><?php echo $fields['label']; ?></strong></td>
                        <td class="value" colspan="1">
                            <?php
                                printf(
                                    '<input type="%s" id="%s" class="number %s" name="%s" value="%s" %s />',
                                    $fields['field'],
                                    $input_id,
                                    $form_control.$text_color,
                                    $field_key,
                                    wpcinvoice_format_value( $value, false ),
                                    $readonly
                                );
                            ?>
                        </td>
                        <?php if( !is_admin() ): ?>
                            <td>&nbsp;</td>
                        <?php endif; ?>
                    </tr>
                    <?php
                }
            }
            do_action( 'wpcinvoice_additional_details_script' );
        }
    }
}
function wpcinvoice_form_shipment_title( $shipment_id ){
    global $wpcargo;
    if( !array_key_exists( 'wpcargo_title_prefix_action', $wpcargo->settings ) || !(int)$shipment_id ){
        return false;
    }
    $invoice_id     = wpcinvoice_number( $shipment_id );
    $status         = get_post_meta( $invoice_id, '__wpcinvoice_status', true );
    ?>
    <div class="col-md-12 mb-3">
        <div class="card">
            <div class="row">
                <div class="col-md-1 p-0 card-header"></div>
                <div class="col-md-10 p-0">
                    <div class="card-header text-center">
                        <?php echo apply_filters( 'wpcinvoice_shipment_number_label', __('Shipment Number', 'wpcargo-invoice' ) ); ?>
                        <h5><?php echo get_the_title( $shipment_id ); ?></h5>
                    </div>
                </div>
                <div class="col-md-1 p-0 pt-4 card-header text-center">
                    <div id="print-invoice" class="wpcinvoice-print" data-id="<?php echo $shipment_id; ?>" data-status="<?php echo $status; ?>"><i class="fa fa-print" title="<?php esc_html_e('Print Invoice', 'wpcargo-invoice');?>"></i></div>
                </div>
            </div>          
        </div>
    </div>
    <?php
}
// Save WPCargo Custom fields
function wpcinvoice_update_shipment(){
    global $WPCCF_Fields;
    if ( isset( $_POST['wpcinvoice_form_fields'] ) && wp_verify_nonce( $_POST['wpcinvoice_form_fields'], 'wpcinvoice_edit_action' ) && isset( $_POST['shipment_id'] ) && is_wpcinvoice_shipment( $_POST['shipment_id'] ) ) {
        wpcinvoice_save_shipment( $_POST, $_POST['shipment_id'] );      
    }
}
// Save Multiple Package data
function wpcinvoice_shipment_multipackage_save( $post_id, $data ){
    if( empty( $data ) || !is_array( $data ) ){
        return false;
    }
   $packages = array_key_exists( 'wpc-multiple-package', $data ) ? maybe_serialize( $data['wpc-multiple-package'] ) : maybe_serialize( array() );
   update_post_meta( $post_id, 'wpc-multiple-package', $packages );
}
function wpcinvoice_template_path_callback( $template_path ){
    return WPC_INVOICE_PATH.'templates/invoice.tpl.php';
}
function wpcinvoice_hooks_filters_callback(){
    // Wordpres hooks
    add_action( 'wp', 'wpcinvoice_update_shipment' );
    // WPCargo Free
    add_filter( 'wpcargo_package_fields', 'wpcinvoice_package_fields_callback' );
    add_action( 'wpcinvoice_after_package_table_row', 'wpcinvoice_after_package_details_callback', 20, 2 );
    // Invoice table data hooks - Header
    add_action( 'wpcinvoice_table_header', 'wpcinvoice_table_header_invoice' );
    add_action( 'wpcinvoice_table_header', 'wpcinvoice_table_header_shipment' );
    add_action( 'wpcinvoice_table_header', 'wpcinvoice_table_header_shipment_type' );
    if ( class_exists( 'WooCommerce' ) ) {
        add_action( 'wpcinvoice_table_header', 'wpcinvoice_table_header_orderno', 10, 2 );
    }
    add_action( 'wpcinvoice_table_header', 'wpcinvoice_table_header_status' );
    add_action( 'wpcinvoice_table_header', 'wpcinvoice_table_header_update' );
    add_action( 'wpcinvoice_table_header', 'wpcinvoice_table_header_print' );
    // Invoice table data hooks - Data
    add_action( 'wpcinvoice_table_data', 'wpcinvoice_table_data_invoice', 10, 2 );
    add_action( 'wpcinvoice_table_data', 'wpcinvoice_table_data_shipment', 10, 2 );
    add_action( 'wpcinvoice_table_data', 'wpcinvoice_table_data_shipment_type', 10, 2 );
    if ( class_exists( 'WooCommerce' ) ) {
        add_action( 'wpcinvoice_table_data', 'wpcinvoice_table_data_orderno', 10, 2 );
    }
    add_action( 'wpcinvoice_table_data', 'wpcinvoice_table_data_status', 10, 2 );
    add_action( 'wpcinvoice_table_data', 'wpcinvoice_table_data_update', 10, 2 );
    add_action( 'wpcinvoice_table_data', 'wpcinvoice_table_data_print', 10, 2 );
    // FM Hooks
    // add_filter( 'invoice_template_path', 'wpcinvoice_template_path_callback', 999 );
    add_filter( 'wpcfe_print_template_path_invoice', 'wpcinvoice_template_path_callback', 999 );
    add_filter( 'wpcinvoice_shipment_sections', 'wpcinvoice_shipment_sections_callback', 10, 1 );
    add_action( 'after_wpcinvoice_shipment_form_fields', 'after_wpcinvoice_shipment_form_fields_callback', 10, 1 );
    // Invoice Hooks
    add_action( 'after_wpcinvoice_save_shipment', 'wpcinvoice_shipment_multipackage_save', 10, 2 );
    add_action( 'before_wpcinvoice_shipment_form_fields', 'wpcinvoice_form_shipment_title', 1 );
    // FM Scripts
    add_filter( 'wpcfe_registered_styles', 'wpcinvoice_registered_styles' );
    add_filter( 'wpcfe_registered_scripts', 'wpcinvoice_registered_scripts' );
}
add_action( 'plugins_loaded', 'wpcinvoice_hooks_filters_callback' );
// Load the auto-update class
function wpcinvoice_get_plugin_remote_update(){
    require_once( WPC_INVOICE_PATH.'includes/autoupdate.php');
    $plugin_remote_path = 'http://wpcargo.com/repository/wpcargo-invoice-addon/'.WPC_INVOICE_UPDATE_REMOTE.'.php';
    return new WPCINVOICE_AutoUpdate ( WPC_INVOICE_VERSION, $plugin_remote_path, WPC_INVOICE_BASENAME );

}
function wpcinvoice_activate_au(){
    wpcinvoice_get_plugin_remote_update();
}
function wpcinvoice_plugin_update_message( $data, $response ) {
	$autoUpdate 	= wpcinvoice_get_plugin_remote_update();
	$remote_info 	= $autoUpdate->getRemote('info');
	if( !empty( $remote_info->update_message ) ){
		echo $remote_info->update_message;
	}
}
add_action( 'in_plugin_update_message-wpcargo-invoice-addon/wpcargo-invoice-addon.php', 'wpcinvoice_plugin_update_message', 10, 2 );
add_action( 'init', 'wpcinvoice_activate_au' );