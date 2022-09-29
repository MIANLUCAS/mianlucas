<?php
	
	/* Styles
	=============================================================== */
	
	function nm_child_theme_styles() {
        // Enqueue child theme styles
        wp_enqueue_style( 'nm-child-theme', get_stylesheet_directory_uri() . '/style.css' );
	}
	add_action( 'wp_enqueue_scripts', 'nm_child_theme_styles', 1000 ); // Note: Use priority "1000" to include the stylesheet after the parent theme stylesheets
	

/**
 1---------------------------------------------------Font Awesome Kit Setup
 * 
 * This will add your Font Awesome Kit to the front-end, the admin back-end,
 * and the login screen area.
 */
if (! function_exists('fa_custom_setup_kit') ) {
  function fa_custom_setup_kit($kit_url = 'https://kit.fontawesome.com/9ce743b782.js') {
    foreach ( [ 'wp_enqueue_scripts', 'admin_enqueue_scripts', 'login_enqueue_scripts' ] as $action ) {
      add_action(
        $action,
        function () use ( $kit_url ) {
          wp_enqueue_script( 'font-awesome-kit', $kit_url, [], null );
        }
      );
    }
  }
}
    

/**
 2---------------------------------------------------Hacer que un productos no se pueda comprar
 * 
 * En su lugar te sale un formulario de contacto que el cliente debe rellenar siempre que seleciones la opcion
 * and the login screen area.
 */
	
add_action( 'woocommerce_product_options_general_product_data', 'woo_add_custom_general_fields' );
function woo_add_custom_general_fields() {

  global $woocommerce, $post;
  
  echo '<div class="options_group">';

	woocommerce_wp_checkbox( 
	array( 
		'id'            => '_no_free_shipping_checkbox', 
		'wrapper_class' => '', 
		'label'         => __('Exclude From Free Shipping', 'woocommerce' ), 
		'description'   => __( 'Dis-allow Free Shipping', 'woocommerce' ) 
		)
	);
	//woocommerce_wp_checkbox( 
	//array( 
	//	'id'            => '_discontinued_product_checkbox', 
	//	'wrapper_class' => '', 
	//	'label'         => __('Discontinued Product', 'woocommerce' ), 
	//	'description'   => __( 'No longer in Production', 'woocommerce' ) 
	//	)
	//);
	woocommerce_wp_checkbox( 
	array( 
		'id'            => '_custom_product', 
		'wrapper_class' => '', 
		'label'         => __('Contact form instead of add to cart button', 'woocommerce' ), 
		'description'   => __( 'Product can be customized', 'woocommerce' ) 
		)
	);	
	
	echo '</div>';
}

// Save Fields
add_action( 'woocommerce_process_product_meta', 'woo_add_custom_general_fields_save' );

function woo_add_custom_general_fields_save( $post_id ){
	
	// Checkbox
	$woocommerce_checkbox = isset( $_POST['_no_free_shipping_checkbox'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, '_no_free_shipping_checkbox', $woocommerce_checkbox );
	
	//$woocommerce_product_checkbox = isset( $_POST['_discontinued_product_checkbox'] ) ? 'yes' : 'no';
	//update_post_meta( $post_id, '_discontinued_product_checkbox', $woocommerce_product_checkbox );
	
	$woocommerce_custom_product_checkbox = isset( $_POST['_custom_product'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, '_custom_product', $woocommerce_custom_product_checkbox );	

}
add_filter('woocommerce_is_purchasable', 'ar_custom_is_purchasable', 10, 2);

function ar_custom_is_purchasable( $is_purchasable, $object ) {

    // get the product id first
	$product_id = $object->get_id();
	
	// get the product meta data
	$is_custom = get_post_meta($product_id, '_custom_product', true);
	
	if ($is_custom == "yes"){
		return false;
	}
	else {
		return true;
	}

}
add_action( 'woocommerce_single_product_summary', 'ar_custom_product_cta', 60);

function ar_custom_product_cta()
{
	global $product;
    // get the product id first
	$product_id = $product->get_id();
	
	// get the product meta data
	$is_custom = get_post_meta($product_id, '_custom_product', true);
	
	// Show the Form if product is Custom
	if ($is_custom == "yes"){
		echo '<div class="formulario-4"><h5> Fill in the form below and we will inform you about the product and how to buy it! </h5></div>';
		echo do_shortcode( '<div class="formulario-4-id">[gravityforms id=4 title=false]</div>' );
	}
}



/**
 3---------------------------------------------------?????????????????????????????????????????????????????
 * 
 * En su lugar te sale un formulario de contacto que el cliente debe rellenar siempre que seleciones la opcion
 * and the login screen area.
 */
 
 /* Cambiar imagen que aparece en wp-login */
function mala_change_image_logo() { 
?>
  <style type="text/css">
    #login h1 a, .login h1 a {
    background-image: url(http://savoy.miguelangellucas.es/wp-content/uploads/2020/01/bird-example-1.png);
      background-repeat: no-repeat;
      background-size: cover;
      height: 84px;
      width: 84px;

    }
  </style>
<?php 
}

add_action( 'login_enqueue_scripts', 'mala_change_image_logo' );

/**
 4---------------------------------------------------?????????????????????????????????????????????????????
 * 
 * En su lugar te sale un formulario de contacto que el cliente debe rellenar siempre que seleciones la opcion
 * and the login screen area.
 */
 

 
 /* MENSAJE 1 */

add_action( 'woocommerce_review_order_before_submit', 'add_privacy_2_checkbox', 9 );
function add_privacy_2_checkbox() {
woocommerce_form_field( 'privacy_policy', array(
'type' => 'checkbox',
'class' => array('form-row privacy'),
'label_class' => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
'input_class' => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
'required' => true,
'label' => '<span class="politica-privacidad-checkboxes">Ich stimme zu, dass meine persönlichen Daten an den Versanddienstleister sowie die Nutricia GmbH übermittelt werden.</span>',
));
}
add_action( 'woocommerce_checkout_process', 'privacy_checkbox_error_message_2' );
function privacy_checkbox_error_message_2() {
if ( ! (int) isset( $_POST['privacy_policy'] ) ) {
wc_add_notice( __( 'Bitte bestätigen Sie, dass Ihre persönlichen Daten an den Versanddienstleister weitergegeben werden.' ), 'error' );
}
} 

 /* MENSAJE 2 */
add_action( 'woocommerce_review_order_before_submit', 'add_privacy_checkbox', 9 );
function add_privacy_checkbox() {
woocommerce_form_field( 'privacy_policy', array(
'type' => 'checkbox',
'class' => array('form-row privacy'),
'label_class' => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
'input_class' => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
'required' => true,
'label' => '<span class="politica-privacidad-checkboxes">Ich habe die <a href="#">Allgemeinen Geschäftsbedingungen</a>, die <a href="#">Datenschutzbestimmung</a> und die <a href="#">Widerrufsbelehrung</a> gelesen und akzeptiert.</span>',
));
}
add_action( 'woocommerce_checkout_process', 'privacy_checkbox_error_message' );
function privacy_checkbox_error_message() {
if ( ! (int) isset( $_POST['privacy_policy'] ) ) {
wc_add_notice( __( 'Bitte stimmen Sie den Allgemeinen Geschäftsbedingungen, den Datenschutzbedingungen sowie der Widerrufsbelehrung zu.' ), 'error' );
}
}


/**
* WooCommerce: show all product attributes, separated by comma, on cart page
*/

/**
    * WooCommerce: show all product attributes listed below each item on Cart page
    */
 
//add_filter( 'woocommerce_cart_item_name', 'showing_sku_in_cart_items', 99, 3 );
//function showing_sku_in_cart_items( $item_name, $cart_item, $cart_item_key  ) {

//    $product = $cart_item['data'];
 
//    $sku = $product->get_sku();


//    if(empty($sku)) return $item_name;

   
//    $item_name .= '<small class="product-sku">' . __( "Artikelnummer: ", "woocommerce") . $sku . '</small>';

 //   return $item_name;
//}

function skyverge_shop_display_skus() {

	global $product;
     
        if ( $product->get_sku() ) {
		echo '<div class="product-meta">SKU: ' . $product->get_sku() . '</div>';
		//echo '<div class="product-meta">Preis: ' . $product->get_price() . ' €</div>';
		 //echo '<div class="product-meta">Gewicht: ' . $product->get_weight() . ' Kg</div>';
		
}
}
//add_action( 'woocommerce_after_shop_loop_item', 'skyverge_shop_display_skus', 9 );
add_action( 'woocommerce_after_shop_loop_item_title', 'skyverge_shop_display_skus', 9 );


