jQuery(document).ready(function($) {

    Funcs = {

        loyalProgSettingsToggle : function() {

            var $checkbox = $( 'input.checkbox-toggle[type="checkbox"]' );

            $checkbox.on( 'change' , function() {

                var $this   = $(this),
                    $target = $( '#' + $this.data( 'toggle' ) ),
                    $parent = $target.closest( 'tr' );

                if ( $this.prop( 'checked' ) ) {

                    $target.prop( "disabled" , false );
                    $target.find( "input,select" ).prop( "disabled" , false );
                    $parent.show();

                } else {

                    $target.prop( "disabled" , true );
                    $target.find( "input,select" ).prop( "disabled" , true );
                    $parent.hide();
                }
            } );

            $checkbox.trigger( 'change' );
        },

        showNoticeOnSettingsFallback : function() {
            
            if ( ! $('.notice.acfw-admin-notice').is(':visible') )
                $('.acfw-settings-notice.acfw-admin-notice').show();
            else
                $('.acfw-settings-notice.acfw-admin-notice').remove();
        }

    };

    Funcs.loyalProgSettingsToggle();
    Funcs.showNoticeOnSettingsFallback();

});