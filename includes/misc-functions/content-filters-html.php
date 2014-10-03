<?php 
/**
 * This file contains the function which hooks to a brick's HTML content output
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
 * This function hooks to the brick output. If it is supposed to be a 'forms', then it will output the forms
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_brick_content_output_forms($default_content_output, $mp_stacks_content_type, $post_id){
	
	//If this stack content type isn't set to be an forms	
	if ($mp_stacks_content_type != 'forms'){
		return $default_content_output; 	
	}
	
	$form_fields = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_fields', array() );
	
	$field_counter = 1;
	
	ob_start(); ?>
	
    <div id="mp-stacks-forms-container-<?php echo $post_id; ?>" class="mp-stacks-forms-container">
        
        <ul id="mp-stacks-form-fields-<?php echo $post_id; ?>" class="mp-stacks-form-fields">
            
            <?php 
			//Loop through each field in this form
			foreach( $form_fields as $form_field ) {
                
				//Set the default form field data
				$default_form_field = array( 
					'field_title' => NULL,
					'field_description' => NULL,
					'field_type' => 'textbox',
					'field_width' => 50,
					'field_placeholder' => NULL,
					'field_select_values' => NULL,
				);
				$form_field = wp_parse_args( $form_field, $default_form_field );
				
				//Convert the field_select_values from comma separated list to array
				$form_field['field_select_values'] = explode( ',', str_replace( ', ', ',', $form_field['field_select_values'] ) );
				
				//Output the field html
                ?>
                <li id="mp-stacks-forms-field-<?php echo $post_id; ?>-<?php echo $field_counter; ?>" class="mp-stacks-form-field">
                	<label class="mp-stacks-forms-field-label" for="mp-stacks-forms-input-<?php echo $post_id; ?>-<?php echo $field_counter; ?>"><?php echo $form_field['field_title']; ?></label>
                    <div class="mp-stacks-forms-field-description"><?php echo $form_field['field_description']; ?></div>
                    <div class="mp-stacks-forms-field-container">
                    	
						<?php 
						if ( isset( $form_field['field_type'] ) && !empty( $form_field['field_type'] ) ){
							
							$args = array(
								'field_placeholder' => $form_field['field_placeholder'],
								'field_width' => $form_field['field_width'],
								'field_select_values' => $form_field['field_select_values']
							);
							
							//Get the name of the function to show the right input field type
							$input_field_function_name = 'mp_stacks_forms_' . $form_field['field_type'] . '_field';
							
							//Show the input field
							echo $input_field_function_name( $post_id, $field_counter, $args ); 
						}
						?>
                        
                    </div>
                </li>
                <?php 
				
				//Increment the field counter
				$field_counter = $field_counter + 1;
                
            } ?>
        
        </ul>
     
    </div>
    
    <?php
	
	//Return
	return ob_get_clean();
	
}
add_filter('mp_stacks_brick_content_output', 'mp_stacks_brick_content_output_forms', 10, 3);

/**
 * This function returns the HTML for a text field
 *
 * @access   public
 * @since    1.0.0
 * @param    int $post_id The id of the brick this field is being used for.
 * @param    int $field_counter The field number this field is in the form.
 * @return   string The HTML for the text field.
 */
function mp_stacks_forms_textbox_field( $post_id, $field_counter, $args = array() ){
	
	$default_args = array(
		'preset_value' => NULL,
		'field_placeholder' => NULL,
		'field_width' => NULL
	);
	
	$args = wp_parse_args( $args, $default_args );
	
	return '<input name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '" id="mp-stacks-forms-input-' . $post_id . '-' . $field_counter . '" type="text" value="' . $args['preset_value'] . '" class="mp-stacks-forms-field-textbox" tabindex="'  . $field_counter . '" placeholder="' . $args['field_placeholder'] . '" style="width:' . $args['field_width'] . '%">';
	
}

/**
 * This function returns the HTML for a text field
 *
 * @access   public
 * @since    1.0.0
 * @param    int $post_id The id of the brick this field is being used for.
 * @param    int $field_counter The field number this field is in the form.
 * @return   string The HTML for the text field.
 */
function mp_stacks_forms_textarea_field( $post_id, $field_counter, $args = array() ){
	
	$default_args = array(
		'preset_value' => NULL,
		'field_placeholder' => NULL,
		'field_width' => NULL
	);
	
	$args = wp_parse_args( $args, $default_args );
	
	return '<textarea name="mp_stacks_forms_input_' .  $post_id . '_' . $field_counter . '" class=".mp-stacks-forms-field-textarea" tabindex="' . $field_counter . '" rows="10" cols="50" style="width:' . $args['field_width'] . '%" placeholder="' . $args['field_placeholder'] . '"></textarea>';
	
}

/**
 * This function returns the HTML for an email field
 *
 * @access   public
 * @since    1.0.0
 * @param    int $post_id The id of the brick this field is being used for.
 * @param    int $field_counter The field number this field is in the form.
 * @return   string The HTML for the text field.
 */
function mp_stacks_forms_email_field( $post_id, $field_counter, $args = array() ){
	
	$default_args = array(
		'preset_value' => NULL,
		'field_placeholder' => NULL,
		'field_width' => NULL
	);
	
	$args = wp_parse_args( $args, $default_args );
	
	return '<input name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '" id="mp-stacks-forms-input-' . $post_id . '-' . $field_counter . '" type="email" value="' . $args['preset_value'] . '" class=" .mp-stacks-forms-field-email" tabindex="'  . $field_counter . '" placeholder="' . $args['field_placeholder'] . '" style="width:' . $args['field_width'] . '%">';
	
}

/**
 * This function returns the HTML for an email field
 *
 * @access   public
 * @since    1.0.0
 * @param    int $post_id The id of the brick this field is being used for.
 * @param    int $field_counter The field number this field is in the form.
 * @return   string The HTML for the text field.
 */
function mp_stacks_forms_color_field( $post_id, $field_counter, $args = array() ){
	
	$default_args = array(
		'preset_value' => NULL,
		'field_placeholder' => NULL,
		'field_width' => NULL
	);
	
	$args = wp_parse_args( $args, $default_args );
	
	return '<input name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '" id="mp-stacks-forms-input-' . $post_id . '-' . $field_counter . '" type="color" value="' . $args['preset_value'] . '" class="mp-stacks-forms-field-color" tabindex="'  . $field_counter . '" placeholder="' . $args['field_placeholder'] . '" style="width:' . $args['field_width'] . '%">';
	
}

/**
 * This function returns the HTML for a date field
 *
 * @access   public
 * @since    1.0.0
 * @param    int $post_id The id of the brick this field is being used for.
 * @param    int $field_counter The field number this field is in the form.
 * @return   string The HTML for the text field.
 */
function mp_stacks_forms_date_field( $post_id, $field_counter, $args = array() ){
	
	$default_args = array(
		'preset_value' => NULL,
		'field_placeholder' => NULL,
		'field_width' => NULL
	);
	
	$args = wp_parse_args( $args, $default_args );
	
	return '<input name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '" id="mp-stacks-forms-input-' . $post_id . '-' . $field_counter . '" type="date" value="' . $args['preset_value'] . '" class="mp-stacks-forms-field-date" tabindex="'  . $field_counter . '" placeholder="' . $args['field_placeholder'] . '" style="width:' . $args['field_width'] . '%">';
	
}

/**
 * This function returns the HTML for a url field
 *
 * @access   public
 * @since    1.0.0
 * @param    int $post_id The id of the brick this field is being used for.
 * @param    int $field_counter The field number this field is in the form.
 * @return   string The HTML for the text field.
 */
function mp_stacks_forms_url_field( $post_id, $field_counter, $args = array() ){
	
	$default_args = array(
		'preset_value' => NULL,
		'field_placeholder' => NULL,
		'field_width' => NULL
	);
	
	$args = wp_parse_args( $args, $default_args );
	
	return '<input name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '" id="mp-stacks-forms-input-' . $post_id . '-' . $field_counter . '" type="url" value="' . $args['preset_value'] . '" class="mp-stacks-forms-field-url" tabindex="'  . $field_counter . '" placeholder="' . $args['field_placeholder'] . '" style="width:' . $args['field_width'] . '%">';
	
}

/**
 * This function returns the HTML for a select field
 *
 * @access   public
 * @since    1.0.0
 * @param    int $post_id The id of the brick this field is being used for.
 * @param    int $field_counter The field number this field is in the form.
 * @return   string The HTML for the text field.
 */
function mp_stacks_forms_select_field( $post_id, $field_counter, $args = array() ){
	
	$default_args = array(
		'field_select_values' => array(),
		'preset_value' => NULL,
		'field_placeholder' => NULL,
		'field_width' => NULL
	);
	
	$args = wp_parse_args( $args, $default_args );
	
	$return_html = '<select name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '" id="mp-stacks-forms-input-' . $post_id . '-' . $field_counter . '" value="' . $args['preset_value'] . '" class="mp-stacks-forms-field-select" tabindex="'  . $field_counter . '" placeholder="' . $args['field_placeholder'] . '" style="width:' . $args['field_width'] . '%">';
		
		//Loop through each option in this dropdown
		foreach ( $args['field_select_values'] as $field_select_value ){
			$return_html .= '<option value="' . $field_select_value . '">' . $field_select_value . '</option>';
		}
	
	$return_html .= '</select>';
	
	return $return_html;
	
}

/**
 * This function returns the HTML for some checkbox fields
 *
 * @access   public
 * @since    1.0.0
 * @param    int $post_id The id of the brick this field is being used for.
 * @param    int $field_counter The field number this field is in the form.
 * @return   string The HTML for the text field.
 */
function mp_stacks_forms_checkbox_field( $post_id, $field_counter, $args = array() ){
	
	$default_args = array(
		'field_select_values' => array(),
		'preset_value' => NULL,
		'field_placeholder' => NULL,
		'field_width' => NULL
	);
	
	$args = wp_parse_args( $args, $default_args );
		
		//Set default for the return HTML
		$return_html = NULL;
		
		//Loop through each option in this dropdown
		foreach ( $args['field_select_values'] as $field_select_value ){
			
			
			$return_html .= '<div class="mp-stacks-forms-checkbox-container">';
			$return_html .= '<input name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '" id="mp-stacks-forms-input-' . $post_id . '-' . $field_counter . '" type="checkbox" value="' . $field_select_value . '" class="mp-stacks-forms-field-checkbox" tabindex="'  . $field_counter . '" placeholder="' . $args['field_placeholder'] . '" style="width:' . $args['field_width'] . '%"><div class="mp-stacks-forms-field-description">' . $field_select_value . '</div>';
			$return_html .= '</div>';

		}
	
	
	return $return_html;
	
}

/**
 * This function returns the HTML for some radio fields
 *
 * @access   public
 * @since    1.0.0
 * @param    int $post_id The id of the brick this field is being used for.
 * @param    int $field_counter The field number this field is in the form.
 * @return   string The HTML for the text field.
 */
function mp_stacks_forms_radio_field( $post_id, $field_counter, $args = array() ){
	
	$default_args = array(
		'field_select_values' => array(),
		'preset_value' => NULL,
		'field_placeholder' => NULL,
		'field_width' => NULL
	);
	
	$args = wp_parse_args( $args, $default_args );
		
		//Set default for the return HTML
		$return_html = NULL;
		
		//Loop through each option in this dropdown
		foreach ( $args['field_select_values'] as $field_select_value ){
			
			
			$return_html .= '<div class="mp-stacks-forms-radio-container">';
			$return_html .= '<input name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '" id="mp-stacks-forms-input-' . $post_id . '-' . $field_counter . '" type="radio" value="' . $field_select_value . '" class="mp-stacks-forms-field-radio" tabindex="'  . $field_counter . '" placeholder="' . $args['field_placeholder'] . '" style="width:' . $args['field_width'] . '%"><div class="mp-stacks-forms-field-description">' . $field_select_value . '</div>';
			$return_html .= '</div>';

		}
	
	
	return $return_html;
	
}