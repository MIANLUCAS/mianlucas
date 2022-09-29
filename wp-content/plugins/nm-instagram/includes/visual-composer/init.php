<?php
    
	/* Visual Composer: Initialize
	================================================== */
	
	if ( class_exists( 'WPBakeryVisualComposerAbstract' ) ) {
		
		// Include external shortcodes
		function nm_instagram_vc_register_shortcodes() {
			include( NM_INSTAGRAM_INC_DIR . '/visual-composer/shortcodes/instagram.php' );
		}
		add_action( 'init', 'nm_instagram_vc_register_shortcodes' );
		
		
		if ( is_admin() ) {
			// Include external elements
			function nm_instagram_vc_register_elements() {
				include( NM_INSTAGRAM_INC_DIR . '/visual-composer/elements/instagram.php' );
			}
            add_action( 'init', 'nm_instagram_vc_register_elements' );
		}
		
	}
