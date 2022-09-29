<?php

// [custom_add_to_cart]

add_filter( 'vc_autocomplete_custom_add_to_cart_id_callback', array( new Vc_Vendor_Woocommerce(), 'productIdAutocompleteSuggester' ), 10, 1 );

vc_map(array(
   "name"			=> "Add to Cart Button",
   "category"		=> "Mr. Tailor",
   "description"	=> "",
   "base"			=> "custom_add_to_cart",
   "class"			=> "",
      
   "params" 	=> array(
      
		array(
			"type"			=> "autocomplete",
			"holder"		=> "div",
			"class"			=> "hide_in_vc_editor",
			"admin_label" 	=> true,
			"heading"		=> "ID",
			"param_name"	=> "id",
			"value"			=> "",
		),
		
		/*array(
			"type"			=> "textfield",
			"holder"		=> "div",
			"class" 		=> "hide_in_vc_editor",
			"admin_label" 	=> true,
			"heading"		=> "SKU",
			"param_name"	=> "sku",
			"value"			=> "",
		),*/
		
		array(
			"type"			=> "dropdown",
			"holder"		=> "div",
			"class" 		=> "hide_in_vc_editor",
			"admin_label" 	=> true,
			"heading"		=> "Show Price",
			"param_name"	=> "show_price",
			"value"			=> array(
				"Yes"			=> "true",
				"No"			=> "false"
			),
		),
		
		array(
			"type"			=> "dropdown",
			"holder"		=> "div",
			"class" 		=> "hide_in_vc_editor",
			"admin_label" 	=> true,
			"heading"		=> "Size",
			"param_name"	=> "size",
			"value"			=> array(
				"Mini"			=> "vc_btn_xs",
				"Small"			=> "vc_btn_sm",
				"Normal"		=> "vc_btn_md",
				"Large"			=> "vc_btn_lg"
			),
		),
		
		array(
			"type"			=> "dropdown",
			"holder"		=> "div",
			"class" 		=> "hide_in_vc_editor",
			"admin_label" 	=> true,
			"heading"		=> "Style",
			"param_name"	=> "style",
			"value"			=> array(
				"Square"			=> "vc_btn_square",
				"Square Outlined"	=> "vc_btn_square_outlined",
				"Rounded"			=> "vc_btn_rounded",
				"Rounded Outlined"	=> "vc_btn_rounded_outlined",
				"Link"				=> "vc_btn_link",
			),
		),
		
		array(
			"type"			=> "dropdown",
			"holder"		=> "div",
			"class" 		=> "hide_in_vc_editor",
			"admin_label" 	=> true,
			"heading"		=> "Align",
			"param_name"	=> "align",
			"value"			=> array(
				"Left"			=> "left",
				"Center"		=> "center",
				"Right"			=> "right",
			),
		),
		
		array(
			"type"			=> "colorpicker",
			"holder"		=> "div",
			"class" 		=> "hide_in_vc_editor",
			"admin_label" 	=> true,
			"heading"		=> "Text Color",
			"param_name"	=> "text_color",
			"value"			=> "",
		),
		
		array(
			"type"			=> "colorpicker",
			"holder"		=> "div",
			"class" 		=> "hide_in_vc_editor",
			"admin_label" 	=> true,
			"heading"		=> "Background Color",
			"param_name"	=> "bg_color",
			"value"			=> "",
		),
   )
   
));