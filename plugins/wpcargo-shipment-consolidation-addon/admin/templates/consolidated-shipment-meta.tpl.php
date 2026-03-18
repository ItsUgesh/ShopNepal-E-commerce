<?php
	// Shipping method
	$weight_unit        = wpcshcon_weight_unit();
	$dimension_unit     = wpcshcon_dimension_unit();
	$shipping_method  = get_post_meta( $shipment_id, 'wpcshcon_shipping_method', true );
	// Addon and Fees
	$total_cost = wpcshcon_get_consolidation_cost($shipment_id);
	$shipments = maybe_unserialize( get_post_meta( $shipment_id, 'wpcshcon_shipments', true ) );
?>
<div id="consolidate-shipments">
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
                    <td><input type="text" class="wpcshcon_cost price form-control" name="shipments_cost_<?php echo $shipment; ?>" value="<?php echo get_post_meta( $shipment_id, 'shipments_cost_'.$shipment, true ); ?>"></td>
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
<div id="consolidation-data" class="wpcshcon-wrapper">
    <?php if( !empty( wpcshcon_shipping_methods() ) ): ?>
        <div id="shipping-method-wrapper">
            <h5><?php _e( 'Shipping Method:', 'wpc-shipment-consoldation' ); ?></h5>
            <table id="shipping-method-list" style="width:100%;">
                <?php 
					$counter = 1;
					foreach ( wpcshcon_shipping_methods() as $method ) {
						$method_meta                = wpcshcon_text_to_meta( $method );
						$cost_method_meta           = 'cost_'.$method_meta;
						$method_meta_data           = get_post_meta( $shipment_id, 'wpcshcon_shipping_method', true );
						$cost_method_meta_data      = get_post_meta( $shipment_id, $cost_method_meta, true );
						?>
						<tr>
							<td>
								<input type="radio" name="wpcshcon_shipping_method" value="<?php echo $method; ?>" <?php checked( $method, $method_meta_data ); ?> id="wpcshcon_shipping_method_<?php echo $counter;?>" class="form-check-input">
								<label for="wpcshcon_shipping_method_<?php echo $counter;?>" class="form-check-label"><?php echo $method; ?></label>
							</td>
							<td>
								<span class="shipping-price">
									<input type="text" class="wpcshcon_cost price form-control" name="<?php echo $cost_method_meta; ?>" value="<?php echo $cost_method_meta_data; ?>" <?php echo ( $method_meta_data == $method ) ? '' : 'readonly' ; ?>>
								</span>
							</td>
						</tr>
						<?php
						$counter++;
					} 
                ?>
            </table>
        </div>
    <?php endif; ?>
    <?php if( !empty( wpcshcon_package_options() ) ): ?>
		<div id="packaging-option-wrapper">
			<h5><?php _e('Packaging Options', 'wpc-shipment-consoldation'); ?></h5>
			<div id="packaging-option-wrapper">
				<?php 
				$counter = 1;
				foreach ( wpcshcon_package_options() as $option ) {
					$option_meta        = wpcshcon_text_to_meta( $option );
					$option_meta_data   = get_post_meta( $shipment_id, $option_meta, true );
					?>
					<div class="input-wrapper form-group">
						<input class="form-check-input" type="checkbox" name="<?php echo $option_meta; ?>" value="<?php echo $option_meta; ?>" <?php checked( $option_meta, $option_meta_data ); ?> id="package-option-<?php echo $counter;?>">
						<label for="package-option-<?php echo $counter;?>"><?php echo $option; ?></label>
					</div>
					<?php
					$counter++;
				}
				?>
			</div>
		</div>
    <?php endif; ?>
</div>
<?php if( !empty( wpcshcon_additional_fees() )): ?>
	<div id="addons-fees" class="wpcshcon-wrapper">
		<h5><?php _e('Addons & Fees:', 'wpc-shipment-consoldation' ); ?></h5>
		<div class="af-section">
			<table class="form-table" style="width:100%;">
				<?php foreach( wpcshcon_additional_fees() as $key => $label ): ?>
					<?php
						$cost_meta = 'wpcshcon_'.$key.'_cost';
						$cost = get_post_meta( $shipment_id, $cost_meta, true );
					?>
					<tr>
						<th><?php echo $label; ?></th>
						<td><input type="text" class="wpcshcon_cost price form-control" name="<?php echo $cost_meta; ?>" value="<?php echo $cost; ?>" autocomplete="off" /></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
		<div class="af-section totals-details">
			<table class="form-table" style="width:100%;">
				<tr>
					<th><strong><?php _e('SUBTOTAL', 'wpc-shipment-consoldation' ); ?></strong></th>
					<td><?php echo get_woocommerce_currency_symbol(); ?> <span class="wpcshcon-subtotal"></span></td>
				</tr>
				<tr>
					<th><strong><?php _e('TAX:', 'wpc-shipment-consoldation' ); ?></strong></th>
					<td><input type="text" class="wpcshcon_cost price form-control" name="wpcshcon_tax_fee" value="<?php echo get_post_meta( $shipment_id, 'wpcshcon_tax_fee', true ); ?>" autocomplete="off" /></td>
				</tr>
				<tr>
					<th><strong><?php _e('TOTAL', 'wpc-shipment-consoldation' ); ?></strong></th>
					<td><?php echo get_woocommerce_currency_symbol(); ?> <span class="wpcshcon-total"></span></td>
				</tr>
			</table>
		</div>
	</div>
<?php endif; ?>
<div style="clear:both;overflow:hidden"></div>