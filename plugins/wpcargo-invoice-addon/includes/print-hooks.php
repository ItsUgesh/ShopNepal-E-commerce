<?php
function wpcinvoice_comapany_info_callback( $shipmentDetails, $invoice_options, $str_find,  $str_replace ){
	$options 		= get_option('wpcargo_option_settings');
	$baseColor 		= '#000';
	if( $options ){
		if( array_key_exists('wpcargo_base_color', $options) ){
			$baseColor = ( $options['wpcargo_base_color'] ) ? $options['wpcargo_base_color'] : $baseColor ;
		}
	}
	$invoice_options    = get_option( 'wpcinvoice_settings' );
	$str_find           = array_keys( wpcinvoice_shortcodes_list() );
	$str_replace        = wpcinvoice_replace_shortcodes_list( $shipmentDetails['shipmentID'] );
	$company_name       = get_bloginfo('name');
	$logo_url           = $shipmentDetails['cargoSettings']['settings_shipment_ship_logo'];
	$company_info 		= wpcinvoice_display_options( $invoice_options, 'company_address' );
	if( empty( $company_info ) ){
		$company_info = wpcinvoice_default_company_addresss();
	}
	$header_details = $logo_url ? '<img style="width: 100%" src="'.$logo_url.'"/>' : '<h3 style="color:'.$baseColor.';font-size: 48px !important; font-weight: 900;" >'.$company_name.'</h3>' ;
	?>
	<td width="50%">
		<table style="width: 100%">
	        <tr>
	            <td colspan="2" valign="top"><?php echo $header_details; ?></td>
	        </tr>
	        <tr>
	            <td colspan="2" valign="top">
	                <?php echo str_replace( $str_find, $str_replace, $company_info ); ?>
	            </td>
	        </tr>
	    </table>                 
    </td>
	<?php
}
function wpcinvoice_invoicing_info_callback( $shipmentDetails, $invoice_options, $str_find,  $str_replace ){
	$invoice_options    = get_option( 'wpcinvoice_settings' );
	$str_find           = array_keys( wpcinvoice_shortcodes_list() );
	$str_replace        = wpcinvoice_replace_shortcodes_list( $shipmentDetails['shipmentID'] );
	$company_invoice	= wpcinvoice_display_options( $invoice_options, 'company_invoice' );
	if( empty( $company_invoice ) ){
		$company_invoice = wpcinvoice_default_company_invoice();
	}
	?>
	<td width="50%" align="right">		
		<?php echo str_replace( $str_find, $str_replace, $company_invoice ); ?>		
	</td>
	<?php	
}
function wpcinvoice_shipper_info_callback( $shipmentDetails, $invoice_options, $str_find, $str_replace ){
	$invoice_options    = get_option( 'wpcinvoice_settings' );
	$str_find           = array_keys( wpcinvoice_shortcodes_list() );
	$str_replace        = wpcinvoice_replace_shortcodes_list( $shipmentDetails['shipmentID'] );
	$shipper_invoice	= wpcinvoice_display_options( $invoice_options, 'shipper_info' );
	if( empty( $shipper_invoice ) ){
		$shipper_invoice = wpcinvoice_default_shipper_invoice();
	}
	?>
	<td class="no-padding">
        <h3 class="section-header"><?php echo apply_filters( 'wpcinvoice_shipper_header', __('SHIPPER INFORMATION', 'wpcargo-invoice' ) ); ?></h3>
        <?php echo str_replace( $str_find, $str_replace, $shipper_invoice ); ?>
    </td>
	<?php
	
}
function wpcinvoice_receiver_info_callback( $shipmentDetails, $invoice_options, $str_find, $str_replace ){
	$invoice_options    = get_option( 'wpcinvoice_settings' );
	$str_find           = array_keys( wpcinvoice_shortcodes_list() );
	$str_replace        = wpcinvoice_replace_shortcodes_list( $shipmentDetails['shipmentID'] );
	$receiver_invoice	= wpcinvoice_display_options( $invoice_options, 'receiver_info' );
	if( empty( $receiver_invoice ) ){
		$receiver_invoice = wpcinvoice_default_receiver_invoice();
	}
	?>
	<td class="no-padding">
        <h3 class="section-header"><?php echo apply_filters( 'wpcinvoice_receiver_header', __('RECEIVER INFORMATION', 'wpcargo-invoice' ) ); ?></h3>
        <?php echo str_replace( $str_find, $str_replace, $receiver_invoice ); ?>
    </td>
	<?php
	
}
function wpcinvoice_package_info_callback( $shipmentDetails, $invoice_options, $str_find, $str_replace ){
	$shipment_type = wpcfe_get_shipment_type( $shipmentDetails['shipmentID'] );
	if( !empty( wpcinvoice_package_fields() ) ):
		?>
		<style>
			.wpcargo-table-bordered{
				border-collapse:collapse;
			}
			.wpcargo-table-bordered th{
				background-color:#f3f3f3;
			}
			.wpcargo-table-bordered th, .wpcargo-table-bordered td{
				border:1px solid #000;
			}
		</style>
	    <h3 class="section-header"><?php echo apply_filters( 'wpcinvoice_package_header', __('PACKAGE DETAILS', 'wpcargo-invoice' ) ); ?></h3>
	    <table class="table wpcargo-table wpcargo-table-bordered" style="width:100%;">
	        <thead>
	            <tr>
	                <?php foreach ( wpcinvoice_package_fields() as $key => $value): ?>
	                    <?php 
	                    if( 
	                        (in_array( $key, wpcargo_package_dim_meta() ) && !wpcargo_package_settings()->dim_unit_enable)
	                        || ( $shipment_type != 'Default' && in_array( $key , array( wpcinvoice_unit_price_key(), wpcinvoice_unit_amount_key()) ) )
                        ){ continue; }
	                    ?>
	                    <th align="left"><?php echo $value['label']; ?></th>
	                <?php endforeach; ?>
	            </tr>
	        </thead>
	        <tbody>
	            <?php if(!empty(wpcargo_get_package_data( $shipmentDetails['shipmentID'] ))): ?>
	                <?php foreach ( wpcargo_get_package_data( $shipmentDetails['shipmentID'] ) as $data_key => $data_value): ?>
	                <tr class="package-row">
	                    <?php foreach ( wpcinvoice_package_fields() as $field_key => $field_value): ?>
	                        <?php 
	                        if( 
	                        	(in_array( $field_key, wpcargo_package_dim_meta() ) && !wpcargo_package_settings()->dim_unit_enable)
	                        	|| ( $shipment_type != 'Default' && in_array( $field_key , array(wpcinvoice_unit_price_key(), wpcinvoice_unit_amount_key()) ) )
	                        ){ continue; }
	                        ?>
	                        <td class="package-data <?php echo wpcargo_to_slug( $field_key ); ?>">
	                            <?php 
	                                $package_data = array_key_exists( $field_key, $data_value ) ? $data_value[$field_key] : '' ;
	                                if( is_array( $package_data ) ){
	                                	$package_data = implode(',', $package_data );
	                                }elseif( in_array( $field_key , array( 'wpc-pm-value', wpcinvoice_unit_price_key(), wpcinvoice_unit_amount_key()) ) ){
	                                	$package_data = wpcinvoice_format_value($package_data);
	                                }
	                                echo $package_data; 
	                            ?>

	                        </td>
	                    <?php endforeach; ?>
	                </tr>
	                <?php endforeach; ?>
	                <?php do_action( 'wpcinvoice_after_package_row', $shipmentDetails ); ?>
	            <?php else: ?>
	                <tr>
	                    <td class="empty-data" colspan="<?php echo !wpcargo_package_settings()->dim_unit_enable ? count( wpcinvoice_package_fields() ) - count( wpcargo_package_dim_meta() ) : count( wpcinvoice_package_fields() ) ; ?>">
	                        <i><?php esc_html_e( 'Data empty', 'wpcargo' ); ?>.</i>
	                    </td>
	                </tr>
	            <?php endif; ?>
	        </tbody>
	    </table>
	    <?php
	endif;
}
function wpcinvoice_comments_info_callback( $shipmentDetails, $invoice_options, $str_find, $str_replace ){
	$invoice_options    = get_option( 'wpcinvoice_settings' );   
	$comment_invoice	= wpcinvoice_display_options( $invoice_options, 'comment' );
	if( empty( $comment_invoice ) ){
		$comment_invoice = wpcinvoice_default_comment_invoice();
	} 
	?>
	<td style="padding:0;vertical-align: top;">	
		<h3 class="section-header"><?php echo apply_filters( 'wpcinvoice_comment_header', __('COMMENTS', 'wpcargo-invoice' ) ); ?></h3>
		<?php echo $comment_invoice; ?>
	</td>
	<?php
}
function wpcinvoice_total_info_callback( $shipmentDetails, $invoice_options, $str_find, $str_replace ){
	$invoice_options    = get_option( 'wpcinvoice_settings' );
	$order_id 			= wpcinvoice_get_invoice_order( $shipmentDetails['shipmentID'] );
	$str_find           = array_keys( wpcinvoice_shortcodes_list() );
	$str_replace        = wpcinvoice_replace_shortcodes_list( $shipmentDetails['shipmentID'] );
	$total_info 		= wpcinvoice_get_total_value( $shipmentDetails['shipmentID'] ); 
	$thankyou_invoice	= wpcinvoice_display_options( $invoice_options, 'thankyou_message' );
	$subtotal 			= $order_id ? wc_price( wpcinvoice_get_order_data( $order_id )->subtotal ) : wpcinvoice_format_value( $total_info['sub_total'], true );
	$total_tax 			= $order_id ? wc_price( wpcinvoice_get_order_data( $order_id )->total_tax ) : wpcinvoice_format_value( $total_info['tax'], true );
	$total 				= $order_id ? wc_price( wpcinvoice_get_order_data( $order_id )->total ) : wpcinvoice_format_value( $total_info['total'], true );
	if( empty( $thankyou_invoice ) ){
		$thankyou_invoice = wpcinvoice_default_thankyou_invoice();
	} 
	
	?>
	<td align="right" style="padding:0;vertical-align: top;" class="package-total">
		<h3>&nbsp;</h3>
        <p><strong><?php echo apply_filters( 'wpcinvoice_subtotal_label', __('Subtotal:', 'wpcargo-invoice' ) ); ?></strong> <span><?php echo $subtotal; ?></span></p>
        <p><strong><?php echo apply_filters( 'wpcinvoice_tax_label', __('Tax:', 'wpcargo-invoice' ) ); ?></strong> <span><?php echo $total_tax; ?></span></p>
        <p><strong><?php echo apply_filters( 'wpcinvoice_total_label', __('Total:', 'wpcargo-invoice' ) ); ?></strong><span><?php echo $total; ?></span></p>        
        <p><?php echo str_replace( $str_find, $str_replace, $thankyou_invoice ); ?> </p>     
    </td>
	<?php
}
function wpcinvoice_print_hooks_callback(){
	add_action( 'wpcinvoice_comapany_info', 'wpcinvoice_comapany_info_callback', 10, 4 );
	add_action( 'wpcinvoice_invoicing_info', 'wpcinvoice_invoicing_info_callback', 10, 4 );
	add_action( 'wpcinvoice_shipper_info', 'wpcinvoice_shipper_info_callback', 10, 4 );
	add_action( 'wpcinvoice_receiver_info', 'wpcinvoice_receiver_info_callback', 10, 4 );
	add_action( 'wpcinvoice_package_info', 'wpcinvoice_package_info_callback', 10, 4 );
	add_action( 'wpcinvoice_comments_info', 'wpcinvoice_comments_info_callback', 10, 4 );
	add_action( 'wpcinvoice_total_info', 'wpcinvoice_total_info_callback', 10, 4 );
}
add_action( 'plugins_loaded', 'wpcinvoice_print_hooks_callback' );