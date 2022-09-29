<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">    
    <div>
        <label class="screen-reader-text" for="s"><?php esc_html_e( 'Search for:', 'mr_tailor' ); ?></label>
        <input type="search" class="search-field" id="s" placeholder="<?php esc_attr_e( 'Search...', 'mr_tailor' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
        <input type="submit" class="search-submit" value="<?php esc_attr_e( 'Search', 'mr_tailor' ); ?>">
    </div>
</form>