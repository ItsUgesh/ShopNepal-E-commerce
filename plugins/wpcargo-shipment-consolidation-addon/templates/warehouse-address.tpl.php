<div class="card bordered">
    <div class="card-header" id="headingOne">
        <h5 class="mb-0">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#wpcscho-warehouse-address"
            aria-expanded="true" aria-controls="wpcscho-warehouse-address">
            <?php _e('Warehouse Address', 'wpcargo-frontend-manager' ); ?>
        </button>
        </h5>
    </div>
    <div id="wpcscho-warehouse-address" class="collapse" aria-labelledby="headingOne" data-parent="#accordionAccountInformation">
        <div class="card-body">
            <p><strong ><?php _e('Address line 1', 'wpcargo-frontend-manager' ); ?></strong>:
                <span id="address_1" class="user-data">
                    <?php echo $warehouse_address['address_1']; ?>
                </span>
            </p>
            <p><strong ><?php _e('Address line 2', 'wpcargo-frontend-manager' ); ?></strong>:
                <span id="address_2" class="user-data">
                    <?php echo $consolidation_code; ?>
                </span>
            </p>
            <p><strong ><?php _e('City', 'wpcargo-frontend-manager' ); ?></strong>:
                <span id="city" class="user-data">
                    <?php echo $warehouse_address['city']; ?>
                </span>
            </p>
            <p><strong ><?php _e('Postcode / ZIP', 'wpcargo-frontend-manager' ); ?></strong>:
                <span id="postcode" class="user-data">
                    <?php echo $warehouse_address['postcode']; ?>
                </span>
            </p>
            <p><strong ><?php _e('Country', 'wpcargo-frontend-manager' ); ?></strong>:
                <span id="country" class="user-data">
                    <?php echo $warehouse_address['country']; ?>
                </span>
            </p>
            <p><strong ><?php _e('State / County', 'wpcargo-frontend-manager' ); ?></strong>:
                <span id="state" class="user-data">
                    <?php echo $warehouse_address['state']; ?>
                </span>
            </p>
            <p><strong ><?php _e('Phone', 'wpcargo-frontend-manager' ); ?></strong>:
                <span id="phone" class="user-data">
                    <?php echo $warehouse_address['phone']; ?>
                </span>
            </p>
        </div>
    </div>
</div>