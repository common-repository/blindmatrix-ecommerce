jQuery(function($){
 
	// on upload button click
	$('body').on( 'click', '.seasonal_image_upl', function(e){
 
		e.preventDefault();
 
		var button = $(this),
		custom_uploader = wp.media({
			title: 'Insert image',
			library : {
				// uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
				type : 'image'
			},
			button: {
				text: 'Use this image' // button label text
			},
			multiple: false
		}).on('select', function() { // it also has "open" and "close" events
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			button.html('<img style="max-width:300px;max-height:200px;" src="' + attachment.url + '">').next().show();
			jQuery("#seasonal_image_img").val(attachment.id);
		}).open();
		
	});
 
	// on remove button click
	$('body').on('click', '.seasonal_image_rmv', function(e){
 
		e.preventDefault();
 
		var button = $(this);
		jQuery("#seasonal_image_img").val('');
		button.hide().prev().html('Upload image');
	});
 
});