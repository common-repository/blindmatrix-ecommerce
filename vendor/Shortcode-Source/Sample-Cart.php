<?php
global $blinds_config;
$site_url = site_url();
$token = md5(rand(1000,9999)); //you can use any encryption
$_SESSION['token'] = $token; //store it as session variable
if(is_array($_SESSION['cart']) && count($_SESSION['cart']) >0){
	$checksampleproduct = checkForSampleId(1, $_SESSION['cart']);
	if($checksampleproduct != count($_SESSION['cart'])){
		wp_redirect(get_bloginfo('url').'/cart/');
	}
}

$rescustomer = wp_get_current_user();
$billing_company = get_user_meta( $rescustomer->ID, 'billing_company', true );
$billing_address_1 = get_user_meta( $rescustomer->ID, 'billing_address_1', true );
$billing_address_2 = get_user_meta( $rescustomer->ID, 'billing_address_2', true );
$billing_city = get_user_meta( $rescustomer->ID, 'billing_city', true );
$billing_postcode = get_user_meta( $rescustomer->ID, 'billing_postcode', true );
$billing_country = get_user_meta( $rescustomer->ID, 'billing_country', true );
$billing_state = get_user_meta( $rescustomer->ID, 'billing_state', true );
$billing_phone = get_user_meta( $rescustomer->ID, 'billing_phone', true );
if($checksampleproduct == 1){
	$samples ='sample';
}else{
	$samples ='samples';
}
?>

<?php if(is_array($_SESSION['cart']) && count($_SESSION['cart']) > 0):?>

<div class="row" id="sample_success_div" style="display:none;">

<div class="col medium-12 small-12 large-12">
	<div class="col-inner text-center">
	
		<div class="cuspricevalue" style="max-width: 700px; padding: 20px;">
			<h4 class="lead uppercase">Thank you For your Order.</h4>
			<h4 class="lead uppercase">You will receive the confirmation mail soon.</h4>
			<h3 class="lead uppercase">Your Free Sample Order Reference Number Is #<span id="ordernum"></span></h3>
			<a class="button-continue-shopping button primary is-outline" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">←&nbsp;Continue shopping	</a>
		</div>
	</div>
</div>

</div>

<div id="sample_submit_div">

<div class="col medium-12 small-12 large-12">
	<div class="col-inner text-center">
		<h3 class="lead uppercase">Your sample order</h3>
		<h5>You have requested the following <?php echo $checksampleproduct.' '.$samples;?>.</h5>
		<p class="medium"> All sample orders are completely free of charge. You may have up to 8 samples on a single order.You will be asked to register so that we can post the samples to you, but you will not be charged. </p>
	</div>
</div>

<div class="row">
	
	<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">
		<ul class="woocommerce-error message-wrapper" role="alert">
		</ul>
	</div>
	
	<div class="col medium-12 small-12 large-6">
	
		<form name="checkout" id="free_sample_checkout" method="post" class="checkout woocommerce-checkout " action="<?php bloginfo('url'); ?>/sample-cart" enctype="multipart/form-data" novalidate="novalidate">
		
		<div id="customer_details">
			<div class="col-inner text-center">
				<h3 style="text-transform: capitalize;">Your Details</h3>
			</div>
			
			<div class="woocommerce-billing-fields__field-wrapper">
				<p class="form-row form-row-wide validate-required validate-email" id="billing_email_field">
					<label for="billing_email">Email &nbsp;<span class="required">*</span></label>
					<input type="email" class="input-text fl-input" name="billing_email" id="billing_email" value="<?php echo $rescustomer->user_email;?>">
				</p>
				<p class="form-row form-row-wide validate-required" id="billing_first_name_field">
					<label for="billing_first_name">First Name &nbsp;<span class="required">*</span></label>
					<input type="text" class="input-text fl-input" name="billing_first_name" id="billing_first_name" value="<?php echo $rescustomer->user_firstname;?>">
				</p>
				<p class="form-row form-row-wide validate-required" id="billing_last_name_field">
					<label for="billing_last_name">Surname &nbsp;<span class="required">*</span></label>
					<input type="text" class="input-text fl-input" name="billing_last_name" id="billing_last_name" value="<?php echo $rescustomer->user_lastname;?>">
				</p>
				<p class="form-row form-row-wide" id="billing_company_field">
					<label for="billing_company">Company</label>
					<input type="text" class="input-text fl-input" name="billing_company" id="billing_company" value="<?php echo $billing_company; ?>">
				</p>
				<p class="form-row address-field form-row-wide validate-required" id="billing_address_1_field">
					<label for="billing_address_1">Address Line 1 &nbsp;<span class="required">*</span></label>
					<input type="text" class="input-text fl-input" name="billing_address_1" id="billing_address_1" value="<?php echo $billing_address_1; ?>">
				</p>
				<p class="form-row address-field form-row-wide">
					<label for="billing_address_2">Address Line 2</label>
					<input type="text" class="input-text fl-input" name="billing_address_2" id="billing_address_2" value="<?php echo $billing_address_2; ?>">
				</p>
				<p class="form-row form-row-wide address-field validate-required" id="billing_city_field">
					<label for="billing_city">Town/City &nbsp;<span class="required">*</span></label>
					<input type="text" class="input-text fl-input" name="billing_city" id="billing_city" value="<?php echo $billing_city; ?>">
				</p>
				<p class="form-row form-row-wide address-field validate-required" id="billing_county_field">
					<label for="billing_county">County &nbsp;<span class="required">*</span></label>
					<input type="text" class="input-text fl-input" name="billing_county" id="billing_county" value="<?php echo $billing_state; ?>">
				</p>
				<p class="form-row form-row-wide address-field validate-required" id="billing_county_field">
					<label for="billing_country">Country &nbsp;<span class="required">*</span></label>
					<input type="text" class="input-text fl-input" name="billing_country" id="billing_country" value="<?php echo $billing_country; ?>">
				</p>
				<p class="form-row form-row-wide address-field validate-required validate-postcode" id="billing_postcode_field">
					<label for="billing_postcode">Postcode &nbsp;<span class="required">*</span></label>
					<input type="text" class="input-text fl-input" name="billing_postcode" id="billing_postcode" value="<?php echo $billing_postcode; ?>">
				</p>
				<p class="form-row form-row-wide validate-required validate-phone" id="billing_phone_field">
					<label for="billing_phone">Mobile Number &nbsp;<span class="required">*</span></label>
					<input type="tel" class="input-text fl-input" name="billing_phone" id="billing_phone" value="<?php echo $billing_phone; ?>">
				</p>
			</div>
			
		<?php

		if ( apply_filters( 'woocommerce_checkout_show_terms', true ) && function_exists( 'wc_terms_and_conditions_checkbox_enabled' ) ) {
			
			do_action( 'woocommerce_checkout_before_terms_and_conditions' );

			?>
			<div class="woocommerce-terms-and-conditions-wrapper">

			<?php 
				do_action( 'woocommerce_checkout_terms_and_conditions' );
			?>
				<?php if ( wc_terms_and_conditions_checkbox_enabled() ) : ?>

					<p class="form-row validate-required">
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
						<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); // WPCS: input var ok, csrf ok. ?> id="terms" />
							<?php if ( $link_style = get_theme_mod( 'checkout_terms_and_conditions' ) ) : ?>
								<span class="woocommerce-terms-and-conditions-checkbox-text"><?php flatsome_terms_and_conditions_checkbox_text( $link_style ); ?></span>&nbsp;<span class="required">*</span>
							<?php else : ?>
								<span class="woocommerce-terms-and-conditions-checkbox-text"><?php wc_terms_and_conditions_checkbox_text(); ?></span>&nbsp;<span class="required">*</span>
							<?php endif; ?>
						</label>
						<input type="hidden" name="terms-field" value="1" />
					</p>
				<?php endif; ?>
			</div>
			<?php
			do_action( 'woocommerce_checkout_after_terms_and_conditions' );
		}
		?>
			
			<div class="single_variation_wrap text-center">
				<div class="woocommerce-variation-add-to-cart variations_button woocommerce-variation-add-to-cart-disabled">
					<button type="button" id="sendsample" class="single_add_to_cart_button button alt js-add-cart" style="text-transform: capitalize;">Send <?php echo($samples); ?></button>
				</div>
			</div>
					
		</div>
		<input type="hidden" id="action" name="action" value="sample_cart_publish">
		</form>
	</div>	

	<div class="col medium-12 small-12 large-6">
		<div class="col-inner text-center">
			<h3 style="text-transform: capitalize;">Your <?php echo($samples); ?></h3>
		</div>
		<div class="row">
		
			<?php if(is_array($_SESSION['cart']) && count($_SESSION['cart']) > 0):?>
			<?php foreach($_SESSION['cart'] as $key=>$i):?>
			
			<?php
			$urlproname = str_replace(' ','-',strtolower($_SESSION['cart'][$key]['productname']));
	        $urlfcname = str_replace(' ','-',strtolower($_SESSION['cart'][$key]['colorname']));
	        $newurl = safe_encode($_SESSION['cart'][$key]['product_code'].'/'.$_SESSION['cart'][$key]['producttypeid'].'/'.$_SESSION['cart'][$key]['fabricid'].'/'.$_SESSION['cart'][$key]['colorid'].'/'.$_SESSION['cart'][$key]['vendorid']);
	        $productviewurl = get_bloginfo('url').'/'.$blinds_config.'/'.$urlproname.'/'.$urlfcname.'/'.$newurl.'/';
			?>
			
			<div class="col medium-4 small-12 large-6">
				<div class="col-inner">
					<div class="box has-hover   has-hover box-shadow-1 box-bounce box-text-bottom">
						<div class="box-image">
							<div class="image-cover" style="padding-top:100%;">
								<a href="<?php echo $productviewurl;?>">
									<img src="<?php echo $_SESSION['cart'][$key]['imagepath']; ?>" style="border:solid 1px #000;">
								</a>
							</div>
						</div><!-- box-image -->

						<div class="box-text text-center">
							<div class="box-text-inner">
								<h4><?php echo $_SESSION['cart'][$key]['colorname']; ?> <?php echo $_SESSION['cart'][$key]['productname']; ?> Sample</h4>
								<p><a href="javascript:;" tile="Remove this item" class="button alert bme-remove-sample-cart"  data-key="<?php echo $key; ?>"><span>Remove</span></a></p>
							</div><!-- box-text-inner -->
						</div><!-- box-text -->
					</div><!-- .box  -->
				</div>
			</div>
			<?php endforeach;?>
			<?php endif;?>
			
		</div>
	</div>

</div>

</div>

<?php else: ?>
		
<div class="text-center pt pb">
	<div class="woocommerce-notices-wrapper"></div>
	<p class="cart-empty">Your Free Sample Cart is Currently Empty.</p>
	<p class="return-to-shop">
		<a class="button primary wc-backward" href="<?php bloginfo('url'); ?>">Return to shop</a>
	</p>
</div>

<?php endif; ?>


<link rel='stylesheet' id='admin-bar-css'  href='<?php bloginfo('stylesheet_directory'); ?>/custom.css' type='text/css' media='all' />
<script type='text/javascript' src='<?php bloginfo('stylesheet_directory'); ?>/custom.js'></script>