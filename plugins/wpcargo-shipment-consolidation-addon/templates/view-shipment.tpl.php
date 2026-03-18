<?php
$shipment_detail                = new stdClass;
$shipment_detail->ID            = $shipment_id;
$shipment_detail->post_title    = get_the_title( $shipment_id );
do_action( 'wpcargo_print_btn' ); 
?>
<div id="wpcargo-result-wrapper" class="wpcargo-wrap-details wpcargo-container">
    <?php
    do_action('wpcargo_before_track_details', $shipment_detail );
    do_action('wpcargo_track_header_details', $shipment_detail );
    do_action('wpcargo_track_after_header_details', $shipment_detail );
    do_action('wpcargo_track_shipper_details', $shipment_detail );
    do_action('wpcargo_before_shipment_details', $shipment_detail );
    do_action('wpcargo_track_shipment_details', $shipment_detail );
    do_action('wpcargo_after_track_details', $shipment_detail );
    ?>
</div>