/**
*	MegaMenu Backend JavaScript file
*/
(function($){
$(document).ready(function() {

	'use strict';

	// Mega Menu image uploader
	var file_frame;

	$('.button.upload-menu-item-bg').live('click', function( event ){

	event.preventDefault();

	var clicked_button = $(this);

	// If the media frame already exists, reopen it.
	if ( file_frame ) {
	  file_frame.open();
	  return;
	}

	// Create the media frame.
	file_frame = wp.media.frames.file_frame = wp.media({
	  title: $( this ).data( 'uploader_title' ),
	  button: {
	    text: $( this ).data( 'uploader_button_text' ),
	  },
	  multiple: false  // Set to true to allow multiple files to be selected
	});

	// When an image is selected, run a callback.
	file_frame.on( 'select', function() {
	  // We set multiple to false so only get one image from the uploader
	  var attachment = file_frame.state().get('selection').first().toJSON();

	  // Do something with attachment.id and/or attachment.url here
	  clicked_button.prev().val(attachment.url);
	});

	// Finally, open the modal
	file_frame.open();
	});

	$('.remove-menu-item-bg').live('click', function( event ){
		$(this).prev().prev().val('');
	});

});
})(jQuery);
