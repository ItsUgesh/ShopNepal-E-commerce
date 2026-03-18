<h2 class="wpcshcon-header-title"><?php echo  $wpcargo->status[$statuskey]; ?></h2>
<?php $this->consolidation_menu(); ?>
<div id="wpcshcon-pconfirm-wrapper" class="table-responsive">
    <table id="wpcshcon-all-shipment" class="wpcargo-table wpcargo-table-responsive-md wpcargo-table-striped">
        <thead>
            <tr>
                <th><?php _e('Date', 'wpc-shipment-consoldation'); ?></th>
                <th><?php _e('Shipment Number', 'wpc-shipment-consoldation'); ?></th>
                <th><?php _e('Consildation NO.', 'wpc-shipment-consoldation'); ?></th>
                <th><?php _e('Store/Sender', 'wpc-shipment-consoldation'); ?></th>
                <th><?php _e('Warehouse', 'wpc-shipment-consoldation'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
                if ( $consolidation->have_posts() ) :
                    while ( $consolidation->have_posts() ) : $consolidation->the_post();
                    $wpcshcon_status = get_post_meta( get_the_ID(), 'wpcargo_status', true );
                    // Addon and Fees
                    $total_cost = wpcshcon_get_consolidation_cost( get_the_ID() );
                    // Shipping Method
                    $shipping_method = get_post_meta( get_the_ID(), 'wpcshcon_shipping_method', true );

                    $consolidation_number = '';
                    $consolidation_url    = '#';
                    $consolidation_id     = get_shipment_consolidation( get_the_ID() );

                    if( $consolidation_id ){
                        $consolidation_number = get_the_title( $consolidation_id );
                        $consolidation_url    = $dashboard_url.'?shconviewId='.$consolidation_id;
                    }   
                    ?>
                    <tr>
                        <td><?php echo get_the_date( 'F j, Y', get_the_ID() ); ?></td>
                        <td><a href="<?php echo $dashboard_url ?>?wpcshview=<?php echo get_the_ID(); ?>"><?php echo get_the_title(); ?></a></td>
                        <td><a href="<?php echo $consolidation_url; ?>"><?php echo $consolidation_number; ?></a></td>
                        <td><?php echo get_post_meta( get_the_ID(), 'wpcshcon_store', true ); ?></td>
                        <td><?php echo get_post_meta( get_the_ID(), 'wpcshcon_warehouse', true ); ?></td>
                    </tr>
                    <?php
                    endwhile;
                else:
                    ?><td colspan="5"><?php _e('No', 'wpc-shipment-consoldation' ); ?> <?php echo  $wpcargo->status[$statuskey]; ?> <?php _e('Consolidation found!', 'wpc-shipment-consoldation' ); ?></td><?php
                endif;
            ?>
        </tbody>
    </table>
</div>