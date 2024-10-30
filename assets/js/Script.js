function copyshort(tdval,id) {
	if(id == 'myTooltip_generate' ){
		tdval = jQuery(".tooltiptext_generate_shortcode").text();
	}
	navigator.clipboard.writeText(tdval);
	var tooltip = document.getElementById(id);
	if(id != 'myTooltip_generate' ){
		tooltip.innerHTML = "Copied: " + tdval;
	}else{
		var tooltip = document.getElementById('copiedtooltiptext');
		tooltip.innerHTML = "Copied: " + tdval;
	}
	return false;
	
}
function listblind_settings(j) {
	var total=0;
	if(jQuery( ".blindslistcheck" ).hasClass( "list_settings_productname" )){
		var elem = document.getElementsByClassName('list_settings_productname');
		for(var i=0; i < elem.length; i++){
			if(elem[i].checked==true){
				total =total +1;
			}
			if(total > 3){
				alert("You have already selected three products; to select more products, upgrade to the Premium version.");
				elem[j].checked = false ;
				return false;
			}
		}
	}
} 
function listshuther_settings(j) {
	var total=0;
	if(jQuery( ".blindslistcheck" ).hasClass( "list_shutter_settings_productname" )){
		var elem = document.getElementsByClassName('list_shutter_settings_productname');
		for(var i=0; i < elem.length; i++){
			if(elem[i].checked==true){
				total =total +1;
			}
			if(total > 2){
				alert("You have already selected two products; to select more products, upgrade to the Premium version.");
				elem[j].checked = false ;
				return false;
			}
		}
	}
} 
function listcurtain_settings(j) {
	var total=0;
	if(jQuery( ".blindslistcheck" ).hasClass( "list_curtain_settings_productname" )){
		var elem = document.getElementsByClassName('list_curtain_settings_productname');
		for(var i=0; i < elem.length; i++){
			if(elem[i].checked==true){
				total =total +1;
			}
			if(total > 2){
				alert("You have already selected three products; to select more products, upgrade to the Premium version.");
				elem[j].checked = false ;
				return false;
			}
		}
	}
} 
function outFunc(id) {
	if(id == 'myTooltip_generate' ){
		var tooltip = document.getElementById('copiedtooltiptext');
		tooltip.innerHTML = "Copy to clipboard";
	}else{
		  var tooltip = document.getElementById(id);
		  tooltip.innerHTML = "Copy to clipboard";
	}
}

jQuery( document ).ready(function($) {
	 $('#bm_primary_color').wpColorPicker();
	$(".shortcode_generator_sub input[type='checkbox']").change(function(){
		if($(this).is(":checked")) {
			$(this).parent('li').addClass("selected");
		} else {
			$(this).parent('li').removeClass("selected");
		}    
	}).change();
	$(".shortcode_generator_sub input[type='radio']").click(function(){
			$('.advance_setting_img').removeClass('selected');   
			$(this).parent('li').addClass('selected');
	}).change();
	$("input[name='create_shortcode_arrgs[]']").change(function(){
		if($(this).is(":checked")) {
			if($(this).val() == 'Title'){
				$(".preview_shortcode_generate h2.donotcross").css('visibility','visible');
			}else if($(this).val() == 'Price'){
				$('.preview_shortcode_generate .card-product__meta').css('background','rgba(0, 113, 223,0.2)');
				$(".card-product__price").show();
			}else if($(this).val() == 'Description'){
				$('.preview_shortcode_generate .card-product__meta').css('background','rgba(0, 113, 223,0.2)');
				$(".shuttertext").show();
			}
		}else{
			if($(this).val() == 'Title'){
				$(".preview_shortcode_generate h2.donotcross").css('visibility','hidden');
			}else if($(this).val() == 'Price'){
				if($(".shortcode_generator_sub .Description").prop('checked') == false){
					$('.preview_shortcode_generate .card-product__meta').css('background','unset');
				}
				$(".card-product__price").hide();
			}else if($(this).val() == 'Description'){
				if($(".shortcode_generator_sub .Price").prop('checked') == false){
					$('.preview_shortcode_generate .card-product__meta').css('background','unset');
				 }
				$(".shuttertext").hide();
			}
		}
	});
   $( "#generate_shortcode" ).submit(function(e){
        e.preventDefault();
		var shortcode_default ='[BlindMatrix source="BM-Products" products="%products" %arguments]';
		checked = $("input[name='create_shortcode_product[]']:checked").length;

      if(!checked) {
        alert("You must check at least one product to generate a shortcode.");
        return false;
      }

		var valuesArray = $('input[name="create_shortcode_product[]"]:checked').map( function() {
			return this.value;
		}).get().join(",");
		shortcode_default = shortcode_default.replace("%products", valuesArray);
		
		var OtherArgArray = $('input[name="create_shortcode_arrgs[]"]:checked').map( function() {
			return this.value;
		}).get().join(",");  
		   arrg ='';
		   if(OtherArgArray.indexOf('Title') != -1){
				arrg += 'title="true"';
			}else{
				arrg += 'title="false"';
			}
			if(OtherArgArray.indexOf('Price') != -1){
				arrg += ' price="true"';
			}else{
				arrg += ' price="false"';
			}
			if(OtherArgArray.indexOf('Description') != -1){
				arrg += ' desc="true"';
			}else{
				arrg += ' desc="false"';
			}
	
		shortcode_default = shortcode_default.replace("%arguments", arrg);
		$(".tooltiptext_generate_shortcode").text(shortcode_default);
	   return false;
    });
	$( ".bm-reset-menu-button" ).click(function(e){
		e.preventDefault();
		if(!confirm('Are you sure you want to reset the menus')){
			return false;
		}
		
		var $this = $(this);
		
		var data={
				action:'bm_reset_menu_action',
		};
		
		$this.block( {
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
		} );
		
		$.ajax({
				url:  bm_custom_js_params.ajaxurl,
				data: data,
				type: 'POST',
				success: function( response ) {
					console.log(response);
					if(response.success){
						$this.unblock();
						alert('Menu Resetted Successfully');
						window.location.reload();
					}
				}
			});
			return false;
	});

	$('#menu_product_type_1').change(function() {
        if(this.checked) {
           $('.blinds_list_settings').show();
           $('.bm-notice.blinds').show();
           $('.hide_while_disabled').show();
        }else{
			$('.blinds_list_settings').hide();
			$('.bm-notice.blinds').hide();
			$('.hide_while_disabled').hide();
		}       
    }).change();
	
	 $('#menu_product_type_2').change(function() {
        if(this.checked) {
           $('.shutter_list_settings').show();
           $('.bm-notice.shutter').show();
		   $('.hide_while_disabled').show();
        }else{
			$('.shutter_list_settings').hide();
			$('.bm-notice.shutter').hide();
			$('.hide_while_disabled').hide();
		}       
    }).change();
	 $('#menu_product_type_3').change(function() {
        if(this.checked) {
           $('.curtian_list_settings').show();
           $('.bm-notice.curtian').show();
		   $('.hide_while_disabled').show();
        }else{
			$('.curtian_list_settings').hide();
			$('.bm-notice.curtian').hide();
			$('.hide_while_disabled').hide();
		}       
    }).change();
	
	$('.enable_products_bm').change(function(){
		 if ($(this).is(":checked")){
			$(this).parents( "td" ).find(".switch_label").text('Enabled');
		 }else{
			$(this).parents( "td" ).find(".switch_label").text('Disabled');
		 }
	 });
	
	$('.bm-submit').click(function(event){
		if( !$('#menu_product_type_1').is(':checked') && !$('#menu_product_type_2').is(':checked') && !$('#menu_product_type_3').is(':checked')){
			   return true;
		}
		
		var err_msg;
		if($('.blindmatrix-menu-setting-location').length > 0){
			err_msg = 'Please select atleast one Menu Location';
			$('.blindmatrix-menu-setting-location').each(function(){
				if($(this).find('.blindmatrix-menu-location-checkbox').is(':checked')){
					err_msg = '';
				}
			});
		}
		
		if(err_msg){
			event.preventDefault();
			alert(err_msg);
			return false;
		}
	});
	function IsEmail(email) {
		var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if(!regex.test(email)) {
			return false;
		}else{
			return true;
		}
	}
	var auto_open = $.confirm({
			columnClass: 'medium popuppremiumcn',
			title: 'Upgrade Premium',
			icon:'fa-solid fa-crown',
			useBootstrap: false,
			titleClass: 'titleClassCk',
			type: 'blue',
			theme: 'material',
			columnClass:'premium-popup-bm',
			closeIcon: true,
			closeIconClass: 'popupclosepremium dashicons dashicons-no',
			lazyOpen: true,
			escapeKey: true,
			content: '' +
			'<form action="" class="formName">' +
			'<div class="form-group-bm">' +
			'<div class="form-group-feild-bm" ><label  class="popup_label_bm">Enter Your Company Name<span class="required">*</span></label>' +
			'<input type="text" placeholder="Your Company" class="bmcompany form-control"  /></div>' +
			'<div class="form-group-feild-bm" ><label class="popup_label_bm">Enter Your Name</label>' +
			'<input type="text" placeholder="Your Name" class="bmname form-control"  /></div>' +
			'<div class="form-group-feild-bm" ><label  class="popup_label_bm">Enter Your Email ID<span class="required">*</span></label>' +
			'<input type="email" placeholder="Your Email" class="bmemail form-control" required /></div>' +
			'<div class="form-group-feild-bm" ><label  class="popup_label_bm">Enter Your Phone Number</label>' +
			'<input type="number" placeholder="Your Number" class="bmnumber form-control"  /></div>' +
			'</div>' +
			'</form>',
			buttons: {
				formSubmit: {
					text: 'Submit',
					btnClass: 'btn-blue premium_popsubmit',
					action: function () {
						var email = this.$content.find('.bmemail').val();
						var company = this.$content.find('.bmcompany').val();
						var name = this.$content.find('.bmname').val();
						var number = this.$content.find('.bmnumber').val();
						var data={
								action:'bm_premium_query',
								email:email,
								company:company,
								name:name,
								number:number,
						};
						if(!company){
							$.alert({
									title:"Error",							
									boxWidth: '30%',
									icon: 'dashicons dashicons-warning',
									useBootstrap: false,
									type: 'red',
									content:'Company Name is required'
							});
							return false;
						}else if(!email){
							$.alert({
									title:"Error",							
									boxWidth: '30%',
									icon: 'dashicons dashicons-warning',
									useBootstrap: false,
									type: 'red',
									content:'Email is required'
							});
						}else{
							if(IsEmail(email)){
									$.ajax({
										url:  bm_custom_js_params.ajaxurl,
										data: data,
										type: 'POST',
										success: function( response ) {
											if(response && response == 'success'){
												$.alert({
														title:"Success",							
														boxWidth: '30%',
														type: 'green',
														icon: 'dashicons dashicons-yes',
														useBootstrap: false,
														content:'Thanks for submitting the form, we will try to contact you.'
												});
											}
										}
									});
								
								
							}else{
								$.alert({
									title:"Error",							
									boxWidth: '30%',
									icon: 'dashicons dashicons-warning',
									type: 'red',
									useBootstrap: false,
									content:'provide a valid email'
								});
								return false;
							}
							
						}
					}
				},
				redirectToEcommerce: {
					text: 'Schedule a demo',
					btnClass: 'demo btn-green premium_popsubmit', // You can style this button as needed
					action: function () {
						window.open('https://blindmatrix.com/ecommerce-for-retailers/', '_blank');
						return false;
					}
				}
			},
			onContentReady: function () {
				// bind to events
				var jc = this;
				this.$content.find('form').on('submit', function (e) {
					console.log('submit');
					// if the user submits the form by pressing enter in the field.
					e.preventDefault();
					jc.$$formSubmit.trigger('click'); // reference the button and click it
				});
			}
		});
	$('.blindmatrix-upgrade-premium-popup').click(function(event){
		event.preventDefault();
		auto_open.open();
		return false;
	});	
	if($( ".viewpremiumpop" ).hasClass( "true")){
			auto_open.open();
	}
});

