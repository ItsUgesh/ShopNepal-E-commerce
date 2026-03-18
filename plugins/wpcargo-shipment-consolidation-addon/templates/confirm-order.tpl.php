<div id="confirm_new_order_wrapper">
    <h4 class="wpcshcon-header-title"><?php _e('Confirm Consolidated Shipments', 'wpc-shipment-consoldation' ); ?></h4>
    <form id="confirm_new_order" method="post">
        <?php wp_nonce_field( 'wpcshcon_confirm_order_action', 'wpcshcon_confirm_order_nonce' ); ?>
        <input type="hidden" name="consolidateID" value="<?php echo $consolidateID; ?>" >
        <?php require_once( PQ_SHIPMENT_CONSOLIDATION_PATH.'templates/view.tpl.php' ); ?>
        <input class="wpcargo-btn wpcargo-btn-success wpcargo-btn-sm" type="submit" name="submit" value="<?php _e('Checkout', 'wpc-shipment-consoldation'); ?>">
    </form>
</div>