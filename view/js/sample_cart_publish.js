jQuery(document).ready(function($){
jQuery("#sendsample").click(function(e){

	e.preventDefault();
	
	var regex = /^[A-Za-z0-9 ]+$/;
	var checknameregex = /^\w+$/;
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	
	jQuery('.woocommerce-checkout').removeClass('processing');
	jQuery(".woocommerce-error").html('');
	var err_msg = '';
	
	var billing_email = jQuery('#billing_email').val();
	if(billing_email == ''){
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an email address.</div></li>';
	}
	else if( !emailReg.test( billing_email ) ) {
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter valid email.</div></li>';
	}
	
	var billing_first_name = jQuery('#billing_first_name').val();
	if(billing_first_name == ''){
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an first name.</div></li>';
	}
	else if (billing_first_name.length>40) {
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>First name field cannot contain more than 40 characters!</div></li>';
	}
	
	var billing_last_name = jQuery('#billing_last_name').val();
	if(billing_last_name == ''){
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an last name.</div></li>';
	}
	else if (billing_last_name.length>40) {
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Last name field cannot contain more than 40 characters!</div></li>';	
	}
	
	var billing_company = jQuery('#billing_company').val();
	
	var billing_address_1 = jQuery('#billing_address_1').val();
	if(billing_address_1 == ''){
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an address 1.</div></li>';
	}
	
	var billing_address_2 = jQuery('#billing_address_2').val();

	var billing_city = jQuery('#billing_city').val();
	if(billing_city == ''){
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an town/city.</div></li>';
	}
	
	var billing_county = jQuery('#billing_county').val();
	if(billing_county == ''){
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an county.</div></li>';
	}

	var billing_postcode = jQuery('#billing_postcode').val();
	if(billing_postcode == ''){
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an Postcode/ZIP.</div></li>';
	}
	
	var billing_phone = jQuery('#billing_phone').val();
	if(billing_phone == ''){
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please enter an phone number.</div></li>';
	}
	else if (!regex.test(billing_phone)) {
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Phone number must be in alphabets only.</div></li>';
	}

	var ship_diff = 0;
	
	if(!jQuery('#terms').is(':checked'))
	{
		err_msg += '<li><div class="message-container container alert-color medium-text-center"><span class="message-icon icon-close"></span>Please read and accept the terms and conditions to proceed with your order.</div></li>';
	}
	
	if(err_msg != ''){
		jQuery(".woocommerce-error").html(err_msg);
		jQuery("html, body").animate({ scrollTop: 10 }, "slow");
		return false;
	}else{
		jQuery(".woocommerce-error").html('');
		jQuery('.woocommerce-checkout').addClass('processing');
		
		var customerid = jQuery('#customerID').val();
		    var formData = jQuery( "#free_sample_checkout" ).serialize();
		jQuery.ajax(
		{
			type: 'POST',
			url: SampleCartMyAjax.ajaxurl,
			data : jQuery( "#free_sample_checkout" ).serialize(),
			dataType: 'JSON',
			success: function(response){
				jQuery('#free-sample-cart').removeAttr('data-icon-label');
				jQuery('.widget_shopping_cart').html('<div><p class="woocommerce-mini-cart__empty-message">No products in the cart.</p></div>');
				jQuery('.carticon').html('<strong>0</strong>');
				jQuery('#sample_submit_div').hide();
				jQuery('#ordernum').html(response.order_number);
				jQuery('#sample_success_div').show();
				//console.log(response);
				jQuery('#free-sample-cart').removeAttr('data-icon-label');
				jQuery('.woocommerce-checkout').removeClass('processing');
				jQuery("html, body").animate({ scrollTop: 150 }, "slow");
				return false;
			}
		});
		
	}
});
});