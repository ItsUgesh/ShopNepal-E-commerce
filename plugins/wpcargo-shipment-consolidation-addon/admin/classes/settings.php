<?php
if(!defined('ABSPATH')){
    exit; //Exit if accessed directly
}
class WPC_Shipment_Consolidation_Settings{
    function __construct(){
        //** Package Quotation settings hook
        add_action( 'admin_menu', array( $this, 'settings_submenu_page' ) );
        //** Add plugin Setting navigation to the WPCargo settings
        add_action( 'wpc_add_settings_nav', array( $this, 'settings_navigation_callback') );
        //** Register settings option group */
        add_action( 'admin_init', array($this, 'set_register_setting') );
    }
    function settings_submenu_page() {

		add_submenu_page(
			'wpcargo-settings',
			__( 'Consolidation Settings', 'wpc-shipment-consoldation' ),
			__( 'Consolidation Settings', 'wpc-shipment-consoldation' ),
			'manage_options',
			'admin.php?page=wpcshcon-settings'
		);
		add_submenu_page(
			NULL,
			__( 'Consolidation Settings', 'wpc-shipment-consoldation' ),
			__( 'Consolidation Settings', 'wpc-shipment-consoldation' ),
			'manage_options',
			'wpcshcon-settings',
			array( $this, 'settings_submenu_page_callback' )
		);
    }
    function set_register_setting(){
        register_setting( 'wpcshcon_option_group', 'wpcshcon_ready_status' );
        register_setting( 'wpcshcon_option_group', 'wpcshcon_default_status' );
        register_setting( 'wpcshcon_option_group', 'wpcshcon_order_submit_status' );
        register_setting( 'wpcshcon_option_group', 'wpcshcon_dashboard_status' );
        register_setting( 'wpcshcon_option_group', 'billing_wooorder_integ_option' );
        register_setting( 'wpcshcon_option_group', 'wpcshcon_default_receiver' );
        register_setting( 'wpcshcon_option_group', 'wpcshcon_product' );
        register_setting( 'wpcshcon_option_group', 'wpcshcon_prefix' );
        register_setting( 'wpcshcon_option_group', 'wpcshcon_package_options' );
        register_setting( 'wpcshcon_option_group', 'wpcshcon_shipping_methods' );
        register_setting( 'wpcshcon_option_group', 'wpcshcon_email_subject' );
        register_setting( 'wpcshcon_option_group', 'wpcshcon_email_body' );
        register_setting( 'wpcshcon_option_group', 'wpcshcon_email_footer' );
        register_setting( 'wpcshcon_option_group', 'wpcshcon_warehouse_name' );
        register_setting( 'wpcshcon_option_group', 'wpcshcon_warehouse_number' );
        register_setting( 'wpcshcon_option_group', 'wpcshcon_warehouse_address' );
        register_setting( 'wpcshcon_option_group', 'wpcshcon_warehouse_email' );
        register_setting( 'wpcshcon_option_group', 'wpcshcon_whs_assign_field' );
        
	}
    function settings_navigation_callback(){
        $view = $_GET['page'];
		?>
        <a class="nav-tab <?php echo ( $view == 'wpcshcon-settings') ? 'nav-tab-active' : '' ;  ?>" href="<?php echo admin_url().'admin.php?page=wpcshcon-settings'; ?>" ><?php _e('Consolidation Settings', 'wpc-shipment-consoldation' ); ?></a>
        <?php
    }
    function settings_submenu_page_callback(){
        global $wpcargo, $WPCCF_Fields;
        $wpcshcon_default_status        = wpcshcon_get_option( 'wpcshcon_default_status', __( 'Processing', 'wpc-shipment-consoldation' ) );
        $wpcshcon_order_submit_status   = wpcshcon_get_option( 'wpcshcon_order_submit_status', __( 'On Hold', 'wpc-shipment-consoldation' ) );
        $wpcshcon_dashboard_status      = get_option('wpcshcon_dashboard_status') ? get_option('wpcshcon_dashboard_status') : array();
        $billing_wooorder_integ_option  = get_option( 'billing_wooorder_integ_option' );
        $wpcshcon_ready_status          = get_option( 'wpcshcon_ready_status' );
        $wpcshcon_default_receiver      = get_option( 'wpcshcon_default_receiver' );
        $wpcshcon_product               = get_option( 'wpcshcon_product' );
        $wpcshcon_prefix                = get_option( 'wpcshcon_prefix' );
        $wpcshcon_package_options       = get_option( 'wpcshcon_package_options' );
        $wpcshcon_shipping_methods      = get_option( 'wpcshcon_shipping_methods' );
        $wpcshcon_email_subject         = get_option( 'wpcshcon_email_subject' );
        $wpcshcon_email_body            = get_option( 'wpcshcon_email_body' );
        $wpcshcon_email_footer          = get_option( 'wpcshcon_email_footer' );
        $wpcshcon_warehouse_name        = get_option( 'wpcshcon_warehouse_name' );
        $wpcshcon_warehouse_number     	= get_option( 'wpcshcon_warehouse_number' );
        $wpcshcon_warehouse_address     = get_option( 'wpcshcon_warehouse_address' );
        $wpcshcon_warehouse_email       = get_option( 'wpcshcon_warehouse_email' );
        $wpcshcon_whs_assign_field      = wpcshcon_whs_assign_field();
        $receiver_fields 		        = $WPCCF_Fields->get_custom_fields( 'receiver_info' );
        $shipper_fields 		        = $WPCCF_Fields->get_custom_fields( 'shipper_info' );
        $products                       = wpcshcon_get_products();
        ?>
        <div id="wpcshcon-settings" class="wrap">
            <h1><?php _e( 'Consolidation Settings', 'wpc-shipment-consoldation' ); ?></h1>
            <?php require_once( PQ_SHIPMENT_CONSOLIDATION_PATH.'../wpcargo/admin/templates/admin-navigation.tpl.php'); ?>
            <div class="postbox">
                <div class="inside">
                    <?php require_once( PQ_SHIPMENT_CONSOLIDATION_PATH.'/admin/templates/settings.tpl.php' ); ?>
                </div>
            </div>

        </div>
        <?php
    }
}
new WPC_Shipment_Consolidation_Settings;