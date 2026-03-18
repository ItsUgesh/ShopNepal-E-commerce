<?php
 	$wpcshcon_store = get_post_meta($shipment_id, 'wpcshcon_store', true );
	$wpcshcon_warehouse = get_post_meta($shipment_id, 'wpcshcon_warehouse', true );
	$shipment_consolidation = get_shipment_consolidation( $shipment_id );
	$consolidate_no     = '';
	if( $shipment_consolidation ){
		$consolidate_no = '<a href="'.admin_url('post.php?post='.$shipment_consolidation.'&action=edit').'">'.get_the_title( $shipment_consolidation ).'</a>';
	}
?>
<h4>
	<?php _e('Free Storage Left: ', 'wpc-shipment-consoldation' ); ?>
	<?php echo get_shipment_storage_left( $shipment_id ); ?> <?php _e('Day(s)', 'wpc-shipment-consoldation'); ?>
</h4>
<h5>
	<?php _e('Consolidate # ', 'wpc-shipment-consoldation' ); ?>: <?php echo $consolidate_no; ?>
</h5>
<div class="form-group">
	<label for="wpcshcon_store" style="font-weight:bold"><?php _e( 'Store/Sender', 'wpc-shipment-consoldation' ); ?></label>:<br/>
	<input id="wpcshcon_store" type="text" name="wpcshcon_store" value="<?php echo $wpcshcon_store; ?>" class="form-control">
</div>
<div class="form-group">
	<label for="wpcshcon_warehouse" style="font-weight:bold"><?php _e( 'Warehouse', 'wpc-shipment-consoldation' ); ?></label>:<br/>
	<input id="wpcshcon_warehouse" type="text" name="wpcshcon_warehouse" value="<?php echo $wpcshcon_warehouse; ?>" class="form-control">
</div>