<div id="wpcshcon-vieworder-wrapper">
    <div class="wpcargo-container">
        <div class="wpcargo-row">
            <div class="wpcargo-col-md-6">
				<h5><?php _e('Ship To:', 'wpc-shipment-consoldation'); ?></h5>
				<table class="form-table" style="width:100%;">
				<?php
				if( !empty( $receiver_fields ) ){
					foreach ($receiver_fields as $_fvalue ) { ?>
						<tr>
							<td><strong><?php echo $_fvalue['label'].':';  ?></strong></td>
							<td><?php echo get_post_meta( $consolidateID, $_fvalue['field_key'], true ); ?></td>
						</tr>
					<?php
					}
				}
				?>
				</table>
            </div>
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
		</div>
		<?php do_action( 'wpcshcon_after_consolidate_details', $consolidateID ) ?>
		<div class="wpcargo-row">
            <div class="wpcargo-col-md-6">
                <h5><?php _e('Addons & Fees:', 'wpc-shipment-consoldation'); ?></h5>
                <div id="addons-fees" class="af-section">
					<table class="form-table" style="width:100%;">
						<?php foreach( wpcshcon_additional_fees() as $key => $label ): ?>
							<?php
								$cost_meta = 'wpcshcon_'.$key.'_cost';
								$cost = wpcshcon_get_meta_cost( $consolidateID, $cost_meta );
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
	<?php 
		$weight_unit = wpcshcon_weight_unit();
		$shipments	 = maybe_unserialize( get_post_meta( $consolidateID, 'wpcshcon_shipments', true ) );
	?>
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
						<td><?php echo get_post_meta( $consolidateID, 'shipments_cost_'.$shipment, true ); ?></td>
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