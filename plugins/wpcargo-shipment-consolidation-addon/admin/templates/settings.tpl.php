<form method="post" action="options.php">
	<?php
	settings_fields( 'wpcshcon_option_group' );
	do_settings_sections( 'wpcshcon_option_group' ); ?>
    <div id="wpcshcon-dashboard-setup" class="settings-section">
        <table class="form-table">
			<tr>
                <th><?php _e('Select Product for Shipment Consolidation Payment', 'wpc-shipment-consoldation' ); ?></th>
                <td>
                    <?php
                    if( !empty( $products  ) ){
                        ?><select name="wpcshcon_product" id="wpcshcon_product"><?php
                            ?><option value=""><?php _e('Select Product', 'wpc-shipment-consoldation' ); ?></option><?php
							foreach( $products as $product ){
								?><option value="<?php echo $product; ?>" <?php selected( $wpcshcon_product, $product ); ?> ><?php echo get_the_title( $product ); ?></option><?php
							}
                        ?></select><?php
                    }else{
                        _e('No Product found.', 'wpc-shipment-consoldation' );
                    }
                    ?>
                </td>
            </tr>
			<tr>
                <th><?php _e('Select Shipment Status to Ready for Consolidation', 'wpc-shipment-consoldation' ); ?></th>
                <td>
                    <?php
                    if( !empty( $wpcargo->status ) ){
                        ?><select name="wpcshcon_ready_status" id="wpcshcon_ready_status"><?php
                            ?><option value=""><?php _e('Select Status', 'wpc-shipment-consoldation' ); ?></option><?php
                        foreach( $wpcargo->status as $status ){
                            if( $status == wpcshcon_awaiting_status() ){
                                continue;
                            }
                            ?><option value="<?php echo $status; ?>" <?php selected( $wpcshcon_ready_status, $status ); ?> ><?php echo $status; ?></option><?php
                        }
                        ?></select><?php
                    }else{
                        _e('No Shipment Status is set ', 'wpc-shipment-consoldation' );
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th><?php _e('Select Shipment Status after Consolidation', 'wpc-shipment-consoldation' ); ?></th>
                <td>
                    <?php
                    if( !empty( $wpcargo->status ) ){
                        ?><select name="wpcshcon_default_status" id="wpcshcon_default_status"><?php
                            ?><option value=""><?php _e('Select Status', 'wpc-shipment-consoldation' ); ?></option><?php
                        foreach( $wpcargo->status as $status ){
                            if( $status == wpcshcon_awaiting_status() ){
                                continue;
                            }
                            ?><option value="<?php echo $status; ?>" <?php selected( $wpcshcon_default_status, $status ); ?> ><?php echo $status; ?></option><?php
                        }
                        ?></select><?php
                    }else{
                        _e('No Shipment Status is set ', 'wpc-shipment-consoldation' );
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th><?php _e('Select Shipment Status after Order Submit is Completed', 'wpc-shipment-consoldation' ); ?></th>
                <td>
                    <?php
                    if( !empty( $wpcargo->status ) ){
                        ?><select name="wpcshcon_order_submit_status" id="wpcshcon_order_submit_status"><?php
                            ?><option value=""><?php _e('Select Status', 'wpc-shipment-consoldation' ); ?></option><?php
                        foreach( $wpcargo->status as $status ){
                            if( $status == wpcshcon_awaiting_status() ){
                                continue;
                            }
                            ?><option value="<?php echo $status; ?>" <?php selected( $wpcshcon_order_submit_status, $status ); ?> ><?php echo $status; ?></option><?php
                        }
                        ?></select><?php
                    }else{
                        _e('No Shipment Status is set ', 'wpc-shipment-consoldation' );
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th><?php _e('Select Shipment Status to display in dashboard', 'wpc-shipment-consoldation' ); ?></th>
                <td>
                    <?php
                    if( !empty( $wpcargo->status ) ){
                        ?><ul><?php
                            foreach( $wpcargo->status as $status ){
                                if( $status == wpcshcon_awaiting_status() ){
                                    continue;
                                }
                                ?><li><input type="checkbox" name="wpcshcon_dashboard_status[]" value="<?php echo $status; ?>" <?php echo in_array( $status, $wpcshcon_dashboard_status ) ? 'checked' : '' ; ?> > <?php echo $status; ?></li><?php
                            }
                        ?></ul><?php
                    }else{
                        _e('No Shipment Status is set ', 'wpc-shipment-consoldation' );
                    }
                    ?>
                </td>
            </tr>
			<tr>
                <th colspan="2"><h3><?php _e('Packages / Shipping Methods', 'wpc-shipment-consoldation' ); ?></h3></th>
			</tr>
			<tr>
                <th><?php _e('Add Package Options', 'wpc-shipment-consoldation' ); ?></th>
                <td>
					<textarea name="wpcshcon_package_options" id="wpcshcon_package_options" cols="60" rows="6"><?php echo $wpcshcon_package_options; ?></textarea>
					<p><?php _e('Each option must ne separated with comma (,). ex. option1,option2...', 'wpc-shipment-consoldation' ); ?></p>
                </td>
			</tr>
			<tr>
                <th><?php _e('Add Shipping Methods', 'wpc-shipment-consoldation' ); ?></th>
                <td>
					<textarea name="wpcshcon_shipping_methods" id="wpcshcon_shipping_methods" cols="60" rows="6"><?php echo $wpcshcon_shipping_methods; ?></textarea>
					<p><?php _e('Each option must ne separated with comma (,). ex. method1,method2...', 'wpc-shipment-consoldation' ); ?></p>
                </td>
			</tr>
        </table>
	</div>
	<div id="map-information">
		<div id="default-receiver-section" class="settings-section">
			<h3><?php _e('Map Default Receiver', 'wpc-shipment-consoldation'); ?></h3>
			<p class="description"><?php _e('Note: This field mapping will auto populate on the Ship To section in Create Order as default field data.', 'wpc-shipment-consoldation'); ?></p>
			<table class="form-table" id="fieldset-receiver">
			<tbody>
				<tr>
					<th><label for="receiver_first_name"><?php _e('First name', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $receiver_fields )) : ?>
						<select name="wpcshcon_default_receiver[first_name]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($receiver_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $wpcshcon_default_receiver['first_name'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="receiver_last_name"><?php _e('Last name', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $receiver_fields )) : ?>
						<select name="wpcshcon_default_receiver[last_name]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($receiver_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $wpcshcon_default_receiver['last_name'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="receiver_company"><?php _e('Company', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $receiver_fields )) : ?>
						<select name="wpcshcon_default_receiver[billing_company]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($receiver_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $wpcshcon_default_receiver['billing_company'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="receiver_address_1"><?php _e('Address line 1', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $receiver_fields )) : ?>
						<select name="wpcshcon_default_receiver[billing_address_1]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($receiver_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $wpcshcon_default_receiver['billing_address_1'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="receiver_address_2"><?php _e('Address line 2', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $receiver_fields )) : ?>
						<select name="wpcshcon_default_receiver[billing_address_2]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($receiver_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $wpcshcon_default_receiver['billing_address_2'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="receiver_city"><?php _e('City', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $receiver_fields )) : ?>
						<select name="wpcshcon_default_receiver[billing_city]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($receiver_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $wpcshcon_default_receiver['billing_city'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="receiver_postcode"><?php _e('Postcode / ZIP', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $receiver_fields )) : ?>
						<select name="wpcshcon_default_receiver[billing_postcode]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($receiver_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $wpcshcon_default_receiver['billing_postcode'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="receiver_country"><?php _e('Country', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $receiver_fields )) : ?>
						<select name="wpcshcon_default_receiver[billing_country]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($receiver_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $wpcshcon_default_receiver['billing_country'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="receiver_state"><?php _e('State / County', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $receiver_fields )) : ?>
						<select name="wpcshcon_default_receiver[billing_state]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($receiver_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $wpcshcon_default_receiver['billing_state'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="receiver_phone"><?php _e('Phone', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $receiver_fields )) : ?>
						<select name="wpcshcon_default_receiver[billing_phone]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($receiver_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $wpcshcon_default_receiver['billing_phone'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="receiver_email"><?php _e('Email address', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $receiver_fields )) : ?>
						<select name="wpcshcon_default_receiver[email]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($receiver_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $wpcshcon_default_receiver['email'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		<div id="billing-section" class="settings-section">
			<h3><?php _e('Map Woocommerce Shipper', 'wpc-shipment-consoldation'); ?></h3>
			<p class="description"><?php _e('Note: This field mapping will auto populate Consolidation Shipper field after Checkout.', 'wpc-shipment-consoldation'); ?></p>
			<table class="form-table" id="fieldset-billing">
			<tbody>
				<tr>
					<th><label for="billing_first_name"><?php _e('First name', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $shipper_fields )) : ?>
						<select name="billing_wooorder_integ_option[first_name]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($shipper_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $billing_wooorder_integ_option['first_name'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="billing_last_name"><?php _e('Last name', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $shipper_fields )) : ?>
						<select name="billing_wooorder_integ_option[last_name]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($shipper_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $billing_wooorder_integ_option['last_name'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="billing_company"><?php _e('Company', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $shipper_fields )) : ?>
						<select name="billing_wooorder_integ_option[company]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($shipper_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $billing_wooorder_integ_option['company'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="billing_address_1"><?php _e('Address line 1', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $shipper_fields )) : ?>
						<select name="billing_wooorder_integ_option[address_1]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($shipper_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $billing_wooorder_integ_option['address_1'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="billing_address_2"><?php _e('Address line 2', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $shipper_fields )) : ?>
						<select name="billing_wooorder_integ_option[address_2]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($shipper_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $billing_wooorder_integ_option['address_2'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="billing_city"><?php _e('City', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $shipper_fields )) : ?>
						<select name="billing_wooorder_integ_option[city]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($shipper_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $billing_wooorder_integ_option['city'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="billing_postcode"><?php _e('Postcode / ZIP', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $shipper_fields )) : ?>
						<select name="billing_wooorder_integ_option[postcode]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($shipper_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $billing_wooorder_integ_option['postcode'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="billing_country"><?php _e('Country', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $shipper_fields )) : ?>
						<select name="billing_wooorder_integ_option[country]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($shipper_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $billing_wooorder_integ_option['country'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="billing_state"><?php _e('State / County', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $shipper_fields )) : ?>
						<select name="billing_wooorder_integ_option[state]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($shipper_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $billing_wooorder_integ_option['state'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="billing_phone"><?php _e('Phone', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $shipper_fields )) : ?>
						<select name="billing_wooorder_integ_option[phone]" >
							<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
							<?php foreach ($shipper_fields as $value) : ?>
								<option value="<?php echo $value['field_key'];   ?>"<?php selected( $billing_wooorder_integ_option['phone'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th><label for="billing_email"><?php _e('Email address', 'wpc-shipment-consoldation'); ?></label></th>
					<td>
						<?php if(!empty( $shipper_fields )) : ?>
							<select name="billing_wooorder_integ_option[email]" >
								<option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
								<?php foreach ($shipper_fields as $value) : ?>
									<option value="<?php echo $value['field_key'];   ?>"<?php selected( $billing_wooorder_integ_option['email'], $value['field_key'] ); ?> ><?php echo $value['label']; ?></option>
								<?php endforeach; ?>
							</select>
						<?php endif; ?>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div id="warehouse-section" class="settings-section">
		<h3><?php _e('Warehouse Address', 'wpc-shipment-consoldation'); ?></h3>
		<p class="description"><?php _e('Note: This will the shipment storage warehouse address.', 'wpc-shipment-consoldation'); ?></p>
		<table class="form-table" id="fieldset-warehouse">
			<tbody>
				<tr>
					<th><?php _e('Warehouse Name', 'wpc-shipment-consoldation' ); ?></th>
					<td><input type="text" name="wpcshcon_warehouse_name" value="<?php echo $wpcshcon_warehouse_name; ?>"></td>
				</tr>
				<tr>
					<th><?php _e('Phone Number', 'wpc-shipment-consoldation' ); ?></th>
					<td><input type="text" name="wpcshcon_warehouse_number" value="<?php echo $wpcshcon_warehouse_number; ?>"></td>
				</tr>
				<tr>
					<th><?php _e('Address', 'wpc-shipment-consoldation' ); ?></th>
					<td><input type="text" name="wpcshcon_warehouse_address" value="<?php echo $wpcshcon_warehouse_address; ?>"></td>
				</tr>
				<tr>
					<th><?php _e('Email', 'wpc-shipment-consoldation' ); ?></th>
					<td><input type="email" name="wpcshcon_warehouse_email" value="<?php echo $wpcshcon_warehouse_email; ?>"></td>
				</tr>
			</tbody>
		</table>
		<h3><label for="wpcshcon_email_subject"><?php _e('Warehouse Email Subject', 'wpc-shipment-consoldation'); ?></label></h3>
		<p class="description"><?php _e('Note: This will overwrite default warehouse email subject', 'wpc-shipment-consoldation'); ?></p>
		<p><input id="wpcshcon_email_subject" class="large-text" type="text" name="wpcshcon_email_subject" value="<?php echo $wpcshcon_email_subject; ?>"></p>
		<h3><label for="wpcshcon_email_body"><?php _e('Warehouse Email Body', 'wpc-shipment-consoldation'); ?></label></h3>
		<p class="description"><?php _e('Note: This will display a message before the warehouse information email template after registration order payment is completed. You can use HTML tags to layout your message.', 'wpc-shipment-consoldation'); ?></p>
		<textarea name="wpcshcon_email_body" id="wpcshcon_email_body" class="large-text code" cols="60" rows="4"><?php echo $wpcshcon_email_body; ?></textarea>
		<h3><label for="wpcshcon_email_footer"><?php _e('Warehouse Email Footer', 'wpc-shipment-consoldation'); ?></label></h3>
		<p class="description"><?php _e('Note: This will override the default wpcargo email footer message. You can use HTML tags to layout your message.', 'wpc-shipment-consoldation'); ?></p>
		<textarea name="wpcshcon_email_footer" id="wpcshcon_email_footer" class="large-text code" cols="60" rows="4"><?php echo $wpcshcon_email_footer; ?></textarea>
	</div>
	<input class="button button-primary button-large" type="submit" name="submit" value="<?php _e('Save Settings', 'wpc-shipment-consoldation' ); ?>">
</form>