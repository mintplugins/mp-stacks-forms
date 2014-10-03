<?php
/**
 * This page contains functions for modifying the metabox for forms as a media type
 *
 * @link http://mintplugins.com/doc/
 * @since 1.0.0
 *
 * @package    MP Stacks Forms
 * @subpackage Functions
 *
 * @copyright   Copyright (c) 2014, Mint Plugins
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @author      Philip Johnston
 */
 
/**
 * Add Image Forms as a Media Type to the dropdown
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @param    array $args See link for description.
 * @return   void
 */
function mp_stacks_forms_create_meta_box(){
	
	/**
	 * Array which stores all info about the new metabox
	 *
	 */
	$mp_stacks_forms_add_meta_box = array(
		'metabox_id' => 'mp_stacks_forms_metabox', 
		'metabox_title' => __( '"Forms" Content-Type', 'mp_stacks_forms'), 
		'metabox_posttype' => 'mp_brick', 
		'metabox_context' => 'advanced', 
		'metabox_priority' => 'low' 
	);
	
	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_forms_items_array = array(
		array(
			'field_id'			=> 'mp_stacks_forms_email',
			'field_title' 	=> __( 'Email Address:', 'mp_stacks_forms'),
			'field_description' 	=> __( 'When this form is submitted, which email should it send to?', 'mp_stacks_forms' ),
			'field_type' 	=> 'textbox',
			'field_value' => '20',
			'field_placeholder' => __('Email Address', 'mp_stacks_forms' ),
		),
		array(
			'field_id'			=> 'mp_stacks_form_style_settings',
			'field_title' 	=> __( 'Form Style Settings:', 'mp_stacks_forms'),
			'field_description' 	=> __( '', 'mp_stacks_forms' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
		array(
			'field_id'			=> 'mp_stacks_forms_field_titles_font_size',
			'field_title' 	=> __( 'Field Titles Font-Size:', 'mp_stacks_forms'),
			'field_description' 	=> __( 'What size should the Field Titles be? Default: 20', 'mp_stacks_forms' ),
			'field_type' 	=> 'number',
			'field_value' => '20',
			'field_showhider' => 'mp_stacks_form_style_settings',
		),
		array(
			'field_id'			=> 'mp_stacks_forms_field_titles_font_color',
			'field_title' 	=> __( 'Field Titles Font-Color', 'mp_stacks_forms'),
			'field_description' 	=> __( 'What color should the font titles be? Default #000 (Black)', 'mp_stacks_forms' ),
			'field_type' 	=> 'colorpicker',
			'field_value' => '#000',
			'field_showhider' => 'mp_stacks_form_style_settings',
		),
		array(
			'field_id'			=> 'mp_stacks_forms_field_desc_font_size',
			'field_title' 	=> __( 'Field Descriptions Font-Size:', 'mp_stacks_forms'),
			'field_description' 	=> __( 'What size should the Field Descriptions be? Default: 15', 'mp_stacks_forms' ),
			'field_type' 	=> 'number',
			'field_value' => '15',
			'field_showhider' => 'mp_stacks_form_style_settings',
		),
		array(
			'field_id'			=> 'mp_stacks_forms_field_desc_font_color',
			'field_title' 	=> __( 'Field Descriptions Font-Color', 'mp_stacks_forms'),
			'field_description' 	=> __( 'What color should the font Descriptions be? Default #000 (Black)', 'mp_stacks_forms' ),
			'field_type' 	=> 'colorpicker',
			'field_value' => '#000',
			'field_showhider' => 'mp_stacks_form_style_settings',
		),
		array(
			'field_id'			=> 'mp_stacks_forms_field_spacing',
			'field_title' 	=> __( 'Field Spacing', 'mp_stacks_forms'),
			'field_description' 	=> __( 'How much space should there be in between each field? Default: 20px', 'mp_stacks_forms' ),
			'field_type' 	=> 'number',
			'field_value' => '20',
			'field_showhider' => 'mp_stacks_form_style_settings',
		),
		array(
			'field_id'	 => 'mp_stacks_forms_field_repeater_title',
			'field_title' => __( 'Form Field', 'mp_stacks'),
			'field_description' => __( '', 'mp_stacks' ),
			'field_type' => 'repeatertitle',
			'field_value' => '',
			'field_repeater' => 'mp_stacks_forms_fields'
		),
		array(
			'field_id'			=> 'field_title',
			'field_title' 	=> __( 'Field Title', 'mp_stacks_forms'),
			'field_description' 	=> __( 'What should the title of this field be? (Example: "First Name")', 'mp_stacks_forms' ),
			'field_type' 	=> 'textbox',
			'field_value' => '',
			'field_repeater' => 'mp_stacks_forms_fields',
		),
		array(
			'field_id'			=> 'field_description',
			'field_title' 	=> __( 'Field Description', 'mp_stacks_forms'),
			'field_description' 	=> __( 'What should the description of this field be? (Example: "Enter your First Name")', 'mp_stacks_forms' ),
			'field_type' 	=> 'textbox',
			'field_value' => '',
			'field_repeater' => 'mp_stacks_forms_fields',
		),
		array(
			'field_id'			=> 'field_placeholder',
			'field_title' 	=> __( 'Field Placeholder Text', 'mp_stacks_forms'),
			'field_description' 	=> __( 'If you want placeholder text to be displayed in this field before the user enters something, enter that here.', 'mp_stacks_forms' ),
			'field_type' 	=> 'textbox',
			'field_value' => '',
			'field_repeater' => 'mp_stacks_forms_fields',
		),
		array(
			'field_id'			=> 'field_type',
			'field_title' 	=> __( 'Field Type', 'mp_stacks_forms'),
			'field_description' 	=> __( 'What type of field should this be? Default: Single Line Text Field', 'mp_stacks_forms' ),
			'field_type' 	=> 'select',
			'field_value' => 'text_field',
			'field_select_values' => array(
				'textbox' => __( 'Single Line Text Field', 'mp_stacks_forms' ),
				'textarea' => __( 'Paragraph Text Field', 'mp_stacks_forms' ),
				'email' => __( 'Email Field', 'mp_stacks_forms' ),
				'date' => __( 'Date Field', 'mp_stacks_forms' ),
				'url' => __( 'URL Field', 'mp_stacks_forms' ),
				'select' => __( 'Dropdown Select Field', 'mp_stacks_forms' ),
				'color' => __( 'Color Picker', 'mp_stacks_forms' ),
				'checkbox' => __( 'Checkboxes', 'mp_stacks_forms' ),
				'radio' => __( 'Radio Buttons', 'mp_stacks_forms' ),
			),
			'field_repeater' => 'mp_stacks_forms_fields',
		),
		array(
			'field_id'			=> 'field_width',
			'field_title' 	=> __( 'Field Width', 'mp_stacks_forms'),
			'field_description' 	=> __( 'What percentage should the width of this field be? Default: 50%', 'mp_stacks_forms' ),
			'field_type' 	=> 'input_range',
			'field_value' => '50',
			'field_repeater' => 'mp_stacks_forms_fields',
		),
		array(
			'field_id'			=> 'field_select_values',
			'field_title' 	=> __( 'Dropdown Field Options', 'mp_stacks_forms'),
			'field_description' 	=> __( 'Enter the options people can choose from in the dropdown - separated by commas (EG: Option 1, Option1, Option 3)', 'mp_stacks_forms' ),
			'field_type' 	=> 'textarea',
			'field_value' => '',
			'field_placeholder' => __('Option 1, Option 2, Option 3', 'mp_stacks_forms' ),
			'field_repeater' => 'mp_stacks_forms_fields',
		),
		array(
			'field_id'			=> 'field_post_meta',
			'field_title' 	=> __( 'Post Meta Slug', 'mp_stacks_forms'),
			'field_description' 	=> __( 'Enter the Post Meta Slug this value will be saved to upon submit.', 'mp_stacks_forms' ),
			'field_type' 	=> 'textarea',
			'field_value' => '',
			'field_placeholder' => __('Option 1, Option 2, Option 3', 'mp_stacks_forms' ),
			'field_repeater' => 'mp_stacks_forms_fields',
		),
		array(
			'field_id'			=> 'mp_stacks_forms_submit_button_showhider',
			'field_title' 	=> __( 'Submit Button Options', 'mp_stacks_forms'),
			'field_description' 	=> __( '', 'mp_stacks_forms' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
		),
		array(
			'field_id'			=> 'mp_stacks_forms_submit_button_showhider',
			'field_title' 	=> __( 'Submit Button Text', 'mp_stacks_forms'),
			'field_description' 	=> __( 'What should the submit button say? Default "Submit"', 'mp_stacks_forms' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',
			'field_showhider' => 'mp_stacks_forms_submit_button_showhider'
		),
	
	);
	
	
	/**
	 * Custom filter to allow for add-on plugins to hook in their own data for add_meta_box array
	 */
	$mp_stacks_forms_add_meta_box = has_filter('mp_stacks_forms_meta_box_array') ? apply_filters( 'mp_stacks_forms_meta_box_array', $mp_stacks_forms_add_meta_box) : $mp_stacks_forms_add_meta_box;
	
	//Globalize the and populate mp_stacks_features_items_array (do this before filter hooks are run)
	global $global_mp_stacks_forms_items_array;
	$global_mp_stacks_forms_items_array = $mp_stacks_forms_items_array;
	
	/**
	 * Custom filter to allow for add on plugins to hook in their own extra fields 
	 */
	$mp_stacks_forms_items_array = has_filter('mp_stacks_forms_items_array') ? apply_filters( 'mp_stacks_forms_items_array', $mp_stacks_forms_items_array) : $mp_stacks_forms_items_array;
	
	/**
	 * Create Metabox class
	 */
	global $mp_stacks_forms_meta_box;
	$mp_stacks_forms_meta_box = new MP_CORE_Metabox($mp_stacks_forms_add_meta_box, $mp_stacks_forms_items_array);
}
add_action('mp_brick_metabox', 'mp_stacks_forms_create_meta_box');