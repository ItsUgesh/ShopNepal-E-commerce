<p><strong><?php _e('Items:', 'wpc-shipment-consoldation' ); ?><strong></p>
<table style="border-collapse: collapse;">
    <tr>
        <th style="padding:8px;border:1px solid #767676;"><?php _e('Qty', 'wpc-shipment-consoldation' ); ?></th>
        <th style="padding:8px;border:1px solid #767676;"><?php _e('Description', 'wpc-shipment-consoldation' ); ?></th>
        <th style="padding:8px;border:1px solid #767676;"><?php _e('Dimension', 'wpc-shipment-consoldation' ); ?>(<?php echo $dimension_unit; ?>)</th>
        <th style="padding:8px;border:1px solid #767676;"><?php _e('Weight', 'wpc-shipment-consoldation' ); ?>(<?php echo $weight_unit; ?>)</th>
    </tr>
    <?php if( !empty( $package_list) ): foreach ($package_list as $package ): ?>
    <tr>
        <td style="padding:8px;border:1px solid #767676;"><?php echo $package['wpc-pm-qty']; ?></td>
        <td style="padding:8px;border:1px solid #767676;"><?php echo $package['wpc-pm-description']; ?></td>
        <td style="padding:8px;border:1px solid #767676;"><?php echo $package['wpc-pm-length'] .' X '.$package['wpc-pm-width'] .' X '.$package['wpc-pm-height']; ?></td>
        <td style="padding:8px;border:1px solid #767676;"><?php echo $package['wpc-pm-weight']; ?></td>
    </tr>
    <?php endforeach; else: ?>
    <tr>
        <td colspan="4" style="padding:8px;border:1px solid #767676;"><?php _e('No items in your package.', 'wpc-shipment-consoldation' ); ?></td>
    </tr>
    <?php endif; ?>
</table>