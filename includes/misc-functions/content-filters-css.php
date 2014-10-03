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
	
	//Title Styling
	$title_font_size = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_field_titles_font_size', 20 );
	$title_color = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_field_titles_font_color' );
	
	//Description Styling
	$description_font_size = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_field_desc_font_size', 15 );
	$description_color = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_field_desc_font_color' );
	
	//Spacing between fields
	$spacing = mp_core_get_post_meta( $post_id, 'mp_stacks_forms_field_spacing', 20 );
		
	//CSS for the forms.
	$css_forms_output = 
	'#mp-stacks-forms-container-' . $post_id . ' .mp-stacks-form-field{' . 
		mp_core_css_line( 'margin-bottom', $spacing, 'px' ) . 	
	'}
	#mp-stacks-forms-container-' . $post_id . ' .mp-stacks-forms-field-label{' . 
		mp_core_css_line( 'font-size', $title_font_size, 'px' ) . 
		mp_core_css_line( 'color', $title_color ) . 
	'} #mp-stacks-forms-container-' . $post_id . ' .mp-stacks-forms-field-description{' . 
		mp_core_css_line( 'font-size', $description_font_size, 'px' ) . 
		mp_core_css_line( 'color', $description_color ) . '}';
	
	return $css_forms_output . $css_output;
		
}
add_filter('mp_brick_additional_css', 'mp_stacks_brick_content_output_css_forms', 10, 4);

/**
 * Default CSS for MP Stacks Forms. We do it this way so that we don't need to Enqueue another CSS stylesheet - killing the speed of the page.
 *
 * @access   public
 * @since    1.0.0
 * @return   void
 */
function mp_stacks_forms_default_css(){
	
	echo '<style type="text/css">';
		
		//Styles for the UL containing the fields
		echo '.mp-stacks-form-fields{';
			echo 'list-style:none;';
			echo 'margin:0px;';
		echo '}';
		
		//Color Picker Input Styles
		echo '.mp-stacks-forms-field-color{';
			echo 'padding:0px;';
		echo '}'; 
		
		//Checkbox Input Styles
		echo '.mp-stacks-forms-checkbox-container .mp-stacks-forms-field-description{';
			echo 'display:inline;';
			echo 'margin-left:5px;';
		echo '}';
		echo '.mp-stacks-forms-field-checkbox{';
			echo 'width:inherit!important;';
			echo 'display:inline-block';
		echo '}'; 
		
		//Radio Input Styles
		echo '.mp-stacks-forms-radio-container .mp-stacks-forms-field-description{';
			echo 'display:inline;';
			echo 'margin-left:5px;';
		echo '}';
		echo '.mp-stacks-forms-field-radio{';
			echo 'width:inherit!important;';
			echo 'display:inline-block';
		echo '}'; 
		
		//Override any user set margins on the last field in the form
		echo '.mp-stacks-form-field:last-child{';
			echo 'margin-bottom:0px!important;';
		echo '}';
		
	echo '</style>';
	
}
add_action( 'wp_enqueue_scripts', 'mp_stacks_forms_default_css' );