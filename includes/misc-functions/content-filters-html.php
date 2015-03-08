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
	
	//If the url contains the "mp_stacks_form" variable
	if ( isset( $_GET['mp_stacks_form'] ) && $_GET['mp_stacks_form'] == 'submitted' ){
		
		//Submit and process the form
		$form_response = mp_stacks_forms_processs_form( $post_id );
		
		//If there were errors in submitting the form
		if ( isset( $form_response['error'] ) ){
			
			return '<div id="mp-stacks-forms-container-' . $post_id .'" class="mp-stacks-forms-container"><div class="mp-stacks-forms-message">' . $form_response['error'] . '</div></div>';
				
		}
		//If the form was successfully submitted
		else{
			return '<div id="mp-stacks-forms-container-' . $post_id .'" class="mp-stacks-forms-container"><div class="mp-stacks-forms-message">' . $form_response['success'] . '</div></div>';
		}
			
	}
	
	//If the form hasn't just been submitted, output the form for the user to fill out
	$form_fields = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_fields', array() );
	
	$field_counter = 1;
	
	ob_start(); ?>
	
    <div id="mp-stacks-forms-container-<?php echo $post_id; ?>" class="mp-stacks-forms-container">
        
        <ul id="mp-stacks-form-fields-<?php echo $post_id; ?>" class="mp-stacks-form-fields">
            
            <form id="mp-stacks-form-<?php echo $post_id; ?>" name="mp-stacks-form-<?php echo $post_id; ?>" enctype="multipart/form-data" method="POST" action="?mp_stacks_form=submitted">
            
				<?php //Output the hidden field which will tell us when an mp_stacks_form has just been submitted ?>
                
                <input type="text" name="mp_stacks_form" value="<?php echo $post_id; ?>" class="mp-stacks-forms-hidden" />
                
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
						'field_placement' => 'table',
						'field_taxonomy' => NULL,
                    );
                    $form_field = wp_parse_args( $form_field, $default_form_field );
                    
                    //Convert the field_select_values from comma separated list to array
                    $form_field['field_select_values'] = explode( ',', str_replace( ', ', ',', $form_field['field_select_values'] ) );
                    
                    //Output the field html
                    ?>
                    <li id="mp-stacks-forms-field-<?php echo $post_id; ?>-<?php echo $field_counter; ?>-li" class="mp-stacks-form-field" style="width:<?php echo $form_field['field_width']; ?>%; display:<?php echo $form_field['field_placement']; ?>;">
                    <?php if ( !empty( $form_field['field_title'] ) ){ ?>
                        <div class="mp-stacks-forms-field-title"><?php echo $form_field['field_title']; ?></div>
                    <?php } ?>
                    <?php if ( !empty( $form_field['field_description'] ) ){ ?>
                        <div class="mp-stacks-forms-field-description"><?php echo $form_field['field_description']; ?></div>
                    <?php } ?>
                    
                        <div class="mp-stacks-forms-field-container">
                            
                            <?php 
                            if ( isset( $form_field['field_type'] ) && !empty( $form_field['field_type'] ) ){
                                
                                $args = array(
                                    'field_placeholder' => $form_field['field_placeholder'],
                                    'field_select_values' => $form_field['field_select_values'],
									'field_taxonomy' => $form_field['field_taxonomy']
                                );
                                
                                //Get the name of the function to show the right input field type
                                $input_field_function_name = 'mp_stacks_forms_' . $form_field['field_type'] . '_field';
                                
                                //Show the input field
                                echo $input_field_function_name( $post_id, $field_counter, $args ); 
                                
                                //Output a hidden field which tells us title of this field
                                echo '<input type="text" class="mp-stacks-forms-hidden" name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '-field-title" value="' . $form_field['field_title'] . '" />';
								
								//Output a hidden field which tells us the type of field this is
                                echo '<input type="text" class="mp-stacks-forms-hidden" name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '-field-type" value="' . $form_field['field_type'] . '" />';
								
								//If this field is a taxonomy selector, output the taxonomy name
								if ( !empty( $form_field['field_taxonomy']  ) ){
									//Output a hidden field which tells us the preset value this field had
									echo '<input type="text" class="mp-stacks-forms-hidden" name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '-field-tax" value="' . $form_field['field_taxonomy'] . '" />';
								}
								
								//Output a hidden field which tells us the meta key to use when saving a new WP post.
                                echo '<input type="text" class="mp-stacks-forms-hidden" name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '-field-meta-key" value="' . $form_field['field_meta_key'] . '" />';
                            }
                            ?>
                            
                        </div>
                    </li>
                    <?php 
                    
                    //Increment the field counter
                    $field_counter = $field_counter + 1;
                    
                } 
				
				//Should we use the Google Recaptcha field?
				$google_recatpcha = mp_core_get_post_meta_checkbox( $post_id, 'mp_stacks_forms_recaptcha', false );
				
				if ( $google_recatpcha ){
					wp_enqueue_script( 'mp_stacks_forms_google_recaptcha', 'https://www.google.com/recaptcha/api.js', array('jquery') );
					
					echo '<li id="mp-stacks-' . $post_id . '-forms-google-recaptcha" class="mp-stacks-forms-google-recaptcha mp-stacks-form-field">';
						echo '<div class="g-recaptcha" data-sitekey="' . mp_core_get_post_meta_checkbox( $post_id, 'mp_stacks_forms_recaptcha_site_key' ) . '"></div>';
					echo '</li>';
				}	
				
				//Get the text to display on the submit button
				$submit_button_text = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_submit_button_text', __( 'Submit', 'mp_stacks_forms' ) );
				?>
                <li id="mp-stacks-forms-google-recaptcha" class="mp-stacks-form-field mp-stacks-form-field-submit">
                	<input type="submit" class="button mp-stacks-forms-submit-btn" value="<?php echo $submit_button_text; ?>" />
                </li>
        	</form>
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
	);
	
	$args = wp_parse_args( $args, $default_args );
	
	return '<input name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '" id="mp-stacks-forms-input-' . $post_id . '-' . $field_counter . '" type="text" value="' . $args['preset_value'] . '" class="mp-stacks-forms-field-textbox" tabindex="'  . $field_counter . '" placeholder="' . $args['field_placeholder'] . '"  />';
	
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
	);
	
	$args = wp_parse_args( $args, $default_args );
	
	return '<textarea name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '" class=".mp-stacks-forms-field-textarea" tabindex="' . $field_counter . '" rows="10" cols="50"  placeholder="' . $args['field_placeholder'] . '"></textarea>';
	
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
	);
	
	$args = wp_parse_args( $args, $default_args );
	
	return '<input name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '" id="mp-stacks-forms-input-' . $post_id . '-' . $field_counter . '" type="email" value="' . $args['preset_value'] . '" class=" .mp-stacks-forms-field-email" tabindex="'  . $field_counter . '" placeholder="' . $args['field_placeholder'] . '"  />';
	
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
	);
	
	$args = wp_parse_args( $args, $default_args );
	
	return '<input name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '" id="mp-stacks-forms-input-' . $post_id . '-' . $field_counter . '" type="color" value="' . $args['preset_value'] . '" class="mp-stacks-forms-field-color" tabindex="'  . $field_counter . '" placeholder="' . $args['field_placeholder'] . '"  />';
	
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
	);
	
	$args = wp_parse_args( $args, $default_args );
	
	return '<input name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '" id="mp-stacks-forms-input-' . $post_id . '-' . $field_counter . '" type="date" value="' . $args['preset_value'] . '" class="mp-stacks-forms-field-date" tabindex="'  . $field_counter . '" placeholder="' . $args['field_placeholder'] . '"  />';
	
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
	);
	
	$args = wp_parse_args( $args, $default_args );
	
	return '<input name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '" id="mp-stacks-forms-input-' . $post_id . '-' . $field_counter . '" type="url" value="' . $args['preset_value'] . '" class="mp-stacks-forms-field-url" tabindex="'  . $field_counter . '" placeholder="' . $args['field_placeholder'] . '"  />';
	
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
	);
	
	$args = wp_parse_args( $args, $default_args );
	
	$return_html = '<select name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '" id="mp-stacks-forms-input-' . $post_id . '-' . $field_counter . '" value="' . $args['preset_value'] . '" class="mp-stacks-forms-field-select" tabindex="'  . $field_counter . '" placeholder="' . $args['field_placeholder'] . '"  />';
		
		//Loop through each option in this dropdown
		foreach ( $args['field_select_values'] as $field_select_value => $field_select_visual ){
			$return_html .= '<option value="' . $field_select_value . '">' . $field_select_visual . '</option>';
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
	);
	
	$args = wp_parse_args( $args, $default_args );
		
		//Set default for the return HTML
		$return_html = NULL;
		
		//Loop through each option in this dropdown
		foreach ( $args['field_select_values'] as $field_select_value ){
			
			
			$return_html .= '<div class="mp-stacks-forms-checkbox-container">';
			$return_html .= '<input name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '[]" id="mp-stacks-forms-input-' . $post_id . '-' . $field_counter . '" type="checkbox" value="' . $field_select_value . '" class="mp-stacks-forms-field-checkbox" tabindex="'  . $field_counter . '" placeholder="' . $args['field_placeholder'] . '" ><div class="mp-stacks-forms-field-description">' . $field_select_value . '</div>';
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
	);
	
	$args = wp_parse_args( $args, $default_args );
		
		//Set default for the return HTML
		$return_html = NULL;
		
		//Loop through each option in this dropdown
		foreach ( $args['field_select_values'] as $field_select_value ){
			
			
			$return_html .= '<div class="mp-stacks-forms-radio-container">';
			$return_html .= '<input name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '" id="mp-stacks-forms-input-' . $post_id . '-' . $field_counter . '" type="radio" value="' . $field_select_value . '" class="mp-stacks-forms-field-radio" tabindex="'  . $field_counter . '" placeholder="' . $args['field_placeholder'] . '" ><div class="mp-stacks-forms-field-description">' . $field_select_value . '</div>';
			$return_html .= '</div>';

		}
	
	
	return $return_html;
	
}

/**
 * This function returns the HTML for a fileupload field
 *
 * @access   public
 * @since    1.0.0
 * @param    int $post_id The id of the brick this field is being used for.
 * @param    int $field_counter The field number this field is in the form.
 * @return   string The HTML for the text field.
 */
function mp_stacks_forms_fileupload_field( $post_id, $field_counter, $args = array() ){
	
	$default_args = array(
		'preset_value' => NULL,
		'field_placeholder' => NULL,
	);
	
	$args = wp_parse_args( $args, $default_args );
	
	return '<input name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '" id="mp-stacks-forms-input-' . $post_id . '-' . $field_counter . '" type="file" value="' . $args['preset_value'] . '" class="mp-stacks-forms-field-fileupload" tabindex="'  . $field_counter . '" placeholder="' . $args['field_placeholder'] . '"  />';
	
}

/**
 * This function returns the HTML for a fileupload field
 *
 * @access   public
 * @since    1.0.0
 * @param    int $post_id The id of the brick this field is being used for.
 * @param    int $field_counter The field number this field is in the form.
 * @return   string The HTML for the text field.
 */
function mp_stacks_forms_taxonomy_field( $post_id, $field_counter, $args = array() ){
	
	$default_args = array(
		'preset_value' => NULL,
		'field_placeholder' => NULL,
		'field_taxonomy' => NULL
	);
	
	$return_html = NULL;
	
	$args = wp_parse_args( $args, $default_args );
		
	$tax_terms = mp_core_get_all_terms_by_tax( $args['field_taxonomy'] );
	
	foreach( $tax_terms as $tax_term_id => $tax_term_name){
		
		$return_html .= '<div class="mp-stacks-forms-checkbox-container">';
		$return_html .= '<input name="mp-stacks-forms-input-' .  $post_id . '-' . $field_counter . '[]" id="mp-stacks-forms-input-' . $post_id . '-' . $field_counter . '" type="checkbox" value="' . $tax_term_id . '" class="mp-stacks-forms-field-checkbox" tabindex="'  . $field_counter . '" placeholder="' . $args['field_placeholder'] . '" ><div class="mp-stacks-forms-field-description">' . $tax_term_name . '</div>';
		$return_html .= '</div>';
			
	}
	
	return $return_html;
	
}