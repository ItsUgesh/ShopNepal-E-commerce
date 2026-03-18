<h4 class="wpcshcon-header-title"><?php echo  __('New consolidation details', 'wpc-shipment-consoldation' ); ?></h4>
<div style="display: block;overflow: hidden;">
    <form id="submit_new_order" method="post">
        <?php wp_nonce_field( 'wpcshco_submit_order_action', 'wpcshco_submit_order_nonce' ); ?>
        <input type="hidden" name="step" value="2"/>
        <!-- Shipment -->
        <input type="hidden" name="shipment" value="<?php echo $shipments; ?>"/>
        <?php do_action( 'wpcshco_before_submit_order_fields', $_POST ); ?>
        <div id="receiver-info" class="order-section">
            <h6><?php _e('Ship To', 'wpc-shipment-consoldation'); ?></h6>
			<table style="100%">
				<?php
				if( !empty( $receiver_fields ) ){
					foreach( $receiver_fields  as $rc_value ){
                        if( !isset( $_POST[ $rc_value['field_key'] ] ) ){
                            continue;
                        }
						$value = maybe_serialize( sanitize_text_field( $_POST[ $rc_value['field_key'] ] ) );
						?>
						<tr>
							<td><strong><?php echo $rc_value['label']; ?>:</strong></td>
							<td>
								<input type="hidden" name="<?php echo $rc_value['field_key']; ?>" value="<?php echo $value; ?>"/>
								<?php echo $value; ?>
							</td>
						</tr>
						<?php
					}
				}
				?>
			</table>
        </div>
        <div id="shipping-method" class="order-section">
            <h6><?php _e('Shipping Method', 'wpc-shipment-consoldation'); ?></h6>
            <ul style="list-style: none; padding:0;">
				<?php $count = 1;?>
                <?php if( !empty( wpcshcon_shipping_methods() ) ): ?>
                    <?php foreach ( wpcshcon_shipping_methods() as $method ): ?>
                        <li>
                            <input type="radio" name="wpcshcon_shipping_method" value="<?php echo $method; ?>" class="form-check-input" id="wpcshcon_shipping_method-<?php echo $count;?>">
                            <label for="wpcshcon_shipping_method-<?php echo $count;?>" class="form-check-label"><?php echo $method; ?></label>
                        </li>
                        <?php $count++;?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
        <div id="packaging-option" class="order-section">
            <h6><?php _e('Packaging Options', 'wpc-shipment-consoldation'); ?></h6>
            <ul id="packaging-option-wrapper" style="list-style: none; padding:0;">
            <?php if( !empty( wpcshcon_package_options() ) ): ?>
                <?php 
                foreach ( wpcshcon_package_options() as $option ) {
                    $option_meta        = wpcshcon_text_to_meta( $option );
                    ?>
                    <li>
                        <input class="form-check-input" id="<?php echo $option_meta; ?>_field" type="checkbox" name="<?php echo $option_meta; ?>" value="<?php echo $option_meta; ?>">
                        <label for="<?php echo $option_meta; ?>_field" class="form-check-label"><?php echo $option; ?></label>
                    </li>
                    <?php
                } 
                ?>
            <?php endif; ?>
        </ul>
        </div>
        <div style="clear: both;"></div>
        <input class="btn btn-success" type="submit" name="submit" value="<?php _e('Submit New Order', 'wpc-shipment-consoldation' ); ?>">
    </form>
</div>