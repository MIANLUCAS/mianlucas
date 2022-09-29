<?php

// [product]
vc_map(array(
   "name" 			=> "Single Product",
   "category" 		=> "Mr. Tailor",
   "description"	=> "",
   "base" 			=> "product_mod",
   "class" 			=> "",
   
   'params' => array(
      array(
        'type' => 'autocomplete',
        'heading' => __( 'Select identificator', 'js_composer' ),
        'param_name' => 'id',
        'description' => __( 'Input product ID or product SKU or product title to see suggestions', 'js_composer' ),
      ),
      array(
        'type' => 'hidden',
        // This will not show on render, but will be used when defining value for autocomplete
        'param_name' => 'sku',
      ),
    ),
));

//Filters For autocomplete param:
  //For suggestion: vc_autocomplete_[shortcode_name]_[param_name]_callback
  add_filter( 'vc_autocomplete_product_mod_id_callback', array(
    'Vc_Vendor_Woocommerce',
    'productIdAutocompleteSuggester',
  ), 10, 1 ); // Get suggestion(find). Must return an array
  add_filter( 'vc_autocomplete_product_mod_id_render', array(
    'Vc_Vendor_Woocommerce',
    'productIdAutocompleteRender',
  ), 10, 1 ); // Render exact product. Must return an array (label,value)
  //For param: ID default value filter
  add_filter( 'vc_form_fields_render_field_product_mod_id_param_value', array(
    'Vc_Vendor_Woocommerce',
    'productIdDefaultValue',
  ), 10, 4 ); // Defines default value for param if not provided. Takes from other param value.