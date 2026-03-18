<?php $this->consolidation_menu(); ?>
<form id="create_new_order" method="post" class="table-responsive">
    <?php wp_nonce_field( 'wpcshco_create_order_action', 'wpcshco_create_order_nonce' ); ?>
    <input type="hidden" name="step" value="1"/>
    <table id="wpcshcon-all-shipment" class="table table-hover table-sm">
        <thead>
            <tr>
                <th><input id="select-all" type="checkbox" name="select-all" /></th>
                <th><?php _e('Date', 'wpc-shipment-consoldation'); ?></th>
                <th><?php _e('Sender/Tracking Number', 'wpc-shipment-consoldation'); ?></th>
                <th><?php _e('Warehouse', 'wpc-shipment-consoldation'); ?></th>
                <th><?php _e('Status', 'wpc-shipment-consoldation'); ?></th>
                <th><?php _e('Free Storage Left', 'wpc-shipment-consoldation'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
				$counter = 1;
                if ( !empty( $ready_shipment ) ) :
                    foreach( $ready_shipment as $shipmentID ):
                    ?>
                    <tr>
                        <td>
							<input class="shipment-options form-check-input" type="checkbox" name="shipment[]" value="<?php echo $shipmentID; ?>" required="required" id="shipment-consolidate-<?php echo $counter;?>">
							<label class="form-check-label" for="shipment-consolidate-<?php echo $counter;?>"></label>
						</td>
                        <td class="no-space"><?php echo get_the_date( 'F j, Y', $shipmentID ); ?></td>
                        <td>
                            <?php _e('Sender', 'wpc-shipment-consoldation'); ?>: <?php echo get_post_meta( $shipmentID, 'wpcshcon_store', true ); ?><br/>
                            <?php echo get_the_title( $shipmentID ); ?>
                        </td>
                        <td><?php echo get_post_meta( $shipmentID, 'wpcshcon_warehouse', true ); ?></td>
                        <td class="no-space"><?php echo get_post_meta( $shipmentID, 'wpcargo_status', true ); ?></td>
                        <td><?php echo get_shipment_storage_left( $shipmentID ); ?> <?php _e('Day(s)', 'wpc-shipment-consoldation'); ?></td>
                    </tr>
                    <?php
					$counter++;
                    endforeach;
                endif;
            ?>
        </tbody>
    </table>
    <h4><?php echo apply_filters( 'wpcshcon_dashboard_create_new_order_title', __('Consolidate', 'wpc-shipment-consoldation' ) ); ?></h4>
    <div id="ship-to-section">
        <?php if( has_action( 'before_wpcshcon_receiver_info_form_fields' ) ): ?>
        	<p><?php _e('Ship To:', 'wpc-shipment-consoldation'); ?></p>
			<?php do_action( 'before_wpcshcon_receiver_info_form_fields', 0 ); ?>
		<?php endif; ?>
        <div id="receiver-fields">
        <?php do_action( 'before_wpcshcon_receiver_form_fields' ); ?>
        <?php $WPCCF_Fields->convert_to_form_fields( $receiver_fields ); ?>
        </div>
        <script>
            jQuery(document).ready(function($){
                var requiredCheckboxes = $('#wpcshcon-all-shipment .shipment-options');
                requiredCheckboxes.on('change', function(e) {
                var checkboxGroup = requiredCheckboxes.filter('[name="' + $(this).attr('name') + '"]');
                var isChecked = checkboxGroup.is(':checked');
                checkboxGroup.prop('required', !isChecked);
                });
                <?php
                if( !empty( $default_receiver ) ){
                    foreach( $default_receiver as $_wooField => $_wooValue ){
                        if( empty( $_wooValue ) ){
                            continue;
                        }
                        $dataValue = maybe_unserialize( get_user_meta( get_current_user_id(), $_wooField, true ) );
                        if( is_array( $dataValue ) ){
                            $dataValue = implode(', ', $dataValue );
                        }
                        if( $_wooField == 'email' ){
                            $user_info = get_userdata( get_current_user_id() );
                            $dataValue = $user_info->user_email;
                        }
                        if( $_wooField == 'first_name' ){
                            $user_info = get_userdata( get_current_user_id() );
                            $dataValue = $user_info->first_name;
                        }
                        if( $_wooField == 'last_name' ){
                            $user_info = get_userdata( get_current_user_id() );
                            $dataValue = $user_info->last_name;
                        }
                        ?>
                        setTimeout(() => {
                            $('input[name="<?php echo $_wooValue; ?>"]').val('<?php echo $dataValue; ?>');
                        }, 100 );
                        <?php
                    }
                }
                ?>
            });
        </script>
		<?php if( has_action( 'after_wpcshcon_receiver_info_form_fields' ) ): ?>
			<?php do_action( 'after_wpcshcon_receiver_info_form_fields', 0 ); ?>
		<?php endif; ?>
    </div>
    <input class="wpcargo-btn wpcargo-btn-success" type="submit" name="submit" value="<?php _e('Next', 'wpc-shipment-consoldation'); ?>">
</form>