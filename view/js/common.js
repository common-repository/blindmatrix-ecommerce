function global_blind_add_cart(getcartdata){	
	console.log('asdasdasd');
	//var user_page = page_current_chng;
	jQuery.ajax({
		type: 'POST',
		url: MyAjax.ajaxurl,
		data : {action:'blind_publish_process',cart:getcartdata},
		dataType: 'JSON',
		success:function(data, textStatus, XMLHttpRequest){
			if (typeof data.error == 'undefined'){
			jQuery(".widget_shopping_cart_content").html(data.min_cart_content);
			jQuery("#header .cart-price").replaceWith(data.min_cart_price);
			jQuery(".header-button .icon-shopping-cart").replaceWith(data.min_cart_count);
			jQuery("#floating_cart_button .icon-shopping-cart").replaceWith(data.min_cart_count);
			jQuery(".header-button .icon-shopping-bag").replaceWith(data.min_cart_count);
			jQuery(".header-button .icon-shopping-basket").replaceWith(data.min_cart_count);
	
						jQuery(".cart-item").addClass("current-dropdown cart-active");
						jQuery(".shop-container").on("click", function () {
							jQuery(".cart-item").removeClass("current-dropdown cart-active");
						}),
						jQuery(".cart-item").hover(function () {
							jQuery(".cart-active").removeClass("cart-active");
						}),
						setTimeout(function () {
							jQuery(".cart-active").removeClass("current-dropdown");
						}, 4000);
						
			jQuery('.js-add-cart').removeClass("btn-disabled");
			jQuery(".blindmatrix-js-add-cart").removeAttr('disabled');
			jQuery('.loading-spin').css('display','none');
			jQuery('html, body').animate({scrollTop: 0});
			jQuery('.curtain-loder').css('display','none');
			jQuery('.curtain-whole-loader').css('display','none');
			console.log('cart-added');
			jQuery.confirm({
				title: 'Success!',
				columnClass: 'col-md-4 col-md-offset-4',
				content: 'The product is successfully added to cart',
				type: 'blue',
				typeAnimated: true,
				boxWidth: '30%',
				useBootstrap: false,
				buttons: {
					 'Continue shopping': {
						btnClass: 'btn-blue',
						text: 'Continue shopping', // With spaces and symbols
						action: function () {
								history.go(0);
						}
					},
					 'Proceed to cart': {
						btnClass: 'btn-dark',             
						text: 'Proceed to cart', // With spaces and symbols
						action: function () {
							window.location.href = MyAjax.cart_url;
						}
					}
				}
			});
			}else{
				jQuery('.js-add-cart').removeClass("btn-disabled");
				jQuery(".blindmatrix-js-add-cart").removeAttr('disabled');
				jQuery('.loading-spin').css('display','none');
				jQuery('.curtain-loder').css('display','none');
				jQuery('.curtain-whole-loader').css('display','none');
				jQuery.dialog({
						title: 'Sorry!',
						boxWidth: '30%',
						content: 'You cannot add curtains/shutters products when normal product(s) are in the cart',
					});
				}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			alert(textStatus);
		}
	});	
}
jQuery(document).on('click', '.blindmatrix-js-add-cart', function(){
    jQuery('#mode').val("addtocart");
	
	var width = jQuery('#width').val();
	var drope = jQuery('#drope').val();
	//if(width != '' && drope != ''){
		jQuery('.woocommerce-error').html('');
		global_blind_add_cart(jQuery("#submitform").serialize());

	/*}else{
		return false;
	}*/
});


jQuery(window).scroll(function(){
var scroll =  jQuery(window).scrollTop();
  if (scroll >= 150){
	 jQuery("#floating_cart_button").show();
  }else{
	  jQuery("#floating_cart_button").hide();
  }
});
jQuery(document).ready(function(){
	var $style = 'margin-right:5px;width: 22px;height: 22px;background:'+MyAjax.icon_bg_color+';';
	jQuery('.bm-blind-activity .nav-top-link').before("<img src='"+MyAjax.curtain_icon+"' style='"+$style+"'>");
	jQuery('.bm-shutter-activity .nav-top-link').before("<img src='"+MyAjax.curtain_icon+"' style='"+$style+"'>");
	jQuery('.bm-curtain-activity .nav-top-link').before("<img src='"+MyAjax.curtain_icon+"' style='"+$style+"'>");
});
jQuery(document).on('click', '.blindmatrix-copy-cart-item', function(){
	var $this = jQuery(this).closest('table');
	$this.block({
			message: null,
			overlayCSS: {
				opacity: 0.6
			}
		});
	var data={
		action:'blindmatrix_copy_cart_item',
		cart_item_key:jQuery(this).data('cart_item_key'),
	};
	jQuery.ajax({
		url:  MyAjax.ajaxurl,
		data: data,
		type: 'POST',
		success: function( response ) {
			console.log(response);
			if(response.success){
					$this.unblock();
				   jQuery('body').trigger( 'update_checkout' );
                   if( jQuery( 'button[name="update_cart"]' ).length ) {
                       jQuery( 'button[name="update_cart"]' ).attr('aria-disabled', false).removeAttr('disabled').trigger('click');
                   }		
			}else{
				$this.unblock();
			}
		}
	});
	return false;
});