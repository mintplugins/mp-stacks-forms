<?php 
/**
 * This file contains the function which hooks to a brick's CSS content output
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
 * This function hooks to the brick css output. If it is supposed to be an 'forms', then it will add the css for the forms to the brick's css
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_brick_content_output_css_forms( $css_output, $post_id, $first_content_type, $second_content_type ){

	if ( $first_content_type != 'forms' && $second_content_type != 'forms' ){
		return $css_output;	
	}
	
	//Enqueue forms CSS
	wp_enqueue_style( 'mp_stacks_forms_css', plugins_url( 'css/mp-stacks-forms.css', dirname( __FILE__ ) ), MP_STACKS_FORMS_VERSION );
	
	//Title Styling
	$title_font_size = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_field_titles_font_size', 20 );
	$title_color = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_field_titles_font_color' );
	
	//Description Styling
	$description_font_size = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_field_desc_font_size', 15 );
	$description_color = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_field_desc_font_color' );
	
	//Spacing between fields
	$spacing = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_field_spacing', 20 );
	
	//Submit Button Styles
	$mp_stacks_forms_submit_button_text_color = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_submit_button_text_color' );
	$mp_stacks_forms_submit_button_background_color = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_submit_button_background_color' );
	$mp_stacks_forms_submit_button_hover_text_color = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_submit_button_hover_text_color' );
	$mp_stacks_forms_submit_button_hover_background_color = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_submit_button_hover_background_color' );
	
	//Text alignment
	$mp_stacks_forms_field_text_alignment = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_field_text_alignment', 'left' );
		
	//CSS for the forms.
	$css_forms_output = 
	'#mp-stacks-forms-container-' . $post_id . ' .mp-stacks-form-field{' . 
		mp_core_css_line( 'margin-bottom', $spacing, 'px' ) . 
		mp_core_css_line( 'text-align', $mp_stacks_forms_field_text_alignment ) . 			
	'}
	#mp-stacks-forms-container-' . $post_id . ' .mp-stacks-forms-field-title,
	#mp-stacks-forms-container-' . $post_id . ' .mp-stacks-forms-message{' . 
		mp_core_css_line( 'font-size', $title_font_size, 'px' ) . 
		mp_core_css_line( 'color', $title_color ) . 
	'} #mp-stacks-forms-container-' . $post_id . ' .mp-stacks-forms-field-description, #mp-stacks-forms-container-' . $post_id . ' .mp-stacks-forms-field-fileupload{' . 
		mp_core_css_line( 'font-size', $description_font_size, 'px' ) . 
		mp_core_css_line( 'color', $description_color ) . 
	'}
	#mp-stacks-forms-container-' . $post_id . ' .mp-stacks-forms-submit-btn{' .
		mp_core_css_line( 'color', $mp_stacks_forms_submit_button_text_color ) . 
		mp_core_css_line( 'background-color', $mp_stacks_forms_submit_button_background_color ) . 
	'}
	#mp-stacks-forms-container-' . $post_id . ' .mp-stacks-forms-submit-btn:hover{' .
		mp_core_css_line( 'color', $mp_stacks_forms_submit_button_hover_text_color ) . 
		mp_core_css_line( 'background-color', $mp_stacks_forms_submit_button_hover_background_color ) . 
	'}';
	
	return $css_forms_output . $css_output;
		
}
add_filter('mp_brick_additional_css', 'mp_stacks_brick_content_output_css_forms', 10, 4);