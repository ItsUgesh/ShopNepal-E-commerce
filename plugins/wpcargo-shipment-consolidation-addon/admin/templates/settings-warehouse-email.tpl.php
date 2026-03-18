<h3><label for="wpcshcon_email_subject"><?php _e('Warehouse Email Subject', 'wpc-shipment-consoldation'); ?></label></h3>
<p class="description"><?php _e('Note: This will overwrite default warehouse email subject', 'wpc-shipment-consoldation'); ?></p>
<p><input id="wpcshcon_email_subject" class="large-text" type="text" name="wpcshcon_email_subject" value="<?php echo $wpcshcon_email_subject; ?>"></p>
<h3><label for="wpcshcon_email_body"><?php _e('Warehouse Email Body', 'wpc-shipment-consoldation'); ?></label></h3>
<p class="description"><?php _e('Note: This will display a message before the warehouse information email template after registration order payment is completed. You can use HTML tags to layout your message.', 'wpc-shipment-consoldation'); ?></p>
<textarea name="wpcshcon_email_body" id="wpcshcon_email_body" class="large-text code" cols="60" rows="4"><?php echo $wpcshcon_email_body; ?></textarea>
<h3><label for="wpcshcon_email_footer"><?php _e('Warehouse Email Footer', 'wpc-shipment-consoldation'); ?></label></h3>
<p class="description"><?php _e('Note: This will override the default wpcargo email footer message. You can use HTML tags to layout your message.', 'wpc-shipment-consoldation'); ?></p>
<textarea name="wpcshcon_email_footer" id="wpcshcon_email_footer" class="large-text code" cols="60" rows="4"><?php echo $wpcshcon_email_footer; ?></textarea>