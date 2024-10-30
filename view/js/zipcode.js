jQuery(document).ready(function($) {
	
	$('body').on('change', '#installation_charges', function(e) {
		e.preventDefault();
		installation_charge();
	});
    function postcodeAjax(){
		if('yes' != zip_code_params.check_blind_product){
			return false;
		}
		
		if($('#installation_charges:checked').length > 0){
			var installation_charges =	'on';
		}else{
			var installation_charges =	'off';
		}
		if($('#ship-to-different-address-checkbox:checked').length > 0){
			var postcode =	$('#shipping_postcode').val();
		}else{
			var postcode =	$('#billing_postcode').val();
		}
        $.ajax({
            type: 'POST',
            data:  {
                action: 'woocommerce_apply_state',
                billing_postcode: postcode,
                installation_charge: installation_charges,
                security: wc_checkout_params.apply_state_nonce
           },
            url: wc_checkout_params.ajaxurl,
            success: function (response) {
				if(response != ''){
					var otp = JSON.parse(response);
				
					if(typeof otp.installation_charge_checkbox != "undefined" && otp.installation_charge_checkbox == 1){
						$(".installation_charges_contianer").show();
						if($('#installation_charges').prop("checked") == false){
							$("#installation_charges").prop('checked',true);
							$("#installation_charges").change();
						}
					}else{
						$(".installation_charges_contianer").hide();
					}
				}else{
					$(".installation_charges_contianer").hide();
				}
                $('body').trigger('update_checkout');
            }
        });
    }

	function installation_charge(){
		if($('#installation_charges:checked').length > 0){
			var installation_charges =	'on';
		}else{
			var installation_charges =	'off';
		}
	
        $.ajax({
            type: 'POST',
            data:  {
                action: 'woocommerce_installation_charges',
                installation_charge: installation_charges,
                security: wc_checkout_params.apply_state_nonce
           },
            url: wc_checkout_params.ajaxurl,
            success: function (response) {
                $('body').trigger('update_checkout');
            }
        });
    }
	
	//setup before functions
	var typingTimer;                //timer identifier
	var doneTypingInterval = 500; 
	var $input = jQuery('#shipping_postcode, #billing_postcode');

	//on keyup, start the countdown
	$input.on('keyup', function () {
		clearTimeout(typingTimer);
		typingTimer = setTimeout(doneTyping, doneTypingInterval);
	});

	//on keydown, clear the countdown 
	$input.on('keydown', function () {
		clearTimeout(typingTimer);
	});

	//user is "finished typing," do something
	function doneTyping () {
		//do something
		postcodeAjax();
	}
	
	$('#shipping_postcode').on('change blur', function(e){
		e.preventDefault();
		postcodeAjax();
	});
	$('#billing_postcode').on('change blur', function(e){
		e.preventDefault();
		postcodeAjax();
	});
	
	$('#shipping_postcode').change();
	$('#billing_postcode').change();
 	
});