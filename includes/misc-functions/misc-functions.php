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
 * Short Description
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @see      function_name()
 * @param    array $args See link for description.
 * @return   void
 */ 
function mp_stacks_forms_mode(){
	
	//Filter here to make it "Advanced". If it is advanced, it adds options for WP Post creation, File Uploads, and other confusing or more dangerous things.
	return apply_filters( 'mp_stacks_forms_mode', 'simple' );
	
}
 