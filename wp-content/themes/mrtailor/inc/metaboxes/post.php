<?php

// CREATE
add_action( 'add_meta_boxes', 'mrtailor_post_options_meta_box_add' );
function mrtailor_post_options_meta_box_add() {
    add_meta_box( 'post_options_meta_box', 'Post Options', 'mrtailor_post_options_meta_box_content', 'post', 'side', 'high' );
}

function mrtailor_post_options_meta_box_content() {
    // $post is already set, and contains an object: the WordPress post
    global $post;
    $values = get_post_custom( $post->ID );
	$check = isset($values['post_featured_image_meta_box_check']) ? esc_attr($values['post_featured_image_meta_box_check'][0]) : 'on';
    ?>

    <div class="components-panel__row">
        <div class="components-base-control">
            <div class="components-base-control__field">
                <span class="components-checkbox-control__input-container">
                    <input type="checkbox" id="post_featured_image_meta_box_check" name="post_featured_image_meta_box_check" class="components-checkbox-control__input" <?php checked( $check, 'on' ); ?> />
                </span>
                <label for="post_featured_image_meta_box_check">Show Featured Image</label>
            </div>
        </div>
    </div>

    <?php
	// We'll use this nonce field later on when saving.
    wp_nonce_field( 'post_options_meta_box', 'post_options_meta_box_nonce' );
}

// SAVE
add_action( 'save_post', 'mrtailor_post_options_meta_box_save' );
function mrtailor_post_options_meta_box_save($post_id) {
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['post_options_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['post_options_meta_box_nonce'], 'post_options_meta_box' ) ) return;

    // if our current user can't edit this post, bail
    if ( !current_user_can( 'edit_post', $post_id ) ) return;

	$chk = isset($_POST['post_featured_image_meta_box_check']) ? 'on' : 'off';
    update_post_meta( $post_id, 'post_featured_image_meta_box_check', $chk );
}