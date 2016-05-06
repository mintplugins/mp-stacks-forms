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
 * @copyright   Copyright (c) 2015, Mint Plugins
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
		'metabox_priority' => 'low',
		'metabox_content_via_ajax' => true,		
	);
	
	$post_types_array = mp_core_get_all_post_types();
	$post_types_array['post'] = 'Posts';
	
	$field_types = array(
		'textbox' => __( 'Single Line Text Field', 'mp_stacks_forms' ),
		'textarea' => __( 'Paragraph Text Field', 'mp_stacks_forms' ),
		'email' => __( 'Email Field', 'mp_stacks_forms' ),
		'date' => __( 'Date Field', 'mp_stacks_forms' ),
		'url' => __( 'URL Field', 'mp_stacks_forms' ),
		'select' => __( 'Dropdown Select Field', 'mp_stacks_forms' ),
		'color' => __( 'Color Picker', 'mp_stacks_forms' ),
		'checkbox' => __( 'Checkboxes', 'mp_stacks_forms' ),
		'radio' => __( 'Radio Buttons', 'mp_stacks_forms' ),
	);
		
	//If we are in mp_stacks_forms advanced mode, handle file uploads.
	if ( mp_stacks_forms_mode() == 'advanced' ){
		$field_types['fileupload'] = __( 'File Upload', 'mp_stacks_forms' );
		$field_types['taxonomy'] = __( 'Category Selector', 'mp_stacks_forms' );
	}

	/**
	 * Array which stores all info about the options within the metabox
	 *
	 */
	$mp_stacks_forms_items_array = array();
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'mp_stacks_form_style_settings',
			'field_title' 	=> __( 'Form Style Settings:', 'mp_stacks_forms'),
			'field_description' 	=> __( '', 'mp_stacks_forms' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',		
	);
	
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'mp_stacks_forms_field_text_alignment',
			'field_title' 	=> __( 'Form Field Alignments:', 'mp_stacks_forms'),
			'field_description' 	=> __( 'How should the form fields be aligned?', 'mp_stacks_forms' ),
			'field_type' 	=> 'select',
			'field_value' => 'left',
			'field_select_values' => array( 
				'left' => __('Left', 'mp_stacks_forms' ),
				'center' => __('Center', 'mp_stacks_forms' ),
				'right' => __('Right', 'mp_stacks_forms' ),
			),
			'field_showhider' => 'mp_stacks_form_style_settings',		
	);
		
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'mp_stacks_forms_field_titles_font_size',
			'field_title' 	=> __( 'Field Titles Font-Size:', 'mp_stacks_forms'),
			'field_description' 	=> __( 'What size should the Field Titles be? Default: 20', 'mp_stacks_forms' ),
			'field_type' 	=> 'number',
			'field_value' => '20',
			'field_showhider' => 'mp_stacks_form_style_settings',		
	);
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'mp_stacks_forms_field_titles_font_color',
			'field_title' 	=> __( 'Field Titles Font-Color', 'mp_stacks_forms'),
			'field_description' 	=> __( 'What color should the font titles be? Default #000 (Black)', 'mp_stacks_forms' ),
			'field_type' 	=> 'colorpicker',
			'field_value' => '#000',
			'field_showhider' => 'mp_stacks_form_style_settings',		
	);
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'mp_stacks_forms_field_desc_font_size',
			'field_title' 	=> __( 'Field Descriptions Font-Size:', 'mp_stacks_forms'),
			'field_description' 	=> __( 'What size should the Field Descriptions be? Default: 15', 'mp_stacks_forms' ),
			'field_type' 	=> 'number',
			'field_value' => '15',
			'field_showhider' => 'mp_stacks_form_style_settings',		
	);
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'mp_stacks_forms_field_desc_font_color',
			'field_title' 	=> __( 'Field Descriptions Font-Color', 'mp_stacks_forms'),
			'field_description' 	=> __( 'What color should the font Descriptions be? Default #000 (Black)', 'mp_stacks_forms' ),
			'field_type' 	=> 'colorpicker',
			'field_value' => '#000',
			'field_showhider' => 'mp_stacks_form_style_settings',		
	);
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'mp_stacks_forms_field_spacing',
			'field_title' 	=> __( 'Field Spacing', 'mp_stacks_forms'),
			'field_description' 	=> __( 'How much space should there be in between each field? Default: 20px', 'mp_stacks_forms' ),
			'field_type' 	=> 'number',
			'field_value' => '20',
			'field_showhider' => 'mp_stacks_form_style_settings',		
	);
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'mp_stacks_forms_fields_showhider',
			'field_title' 	=> __( 'Form Fields', 'mp_stacks_forms'),
			'field_description' 	=> '',
			'field_type' 	=> 'showhider',
			'field_value' => '',		
	);
	$mp_stacks_forms_items_array[] = array(
			'field_id'	 => 'mp_stacks_forms_field_repeater_title',
			'field_title' => __( 'Form Field', 'mp_stacks'),
			'field_description' => __( '', 'mp_stacks' ),
			'field_type' => 'repeatertitle',
			'field_value' => '',
			'field_repeater' => 'mp_stacks_forms_fields',
			'field_showhider' => 'mp_stacks_forms_fields_showhider',		
	);
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'field_title',
			'field_title' 	=> __( 'Field Title', 'mp_stacks_forms'),
			'field_description' 	=> __( 'What should the title of this field be? (Example: "First Name")', 'mp_stacks_forms' ),
			'field_type' 	=> 'textbox',
			'field_value' => '',
			'field_repeater' => 'mp_stacks_forms_fields',
			'field_showhider' => 'mp_stacks_forms_fields_showhider',		
	);
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'field_description',
			'field_title' 	=> __( 'Field Description', 'mp_stacks_forms'),
			'field_description' 	=> __( 'What should the description of this field be? (Example: "Enter your First Name")', 'mp_stacks_forms' ),
			'field_type' 	=> 'textbox',
			'field_value' => '',
			'field_repeater' => 'mp_stacks_forms_fields',
			'field_showhider' => 'mp_stacks_forms_fields_showhider',		
	);
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'field_type',
			'field_title' 	=> __( 'Field Type', 'mp_stacks_forms'),
			'field_description' 	=> __( 'What type of field should this be? Default: Single Line Text Field', 'mp_stacks_forms' ),
			'field_type' 	=> 'select',
			'field_value' => 'text_field',
			'field_select_values' => $field_types,
			'field_repeater' => 'mp_stacks_forms_fields',
			'field_showhider' => 'mp_stacks_forms_fields_showhider',		
	);
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'field_required',
			'field_title' 	=> __( 'Field Required', 'mp_stacks_forms'),
			'field_description' 	=> __( 'Do you want this field to be required?', 'mp_stacks_forms' ),
			'field_type' 	=> 'checkbox',
			'field_value' => '',
			'field_repeater' => 'mp_stacks_forms_fields',
			'field_showhider' => 'mp_stacks_forms_fields_showhider',		
	);
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'field_placeholder',
			'field_title' 	=> __( 'Field Placeholder Text', 'mp_stacks_forms'),
			'field_description' 	=> __( 'If you want placeholder text to be displayed in this field before the user enters something, enter that here.', 'mp_stacks_forms' ),
			'field_type' 	=> 'textbox',
			'field_value' => '',
			'field_repeater' => 'mp_stacks_forms_fields',
			'field_showhider' => 'mp_stacks_forms_fields_showhider',
			'field_conditional_id' => 'field_type',
			'field_conditional_values' => array('textbox', 'textarea', 'email', 'date', 'url', 'color'),		
	);
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'field_taxonomy',
			'field_title' 	=> __( 'Which Taxonomy?', 'mp_stacks_forms'),
			'field_description' 	=> __( 'Category Types are called "Taxonomies". From which Taxonomy should the user select their categories?', 'mp_stacks_forms' ),
			'field_type' 	=> 'select',
			'field_select_values' => get_taxonomies(),
			'field_value' => '',
			'field_repeater' => 'mp_stacks_forms_fields',
			'field_showhider' => 'mp_stacks_forms_fields_showhider',
			'field_conditional_id' => 'field_type',
			'field_conditional_values' => array('taxonomy'),		
	);
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'field_select_values',
			'field_title' 	=> __( 'Field Selection Options', 'mp_stacks_forms'),
			'field_description' 	=> __( 'Enter the options people can choose from in the dropdown, checkboxes, or radio button - separated by commas (EG: Option 1, Option1, Option 3)', 'mp_stacks_forms' ),
			'field_type' 	=> 'textarea',
			'field_value' => '',
			'field_placeholder' => __('Option 1, Option 2, Option 3', 'mp_stacks_forms' ),
			'field_repeater' => 'mp_stacks_forms_fields',
			'field_conditional_id' => 'field_type',
			'field_conditional_values' => array('select', 'radio', 'checkbox'),
			'field_showhider' => 'mp_stacks_forms_fields_showhider',		
	);
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'field_width',
			'field_title' 	=> __( 'Field Width', 'mp_stacks_forms'),
			'field_description' 	=> __( 'What percentage should the width of this field be? Default: 50%. Tip: If you want this field to sit beside another field, make sure the 2 adjacent fields add up to 99% or less.', 'mp_stacks_forms' ),
			'field_type' 	=> 'input_range',
			'field_value' => '49',
			'field_repeater' => 'mp_stacks_forms_fields',
			'field_showhider' => 'mp_stacks_forms_fields_showhider',		
	);
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'field_placement',
			'field_title' 	=> __( 'Field Placement', 'mp_stacks_forms'),
			'field_description' 	=> __( 'Should this field sit side-by-side with other side-by-side fields?', 'mp_stacks_forms' ),
			'field_type' 	=> 'select',
			'field_value' => 'table',
			'field_select_values' => array( 
				'table' => __( 'No other fields beside.', 'mp_stacks_forms' ), 
				'inline-block' => __( 'Side-by-side (if adjacent fields are set to side-by-side as well)', 'mp_stacks_forms' ), 
			),
			'field_repeater' => 'mp_stacks_forms_fields',
			'field_showhider' => 'mp_stacks_forms_fields_showhider',		
	);
	
	//If we are in mp_stacks_forms advances mode, handle file uploads.
	if ( mp_stacks_forms_mode() == 'advanced' ){
		
		$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'field_meta_key',
			'field_title' 	=> __( 'Field Meta Key (Optional)', 'mp_stacks_forms'),
			'field_description' 	=> __( 'If this form is being used to create a WordPress post, enter the EXACT meta key slug this field will be saved as (or select an option from the dropdown).', 'mp_stacks_forms' ),
			'field_type' 	=> 'datalist',
			'field_value' => '',
			'field_select_values' => array( 
				'post_title' => 'post_title', 
				'post_date' => 'post_date', 
				'post_content' => 'post_content', 
				'featured_image' => 'featured_image'
			),
			'field_repeater' => 'mp_stacks_forms_fields',
			'field_showhider' => 'mp_stacks_forms_fields_showhider',
			'field_conditional_id' => 'field_type',
			'field_conditional_values' => $field_types
		);
		
	}
	
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'mp_stacks_forms_submit_button_showhider',
			'field_title' 	=> __( 'Submit Button Options', 'mp_stacks_forms'),
			'field_description' 	=> __( '', 'mp_stacks_forms' ),
			'field_type' 	=> 'showhider',
			'field_value' => '',		
	);
	$mp_stacks_forms_items_array[] = array(
				'field_id'			=> 'mp_stacks_forms_submit_button_text',
				'field_title' 	=> __( 'Submit Button Text', 'mp_stacks_forms'),
				'field_description' 	=> __( 'What should the submit button say? Default "Submit"', 'mp_stacks_forms' ),
				'field_type' 	=> 'textbox',
				'field_value' => '',
				'field_showhider' => 'mp_stacks_forms_submit_button_showhider'		
	);
	$mp_stacks_forms_items_array[] = array(
				'field_id'			=> 'mp_stacks_forms_submit_button_text_color',
				'field_title' 	=> __( 'Submit Button Text Color', 'mp_stacks_forms'),
				'field_description' 	=> __( 'What color should the text on submit button be?', 'mp_stacks_forms' ),
				'field_type' 	=> 'colorpicker',
				'field_value' => '',
				'field_showhider' => 'mp_stacks_forms_submit_button_showhider'		
	);
	$mp_stacks_forms_items_array[] = array(
				'field_id'			=> 'mp_stacks_forms_submit_button_background_color',
				'field_title' 	=> __( 'Submit Button Background Color', 'mp_stacks_forms'),
				'field_description' 	=> __( 'What color should the background of the submit button be?', 'mp_stacks_forms' ),
				'field_type' 	=> 'colorpicker',
				'field_value' => '',
				'field_showhider' => 'mp_stacks_forms_submit_button_showhider'		
	);
	$mp_stacks_forms_items_array[] = array(
				'field_id'			=> 'mp_stacks_forms_submit_button_hover_text_color',
				'field_title' 	=> __( 'Mouse Over: Submit Button Text Color', 'mp_stacks_forms'),
				'field_description' 	=> __( 'What color should the text on submit button be when the mouse is over it?', 'mp_stacks_forms' ),
				'field_type' 	=> 'colorpicker',
				'field_value' => '',
				'field_showhider' => 'mp_stacks_forms_submit_button_showhider'		
	);
	$mp_stacks_forms_items_array[] = array(
				'field_id'			=> 'mp_stacks_forms_submit_button_hover_background_color',
				'field_title' 	=> __( 'Mouse Over: Submit Button Background Color', 'mp_stacks_forms'),
				'field_description' 	=> __( 'What color should the background of the submit button be?', 'mp_stacks_forms' ),
				'field_type' 	=> 'colorpicker',
				'field_value' => '',
				'field_showhider' => 'mp_stacks_forms_submit_button_showhider'		
	);
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'mp_stacks_forms_security_showhider',
			'field_title' 	=> __( 'Form Security', 'mp_stacks_forms'),
			'field_description' 	=> '',
			'field_type' 	=> 'showhider',
			'field_value' => '',		
	);
	$mp_stacks_forms_items_array[] = array(
				'field_id'			=> 'mp_stacks_forms_google_recaptcha_showhider',
				'field_title' 	=> __( 'Google reCaptcha', 'mp_stacks_forms'),
				'field_description' 	=> '',
				'field_type' 	=> 'showhider',
				'field_value' => '',
				'field_showhider' => 'mp_stacks_forms_security_showhider'		
	);
	$mp_stacks_forms_items_array[] = array(
					'field_id'			=> 'mp_stacks_forms_recaptcha',
					'field_title' 	=> __( 'Secure this form with Google reCaptcha?', 'mp_stacks_forms'),
					'field_description' 	=> __( 'If you\'d like to prevent spam using Google\'s Free reCaptcha Service, check this option.', 'mp_stacks_forms' ),
					'field_type' 	=> 'checkbox',
					'field_value' => '',
					'field_showhider' => 'mp_stacks_forms_google_recaptcha_showhider'		
	);
	$mp_stacks_forms_items_array[] = array(
					'field_id'			=> 'mp_stacks_forms_recaptcha_site_key',
					'field_title' 	=> __( 'Google reCaptcha Site Key', 'mp_stacks_forms'),
					'field_description' 	=> '<a target="_blank" href="https://www.google.com/recaptcha/admin">' . __( 'Click Here to create/locate your recaptcha Site Key', 'mp_stacks_forms' ) . '</a>',
					'field_type' 	=> 'textbox',
					'field_value' => '',
					'field_conditional_id' => 'mp_stacks_forms_recaptcha',
					'field_conditional_values' => array('mp_stacks_forms_recaptcha'),
					'field_showhider' => 'mp_stacks_forms_google_recaptcha_showhider'		
	);
	$mp_stacks_forms_items_array[] = array(
					'field_id'			=> 'mp_stacks_forms_recaptcha_secret_key',
					'field_title' 	=> __( 'Google reCaptcha Secret Key', 'mp_stacks_forms'),
					'field_description' 	=> '<a target="_blank" href="https://www.google.com/recaptcha/admin">' . __( 'Click Here to create/locate your recaptcha Secret Key', 'mp_stacks_forms' ) . '</a>',
					'field_type' 	=> 'textbox',
					'field_value' => '',
					'field_conditional_id' => 'mp_stacks_forms_recaptcha',
					'field_conditional_values' => array('mp_stacks_forms_recaptcha'),
					'field_showhider' => 'mp_stacks_forms_google_recaptcha_showhider'		
	);
	$mp_stacks_forms_items_array[] = array(
				'field_id'			=> 'mp_stacks_forms_delay_showhider',
				'field_title' 	=> __( 'Submission Delay', 'mp_stacks_forms'),
				'field_description' 	=> '',
				'field_type' 	=> 'showhider',
				'field_value' => '',
				'field_showhider' => 'mp_stacks_forms_security_showhider'		
	);
	$mp_stacks_forms_items_array[] = array(
					'field_id'			=> 'mp_stacks_forms_submission_delay',
					'field_title' 	=> __( 'Submission Delay', 'mp_stacks_forms'),
					'field_description' 	=> __( 'Set the minimum number of seconds that must pass between form submissions by a single user.', 'mp_stacks_forms' ),
					'field_type' 	=> 'number',
					'field_value' => '20',
					'field_showhider' => 'mp_stacks_forms_delay_showhider'		
	);
	$mp_stacks_forms_items_array[] = array(
					'field_id'			=> 'mp_stacks_forms_submission_delay_message',
					'field_title' 	=> __( 'Message if the user hasn\'t waited long enough before submitting again.', 'mp_stacks_forms'),
					'field_description' 	=> __( 'What should the user read if they haven\'t waited long enough between submissions?', 'mp_stacks_forms' ),
					'field_type' 	=> 'textarea',
					'field_value' => __( 'You must wait 20 seconds between submissions.', 'mp_stacks_forms' ),
					'field_showhider' => 'mp_stacks_forms_delay_showhider'		
	);
	$mp_stacks_forms_items_array[] = array(
				'field_id'			=> 'mp_stacks_forms_max_subs_per_day_showhider',
				'field_title' 	=> __( 'Max Submissions Per Day', 'mp_stacks_forms'),
				'field_description' 	=> '',
				'field_type' 	=> 'showhider',
				'field_value' => '',
				'field_showhider' => 'mp_stacks_forms_security_showhider'		
	);
	$mp_stacks_forms_items_array[] = array(
					'field_id'			=> 'mp_stacks_forms_max_submissions_per_day',
					'field_title' 	=> __( 'Maximum Submissions Per Day', 'mp_stacks_forms'),
					'field_description' 	=> __( 'How many times can a single user submit the form every 24 hours?', 'mp_stacks_forms' ),
					'field_type' 	=> 'number',
					'field_value' => '10',
					'field_showhider' => 'mp_stacks_forms_max_subs_per_day_showhider'		
	);
	$mp_stacks_forms_items_array[] = array(
					'field_id'			=> 'mp_stacks_forms_max_submissions_message',
					'field_title' 	=> __( 'Message for Maximum Submissions Per Day', 'mp_stacks_forms'),
					'field_description' 	=> __( 'What should the user read when they reach the maximum number of submissions for the day?', 'mp_stacks_forms' ),
					'field_type' 	=> 'textarea',
					'field_value' => __( 'You have reached the maximum number of submissions for today. Try again in 24 hours.', 'mp_stacks_forms' ),
					'field_showhider' => 'mp_stacks_forms_max_subs_per_day_showhider'		
	);
	$mp_stacks_forms_items_array[] = array(
			'field_id'			=> 'mp_stacks_forms_submission_actions_showhider',
			'field_title' 	=> __( 'Form Submission Actions', 'mp_stacks_forms'),
			'field_description' 	=> '',
			'field_type' 	=> 'showhider',
			'field_value' => '',		
	);
	
	$post_submission_action_options = array(
		'email' => __( 'Send an email containing the form data', 'mp_stacks_forms' ),
		'redirect' => __( 'Redirect user to specific URL upon submission.', 'mp_stacks_forms' ),
	);
	
	//If we are in mp_stacks_forms advanced mode, allow WordPress post creation
	if ( mp_stacks_forms_mode() == 'advanced' ){
		$post_submission_action_options['create_wp_post'] = __( 'Create a Wordpress Post using the form data.', 'mp_stacks_forms' );
	}
				
	$mp_stacks_forms_items_array[] = array(
				'field_id'			=> 'mp_stacks_forms_action',
				'field_title' 	=> __( 'Form Submission Action', 'mp_stacks_forms'),
				'field_description' 	=> __( 'Select an action that should take place when this form is submitted.', 'mp_stacks_forms' ),
				'field_type' 	=> 'select',
				'field_select_values' => $post_submission_action_options,
				'field_value' => '',
				'field_showhider' => 'mp_stacks_forms_submission_actions_showhider',
				'field_repeater' => 'mp_stacks_forms_submission_actions'		
	);
	$mp_stacks_forms_items_array[] = array(
				'field_id'			=> 'mp_stacks_forms_emails',
				'field_title' 	=> __( '"Submit To" emails.', 'mp_stacks_forms'),
				'field_description' 	=> __( 'Enter the email addresses this form should be sent to when submitted. For multiple, separate with Commas.', 'mp_stacks_forms' ),
				'field_type' 	=> 'textarea',
				'field_placeholder' => __( 'email1@email.com, email2@email.com, email3@email.com' ),
				'field_value' => '',
				'field_showhider' => 'mp_stacks_forms_submission_actions_showhider',
				'field_conditional_id' => 'mp_stacks_forms_action',
				'field_conditional_values' => array('email'),
				'field_repeater' => 'mp_stacks_forms_submission_actions'		
	);
	$mp_stacks_forms_items_array[] = array(
				'field_id'			=> 'email_subject_line',
				'field_title' 	=> __( 'Email Subject Line', 'mp_stacks_forms'),
				'field_description' 	=> __( 'When the email is sent with the form data, what should the subject line be?', 'mp_stacks_forms' ),
				'field_type' 	=> 'textbox',
				'field_value' => __('Form submitted from', 'mp_stacks_forms') . ' ' . get_bloginfo( 'wpurl' ),
				'field_showhider' => 'mp_stacks_forms_submission_actions_showhider',
				'field_conditional_id' => 'mp_stacks_forms_action',
				'field_conditional_values' => array('email'),
				'field_repeater' => 'mp_stacks_forms_submission_actions'		
	);
	$mp_stacks_forms_items_array[] = array(
				'field_id'			=> 'mp_stacks_forms_redirect_url',
				'field_title' 	=> __( 'Redirect user upon submission', 'mp_stacks_forms'),
				'field_description' 	=> __( 'Enter the URL you\'d like to redirect the user to when they submit the form.', 'mp_stacks_forms' ),
				'field_type' 	=> 'textbox',
				'field_placeholder' => __( 'http://' ),
				'field_value' => '',
				'field_showhider' => 'mp_stacks_forms_submission_actions_showhider',
				'field_conditional_id' => 'mp_stacks_forms_action',
				'field_conditional_values' => array('redirect'),
				'field_repeater' => 'mp_stacks_forms_submission_actions'		
	);
	$mp_stacks_forms_items_array[] = array(
				'field_id'			=> 'email_subject_line',
				'field_title' 	=> __( 'Email Subject Line', 'mp_stacks_forms'),
				'field_description' 	=> __( 'When the email is sent with the form data, what should the subject line be?', 'mp_stacks_forms' ),
				'field_type' 	=> 'textbox',
				'field_value' => __('Form submitted from', 'mp_stacks_forms') . ' ' . get_bloginfo( 'wpurl' ),
				'field_showhider' => 'mp_stacks_forms_submission_actions_showhider',
				'field_conditional_id' => 'mp_stacks_forms_action',
				'field_conditional_values' => array('email'),
				'field_repeater' => 'mp_stacks_forms_submission_actions'		
	);
	
	//If we are in mp_stacks_forms advanced mode, allow WordPress post creation
	if ( mp_stacks_forms_mode() == 'advanced' ){
		
		$mp_stacks_forms_items_array[] = array(
					'field_id'			=> 'wppost_post_type',
					'field_title' 	=> __( 'Select Post Type to create upon form submission:', 'mp_stacks_forms'),
					'field_description' 	=> __( 'Select the post type you\'d like to have created when this form is submitted:', 'mp_stacks_forms' ),
					'field_type' 	=> 'select',
					'field_value' => '',
					'field_select_values' => $post_types_array,
					'field_showhider' => 'mp_stacks_forms_submission_actions_showhider',
					'field_conditional_id' => 'mp_stacks_forms_action',
					'field_conditional_values' => array('create_wp_post'),
					'field_repeater' => 'mp_stacks_forms_submission_actions'		
		);
		$mp_stacks_forms_items_array[] = array(
					'field_id'			=> 'wppost_post_status',
					'field_title' 	=> __( 'WP Post Status:', 'mp_stacks_forms'),
					'field_description' 	=> __( 'What should the post status of the new post be?', 'mp_stacks_forms' ),
					'field_type' 	=> 'select',
					'field_value' => '',
					'field_select_values' => array( 
						'draft' => __( 'Draft', 'mp_stacks_forms' ),
						'publish' => __( 'Published (Be careful. This will make user-submitted posts live.)', 'mp_stacks_forms' ),
					),
					'field_showhider' => 'mp_stacks_forms_submission_actions_showhider',
					'field_conditional_id' => 'mp_stacks_forms_action',
					'field_conditional_values' => array('create_wp_post'),
					'field_repeater' => 'mp_stacks_forms_submission_actions'		
		);
	}
		$mp_stacks_forms_items_array[] = array(
				'field_id'			=> 'mp_stacks_forms_submission_messages',
				'field_title' 	=> __( 'Form Submission Message', 'mp_stacks_forms'),
				'field_description' 	=> '',
				'field_type' 	=> 'showhider',
				'field_value' => '',		
		);
		$mp_stacks_forms_items_array[] = array(
					'field_id'			=> 'mp_stacks_forms_submission_success_message',
					'field_title' 	=> __( 'Success Message', 'mp_stacks_forms'),
					'field_description' 	=> __( 'When the form is successfully submitted, what should the user read?', 'mp_stacks_forms' ),
					'field_type' 	=> 'textarea',
					'field_value' => __( 'Thanks for your entry. The form was successfully submitted!', 'mp_stacks_forms' ),
					'field_showhider' => 'mp_stacks_forms_submission_messages',		
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
add_action('mp_brick_ajax_metabox', 'mp_stacks_forms_create_meta_box');
add_action('wp_ajax_mp_stacks_forms_metabox_content', 'mp_stacks_forms_create_meta_box');