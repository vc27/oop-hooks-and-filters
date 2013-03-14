<?php
/**
 * File Name remove-actions-and-filters.php
 * @package WordPress
 * @license GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @version 1.0
 * @updated 02.16.13
 **/
#################################################################################################### */






/**
 * Remove_WC_HooksAndFilters Class
 *
 * @version 1.0
 * @updated 02.16.13
 **/
$Remove_WC_HooksAndFilters = new Remove_WC_HooksAndFilters();
class Remove_WC_HooksAndFilters {
	
	
	
	
	
	
	/**
	 * __construct
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function __construct() {

		// hook method init
		add_action( 'init', array( &$this, 'init' ), 11 );

		// hook method admin_init
		add_action( 'admin_init', array( &$this, 'admin_init' ), 11 );

	} // end function __construct
	
	
	
	
	
	
	/**
	 * init
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 * @codex http://codex.wordpress.org/Plugin_API/Action_Reference/init
	 * 
	 * Description:
	 * Runs after WordPress has finished loading but before any headers are sent.
	 **/
	function init() {
		global $WC_HooksAndFilters;
        
		
		/**
		 * Register Styles and Scripts
		 **/
		
		// Style CSS
		wp_deregister_style( 'wc-default' );
		
		// Custom JS
		wp_deregister_script( 'wc-custom' );
		
		
		
		/**
		 * Enqueue Styles and Scripts
		 **/
		
		// CSS // wp_print_styles
		remove_action( 'wp_print_styles', array( $WC_HooksAndFilters, 'wp_print_styles' ) );

		// Javascripts // wp_enqueue_scripts // wp_print_scripts
		remove_action( 'wp_enqueue_scripts', array( $WC_HooksAndFilters, 'wp_enqueue_scripts' ) );
		
		
		
		/**
		 * General Filters
		 **/
		
		// Filter Post class
		remove_filter( 'post_class', array( $WC_HooksAndFilters, 'post_class' ) );
		
		// Filter Body class
		remove_filter( 'body_class', array( $WC_HooksAndFilters, 'body_class' ) ); 
		
		/**
		 * Filter The Content
		 *
		 * Note:
		 * a priority of 9 was used so the filter would be ran
         * before all default wp core filters.
		 **/
		remove_filter( 'the_content', array( $WC_HooksAndFilters, 'the_content' ), 9 );
		
		
		/**
		 * General Actions
		 **/
		
		// Filter Body class
		remove_action( 'wp_head', array( $WC_HooksAndFilters, 'favicon' ) );
		
		
		/**
		 * Ajax Filters
		 **/
		 
		 // For logged in users
 		remove_action( "wp_ajax_site-ajax", array( $WC_HooksAndFilters, 'do_ajax' ) );
		
	} // end function init
	
	
	
	
	
	
	/**
	 * admin_init
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 * @codex http://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
	 * 
	 * Description:
	 * admin_init is triggered before any other hook when a user access the admin area.
	 * This hook doesn't provide any parameters, so it can only be used to callback a 
	 * specified function.
	 **/
	function admin_init() {
		
		/**
		 * Admin Register Styles and Scripts
		 **/
		
		// admin css
		wp_deregister_style( 'wc-admin-default' );
		
		// admin js
		wp_deregister_script( 'wc-admin-custom' );
		
		
		/**
		 * Shortcodes
		 **/
		
		// Add Shortcode recognition to text widget
		remove_filter( 'widget_text', 'do_shortcode' );
		
	} // end function admin_init
	
	
	
} // end class Remove_WC_HooksAndFilters