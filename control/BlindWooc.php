<?php
class BlindWooc extends MainController {
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'init_plugin' ) );
		add_action( 'wp_ajax_blind_publish_process', array( $this, 'blind_publish_process' ) ); 
		add_action( 'wp_ajax_nopriv_blind_publish_process', array( $this, 'blind_publish_process' ) );	
		add_action( 'wp_ajax_sample_cart_publish', array( $this, 'sample_cart_publish' ) ); 
		add_action( 'wp_ajax_nopriv_sample_cart_publish', array( $this, 'sample_cart_publish' ) );	
		
		add_filter( 'woocommerce_get_item_data', array( $this, 'display_cart_item_weight' ), 10, 2 );	
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'before_calculate_totals' ), 10, 1 );
		add_action( 'woocommerce_add_order_item_meta', array( $this, 'add_order_item_meta' ), 10, 2);
		add_filter( 'woocommerce_cart_item_name', array( $this, 'custom_variation_item_name'), 10, 3 );
		add_filter( 'woocommerce_cart_item_price', array( $this,'filter_cart_item_price'), 10, 3 );
		add_filter( 'woocommerce_cart_item_subtotal', array( $this,'filter_cart_item_subtotal'), 999, 3 );
		add_action('wp_enqueue_scripts', array( $this,'load_dashicons'));
		add_filter('woocommerce_widget_cart_item_quantity',array($this,'alter_widget_cart_item_quantity'),999,3);
	}
	
	public function alter_widget_cart_item_quantity($html,$cart_item, $cart_item_key){
		$product_id = get_option('blindproduct');
		if($cart_item['product_id']==$product_id){
			// Display original weight
			if ( isset($cart_item['product_my_blind_attr'])) {
			$url = isset($cart_item['blinds_order_item_data']['edit_product_url']) ? $cart_item['blinds_order_item_data']['edit_product_url']:'';
				$parse_url = parse_url($url);
				$urls = !empty($parse_url) && is_array($parse_url) && isset($parse_url['path']) ? explode('/',$parse_url['path']):array();
				$decoded_url = isset($urls[4]) ? safe_decode($urls[4]) :'' ;
				if(!empty($decoded_url)){
				if( isset($cart_item['old_cart_item_key'])){
					$decoded_url_arr = explode('/',$decoded_url);
					 if(!empty($decoded_url_arr) && is_array($decoded_url_arr)){
						end($decoded_url_arr);
						$index = key($decoded_url_arr);
						if($decoded_url_arr[$index]){
							unset($decoded_url_arr[$index]);
						}
						$decoded_url = implode('/',$decoded_url_arr);
					 }
				}

				$decoded_url = $decoded_url.'/'.$cart_item['key'];
				$encoded_url = safe_encode($decoded_url);
				$urls[4] = $encoded_url;
				$parse_url['path'] = implode('/',$urls);
				$url = get_site_url().$parse_url['path'];
				if($url){
					$html.=sprintf('<a href="%s" class="button primary is-outline" style="text-transform: none; line-height: 2; padding: 0px 2px; min-height: auto !important; font-size: 12px;" target="_blank">Edit<span class="dashicons dashicons-edit bm-cart-edit" style="line-height: 1;width:auto"></span></a>' ,$url);
			   }
			}
		  }
		}
		return $html;
	}
	
	public function load_dashicons(){
    	wp_enqueue_style('dashicons');
	}
	
	public function filter_cart_item_price( $price_html, $cart_item, $cart_item_key) {
		if (isset($cart_item['blinds_order_item_data']['extra_offer']) && 0 != absint($cart_item['blinds_order_item_data']['extra_offer'])) {
			$extra_offer_val = absint($cart_item['blinds_order_item_data']['extra_offer']);
			$price = floatval($cart_item['my_new_price']);
			$percent = 100 - $extra_offer_val;
			$total_price = ( $price/$percent )*100;
			$del = sprintf('<del aria-hidden="true">%s</span></del><br><span class="extra_val">( %s off )</span><br>', wc_price($total_price), $extra_offer_val . '%');	
			
			return $del . ' ' . $price_html;
		}
		
		return $price_html;
	}
	
	public function filter_cart_item_subtotal( $item_subtotal, $cart_item, $cart_item_key) {
		$cart_quantity = isset($cart_item['quantity']) ? $cart_item['quantity']:1;
		if (isset($cart_item['blinds_order_item_data']['extra_offer']) && 0 != absint($cart_item['blinds_order_item_data']['extra_offer'])) {
			$extra_offer_val = absint($cart_item['blinds_order_item_data']['extra_offer']);
			$price = floatval($cart_item['my_new_price']);
			$percent = 100 - $extra_offer_val;
			$total_price = ( ( $price/$percent )*100 )*$cart_quantity;
			$del = sprintf('<del aria-hidden="true">%s</span></del><br><span class="extra_val">( %s off )</span><br>', wc_price($total_price), $extra_offer_val . '%');	
			return $del . ' ' . $item_subtotal;
		}
		
		return $item_subtotal;
	}
	
	public function init_plugin() {
		wp_register_script( 'book-publish-form-function', plugins_url( '../view/js/common.js', __FILE__ ), array('jquery','jquery-blockui'), true  );
		wp_enqueue_script( 'book-publish-form-function' );
		$option_blindmatrix_settings = get_option( 'option_blindmatrix_settings' );
		$bg_color = isset($option_blindmatrix_settings['bm_primary_color']) ? $option_blindmatrix_settings['bm_primary_color']:'';
		if (!isset($option_blindmatrix_settings['bm_primary_color']) || ( isset($option_blindmatrix_settings['bm_primary_color']) && '' == $option_blindmatrix_settings['bm_primary_color'] )) {
			$bg_color = '#00c2ff';
		}
		wp_localize_script( 'book-publish-form-function', 'MyAjax', array(
			 'ajaxurl' => admin_url( 'admin-ajax.php' ),
			 'blind_icon' => untrailingslashit( plugins_url( '/', BM_ECO_PLUGIN_FILE )).'/assets/image/blind.png',
			 'shutter_icon' => untrailingslashit( plugins_url( '/', BM_ECO_PLUGIN_FILE )).'/assets/image/shutter.png',
			 'curtain_icon' => untrailingslashit( plugins_url( '/', BM_ECO_PLUGIN_FILE )).'/assets/image/curtain.png',
			 'icon_bg_color'=> $bg_color, 
			 'cart_url'   => wc_get_cart_url(),
		) );
		
		wp_register_script( 'sample-cart-publish', plugins_url( '../view/js/sample_cart_publish.js', __FILE__ ), array('jquery'), true  );
		wp_enqueue_script( 'sample-cart-publish' );
		wp_localize_script( 'sample-cart-publish', 'SampleCartMyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		
		if (is_checkout() ) {
			wp_register_script( 'zip_code', plugins_url( '../view/js/zipcode.js', __FILE__ ), array('jquery'), true  );
			wp_enqueue_script( 'zip_code' );
			wp_localize_script( 'zip_code', 'zip_code_params', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'check_blind_product' => checkBlindProduct() ? 'yes':'no' ) );
		}
	}
	public function custom_variation_item_name( $item_name, $cart_item, $cart_item_key ) {
		if ( '' != $cart_item['new_product_url']) {
				$item_name = sprintf( '<a href="%s">%s</a>', esc_url( $cart_item['new_product_url'] ), $cart_item['current_post_title'] );
		}
		return $item_name;
		
	}
	public function sample_cart_publish() {
		global $product_page;
		global $product_category_page;
		global $blinds_config;
		global $shutters_page;
		global $shutters_type_page;
		global $shutter_visualizer_page;

			$product =array();
			$billing_first_name    = isset($_REQUEST['billing_first_name']) ? wc_clean(wp_unslash($_REQUEST['billing_first_name'])):'';
			$billing_last_name  = isset($_REQUEST['billing_last_name']) ? wc_clean(wp_unslash($_REQUEST['billing_last_name'])):'';
			$billing_company    = isset($_REQUEST['billing_company']) ? wc_clean(wp_unslash($_REQUEST['billing_company'])):'';
			$billing_address_1  = isset($_REQUEST['billing_address_1']) ? wc_clean(wp_unslash($_REQUEST['billing_address_1'])):'';
			$billing_address_2  = isset($_REQUEST['billing_address_2']) ? wc_clean(wp_unslash($_REQUEST['billing_address_2'])):'' ;
			$billing_city       = isset($_REQUEST['billing_city']) ? wc_clean(wp_unslash($_REQUEST['billing_city'])):'';
			$billing_country    = isset($_REQUEST['billing_country']) ? wc_clean(wp_unslash($_REQUEST['billing_country'])):'' ;
			$billing_postcode   = isset($_REQUEST['billing_postcode']) ? wc_clean(wp_unslash($_REQUEST['billing_postcode'])):'';
			$billing_county     = isset($_REQUEST['billing_county']) ? wc_clean(wp_unslash($_REQUEST['billing_county'])):'';
			$billing_email      = isset($_REQUEST['billing_email']) ? wc_clean(wp_unslash($_REQUEST['billing_email'])):'' ;
			$billing_phone      = isset($_REQUEST['billing_phone']) ? wc_clean(wp_unslash($_REQUEST['billing_phone'])):'' ;
			
			//$billing_details = serialize($billing_details);
			$order_details = isset($_SESSION['cart']) ? serialize(wc_clean(wp_unslash($_SESSION['cart']))):'';
			
			/*if ( is_user_logged_in() ) {
				 $customer_type ='user';
				 $customer_id= get_current_user_id();

				 $json_sample_response = CallAPI("POST", $post=array("mode"=>"place_sample_order", "billing_details"=>$billing_details, "order_details"=>$order_details, "customer_id"=>$customer_id, "customer_type"=>$customer_type));
			}else{
				 $customer_type ='guest';
				 $customer_id = null;
				 
				 $json_sample_response = CallAPI("POST", $post=array("mode"=>"place_sample_order", "billing_details"=>$billing_details, "order_details"=>$order_details, "customer_id"=>$customer_id, "customer_type"=>$customer_type));
			}
			$order_number = $json_sample_response->order_number;*/
			
		if ( is_user_logged_in() ) {
			$user_id= get_current_user_id();
			$customerid = get_user_meta($user_id, 'bindCustomerid', true);
		} else {
			$json_customer_response = CallAPI('POST', $post=array('mode'=>'guestlogin', 'FirstName'=>$billing_first_name, 'LastName'=>$billing_last_name, 'MobileNumber'=>$billing_phone, 'Email'=>$billing_email, 'billing_company'=>$billing_company, 'billing_address_1'=>$billing_address_1, 'billing_address_2'=>$billing_address_2, 'billing_city'=>$billing_city, 'billing_county'=>$billing_county, 'billing_postcode'=>$billing_postcode, 'billing_country'=>$billing_country));
			$customerid = $json_customer_response->customerid;
		}

			$json_sample_response = CallAPI('POST', $post=array('mode'=>'place_order', 'customerid'=>$customerid, 'salesorderid'=>'', 'billing_email'=>$billing_email, 'billing_first_name'=>$billing_first_name, 'billing_last_name'=>$billing_last_name, 'billing_company'=>$billing_company, 'billing_address_1'=>$billing_address_1, 'billing_address_2'=>$billing_address_2, 'billing_city'=>$billing_city, 'billing_county'=>$billing_county, 'billing_postcode'=>$billing_postcode, 'billing_phone'=>$billing_phone, 'billing_country'=>$billing_country, 'orderitemval'=>$order_details, 'sample'=>1, 'order_status'=>'Lead', 'amount'=>'0.00'));
			$salesorderid = $json_sample_response->salesorderid;
			$order_number = $json_sample_response->salesorder_no;
			
		if ('' != $order_number) {
			$site_title = get_bloginfo( 'name' );
			$email_heading =  'Your ' . $site_title . ' Free Sample Order is now complete';
			$admin_email_heading = 'New Free Sample Order #' . $order_number;
			// get the preview email content.
			ob_start();
			?>
			<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="background-color: #ffffff;border: 1px solid #dedede;border-radius: 3px">
   <tbody>
	  <tr>
		 <td align="center" valign="top">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header" style="background-color: #1ca7be;color: #ffffff;border-bottom: 0;font-weight: bold;line-height: 100%;vertical-align: middle;font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif;border-radius: 3px 3px 0 0">
			   <tbody>
				  <tr>
					 <td id="header_wrapper" style="padding: 36px 48px">
						<h1 style="font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif;font-size: 30px;font-weight: 300;line-height: 150%;margin: 0;text-align: left;color: #ffffff;background-color: inherit">Thanks for shopping with us</h1>
					 </td>
				  </tr>
			   </tbody>
			</table>
		 </td>
	  </tr>
	  <tr>
		 <td align="center" valign="top">
			<!-- Body -->
			<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
			   <tbody>
				  <tr>
					 <td valign="top" id="body_content" style="background-color: #ffffff">
						<!-- Content -->
						<table border="0" cellpadding="20" cellspacing="0" width="100%">
						   <tbody>
							  <tr>
								 <td valign="top" style="padding: 48px 48px 32px">
									<div id="body_content_inner" style="color: #636363;font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif;font-size: 14px;line-height: 150%;text-align: left">
								   <?php  
									$billing_first_name = isset($_REQUEST['billing_first_name']) ? wc_clean(wp_unslash($_REQUEST['billing_first_name'])):''; 
									$billing_last_name = isset($_REQUEST['billing_last_name']) ? wc_clean(wp_unslash($_REQUEST['billing_last_name'])):''; 
									?>
										<p style="margin: 0 0 16px">Hi <?php echo wp_kses_post($billing_first_name . ' ' . $billing_last_name); ?>,</p>
									   <p style="margin: 0 0 16px">We have finished processing your order.</p>
									   <h2 style="color: #1ca7be;font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif;font-size: 18px;font-weight: bold;line-height: 130%;margin: 0 0 18px;text-align: left">
									   Free Sample [Order #<?php echo wp_kses_post($order_number); ?>] 
									   </h2>
									   <div style="margin-bottom: 40px">
										  <table class="td" cellspacing="0" cellpadding="6" border="1" style="color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;width: 100%;font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif">
											 <thead>
												<tr>
												   <th class="td" scope="col" style="color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px;text-align: left">S.No</th>
													<th class="td" scope="col" style="color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px;text-align: left">Product Description</th>
												</tr>
											 </thead>
											 <tbody>
 
			<?php
			$cart = isset($_SESSION['cart']) ? wc_clean(wp_unslash($_SESSION['cart'])):array();
			foreach ($cart as $key=>$i) {
				$product[$key]['imagepath'] = $cart[$key]['imagepath'];
				$product[$key]['productname'] = $cart[$key]['colorname'] . ' ' . $cart[$key]['productname'];
				$urlfcname = $cart[$key]['colorname'];
				$product[$key]['product_url']  = get_bloginfo('url') . '/' . $blinds_config . '/' . str_replace(' ', '-', strtolower($cart[$key]['productname'])) . '/' . str_replace(' ', '-', strtolower($urlfcname)) . '/?pc=' . safe_encode($cart[$key]['product_code']) . '&ptid=' . safe_encode($cart[$key]['producttypeid']) . '&fid=' . safe_encode($cart[$key]['fabricid']) . '&cid=' . safe_encode($cart[$key]['colorid']) . '&vid=' . safe_encode($cart[$key]['vendorid']);
			
			
				?>

			  <tr class="order_item">
				 <td class="td" style="color: #636363;border: 1px solid #e5e5e5;width: 1px;padding: 12px;text-align:center;vertical-align: middle;font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif">
				   
				   <b><?php echo wp_kses_post($key+1); ?></b>
				  </td>
			   <td class="td" style="color: #636363;border: 1px solid #e5e5e5;padding: 12px;width: 250px;text-align: left;vertical-align: middle;font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif">
				  <div style="width: 15%;vertical-align: middle;display: inline-block;">
					<a>
					 <img src="<?php echo esc_url($product[$key]['imagepath']); ?>" height="50" width="100" style="border: 1px solid #000;font-size: 14px;font-weight: bold;height: auto;text-decoration: none;text-transform: capitalize;margin-right: 10px;max-width: 100%;vertical-align: middle"></a>
				  </div>
				  <div style="width: 63%;vertical-align: middle;display: inline-block;margin-left:4%">
					 <a style="color: #222;text-decoration: none;"><b><?php echo wp_kses_post($product[$key]['productname']); ?></b></a>
				  </div>
			   </td>
			</tr>
										  
	
				<?php
			}
			?>
		   </tbody>
				  </table>
			   </div>
			   <table id="addresses" cellspacing="0" cellpadding="0" border="0" style="width: 100%;vertical-align: top;margin-bottom: 40px;padding: 0">
				  <tbody>
					 <tr>
						<td valign="top" width="50%" style="text-align: left;font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;border: 0;padding: 0">
						   <h2 style="color: #1ca7be;font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif;font-size: 18px;font-weight: bold;line-height: 130%;margin: 0 0 18px;text-align: left">Billing address</h2>
						   <address class="address" style="padding: 12px;color: #636363;border: 1px solid #e5e5e5">
						 <?php  
							$billing_first_name = isset($_REQUEST['billing_first_name']) ? wc_clean(wp_unslash($_REQUEST['billing_first_name'])):''; 
							$billing_last_name = isset($_REQUEST['billing_last_name']) ? wc_clean(wp_unslash($_REQUEST['billing_last_name'])):''; 
							$billing_address_1 = isset($_REQUEST['billing_address_1']) ? wc_clean(wp_unslash($_REQUEST['billing_address_1'])):''; 
							$billing_address_2 = isset($_REQUEST['billing_address_2']) ? wc_clean(wp_unslash($_REQUEST['billing_address_2'])):''; 
							$billing_city = isset($_REQUEST['billing_city']) ? wc_clean(wp_unslash($_REQUEST['billing_city'])):''; 
							$billing_postcode = isset($_REQUEST['billing_postcode']) ? wc_clean(wp_unslash($_REQUEST['billing_postcode'])):''; 
							$billing_county = isset($_REQUEST['billing_county']) ? wc_clean(wp_unslash($_REQUEST['billing_county'])):''; 
							$billing_phone = isset($_REQUEST['billing_phone']) ? wc_clean(wp_unslash($_REQUEST['billing_phone'])):''; 
							$billing_email = isset($_REQUEST['billing_email']) ? wc_clean(wp_unslash($_REQUEST['billing_email'])):''; 
							?>
						   
						 <?php echo wp_kses_post($billing_first_name . ' ' . $billing_last_name); ?><br> <?php echo wp_kses_post($billing_address_1 . ' ' . $billing_address_2); ?><br> <?php echo wp_kses_post($billing_city); ?><br> <?php echo wp_kses_post($billing_postcode); ?> <br><?php echo wp_kses_post($billing_county); ?>,<?php echo wp_kses_post($billing_county); ?>									<br><a href="tel:<?php echo wp_kses_post($billing_phone); ?>" style="color: #1ca7be;font-weight: normal;text-decoration: underline"><?php echo wp_kses_post($billing_phone); ?></a>													<br><?php echo wp_kses_post($billing_email); ?>					
						   </address>
						</td>
					 </tr>
				  </tbody>
			   </table>
			   <p style="margin: 0 0 16px">Thanks for using <?php echo esc_url(get_site_url()); ?>!</p>
			</div>
		 </td>
	  </tr>
	</tbody>
	</table>
	<!-- End Content -->
	</td>
	</tr>
	</tbody>
	</table>
	<!-- End Body -->
	</td>
	</tr>
	</tbody>
	</table>
		
			<?php
				
			$message = ob_get_clean();

			$headers = array('Content-Type: text/html; charset=UTF-8');
			$mail = isset($_REQUEST['billing_email']) ? wc_clean(wp_unslash($_REQUEST['billing_email'])):'';
			$mail = wp_mail($mail, $email_heading, $message, $headers );
			$admin_email = get_option('woocommerce_email_from_address');
			$admin_mail = wp_mail($admin_email, $admin_email_heading, $message, $headers );
			if ($admin_mail && $mail) {
				$json_sample_mail_response = CallAPI('POST', $post=array('mode'=>'sample_order_mail_status', 'order_number'=>$order_number, 'mail_status'=>0));
			}
			unset($_SESSION['cart']);
			$response = array('order_number'=>$order_number);
			echo( json_encode($response) );
		}

			die();
			
	}
	public function save_image( $base64_img, $title ) {

		// Upload dir.
		$upload_dir  = wp_upload_dir();
		$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

		$img             = str_replace( 'data:image/jpeg;base64,', '', $base64_img );
		$img             = str_replace( ' ', '+', $img );
		$decoded         = base64_decode( $img );
		$filename        = $title . '.jpeg';
		$file_type       = 'image/jpeg';
		$hashed_filename = md5( $filename . microtime() ) . '_' . $filename;

		// Save the image in the uploads directory.
		$upload_file = file_put_contents( $upload_path . $hashed_filename, $decoded );

		$attachment = array(
			'post_mime_type' => $file_type,
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $hashed_filename ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
			'guid'           => $upload_dir['url'] . '/' . basename( $hashed_filename )
		);

		$attach_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $hashed_filename );
		return $attach_id;
	}
	public function generateRandomString( $length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	public function blind_publish_process() {
		$request = blindmatrix_get_request(); 
		$post = isset($request['cart']) ? $request['cart']:array();
		$post = wp_parse_args($post);
		if (isset($post['type']) && 'custom_add_cart_blind' == $post['type']) {
			global $woocommerce;
			$response = array();
			$res_product_data = $this->get_product_data($post);
			if ('' != $res_product_data['productid']) {
				//Woocommerce cart added values
				$product_id = get_option('blindproduct');
				$product_my_blind_attr = $res_product_data['product_my_blind_attr'];
				$custom_price = $res_product_data['totalprice'];
				$current_post_title = $res_product_data['current_post_title'];
				$randomString = $this->generateRandomString();
				$attach_id = $this->save_image($res_product_data['imagepath'], $randomString);
				$new_product_image_path = wp_get_attachment_url($attach_id);
				$response['att_id'] =$attach_id;
				$response['new_product_image_path'] =$new_product_image_path;
				$qty = round($res_product_data['qty']);
				$new_product_url = $res_product_data['new_product_url'];
				$blinds_order_item_data = $post;
				$vaterate = $res_product_data['vaterate'];
				$new_product_url = $res_product_data['new_product_url'];
				if(isset($post['stored_cart_item_key']) && '' != $post['stored_cart_item_key']){
					$old_cart_item_key = $post['stored_cart_item_key'];
					WC()->cart->remove_cart_item($post['stored_cart_item_key']);
				}

				/**
				 * Add to cart validation.
				 *
				 * @since 1.0
				 */
				$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $qty );


				if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $qty, '', false, array('my_new_price'=>$custom_price,'current_post_title'=>$current_post_title,'attach_id'=>$attach_id,'product_my_blind_attr'=>$product_my_blind_attr,'new_product_image_path'=>$new_product_image_path,'new_product_url'=>$new_product_url,'vaterate'=>$vaterate,'blinds_order_item_data'=>$blinds_order_item_data,'old_cart_item_key' => $old_cart_item_key)) ) {

						$icon = get_theme_mod( 'cart_icon', 'basket' );
						ob_start();
					?>
						<i class="icon-shopping-<?php echo wp_kses_post($icon); ?>" data-icon-label="<?php echo wp_kses_post(WC()->cart->cart_contents_count); ?>">
						<?php
						$response[ 'min_cart_count'] = ob_get_clean();
						
						ob_start();
						woocommerce_mini_cart();
						$response['min_cart_content'] = ob_get_clean();
						
						ob_start();
						?>
						 <span class="cart-price"><?php echo wp_kses_post(WC()->cart->get_cart_subtotal()); ?></span>
						<?php
						
						$response['min_cart_price'] = ob_get_clean();
						
						echo( json_encode($response) );
				} else {
					
					// If there was an error adding to the cart, redirect to the product page to show any errors.
					$data = array(
						'error'       => true
					);

					wp_send_json( $data );
				}

				die();

			}
		}
	}

	public function display_cart_item_weight( $item_data, $cart_item ) {
		$product_id = get_option('blindproduct');
		if ( $cart_item['product_id']== $product_id ) {
			// Display original weight
			if ( isset($cart_item['product_my_blind_attr'])) {
			$item_data[] = array(
					'key'     => __( 'Attributes', 'woocommerce' ),
					'value'   =>  $cart_item['product_my_blind_attr'] ,
			);
				
			$url = isset($cart_item['blinds_order_item_data']['edit_product_url']) ? $cart_item['blinds_order_item_data']['edit_product_url']:'';
				$parse_url = parse_url($url);
				$urls = !empty($parse_url) && is_array($parse_url) && isset($parse_url['path']) ? explode('/',$parse_url['path']):array();
				$decoded_url = isset($urls[4]) ? safe_decode($urls[4]) :'' ;
				if(!empty($decoded_url)){
				if( isset($cart_item['old_cart_item_key'])){
					$decoded_url_arr = explode('/',$decoded_url);
					 if(!empty($decoded_url_arr) && is_array($decoded_url_arr)){
						end($decoded_url_arr);
						$index = key($decoded_url_arr);
						if($decoded_url_arr[$index]){
							unset($decoded_url_arr[$index]);
						}
						$decoded_url = implode('/',$decoded_url_arr);
					 }
				}

				$decoded_url = $decoded_url.'/'.$cart_item['key'];
				$encoded_url = safe_encode($decoded_url);
				$urls[4] = $encoded_url;
				$parse_url['path'] = implode('/',$urls);
				$url = get_site_url().$parse_url['path'];
				if($url){
					$item_data[] = array(
						'key'     => 'Edit',
						'value'   =>  sprintf('<a href="%s" class="button primary is-outline" style="text-transform: none; margin: 4px auto; line-height: 2; padding: 0px 2px; min-height: auto !important; font-size: 12px;" target="_blank">Edit<span class="dashicons dashicons-edit bm-cart-edit" style="line-height: 1;width:auto"></span></a>' ,$url),
					);	
			   }
			if(is_cart() || is_checkout()){
			   $cart_item_key = isset($cart_item['key']) ? $cart_item['key']:'';
			   $item_data[] = array(
						'key'     => 'Copy',
						'value'   =>  sprintf('<a href="#" class="button primary is-outline blindmatrix-copy-cart-item" style="text-transform: none; margin: 4px auto; line-height: 2; padding: 0px 2px; min-height: auto !important; font-size: 12px;" target="_blank" data-cart_item_key="%s">Copy</a>' ,$cart_item_key),
				);	
			}
			}
		  }
		}
		return $item_data;
	}
	
	public function before_calculate_totals( $cart_obj ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			   return;
		}
		
		if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
			return;
		}
		 
		foreach ( $cart_obj->get_cart() as $key=>$value ) {			
			/*$product_id = $key['product_id'];*/
			if ( isset( $value['my_new_price'] ) ) {				
				$price = $value['my_new_price'];
				$value['data']->set_price( ( $price ) );
			}
			if ( isset( $value['current_post_title'] ) ) {
				$new_name = $value['current_post_title'];
				$value['data']->set_name( ( $new_name ) );				
			}
			$value['data']->set_weight( 100 );
		}
	}
	
	public function add_order_item_meta ( $item_id, $values ) {

		if ( isset( $values [ 'product_my_blind_attr' ] ) ) {
			$product_my_blind_attr  = $values [ 'product_my_blind_attr' ];
			wc_add_order_item_meta( $item_id, 'Attributes', $product_my_blind_attr );				
		}	
		if ( isset( $values [ 'new_product_image_path' ] ) ) {
			$product_my_blind_attr  = $values [ 'new_product_image_path' ];
			
			$data = substr($product_my_blind_attr, strpos($product_my_blind_attr, ',') + 1);
			if ( base64_encode(base64_decode($data, true)) === $data) {

				$decodedData = base64_decode($data);
				$post_id = rand();
				$filename='title-canvas-' . $post_id . '.png';
				$attachment = wp_upload_bits( $filename, null, $decodedData );
			
				wc_add_order_item_meta( $item_id, 'new_product_image_path', $attachment['url'] );	
			} else {
				wc_add_order_item_meta( $item_id, 'new_product_image_path', $product_my_blind_attr );				
			}
		}
		if ( isset( $values [ 'blinds_order_item_data' ] ) ) {
			$blinds_order_item_data  = $values [ 'blinds_order_item_data' ];
			wc_add_order_item_meta( $item_id, 'blinds_order_item_data', $blinds_order_item_data );				
		}
		if ( isset( $values [ 'new_product_url' ] ) ) {	
			$new_product_url  = $values [ 'new_product_url' ];	
			wc_add_order_item_meta( $item_id, 'new_product_url', $new_product_url );					
		}
	}
	public function get_product_data( $request) {
		global $product_page;
		global $product_category_page;
		global $blinds_config;
		global $shutters_page;
		global $shutters_type_page;
		global $shutter_visualizer_page;
		global $curtains_single_page;
		global $curtains_config;
		global $blinds_config;
		$componentvalue = array(); 
		$product_my_blind_attr = '';
		
		$single_product_price = isset($request['single_product_price']) ? $request['single_product_price']:'';
		$single_product_netprice = isset($request['single_product_netprice']) ? $request['single_product_netprice']:'';
		$single_product_orgvat = isset($request['single_product_orgvat']) ? $request['single_product_orgvat']:'';
		$blindstype = isset($request['blindstype']) ? $request['blindstype']:'';
		$producttypepriceid = isset($request['producttypepriceid']) ?$request['producttypepriceid']:'' ;
		
		$vat = ( $single_product_netprice / 100 ) * $single_product_orgvat;
		$single_product_netpricewithvat = $single_product_netprice+$vat;

		$producttypeparametername = isset($request['producttypeparametername']) ?$request['producttypeparametername']:'' ;
		$producttypeparametervalue = isset($request['producttypeparametervalue']) ?$request['producttypeparametervalue']:'' ;
		$imagepath = $request['imagepath'];
		$orderItemId = $request['product_code'] . $request['producttypeid'] . $request['fabricid'] . $request['colorid'] . $request['vendorid'];

		if (isset($request['sample']) && 1 == $request['sample']) {
			$product_my_blind_attr .= 'Free sample';
		} else {
			$product_my_blind_attr .= $request['width'];
			if (1 == $request['widthfraction']  && 'inch' == $request['unit']) {
				$product_my_blind_attr .= '&nbsp;1/8';
			} elseif (2 == $request['widthfraction'] && 'inch' == $request['unit']) {
				$product_my_blind_attr .= '&nbsp;1/4';
			} elseif (3 == $request['widthfraction'] && 'inch' == $request['unit']) {
				$product_my_blind_attr .= '&nbsp;3/8';
			} elseif (4 == $request['widthfraction'] && 'inch' == $request['unit']) {
				$product_my_blind_attr .= '&nbsp;1/2';
			} elseif (5 == $request['widthfraction'] && 'inch' == $request['unit']) {
				$product_my_blind_attr .= '&nbsp;5/8';
			} elseif (6 == $request['widthfraction'] && 'inch' == $request['unit']) {
				$product_my_blind_attr .= '&nbsp;3/4';
			} elseif (7 == $request['widthfraction'] && 'inch' == $request['unit']) {
				$product_my_blind_attr .= '&nbsp;7/8';
			}
			
			$product_my_blind_attr .= '&nbsp;' . $request['unit'] . ' width x'; 
			$product_my_blind_attr .=  '&nbsp;' . $request['drope'];
			
			if (1 == $request['dropfraction'] && 'inch' == $request['unit']) {
				$product_my_blind_attr .= '&nbsp;1/8';
			} elseif (2 == $request['dropfraction'] && 'inch' == $request['unit']) {
				$product_my_blind_attr .= '&nbsp;1/4';
			} elseif (3 == $request['dropfraction'] && 'inch' == $request['unit']) {
				$product_my_blind_attr .= '&nbsp;3/8';
			} elseif (4 == $request['dropfraction'] && 'inch' == $request['unit']) {
				$product_my_blind_attr .= '&nbsp;1/2';
			} elseif (5 == $request['dropfraction'] && 'inch' == $request['unit']) {
				$product_my_blind_attr .= '&nbsp;5/8';
			} elseif (6 == $request['dropfraction'] && 'inch' == $request['unit']) {
				$product_my_blind_attr .= '&nbsp;3/4';
			} elseif (7 == $request['dropfraction'] && 'inch' == $request['unit']) {
				$product_my_blind_attr .= '&nbsp;7/8';
			}
			$product_my_blind_attr .= '&nbsp;' . $request['unit'] . ' drop';
			
			if (!empty($request['ProductsParametervalue'])) {
				foreach ($request['ProductsParametervalue'] as $name=>$ProductsParametervalue) {
						$ppv = explode('~', $ProductsParametervalue);
						$ProductsParametertext	= $ppv[1];  
					if ('' != $ProductsParametertext) {
						$product_my_blind_attr .= "\n" . $request['ProductsParametername'][$name] . ' - ' . $ProductsParametertext;
					}
				}
			}
			
			if (!empty($request['shuttercolorvalue'])) {
				$scv = explode('~', $request['shuttercolorvalue']);
				$shuttercolortext	= $scv[1];
				if ( '' != $shuttercolortext) {
					$product_my_blind_attr .= "\n" . $request['shuttercolorname'] . ' - ' . $shuttercolortext;
				}
			}
			
			if (!empty($request['Componentvalue'])) {
				foreach ($request['Componentvalue'] as $name=>$Componentvalue) {
					$compname=array();
					foreach ($Componentvalue as $Component_value) {
						$comp = explode('~', $Component_value);
						$compname[]= $comp[1];
					}
					$compname1 = implode(', ', $compname);

					if ('' != $compname1) {
						$product_my_blind_attr .= "\n" . $request['ComponentParametername'][$name] . ' - ' . $compname1;
					}
					
					#get subcomponent details
					$Componentsubvalue = $request['Componentsubvalue'][$name];
					if (!empty($Componentsubvalue)) {
						foreach ($Componentsubvalue as $subname=>$Componentsubvalue) {
							$compsubname=array();
							foreach ($Componentsubvalue as $Componentsub_value) {
								$subcomp = explode('~', $Componentsub_value);
								if (!empty($subcomp) && count($subcomp) > 1) {
									$compsubname[]= $subcomp[1];
									$subcomponentprice += $subcomp[0];
									$subcomponentcostprice += $subcomp[2];
								} else {
									$compsubname[]= $Componentsub_value;
								}
							}
							$compsubname1 = implode(', ', $compsubname);
							
							if ('' != $compsubname1) {
								$product_my_blind_attr .= "\n" . $request['ComponentSubParametername'][$name][$subname] . ' - ' . $compsubname1;
							}
						}
					}
					
				}
			}
			
			if (!empty($request['Othersvalue'])) {
				foreach ($request['Othersvalue'] as $name=>$Othersvalue) {
					if ('' != $Othersvalue) {
						$product_my_blind_attr .= "\n" . $request['OthersParametername'][$name] . ' - ' . $Othersvalue;
					}
				}
			}
			if (!empty($producttypeparametervalue)) {
				$product_my_blind_attr .= "\n" . $producttypeparametername . ' - ' . $producttypeparametervalue;
			}
		}
		
		if (4 == $blindstype) {
			$request['qty'] =1;
			$result['current_post_title']  = $request['producttypename'] . ' ' . $request['producttype_price_name'] . ' ' . $request['productname'];
			$new_product_url = get_bloginfo('url') . '/' . $shutter_visualizer_page . '/' . strtolower(str_replace(' ', '-', $request['producttypename'])) . '/' . $request['producttypeid'] . '/' . $producttypepriceid;    
		} else if (2 == $blindstype || 3 == $blindstype) {
			$request['qty'] =1;
			$product_my_blind_attr = '';
			foreach ($request['orderitem'] as $items) {
				$myArray =	json_decode( html_entity_decode( stripslashes ($items ) ), true );
				foreach ($myArray as $key=>$item) {
					$product_my_blind_attr .= "\n" . $key . ' - ' . $item;
				}
			}
				
			
			$result['current_post_title']  = $request['producttypename'] . ' ' . $request['productname'];
			$urlfcname = $request['producttypename'];
			$new_product_url = get_bloginfo('url') . '/' . $curtains_config . '/' . str_replace(' ', '-', strtolower($request['producttypename'])) . '/' . $request['productid'] . '/' . $request['producttypeid'];
		} else {
			$result['current_post_title']  = $request['colorname'] . ' ' . $request['productname'];
			$urlfcname = $request['colorname'];
			//$new_product_url = get_bloginfo('url').'/'.$blinds_config.'/'.str_replace(' ','-',strtolower($request['productname'])).'/'.str_replace(' ','-',strtolower($urlfcname)).'/';
			//$new_product_url = get_bloginfo('url').'/'.$blinds_config.'/'.str_replace(' ','-',strtolower($request['productname'])).'/'.str_replace(' ','-',strtolower($urlfcname)).'/?pc='.safe_encode($request['product_code']).'&ptid='.safe_encode($request['producttypeid']).'&fid='.safe_encode($request['fabricid']).'&cid='.safe_encode($request['colorid']).'&vid='.safe_encode($request['vendorid']);
			$productname =	$request['productname'];
			$productname_arr = explode('(', $productname); 
			$name =trim($productname_arr[0]);
			$productname =str_replace(' ', '-', strtolower($name));
			
			//$new_product_url = get_bloginfo('url').'/'.$blinds_config.'/'.$productname;
			//$new_product_url = get_bloginfo('url').'/'.$blinds_config.'/'.$productname.'/'.str_replace(' ','-',strtolower($urlfcname)).'/?pc='.safe_encode($request['product_code']).'&ptid='.safe_encode($request['producttypeid']).'&fid='.safe_encode($request['fabricid']).'&cid='.safe_encode($request['colorid']).'&vid='.safe_encode($request['vendorid']);
			$newurl = safe_encode($request['product_code'] . '/' . $request['producttypeid'] . '/' . $request['fabricid'] . '/' . $request['colorid'] . '/' . $request['vendorid']);
			$new_product_url = get_bloginfo('url') . '/' . $blinds_config . '/' . $productname . '/' . str_replace(' ', '-', strtolower($urlfcname)) . '/' . $newurl . '/';
		}
		$result['product_my_blind_attr']  = $product_my_blind_attr;
		$result['qty'] = $request['qty'];
		$result['productid'] = $request['productid'];
		
		$result['imagepath'] = $imagepath;
		$result['totalprice'] = $single_product_price;
		$result['new_product_url'] = $new_product_url;
		$result['vaterate'] = $request['vaterate'];
		
		return $result;
	}
}
add_filter( 'woocommerce_order_item_thumbnail', 'add_custom_image_to_wc_emails', 999, 2);

function add_custom_image_to_wc_emails( $image, $item) {
	ob_start();
	?>
		<img src="<?php echo esc_url($item->get_meta( 'new_product_image_path', true )); ?>"  height="50" width="100" style="vertical-align:middle; " />
	<?php
	$image = ob_get_clean();
	return $image;
}

?>
