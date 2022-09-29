<?php
	add_option( "fliptimer-date", "" );
	add_option( "fliptimer-timeZone", "0" );
	add_option( "fliptimer-past", "false" );

	// Current date
	add_option( "fliptimer-currentdate", "false" );
	
	// The number of days to be shown
	add_option( "fliptimer-dayTextNumber", "2" );
	
	// Show-Hide Day, Hour, Minute, Second
	add_option( "fliptimer-showDay", "true" );
	add_option( "fliptimer-showHour", "true" );
	add_option( "fliptimer-showMinute", "true" );
	add_option( "fliptimer-showSecond", "true" );
	
	// Texts
	add_option( "fliptimer-dayText", "Days" );
	add_option( "fliptimer-hourText", "Hours" );
	add_option( "fliptimer-minuteText", "Minutes" );
	add_option( "fliptimer-secondText", "Seconds" );
	
	// Flip style
	add_option( "fliptimer-bgColor", "#333333" );
	add_option( "fliptimer-dividerColor", "#000000" );
	add_option( "fliptimer-digitColor", "#ffffff" );
	add_option( "fliptimer-textColor", "#666666" );
	add_option( "fliptimer-borderRadius", "6" );
	add_option( "fliptimer-boxShadow", "true" );
	
	// Multi color
	add_option( "fliptimer-multiColor", "false" );

	// Auto reset
	add_option( "fliptimer-autoReset", "false" );
	add_option( "fliptimer-resetInterval", "86400" );
	
	// Expire
	add_option( "fliptimer-expireType", "message" );
	add_option( "fliptimer-message", "Sorry, you are too late!" );
	add_option( "fliptimer-redirect", "" );
?>
