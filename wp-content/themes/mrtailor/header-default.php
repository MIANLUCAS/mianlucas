<header id="masthead" class="site-header <?php echo ( '0' === MrTailor_Opt::getOption( 'header_layout' ) ) ? 'header-default' : 'header-centered'; ?>" role="banner">
    <div class="row">
        <div class="large-12 columns">
            <div class="site-header-wrapper">

                <div class="mobile-menu-button">
                    <span class="mobile-menu-text"></span>
                </div>

                <div class="site-branding">
                    <div class="site-logo-link">
                        <?php mrtailor_get_logo(); ?>
                    </div>

                    <div class="site-logo-alt-link">
                        <?php mrtailor_get_alt_logo(); ?>
                    </div>
                </div>

                <div id="site-menu">
                    <?php echo mrtailor_get_header_menu(); ?>

                    <div class="site-tools">
                        <?php echo mrtailor_get_header_tool_icons(); ?>
                    </div>

                </div>

            </div>
        </div>
    </div>
</header>
