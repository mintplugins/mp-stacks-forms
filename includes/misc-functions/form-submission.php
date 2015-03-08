<?php
/**
 * This file contains the function which fires when the form is submitted
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
 * This function processes a submitted form and can be initiated by either ajax or by normal page reloads.
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @param    string $post_id The id of the Brick in which this form originates.
 * @return   void
 */
function mp_stacks_forms_processs_form( $post_id ){
	
	//Check if we should do google recaptcha
	$use_recaptcha = mp_core_get_post_meta_checkbox( $post_id, 'mp_stacks_forms_recaptcha', false );
	
	//If we are supposed to be using recaptcha
	if ( $use_recaptcha ){
				
		//Get the user's recaptcha secret key
		$recaptcha_secret = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_recaptcha_secret_key', false );
		
		if ( !$recaptcha_secret ){
			return array( 'error' => __( 'No secret key was entered', 'mp_stacks_forms' ) );	
		}
		
		// Was there a reCAPTCHA response from the form?
		if ( $_POST["g-recaptcha-response"] ) {
			$resp = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', array(
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => array( 'secret' => $recaptcha_secret, 'response' => $_POST["g-recaptcha-response"], 'remoteip' => $_SERVER["REMOTE_ADDR"] ),
				'cookies' => array()
				)
			);
		
			$response = json_decode( wp_remote_retrieve_body( $resp ), true );
			
			//If there were errors coming back from Google
			if ( isset( $response['error-codes'] ) ){
				return array( 'error' => $response['error-codes'] );
			}
			
		}
		//If the Captcha was empty
		else{
			return array( 'error' => __( 'The Captcha was empty!', 'mp_stacks_forms' ) );	
		}
		
	}
	
	//Get the actions we should take when this form is submitted
	$mp_stacks_forms_submission_actions = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_submission_actions' );
	
	//If no submission actions have been set, get out of here.
	if ( empty( $mp_stacks_forms_submission_actions ) || !is_array( $mp_stacks_forms_submission_actions ) ){
		return array( 'error' => __( 'No submission actions have been set up', 'mp_stacks_forms' ) );	
	}
	
	//First lets handle any file uploads. Here, we loop through any file uploads.
	$uploaded_files = NULL;
	foreach( $_FILES as $key => $file_upload ){
	
		//Include the file upload script if it isn;t already loaded
		if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );
		
		//Get the uploaded file so we can pass it to WordPress file upload function
		$uploadedfile = $_FILES[$key];
		
		//For the upload to actually happen, test_form must be set to false.
		$upload_overrides = array( 'test_form' => false );
		$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
		
		//If the file was successfully uploaded
		if ( !isset( $movefile['upload_error_handler'] ) && !isset( $movefile['error'] ) ) {
			
			//Lets add it to the WordPress media library
			$wp_upload_dir = wp_upload_dir();
			$wp_filetype = wp_check_filetype(basename($movefile['file']), null );
			$filepath = $movefile['file'];

			$attachment = array(
				'guid' => $wp_upload_dir['url'] . '/' . basename( $filepath ), 
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filepath ) ),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			
			$attach_id = wp_insert_attachment( $attachment, $filepath, 37 );
			
			//Store the attachment id in an array so we can use the file attachment later on
			$uploaded_files[$key] = array(
				'meta_key' => $_POST[$key . '-field-meta-key'],
				'attachment_id' => $attach_id,
				'file_path' => $movefile['file'],
				'file_url' => $movefile['url'],
				'file_type' => $movefile['type'],
			);
			
			//If this is an image, we want to generate the thumbnails for it:
			if ( $wp_filetype['type'] == 'image/jpeg' || $wp_filetype['type'] == 'image/png' ){
				// we must first include the image.php file
				// for the function wp_generate_attachment_metadata() to work
				if ( ! function_exists( 'wp_crop_image' ) ) require_once( ABSPATH . 'wp-admin/includes/image.php' );
				
				//Create the thumbnail sizes for this new image
				$attach_data = wp_generate_attachment_metadata( $attach_id, $filepath );
				wp_update_attachment_metadata( $attach_id, $attach_data );
			}

		} 
		
	}
				
	//Loop through each action we want to take when submitting this form
	foreach( $mp_stacks_forms_submission_actions as $mp_stacks_forms_submission_actions ){
		
		//If this action is to send an email continaing the form data
		if ( $mp_stacks_forms_submission_actions['mp_stacks_forms_action'] == 'email' ){
			
			$body = __( 'The form located at', 'mp_stacks_forms' ) . ' ' . mp_core_get_current_url() . ' ' . __( 'has been submitted. Here is the data:', 'mp_stacks_forms' ) . "\r\n";
									
			//Formulate all the form data into a string we can send as an email
			//Loop through each field in the form
			foreach( $_POST as $key => $value ){
					
				//If this is the field which holds the post id, don't do anything
				if ( strpos( $key, 'mp-stacks-forms-input-' . $post_id ) === false ){
					continue;	
				}
				
				//Check if this is one of the fields we skip
				$field_title = strpos( $key, 'field-title' );
				$field_meta_key = strpos( $key, 'field-meta-key' );
				$field_type = strpos( $key, 'field-type' );
				$field_tax = strpos( $key, 'field-tax' );
									
				//If this field is not the "field type" field ( the fields which tell us the type of field) And it isn't the field which tells us the meta key to use.
				if ( $field_title === false && $field_meta_key === false && $field_type === false && $field_tax === false){
				
					//Filter the value according to the field type and context
					$value = apply_filters( 'mp_stacks_form_value', $value, mp_stacks_forms_sanitize_form_value( $_POST[$key . '-field-type'] ), 'email');
					
					//If the POST value is an array, it's probably multiple checkboxes
					if ( is_array( $value ) ){
						//Loop through each array value
						foreach( $value as $single_value ){
							
							$single_value = empty( $single_value ) ? __( 'No value entered', 'mp_stacks_forms' ) : $single_value;
							
							$single_value = mp_stacks_forms_sanitize_form_value( $single_value );
							
							//Add this field's Title: Value to the body of the email
							$body .= isset( $_POST[$key . '-field-title'] ) ? "\r\n \r\n " . mp_stacks_forms_sanitize_form_value( $_POST[$key . '-field-title'] ) . ": \r\n" . $single_value : NULL;	
						}
					}
					//If the POST value is not an array
					else{
						
						$value = empty( $value ) ? __( 'No value entered', 'mp_stacks_forms' ) : $value;
						$value = mp_stacks_forms_sanitize_form_value( $value );
						
						//Add this field's Title: Value to the body of the email
						$body .= isset( $_POST[$key . '-field-title'] ) ? "\r\n \r\n" . mp_stacks_forms_sanitize_form_value( $_POST[$key . '-field-title'] ) . ": \r\n" . $value : NULL;
					}
				
				}
					
			} 
						
			//Strip White Space out of the list of email addresses we want to send this form to
			$mp_stacks_forms_emails = preg_replace('/\s+/', '', $mp_stacks_forms_submission_actions['mp_stacks_forms_emails'] );
			
			//Explode emails into array using commas
			$mp_stacks_forms_emails = explode( ',', $mp_stacks_forms_submission_actions['mp_stacks_forms_emails'] );
			
			//Loop through all the uploaded files and list their URLs
			
			if ( is_array( $uploaded_files ) ){
				
				$body .= "\r\n \r\n" . __( 'Uploaded Files:', 'mp_stacks_forms' ) . "\r\n";
				
				foreach( $uploaded_files as $uploaded_file ){
				
					$body .= $uploaded_file['file_url'] . "\r\n";
					
				}
			}
								
			//Send the form to each email address.
			wp_mail( $mp_stacks_forms_emails, $mp_stacks_forms_submission_actions['email_subject_line'] , $body );
								
		}
		//If this action is to create a WP Post
		else if ( $mp_stacks_forms_submission_actions['mp_stacks_forms_action'] == 'create_wp_post' ){
			
			//Create a new mp_stacks_forms custom post
			$new_mp_stacks_form_post = array(
			  'post_author'    => 1,
			  'post_name'      => 'temp', // The name (slug) for your post
			  'post_title'     => 'temp', // The title of your post.
			  'post_status'    => $mp_stacks_forms_submission_actions['wppost_post_status'], // Default 'draft'.
			  'post_type'      => $mp_stacks_forms_submission_actions['wppost_post_type'],// Default 'post'.
			); 	
			
			//Create a wp post for this socialgrid item	
			$new_post_id = wp_insert_post( $new_mp_stacks_form_post );
			
			//Loop through each field in the form
			foreach( $_POST as $key => $value ){
				
				//If this is the field which holds the post id, don't do anything
				if ( strpos( $key, 'mp-stacks-forms-input-' . $post_id ) === false ){
					continue;	
				}
				
				//Check if this is one of the fields we skip
				$field_title = strpos( $key, 'field-title' );
				$field_meta_key = strpos( $key, 'field-meta-key' );
				$field_type = strpos( $key, 'field-type' );
				$field_tax = strpos( $key, 'field-tax' );
									
				//If this field is not the "field type" field ( the fields which tell us the type of field) And it isn't the field which tells us the meta key to use.
				if ( $field_title === false && $field_meta_key === false && $field_type === false && $field_tax === false){
														
					//If this field should be used for the Post Title
					if ( $_POST[$key . '-field-meta-key'] == 'post_title' ){
						
						$new_mp_stacks_form_post = array(
						  'ID'             => $new_post_id,
						  'post_name'      => sanitize_title( $value ), // The name (slug) for your post
						  'post_title'     => sanitize_text_field( $value ), // The title of your post.
						); 	
						wp_update_post( $new_mp_stacks_form_post );
					}
					
					//If this field should be used for the Post Date
					else if ( $_POST[$key . '-field-meta-key'] == 'post_date' ){
							
						$date = new DateTime( mp_stacks_forms_sanitize_form_value( $value ) );	
										
						$new_mp_stacks_form_post = array(
						  'ID'             => $new_post_id,
						  'post_date'      => date_format($date, 'Y-m-d H:i:s'), // The name (slug) for your post
						); 	
						wp_update_post( $new_mp_stacks_form_post );
		
					}
					
					//If this field should be used for the Post Content
					else if ( $_POST[$key . '-field-meta-key'] == 'post_content' ){
						$new_mp_stacks_form_post = array(
						  'ID'             => $new_post_id,
						  'post_content'      => mp_stacks_forms_sanitize_form_value( $value ), // The name (slug) for your post
						); 	
						wp_update_post( $new_mp_stacks_form_post );
					}
					
					//If this field should be used to set the taxonomy terms for the post
					else if ( $_POST[$key . '-field-type'] == 'taxonomy' ){
						
						$tax_term_array = array();
						
						//Loop through each category the user selected
						foreach( $value as $tax_term ){
							
							//Add that category to the list of categories for this post
							array_push($tax_term_array, $tax_term);
								
						}
						
						//Set the categories for this post
						wp_set_post_terms( $new_post_id, $tax_term_array, $_POST[$key . '-field-tax'] );
		
					}
										
					//If the POST value is an array, it's probably multiple checkboxes
					else if ( is_array( $value ) ){
						
						$array_value = array();
						$value_counter = 0;
						
						//Loop through each array value
						foreach( $value as $single_value ){
							//Add this field's Title: Value to the body of the email
							$array_value[$_POST[$key . '-field-meta-key'] . '_' . $value_counter ] = mp_stacks_forms_sanitize_form_value( $single_value );
							$value_counter = $value_counter + 1;
						}
																		
						//Save the post value to the new form post
						add_post_meta( $new_post_id, $_POST[$key . '-field-meta-key'], $array_value );
					}
					//If the POST value is not an array
					else{
						//Save the post value to the new form post
						add_post_meta( $new_post_id, $_POST[$key . '-field-meta-key'], mp_stacks_forms_sanitize_form_value( $value ) );
					}
				
				}
					
			}
			
			//Loop through all the uploaded files and attach them to the newly created post in the approriate meta key
			if ( is_array( $uploaded_files ) ){
				
				$meta_array_value = array();
				
				foreach( $uploaded_files as $uploaded_file ){
					
					//If the meta value is supposed to be stored as an array (meta key is something like "edd_download_files[0][file]"
					if ( strpos( $uploaded_file['meta_key'], '[' ) !== false ){
						//Check if the metakey has an array
						$meta_key_explode = explode( '[', $uploaded_file['meta_key'] ); //edd_download_files, 0], file]
						
						foreach( $meta_key_explode as $exploded_chunk ){
							$new_explode = explode( ']', $exploded_chunk );
							$meta_key_array[] = $new_explode[0]; // edd_download_files, 0, file
						}
						//edd_download_files
						$meta_key = $meta_key_array[0];
						
						//array( 'file' => 'uploadedfileurl' );
						$meta_array_value[][$meta_key_array[2]] = $uploaded_file['file_url'];
																		
						update_post_meta( $new_post_id, $meta_key, $meta_array_value );
					}
					//If this should be the featured image, store the attachment id in the featured image's meta key (_thumbnail_id)
					else if ( $uploaded_file['meta_key'] == 'featured_image' ){
						update_post_meta( $new_post_id, '_thumbnail_id', $uploaded_file['attachment_id'] );
					}
					//If it isn't the featured image, store the file URL in the designated meta key
					else{
						update_post_meta( $new_post_id, $uploaded_file['meta_key'], $uploaded_file['file_url'] );
					}
							
				}
			}
	
		}
		
	}
	
	//Get the message we want to show the user when the form is successfully submitted
	$success_message = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_submission_success_message', __( 'Thanks for your entry. The form was successfully submitted!', 'mp_stacks_forms' ) );
	return array( 'success' => $success_message );
}

/**
 * Sanitize incoming form data through wpkses and esc html
 *
 * @since    1.0.0
 * @link     http://mintplugins.com/doc/
 * @param    string $value The value we want to sanitize
 * @return   void
 */
function mp_stacks_forms_sanitize_form_value( $value ){
	
	//Set the allowed html tags according to what WordPress allows for posts
	$allowed_tags = wp_kses_allowed_html( 'post' );
			
	//Sanitize each field
	$value = wp_kses( mp_core_fix_quotes( esc_html( $value ) ), $allowed_tags ); 
	
	
	return $value;
							
}