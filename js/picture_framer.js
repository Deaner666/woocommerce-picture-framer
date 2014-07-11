jQuery(document).ready( function() {

	jQuery("#size-link").click(function() {
		jQuery("html, body").animate({ scrollTop: jQuery('#tab-description').offset().top }, 1000);
	});
});