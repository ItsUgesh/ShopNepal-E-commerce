<div id="shipment-details">
	<h1><?php echo apply_filters('wpc_shipment_details_label', esc_html__('Shipment Details', 'wpcargo' ) ); ?></h1>
	<?php do_action('wpc_before_shipment_details_table', $post->ID); ?>
	<table class="wpcargo form-table">
		<?php do_action('wpc_before_shipment_details_metabox', $post->ID); ?>
		<tr>
			<th><label><?php esc_html_e('Type of Shipment', 'wpcargo' ); ?></label></th>
			<td>
				<?php if( !empty( $shipment_type_list ) ){ ?>
					<select name="wpcargo_type_of_shipment">
						<option value=""><?php esc_html_e('-- Select One --', 'wpcargo' ); ?></option>
						<?php foreach ( $shipment_type_list as $val) { ?>
							<option value="<?php echo trim( esc_html( $val ) ); ?>" <?php echo ( trim( esc_html( $val ) ) == esc_html( get_post_meta($post->ID, 'wpcargo_type_of_shipment', true) )) ? 'selected' : '' ; ?>><?php echo trim( esc_html( $val ) ); ?></option>
						<?php } ?>
					</select>
				<?php } ?>
				<?php if( empty( $shipment_type ) ): ?>
					<span class="meta-box error">
						<strong>
							<?php esc_html__('No Selection setup, Please add selection', 'wpcargo'); ?>
							<a href="<?php echo admin_url().'/admin.php?page=wpcargo-settings'; ?>" ><?php esc_html__('here.', 'wpcargo'); ?></a>
						</strong>
					</span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><label><?php esc_html_e('Net weight','wpcargo'); ?></label></th>
			<td><input type="text" id="wpcargo_weight" name="wpcargo_weight" value="<?php echo esc_html( get_post_meta($post->ID, 'wpcargo_weight', true) ); ?>"size="25" /></td>
		</tr>
		<tr>
			<th><label><?php esc_html_e('Chargable weight','wpcargo'); ?></label></th>
			<td><input type="number" id="wpcargo_chargable_weight" name="wpcargo_chargable_weight" value="<?php echo esc_html( get_post_meta($post->ID, 'wpcargo_chargable_weight', true) ); ?>"size="25" /></td>
		</tr>
		<tr>
			<th><label><?php esc_html_e('Number of pieces','wpcargo'); ?></label></th>
			<td><input type="text" id="pack" name="wpcargo_packages" value="<?php echo esc_html( get_post_meta($post->ID, 'wpcargo_packages', true) ); ?>"size="25" /></td>
		</tr>
		<tr>
			<th><label><?php esc_html_e('Mode','wpcargo'); ?></label></th>
			<td>
				<?php if( !empty($shipment_mode_list) ){ ?>
					<select name="wpcargo_mode_field">
						<option value=""><?php esc_html_e('-- Select One --', 'wpcargo' ); ?></option>
						<?php foreach ( $shipment_mode_list as $val) { ?>
							<option value="<?php echo trim( esc_html( $val ) ); ?>" <?php echo ( trim( esc_html( $val ) ) == esc_html( get_post_meta($post->ID, 'wpcargo_mode_field', true) )) ? 'selected' : '' ; ?>><?php echo trim( esc_html( $val ) ); ?></option>
						<?php } ?>
					</select>
				<?php } ?>
				<?php if( empty( $shipment_mode ) ): ?>
					<span class="meta-box error">
						<strong>
							<?php esc_html__('No Selection setup, Please add selection', 'wpcargo'); ?>
							<a href="<?php echo admin_url().'/admin.php?page=wpcargo-settings'; ?>" ><?php esc_html__('here.', 'wpcargo'); ?></a>
						</strong>
					</span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><label><?php esc_html_e('Origin','wpcargo'); ?></label></th>
			<td>
				<?php if( !empty($shipment_country_org_list) ){ ?>
					<select name="wpcargo_origin_field">
						<option value=""><?php esc_html_e('-- Select One --', 'wpcargo' ); ?></option>
						<?php foreach ( $shipment_country_org_list as $val) { ?>
							<option value="<?php echo trim( esc_html( $val ) ); ?>" <?php echo ( trim( esc_html( $val ) ) == esc_html( get_post_meta($post->ID, 'wpcargo_origin_field', true) )) ? 'selected' : '' ; ?> ><?php echo trim( esc_html( $val ) ); ?></option>
						<?php } ?>
					</select>
				<?php } ?>
				<?php if( empty( $shipment_country_org ) ): ?>
					<span class="meta-box error">
						<strong>
							<?php esc_html__('No Selection setup, Please add selection', 'wpcargo'); ?>
							<a href="<?php echo admin_url().'/admin.php?page=wpcargo-settings'; ?>" ><?php esc_html__('here.', 'wpcargo'); ?></a>
						</strong>
					</span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><label><?php esc_html_e('Destination','wpcargo'); ?></label></th>
			<td>
				<?php if( !empty( $shipment_country_des_list ) ){ ?>
					<select id="dest_1" name="wpcargo_destination">
						<option value=""><?php esc_html_e('-- Select One --', 'wpcargo' ); ?></option>
						<?php foreach ( $shipment_country_des_list as $val) { ?>
							<option value="<?php echo trim( esc_html( $val ) ); ?>" <?php echo ( trim( esc_html( $val ) ) == esc_html( get_post_meta($post->ID, 'wpcargo_destination', true) )) ? 'selected' : '' ; ?> ><?php echo trim( esc_html( $val ) ); ?></option>
						<?php } ?>
					</select>
				<?php } ?>
				<?php if( empty( $shipment_country_des ) ): ?>
					<span class="meta-box error">
						<strong>
							<?php esc_html__('No Selection setup, Please add selection', 'wpcargo'); ?>
							<a href="<?php echo admin_url().'/admin.php?page=wpcargo-settings'; ?>" ><?php esc_html__('here.', 'wpcargo'); ?></a>
						</strong>
					</span>
				<?php endif; ?>
			</td>
		</tr>
		<!-- <tr>
			<th><label><?php //esc_html_e('Pickup Date','wpcargo'); ?></label></th>
			<td><input type='text' class="wpcargo-datepicker" id='datetimepicker4' name="wpcargo_pickup_date_picker" autocomplete="off" value="<?php //echo esc_html( $wpcargo_pickup_date_picker ); ?>"/></td>
		</tr>
		<tr>
			<th><label><?php //esc_html_e('Pickup Time :','wpcargo'); ?></label></th>
			<td><input type='text' class="wpcargo-timepicker" id='datetimepicker7' name="wpcargo_pickup_time_picker" autocomplete="off" value="<?php //echo esc_html( get_post_meta($post->ID, 'wpcargo_pickup_time_picker', true) ); ?>" /></td>
		</tr>
		<tr>
			<th><label><?php //esc_html_e('Expected Delivery Date', 'wpcargo'); ?></label></th>
			<td><input type='text' class="wpcargo-datepicker" id='datetimepicker3' name="wpcargo_expected_delivery_date_picker" autocomplete="off" value="<?php //echo esc_html(  $wpcargo_expected_delivery_date_picker ); ?>"/></td>
		</tr>
		<tr>
			<th><label><?php //esc_html_e('Comments','wpcargo'); ?></label></th>
			<td><textarea rows="4" cols="50" id="wpcargo_comments" name="wpcargo_comments"><?php //echo esc_html( get_post_meta($post->ID, 'wpcargo_comments', true) ); ?></textarea></td>
		</tr>
		<tr>
		<th><label><?php //esc_html_e('Des. of Goods','wpcargo'); ?></label></th>
			<td><input type="text" id="prod" name="wpcargo_des_of_goods" value="<?php echo esc_html( get_post_meta($post->ID, 'wpcargo_des_of_goods', true) ); ?>" size="40" /></td>
		</tr> -->
		<?php do_action('wpc_after_shipment_details_metabox', $post->ID); ?>
	</table>
	<?php do_action('wpc_after_shipment_details_table', $post->ID); ?>
</div>