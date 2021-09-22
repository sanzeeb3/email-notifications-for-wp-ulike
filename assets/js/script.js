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

		var section = $( '.ulf-section[data-section-id="configuration/email-notifications"]' );

		section.find('.ulf-field-textarea').prepend('<span class="enfwpul-smart-tags"><a href="#">' + enfwpul_params.show_smart_tags +'</a></span>');
	};

	enfwpulInit();

})( jQuery );