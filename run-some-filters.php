<?php
/**
 * File Name run-some-filters.php
 * @package WordPress
 * @license GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @version 1.0
 * @updated 02.16.13
 **/
#################################################################################################### */






/**
 * Filter_WC_HooksAndFilters Class
 *
 * @version 1.0
 * @updated 02.16.13
 **/
$Filter_WC_HooksAndFilters = new Filter_WC_HooksAndFilters();
class Filter_WC_HooksAndFilters {
	
	
	
	
	
	
	/**
	 * __construct
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function __construct() {
		
		// hook method init
		add_action( 'init', array( &$this, 'init' ) );
		
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
		
        // Filter Localize JS
		add_filter( 'wc-localize_script', array( &$this, 'localize_script' ) );
		
	} // end function init
	
	
	
	
	
	
	####################################################################################################
	/**
	 * Functionality
	 **/
	####################################################################################################
	
	
	
	
	
	
	/**
	 * localize_script
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function localize_script( $array ) {
	    global $wp_query;
	    
	    if ( $wp_query->post->post_type == 'post' AND is_single() )
	        $array['post_id'] = $wp_query->post->ID;
		
        return $array;

	} // end function localize_script
	
	
	
} // end class Filter_WC_HooksAndFilters