jQuery(document).ready( function($) {

	// Add Select2.
	jQuery( '.on-boarding-select2' ).select2({
		placeholder : 'Select All Suitable Options...',
	});


	// Show Popup after 5 seconds of entering into the MWB pagescreen.
	if ( jQuery( '#show-counter' ).length > 0 && jQuery( '#show-counter' ).val() == 'not-sent' ) {

		setTimeout( mwb_show_onboard_popup(), 1000 );
	}

	/* Open Popup */
	function mwb_show_onboard_popup() {
		jQuery( '.mwb-on-boarding-wrapper-background' ).addClass( 'onboard-popup-show' );
	}

	/* Close Popup */
	function mwb_hide_onboard_popup() {
		jQuery( '.mwb-on-boarding-wrapper-background' ).removeClass( 'onboard-popup-show' );
	}
});