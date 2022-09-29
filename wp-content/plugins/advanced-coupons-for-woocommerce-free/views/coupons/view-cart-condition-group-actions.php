<?php if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$condition_fields = apply_filters( 'acfw_cart_condition_field_options', array(
    'product-category'              => __( 'Product Category Exist In The Cart' , 'advanced-coupons-for-woocommerce-free' ),
    'customer-logged-in-status'     => __( 'Customer Logged In Status' , 'advanced-coupons-for-woocommerce-free' ),
    'customer-user-role'            => __( 'Allowed Customer User Role' , 'advanced-coupons-for-woocommerce-free' ),
    'disallowed-customer-user-role' => __( 'Disallowed Customer User Role' , 'advanced-coupons-for-woocommerce-free' ),
    'cart-quantity'                 => __( 'Cart Quantity' , 'advanced-coupons-for-woocommerce-free' ),
    'cart-subtotal'                 => __( 'Cart Subtotal' , 'advanced-coupons-for-woocommerce-free' )
) );

ob_start(); ?>

<div class="condition-group-actions">

    <div class="add-condition-form" style="display: none;">
        <select class="condition-types">
            <?php foreach ( $condition_fields as $value => $label ) : ?>
                <option value="<?php echo esc_attr( $value ); ?>"><?php echo sanitize_text_field( $label ); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="button" class="add-condition-field button-primary"><?php _e( 'Add' , 'advanced-coupons-for-woocommerce-free' ); ?></button>
        <button type="button" class="cancel-add-condition-field button"><?php _e( 'Cancel' , 'advanced-coupons-for-woocommerce-free' ); ?></button>
    </div>

    <button type="button" class="add-condition-trigger button">
        <i class="dashicons dashicons-plus"></i>
        <?php _e( "Add a New 'AND' Rule" , 'advanced-coupons-for-woocommerce-free' ); ?>
    </button>
</div>

<?php
return ob_get_clean();
