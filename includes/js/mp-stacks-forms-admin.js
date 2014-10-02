jQuery(document).ready(function($){
	
	mp_stacks_forms_reset_extra_field_types();
		
	$(document).on('change', "[class$='mp_stacks_forms_field_typeBBBBB'] select, [class*='mp_stacks_forms_field_typeBBBBB '] select", function() {
		mp_stacks_forms_reset_extra_field_types();
	});
	
	function mp_stacks_forms_reset_extra_field_types(){
		
		$("[class$='mp_stacks_forms_field_typeBBBBB'] select>option:selected, [class*='mp_stacks_forms_field_typeBBBBB '] select>option:selected").map(function() {	
			
			var icon_type = $(this).val();
			
			//If the value of the selected option is feature_icon	
			if ( icon_type == 'select' ){
				//Show the select options field
				$(this).parent().parent().parent().find("[class$='mp_stacks_forms_field_select_optionsBBBBB'], [class*='mp_stacks_forms_field_select_optionsBBBBB ']").css('display', 'block');
			}
			else{
				//Hide the select options field
				$(this).parent().parent().parent().find("[class$='mp_stacks_forms_field_select_optionsBBBBB'], [class*='mp_stacks_forms_field_select_optionsBBBBB ']").css('display', 'none');
			}
						
		});
	}
	
	//When a new feature gets duplicated
	$(window).on('mp_core_duplicate_repeater_after', function(event, data){
		
		var containing_li = data[0];
		
		//Hide the icon and image upload fields
		$( containing_li ).next( ".mp_stacks_forms_fields_repeater" ).find("[class$='mp_stacks_forms_field_select_optionsBBBBB'], [class*='mp_stacks_forms_field_select_optionsBBBBB ']").css('display', 'none');
		
	});
	
});