jQuery(document).ready(function() {
	
	//Save button click event handler
    jQuery("#fliptimer_general .fliptimer_save").on('click', function(e) {
		e.preventDefault();
		jQuery("#fliptimer_form").submit();
    });
	
	//Generate button click event handler
    jQuery("#fliptimer_general .fliptimer_generate").on('click', function(e) {
		e.preventDefault();
	      
        var shortcode = "[fliptimer";
            
		jQuery("#fliptimer_general form input, #fliptimer_general form select").each(function() {
			var input = jQuery(this);
			var type = input.attr("type");
			var name = input.attr("name");
			var val = "";
			
			if (name=="task") return;
			
			//Default value
			var val_default = input.data("val");
			
			if (val_default!=undefined) {
				val_default = val_default.toString();
			}
			
			//Get value
			if (type!="checkbox") {
				val = input.val();
			} else {
				val = input.prop("checked") ? "true" : "false";
			}
			
			//Add to shortcode
			if (val!=val_default) {
				shortcode += " " + name + '="' + val + '"';
			}
		});
		
		shortcode += "]";
		
		jQuery("#fliptimer_general #theshortcode textarea").val(shortcode);
		jQuery("#fliptimer_general #theshortcode").show();
    });
    
	//Date picker
    jQuery('#fliptimer_general .date').datepicker({
        dateFormat:"yy/mm/dd 00:00:00"
    });
    
	//Color picker
    jQuery('#fliptimer_general .color_picker').each(function() {	
	    var inputID = jQuery(this).attr('id');
		
	    jQuery(this).ColorPicker({
	    	color:jQuery(this).val(),
	    	onShow:function (colpkr) {
	    		jQuery(colpkr).fadeIn(200);
	    		return false;
	    	},
	    	onHide:function (colpkr) {
	    		jQuery(colpkr).fadeOut(200);
	    		return false;
	    	},
	    	onChange:function (hsb, hex, rgb, el) {
	    		jQuery('#'+inputID).val('#'+hex);
	    		jQuery('#'+inputID+'_bg').css('backgroundColor', '#'+hex);
	    	}
	    });	
	});
	
	//Select
	jQuery("#fliptimer_general #expireType").on('change', function() {
		var val = this.value;
		
		jQuery('#fliptimer_general .expire-type').hide();
		jQuery('#fliptimer_general .expire-type.expire-'+val).show();
    });
	
    //iPhone style checkbox
	jQuery('.iphone_checkboxes').iphoneStyle({
		checkedLabel:'YES',
		uncheckedLabel:'NO'
	});
});