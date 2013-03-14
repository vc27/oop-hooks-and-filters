<?php
/*
Plugin Name: OOP Actions & Filters
Version: 1.3
Updated: 02.16.13
Description: OOP Actions and filters example plugin for WordCamp
Author: Randy Hicks
Author URI: http://visualcoma.com
License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

Note: Please note that this is NOT an example of a plugin. The purpose of this file is to present
example code and usage of various actions and filters. If you have question or comments please feel
free to email randy@visualcoma.com
*/
#################################################################################################### */






/**
 * Remove Actions and filters
 * 
 * Description:
 * Call in the file that perform removal
 * of actions and filters
 **/
// require_once( 'remove-actions-and-filters.php' );






/**
 * Run Some Filters
 * 
 * Description:
 * Call in the file that perform filters
 * on the code below.
 **/
require_once( 'run-some-filters.php' );






/**
 * WC_HooksAndFilters Class
 *
 * @version 1.0
 * @updated 02.16.13
 **/
$WC_HooksAndFilters = new WC_HooksAndFilters();
class WC_HooksAndFilters {
	
	
	
	var $action = 'wc-ajax';
	
	
	
	
	
	
	/**
	 * __construct
	 *
	 * @version 1.1
	 * @updated 03.14.13
	 **/
	function __construct() {

		// hook method after_setup_theme
		add_action( 'after_setup_theme', array( &$this, 'after_setup_theme' ) );

		// hook method init
		add_action( 'init', array( &$this, 'init' ) );

		// hook method admin_init
		add_action( 'admin_init', array( &$this, 'admin_init' ) );

	} // end function __construct
	
	
	
	
	
	
	/**
	 * after_setup_theme
	 *
	 * @version 1.1
	 * @updated 03.14.13
	 *
	 * @codex http://codex.wordpress.org/Plugin_API/Action_Reference/after_setup_theme
	 *
	 * Description:
	 * This hook is called during a themes initialization. 
	 * Is generally used to perform basic setup, registration, and init actions for a theme.
	 **/
	function after_setup_theme() {
		
		/**
		 * Set Static Options
		 * These options are not in connect to the db. They
		 * are a simple array of options for this specific class.
		 **/
		$this->set_options();
		
		/**
		 * Theme Support
		 * http://codex.wordpress.org/Function_Reference/add_theme_support
		 *
		 * Codex Desc:
		 * Allows a theme or plugin to register support of a certain theme feature.
		 * If called from a theme, it should be done in the theme's functions.php file to work. 
		 * It can also be called from a plugin if attached to a hook.
		 * 
         * If attached to a hook, it must be after_setup_theme.
         * The init hook may be too late for some features.
		 **/
		
		// Various Theme Support
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'nav-menus' );
		
		/**
		 * Note:
		 * The follow two items use variables from the options example
		 * to populate set_post_thumbnail_size and add_image_size.
		 *
		 * The purpose in utilizing options is to do show an example of 
		 * filtering an array before it is put to use.
		 **/
		
		// Set post thumbnail size
		if ( isset( $this->options['set_post_thumbnail_size'] ) AND is_array( $this->options['set_post_thumbnail_size'] ) AND ! empty( $this->options['set_post_thumbnail_size'] ) )
			set_post_thumbnail_size( $this->options['set_post_thumbnail_size']['width'], $this->options['set_post_thumbnail_size']['height'] , $this->options['set_post_thumbnail_size']['crop'] );
		
		// Add image sizes
		if ( isset( $this->options['add_image_sizes'] ) AND is_array( $this->options['add_image_sizes'] ) ) {
			foreach ( $this->options['add_image_sizes'] as $add_image_sizes ) {
				if ( is_array( $add_image_sizes ) )
					add_image_size( $add_image_sizes['name'], $add_image_sizes['width'], $add_image_sizes['height'], $add_image_sizes['crop'] );
			}
		}
		
	} // end function after_setup_theme
	
	
	
	
	
	
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
		
        
		
		/**
		 * Register Styles and Scripts
		 **/
		
		// Style CSS
		wp_register_style( 'wc-default', plugins_url( basename( dirname(__File__) ) . '/style.css' ) );
		
		// Custom JS
		wp_register_script( 'wc-custom', plugins_url( basename( dirname(__File__) ) . '/custom.js' ), array( 'jquery' ) );
		
		
		
		/**
		 * Enqueue Styles and Scripts
		 **/
		
		// CSS // wp_print_styles
		add_action( 'wp_print_styles', array( &$this, 'wp_print_styles' ) );

		// Javascripts // wp_enqueue_scripts // wp_print_scripts
		add_action( 'wp_enqueue_scripts', array( &$this, 'wp_enqueue_scripts' ) );
		
		
		
		/**
		 * General Filters
		 **/
		
		// Filter Post class
		add_filter( 'post_class', array( &$this, 'post_class' ) );
		
		// Filter Body class
		add_filter( 'body_class', array( &$this, 'body_class' ) ); 
		
		
		/**
		 * Filter The Content
		 *
		 * Note:
		 * a priority of 9 was used so the filter would be ran
         * before all default wp core filters.
		 **/
		add_filter( 'the_content', array( &$this, 'the_content' ), 9 );
		
		
		/**
		 * General Actions
		 **/
		
		// Filter Body class
		add_action( 'wp_head', array( &$this, 'favicon' ) );
		
		
		/**
		 * Ajax
		 **/
 		add_action( "wp_ajax_$this->action", array( &$this, 'do_ajax' ) );
		
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
		wp_register_style( 'wc-admin-default', plugins_url( basename( dirname(__File__) ) . "/admin-style.css" ) );
		
		// admin js
		wp_register_script( 'wc-admin-custom', plugins_url( basename( dirname(__File__) ) . "/admin-custom.js"), array( 'jquery' ) );
		
		
		/**
		 * Enqueue Styles and Scripts
		 **/
		
		// CSS // admin_print_styles
		add_action( 'admin_print_styles', array( &$this, 'admin_print_styles' ) );

		// Javascripts // admin_enqueue_scripts
		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) );
		
		
		/**
		 * Meta Boxes
		 **/
		
		// Add custom meta Bbox excerpt to pages.
		add_meta_box( 'postexcerpt', 'Excerpt', 'post_excerpt_meta_box', 'page', 'normal', 'core' );
		
		
		/**
		 * Shortcodes
		 **/
		
		// Add Shortcode recognition to text widget
		add_filter( 'widget_text', 'do_shortcode' );
		
	} // end function admin_init
	
	
	
	
	
	
	####################################################################################################
	/**
	 * Functionality
	 **/
	####################################################################################################
	
	
	
	
	
	
	/**
	 * Set Options
	 *
	 * @version 1.1
	 * @updated 03.14.13
	 * 
	 * Notes:
	 * Return an array that will be used as default options.
	 **/
	function set_options() {
		
		// Set Options array
		$this->options = apply_filters( 'init-site-options', array(	
			'favicon' => home_url() . '/favicon.ico',
			
			'set_post_thumbnail_size' => array(
				'width' => get_option( 'thumbnail_size_w' ),
				'height' => get_option( 'thumbnail_size_h' ),
				'crop' => get_option( 'thumbnail_crop' ),
				),
			
			'add_image_sizes' => array(
				array(
					'name' => 'wc-louis-image-lrg',
					'width' => 500,
					'height' => 500,
					'crop' => false,
					),
				array(
					'name' => 'wc-louis-image-med',
					'width' => 300,
					'height' => 300,
					'crop' => false,
					),
				array(
					'name' => 'wc-louis-image-sml',
					'width' => 150,
					'height' => 150,
					'crop' => false,
					),
				)
			) );
		
	} // end function set_options
	
	
	
	
	
	
	/**
	 * Add CSS
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function wp_print_styles() {
		
		wp_enqueue_style( 'wc-default' );

	} // end function wp_print_styles
	
	
	
	
	
	
	/**
	 * Enqueue Scripts
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function wp_enqueue_scripts() {
	    
	    // Localize JS
		wp_localize_script( 'wc-custom', 'wcObject', apply_filters( 'wc-localize_script', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'action' => $this->action,
			'template_directory' => get_stylesheet_directory_uri(),
			'home_url' => home_url(),
			) ) );
		
		wp_enqueue_script( 'wc-custom' );
		
	} // function wp_enqueue_scripts
	
	
	
	
	
	
	/**
	 * Admin CSS
	 * 
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function admin_print_styles() {
		
		wp_enqueue_style( 'wc-admin-default' );

	} // function admin_print_styles
	
	
	
	
	
	
	/**
	 * Admin Enqueue Scripts
	 * 
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function admin_enqueue_scripts() {
		
		// localize admin js
		wp_localize_script( 'wc-admin-custom', 'wcAdminObject', apply_filters( 'wc-admin-custom', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'action' => $this->action,
			) ) );
			
		wp_enqueue_script( 'wc-admin-custom' );

	} // end function admin_enqueue_scripts
	
	
	
	
	
	
	/**
	 * Post Class
	 *
	 * @version 1.0
	 * @updated	02.16.13
	 * @codex http://codex.wordpress.org/Function_Reference/post_class
	 **/
	function post_class( $classes ) {
		global $wp_query;
		
		if ( has_post_thumbnail( $wp_query->post->ID ) )
			$classes[] = 'has-post-thumbnail';
		
		return $classes;
		
	} // end function post_class
	
	
	
	
	
	
	/**
	 * Body Class
	 *
	 * @version 1.0
	 * @updated	02.16.13
	 * @codex http://codex.wordpress.org/Function_Reference/body_class
	 **/
	function body_class( $classes ) {
		global $wp_query;
		
		if ( isset( $wp_query->post->post_type ) AND ! empty( $wp_query->post->post_type ) )
			$classes[] = "content-post-type-" . $wp_query->post->post_type;
		
		return $classes;
		
	} // end function body_class 
	
	
	
	
	
	
	/**
	 * Body Class
	 *
	 * @version 1.0
	 * @updated	02.16.13
	 * @codex http://codex.wordpress.org/Function_Reference/the_content
	 **/
	function the_content( $content ) {
		global $post;
		
		if ( $post->post_type == 'post' AND get_post_meta( $post->ID, 'have_transformers', true ) == 1 ) {
			$nonce = wp_create_nonce($this->action);
			$content = $content . "\n\nI like <a class=\"transformers\" href=\"http://images1.wikia.nocookie.net/__cb20061017232156/transformers/images/6/6d/ArmadaUnicron02.jpg\" data-case=\"transformers\" data-name=\"unicron\" data-nonce=\"$nonce\">Unicron</a> more than <a class=\"transformers\" href=\"http://cdn1.screenrant.com/wp-content/uploads/Shockwave-Transformers.jpg\" data-case=\"transformers\" data-name=\"shockwave\" data-nonce=\"$nonce\">Shockwave</a>.";
		}
		
		return $content;
		
	} // end function the_content
	
	
	
	
	
	
	/** 
	 * favicon
	 *
	 * @version 1.0
	 * @updated	02.16.13
	 **/
	function favicon() {
		
		if ( isset( $this->options['favicon'] ) AND ! empty( $this->options['favicon'] ) )
			echo "\n<link rel=\"icon\" href=\"" . $this->options['favicon'] . "\" />\n";
			
	} // end function favicon
	
	
	
	
	
	
	####################################################################################################
	/**
	 * Ajax
	 **/
	####################################################################################################
	
	
	
	
	
	
	/**
	 * Do Ajax
	 *
	 * @version 1.0
	 * @updated 02.16.13
	 **/
	function do_ajax() {
		
		// Default Response
		$response = array(
			'status'	=> 'error',
			'message'	=> 'Invalid AJAX call',
			'element'	=> esc_attr( @$_POST['element'] )
			);
		
		if ( defined( 'DOING_AJAX') AND DOING_AJAX ) {
			
			$response['message'] = 'Invalid nonce';
			
			if ( isset( $_POST['switch_case'] ) AND isset( $_POST['nonce'] ) AND wp_verify_nonce( $_POST['nonce'], $this->action ) ) {
				$_post = apply_filters( 'wc-ajax-incoming-data', $_POST );
				extract( $_post, EXTR_SKIP );
				
				switch ( $switch_case ) {
					
					case "transformers" :
						if ( isset( $imgSrc ) AND ! empty( $imgSrc ) ) {
							$response['image'] = "<img class=\"transformer-image transformer-$name\" src=\"$imgSrc\" alt=\"\" />";
							$response['status'] = 'success';
							$response['name'] = $name;
							$response['message'] = "More than meets the eye!";
						} else {
							$response['status'] = 'error';
							$response['message'] = "Your Image is missing... check the href";
						}
						break;
					
				} // end switch ( $switch_case )
				
				$response['element'] = esc_attr( @$_POST['element'] );
			
			} // end if varify
			
			$response = apply_filters( 'wc-ajax-outgoing-data', $response, $_post );
			
			header( 'Content: application/json' );
			echo json_encode( $response );

			die();
		
		} // end if DOING_AJAX
		
	} // end function do_ajax
	
	
	
} // end class WC_HooksAndFilters