jQuery(document).ready( function($) {

	// Add Select2.
	jQuery( '.on-boarding-select2' ).select2({
		placeholder : 'Select All Suitable Options...',
	});


	// Show Popup after 1 second of entering into the MWB pagescreen.
	if ( jQuery( '#show-counter' ).length > 0 && jQuery( '#show-counter' ).val() == 'not-sent' ) {
		setTimeout( mwb_show_onboard_popup(), 1000 );
	}

	/* Close Button Click */
	jQuery( document ).on( 'click','.mwb-on-boarding-close-btn a',function(e){
		mwb_hide_onboard_popup();
	});

	/* Skip For a day. */
	jQuery( document ).on( 'click','.mwb-on-boarding-no_thanks',function(e){

		jQuery.ajax({
            type: 'post',
            dataType: 'json',
            url: mwb.ajaxurl,
            data: {
                nonce : mwb.auth_nonce, 
                action: 'skip_onboarding_popup' ,
            },
            success: function( msg ){
                
            }
        });

		mwb_hide_onboard_popup();
	});

	/* Submitting Form */
	jQuery( document ).on( 'submit','form.mwb-on-boarding-form',function(e){

		e.preventDefault();
		var form_data = JSON.stringify( jQuery( 'form.mwb-on-boarding-form' ).serializeArray() ); 

		jQuery.ajax({
            type: 'post',
            dataType: 'json',
            url: mwb.ajaxurl,
            data: {
                nonce : mwb.auth_nonce, 
                action: 'send_onboarding_data' ,
                form_data: form_data,  
            },
            success: function( msg ){
                
            }
        });
	});

	/* Open Popup */
	function mwb_show_onboard_popup() {
		jQuery( '.mwb-on-boarding-wrapper-background' ).addClass( 'onboard-popup-show' );
		jQuery( '.mwb-onboarding-section' ).show();
	}

	/* Close Popup */
	function mwb_hide_onboard_popup() {
		jQuery( '.mwb-on-boarding-wrapper-background' ).removeClass( 'onboard-popup-show' );
		jQuery( '.mwb-onboarding-section' ).hide();
	}
});