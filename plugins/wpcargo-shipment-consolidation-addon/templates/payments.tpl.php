<div id="consolidation-orders-wrapper" class="table-responsive" >
    <h3 class="wpcshcon-header-title" ><?php echo  __('Payments', 'wpc-shipment-consoldation' ); ?></h3>
    <p><a class="wpcargo-btn wpcargo-btn-sm wpcargo-btn-primary" href="<?php echo get_the_permalink( ); ?>"><?php _e('Dashboard', 'wpc-shipment-consoldation' ); ?></a></p>
    <table class="wpcargo-table wpcargo-table-responsive-md wpcargo-table-striped">
        <thead>
            <tr>
                <th><?php _e('Date', 'wpc-shipment-consoldation'); ?></th>
                <th><?php _e('Order No.', 'wpc-shipment-consoldation'); ?></th>
                <th><?php _e('Consolidation No.', 'wpc-shipment-consoldation'); ?></th>
                <th><?php _e('Payment Method', 'wpc-shipment-consoldation'); ?></th>
                <th><?php _e('Amount', 'wpc-shipment-consoldation'); ?></th>
                <th><?php _e('Payment Status', 'wpc-shipment-consoldation'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if( !empty( $payments ) ){
                foreach( $payments as $customer_order ){
                    $order      = wc_get_order( $customer_order->ID );
                    $payment_method = $order->get_payment_method();
                    $order_total    = $order->get_formatted_order_total();
                    ?>
                    <tr>
                        <td class="no-space"><time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time></td>
                        <td><a href="<?php echo $wooorder_url; ?>view-order/<?php echo $order->get_order_number(); ?>"><?php echo _x( '#', 'hash before order number', 'wpc-shipment-consoldation' ) . $order->get_order_number(); ?></a></td>
                        <td>
                        <?php
                            $consolidations = ( wpcshcon_get_order_consolidation( $customer_order->ID ) );
                            if( !empty( $consolidations ) ){
                                ?><ul class="consolidation-list"><?php
                                foreach( $consolidations as $consolidateID ){
                                    $consolidation_number = '<a href="'.get_the_permalink().'?shconviewId='.$consolidateID.'">'.get_the_title( $consolidateID ).'</a>';
                                    ?> <li><?php echo $consolidation_number; ?></li><?php
                                }
                                ?></ul><?php
                            }else{
                                echo '------------';
                            }
                            ?>
                        </td>
                        <td><?php echo $payment_method; ?></td>
                        <td><?php echo $order_total; ?></td>
                        <td class="no-space"><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></td>
                    </tr>
                    <?php
                }
            }else{
                ?><tr><td colspan="6"><?php _e( 'No order has been made yet.', 'wpc-shipment-consoldation' ); ?></td></tr><?php
            }
            ?>
        </tbody>
    </table>
</div>

