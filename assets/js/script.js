/**
 * All JS works on admin side.
 *
 * global jQuery, enfwpul_params
 *
 * @since 1.5.0
 */

;(function($) {

	'use strict';

	const enfwpulInit = function() {
		var found = $('.ulf-field-textarea').prepend('<span class="enfwpul-smart-tags">' + enfwpul_params.show_smart_tags +'</span>');
	};

	enfwpulInit();

})( jQuery );