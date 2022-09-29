<?php
	/*
	Plugin Name: 	FlipTimer - jQuery Countdown Timer WordPress Plugin
	Plugin URI: 	https://codecanyon.net/item/fliptimer-jquery-countdown-timer-wordpress-plugin/21253343
	Description: 	jQuery framework used countdown-countup timer.
	Author: 		AthenaStudio
	Version: 		1.0
	Author URI: 	https://themeforest.net/user/athenastudio
	*/
	
	$currentFile = __FILE__;
	$currentFolder = dirname( $currentFile );
	
	defined( "FLIPTIMER_FILE") ? NULL : define( "FLIPTIMER_FILE", $currentFile );
	define( "FLIPTIMER_TEXTDOMAIN", "fliptimer" );
	
	// Include global Class
	require_once( $currentFolder . '/includes/fliptimer_globals.class.php' );
	
	// Load language
	load_plugin_textdomain( "fliptimer", false, $currentFolder . '/languages/' );


	/**********************
	   - Installation -
	**********************/
	
	// Install
	function flipTimerInstall() {
		require_once( dirname( __FILE__ ) . '/includes/fliptimer_params.class.php' );
	}
	
	register_activation_hook( $currentFile, "flipTimerInstall" );
	
	// Uninstall
	function flipTimerUninstall() {
		require_once( dirname( __FILE__ ) . '/uninstall.php' );
	}
	
	register_uninstall_hook( $currentFile, "flipTimerUninstall" );
	
	/*********************
		- Admin menu -
	*********************/
	
	// Register admin menu
	function registerFlipTimerAdminMenu() {
		// Add menu
		$page = 'fliptimer-wp/fliptimer_admin.php';
		add_menu_page( 'Flip Timer', 'Flip Timer', 'add_users', $page, '', plugins_url( "images/icon.svg", __FILE__ ) );	
		
		// Load CSS
		wp_enqueue_style( 'fliptimer-admin', plugins_url( 'css/admin.css', __FILE__ ) );
		wp_enqueue_style( 'fliptimer-colorpicker', plugins_url( 'js/colorpicker/css/colorpicker.css', __FILE__ ) );
		wp_enqueue_style( 'fliptimer-jqueryui', plugins_url( 'css/ui-lightness/jquery-ui-1.10.2.custom.min.css', __FILE__ ) );
		
		// Add fontello
		wp_enqueue_style( 'fliptimer-fontello', plugins_url( 'css/fontello.css', __FILE__ ) );
		
		// Load JS
		wp_enqueue_script( 'fliptimer-admin', plugins_url( 'js/admin.js', __FILE__ ), array( "jquery", "jquery-ui-datepicker" ));
		wp_enqueue_script( 'fliptimer-admin-colorpicker', plugins_url( 'js/colorpicker/js/colorpicker.js', __FILE__ ), array( "jquery" ) );
		wp_enqueue_script( 'fliptimer-admin-iphone-style-checkboxes', plugins_url( 'js/jquery.iphone-style-checkboxes.js', __FILE__ ), array( "jquery" ) );
	}
	
	add_action( 'admin_menu', 'registerFlipTimerAdminMenu' );
	
	/********************
		- Front End -
	********************/
	
	// [fliptimer] shortcode - displays front end
	function flipTimerCheckForShortcode() {
		FlipTimerGlobals::enqueueScripts();
	}
	
	if ( !is_admin() ) {
		add_action( 'wp', 'flipTimerCheckForShortcode' );
	}
	
	function flipTimerDisplay( $args ) {
		ob_start();
		
		FlipTimerGlobals::displayOptions( $args );
		
		return ob_get_clean();
	}
	
	add_shortcode( "fliptimer", "flipTimerDisplay" );
	
	
	
	