<?php global $nm_theme_options; ?>
<!DOCTYPE html>

<html <?php language_attributes(); ?> class="<?php echo esc_attr( 'footer-sticky-' . $nm_theme_options['footer_sticky'] ); ?>">
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        
        <link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
        
		<?php wp_head(); ?>
    </head>
    
	<body <?php body_class(); ?>>
	<div class="schnelllinks-icons-home">

    <div class="schnelllinks-icon-home">
        <i class="fa fa-envelope" style="font-size:30px;color:#fff;" aria-hidden="true"></i>
        <p style="vertical-align:middle;"><a href="/contact/" title="Kontakt per E-Mail" class="link-icono-fijo customize-unpreviewable">Kontakt per E-Mail</a>
        </p>
        
    </div>

    <div class="schnelllinks-icon-home">
        <i class="fa fa-phone" style="font-size:30px;color:#fff;" aria-hidden="true"></i>
        <p style="vertical-align:middle;">+49 000000000
        </p>
        
    </div>


    <!-- div class="schnelllinks-icon-home">
        <img src="https://www.pepepool.com/pruebas/brite/html/images/telefono-img.svg" alt="" title="">
        <table class="">
            <tbody>
                <tr>
                    <td>JUJUJU BENISSA</td>
                    <td>&nbsp;&nbsp;&nbsp; +34 96573 3336</td>
                </tr>
                <tr>
                    <td>JUJUJU TORREVIEJA</td>
                    <td>&nbsp;&nbsp;&nbsp; +34 96573 3336</td>
                </tr>
                <tr>
                    <td>Contabilidad</td>
                    <td>&nbsp;&nbsp;&nbsp; +34 96573 3336-12</td>
                </tr>
                <tr>
                    <td>Almacen</td>
                    <td>&nbsp;&nbsp;&nbsp; +34 96573 3336-10</td>
                </tr>
                <tr>
                    <td>Pedidos</td>
                    <td>&nbsp;&nbsp;&nbsp; +34 96573 3336-20</td>
                </tr>
            </tbody>
        </table>
    </div -->

</div>
        <?php if ( $nm_theme_options['page_load_transition'] ) : ?>
        <div id="nm-page-load-overlay" class="nm-page-load-overlay"></div>
        <?php endif; ?>
        
        <div class="nm-page-overflow">
            <div class="nm-page-wrap">
                <?php
                    // Top bar
                    if ( $nm_theme_options['top_bar'] ) {
                        get_template_part( 'template-parts/header/header', 'top-bar' );
                    }
                ?>
                            
                <div class="nm-page-wrap-inner">
                    <?php
                        // Header
                        get_template_part( 'template-parts/header/header', 'content' );
                    ?>
