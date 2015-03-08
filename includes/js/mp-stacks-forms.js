jQuery(document).ready(function($){
	
	//Show a thumbnail of the image when submitting on the submit form	
	$( '.mp-stacks-form-field input[type="file"]' ).on('change', function(e){
		
		//Remove this tumbnial preview if it's already been added (the user chose the wrong image)
		$( this ).parent().find('.mp-stacks-forms-submit-form-image-preview').remove();
		
		for (var i = 0; i < e.target.files.length; i++) {

			var file = e.originalEvent.srcElement.files[i];
			
			// Only process image files.
			if (!file.type.match('image.*')) {
				continue;
			}

			var img = document.createElement("img");
			var reader = new FileReader();
			reader.onloadend = function() {
				 img.src = reader.result;
				 img.className = 'mp-stacks-forms-submit-form-image-preview';
			}
			reader.readAsDataURL(file);
			$(this).after(img);
		}
	
	});
	
});