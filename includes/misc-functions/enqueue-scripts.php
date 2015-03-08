<?php
/**
 * This file contains the enqueue scripts function for the forms plugin
 *
 * @since 1.0.0
 *
 * @package    MP Stacks Forms
 * @subpackage Functions
 *
 * @copyright  Copyright (c) 2014, Mint Plugins
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author     Philip Johnston
 */

/**
 * Admin Enqueue css and js
 *
 */
function mp_stacks_forms_admin_enqueue_scripts(){
			
	//Enqueue forms JS
	wp_enqueue_script( 'mp_stacks_forms_admin_js', plugins_url( 'js/mp-stacks-forms-admin.js', dirname( __FILE__ ) ), array('jquery'), MP_STACKS_FORMS_VERSION, true );

}
add_action( 'admin_enqueue_scripts', 'mp_stacks_forms_admin_enqueue_scripts' );

/**
 * Front end Enqueue css and js
 *
 */
function mp_stacks_forms_enqueue_scripts(){
	//Enqueue forms JS
	wp_enqueue_style( 'mp_stacks_forms_css', plugins_url( 'css/mp-stacks-forms.css', dirname( __FILE__ ) ), MP_STACKS_FORMS_VERSION );
	
	//Enqueue frontend JS
	wp_enqueue_script( 'mp_stacks_forms_js', plugins_url( 'js/mp-stacks-forms.js', dirname( __FILE__ ) ), array('jquery', 'mp_stacks_front_end_js'), MP_STACKS_FORMS_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'mp_stacks_forms_enqueue_scripts' );