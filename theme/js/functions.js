'use strict';

function autosize() {
	// auto adjust the height of
	jQuery('body').on('keyup', 'textarea', function() {
		jQuery(this).height(0);
		jQuery(this).height((this.scrollHeight-10));
	});
}
	

  // loading bar code

  $(function() {
	$("body").on("click", "a[rel='loadpage']", function(e) {
		
		// Get the link location that was clicked
		liveLoad(jQuery(this).attr('href'), 0, null);
		
		return false;
	});
});


// Override the back button to get the ajax content via the back content */
jQuery(window).on('popstate', function(ev) {
	liveLoad(location.href, 0, null);
});

$.fn.scrollIntoView = function(padding, duration, easing) {	
    jQuery('html,body').animate({
        scrollTop: this.offset().top-padding
    }, duration, easing);
    return this;
};
function startLoadingBar() {
	// only add progress bar if added yet.
	jQuery("#loading-bar").show();
	jQuery("#loading-bar").width((50 + Math.random() * 30) + "%");
}
function stopLoadingBar() {
	//End loading animation
	jQuery("#loading-bar").width("101%").delay(200).fadeOut(400, function() {
		jQuery(this).width("0");
	});
}









function liveLoad(pageurl, type, parameters) {
	// page url = request url
	// type = 1: POST; 0: GET;
	// parameters: serialized params

	startLoadingBar();
	
	if(type == 1) {
		var type = "POST";
	} else {
		var type = "GET";
	}
	
	// Request the page
	$.ajax({url: pageurl, type: type, data: parameters, success: function(data) {

		var result = jQuery.parseJSON(data);
		// Show the content
		jQuery('#content').html(result.content);


        // if (result.script) {
        //     var scriptTag = document.createElement('script');
        //     scriptTag.text = result.script;
        //     document.body.appendChild(scriptTag);
        // }

		if (result.script) {
            $('spt').html(result.script);
        }

		// Stop the loading bar
		stopLoadingBar();
		// Set the new title tag
		document.title = result.title;
		// Scroll the document at the top of the page
		jQuery(document).scrollTop(0);
		
	}});
	
	// Store the url to the last page accessed
	if(pageurl != window.location) {
		window.history.pushState({path:pageurl}, '', pageurl);	
	}
	return false;
}

  
  
  

	