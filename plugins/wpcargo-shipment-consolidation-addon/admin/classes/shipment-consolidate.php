<?php
if(!defined('ABSPATH')){
    exit; // Exit if accessed directly
}

class WPC_Shipment_Consolidation_Post{
    function __construct(){

        add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ), 1 );
        add_filter( 'default_wpcargo_columns', array( $this, 'shipment_typle_column' ) );
        add_action( 'manage_wpcargo_shipment_posts_custom_column', array( $this, 'manage_wpcargo_columns' ), 10, 2 );
        add_action( 'admin_footer', array( $this, 'consolidate_shipment_callback' ) );
        add_action( 'save_post', array( $this, 'save_shipping_package_meta_boxes' ), 20 );

    }
    function register_meta_boxes() {
        add_meta_box(
            'wpcsh-consolidate-box-id',
            __( 'Consolidated Shipments', 'wpc-shipment-consoldation' ),
            array( $this, 'shipments_display_callback' ),
            'wpcargo_shipment'
        );
        add_meta_box(
            'wpcshcon-shipment-type-box-id',
            __( 'Shipment Type', 'wpc-shipment-consoldation' ),
            array( $this, 'shipment_type_display_callback' ),
            'wpcargo_shipment',
            'side',
            'high'
        );
        add_meta_box(
            'wpcshcon-mail-notification-box-id',
            __( 'Email Notification', 'wpc-shipment-consoldation' ),
            array( $this, 'mail_notification_display_callback' ),
            'wpcargo_shipment',
            'side',
            'high'
        );
        add_meta_box(
            'wpcsh-consolidate-order-box-id',
            __( 'Woo Order', 'wpc-shipment-consoldation' ),
            array( $this, 'order_display_callback' ),
            'wpcargo_shipment',
            'side',
            'high'
        );
        add_meta_box(
            'wpcshcon-store-order-box-id',
            __( 'Consolidation Information', 'wpc-shipment-consoldation' ),
            array( $this, 'sender_display_callback' ),
            'wpcargo_shipment',
            'side',
            'high'
        );
        
    }
    function shipment_typle_column( $columns ){
        $arr_position = count( $columns ) - 1;
        $columns = array_slice($columns, 0, $arr_position, true) +
            array( 'wpc_shipment_type' =>  __( 'Shipment Type', 'wpc-shipment-consoldation' ) ) +
            array_slice($columns, $arr_position, count($columns) - 1, true) ;
        return $columns;
    }
    function manage_wpcargo_columns( $column, $post_id ){
        if( $column == 'wpc_shipment_type' ){
            echo ucfirst( get_post_meta( $post_id, 'wpc_shipment_type', true ) );
        }
    }
    function shipment_type_display_callback( $post ){
        $wpc_shipment_type = get_post_meta( $post->ID, 'wpc_shipment_type', true );
        $wpc_shipment_type = ( $wpc_shipment_type ) ? $wpc_shipment_type : 'standard' ;
        ?>
        <div id="post-formats-select" class="wpcshcon-shipment-type">
            <fieldset>
                <legend class="screen-reader-text"><?php _e( 'Shipment Type', 'wpc-shipment-consoldation' ); ?></legend>
                <input type="radio" name="wpc_shipment_type" class="post-format" id="post-format-0" value="standard" <?php checked( 'standard', $wpc_shipment_type ); ?>>
                <label for="post-format-0" class="post-format-icon post-format-standard"><?php _e( 'Standard', 'wpc-shipment-consoldation' ); ?></label><br>
                <input type="radio" name="wpc_shipment_type" class="post-format" id="post-format-aside" value="consolidation" <?php checked( 'consolidation', $wpc_shipment_type ); ?> > <label for="post-format-aside" class="post-format-aside"> <img id="consolidate-icon" src="<?php echo PQ_SHIPMENT_CONSOLIDATION_URL.'admin/assets/images/consolidation-gray.png'; ?>" alt="Consolidation"> <?php _e( 'Consolidation', 'wpc-shipment-consoldation' ); ?></label>
            </fieldset>
        </div>
        <script>
        jQuery(document).ready(function($){
            var shipment_type = '<?php echo $wpc_shipment_type; ?>';
            var imgLight      = '<?php echo PQ_SHIPMENT_CONSOLIDATION_URL.'admin/assets/images/consolidation-light.png'; ?>';
            var imgGray       = '<?php echo PQ_SHIPMENT_CONSOLIDATION_URL.'admin/assets/images/consolidation-gray.png'; ?>';
            if( shipment_type == 'standard' ){
                $('#wpcshcon-store-order-box-id').css({'display':'block'});
                $('#wpcsh-consolidate-order-box-id').css({'display':'none'});
                $('#wpcsh-consolidate-box-id').css({'display':'none'});
                $('.wpcshcon-shipment-type img#consolidate-icon').attr('src', imgGray );
            }else if( shipment_type == 'consolidation' ){
                $('#wpcshcon-store-order-box-id').css({'display':'none'});
                $('#wpcsh-consolidate-order-box-id').css({'display':'block'});
                $('#wpcsh-consolidate-box-id').css({'display':'block'});
                $('.wpcshcon-shipment-type img#consolidate-icon').attr('src', imgLight );
            }
            $( 'input[name="wpc_shipment_type"]' ).on('click',function(e){
                var shipment_type = $(this).val();
                if( shipment_type == 'consolidation' ){
                    $('#wpcshcon-store-order-box-id').css({'display':'none'});
                    $('#wpcsh-consolidate-order-box-id').css({'display':'block'});
                    $('#wpcsh-consolidate-box-id').css({'display':'block'});
                    $('.wpcshcon-shipment-type img#consolidate-icon').attr('src', imgLight );
                }else{
                    $('#wpcshcon-store-order-box-id').css({'display':'block'});
                    $('#wpcsh-consolidate-order-box-id').css({'display':'none'});
                    $('#wpcsh-consolidate-box-id').css({'display':'none'});
                    $('.wpcshcon-shipment-type img#consolidate-icon').attr('src', imgGray );
                }
            });
        });
        </script>
        <?php
    }
    function mail_notification_display_callback( $post ){
        if( $post->post_status != 'publish' ){
            return;
        }
        $shipment_type = get_post_meta( $post->ID, 'wpc_shipment_type', true );
        $shipment_type = ( $shipment_type ) ? $shipment_type : 'standard' ;
        $package_list       = !empty( get_post_meta( $post->ID, 'wpc-multiple-package', true) ) ? maybe_unserialize( get_post_meta( $post->ID, 'wpc-multiple-package', true) ) : array();
        $item_notification = maybe_unserialize( get_post_meta( $post->ID, 'wpcshcon_item_notification', true ) );
        $has_package       = 1;
        if( $shipment_type == 'standard' && ( count($package_list) == 1 && (int)$package_list[0]['wpc-pm-qty'] <= 0 ) ){
            $has_package = 0;
        }
        if( $shipment_type == 'consolidation' ){
            $consolidation_cost = wpcshcon_get_consolidation_cost( $post->ID );
            if( $consolidation_cost <= 0 ){
                $has_package = 0;
            }
        }
        ?>
        <div class="misc-pub-section item-email-notification">
            <span class="dashicons dashicons-email-alt"></span> <a id="send-item-email-notification" href="#" data-postid="<?php echo $post->ID; ?>" data-package="<?php echo $has_package; ?>" data-type="<?php echo $shipment_type; ?>" ><?php _e( 'Send Notification', 'wpc-shipment-consoldation' ); ?></a>
        </div>
        <div class="misc-pub-section item-email-notes" style="border-top: 1px solid #757575;border-bottom: 1px solid #757575;">
        <?php
        $counter = 0;
        if( !empty( $item_notification ) ){
            ?><ul class="item-notification-list"><?php
                foreach ( array_reverse( $item_notification ) as $notification ) {
                    if( $counter == 6 ){
                        break;
                    }
                    ?><li><?php echo  $notification[1].' '.$notification[2].' on '.$notification[0]; ?></li><?php
                    $counter++;
                }
            ?></ul><?php
        }else{
            echo __('No sent email records found!');
        }
        ?>
        </div>
        <?php
        
    }
    function sender_display_callback( $post ){
		$shipment_id = $post->ID;
        require_once( PQ_SHIPMENT_CONSOLIDATION_PATH.'/admin/templates/shipment-meta.tpl.php' );
    }
    function order_display_callback( $post ){
        $consolidation = get_the_title( $post->ID );
        $orderID = wpcshcon_get_consolidation_order( $post->ID );
        if( $orderID ){
            $order 		= wc_get_order( $orderID );
            $order_data = $order->get_data();
            $created_via = $order->get_created_via();
            $order_status = ucfirst( $order_data['status'] );
            $payment_type = $order_data['payment_method_title'];
            $order_url 	= admin_url('post.php?post='.$orderID.'&action=edit');
            ?>
            <h4 style="border-bottom: 1px solid #757575; padding-bottom: 6px;"><?php _e( 'Order', 'wpc-shipment-consoldation' ); ?> <a href="<?php echo $order_url; ?>">#<?php echo $orderID; ?></a> <?php _e( 'details', 'wpc-shipment-consoldation' ); ?></h4>
            <p ><?php _e( 'Order Status', 'wpc-shipment-consoldation' ); ?>: <?php echo $order_status; ?><br/>
	        <?php _e( 'Payment via', 'wpc-shipment-consoldation' ); ?> <?php echo $payment_type; ?></p>
            <?php
        }else{
            ?><h4 style="border-bottom: 1px solid #757575; padding-bottom: 6px;"><?php _e( 'No Order for this Consolidation.', 'wpc-shipment-consoldation' ); ?></h4><?php
        }
    }
    function shipments_display_callback( $post ) {
		$shipment_id = $post->ID;
        wp_nonce_field( 'wpcshcon_metabox_action', 'wpcshcon_metabox_nonce' );
        require_once( PQ_SHIPMENT_CONSOLIDATION_PATH.'/admin/templates/consolidated-shipment-meta.tpl.php' );
    }
    function consolidate_shipment_callback(){
        $current_screen = get_current_screen();
        if( $current_screen->base == 'post' && $current_screen->post_type == 'wpcargo_shipment' ){
            $shipments = wpcshcon_get_ready_shipment();
            ?>
            <div id="wpshcon-consolidate-shipment" class="modal">
                <div class="modal-content">
                    <span class="close"><span class="dashicons dashicons-no-alt"></span></span>
                    <h1 class="shipment-top-title"><span><?php _e( 'Shipment List', 'wpc-shipment-consoldation' ); ?></span> <button id="add-shipment" class="button" data-id="<?php echo $post->ID; ?>" ><?php _e( 'Add Shipment', 'wpc-shipment-consoldation' ); ?></button><span class="spinner"></span></h1>
                    <div id="shipment-container">
                        <?php
                        if( !empty( $shipments ) ){
                            ?><ul id="shipment-list"><?php
                            foreach( $shipments as $shipment ){
                                $shipment_title = get_the_title($shipment);
                                $receiver       = get_post_meta( $shipment, 'registered_shipper', true );
                                ?>
                                <li><input type="checkbox" name="consolidateShipment[]" value="<?php echo $shipment; ?>"><?php echo $shipment_title; ?> - <?php echo wpcshcon_get_user_fullname($receiver); ?></li>
                                <?php
                            }
                            ?></ul><?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    function save_shipping_package_meta_boxes( $post_id ){
        $wpc_shipment_type = get_post_meta( $post_id, 'wpc_shipment_type', true );
        if( !empty( wpcshcon_shipping_methods() ) ){
            foreach ( wpcshcon_shipping_methods() as $method ) {
                $method_meta        = wpcshcon_text_to_meta( $method );
                $cost_method_meta   = 'cost_'.$method_meta;
                $meta_value         = '';
                $meta_cost          = '';
                if( array_key_exists( $method_meta, $_POST )){
                    $meta_value         = $_POST[$method_meta];
                }
                if( array_key_exists( $cost_method_meta, $_POST )){
                    $meta_cost        = $_POST[$cost_method_meta];
                }
                update_post_meta( $post_id, $method_meta, $meta_value );
                update_post_meta( $post_id, $cost_method_meta, $meta_cost );
            } 
        }
        if( !empty( wpcshcon_package_options() ) ){
            foreach ( wpcshcon_package_options() as $option ) {
                $option_meta        = wpcshcon_text_to_meta( $option );
                $option_value         = '';
                if( array_key_exists( $option_meta, $_POST )){
                    $option_value         = $_POST[$option_meta];
                }
                update_post_meta( $post_id, $option_meta, $option_value );
            } 
        }
        // Check is Consolidation status update
        // Check if shipment is Consolidated
        if( isset( $_POST['wpcargo_status'] ) && !empty( $_POST['wpcargo_status'] ) && 'consolidation' == $wpc_shipment_type ){
            $shipments = maybe_unserialize( get_post_meta( $post_id, 'wpcshcon_shipments', true ) );
            if( !empty( $shipments ) && is_array( $shipments ) ){
                foreach ($shipments as $shipment ) {
                    update_post_meta( $shipment, 'wpcargo_status', sanitize_text_field( $_POST['wpcargo_status'] ) );
                }
            }
        }
    }
}
new WPC_Shipment_Consolidation_Post;