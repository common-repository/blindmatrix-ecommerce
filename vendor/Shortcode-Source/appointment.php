<?php
?>


	<style>
/*basic reset*/
* {margin: 0; padding: 0;}

html {
  height: 100%;
  /*Image only BG fallback*/
  background: #e9e9e9;
}

body {
  font-family: montserrat, arial, verdana;
    width: 100%;
  overflow-x: hidden;
}
/*form styles*/
.steps {
	min-height: 750px;
	width: 1080px;
  margin: 50px auto;
  position: relative;
  height: auto;
    display: block;
}

.steps fieldset {
  background: white;
  border: 0 none;
  border-radius: 3px;
  box-shadow: 0 17px 41px -21px rgb(0, 0, 0);
  padding: 20px 30px;
  border-top: 9px solid #7B1FA2;
  box-sizing: border-box;
  width: 80%;
  margin: 0 10%;
  
  /*stacking fieldsets above each other*/
  position: absolute;
}
/*Hide all except first fieldset*/
.steps fieldset:not(:first-of-type) {
  display: none;
}
/*inputs*/
.steps label{
  color: #333333;
  text-align: left !important;
  font-size: 15px;
  font-weight: 500;
  padding-bottom: 7px;
  padding-top: 12px;
  display: inline-block;
}


.steps input, .steps select, .steps textarea {
  outline: none;
  display: block;
  width: 100%;
  margin: 0 0 20px;
  border: 1px solid #d9d9d9;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
  color: #837E7E;
  font-family: "Roboto";
  -webkti-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  font-size: 14px;
  font-wieght: 400;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  -webkit-transition: all 0.3s linear 0s;
  -moz-transition: all 0.3s linear 0s;
  -ms-transition: all 0.3s linear 0s;
  -o-transition: all 0.3s linear 0s;
  transition: all 0.3s linear 0s;
}

.steps input:focus, .steps textarea:focus{
  color: #333333;
  border: 1px solid #7B1FA2;
}

.error1{
   -moz-border-radius: 3px;
  -webkit-border-radius: 3px;
  border-radius: 3px;
  -moz-box-shadow: 0 0 0 transparent;
  -webkit-box-shadow: 0 0 0 transparent;
  box-shadow: 0 0 0 transparent;
  position: initial;
  margin-top:-22px;
  padding: 0 10px;
  height: 39px;
  display: block;
  color: #ffffff;
  background: #e62163;
  border: 0;
  font: 14px Corbel, "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", "DejaVu Sans", "Bitstream Vera Sans", "Liberation Sans", Verdana, "Verdana Ref", sans-serif;
  line-height: 39px;
  white-space: nowrap;

}

.appointment_form.steps textarea {
    height: 100px;
}
.error-log{
    margin: 5px 5px 5px 0;
  font-size: 19px;
  position: relative;
  bottom: -2px;
}

.question-log {
  margin: 5px 1px 5px 0;
  font-size: 15px;
  position: relative;
  bottom: -2px;
  }

/*buttons*/
.steps .action-button, .action-button {
  width: 100px !important;
  background: #7B1FA2;
  font-weight: bold;
  color: white;
  border: 0 none;
  border-radius: 1px;
  cursor: pointer;
  padding: 10px 5px;
  margin: 10px auto;
  -webkit-transition: all 0.3s linear 0s;
  -moz-transition: all 0.3s linear 0s;
  -ms-transition: all 0.3s linear 0s;
  -o-transition: all 0.3s linear 0s;
  transition: all 0.3s linear 0s;
  display: block;
}

.steps .next, .steps .submit{
    float: right;
}

.steps .previous{
  float:left;
}

.steps .action-button:hover, .steps .action-button:focus, .action-button:hover, .action-button:focus {
  background:#9F2AD0;;
}

.steps .explanation{
display: block;
  clear: both;
  width: 540px;
  background: #f2f2f2;
  position: relative;
  margin-left: -30px;
  padding: 22px 0px;
  margin-bottom: -10px;
  border-bottom-left-radius: 3px;
  border-bottom-right-radius: 3px;
  top: 10px;
  text-align: center;
  color: #333333;
  font-size: 12px;
  font-weight: 200;
  cursor:pointer;
}


/*headings*/
.fs-title {
  text-transform: uppercase;
     margin: 0 0 5px;
     line-height: 1;
     color: #7B1FA2;
     font-size: 18px;
    font-weight: 400;
    text-align:center;
}
.fs-subtitle {
  font-weight: normal;
  font-size: 13px;
  color: #837E7E;
  margin-bottom: 20px;
  text-align: center;
}
/*progressbar*/
#progressbar {
  margin-bottom: 30px;
  overflow: hidden;
  /*CSS counters to number the steps*/
  counter-reset: step;
  width:100%;
  text-align: center;
}
#progressbar li {
  margin:0;
  list-style-type: none;
  color: rgb(51, 51, 51);
  text-transform: uppercase;
  font-size: 9px;
  width: 50%;
  float: left;
  position: relative;
}
#progressbar li:before {
  content: counter(step);
  counter-increment: step;
  width: 20px;
  line-height: 20px;
  display: block;
  font-size: 10px;
  color: #333;
  background: #80808047;
  border-radius: 3px;
  margin: 0 auto 5px auto;
}
/*progressbar connectors*/
#progressbar li:after {
	content: '';
    width: 96%;
    height: 2px;
    background: white;
    /* overflow: auto; */
    position: absolute;
    left: -48%;
    top: 9px;
    z-index: 0;
}
#progressbar li:first-child:after {
  /*connector not needed before the first step*/
  content: none; 
}
/*marking active/completed steps green*/
/*The number of the step and the connector before it = green*/
#progressbar li.active:before,  #progressbar li.active:after{
  background: #7B1FA2;
  color: white;
}


/* my modal */

.modal p{
  font-size: 15px;
  font-weight: 100;
  font-family: sans-serif;
  color: #3C3B3B;
  line-height: 21px;
}

.modal {
  position: fixed;
  top: 50%;
  left: 50%;
  width: 50%;
  max-width: 630px;
  min-width: 320px;
  height: auto;
  z-index: 2000;
  visibility: hidden;
  -moz-backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  -webkit-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
}
.modal.modal-show {
  visibility: visible;
}
.lt-ie9 .modal {
  top: 0;
  margin-left: -315px;
}

.modal-content {
  background: #ffffff;
  position: relative;
  margin: 0 auto;
  padding: 40px;
  border-radius: 3px;
}

.modal-overlay {
  background: #000000;
  position: fixed;
  visibility: hidden;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1000;
  filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=0);
  opacity: 0;
  -moz-transition-property: visibility, opacity;
  -o-transition-property: visibility, opacity;
  -webkit-transition-property: visibility, opacity;
  transition-property: visibility, opacity;
  -moz-transition-delay: 0.5s, 0.1s;
  -o-transition-delay: 0.5s, 0.1s;
  -webkit-transition-delay: 0.5s, 0.1s;
  transition-delay: 0.5s, 0.1s;
  -moz-transition-duration: 0, 0.5s;
  -o-transition-duration: 0, 0.5s;
  -webkit-transition-duration: 0, 0.5s;
  transition-duration: 0, 0.5s;
}
.modal-show .modal-overlay {
  visibility: visible;
  filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=60);
  opacity: 0.6;
  -moz-transition: opacity 0.5s;
  -o-transition: opacity 0.5s;
  -webkit-transition: opacity 0.5s;
  transition: opacity 0.5s;
}

/*slide*/
.modal[data-modal-effect|=slide] .modal-content {
  filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=0);
  opacity: 0;
  -moz-transition: all 0.5s 0;
  -o-transition: all 0.5s 0;
  -webkit-transition: all 0.5s 0;
  transition: all 0.5s 0;
}
.modal[data-modal-effect|=slide].modal-show .modal-content {
  filter: progid:DXImageTransform.Microsoft.Alpha(enabled=false);
  opacity: 1;
  -moz-transition: all 0.5s 0.1s;
  -o-transition: all 0.5s 0.1s;
  -webkit-transition: all 0.5s;
  -webkit-transition-delay: 0.1s;
  transition: all 0.5s 0.1s;
}
.modal[data-modal-effect=slide-top] .modal-content {
  -moz-transform: translateY(-300%);
  -ms-transform: translateY(-300%);
  -webkit-transform: translateY(-300%);
  transform: translateY(-300%);
}
.modal[data-modal-effect=slide-top].modal-show .modal-content {
  -moz-transform: translateY(0);
  -ms-transform: translateY(0);
  -webkit-transform: translateY(0);
  transform: translateY(0);
}


/* RESPONSIVE */

/* moves error logs in tablet/smaller screens */

@media (max-width:1000px){

/*brings inputs down in size */
.steps input,  .steps select, .steps textarea {
  outline: none;
  display: block;
  width: 100% !important;
  }

  /*brings errors in */
  .error1 {
  left: 345px;
  margin-top: -58px;
}




}


@media (max-width:675px){
/*mobile phone: uncollapse all fields: remove progress bar*/

.steps {
  width: 100%;
  margin: 50px auto;
  position: relative;
}

/*move error logs */
.error1 {
  position: relative;
  left: 0 !important;
  margin-top: 24px;
  top: -11px;
}

/*show hidden fieldsets */


.steps fieldset{
  position:relative;
  width: 100%;
  margin:0 auto;
  margin-top: 45px;
}

.steps .submit {
  float: right;
  margin: 28px auto 10px;
  width: 100% !important;
}


}



/* Info */
.info {
  margin: 35px auto;
  text-align: center;
  font-family: 'roboto', sans-serif;
}
.info h1 {
  position: relative;
  font-size: 30px;
  font-weight:700;  
  letter-spacing:1px; 
  text-transform:uppercase; 
  width: 380px;
  text-align:center; 
  margin:auto; 
  font-family: "Playfair Display","Bookman",serif;
  white-space:nowrap; 
  padding-bottom:13px;
}
.info h1:before {
    background-color: #c50000;
    content: '';
    display: block;
    height: 3px;
    width: 180px;
    margin-bottom: 5px;
}
.info h1:after {
    background-color: #c50000;
    content: '';
    display: block;
  position:absolute; right:0; bottom:0;
    height: 3px;
    width: 180px;
    margin-bottom: 0.25em;
}


.info span {
  color:#666666;
  font-size: 13px;
  margin-top:20px;
}
.info span a {
  color: #666666;
  text-decoration: none;
}
.info span .fa {
  color: rgb(226, 168, 16);
  font-size: 19px;
  position: relative;
  left: -2px;
}

.info span .spoilers {
  color: #999999;
  margin-top: 5px;
  font-size: 10px;
}
appointment_form .col-md-6 {
	width:100%;
}

	.appointment_form .form-row {
		display: -ms-flexbox;
		display: flex;
		-ms-flex-wrap: wrap;
		flex-wrap: wrap;
		margin-right: -5px;
		margin-left: -5px;
	}
	.appointment_form .form-control {
				display: block;
				width: 100%;
				height: calc(1.5em + .75rem);
				padding: .375rem .75rem;
				font-size: 1rem;
				font-weight: 400;
				line-height: 1.5;
				color: #495057;
				background-color: #fff;
				background-clip: padding-box;
				border: 1px solid #ced4da;
				border-radius: .25rem;
				transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
			}
			.steps fieldset.success_message_ap{
				color: #3c763d;
				background-color: #dff0d8;

			}
@media (min-width: 768px){
	.appointment_form .col-md-6 {
		-ms-flex: 0 0 50%;
		flex: 0 0 50%;
	    max-width: 48%;
		display: inline-block;
		margin-left: 10px;
	}
	.appointment_date_time_cn .form-group.col-md-6 {
		max-width: 47%;
	}
	

}
@media (max-width:675px){
.appointment_form .form-row {
		display: block;
	}
}
.act_button {
  background-color: Crimson;  
  border-radius: 5px;
  color: white;
  padding: .5em;
  text-decoration: none;
}

.act_button:focus,
.act_button:hover {
  background-color: FireBrick;
  color: White;
}

</style>
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
  <script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
  crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<div class="info">
  <h1>Book a Appointment</h1>
</div>




<form class="steps appointment_form"  id="addBDM" action=""  method="post" accept-charset="UTF-8" enctype="multipart/form-data" novalidate="">
  <fieldset>
    <h2 class="fs-title">Fill your Details</h2>
    <h3 class="fs-subtitle">Please provide your details in the form below to proceed with booking.</h3>
		<div class="form-row">
				<div class="form-group col-md-6">
					<label for="firstname_ap">First name <abbr class="fusion-form-element-required" title="required">*</abbr></label>
					<?php wp_nonce_field('name_of_your_action', 'name_of_nonce_field');  ?>
					<input type="text" required="required"   data-rule-required="true" data-msg-required="Please enter your first name" class="form-control"  name="firstname" id="firstname_ap" placeholder="First name">
					 <span class="error1" style="display: none;">
						<i class="error-log fa fa-exclamation-triangle"></i>
					</span>
				</div>
				<div class="form-group col-md-6">
					<label for="lastname_ap">Last name <abbr class="fusion-form-element-required" title="required">*</abbr></label>
					<input type="text" required="required"  data-rule-required="true" data-msg-required="Please enter your last name" class="form-control" name="lastname" id="lastname_ap" placeholder="Last name">
					 <span class="error1" style="display: none;">
						<i class="error-log fa fa-exclamation-triangle"></i>
					</span>
				</div>
			</div>
			<div class="form-group">
				<label  required="required"  data-rule-required="true" data-msg-required="Please enter your address" for="address_ap">Address <abbr class="fusion-form-element-required" title="required">*</abbr></label>
				<textarea cols="40" rows="4" tabindex="" id="address_ap" name="address"  required="true" aria-required="true" ></textarea>
				 <span class="error1" style="display: none;">
					<i class="error-log fa fa-exclamation-triangle"></i>
				</span>
			</div>
						<div class="form-group">
			<label for="meeting-time">Choose a time for your appointment:</label>

<input type="datetime-local" id="meeting-time"
       name="meeting-time" value="2018-06-12T19:30"
       min="2022-06-07T00:00" max="2024-06-14T00:00">
				
				 <span class="error1" style="display: none;">
					<i class="error-log fa fa-exclamation-triangle"></i>
				</span>
			</div>

			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="email_ap">Email <abbr class="fusion-form-element-required" title="required">*</abbr></label>
					<input type="email" required="required"  data-rule-required="true" data-msg-required="Please enter your email address" class="form-control" name="email_ap" id="email_ap" placeholder="Email">
					 <span class="error1" style="display: none;">
						<i class="error-log fa fa-exclamation-triangle"></i>
					</span>
				</div>
				<div class="form-group col-md-6">
					<label for="telephone_ap">Telephone</label>
					<input type="text" class="form-control" name="telephone_ap" id="telephone_ap" >
				</div>
				
			</div>
			<div class="form-group">
				<label for="message_ap">Message</label>
				<textarea cols="40" rows="4" tabindex="" id="message_ap" name="message"  aria-required="true" ></textarea>
			</div>
    <input id="submit" class="hs-button primary large action-button next" type="submit" value="Submit">
  </fieldset>

  <fieldset class="success_message_ap">

    <h3 class="fs-subtitle">Thank you! Your booking is complete. An email with details of your booking has been sent to you shortly.</h3>
	 <a style="float: right;" href="<?php echo get_home_url(); ?>" class="act_button " role="button">Back To Home</a>
	 <a style="float: left;background-color: #7B1FA2;" href="" class="act_button " role="button">Book Another Appointment</a>
  </fieldset>


<script>


jQuery(document).ready(function($){
	var dtToday = new Date();
				var month = dtToday.getMonth() + 1;
				var day = dtToday.getDate();
				var year = dtToday.getFullYear();
				if(month < 10){
					month = '0' + month.toString();
				}
				if(day < 10){
					day = '0' + day.toString();
				}
				var maxDate = year + '-' + month + '-' + day;
                
				$('#date_ap').attr('min', maxDate);
				
				$('#appointment_type').change(function(){
					var users = $('#appointment_type').find(':selected').data('users');
					$("#appointment_users").val(users);
					if($(this).val() !== ""){
						$(".appointment_date_time_cn").show();
						$("#appointment_time").val('');
						$('#date_ap').val('');
					}else{
						$(".appointment_date_time_cn").hide();
					}
				});
			
				$(".appointment_form").submit(function(e) {
					e.preventDefault();
					$(".steps").validate({
						errorClass: 'invalid',
						errorElement: 'span',
						errorPlacement: function (error, element) {
							error.insertAfter(element.next('span').children());
						},
						highlight: function (element) {
							$(element).next('span').show();
						},
						unhighlight: function (element) {
							$(element).next('span').hide();
						},success: function(label){
							console.log('submit');
							 add_post_bmdirectory();
							
							
							
						}
					});
					if ((!$('.steps').valid())) {
						console.log('hjgh');
						return false;
					}

					var form = $(this);
					var url = form.attr('action');
					
					//alert("aaa");return false;
					
					$.ajax({
						   url: "<?php echo(admin_url('admin-ajax.php'));?>",
							dataType : "json",
							data: {action:"create_appointment", "value": form.serialize() },
							type: "post",
						   success: function(data)
						   {
							     add_post_bmdirectory();
						       console.log(data);
							 if(data.success_msg == true){
								 $(".success_message_ap").show();
								 $("#reset_form").trigger('click');
								 $("#appointment_time").val('');
								 jQuery('html, body').animate({
                        			scrollTop: jQuery(".info").offset().top
                        	     }, 150);

							 }else if(data.success_msg == false){
							  $(".success_message_ap").text(data.message);
							  $(".success_message_ap").css({"color": "#a94442", "background-color": "#f2dede"});
							   $(".success_message_ap").show();
							   $("#reset_form").trigger('click');
							   $("#appointment_time").val('');
								
							 }
							   
						   }
						 });

					
				});
				$('#date_ap').change(function(){
					var value = $(this).val();
					var users = $('#appointment_type').find(':selected').data('users');
					
					$.ajax({
						url: "<?php echo(admin_url('admin-ajax.php'));?>",
						dataType : "json",
						data: {action:"appointment_available_time", "value": value, "users": users },
						type: "post",
						success: function(data){
							var count = data.length;
							if(data !== null){
								$(".appointment_form #appointment_time").children().remove();
								jQuery.each( data, function( i, val ) {
									$(".appointment_form #appointment_time").append($("<option></option>") .attr("value", val) .text(val));
								});
							
								$("#appointment_time").val('');
							}
							if( count == 0){
								alert("No staffs available in this day");
								$(".appointment_form #appointment_time").children().remove();
								$(".appointment_form #appointment_time").append($("<option></option>") .attr("value", '') .text('Preferred Appointment Time'));
								$('#date_ap').val('');
								add_post_bmdirectory();
							}
						}
					});
				});
					
					
				



var current_fs,next_fs,previous_fs;var left,opacity,scale;var animating;$(".steps").validate({errorClass:'invalid',errorElement:'span',errorPlacement:function(error,element){error.insertAfter(element.next('span').children());},highlight:function(element){$(element).next('span').show();},unhighlight:function(element){$(element).next('span').hide();}});$(".next").click(function(){$(".steps").validate({errorClass:'invalid',errorElement:'span',errorPlacement:function(error,element){error.insertAfter(element.next('span').children());},highlight:function(element){$(element).next('span').show();},unhighlight:function(element){$(element).next('span').hide();}});if((!$('.steps').valid())){return true;}
if(animating)return false;animating=true;current_fs=$(this).parent();next_fs=$(this).parent().next();$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");next_fs.show();current_fs.animate({opacity:0},{step:function(now,mx){scale=1-(1-now)*0.2;left=(now*50)+"%";opacity=1-now;current_fs.css({'transform':'scale('+scale+')'});next_fs.css({'left':left,'opacity':opacity});},duration:800,complete:function(){current_fs.hide();animating=false;},easing:'easeInOutExpo'});});

$(".previous").click(function(){if(animating)return false;animating=true;current_fs=$(this).parent();previous_fs=$(this).parent().prev();$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");previous_fs.show();current_fs.animate({opacity:0},{step:function(now,mx){scale=0.8+(1-now)*0.2;left=((1-now)*50)+"%";opacity=1-now;current_fs.css({'left':left});previous_fs.css({'transform':'scale('+scale+')','opacity':opacity});},duration:800,complete:function(){current_fs.hide();animating=false;},easing:'easeInOutExpo'});});});
var modules={$window:$(window),$html:$('html'),$body:$('body'),$container:$('.container'),init:function(){$(function(){modules.modals.init();});},modals:{trigger:$('.explanation'),modal:$('.modal'),scrollTopPosition:null,init:function(){var self=this;if(self.trigger.length>0&&self.modal.length>0){modules.$body.append('<div class="modal-overlay"></div>');self.triggers();}},triggers:function(){var self=this;self.trigger.on('click',function(e){e.preventDefault();var $trigger=$(this);self.openModal($trigger,$trigger.data('modalId'));});$('.modal-overlay').on('click',function(e){e.preventDefault();self.closeModal();});modules.$body.on('keydown',function(e){if(e.keyCode===27){self.closeModal();}});$('.modal-close').on('click',function(e){e.preventDefault();self.closeModal();});},openModal:function(_trigger,_modalId){var self=this,scrollTopPosition=modules.$window.scrollTop(),$targetModal=$('#'+_modalId);self.scrollTopPosition=scrollTopPosition;modules.$html.addClass('modal-show').attr('data-modal-effect',$targetModal.data('modal-effect'));$targetModal.addClass('modal-show');modules.$container.scrollTop(scrollTopPosition);},closeModal:function(){var self=this;$('.modal-show').removeClass('modal-show');modules.$html.removeClass('modal-show').removeAttr('data-modal-effect');modules.$window.scrollTop(self.scrollTopPosition);}}}
modules.init();
</script>
<?
		

