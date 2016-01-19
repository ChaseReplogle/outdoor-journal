
// Creates a hide class that can be applied in the form editor.
jQuery(document).ready(function($){
     $(".form-hide").hide();
});


// Maps do not scroll with page unless clicked first.
jQuery(document).ready(function($){
	  // you want to enable the pointer events only on click;
	$('#wpuf-map-choose_location').addClass('scrolloff'); // set the pointer events to none on doc ready
	  	$('div.wpuf-fields').live("click", function () {
	  	$('#wpuf-map-choose_location').removeClass('scrolloff'); // set the pointer events true on click
	});

    // you want to disable pointer events when the mouse leave the canvas area;
    $("div.wpuf-fields").mouseleave(function() {
       $('#wpuf-map-choose_location').addClass('scrolloff'); // set the pointer events to none when mouse leaves the map area
    });
});


// Hide the "add new items" field when editing a post... This field gets emptied when saved so it doesn't need to be showed the next time.
jQuery(document).ready(function($){
	$('.add_item .wpuf-fields input').prop('checked', false);
	$('.item').css('display', 'none');
});
