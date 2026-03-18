<p><?php _e('Hello', 'wpc-shipment-consoldation' ); ?> {<?php echo $shipper_shortcode; ?>},</p>
<p><?php _e('We are happy to let you know that we finished processing your order', 'wpc-shipment-consoldation' ); ?> <strong># {wpcargo_tracking_number}</strong>.</p>
<p><?php _e('The new consolidated weight is', 'wpc-shipment-consoldation' ); ?> <?php echo $total_weight; ?> <?php _e('and the total shipment cost is', 'wpc-shipment-consoldation' ); ?> <strong><?php echo $cost; ?></strong>.</p>
<p><?php _e('Please login to your', 'wpc-shipment-consoldation' ); ?> <a href="<?php echo get_bloginfo('url'); ?>"><?php _e('account', 'wpc-shipment-consoldation' ); ?></a> <?php _e('and pay the invoice within 7 days to avoid any storage charges.', 'wpc-shipment-consoldation' ); ?></p>
<div class="notes" style="padding: 12px;background-color: #f7e5e5;">
    <p><strong><?php _e('Notes:', 'wpc-shipment-consoldation' ); ?></strong></p>
    <p><?php _e('After 10 days where no payment is received, your order will be considered as abandoned and will be disposed.', 'wpc-shipment-consoldation' ); ?></p>
</div>
<p><?php _e('Thank you for using', 'wpc-shipment-consoldation' ); ?> <?php echo get_bloginfo('name'); ?></p>