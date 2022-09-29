<?php
	// Logo URLs
    $logo_url = nm_logo_get_url();
    $alt_logo_url = nm_alt_logo_get_url();
?>
<div class="nm-header-logo">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
        <img src="<?php echo esc_url( $logo_url ); ?>" class="nm-logo" alt="<?php bloginfo( 'name' ); ?>">
        <?php if ( $alt_logo_url ) : ?>
        <img src="<?php echo esc_url( $alt_logo_url ); ?>" class="nm-alt-logo" alt="<?php bloginfo( 'name' ); ?>">
        <?php endif; ?>
    </a>
</div>