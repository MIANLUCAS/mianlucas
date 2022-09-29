jQuery(function($) {

	"use strict";

    // Only show the "remove image" button when needed
    if ( ! $('#product_cat_header_id').val() )
        $('.remove_header_image_button').hide();

    // Uploading files
    var header_file_frame;

    $(document).on( 'click', '.upload_header_button', function( event ) {

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( header_file_frame ) {
            header_file_frame.open();
            return;
        }

        // Create the media frame.
        header_file_frame = wp.media.frames.downloadable_file = wp.media({
            title: 'Choose an image',
            button: {
                text: 'Use image',
            },
            multiple: false
        });

        // When an image is selected, run a callback.
        header_file_frame.on( 'select', function() {
            var attachment = header_file_frame.state().get('selection').first().toJSON();
            $('#product_cat_header_id').val( attachment.id );
            $('#product_cat_header').css('background-image', 'url('+attachment.url+')').css('width', '').css('height', '');
            $('.remove_header_image_button').show();
        });

        // Finally, open the modal.
        header_file_frame.open();

    });

    $(document).on( 'click', '.remove_header_image_button', function( event ) {
        $('#product_cat_header').css('width', '0').css('height', '0');
        $('#product_cat_header_id').val('');
        $('.remove_header_image_button').hide();
        return false;
    });

    if ($('#product_cat_thumbnail_id').val() == 0) {
         $('.remove_image_button').hide();
     }

    if ($('#product_cat_header_id').val() == 0) {
         $('.remove_header_image_button').hide();
     }
});
