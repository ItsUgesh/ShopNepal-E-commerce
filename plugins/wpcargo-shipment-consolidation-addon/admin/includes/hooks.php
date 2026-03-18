<?php
if(!defined('ABSPATH')){
    exit; //Exit if accessed directly
}
// Woocommerce Hook
/**
 * Add the field to the checkout page
 */
/**
 * Process the checkout
 */

// After payment order is Complete
function wpcshcon_warehouse_email_body_container( $user_id ){
    global $wpcargo;
    $default_logo       = plugins_url().'/wpcargo/admin/assets/images/wpcargo-logo-email.png';
    $footer_image       = plugins_url().'/wpcargo/admin/assets/images/wpc-email-footer.png';
    $brand_logo         = !empty( $wpcargo->logo ) ? $wpcargo->logo : $default_logo;
    $warehouse_address  = get_option('wpcshcon_warehouse_address');
    $consolidation_code = get_user_meta( $user_id, 'consolidation_code', true );
    $body_content       = get_option( 'wpcshcon_email_body' ) ? get_option( 'wpcshcon_email_body' ) : '' ;
    $defualt_footer     = function_exists('wpcargo_default_email_footer') ? wpcargo_default_email_footer() : '' ;
	$footer_content     = get_option( 'wpcshcon_email_footer' ) ? get_option( 'wpcshcon_email_footer' ) : $defualt_footer ;
	$whs_name  			= get_option( 'wpcshcon_warehouse_name' );

    if( empty( $consolidation_code ) ){
        update_user_meta( $user_id, 'consolidation_code', wpcshcon_gen_consolidation_code() );
    }
    ob_start();
    ?>
    <div class="wpc-email-notification-wrap" style="width: 100%; font-family: sans-serif;">
        <div class="wpc-email-notification" style="padding: 3em; background: #efefef;">
            <div class="wpc-email-template" style="background: #fff; width: 95%; margin: 0 auto;">
                <div class="wpc-email-notification-logo" style="padding: 2em 2em 0px 2em;">
                    <table width="100%" style="text-align: center;"><tr><td><img src="<?php echo $brand_logo; ?>" style="max-width:210px;"/></td></tr></table>
                </div>
                <div class="wpc-email-notification-content" style="padding: 2em 2em 1em 2em; font-size: 18px;">
                    <?php echo $body_content; ?>
                    <p><?php echo apply_filters( 'wpcicon_email_warehouse_address_header', __('This will be your warehouse address.', 'wpc-shipment-consoldation') ); ?></p>
                    <address style="padding:12px 12px 0;color:#636363;border:1px solid #e5e5e5;">
                        <?php
						if( $whs_name ){
							echo apply_filters( 'wpcshcon_consolidation_whs_name', __('Warehouse Name', 'wpc-shipment-consoldation' ) ).': '.$whs_name.'<br/>';
						}
						echo apply_filters( 'wpcshcon_consolidation_code_address', __('Storage Code', 'wpc-shipment-consoldation' ) ).': '.trim( get_user_meta( $user_id, 'consolidation_code', true ) ).'<br/>';
                        foreach ( $warehouse_address as $key => $value ) {
                            if( $key == 'city' ){
                                echo apply_filters( 'wpcshcon_consolidation_code_address', __('Address 2', 'wpc-shipment-consoldation' ) ).': '.trim( get_user_meta( $user_id, 'consolidation_code', true ) ).'<br/>';
                            }
                            echo ucfirst( str_replace('_', ' ', $key ) ).': '.trim( $value ).'<br/>';
                        }
                        ?>
                    </address>
                </div>
                <div class="wpc-email-notification-footer" style="font-size: 10px; text-align: center; margin: 0 auto;">
                    <div class="wpc-footer-devider">
                    <img src="<?php echo $footer_image; ?>" style="width:100%;" />
                </div>
                    <?php echo $footer_content; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    $output = ob_get_clean();
    return apply_filters( 'wpcshcon_warehouse_email_body_container', $output );
}
add_action( 'init', function(){
    global $wpdb;
    require_once ( PQ_SHIPMENT_CONSOLIDATION_PATH.'admin/classes/class-autoupdate.php' );
    $plugin_remote_path = 'http://www.wpcargo.com/repository/wpcargo-shipment-consolidation-addon/updates-php7.2.php';
    new WPCargo_Shipment_Consolidation_AutoUpdate ( PQ_SHIPMENT_CONSOLIDATION_VERSION, $plugin_remote_path, PQ_SHIPMENT_CONSOLIDATION_BASENAME );
} );

// Fontend Manager Hooks
add_action('wpcfe_after_account_information', 'wpcshcon_warehouse_address_callback', 10, 1 );
function wpcshcon_warehouse_address_callback( $user_info ){
    $warehouse_address = array(
        'address_1' => '',
        'city'      => '',
        'postcode'  => '',
        'country'   => '',
        'state'     => '',
        'phone'     => ''
    );
    if( get_option( 'wpcshcon_warehouse_address' ) ){
        $warehouse_address  = get_option( 'wpcshcon_warehouse_address' );
    }
    $consolidation_code     = get_user_meta( $user_info->ID, 'consolidation_code', true );
    require_once( PQ_SHIPMENT_CONSOLIDATION_PATH.'templates/warehouse-address.tpl.php' );
}
add_filter( 'wpcfe_after_sidebar_menu_items', 'wpcsc_menu_item' );
function wpcsc_menu_item($menu_items){
	$user_roles = wpcfe_current_user_role();
	if( function_exists('wpcfe_admin_page') && in_array( 'wpcargo_client', $user_roles )  ){
		$menu_items['consolidation-menu'] = array(
											'label' => __( 'Consolidated Shipments', 'wpcargo-address-book' ),
											'permalink' => get_permalink( wpc_shipment_consolidation_get_frontend_page() ),
											'icon' => 'fa-list'
										);
	}
	return $menu_items;
}
add_action( 'wpcfe_after_add_shipment', 'wpcshcon_after_add_shipment', 30 );
function wpcshcon_after_add_shipment(){
	$user_roles = wpcfe_current_user_role();
	if( in_array( 'wpcargo_client', $user_roles ) ){
		$consolidate_page = wpc_consolidate_get_frontend_page();
		$active = is_page( $consolidate_page ) ? 'active' : '';
		?>
		<a href="<?php echo get_the_permalink( $consolidate_page ); ?>" class="list-group-item waves-effect consolidate <?php echo $active;?>">
			<i class="fa fa-plus mr-3"></i><?php echo get_the_title( $consolidate_page ); ?> 
		</a>
		<?php
	}
}
add_filter( 'wpcfe_registered_scripts', 'wpcsc_scripts_to_wpcfe' );
function wpcsc_scripts_to_wpcfe( $scripts ){
	$scripts[] = 'wpcshcon_scripts_js';
	$scripts[] = 'wpcshcon_js';
	//$scripts[] = 'wpcshcon_croppie_js';
	return $scripts;
}
add_filter( 'wpcfe_registered_styles', 'wpcsc_style_to_wpcfe', 10, 1 );
function wpcsc_style_to_wpcfe( $styles ){
	$styles[] = 'wpcshcon_frontend_css';
	//$styles[] = 'wpcshcon_croppie_css';
	return $styles;
}
add_filter( 'wpcfe_shipment_type_list', 'wpcshcon_shipment_type_list' );
function wpcshcon_shipment_type_list( $shipment_type ){
	$shipment_type['shipment-consolidation'] = 'Shipment Consolidation';
	return $shipment_type;
}
add_action( 'after_wpcfe_shipment_form_fields', 'wpcshcon_shipments_table', 5 );
function wpcshcon_shipments_table( $shipment_id ){
	global $post;
	$shipment_type = get_post_meta( $shipment_id, '__shipment_type', true );
	if( $shipment_type == 'shipment-consolidation' ){
		?>
		<div id="wpcsh-consolidate-box-id" class="col-md-12 mb-4">
			<div class="card">
				<section class="card-header"><?php _e('Consolidated Shipments', 'wpc-shipment-consoldation'); ?></section>
				<section class="card-body">
					<?php require_once( PQ_SHIPMENT_CONSOLIDATION_PATH.'/admin/templates/consolidated-shipment-meta.tpl.php' ); ?>
				</section>
			</div>
		</div>
		<?php
	}
}
add_filter( 'wpcfe_shipment_sections', 'wpcshcon_remove_shipment_sections' );
function wpcshcon_remove_shipment_sections( $section ){
	if( isset( $_GET['wpcfe'] ) && isset( $_GET['id'] ) &&  $_GET['wpcfe'] == 'update' ){
		$shipment_type = get_post_meta( $_GET['id'], '__shipment_type', true );
		if( $shipment_type == 'shipment-consolidation' ){
			unset( $section['shipment_info'] );
		}
	}
	return $section;
}
add_action( 'before_wpcfe_shipment_form_submit', 'wpcshcon_send_notification_frontend_callback', 30 );
function wpcshcon_send_notification_frontend_callback( $shipment_id ){
	$current_user   = wp_get_current_user();
	$user_roles     = $current_user->roles;
	$shipment_type 	= get_post_meta( $shipment_id, '__shipment_type', true );
	if( array_intersect( array( 'wpcargo_employee', 'administrator' ), (array)$user_roles )  ){
		if( $shipment_type == 'shipment-consolidation' ){
			$package_list      = !empty( get_post_meta( $shipment_id, 'wpc-multiple-package', true) ) ? maybe_unserialize( get_post_meta( $shipment_id, 'wpc-multiple-package', true) ) : array();
			$item_notification = maybe_unserialize( get_post_meta( $shipment_id, 'wpcshcon_item_notification', true ) );
			$has_package       = 1;
			$consolidation_cost = wpcshcon_get_consolidation_cost( $shipment_id );
			if( $consolidation_cost <= 0 ){
				$has_package = 0;
			}
			?>
			<div class="card mb-4">
				<section class="card-header"><?php esc_html_e('Notification', 'wpc-shipment-consoldation'); ?></section>
				<section class="card-body">
					<div class="item-email-notification">
						<a id="send-item-email-notification" class="btn btn-small btn-success btn-fill btn-wd btn-block waves-effect waves-light" href="#" data-postid="<?php echo $shipment_id; ?>" data-package="<?php echo $has_package; ?>" data-type="consolidation" >
							<i class="dashicons dashicons-email-alt"></i>
							<?php esc_html_e( 'Send Notification', 'wpc-shipment-consoldation' ); ?>
						</a>
					</div>
					<div class="item-email-notes">
					<?php
					$counter = 0;
					if( !empty( $item_notification ) ){
						?><ul class="item-notification-list"><?php
							foreach ( array_reverse( $item_notification ) as $notification ) {
								if( $counter == 6 ){
									break;
								}
								?><li><?php echo  $notification[1].' '.$notification[2].' on '.$notification[0]; ?></li><?php
								$counter++;
							}
						?></ul><?php
					}else{
						esc_html_e('No sent email records found!', 'wpc-shipment-consoldation' );
					}
					?>
					</div>
				</section>
			</div>
			<?php
		}else{
			?>
			<div class="card mb-4">
				<section class="card-header"><?php esc_html_e('Consolidation Information', 'wpc-shipment-consoldation'); ?></section>
				<section class="card-body">
					<?php
						$wpcshcon_store 	= get_post_meta( $shipment_id, 'wpcshcon_store', true );
						$wpcshcon_warehouse = get_post_meta( $shipment_id, 'wpcshcon_warehouse', true );
						$consolidate_no     = '';
						$shipment_consolidation = get_shipment_consolidation( $shipment_id );
						if( $shipment_consolidation ){
							$consolidate_no = '<a href="'.admin_url('post.php?post='.$shipment_consolidation.'&action=edit').'">'.get_the_title( $shipment_consolidation ).'</a>';
						}
						require_once( PQ_SHIPMENT_CONSOLIDATION_PATH.'/admin/templates/shipment-meta.tpl.php' );
					?>
				</section>
			</div>
			<?php
		}
	}
}

add_action( 'after_wpcfe_save_shipment', 'wpcshcon_wpcfe_save', 10, 2 );
function wpcshcon_wpcfe_save( $shipment_id, $data ){
	$shipments 			= maybe_unserialize( get_post_meta( $shipment_id, 'wpcshcon_shipments', true ) );
	$wpc_shipment_type 	= get_post_meta( $shipment_id, 'wpc_shipment_type', true );
	if( !empty( $shipments ) ){
		foreach( $shipments as $shipment ){
			$shipment_cost_meta = 'shipments_cost_'.$shipment;
			if( isset( $data[$shipment_cost_meta] ) ){
				update_post_meta( $shipment_id, $shipment_cost_meta, $data[$shipment_cost_meta] );
			}
		}
	}
	if( !empty( wpcshcon_additional_fees() ) ){
		foreach( wpcshcon_additional_fees() as $key=>$label ){
			$fee_cost_meta = 'wpcshcon_'.$key.'_cost';
			if( isset( $data[$fee_cost_meta] ) ){
				update_post_meta( $shipment_id, $fee_cost_meta, $data[$fee_cost_meta] );
			}
		}
	}
	if( !empty( wpcshcon_package_options() ) ){
		foreach ( wpcshcon_package_options() as $option ) {
			$option_meta = wpcshcon_text_to_meta( $option );
			if( isset( $data[$option_meta] ) ){
				update_post_meta( $shipment_id, $option_meta, $data[$option_meta] );
			}
		}
	}
	if( isset( $data['wpcshcon_tax_fee'] ) ){
		update_post_meta( $shipment_id, 'wpcshcon_tax_fee', $data['wpcshcon_tax_fee'] );
	}
	// Set shipment type to standard - Shipment form consolidation
	if( !$wpc_shipment_type ){
		update_post_meta( $shipment_id, 'wpc_shipment_type', 'standard' );
	}

	if( isset( $data['wpcshcon_store'] ) ){
		update_post_meta( $shipment_id, 'wpcshcon_store', sanitize_text_field( $data['wpcshcon_store'] ) );
	}
	if( isset( $data['wpcshcon_warehouse'] ) ){
		update_post_meta( $shipment_id, 'wpcshcon_warehouse', sanitize_text_field( $data['wpcshcon_warehouse'] ) );
	}
}
add_filter( 'wpcfe_get_orders', 'wpcshcon_get_orders' );
function wpcshcon_get_orders( $wpcfe_orders ){
	foreach( $wpcfe_orders as $id => $order ){
		foreach( $order['order-items'] as $item_id => $order_items ){
			$get_shipment_ID = wc_get_order_item_meta( $item_id, 'Consolidate No.', true );
		}
		if( !empty( $get_shipment_ID ) ){
			$wpcfe_orders[$id]['shipment-id'] = $get_shipment_ID;
			$wpcfe_orders[$id]['shipment-type'] = 'Shipment Consolidation';
		}
	}
	return $wpcfe_orders;
}

add_action('init', 'wpcshcon_remove_hooks');
function wpcshcon_remove_hooks(){
	global $wpc_cf_hooks;
	if( isset( $_GET['wpcfe'] ) && $_GET['wpcfe'] == 'track' && isset( $_GET['num'] ) ){
		$shipment_id = wpcfe_shipment_id( $_GET['num'] );
		$shipment_type = get_post_meta( $shipment_id, '__shipment_type', true );
		if( $shipment_type == 'shipment-consolidation'){
			remove_action( 'wpcargo_track_shipment_details', array( $wpc_cf_hooks, 'wpc_cf_track_result_shipment_template' ), 10 );
			remove_action( 'wpcargo_after_package_details', 'wpcargo_multiple_package_after_track_details', 5 );
			remove_action( 'wpcargo_after_package_totals', 'wpcargo_after_package_details_callback' );
			remove_action( 'after_wpcfe_shipment_form_fields', 'wpcfe_shipment_multipackage_template', 10, 1 );
		}
	}
}

add_action('wpcargo_after_package_totals', 'wpcshcon_track_details', 5, 1 );
function wpcshcon_track_details( $shipment ){
    if(!$shipment ){
        return false;
    }
	$shipment_id  = $shipment->ID;
	$shipment_type = get_post_meta( $shipment_id, '__shipment_type', true );
	if( $shipment_type == 'shipment-consolidation'){
		
		$shipments              = maybe_unserialize( get_post_meta ($shipment_id, 'wpcshcon_shipments', true ) );
		$shipments              = !empty( $shipments ) ? array_filter( $shipments ) : array();
		$order_no               = wpcshcon_get_consolidation_order( $shipment_id );
		$storageCost            = 0;
		if( !empty( $shipments ) ){
			foreach( $shipments as $shipment ){
				$_shipmentCost = get_post_meta( $shipment_id, 'shipments_cost_'.$shipment, true ) ? get_post_meta( $shipment_id, 'shipments_cost_'.$shipment, true ) : 0 ;
				$storageCost  = $storageCost + $_shipmentCost;
			}
		}

		$shipping_method        = get_post_meta( $shipment_id, 'wpcshcon_shipping_method', true );
		$method_meta            = 'cost_'.wpcshcon_text_to_meta( $shipping_method );

		// Shipping Method
		$shipping_cost          = wpcshcon_format_number( get_post_meta( $shipment_id, $method_meta, true ) );
		$tax_cost               = wpcshcon_format_number( get_post_meta( $shipment_id, 'wpcshcon_tax_fee', true ) );

		// Addon and Fees
		$insurance_cost         = wpcshcon_get_meta_cost( $shipment_id, 'wpcshcon_insurance_cost' );
		$extra_packaging_cost   = wpcshcon_get_meta_cost( $shipment_id, 'wpcshcon_extra_packaging_cost');
		$fragile_sticker_cost   = wpcshcon_get_meta_cost( $shipment_id, 'wpcshcon_fragile_sticker_cost' );
		$storage_cost           = wpcshcon_get_meta_cost( $shipment_id, 'wpcshcon_storage_cost' );
		$ship_address_cost      = wpcshcon_get_meta_cost( $shipment_id, 'wpcshcon_ship_address_cost' );
		$urgent_fees_cost       = wpcshcon_get_meta_cost( $shipment_id, 'wpcshcon_urgent_fees_cost' );
		$smart_pack_cost        = wpcshcon_get_meta_cost( $shipment_id, 'wpcshcon_smart_pack_cost' );
		$dangerous_item_cost    = wpcshcon_get_meta_cost( $shipment_id, 'wpcshcon_dangerous_item_cost' );
		$picture_cost           = wpcshcon_get_meta_cost( $shipment_id, 'wpcshcon_picture_cost' );
		$ordered_on             = get_the_date( 'F j, Y', $shipment_id );
		$consolidate_no         = get_the_title( $shipment_id );
		$status                 = get_post_meta( $shipment_id, 'wpcargo_status', true );
		$total_cost             = wpcshcon_get_consolidation_cost( $shipment_id );
		$weight_unit = wpcshcon_weight_unit();
		$shipments	 = maybe_unserialize( get_post_meta( $shipment_id, 'wpcshcon_shipments', true ) );
		?>
		<div id="shipment_info" class="wpcargo-row detail-section">
			<div class="wpcargo-col-md-12">
				<p id="shipment_info-header" class="header-title"><strong><?php _e('Consolidation Information', 'wpc-shipment-consoldation'); ?></strong></p>
			</div>
			<div class="wpcargo-container">
				<div class="wpcargo-row">
					<div class="wpcargo-col-md-6">
						<h5><?php _e('Consolidation Details:', 'wpc-shipment-consoldation'); ?></h5>
						<table class="form-table" style="width:100%;">
							<tr>
								<td><strong><?php _e('Ordered On:', 'wpc-shipment-consoldation'); ?></strong></td>
								<td><?php echo $ordered_on; ?></td>
							</tr>
							<tr>
								<td><strong><?php _e('Consolidate No.:', 'wpc-shipment-consoldation'); ?></strong></td>
								<td><?php echo $consolidate_no; ?></td>
							</tr>
							<tr>
								<td><strong><?php _e('Shipping Method:', 'wpc-shipment-consoldation'); ?></strong></td>
								<td><?php echo $shipping_method; ?></td>
							</tr>
							<tr>
								<td><strong><?php _e('Status:', 'wpc-shipment-consoldation'); ?></strong></td>
								<td><?php echo $status; ?></td>
							</tr>
							<tr>
								<td><strong><?php _e('Total Shipment', 'wpc-shipment-consoldation' ); ?></strong></td>
								<td><?php echo count($shipments); ?></td>
							</tr>
						</table>
					</div>
					<div class="wpcargo-col-md-6">
						<h5><?php _e('Addons & Fees:', 'wpc-shipment-consoldation'); ?></h5>
						<div id="addons-fees" class="af-section">
							<table class="form-table" style="width:100%;">
								<?php foreach( wpcshcon_additional_fees() as $key => $label ): ?>
									<?php
										$cost_meta = 'wpcshcon_'.$key.'_cost';
										$cost = wpcshcon_get_meta_cost( $shipment_id, $cost_meta );
									?>
									<tr>
										<td><strong><?php echo $label; ?></strong></td>
										<td><?php echo $cost; ?></td>
									</tr>
								<?php endforeach; ?>
								<tr>
									<td><strong><span class="addon-label"><?php _e('Shipping Cost:', 'wpc-shipment-consoldation' ); ?></strong></span></td>
									<td><strong><?php echo $shipping_cost; ?></strong></td>
								</tr>
								<tr>
									<td><strong><span class="addon-label"><?php _e('Tax:', 'wpc-shipment-consoldation' ); ?></span></strong></td>
									<td><strong><?php echo $tax_cost; ?></strong></td>
								</tr>
								<tr>
									<td style="border-top: 2px solid #cecece;"><h5><?php _e('Total Cost:', 'wpc-shipment-consoldation'); ?></h5></td>
									<td style="border-top: 2px solid #cecece;"><h5><?php echo get_woocommerce_currency_symbol().' '.$total_cost; ?></h5></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div id="shipment-details-section" class="wpcargo-container">
				<h5><?php _e('Shipment Details:', 'wpc-shipment-consoldation'); ?></h5>
				<table class="table table-hover table-sm wpcargo-table" style="width:100%;">
					<thead>
						<tr>
							<th><?php _e('Date', 'wpc-shipment-consoldation'); ?></th>
							<th><?php _e('Tracking Number', 'wpc-shipment-consoldation'); ?></th>
							<th><?php _e('Sender', 'wpc-shipment-consoldation'); ?></th>
							<th><?php _e('Weight', 'wpc-shipment-consoldation'); ?>(<?php  echo $weight_unit; ?>)</th>
							<th><?php _e('Status', 'wpc-shipment-consoldation'); ?></th>
							<th><?php _e('Free Storage Left', 'wpc-shipment-consoldation'); ?></th>
							<th><?php _e('Cost', 'wpc-shipment-consoldation'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php
					if( !empty( $shipments ) ){
						foreach( $shipments as $shipment ){
							$shipment_weight = wpcshcon_shipment_weight( $shipment );
							?>
							<tr>
								<td><?php echo get_the_date( 'F j, Y', $shipment ); ?></td>
								<td><?php echo get_the_title(  $shipment ); ?></td>
								<td><?php echo get_post_meta( $shipment, 'wpcshcon_store', true ); ?></td>
								<td><?php echo $shipment_weight; ?></td>
								<td><?php echo get_post_meta( $shipment, 'wpcargo_status', true ); ?></td>
								<td><?php echo get_shipment_storage_left( $shipment ); ?> <?php _e('Day(s)', 'wpc-shipment-consoldation'); ?></td>
								<td><?php echo get_post_meta( $shipment_id, 'shipments_cost_'.$shipment, true ); ?></td>
							</tr>
							<?php
						}
					}else{
						?><tr class="no-consolidation"><td colspan="7"><?php _e('No Consolidated Shipment Found', 'wpc-shipment-consoldation'); ?></td></tr><?php
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
		<?php
	}
}