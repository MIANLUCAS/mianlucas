<?php

//http://code.tutsplus.com/tutorials/how-to-create-custom-wordpress-writemeta-boxes--wp-20336



// CREATE

add_action( 'add_meta_boxes', 'page_options_meta_box_add' );

function page_options_meta_box_add()
{
    add_meta_box( 'page_options_meta_box', 'Page Options', 'page_options_meta_box_content', 'page', 'side', 'high' );
}

function page_options_meta_box_content()
{
    // $post is already set, and contains an object: the WordPress post
    global $post;
    $values = get_post_custom( $post->ID );
    $page_title_check = isset($values['page_title_meta_box_check']) ? esc_attr($values['page_title_meta_box_check'][0]) : 'off';
	$footer_check = isset($values['footer_meta_box_check']) ? esc_attr($values['footer_meta_box_check'][0]) : 'off';
	$selected = isset($values['page_header_transparency']) ? esc_attr( $values['page_header_transparency'][0]) : '';

    $page_template = get_post_meta($post->ID, '_wp_page_template', true);
    ?>

    <?php if ( ( 'default' == $page_template ) || ( '' == $page_template ) || ( 'page-narrow.php' == $page_template ) ) { ?>
        <div class="components-panel__row">
            <div class="components-base-control">
                <div class="components-base-control__field">
                    <span class="components-checkbox-control__input-container">
                        <input type="checkbox" id="page_title_meta_box_check" name="page_title_meta_box_check" class="components-checkbox-control__input" <?php checked( $page_title_check, 'on' ); ?> />
                    </span>
                    <label for="page_title_meta_box_check">Hide Page Title</label>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="components-panel__row">
        <div class="components-base-control">
            <div class="components-base-control__field">
                <span class="components-checkbox-control__input-container">
                    <input type="checkbox" id="footer_meta_box_check" name="footer_meta_box_check" class="components-checkbox-control__input" <?php checked( $footer_check, 'on' ); ?> />
                </span>
                <label for="footer_meta_box_check">Hide Footer</label>
            </div>
        </div>
    </div>

    <div class="components-panel__row">
        <div class="components-base-control header-transparency">
            <label for="page_header_transparency" class="components-base-control__label">Header Transparency</label>
            <div class="components-base-control__field">
                <select name="page_header_transparency" id="page_header_transparency" style="width:100%">
                    <option value="inherit" <?php selected( $selected, 'inherit' ); ?>>Inherit</option>
                    <option value="transparency_light" <?php selected( $selected, 'transparency_light' ); ?>>Light</option>
                    <option value="transparency_dark" <?php selected( $selected, 'transparency_dark' ); ?>>Dark</option>
                    <option value="no_transparency" <?php selected( $selected, 'no_transparency' ); ?>>No Transparency</option>
                </select>
            </div>
        </div>
    </div>

    <?php

	// We'll use this nonce field later on when saving.
    wp_nonce_field( 'page_options_meta_box', 'page_options_meta_box_nonce' );
}




// SAVE

add_action( 'save_post', 'page_options_meta_box_save' );

function page_options_meta_box_save($post_id)
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['page_options_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['page_options_meta_box_nonce'], 'page_options_meta_box' ) ) return;

    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post', $post_id ) ) return;

    $page_title_chk = isset($_POST['page_title_meta_box_check']) ? 'on' : 'off';
    update_post_meta( $post_id, 'page_title_meta_box_check', $page_title_chk );

	$footer_chk = isset($_POST['footer_meta_box_check']) ? 'on' : 'off';
    update_post_meta( $post_id, 'footer_meta_box_check', $footer_chk );

	if( isset( $_POST['page_header_transparency'] ) )
    update_post_meta( $post_id, 'page_header_transparency', esc_attr( $_POST['page_header_transparency'] ) );
}
