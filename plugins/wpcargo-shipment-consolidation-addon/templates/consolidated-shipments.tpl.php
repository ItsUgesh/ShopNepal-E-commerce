<div id="consolidation-orders-wrapper" class="table-responsive" >
    <h3 class="wpcshcon-header-title" ><?php echo  __('Consolidated Shipments', 'wpc-shipment-consoldation' ); ?></h3>
    <table id="consolidation-orders-table" class="wpcargo-table wpcargo-table-responsive-md wpcargo-table-striped">
        <thead>
            <tr>
                <th><?php echo  __('Date', 'wpc-shipment-consoldation' ); ?></th>
                <th><?php echo  __('Order No.', 'wpc-shipment-consoldation' ); ?></th>
                <th><?php echo  __('Consolidation No.', 'wpc-shipment-consoldation' ); ?></th>
                <th><?php echo  __('Shipping Method', 'wpc-shipment-consoldation' ); ?></th>
                <th><?php echo  __('Ship to', 'wpc-shipment-consoldation' ); ?></th>
                <th><?php echo  __('Status', 'wpc-shipment-consoldation' ); ?></th>
                <th><?php echo  __('Amount', 'wpc-shipment-consoldation' ); ?></th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if( !empty( $consolidated_shipments ) ){
            foreach ($consolidated_shipments as $consolidationID ) {
                $orderID            = wpcshcon_get_consolidation_order( $consolidationID );
                $shipping_method    = get_post_meta( $consolidationID, 'wpcshcon_shipping_method', true );
                $shipto             = get_post_meta( $consolidationID, 'wpcargo_receiver_name', true );
				
                $wpcargo_status = get_post_meta( $consolidationID, 'wpcargo_status', true );
                $shipmentView   = '';
                $consolidation_number = '<a href="'.get_the_permalink().'?shconviewId='.$consolidationID.'">'.get_the_title( $consolidationID ).'</a>';
                if( $wpcargo_status == wpcshcon_awaiting_status() ){
                    $shipmentView = '<a href="'.get_the_permalink().'?cforder='.$consolidationID.'">'.__('Pay Now', 'wpc-shipment-consoldation' ).'</a>';
                    $consolidation_number = '<a href="'.get_the_permalink().'?cforder='.$consolidationID.'">'.get_the_title( $consolidationID ).'</a>';
                }else{
                    $shipmentView = '<a href="'.get_the_permalink().'?shconviewId='.$consolidationID.'">'.__('View', 'wpc-shipment-consoldation' ).'</a>';
                }
                ?>
                <tr>
                    <td class="no-space"><?php echo get_the_date( 'F j, Y', $consolidationID ); ?></td>
                    <td class="no-space"><?php echo $orderID; ?></td>
                    <td><?php echo $consolidation_number; ?></td>
                    <td><?php echo $shipping_method; ?></td>
                    <td class="no-space"><?php echo $shipto; ?></td>
                    <td class="no-space"><?php echo $wpcargo_status; ?></td>
                    <td class="no-space"><?php echo get_woocommerce_currency_symbol(); ?> <?php echo wpcshcon_get_consolidation_cost( $consolidationID ); ?></td>
                    <td class="no-space"><?php echo $shipmentView; ?></td>
                </tr>
                <?php
            }
        }else{
            ?><tr><td colspan="8"><?php echo  __('No Orders found.', 'wpc-shipment-consoldation' ); ?></td></tr><?php
        }
        ?>
        </tbody>
    </table>
</div>

