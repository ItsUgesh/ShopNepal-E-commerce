<?php
/*
 * Plugin Name: WPCargo Shipment Consolidation Add on
 * Plugin URI: http://wptaskforce.com/
 * Description: This plugin will help you consolidate client shipments. Available shortcodes [wpcshcon_dashboard order="orderPageID" book="addressBookPageID" wooorder="woocommerceAccountPageID"], [wpcshcon_consolidate_shipments dashboard="dashboardPageID"] and [wpcshcon_address_book dashboard="dashboardPageID"]
 * Author: <a href="http://www.wptaskforce.com/">WPTaskForce</a>
 * Text Domain: wpc-shipment-consoldation
 * Domain Path: /languages
 * Version: 5.0.4
 */
if(!defined('ABSPATH')){
 exit; //Exit if accessed directly
}
define('PQ_SHIPMENT_CONSOLIDATION_VERSION', "5.0.4");
define('PQ_SHIPMENT_CONSOLIDATION_TEXTDOMAIN', "wpc-shipment-consoldation");
define('PQ_SHIPMENT_CONSOLIDATION_FILE', __FILE__);
define('PQ_SHIPMENT_CONSOLIDATION_HOME_URL', home_url());
define('PQ_SHIPMENT_CONSOLIDATION_URL', plugin_dir_url( __FILE__ ) );
define('PQ_SHIPMENT_CONSOLIDATION_PATH', plugin_dir_path( __FILE__ ) );
define('PQ_SHIPMENT_CONSOLIDATION_PLUGIN_DIR', plugin_dir_path( __DIR__ ) );
define('PQ_SHIPMENT_CONSOLIDATION_BASENAME', plugin_basename( __FILE__ ) );

//* Storage days allowance */
define('PQ_SHIPMENT_CONSOLIDATION_STORAGE', 50 );

//** Include file */
require_once( PQ_SHIPMENT_CONSOLIDATION_PATH.'/admin/includes/functions.php' );
require_once( PQ_SHIPMENT_CONSOLIDATION_PATH.'/admin/includes/hooks.php' );
require_once( PQ_SHIPMENT_CONSOLIDATION_PATH.'/admin/admin.php' );
//** Load Plugin text domain
add_action( 'plugins_loaded', 'wpcsh_consolidation_load_textdomain' );
function wpcsh_consolidation_load_textdomain() {
	load_plugin_textdomain( 'wpc-shipment-consoldation', false, '/wpcargo-shipment-consolidation-addon/languages' );
}
register_activation_hook(  __FILE__, 'add_wpcargo_default_status' );
function add_wpcargo_default_status(){
	$options = get_option('wpcargo_option_settings');
	$default_status = array_map('trim', explode( ',', $options['settings_shipment_status'] ) );
	$additional_status = array(
		__( 'Ready for Consolidation', 'wpc-shipment-consoldation' ),
		__( 'Consolidated', 'wpc-shipment-consoldation' ),
	);
	$new_status = array_merge( $default_status, $additional_status );
	$options['settings_shipment_status'] = join( ',', $new_status );
	update_option( 'wpcargo_option_settings', $options );
}