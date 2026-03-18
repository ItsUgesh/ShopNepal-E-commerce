<?php include( WPC_INVOICE_PATH.'includes/invoice-print-css.php' ); ?>
<div id="wpcinvoice-print">
    <table cellspacing="0" cellpadding="0" style="width: 100%">
        <?php do_action( 'wpcinvoice_before_company_info', $shipmentDetails, $invoice_options, $str_find, $str_replace ); ?>
        <tr>
            <td class="no-padding section">
                <table style="width: 100%">
                    <tr>
                        <?php do_action('wpcinvoice_comapany_info', $shipmentDetails, $invoice_options, $str_find,  $str_replace ); ?>
                        <?php do_action('wpcinvoice_invoicing_info', $shipmentDetails, $invoice_options, $str_find,  $str_replace ); ?>
                    </tr>
                </table>
            </td>
        </tr>
        <?php do_action( 'wpcinvoice_before_shipper_info', $shipmentDetails, $invoice_options, $str_find, $str_replace ); ?>
        <tr>
            <td class="no-padding section">
                <table style="width: 100%;">
                    <tr>  
                        <?php do_action( 'wpcinvoice_shipper_info', $shipmentDetails, $invoice_options, $str_find, $str_replace ); ?>
                        <?php do_action( 'wpcinvoice_receiver_info', $shipmentDetails, $invoice_options, $str_find, $str_replace ); ?>
                    </tr>
                </table>
            </td>
        </tr>
        <?php do_action( 'wpcinvoice_before_package_info', $shipmentDetails, $invoice_options, $str_find, $str_replace ); ?>
        <tr>
            <td class="no-padding section">
                <?php do_action('wpcinvoice_before_package_info', $shipmentDetails, $invoice_options, $str_find, $str_replace ); ?>
                <?php do_action('wpcinvoice_package_info', $shipmentDetails, $invoice_options, $str_find, $str_replace ); ?>
                <?php do_action('wpcinvoice_after_package_info', $shipmentDetails, $invoice_options, $str_find, $str_replace ); ?>
            </td>
        </tr>
        <?php do_action( 'wpcinvoice_before_comments_info', $shipmentDetails, $invoice_options, $str_find, $str_replace ); ?>
        <tr>
            <td class="no-padding section">
                <table style="width: 100%">
                    <tr>
                        <?php do_action( 'wpcinvoice_comments_info', $shipmentDetails, $invoice_options, $str_find, $str_replace ); ?>
                        <?php do_action( 'wpcinvoice_total_info', $shipmentDetails, $invoice_options, $str_find, $str_replace ); ?>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>