<?php
	class FlipTimerGlobals {
	
		// Enqueues front-end javascripts
		public static function enqueueScripts() {			
			// Load CSS
			wp_enqueue_style( 'fliptimer', plugins_url( 'fliptimer/fliptimer.css', FLIPTIMER_FILE ) );
			
			// Load JS
			wp_enqueue_script( 'fliptimer', plugins_url( 'fliptimer/jquery.fliptimer.js', FLIPTIMER_FILE ), array( "jquery" ) );
		}
		
		// Display options
		public static function displayOptions( $args ) {
			$uniqueId = FlipTimerGlobals::uniqueId();
			$options = FlipTimerGlobals::getOptions();
			
			// Container
			echo '<div id="' . $uniqueId . '"></div>';

			// Shortcode arguments overwrite admin options
			if ( isset( $args ) && is_array( $args ) ) {
				foreach ( $args as $key => $value ) {
					$options[$key] = $value;
				}
			}
			
			// Script
			$js = '
					jQuery(document).ready(function() {
						jQuery("#' . $uniqueId . '").flipTimer({
							date:"' . $options[ "date" ] . '",
							timeZone:' . $options[ "timezone" ] . ',
							past:' . $options[ "past" ] . ',
							
							// Current date
							currentDate:' . ( $options[ "currentdate" ] == "true" ? '"' . date( "Y/m/d H:i:s" ) . '"' : "false" ) . ',

							// The number of days to be shown
							dayTextNumber:"' . $options[ "daytextnumber" ] . '",

							// Show-Hide Day, Hour, Minute, Seconds
							showDay:' . $options[ "showday" ] . ',
							showHour:' . $options[ "showhour" ] . ',
							showMinute:' . $options[ "showminute" ] . ',
							showSecond:' . $options[ "showsecond" ] . ',

							// Texts
							dayText:"' . $options[ "daytext" ] . '",
							hourText:"' . $options[ "hourtext" ] . '",
							minuteText:"' . $options[ "minutetext" ] . '",
							secondText:"' . $options[ "secondtext" ] . '",

							// Flip style
							bgColor:"' . $options[ "bgcolor" ] . '",
							dividerColor:"' . $options[ "dividercolor" ] . '",
							digitColor:"' . $options[ "digitcolor" ] . '",
							textColor:"' . $options[ "textcolor" ] . '",
							borderRadius:' . $options[ "borderradius" ] . ',
							boxShadow:' . $options[ "boxshadow" ] . ',

							// Multi color
							multiColor:' . $options[ "multicolor" ] . ',

							// Auto reset
							autoReset:' . $options[ "autoreset" ] . ',
							resetInterval:' . $options[ "resetinterval" ] . ',
							
							// Expire
							expireType:"' . $options[ "expiretype" ] . '", //message, hide, redirect
							message:"' . $options[ "message" ] . '",
							redirect:"' . $options[ "redirect" ] . '"
						});
				   });
			';
			
			wp_register_script( $uniqueId, '', FLIPTIMER_FILE, false, true );
			wp_enqueue_script( $uniqueId );
			wp_add_inline_script( $uniqueId, $js );
		}
	
		// Get options
		public static function getOptions() {
			$options = array();
			
			$options[ "date" ] = get_option( "fliptimer-date" );
			$options[ "timezone" ] = get_option( "fliptimer-timeZone" );
			$options[ "past" ] = get_option( "fliptimer-past" );
			
			// Current date
			$options[ "currentdate" ] = get_option( "fliptimer-currentdate" );
			
			// The number of days to be shown
			$options[ "daytextnumber" ] = get_option( "fliptimer-dayTextNumber" );
			
			// Show-Hide Day, Hour, Minute, Second
			$options[ "showday" ] = get_option( "fliptimer-showDay" );
			$options[ "showhour" ] = get_option( "fliptimer-showHour" );
			$options[ "showminute" ] = get_option( "fliptimer-showMinute" );
			$options[ "showsecond" ] = get_option( "fliptimer-showSecond" );
			
			// Texts
			$options[ "daytext" ] = get_option( "fliptimer-dayText" );
			$options[ "hourtext" ] = get_option( "fliptimer-hourText" );
			$options[ "minutetext" ] = get_option( "fliptimer-minuteText" );
			$options[ "secondtext" ] = get_option( "fliptimer-secondText" );
			
			// Flip style
			$options[ "bgcolor" ] = get_option( "fliptimer-bgColor" );
			$options[ "dividercolor"]  = get_option( "fliptimer-dividerColor" );
			$options[ "digitcolor" ] = get_option( "fliptimer-digitColor" );
			$options[ "textcolor" ] = get_option( "fliptimer-textColor" );
			$options[ "borderradius" ] = get_option( "fliptimer-borderRadius") ;
			$options[ "boxshadow" ] = get_option( "fliptimer-boxShadow" );
			
			// Multi color
			$options[ "multicolor" ] = get_option( "fliptimer-multiColor" );
			
			// Auto reset
			$options[ "autoreset" ] = get_option( "fliptimer-autoReset" );
			$options[ "resetinterval" ] = get_option( "fliptimer-resetInterval" );
			
			// Expire
			$options[ "expiretype" ] = get_option( "fliptimer-expireType" );
			$options[ "message" ] = get_option( "fliptimer-message" );
			$options[ "redirect" ] = get_option( "fliptimer-redirect" );
			
			return $options;
		}
	
		// Update options
		public static function updateOptions() {
			update_option( "fliptimer-date", $_POST[ "date" ] );
			update_option( "fliptimer-timeZone", $_POST[ "timeZone" ] );		
			update_option( "fliptimer-past", isset( $_POST[ "past" ] ) ? "true" : "false" );
			
			// Current date
			update_option( "fliptimer-currentdate", isset( $_POST[ "currentdate" ] ) ? "true" : "false" );
			
			// The number of days to be shown
			update_option( "fliptimer-dayTextNumber", $_POST[ "dayTextNumber" ] );
			
			// Show-Hide Day, Hour, Minute, Second
			update_option( "fliptimer-showDay", isset( $_POST[ "showDay" ] ) ? "true" : "false" );
			update_option( "fliptimer-showHour", isset( $_POST[ "showHour" ] ) ? "true" : "false" );
			update_option( "fliptimer-showMinute", isset( $_POST[ "showMinute" ] ) ? "true" : "false" );
			update_option( "fliptimer-showSecond", isset( $_POST[ "showSecond" ] ) ? "true" : "false" );
			
			// Texts
			update_option( "fliptimer-dayText", $_POST[ "dayText" ] );
			update_option( "fliptimer-hourText", $_POST[ "hourText" ] );
			update_option( "fliptimer-minuteText", $_POST[ "minuteText" ] );
			update_option( "fliptimer-secondText", $_POST[ "secondText" ] );
			
			// Flip style
			update_option( "fliptimer-bgColor", $_POST[ "bgColor" ] );
			update_option( "fliptimer-dividerColor", $_POST[ "dividerColor" ] );
			update_option( "fliptimer-digitColor", $_POST[ "digitColor" ] );
			update_option( "fliptimer-textColor", $_POST[ "textColor" ] );
			update_option( "fliptimer-borderRadius", $_POST[ "borderRadius" ] );
			update_option( "fliptimer-boxShadow", isset( $_POST[ "boxShadow" ] ) ? "true" : "false" );
			
			// Multi color
			update_option( "fliptimer-multiColor", isset( $_POST[ "multiColor" ] ) ? "true" : "false" );
			
			// Auto reset
			update_option( "fliptimer-autoReset", isset( $_POST[ "autoReset" ] ) ? "true" : "false" );
			update_option( "fliptimer-resetInterval", $_POST[ "resetInterval" ] );
			
			// Expire
			update_option( "fliptimer-expireType", stripslashes( $_POST[ "expireType" ] ) );
			update_option( "fliptimer-message", stripslashes( $_POST[ "message" ] ) );
			update_option( "fliptimer-redirect", stripslashes( $_POST[ "redirect" ] ) );
		}
	
		// Create unique id
		public static function uniqueId() {
			$chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$chars_length = strlen( $chars );
			$i = 0;    
			$str = "";
			
			srand( (double)microtime() * 1000000 );	
			
			while ( $i < 8 ) {
				$num = rand() % $chars_length;
				$tmp = substr( $chars, $num, 1 );
				$str .= $tmp;
				$i++;
			}
			  
			return "fliptimer_" . $str;
		}
	
	}
?>