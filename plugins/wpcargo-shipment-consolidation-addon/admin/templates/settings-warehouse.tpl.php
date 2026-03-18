<h3><?php _e('Warehouse Address', 'wpc-shipment-consoldation'); ?></h3>
<p class="description"><?php _e('Note: This will the shipment storage warehouse address.', 'wpc-shipment-consoldation'); ?></p>
<table class="whs-table" id="fieldset-warehouse" style="width:600px;">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th style="text-align:left"><?php _e('Assign to Field', 'wpc-shipment-consoldation'); ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th style="text-align:left"><?php _e('Warehouse Name', 'wpc-shipment-consoldation' ); ?></th>
            <td><input type="text" name="wpcshcon_warehouse_name" value="<?php echo $wpcshcon_warehouse_name; ?>"></td>
            <td>
                <select name="wpcshcon_whs_assign_field[name]" >
                    <option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
                    <?php foreach ($shipper_fields as $value) : ?>
                        <option value="<?php echo $value['field_key']; ?>" <?php selected( $wpcshcon_whs_assign_field['name'], $value['field_key'] ); ?>><?php echo $value['label']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th style="text-align:left"><?php _e('User Address Prefix', 'wpc-shipment-consoldation' ); ?></th>
            <td><input type="text" name="wpcshcon_prefix" value="<?php echo $wpcshcon_prefix; ?>"></td>
        </tr>
        <tr>
            <th style="text-align:left"><label for="warehouse_phone"><?php _e('Phone', 'wpc-shipment-consoldation'); ?></label></th>
            <td>
                <input type="text" name="wpcshcon_warehouse_address[phone]" value="<?php echo $wpcshcon_warehouse_address['phone']; ?>">
            </td>
            <td>
                <select name="wpcshcon_whs_assign_field[phone]" >
                    <option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
                    <?php foreach ($shipper_fields as $value) : ?>
                        <option value="<?php echo $value['field_key']; ?>" <?php selected( $wpcshcon_whs_assign_field['phone'], $value['field_key'] ); ?>><?php echo $value['label']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th style="text-align:left"><label for="warehouse_address_1"><?php _e('Address line 1', 'wpc-shipment-consoldation'); ?></label></th>
            <td>
                <input type="text" name="wpcshcon_warehouse_address[address_1]" value="<?php echo $wpcshcon_warehouse_address['address_1']; ?>">
            </td>
            <td>
                <select name="wpcshcon_whs_assign_field[address_1]" >
                    <option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
                    <?php foreach ($shipper_fields as $value) : ?>
                        <option value="<?php echo $value['field_key']; ?>" <?php selected( $wpcshcon_whs_assign_field['address_1'], $value['field_key'] ); ?>><?php echo $value['label']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th style="text-align:left"><label for="warehouse_city"><?php _e('City', 'wpc-shipment-consoldation'); ?></label></th>
            <td>
                <input type="text" name="wpcshcon_warehouse_address[city]" value="<?php echo $wpcshcon_warehouse_address['city']; ?>">
            </td>
            <td>
                <select name="wpcshcon_whs_assign_field[city]" >
                    <option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
                    <?php foreach ($shipper_fields as $value) : ?>
                        <option value="<?php echo $value['field_key']; ?>" <?php selected( $wpcshcon_whs_assign_field['city'], $value['field_key'] ); ?>><?php echo $value['label']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th style="text-align:left"><label for="warehouse_postcode"><?php _e('Postcode / ZIP', 'wpc-shipment-consoldation'); ?></label></th>
            <td>
                <input type="text" name="wpcshcon_warehouse_address[postcode]" value="<?php echo $wpcshcon_warehouse_address['postcode']; ?>">
            </td>
            <td>
                <select name="wpcshcon_whs_assign_field[postcode]" >
                    <option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
                    <?php foreach ($shipper_fields as $value) : ?>
                        <option value="<?php echo $value['field_key']; ?>" <?php selected( $wpcshcon_whs_assign_field['postcode'], $value['field_key'] ); ?>><?php echo $value['label']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th style="text-align:left"><label for="warehouse_state"><?php _e('State / County', 'wpc-shipment-consoldation'); ?></label></th>
            <td>
                <input type="text" name="wpcshcon_warehouse_address[state]" value="<?php echo $wpcshcon_warehouse_address['state']; ?>">
            </td>
            <td>
                <select name="wpcshcon_whs_assign_field[state]" >
                    <option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
                    <?php foreach ($shipper_fields as $value) : ?>
                        <option value="<?php echo $value['field_key']; ?>" <?php selected( $wpcshcon_whs_assign_field['state'], $value['field_key'] ); ?>><?php echo $value['label']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th style="text-align:left"><label for="warehouse_country"><?php _e('Country', 'wpc-shipment-consoldation'); ?></label></th>
            <td>
                <input type="text" name="wpcshcon_warehouse_address[country]" value="<?php echo $wpcshcon_warehouse_address['country']; ?>">
            </td>
            <td>
                <select name="wpcshcon_whs_assign_field[country]" >
                    <option value=""><?php _e('--Select One--', 'wpc-shipment-consoldation'); ?></option>
                    <?php foreach ($shipper_fields as $value) : ?>
                        <option value="<?php echo $value['field_key']; ?>" <?php selected( $wpcshcon_whs_assign_field['country'], $value['field_key'] ); ?>><?php echo $value['label']; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </tbody>
</table>