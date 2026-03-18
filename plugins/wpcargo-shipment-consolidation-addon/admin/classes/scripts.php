<?php
if(!defined('ABSPATH')){
    exit; //Exit if accessed directly
}
class WPC_Shipment_Consolidation_Scripts{
    function __construct(){
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
    }
    function admin_scripts(){
        // Styles
        wp_register_style( 'wpcshcon_admin_css', PQ_SHIPMENT_CONSOLIDATION_URL . 'admin/assets/css/admin-style.css', false, PQ_SHIPMENT_CONSOLIDATION_VERSION );
        wp_enqueue_style( 'wpcshcon_admin_css' );
        wp_enqueue_style( 'dashicons' );
        // Scripts
        wp_enqueue_script( 'jquery' );
        wp_register_script( 'wpcshcon_admin_js', PQ_SHIPMENT_CONSOLIDATION_URL . 'admin/assets/js/admin-script.js', array ('jquery'), PQ_SHIPMENT_CONSOLIDATION_VERSION );
        wp_enqueue_script( 'wpcshcon_admin_js' );
        $translation_array = array(
            'ajaxurl'                   => admin_url( 'admin-ajax.php' ),
            'standardItemErrorMessage'  => __('No item(s) found in the this shipment, Please check and click publish to save the Item(s).', 'wpc-shipment-consoldation'),
            'consolidationItemErrorMessage' => __('No cost is set for this shipment, Please check and click publish to save the cost.', 'wpc-shipment-consoldation'),
            'messageSent' => __('Message sent!', 'wpc-shipment-consoldation'),
            'messageFailed' => __('Message Failed! Reload and try again.', 'wpc-shipment-consoldation'),
        );
        wp_localize_script( 'wpcshcon_admin_js', 'wpshconAjaxHandler', $translation_array );
    }
    function frontend_scripts(){
        global $post;

		$wpcfm_dashboard = false;
		if( !empty( $post ) && get_page_template_slug( $post->ID ) == 'dashboard.php' ){
			$wpcfm_dashboard = true;
		}

			// Styles
		wp_register_style( 'wpcshcon_frontend_css', PQ_SHIPMENT_CONSOLIDATION_URL . 'assets/css/wpcshcon-style.css', false, PQ_SHIPMENT_CONSOLIDATION_VERSION );
		wp_register_style( 'wpcshcon_select2_css', PQ_SHIPMENT_CONSOLIDATION_URL . 'assets/css/select2.min.css', false, PQ_SHIPMENT_CONSOLIDATION_VERSION );
		wp_register_style( 'wpcshcon_croppie_css', PQ_SHIPMENT_CONSOLIDATION_URL . 'assets/css/croppie.css', false, PQ_SHIPMENT_CONSOLIDATION_VERSION );
		wp_enqueue_style( 'wpcshcon_frontend_css' );
		wp_enqueue_style( 'wpcshcon_select2_css' );
		wp_enqueue_style( 'wpcshcon_croppie_css' );
		wp_enqueue_style( 'dashicons' );
			//  Scripts
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_register_script( 'wpcshcon_scripts_js', PQ_SHIPMENT_CONSOLIDATION_URL . 'assets/js/wpcshcon-scripts.js', array ('jquery'), PQ_SHIPMENT_CONSOLIDATION_VERSION );
		wp_register_script( 'wpcshcon_select2_js', PQ_SHIPMENT_CONSOLIDATION_URL . 'assets/js/select2.min.js', array ('jquery'), PQ_SHIPMENT_CONSOLIDATION_VERSION );
		wp_register_script( 'wpcshcon_croppie_js', PQ_SHIPMENT_CONSOLIDATION_URL . 'assets/js/croppie.js', array ('jquery'), PQ_SHIPMENT_CONSOLIDATION_VERSION );
		wp_enqueue_script( 'wpcshcon_select2_js' );
		wp_enqueue_script( 'wpcshcon_scripts_js' );
		if( !$wpcfm_dashboard ){
			wp_enqueue_script( 'wpcshcon_croppie_js' );
		}
		$translation_array = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'formSubmitError' 		=> __( 'Something went wrong during process, Please try agian later.', 'wpc-shipment-consoldation' ),
			'deleteMessage' 		=> __( 'Are you sure you want to Delete Address?', 'wpc-shipment-consoldation' ),
			'timeFormat'			=> get_option( 'time_format' ),
			'profile_nonce'         => wp_create_nonce('update_profile_nonce'),
			'avatar_placeholder'    => PQ_SHIPMENT_CONSOLIDATION_URL.'assets/images/wpc-avatar.png',
			'wpcfm_dashboard'       => $wpcfm_dashboard,
			'standardItemErrorMessage'  => __('No item(s) found in the this shipment, Please check and click publish to save the Item(s).', 'wpc-shipment-consoldation'),
            'consolidationItemErrorMessage' => __('No cost is set for this shipment, Please check and click publish to save the cost.', 'wpc-shipment-consoldation'),
            'messageSent' => __('Message sent!', 'wpc-shipment-consoldation'),
            'messageFailed' => __('Message Failed! Reload and try again.', 'wpc-shipment-consoldation'),
		);
		wp_localize_script( 'wpcshcon_scripts_js', 'wpshconAjaxHandler', $translation_array );
			
        
		wp_register_script( 'wpcshcon_js', PQ_SHIPMENT_CONSOLIDATION_URL . 'admin/assets/js/admin-script.js', array ('jquery'), PQ_SHIPMENT_CONSOLIDATION_VERSION );
		wp_enqueue_script( 'wpcshcon_js' );
		$translation_array2 = array(
            'ajaxurl'                   => admin_url( 'admin-ajax.php' ),
            'standardItemErrorMessage'  => __('No item(s) found in the this shipment, Please check and click publish to save the Item(s).', 'wpc-shipment-consoldation'),
            'consolidationItemErrorMessage' => __('No cost is set for this shipment, Please check and click publish to save the cost.', 'wpc-shipment-consoldation'),
            'messageSent' => __('Message sent!', 'wpc-shipment-consoldation'),
            'messageFailed' => __('Message Failed! Reload and try again.', 'wpc-shipment-consoldation'),
        );
        wp_localize_script( 'wpcshcon_admin_js', 'wpshconAjaxHandler', $translation_array2 );
    }
}
new WPC_Shipment_Consolidation_Scripts;