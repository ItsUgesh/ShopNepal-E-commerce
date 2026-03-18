<?php
date_default_timezone_set('Asia/Kathmandu');
// Get today's date
$date = date('d M Y');
// Get the current time
$time = date('H:i');


$barcode_height     = !empty(wpcargo_print_barcode_sizes()['waybill']['height']) ? wpcargo_print_barcode_sizes()['waybill']['height'] : 80;
$barcode_width      = !empty(wpcargo_print_barcode_sizes()['waybill']['height']) ? wpcargo_print_barcode_sizes()['waybill']['width'] : 250;
$copies = [
    'account-copy' => esc_html__('Accounts Copy', 'wpcargo'),
    'consignee-copy' => esc_html__('Consignee Copy', 'wpcargo'),
    'shippers-copy' => esc_html__('Shippers Copy', 'wpcargo'),
];
$copies = apply_filters('wpcargo_print_label_template_copies', $copies);
if (empty($copies)) {
    return false;
}
?>
<?php do_action('wpc_label_before_header_information', $shipmentDetails['shipmentID']); ?>
<?php foreach ($copies as $key => $label) : ?>
    <table>
        <tr>
            <td rowspan="3" class="align-center">
                <?php echo wp_kses($shipmentDetails['logo'], 'post'); ?>
            </td>
            <td rowspan="3" class="align-center" style="font-weight: 900; font-size: 32px; padding-left: 230px; padding-right: 230px;">
                Your Location
            </td>
            <td rowspan="3" class="align-center">
                <img id="admin-waybill-barcode" class="waybill-barcode" style="float: none !important; margin: 0 !important; width: <?php echo absint($barcode_width) . 'px'; ?>!important; height: <?php echo absint($barcode_height) . 'px'; ?>!important; " src="<?php echo esc_html($shipmentDetails['barcode']); ?>" alt="<?php echo esc_html(get_the_title($shipmentDetails['shipmentID'])); ?>" />
                <p style="margin:0;padding:0;font-weight: bold;"><?php echo esc_html(get_the_title($shipmentDetails['shipmentID'])); ?></p>
                <?php do_action('wpc_label_header_barcode_information', $shipmentDetails['shipmentID']); ?>
                <span class="copy-label"><?php echo esc_html($label); ?></span>
            </td>
        </tr>
    </table>
    <div id="<?php echo $key; ?>" class="copy-section">
        <table class="shipment-header-table" cellpadding="0" cellspacing="0" style="border: 1px solid #000;width: 100%;margin:0;padding:0;">
            <tr><!--1st row-->

                <td style="font-weight: bold; font-size: 16px;"><?php esc_html_e('Account Number', 'wpcargo'); ?> :
                    <span class="data" style="font-weight: bold; font-size: 14px;"><?php echo esc_html(wpcargo_get_postmeta($shipmentDetails['shipmentID'], 'wpcargo_account_number')); ?></span>
                </td>
                <td colspan="6" class="align-center">
                    <span class="wpcargo-label" style="font-weight: bold; font-size: 16px;">
                        <?php echo esc_html_e('Destination: ', 'wpcargo'); ?>
                    </span>
                    <span class="wpcargo-label" style="font-weight: bold; font-size: 13px;">
                        <?php echo esc_html(wpcargo_get_postmeta($shipmentDetails['shipmentID'], 'wpcargo_origin_field')); ?>
                        <?php esc_html_e(' to ', 'wpcargo'); ?>
                    </span>
                    <span class="data" style="font-weight: bold; font-size: 13px;">
                        <?php echo esc_html(wpcargo_get_postmeta($shipmentDetails['shipmentID'], 'wpcargo_destination')); ?>
                    </span>
                </td>

                <td colspan="4" class="align-center">
                    <span class="wpcargo-label" style="font-weight: bold; font-size: 16px;"><?php esc_html_e('Number of pieces: ', 'wpcargo'); ?></span>: <span class="data" style="font-weight: bold; font-size: 14px;"><?php echo esc_html(wpcargo_get_postmeta($shipmentDetails['shipmentID'], 'wpcargo_packages')); ?></span>
                </td>
            </tr>
            <tr> <!--2st row-->
                <td style="font-weight: bold; font-size: 16px;"><?php esc_html_e('Shipper', 'wpcargo'); ?> :
                    <span class="data" style="font-weight: bold; font-size: 14px;"><?php echo esc_html(wpcargo_get_postmeta($shipmentDetails['shipmentID'], 'wpcargo_shipper_name')); ?></span>
                </td>
                </td>
                <td rowspan="3" colspan="4" class="align-center">
                <td style="font-weight: bold; font-size: 16px;"><?php esc_html_e('Consignee', 'wpcargo'); ?> :
                    <span class="data" style="font-weight: bold; font-size: 14px;"><?php echo esc_html(wpcargo_get_postmeta($shipmentDetails['shipmentID'], 'wpcargo_receiver_name')); ?></span>
                </td>
                <td colspan="3" class="align-center">
                    <span class="wpcargo-label" style="font-weight: bold; font-size: 14px;"><?php esc_html_e('Net weight', 'wpcargo'); ?></span>: <span class="data" style="font-weight: bold; font-size: 14px;"><?php echo esc_html(wpcargo_get_postmeta($shipmentDetails['shipmentID'], 'wpcargo_weight')); ?></span>
                </td>
            </tr>
            <tr><!--3rd row-->
                <td colspan="2">

                    <span class="label" style="font-weight: bold; font-size: 14px;">Address :</span>
                    <span class="data" style="font-weight: bold; font-size: 14px; margin-bottom: 15px; display: block;"><?php echo esc_html(wpcargo_get_postmeta($shipmentDetails['shipmentID'], 'wpcargo_shipper_address')); ?></span><br>

                    <span class="label" style="font-weight: bold; font-size: 14px;">Phone Number :</span>
                    <span class="data" style="font-weight: bold; font-size: 14px;"><?php echo esc_html(wpcargo_get_postmeta($shipmentDetails['shipmentID'], 'wpcargo_shipper_phone')); ?></span><br>

                    <span class="label" style="font-weight: bold; font-size: 14px;">Email :</span>
                    <span class="data" style="font-weight: bold; font-size: 14px;"><?php echo esc_html(wpcargo_get_postmeta($shipmentDetails['shipmentID'], 'wpcargo_shipper_email')); ?></span><br>

                </td>
                <td colspan="2">
                    <span class="label" style="font-weight: bold; font-size: 14px;">Address :</span>
                    <span class="data" style="font-weight: bold; font-size: 14px; margin-bottom: 15px; display: block;"><?php echo esc_html(wpcargo_get_postmeta($shipmentDetails['shipmentID'], 'wpcargo_receiver_address')); ?></span></br>

                    <span class="label" style="font-weight: bold; font-size: 14px;">Phone Number :</span>
                    <span class="data" style="font-weight: bold; font-size: 14px;"><?php echo esc_html(wpcargo_get_postmeta($shipmentDetails['shipmentID'], 'wpcargo_receiver_phone')); ?></span></br>

                    <span class="label" style="font-weight: bold; font-size: 14px;">Email :</span>
                    <span class="data" style="font-weight: bold; font-size: 14px;"><?php echo esc_html(wpcargo_get_postmeta($shipmentDetails['shipmentID'], 'wpcargo_receiver_email')); ?></span></br>
                </td>
                <!-- <td>
                    <span class="wpcargo-label" style="font-weight: bold; font-size: 14px;"><?php esc_html_e('Origin :', 'wpcargo'); ?></span> <span class="data" style="font-weight: bold; font-size: 14px;"><?php echo esc_html(wpcargo_get_postmeta($shipmentDetails['shipmentID'], 'wpcargo_origin_field')); ?></span>
                </td>
-->
                <td colspan="4" rowspan="1" class="align-center" style="padding: 0;">
                    <span class="wpcargo-label" style="font-weight: bold; font-size: 14px;"><?php esc_html_e('Chargable weight', 'wpcargo'); ?></span>: <span class="data" style="font-weight: bold; font-size: 14px;"><?php echo esc_html(wpcargo_get_postmeta($shipmentDetails['shipmentID'], 'wpcargo_chargable_weight')); ?></span>
                    <span style="border-top: 1px solid #000;display: block;  width: calc(100% + 1px); margin: 0 -1px; margin-top: 20px;margin-bottom: 15px;"></span>
                    <span class="wpcargo-label" style="font-weight: bold; font-size: 14px;"><?php esc_html_e('Type of Shipment', 'wpcargo'); ?></span>: <span class="data" style="font-weight: bold; font-size: 14px;"><?php echo esc_html(wpcargo_get_postmeta($shipmentDetails['shipmentID'], 'wpcargo_type_of_shipment')); ?></span>
                </td>
            </tr>
            <tr><!--4th row-->
                <table id="admin-print-invoice" style="width:100%;">
                    <?php do_action('wpcfe_end_invoice_section', $shipmentDetails); ?>
                </table>
                <?php do_action('wpcfe_after_invoice_content', $shipmentDetails); ?>
            </tr>
            <tr style="padding-bottom:20px;"><!--5th row-->
                <strong style="font-weight: bold; font-size: 14px; ">Authority signature: <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <?php echo "Date: $date" ?>
                </strong>

            </tr>


        </table>

    </div>

<?php endforeach; ?>
<?php do_action('wpc_label_footer_information', $shipmentDetails['shipmentID']); ?>
</div>