<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
function wpcinvoice_enqueue_scripts(){
    global $post, $wpcargo;
    $wpcargo_tax = ( $wpcargo->tax > 0) ? floatval( $wpcargo->tax ) / 100 : 0;
    if( !empty( $post ) ){
        $template = get_page_template_slug( $post->ID );
        if( $template == 'dashboard.php' || $post->ID == wpcinvoice_dashboard_page() ){
            // Register Styles
            wp_enqueue_style( 'wpcinvoice-styles', WPC_INVOICE_URL.'assets/css/styles.css', WPC_INVOICE_VERSION );
            // Register Scritps
            wp_register_script( 'wpcinvoice-scripts', WPC_INVOICE_URL . 'assets/js/scripts.js', array( 'jquery' ), WPC_INVOICE_VERSION, true );
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'wpcinvoice-scripts' );
            // Local translation
            $translation   = array(
                'ajaxurl'               => admin_url( 'admin-ajax.php' ),
                'noShipmentSelected'    => __('No shipment selected, Please select atleast one Shipment.', 'wpcargo-invoice'),
                'pageURL'               => get_the_permalink(),
                'wpcargo_tax'           => $wpcargo_tax,
                'waybillLabel'          => __('Waybill', 'wpcargo-invoice'),
                'cantPrint'             => __('Cannot print Waybill the following Invoices status not Paid', 'wpcargo-invoice'),
                'priceMetakey'          => wpcinvoice_unit_price_key(),
                'amountMetakey'         => wpcinvoice_unit_amount_key()
            );
            wp_localize_script( 'wpcinvoice-scripts', 'wpcinvoiceAjaxHandler', $translation );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'wpcinvoice_enqueue_scripts' );
function wpcinvoice_registered_styles( $styles ){
    $styles[] = 'wpcinvoice-styles';
    return $styles;
} 
function wpcinvoice_registered_scripts( $scripts ){
    $scripts[] = 'wpcinvoice-scripts';
    return $scripts;
}     