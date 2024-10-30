<?php
add_action('init', 'start_session', 1);

function start_session() {
	if(!session_id()) {
		session_start();
	}
}
function end_session() {
	session_destroy();
}

add_action('wp_logout', 'end_session');
add_action('wp_login', 'end_session');
add_action('end_session_action', 'end_session');
	
function blindmatrix_clean_data( $var) {
	if ( is_array( $var ) ) {
		return array_map( 'blindmatrix_clean_data', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}

function bm_eco_global_blinds_variables() {
	global $product_page;
	global $product_category_page;
	global $shutters_page;
	global $shutters_type_page;
	global $shutter_visualizer_page;
	global $curtains_single_page;
	global $curtains_config;
	global $blinds_config;
	$blindmatrix_settings = get_option( 'option_blindmatrix_settings' );
	
	if (isset( $blindmatrix_settings['product_page'] ) && '' != $blindmatrix_settings['product_page'] ) {
		$product_page = $blindmatrix_settings['product_page'];
	} else {
		$product_page = 'blinds-list';
	}
	if (isset( $blindmatrix_settings['blinds_config'] ) && ''!=$blindmatrix_settings['blinds_config'] ) {
		$blinds_config = $blindmatrix_settings['blinds_config'];
	} else {
		$blinds_config = 'blinds-config';
	}
	if (isset( $blindmatrix_settings['shutters_page'] ) && '' != $blindmatrix_settings['shutters_page'] ) {
		$shutters_page = $blindmatrix_settings['shutters_page'];
	} else {
		$shutters_page = 'shutter-single-type';
	}
	if (isset( $blindmatrix_settings['shutters_type_page'] ) && '' != $blindmatrix_settings['shutters_type_page'] ) {
		$shutters_type_page = $blindmatrix_settings['shutters_type_page'];
	} else {
		$shutters_type_page = 'shutter-type';
	}
	if (isset( $blindmatrix_settings['shutter_visualizer_page'] ) && '' != $blindmatrix_settings['shutter_visualizer_page'] ) {
		$shutter_visualizer_page = $blindmatrix_settings['shutter_visualizer_page'];
	} else {
		$shutter_visualizer_page = 'shutter-visualizer';
	}
	if (isset( $blindmatrix_settings['curtains_single'] ) && '' != $blindmatrix_settings['curtains_single'] ) {
		$curtains_single_page = $blindmatrix_settings['curtains_single'];
	} else {
		$curtains_single_page = 'curtain-single';
	}
	if (isset( $blindmatrix_settings['curtains_config'] ) && '' != $blindmatrix_settings['curtains_config'] ) {
		$curtains_config = $blindmatrix_settings['curtains_config'];
	} else {
		$curtains_config = 'curtain-config';
	}

}
add_action( 'after_setup_theme', 'bm_eco_global_blinds_variables' );



function bm_eco_custom_rewrite_tag() {
	add_rewrite_tag('%pc%', '([^&]+)');
	add_rewrite_tag('%ptn%', '([^&]+)');
	add_rewrite_tag('%ptid%', '([^&]+)');
	add_rewrite_tag('%ptpid%', '([^&]+)');
	add_rewrite_tag('%pid%', '([^&]+)');
	add_rewrite_tag('%productname%', '([^&]+)');
	add_rewrite_tag('%colorname%', '([^&]+)');
	add_rewrite_tag('%fid%', '([^&]+)');
	add_rewrite_tag('%cid%', '([^&]+)');
	add_rewrite_tag('%vid%', '([^&]+)');
}
add_action('init', 'bm_eco_custom_rewrite_tag', 10, 0);

function bm_eco_custom_rewrite_rule() {
	global $product_page;
	global $shutters_page;
	global $shutter_visualizer_page;
	global $curtains_single_page;
	global $curtains_config;
	global $blinds_config;
	
	$blinds_list_id = get_option('blinds_list');
	$blinds_config_id = get_option('blinds_config');
	$shutter_single_type_id = get_option('shutter_single_type');
	$shutter_config_id = get_option('shutter_config');
	$curtains_single_id = get_option('curtains_single');
	$curtain_config_id = get_option('curtain_config');
	
	add_rewrite_rule('^' . $product_page . '/([^/]*)/?', 'index.php?page_id=' . $blinds_list_id . '&pc=$matches[1]', 'top');
	add_rewrite_rule('^' . $blinds_config . '/([^/]*)/([^/]*)/?', 'index.php?page_id=' . $blinds_config_id . '&productname=$matches[1]&colorname=$matches[2]', 'top');
	add_rewrite_rule('^' . $shutters_page . '/([^/]*)/([^/]*)/?', 'index.php?page_id=' . $shutter_single_type_id . '&ptn=$matches[1]&ptid=$matches[2]', 'top');
	add_rewrite_rule('^' . $shutter_visualizer_page . '/([^/]*)/([^/]*)/([^/]*)/?', 'index.php?page_id=' . $shutter_config_id . '&ptn=$matches[1]&ptid=$matches[2]&ptpid=$matches[3]', 'top');
	add_rewrite_rule('^' . $curtains_single_page . '/([^/]*)/?', 'index.php?page_id=' . $curtains_single_id . '&ptn=$matches[1]&ptn=$matches[2]&ptn=$matches[3]', 'top');
	add_rewrite_rule('^' . $curtains_config . '/([^/]*)/([^/]*)/([^/]*)/?', 'index.php?page_id=' . $curtain_config_id . '&ptn=$matches[1]&pid=$matches[2]&ptid=$matches[3]', 'top');

	
}
add_action('init', 'bm_eco_custom_rewrite_rule', 10, 0);

function set_post_order_in_admin( $wp_query ) {
	global $pagenow;
	if ( is_admin() && 'edit.php' == $pagenow && !isset($_GET['orderby'])) {
		//$wp_query->set( 'orderby', 'title' );
		$wp_query->set( 'order', 'DSC' );
	}
}
add_filter('pre_get_posts', 'set_post_order_in_admin' );

function get_ajax_url() { 
	
	$site_url = untrailingslashit( plugins_url( '/', BM_ECO_PLUGIN_FILE ));
	
	echo ( '<script type="text/javascript">
	var ajaxurl = "' . esc_url(admin_url('admin-ajax.php')) . '";
    var get_site_url = "' . esc_url($site_url) . '";
    </script>' );
}
add_filter( 'wp_head', 'get_ajax_url' );
add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_cart_fragments', 11); 
function dequeue_woocommerce_cart_fragments() { 
	if (is_front_page()) { 
		wp_dequeue_script('wc-cart-fragments'); 
	}
		$blinds_list_id = get_option('blinds_list');
	$blinds_config_id = get_option('blinds_config');
	$shutter_single_type_id = get_option('shutter_single_type');
	$curtains_single_id = get_option('curtains_single');
	$curtain_config_id = get_option('curtain_config');
	$shutter_config = get_option('shutter_config');
	if (is_page($shutter_config)) {
		wp_enqueue_style( 'blindmatrix_custom_css', untrailingslashit( plugins_url( '/', BM_ECO_PLUGIN_FILE )) . '/assets/css/custom.css', array(), '1.0' );
	}

	if(!bm_is_flatsome_theme_activated()){
		wp_enqueue_style( 'blindmatrix__flat_css_api', untrailingslashit( plugins_url( '/', BM_ECO_PLUGIN_FILE )) . '/assets/css/flat.css' , array(), '1.0');
	}
	
	wp_enqueue_style( 'blindmatrix_css_api', untrailingslashit( plugins_url( '/', BM_ECO_PLUGIN_FILE )) . '/assets/css/style.css' , array(), '1.0');
	if ((is_page($shutter_config) || is_page($blinds_list_id) || is_page($blinds_config_id)  ||  is_page($shutter_single_type_id) ||  is_page($curtains_single_id)  ||  is_page($curtain_config_id)) && !bm_is_flatsome_theme_activated()) {

		wp_register_script('blindmatrix__flat_js_api', untrailingslashit( plugins_url( '/', BM_ECO_PLUGIN_FILE )) . '/assets/js/flat.js', array('jquery'), '1.0');
		
		wp_localize_script( 'blindmatrix__flat_js_api', 'flatsomeVars', array(
			'ajaxurl'       => admin_url( 'admin-ajax.php' ),
			'rtl'           => '',
			'sticky_height' => '',
			'lightbox'      => array(
				'close_markup'     => '',
				'close_btn_inside' => '',
			),
			'user'          => array(
				'can_edit_pages' => current_user_can( 'edit_pages' ),
			),
			'i18n'          => array(
				'mainMenu' => '',
			),
			'options'       => array(
				'cookie_notice_version' => '',
			),
		) );
		wp_enqueue_script('blindmatrix__flat_js_api');
	}
	
	wp_register_script('blindmatrix__custom_js', untrailingslashit( plugins_url( '/', BM_ECO_PLUGIN_FILE )) . '/assets/js/custom.js', array(), '1.0');
	wp_enqueue_script('blindmatrix__custom_js');
	
}

add_action('init', 'get_products_and_category');
function get_products_and_category() {
	$get_products_and_categories = CallAPI('GET', array('mode'=>'products_and_category'));
	update_option( 'productlist', $get_products_and_categories);
}

add_action( 'woocommerce_init', 'wc_init_currency_symbol' );
function wc_init_currency_symbol() {
	$currency = get_woocommerce_currency();
	$symbols = get_woocommerce_currency_symbols();
	$_SESSION['currencysymbol'] = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';
	
}

function truncate_description( $text, $chars = 25) {
	if (strlen($text) <= $chars) {
		return $text;
	}
	$text = $text . ' ';
	$text = substr($text, 0, $chars);
	$text = substr($text, 0, strrpos($text, ' '));
	$text = $text . '...';
	return $text;
}

function replace_fabric_color_path( $imagepath) {
	$change_url = 'https://ecommerceimages.blindsmatrix.co.uk';
	$url = 'https://blindmatrix.biz/modules/PriceBooks/fabric_color';
	$image_path = str_replace($url, $change_url, $imagepath);
	
	return $image_path;
}

function custom_logs( $message) { 
	if (is_array($message)) { 
		$message = json_encode($message); 
	} 
	$file = fopen('custom_logs.log', 'a'); 
	fwrite($file, "\n" . gmdate('Y-m-d h:i:s') . ' :: ' . $message); 
	fclose($file); 
}

function safe_encode( $string) {
	return strtr(base64_encode($string), '+/=', '-_-');
}

function safe_decode( $string) {
	return base64_decode(strtr($string, '-_-', '+/='));
}

function clean( $string) {
	$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
function checkForSampleId( $id, $array) {
	$k=0;
	foreach ($array as $key => $val) {
		if ($val['sample'] === $id) {
			$k +=1;
		}
	}
	return $k;
}
function checkForSameId( $id, $array) {
	$k=0;
	foreach ($array as $key => $val) {
		$check_SameId = $val['product_code'] . $val['fabricid'] . $val['colorid'];
		if ($check_SameId === $id) {
			$k = 1;
		}
	}
	return $k;
}
function getproducticon( $productname, $foldername) {
	
	$product_icon = BM_ECO_ABSPATH . '/vendor/Shortcode-Source/image/' . $foldername ;

	   
	$scan = scandir($product_icon);
	   
 
	foreach ($scan as $file) {
		if (!is_dir($product_icon . "/$file")) {
			$searcharr[] = plugin_dir_url(__FILE__ ) . 'vendor/Shortcode-Source/image/' . $foldername . '/' . $file;
		}
	}
	
	$filterArray = array_filter($searcharr, function ( $var) use ( $productname) {
		if (strpos($var, $productname) == true) {
			return $var;
		}
	});
	$filterArray = array_values($filterArray);
	$menuproducticon = $filterArray[0];
	if ('' == $filterArray[0]) {
		$menuproducticon =  plugin_dir_url(__FILE__) . 'vendor/Shortcode-Source/image/product_icons/default_blinds.svg';
	}
	return $menuproducticon;
}

function bm_register_settings() {
	add_option( 'Api_Url', 'https://blindmatrix.biz/api/api-ecommerce-live.php');
	add_option( 'Api_Name', 'YOURBLINDSSHOP');
	/*  register_setting( 'bm_options_group', 'Api_Url', 'bm_callback' );
	register_setting( 'bm_options_group', 'Api_Name', 'bm_callback' ); */
}
add_action( 'admin_init', 'bm_register_settings' );

function bm_register_options_page() {
	$icon = plugin_dir_url(__FILE__) . 'assets/image/icon.png';
	add_menu_page('Blindmatrix ECommerce', 'Blindmatrix ECommerce', 'manage_options', 'bm', 'bm_options_page', $icon, 2);
	add_submenu_page('bm', 'Dashboard', 'Dashboard', 'manage_options', 'bm', 'bm_options_page' );
}
add_action('admin_menu', 'bm_register_options_page');

include 'blind_settings.php';


function BlindMatrix_Hub( $attrs, $content = null) {
	if(is_admin()){
		return;
	}
	$attrs = shortcode_atts(
		array(
			'title' => 'true',
			'desc' => 'true',
			'price' => 'true',
			'products' => '',
			'style'=> 'layout1',
			'source'=>'',
		), $attrs, 'BlindMatrix');
	if (isset($attrs['source'])) {
		$file = strip_tags($attrs['source']);
		if ('/' != $file[0]) {
			$theme_file_path = get_stylesheet_directory().'/'.basename( plugin_dir_url(__FILE__) ).'/'.$file.'.php';
			$file = BM_ECO_ABSPATH . '/vendor/Shortcode-Source/' . $file . '.php';
			if(file_exists($theme_file_path )){
				$file = $theme_file_path;
			}
			$file = apply_filters('blindmatrix_shortcode_path',$file,$attrs);
		}

		if(!file_exists($file)){
			return;
		}
	
		ob_start();
		include($file);
		$buffer = ob_get_clean();
		$options = get_option('BlindMatrix', array());
		if (isset($options['shortcode'])) {
			$buffer = do_shortcode($buffer);
		}
	} else {
		$tmp = '';
		foreach ($attrs as $key => $value) {
			if ('src' == $key) {
				$value = strip_tags($value);
			}
			$value = str_replace('&amp;', '&', $value);
			if ('src' == $key) {
				$value = strip_tags($value);
			}
			$tmp .= ' ' . $key . '="' . $value . '"';
		}
		$buffer = '<iframe' . $tmp . '></iframe>';
	}
	return $buffer;
}

// Here because the funciton MUST be define before the "add_shortcode" since 
// "add_shortcode" check the function name with "is_callable".
add_shortcode('BlindMatrix', 'BlindMatrix_Hub');


// The ajax shortcode function


//Custom Style  and custom js added admin style sheet
add_action('admin_enqueue_scripts', 'adding_js_css_bm_backend');

function adding_js_css_bm_backend( $hook) {
	wp_register_style( 'jquery-confirmcss', untrailingslashit( plugins_url( '/', BM_ECO_PLUGIN_FILE ) ) . '/assets/css/jquery-confirm.css', array(), '1.0' );
	wp_enqueue_style('jquery-confirmcss');
	wp_register_script( 'jquery-confirmjs', untrailingslashit( plugins_url( '/', BM_ECO_PLUGIN_FILE ) ) . '/assets/js/jquery-confirm.js', array(), '1.0', true );
	wp_enqueue_script('jquery-confirmjs');
	wp_enqueue_style( 'wp-color-picker' );
	wp_register_style( 'blindmatrix-inline-style', false, array(), '1.0');
	wp_enqueue_style( 'blindmatrix-inline-style' );
	$dir = plugin_dir_url( __FILE__ );
	$css= ".jconfirm-title-c.titleClassCk span.jconfirm-title:before{
		background: url($dir/assets/css/icons/crown.png) no-repeat;
	}";
	wp_add_inline_style( 'blindmatrix-inline-style', $css );
	wp_register_style( 'blindmatrix_api', untrailingslashit( plugins_url( '/', BM_ECO_PLUGIN_FILE ) ) . '/assets/css/admin_style.css', array(), '1.0');
	wp_enqueue_style( 'blindmatrix_api' );
	wp_register_script('custom_js', untrailingslashit( plugins_url( '/', BM_ECO_PLUGIN_FILE ) ) . '/assets/js/Script.js', array('jquery', 'wp-color-picker', 'jquery-blockui'), '1.0');
	wp_enqueue_script('custom_js');
	wp_localize_script(
			'custom_js',
			'bm_custom_js_params',
			array(
				'ajaxurl'                  => admin_url( 'admin-ajax.php' ),
			)
		);

	if ('blindmatrix-ecommerce_page_bmsettings' == $hook) {	
		if ( ! did_action( 'wp_enqueue_media' ) ) {	
			wp_enqueue_media();	
		}	
		wp_enqueue_script( 'myuploadscript', untrailingslashit( plugins_url( '/', BM_ECO_PLUGIN_FILE ) ) . '/assets/js/media.js', array( 'jquery' ), '1.0' );	
	}
}

function custom_new_product_image( $_product_img, $cart_item, $cart_item_key ) {
	$targeted_id = get_option('blindproduct');
	if ( $cart_item['product_id'] == $targeted_id) { 
		$_product_img = '<img src="' . $cart_item['new_product_image_path'] . '"/>';
	}
	return $_product_img;
}

function custom_cart_item_permalink( $product_url, $cart_item, $cart_item_key ) {
	$targeted_id = get_option('blindproduct');
	if ( $cart_item['product_id'] == $targeted_id) { 
		$product_url = $cart_item['new_product_url'];
	}
	return $product_url;
}
function custom_order_item_permalink( $product_url, $cart_item, $cart_item_key ) {	
	$targeted_id = get_option('blindproduct');
	if ( $cart_item['product_id'] == $targeted_id) { 
		$product_url = $cart_item['new_product_url'];
	}
	return $product_url;
}

add_filter( 'woocommerce_cart_item_thumbnail', 'custom_new_product_image', 10, 3 );
add_filter( 'woocommerce_cart_item_permalink', 'custom_cart_item_permalink', 10, 3 );
add_filter( 'woocommerce_order_item_permalink', 'custom_order_item_permalink', 10, 3 );

// Part 1 
// Display Radio Buttons
add_action( 'woocommerce_review_order_before_order_total', 'bbloomer_checkout_radio_choice', 20 );
function bbloomer_checkout_radio_choice() {
	if (!checkBlindProduct()) {
		return;
	}
	
	$domain = 'wocommerce';
	
	$ecommerce_default_deltype = WC()->session->get( 'ecommerce_default_deltype' );
	$delivery_array = WC()->session->get( 'delivery_array' );
	
	if (( 7 == $ecommerce_default_deltype || 9 == $ecommerce_default_deltype ) && ( !empty($delivery_array) )) :

		echo '<tr class="delivery-radio"><th style="text-align:center;" colspan="2"><h4>Choose option</h4></th></tr><tr class="delivery-radio"><td colspan="2">';

		$chosen = WC()->session->get('radio_chosen');
		$chosen = empty($chosen) ? WC()->checkout->get_value('radio_choice') : $chosen;
		$chosen = empty( $chosen ) ? '0' : $chosen;
		
		// Add a custom checkbox field
		$args = array(
			'type' => 'radio',
			'class' => array( 'form-row-wide', 'update_totals_on_change' ),
			'options' => $delivery_array,
			'default' => $chosen
		);
		
		woocommerce_form_field( 'radio_choice', $args, $chosen );

		echo '</td></tr>';

	endif;
}
  
// Part 2 
// Add Fee and Calculate Total
add_action( 'woocommerce_cart_calculate_fees', 'bbloomer_checkout_radio_choice_fee' );
function bbloomer_checkout_radio_choice_fee() {
	if (!checkBlindProduct()) {
		return;
	}
	
	global $woocommerce;
	
	/*echo '<pre>';
	print_r($woocommerce->cart->cart_contents);
	echo '</pre>';*/
	
	$productid_array = array();
	$width_array = array();
	foreach ($woocommerce->cart->cart_contents as $cart_contents) {
		$productid_array[] = isset($cart_contents['blinds_order_item_data']['productid']) ? $cart_contents['blinds_order_item_data']['productid']:'';
		$unit = isset($cart_contents['blinds_order_item_data']['unit']) ?$cart_contents['blinds_order_item_data']['unit']:'';
		$width = isset( $cart_contents['blinds_order_item_data']['width']) ?  $cart_contents['blinds_order_item_data']['width']:'';
		$width_array[] = $width . '~~' . $unit;
	}
	
	$radio = WC()->session->get( 'radio_chosen' );
	
	$sub_total = $woocommerce->cart->get_subtotal();
	
	$deliveryid = '';

	$resdeliverydetails = CallAPI('GET', array('mode'=>'getdeliverycostdetails','sel_delivery_id'=>$deliveryid,'netprice'=>$sub_total,'productid_array'=>$productid_array,'width_array'=>$width_array));
	
	$delivery_array = array();
	$default_delivery_cost = '';
	$default_delivery_id = '';
	$sel_delivery_name = '';
	$delivery_cost_value = '';
	$delivery_widthout_cost = 0;
	if (7 == $resdeliverydetails->ecommerce_default_deltype) {
		if (is_array($resdeliverydetails->deliverycostdetails) && count($resdeliverydetails->deliverycostdetails) > 0) {
			foreach ($resdeliverydetails->deliverycostdetails as $deliverycostdetails) {
				$incvat = ( $deliverycostdetails->cost / 100 ) * $resdeliverydetails->vaterate;
				$delivery_cost_incvat = $deliverycostdetails->cost+$incvat;
				$deliverycostincvat = number_format(round($delivery_cost_incvat, 2), 2);
				
				$delivery_array[$deliverycostdetails->id] = $deliverycostdetails->name;
				
				if ('' != $radio && $deliverycostdetails->id == $radio) {
					$delivery_cost_value = $deliverycostincvat;
					$sel_delivery_name = $deliverycostdetails->name;
					$delivery_widthout_cost = $deliverycostdetails->cost;
				} else if ('' == $radio && '1' == $deliverycostdetails->default_delcost) {
					$default_delivery_cost = $deliverycostincvat;
					$default_delivery_id = $deliverycostdetails->id;
					$sel_delivery_name = $deliverycostdetails->name;
					$delivery_cost_value = $deliverycostincvat;
					$delivery_widthout_cost = $deliverycostdetails->cost;
				}
			}
		}
		
		if ('' != $default_delivery_id && '' == $radio) {
			WC()->session->set( 'radio_chosen', $default_delivery_id );
		}
		
		WC()->session->set( 'delivery_charges', round($delivery_widthout_cost, 2) );
		WC()->session->set( 'delivery_array', $delivery_array );
   
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		if ( $radio ) {
			$delivery_name = 'Delivery (' . $sel_delivery_name . ')';
			$woocommerce->cart->add_fee( $delivery_name, $delivery_cost_value );
		}
	} else if (9 == $resdeliverydetails->ecommerce_default_deltype) {
		
		$delivery_array[1] = 'Normal';
		$delivery_array[2] = 'Fastrack';

		if (2 == $radio) {
			$get_delivery_cost = $resdeliverydetails->defaultdeliverydetails->cost+$resdeliverydetails->defaultdeliverydetails->fastrackcost;
			if ('' != $resdeliverydetails->defaultdeliverydetails->sizecost) {
				$get_delivery_cost = $get_delivery_cost + $resdeliverydetails->defaultdeliverydetails->sizecost;
			}
			
			$incvat = ( $get_delivery_cost / 100 ) * $resdeliverydetails->vaterate;
			$delivery_cost_incvat = $get_delivery_cost+$incvat;
			$deliverycostincvat = number_format(round($delivery_cost_incvat, 2), 2);
			$delivery_cost_value = $deliverycostincvat;
			$sel_delivery_name = 'Fastrack';
			$delivery_widthout_cost = $get_delivery_cost;
		} else {
			$get_delivery_cost = $resdeliverydetails->defaultdeliverydetails->cost;
			if ('' != $resdeliverydetails->defaultdeliverydetails->sizecost) {
				$get_delivery_cost = $get_delivery_cost + $resdeliverydetails->defaultdeliverydetails->sizecost;
			}
			$incvat = ( $get_delivery_cost / 100 ) * $resdeliverydetails->vaterate;
			$delivery_cost_incvat = $get_delivery_cost+$incvat;
			$deliverycostincvat = number_format(round($delivery_cost_incvat, 2), 2);
			$default_delivery_id = 1;
			$sel_delivery_name = 'Normal';
			$delivery_cost_value = $deliverycostincvat;
			$delivery_widthout_cost = $get_delivery_cost;
		}

		if ('' != $default_delivery_id && '' == $radio) {
			WC()->session->set( 'radio_chosen', $default_delivery_id );
		}
		
		WC()->session->set( 'delivery_charges', round($delivery_widthout_cost, 2) );
		WC()->session->set( 'delivery_array', $delivery_array );
   
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		if ( $radio ) {
			$delivery_name = 'Delivery (' . $sel_delivery_name . ')';
			$woocommerce->cart->add_fee( $delivery_name, $delivery_cost_value );
		}
		
	} else {
		if (10 != $resdeliverydetails->ecommerce_default_deltype) {
			$incvat = ( $resdeliverydetails->defaultdeliverydetails->cost / 100 ) * $resdeliverydetails->vaterate;
			$delivery_cost_incvat = $resdeliverydetails->defaultdeliverydetails->cost+$incvat;
			$deliverycostincvat = number_format(round($delivery_cost_incvat, 2), 2);
			
			$delivery_charges = round($resdeliverydetails->defaultdeliverydetails->cost, 2);
			WC()->session->set( 'delivery_charges', $delivery_charges );
			WC()->session->set( 'radio_chosen', '' );
			
			$woocommerce->cart->add_fee( __('Delivery', 'woocommerce'), $deliverycostincvat );
		}
	}
	WC()->session->set( 'ecommerce_default_deltype', $resdeliverydetails->ecommerce_default_deltype );
}
  
// Part 3 
// Add Radio Choice to Session
add_action( 'woocommerce_checkout_update_order_review', 'bbloomer_checkout_radio_choice_set_session' );
function bbloomer_checkout_radio_choice_set_session( $posted_data ) {
	parse_str( $posted_data, $output );
	if ( isset( $output['radio_choice'] ) ) {
		WC()->session->set( 'radio_chosen', $output['radio_choice'] );
	}
}

add_filter( 'woocommerce_new_order', 'woocommerce_change_order_number', 1, 1  );
function woocommerce_change_order_number( $order_id) {
	// do your magic here
	if (!checkBlindProduct()) {
		return;
	}
	$json_ordernum_response = CallAPI('POST', array('mode'=>'salesorderprefix'));
	$order_number = $json_ordernum_response->salesorderprefix;
	update_post_meta($order_id, '_order_number', esc_attr(htmlspecialchars($order_number)));
	
	$delivery_charges = WC()->session->get( 'delivery_charges' );
	update_post_meta($order_id, 'bm_delivery_charges', esc_attr($delivery_charges));
	
}

add_action( 'woocommerce_payment_complete', 'so_payment_complete', 10, 1 );
function so_payment_complete( $order_id ) {
	if (!checkBlindProduct($order_id)) {
		return;
	}
	$bm_sales_order_id = get_post_meta($order_id, 'bm_sales_order_id', true);
	if ('' == $bm_sales_order_id) {
		$order = wc_get_order( $order_id );
		$user = $order->get_user();
		
		$payment_method = $order->get_payment_method();
		$payment_method_title = $order->get_payment_method_title();
		$total = $order->get_total(); // need check this
		
		#get billing details
		$billing_first_name = $order->get_billing_first_name();
		$billing_last_name = $order->get_billing_last_name();
		$billing_company = $order->get_billing_company();
		$billing_address_1 = $order->get_billing_address_1();
		$billing_address_2 = $order->get_billing_address_2();
		$billing_city = $order->get_billing_city();
		$billing_country = $order->get_billing_state();
		$billing_postcode = $order->get_billing_postcode();
		$billing_county = $order->get_billing_country();
		$billing_email = $order->get_billing_email();
		$billing_phone = $order->get_billing_phone();
		
		//get shipping details
		$shipping_first_name = $order->get_shipping_first_name();
		$shipping_last_name = $order->get_shipping_last_name();
		$shipping_address_1 = $order->get_shipping_address_1();
		$shipping_address_2 = $order->get_shipping_address_2();
		$shipping_city = $order->get_shipping_city();
		$shipping_state = $order->get_shipping_state();
		$shipping_postcode = $order->get_shipping_postcode();
		$shipping_country = $order->get_shipping_country();

		$orderitemval = array();
		// Loop through order line items
		$i=0;
		foreach ( $order->get_items() as $item ) {
			// get order item data (in an unprotected array)
			$item_data = $item->get_data();
			
			$item_quantity  = $item->get_quantity(); // Get the item quantity
			
			// NOTICE! Understand what this does before running. 
			$order_item_data = wc_get_order_item_meta($item_data['id'], 'blinds_order_item_data', true);
			foreach ($order_item_data as $key => $value) {
				if (is_array($value) && !empty($value)) {
					$orderitemval[$i][$key] = array_filter($value);
				} else if (!empty($value)) {
					$orderitemval[$i][$key] = $value;
				}
			}
			$orderitemval[$i]['quantity'] = $item_quantity;
			$i++;    
		}
		$orderitemdata = serialize($orderitemval);

		//$delivery_charges = WC()->session->get( 'delivery_charges' );
		$delivery_charges = get_post_meta($order_id, 'bm_delivery_charges', true);
		
		$user_id =get_current_user_id();
		$customerid = get_user_meta($user_id, 'bindCustomerid', true);

		$request            = blindmatrix_get_request();
		$FirstName 			= $request['FirstName'];
		$LastName 			= $request['LastName'];
		$MobileNumber 		= $request['MobileNumber'];
		$Email 				= $request['Email'];
		if ('' == $customerid) {
			$json_customer_response = CallAPI('POST', array('mode'=>'guestlogin', 'FirstName'=>$billing_first_name, 'LastName'=>$billing_last_name, 'MobileNumber'=>$billing_phone, 'Email'=>$billing_email));
			$customerid = $json_customer_response->customerid;
		}
		if ($customerid > 0) {
			$json_order_response = CallAPI('POST', array('mode'=>'place_order', 'customerid'=>$customerid, 'salesorderid'=>'', 'billing_email'=>$billing_email, 'billing_first_name'=>$billing_first_name, 'billing_last_name'=>$billing_last_name, 'billing_company'=>$billing_company, 'billing_address_1'=>$billing_address_1, 'billing_address_2'=>$billing_address_2, 'billing_city'=>$billing_city, 'billing_county'=>$billing_county, 'billing_postcode'=>$billing_postcode, 'billing_phone'=>$billing_phone, 'billing_country'=>$billing_country, 'delivery_charges'=>$delivery_charges, 'orderitemval'=>$orderitemdata, 'paymentMethod'=>$payment_method, 'payment_method_title'=>$payment_method_title, 'amount'=>$total, 'shipping_first_name'=>$shipping_first_name, 'shipping_last_name'=>$shipping_last_name, 'shipping_address_1'=>$shipping_address_1, 'shipping_address_2'=>$shipping_address_2, 'shipping_city'=>$shipping_city, 'shipping_state'=>$shipping_state, 'shipping_postcode'=>$shipping_postcode, 'shipping_country'=>$shipping_country, 'order_status'=>'Invoiced'));
			$salesorderid = $json_order_response->salesorderid;
			$salesorder_no = $json_order_response->salesorder_no;
			
			update_post_meta($order_id, 'bm_sales_order_id', esc_attr($salesorderid));
			
			WC()->session->set( 'radio_chosen', '' );
			WC()->session->set( 'delivery_charges', '' );
			WC()->session->set( 'delivery_array', '' );
			WC()->session->set( 'ecommerce_default_deltype', '' );
		}
	}
}


//cart page and checkout page header title

//add_action('woocommerce_before_checkout_form', 'before_text_checkoutform');
function before_text_checkoutform() {
	ob_start();
	if ( is_user_logged_in() ) {
		$logedinClass = 'isLogin';
	} else {
		$logedinClass = 'notLogin';
	}
	?>
<div class="before-cart row">
<div class="col large-7 pb-0 <?php echo wp_kses_post( $logedinClass ); ?>">
<h3 class="checkout">Complete Your Order</h3>
</div>
<div class="col large-5 <?php echo wp_kses_post( $logedinClass ); ?>">
<div id="logo" class="flex-col logo cart-logo">
<center>
	<?php get_template_part('template-parts/header/partials/element', 'logo'); ?>
  </div></center></div>
	<?php 
	$out1 = ob_get_contents();
	ob_end_clean();
	echo wp_kses_post($out1);
	?>
</div>
	<?php
}
//add_action('woocommerce_before_cart','before_text_cart');

function before_text_cart() {
	ob_start();
	if ( is_user_logged_in() ) {
		$logedinClass = 'isLogin';
	} else {
		$logedinClass = 'notLogin';
	}
	?>

<div class="before-cart row">

<div class="col large-7 pb-0 <?php echo wp_kses_post( $logedinClass ); ?>">
<h3 class="checkout">Your Shopping Cart</h3>
</div>
<div class="col large-5 <?php echo wp_kses_post( $logedinClass ); ?>">
<div id="logo" class="flex-col logo cart-logo">
<center>
	<?php get_template_part('template-parts/header/partials/element', 'logo'); ?>
  </div></center></div>
	<?php 
	$out1 = ob_get_contents();
	ob_end_clean();
	echo wp_kses_post($out1);
	?>
</div>
	<?php
}

add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields', 999);
function custom_override_checkout_fields( $fields) {
	$fields['billing']['billing_first_name']['priority'] = 1;
	$fields['billing']['billing_last_name']['priority'] = 2;

		$fields['billing']['billing_phone']['priority'] = 3;
		$fields['billing']['billing_email']['priority'] = 4;
		$fields['billing']['billing_address_1']['priority'] = 5;
		$fields['billing']['billing_address_2']['priority'] = 6;
		$fields['billing']['billing_city']['priority'] = 7;
		$fields['billing']['billing_state']['priority'] = 8;
		$fields['billing']['billing_country']['priority'] = 9;
		$fields['billing']['billing_postcode']['priority'] = 10;
	if ( is_user_logged_in() ) {
		$fields['billing']['billing_email'] = array(
			'label' => 'Email address',
			'required'  => false,
			'custom_attributes' => array(
				'disabled' => 'disabled',
			)
		);
	} else {
		$fields['billing']['billing_email'] = array(
			'label' => 'Email address',
			'required'  => true,

		);
	}
	 unset($fields['order']['order_comments']);
	return $fields;
}
add_filter( 'woocommerce_enable_order_notes_field', '__return_false', 9999 );
if ( ! function_exists( 'wc_display_item_meta' ) ) {
	/**
	 * Display item meta data.
	 *
	 * @since  3.0.0
	 * @param  WC_Order_Item $item Order Item.
	 * @param  array         $args Arguments.
	 * @return string|void
	 */
	function wc_display_item_meta( $item, $args = array() ) {
		$strings = array();
		$html    = '';
		$args    = wp_parse_args(
			$args,
			array(
				'before'       => '<ul class="wc-item-meta"><li>',
				'after'        => '</li></ul>',
				'separator'    => '</li><li>',
				'echo'         => true,
				'autop'        => false,
				'label_before' => '<strong class="wc-item-meta-label">',
				'label_after'  => ':</strong> ',
			)
		);
		
		foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
			
			if ('new_product_url' !== $meta->key && 'blinds_order_item_data' !== $meta->key && 'new_product_image_path' !== $meta->key) { 
				$value     = $args['autop'] ? wp_kses_post( $meta->display_value ) : wp_kses_post( make_clickable( trim( $meta->display_value ) ) );
				$strings[] =  $value;
			}
		}

		if ( $strings ) {
			$html = $args['before'] . implode( $args['separator'], $strings ) . $args['after'];
		}
		/**
		 * Display item meta.
		 *
		 * @since 1.0
		 */
		$html = apply_filters( 'woocommerce_display_item_meta', $html, $item, $args );

		if ( $args['echo'] ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo wp_kses_post($html);
		} else {
			return $html;
		}
	}
}
add_action( 'wp_ajax_ajax_search_products', 'ajax_search_products', 1 );
add_action( 'wp_ajax_nopriv_ajax_search_products', 'ajax_search_products', 1 );

function ajax_search_products() {
	$action = ( isset( $_REQUEST['action'] ) ) ? wc_clean(wp_unslash($_REQUEST['action'])) : '';
		if (isset($_REQUEST['query']) && '' == wc_clean(wp_unslash($_REQUEST['query'])) ) {
			wp_send_json('');
			wp_die( '0' );
		}
		global $product_page;
		global $shutters_page;
		global $curtains_single_page;
		global $blinds_config;
		$get_productlist = get_option('productlist', true);
		$query = isset($_REQUEST['query']) ? wc_clean(wp_unslash($_REQUEST['query'])):'';
		$response = CallAPI('GET', array('mode'=>'searchecommerce', 'search_text'=>$query, 'search_type'=>'overall', 'page'=>'1', 'rows'=>'100'));
		$fabric_list = $response->fabric_list;
		
		$product = array();
		$blindmatrix_settings = get_option('option_blindmatrix_settings', true);
		if (in_array('Blinds', $blindmatrix_settings['menu_product_type']) ) {
			if (is_array($fabric_list) && count($fabric_list) > 0) {
				foreach ($fabric_list as $key=>$searchval) {
					if (1 == $searchval->skipcolorfield) {
						$urlfcname = $searchval->colorname;
					} else {
						$urlfcname = $searchval->fabricname . '-' . $searchval->colorname;
					}
					$urlproname = str_replace(' ', '-', strtolower($searchval->productname));
					$urlfcname = str_replace(' ', '-', strtolower($urlfcname));
					$newurl = safe_encode($searchval->product_no . '/' . $searchval->producttypeid . '/' . $searchval->fabricid . '/' . $searchval->colorid . '/' . $searchval->vendorid);
					$productviewurl = get_bloginfo('url') . '/' . $blinds_config . '/' . $urlproname . '/' . $urlfcname . '/' . $newurl . '/';
					
					$product['blind_' . $key]['name'] = $searchval->fabricname . ' ' . $searchval->colorname . ' ' . trim($searchval->productname);
					$product['blind_' . $key]['url'] = $productviewurl;
					$product['blind_' . $key]['img'] = $searchval->imagepath;
					$currency_symbol = get_woocommerce_currency_symbol();
					$product['blind_' . $key]['price'] = $currency_symbol . $searchval->price;
				}
			}
		}

		if (in_array('Shutters', $blindmatrix_settings['menu_product_type']) ) {
			if (is_array($get_productlist->shutter_product_list) && count($get_productlist->shutter_product_list) > 0) {
				foreach ($get_productlist->shutter_product_list as $shutter_product_list) {
					if (is_array($shutter_product_list->GetShutterProductTypeList) && count($shutter_product_list->GetShutterProductTypeList) > 0) {
						$inc_shutterproducts = isset($blindmatrix_settings['shutterlistproid']) && is_array($blindmatrix_settings['shutterlistproid']) && !empty($blindmatrix_settings['shutterlistproid']) ? array_keys($blindmatrix_settings['shutterlistproid']): array();  
						foreach ($shutter_product_list->GetShutterProductTypeList as $keys=>$GetShutterProductTypeList) {
							if (!isset($GetShutterProductTypeList->parameterTypeId) || empty($inc_shutterproducts) || !in_array($GetShutterProductTypeList->parameterTypeId, $inc_shutterproducts)) {
								continue;
							} 
							$url_productTypeSubName = str_replace(' ', '-', $GetShutterProductTypeList->productTypeSubName);
							$product['shuther_' . $keys]['name'] = $GetShutterProductTypeList->productTypeSubName;
							$product['shuther_' . $keys]['url'] = get_bloginfo('url') . '/' . $shutters_page . '/' . trim(strtolower($url_productTypeSubName)) . '/' . $GetShutterProductTypeList->parameterTypeId; 
							$product['shuther_' . $keys]['img'] =  $GetShutterProductTypeList->imgurl;
							$product['shuther_' . $keys]['price'] =  '';
						}
					}
				}
			}
		}
		if (in_array('Curtains', $blindmatrix_settings['menu_product_type']) ) {
			$curtain_products = array('pencil-pleat','eyelet','goblet','goblet-buttoned','double-pinch','double-pinch-buttoned','triple-pinch','triple-pinch-buttoned');
			$inc_products = isset($blindmatrix_settings['curtainlistproid']) && is_array($blindmatrix_settings['curtainlistproid']) && !empty($blindmatrix_settings['curtainlistproid']) ? array_keys($blindmatrix_settings['curtainlistproid']): array();  
			foreach ($curtain_products as $keyss=>$curtain_product) {
				if (!in_array($curtain_product, $inc_products)) {
					continue;
				}
				$product['curtain_' . $keyss]['name'] = str_replace('-', ' ', ucfirst($curtain_product));
				$product['curtain_' . $keyss]['url'] = get_bloginfo('url') . '/' . $curtains_single_page . '/' . $curtain_product;
				$product['curtain_' . $keyss]['img'] =untrailingslashit( plugins_url( '/', BM_ECO_PLUGIN_FILE )) . '/vendor/Shortcode-Source/image/headertype_icon/' . $curtain_product . '.png';
				$product['curtain_' . $keyss]['price'] = '';
			}
		}
		$query = isset($_REQUEST['query']) ? wc_clean(wp_unslash($_REQUEST['query'])):'';
		if ($query) {
			$result = array_filter($product, function ( $item) use ( $query) {
				$main_category_name = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $item['name'])));
				if ( ( stripos($item['name'], $query) !== false ) ) {
					return true;
				}
				return false;
			});
			$searcharrfilter = array_values($result);
		}
		if (is_array($searcharrfilter) &&  count($searcharrfilter) > 0) {
			$searchresult=array();
			foreach ($searcharrfilter as $keyss=>$searchval) {
					$searchresult['type'] = 'Product';
					$searchresult['id'] = $keyss;
					$searchresult['value'] = $searchval['name'];
					$searchresult['url'] = $searchval['url'];
					$searchresult['img'] = $searchval['img'];
					$searchresult['price'] = $searchval['price'];
				
				$searchresultlist[] = $searchresult;
			}
			$return['suggestions'] = $searchresultlist;
		} else {
			$return['suggestions'] = array(
			array(
				'id'    => -1,
				'value' => 'No products found.',
				'url'   => ''
			)
			);
		}

		wp_send_json($return);
		wp_die( '0' );
	
}

add_action( 'user_register', 'blindRegistrationSave', 10, 1 );
 
function blindRegistrationSave( $user_id ) {
	$user= get_userdata($user_id);

	$json_response = CallAPI('POST', array('mode'=>'register', 'FirstName'=>'', 'LastName'=>$user->user_login, 'MobileNumber'=>'', 'Email'=>$user->user_email, 'Password'=>$user->user_pass, 'ConfirmPassword'=>$user->user_pass));
	
	if (isset($json_response->customerid) && !empty($json_response->customerid)) {
		
		add_user_meta($user_id, 'bindCustomerid', $json_response->customerid);
			
		update_user_meta($user_id, 'bindCustomerid', $json_response->customerid);
	
	}
	

}
function wpdocs_clear_transient_on_logout() {
	session_unset();
	session_destroy();
}
add_action( 'wp_logout', 'wpdocs_clear_transient_on_logout' );

add_action( 'woocommerce_thankyou', 'custom_woocommerce_auto_complete_order' );
function custom_woocommerce_auto_complete_order( $order_id ) { 
	if ( ! $order_id ) {
		return;
	}

		// get all the order data
	  $order = new WC_Order($order_id);
	  if ( $order->has_status( 'cancelled' ) || $order->has_status( 'failed' )) {
		wp_safe_redirect( wc_get_cart_url() );
		exit;
	  }
	  
	  //get the user email from the order
	  $order_email = $order->billing_email;
		
	  // check if there are any users with the billing email as user or email
	  $email = email_exists( $order_email );  
	  $user = get_user_by( 'email', $order_email );
	if ( $user ) {
		$user = $user->ID;
	} else {
		$user = false;
	}
	  // if the UID is null, then it's a guest checkout
	if ( false == $user && false == $email) {
		// random password with 12 chars
		$random_password = wp_generate_password();
		/*
		// create new user with email as username & newly created pw
		$user_id = wp_create_user( $order_email, $random_password, $order_email );
		
		//WC guest customer identification
		update_user_meta( $user_id, 'guest', 'yes' );
	 
		//user's billing data
		update_user_meta( $user_id, 'billing_address_1', $order->billing_address_1 );
		update_user_meta( $user_id, 'billing_address_2', $order->billing_address_2 );
		update_user_meta( $user_id, 'billing_city', $order->billing_city );
		update_user_meta( $user_id, 'billing_company', $order->billing_company );
		update_user_meta( $user_id, 'billing_country', $order->billing_country );
		update_user_meta( $user_id, 'billing_email', $order->billing_email );
		update_user_meta( $user_id, 'billing_first_name', $order->billing_first_name );
		update_user_meta( $user_id, 'billing_last_name', $order->billing_last_name );
		update_user_meta( $user_id, 'billing_phone', $order->billing_phone );
		update_user_meta( $user_id, 'billing_postcode', $order->billing_postcode );
		update_user_meta( $user_id, 'billing_state', $order->billing_state );
	 
		// user's shipping data
		update_user_meta( $user_id, 'shipping_address_1', $order->shipping_address_1 );
		update_user_meta( $user_id, 'shipping_address_2', $order->shipping_address_2 );
		update_user_meta( $user_id, 'shipping_city', $order->shipping_city );
		update_user_meta( $user_id, 'shipping_company', $order->shipping_company );
		update_user_meta( $user_id, 'shipping_country', $order->shipping_country );
		update_user_meta( $user_id, 'shipping_first_name', $order->shipping_first_name );
		update_user_meta( $user_id, 'shipping_last_name', $order->shipping_last_name );
		update_user_meta( $user_id, 'shipping_method', $order->shipping_method );
		update_user_meta( $user_id, 'shipping_postcode', $order->shipping_postcode );
		update_user_meta( $user_id, 'shipping_state', $order->shipping_state );
		
		// link past orders to this newly created customer
		wc_update_new_customer_past_orders( $user_id );
		*/
	} else {
		
		$user_id = $user;
		//user's billing data
		update_user_meta( $user_id, 'billing_address_1', $order->billing_address_1 );
		update_user_meta( $user_id, 'billing_address_2', $order->billing_address_2 );
		update_user_meta( $user_id, 'billing_city', $order->billing_city );
		update_user_meta( $user_id, 'billing_company', $order->billing_company );
		update_user_meta( $user_id, 'billing_country', $order->billing_country );
		update_user_meta( $user_id, 'billing_email', $order->billing_email );
		update_user_meta( $user_id, 'billing_first_name', $order->billing_first_name );
		update_user_meta( $user_id, 'billing_last_name', $order->billing_last_name );
		update_user_meta( $user_id, 'billing_phone', $order->billing_phone );
		update_user_meta( $user_id, 'billing_postcode', $order->billing_postcode );
		update_user_meta( $user_id, 'billing_state', $order->billing_state );
	 
		// user's shipping data
		update_user_meta( $user_id, 'shipping_address_1', $order->shipping_address_1 );
		update_user_meta( $user_id, 'shipping_address_2', $order->shipping_address_2 );
		update_user_meta( $user_id, 'shipping_city', $order->shipping_city );
		update_user_meta( $user_id, 'shipping_company', $order->shipping_company );
		update_user_meta( $user_id, 'shipping_country', $order->shipping_country );
		update_user_meta( $user_id, 'shipping_first_name', $order->shipping_first_name );
		update_user_meta( $user_id, 'shipping_last_name', $order->shipping_last_name );
		update_user_meta( $user_id, 'shipping_method', $order->shipping_method );
		update_user_meta( $user_id, 'shipping_postcode', $order->shipping_postcode );
		update_user_meta( $user_id, 'shipping_state', $order->shipping_state );
		
		// link past orders to this newly created customer
		wc_update_new_customer_past_orders( $user_id );
		 
	}
	$order = wc_get_order( $order_id );
	$order->update_status( 'completed' );
}
remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button' );

add_filter( 'woocommerce_account_menu_items', 'remove_my_account_dashboard', 999, 1 );
function remove_my_account_dashboard( $menu_links ) {
	
	unset( $menu_links['downloads'] );
	unset( $menu_links['payment-methods'] );
	unset( $menu_links['customer-logout'] );
	return $menu_links;
 
}
function w3p_add_image_to_wc_emails( $args ) {
	$args['show_image'] = true;
	$args['image_size'] = array( 100, 50 );
	return $args;
}
add_filter( 'woocommerce_email_order_items_args', 'w3p_add_image_to_wc_emails' );

function blindLogin( $user_login, $user) {
	
	$json_response = CallAPI('POST', array('mode'=>'login', 'Email'=>$user->user_email));
	$user_id = $user->id;
	if (isset($json_response->customerid) && !empty($json_response->customerid)) {
		
		if (get_user_meta($user_id, 'bindCustomerid', true)) {
			update_user_meta($user_id, 'bindCustomerid', $json_response->customerid);
		} else {
			add_user_meta($user_id, 'bindCustomerid', $json_response->customerid);
		}
	}
}
//add_action( 'wp_login', 'blindLogin', 10, 2 );



function order_detail_label( $value1, $value2, $value3 ) {

	$value1['cart_subtotal']['label']='Order SubTotal:';
  
	$value1['order_total']['label']= 'Order Total:';
	return $value1;

   
   
}
add_filter( 'woocommerce_get_order_item_totals', 'order_detail_label', 10, 3 );


function remove_order_item_meta_fields( $fields ) {
	$fields[] = 'new_product_image_path';
	$fields[] = 'new_product_url';

	return $fields;
}
add_filter( 'woocommerce_hidden_order_itemmeta', 'remove_order_item_meta_fields' );

function change_woocommerce_admin_order_item_thumbnail( $image, $item_id, $item ) {
	global $post;
	if (!checkBlindProduct($post->ID)) {
		return;
	}
	ob_start();
	?>
	<a href="<?php echo esc_url($item->get_meta('new_product_url')); ?>" >
	<img src="<?php echo esc_url($item->get_meta('new_product_image_path')); ?>"  width="50" height="50"></a>

	<?php
	$image = ob_get_contents();
	ob_end_clean();
	return $image;
	
}
add_filter( 'woocommerce_admin_order_item_thumbnail', 'change_woocommerce_admin_order_item_thumbnail', 10, 3 );

function adding_my_account_orders_column( $columns ) {

	$new_columns = array();

	foreach ( $columns as $key => $name ) {

		$new_columns[ $key ] = $name;

		// add ship-to after order status column
		if ( 'order-status' === $key ) {
			$new_columns['track-my-order'] = __( 'Order Status', 'textdomain' );
		}
	}

	return $new_columns;
}
add_filter( 'woocommerce_my_account_my_orders_columns', 'adding_my_account_orders_column' );

function adding_my_account_orders_column_data( $order ) {
	

	$user_id =get_current_user_id();
	$customerid = get_user_meta($user_id, 'bindCustomerid', true);
	$bm_sales_order_id = get_post_meta( $order->get_id(), 'bm_sales_order_id', true ); 
	
	$respones = CallAPI('GET', array('mode'=>'getorderstatus', 'customerid'=>$customerid,'bm_sales_order_id'=>$bm_sales_order_id ));
	if (isset( $respones->selectstatusnotes)) {
		
		echo wp_kses_post($respones->selectstatusnotes);
	
	} else {
		echo '';
	}
}
add_action( 'woocommerce_my_account_my_orders_column_track-my-order', 'adding_my_account_orders_column_data' );


function new_modify_user_table( $column ) {

	$columns['cb'] = '<input type="checkbox" />';
	$columns['username'] = 'Username';
	$columns['bindcustomerid'] = 'BM Customer ID';
	$columns['name'] = 'Name';
	$columns['email'] = 'Email';
	$columns['role'] = 'Role';
	$columns['posts'] = 'Posts';
	
	return $columns;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );

function new_modify_user_table_row( $val, $column_name, $user_id ) {
	switch ($column_name) {
		case 'bindcustomerid':
			return get_user_meta($user_id, 'bindCustomerid', true);
		default:
	}
	return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );
	
function filter_woocommerce_account_orders_columns( $columns ) {
	
	$columns['order-status'] = __( 'Payment Status', 'woocommerce' );

	return $columns;
}
add_filter( 'woocommerce_account_orders_columns', 'filter_woocommerce_account_orders_columns', 10, 1 );

add_filter( 'flatsome_header_class', 'flatsome_sticky_headers_fn', 20, 1 );
function flatsome_sticky_headers_fn( $classes) {
	global $shutter_visualizer_page;
	global $curtains_config;
	global $blinds_config;
	global $product_page;
	
	if (is_page($shutter_visualizer_page) || is_page($curtains_config) || is_page($blinds_config)|| is_page($product_page)) {
		$classes= array('header-full-width');
	}
	return $classes;
}
function gretathemes_meta_description() {
	global $post;
	global $blinds_config;
	global $product_page;
	global $shutters_page;

	if ( is_page($blinds_config) ) {
		
		
		$url_prduct_name = get_query_var('productname');
		$url_colorname = get_query_var('colorname');
		
		$productname1 = str_replace('-', ' ', get_query_var('productname'));
		$getallfilterproduct = get_option('productlist', true);
		$product_list_array = $getallfilterproduct->product_list;
		$id1 = array_search($productname1, array_column($product_list_array, 'productname_lowercase'));
		$product_code = $product_list_array[$id1]->product_no;
		
		$getresponseid = CallAPI('GET', array('mode'=>'fabriclist', 'productcode'=>$product_code, 'url_colorname'=>$url_colorname));
		$urlfcnamelist = $getresponseid->urlfcnamelist;
		$getid = array_search($url_colorname, array_column($urlfcnamelist, 'url_fcname'));
		
		$producttypeid = $urlfcnamelist[$getid]->producttypeid;
		$fabricid = $urlfcnamelist[$getid]->fabricid;
		$colorid = $urlfcnamelist[$getid]->colorid;
		$vendorid = $urlfcnamelist[$getid]->vendorid;
	

		$response = CallAPI('GET', array('mode'=>'getproductdetail', 'productcode'=>$product_code, 'producttypeid'=>$producttypeid, 'fabricid'=>$fabricid, 'colorid'=>$colorid, 'vendorid'=>$vendorid));
		$productname_arr = explode('(', $response->product_details->productname);
		$meta_description = $response->product_details->meta_description;
		$meta_title = $response->product_details->meta_title;
		$meta_keyword = $response->product_details->meta_keyword;
		$canonical_tag = $response->product_details->canonical_tag;
		$alt_text_tag = $response->product_details->alt_text_tag;
	
		if ('' != $meta_title) {
			echo '<meta name="meta_name" content="' . wp_kses_post($meta_title) . '" >' . "\n";
		} else {
			
			$response_title = $response->product_details->colorname . ' ' . trim($productname_arr[0]);
			$meta_title= $response_title;
			echo '<meta name="meta_name" content="' . wp_kses_post($response_title) . '" >' . "\n";
		}
		
		if ('' != $meta_description) {
			$description = $meta_description;
			echo '<meta name="description" content="' . wp_kses_post($meta_description) . '" >' . "\n";
		} else {
			
			$description ='Get your various blinds products here.';
			echo '<meta name="description" content="' . wp_kses_post($description) . '">' . "\n";
		}
		if ('' != $meta_keyword) {
			echo '<meta name="keywords" content="' . wp_kses_post($meta_keyword) . '" >' . "\n";
		} else {
			
			echo '<meta name="keywords" content="' . wp_kses_post(trim($productname_arr[0])) . '" >' . "\n";
		}
		if ('' != $canonical_tag) {
			echo '<link rel="canonical" href="' . wp_kses_post($canonical_tag) . '" />' . "\n";
		}
		$page = get_page_by_path($blinds_config);
		if (isset($page->ID)) {
			update_post_meta( $page->ID, '_yoast_wpseo_metadesc', $description );
			update_post_meta( $page->ID, '_yoast_wpseo_title', $meta_title );
			update_post_meta( $page->ID, '_yoast_wpseo_canonical', $canonical_tag );
		}

	} elseif ( is_page($product_page)) {
			
		$getallfilterproduct = get_option('productlist', true);
		$productname = str_replace('-', ' ', get_query_var('pc'));
		$product_list_array = $getallfilterproduct->product_list;
		$productname = strtolower($productname);
		$id = array_search($productname, array_column($product_list_array, 'productname_lowercase'));
		
		$meta_description =  $product_list_array[$id]->meta_description;
		$meta_title =  $product_list_array[$id]->meta_title;
		$meta_keyword = $product_list_array[$id]->meta_keyword;
		$canonical_tag = $product_list_array[$id]->canonical_tag;
		$alt_text_tag = $product_list_array[$id]->alt_text_tag;

		if ('' != $meta_title) {
			echo '<meta name="meta_name" content="' . wp_kses_post($meta_title) . '" >' . "\n";
		} else {
			
			$response_title = $product_list_array[$id]->productname;
			$meta_title= $response_title;
			echo '<meta name="meta_name" content="' . wp_kses_post($response_title) . '" >' . "\n";
		}
		
		if ('' != $meta_description) {
			$description = $meta_description;
			echo '<meta name="description" content="' . wp_kses_post($meta_description) . '" >' . "\n";
		} else {
			
			$description =$product_list_array[$id]->productdescription;
			echo '<meta name="description" content="' . wp_kses_post($description) . '">' . "\n";
		}
		if ('' != $meta_keyword) {
			echo '<meta name="keywords" content="' . wp_kses_post($meta_keyword) . '" >' . "\n";
		} else {
			
			echo '<meta name="keywords" content="' . wp_kses_post($product_list_array[$id]->productname_lowercase) . '" >' . "\n";
		}
		if ('' != $canonical_tag) {
			echo '<link rel="canonical" href="' . wp_kses_post($canonical_tag) . '" />' . "\n";
		}
		$page = get_page_by_path($product_page);
		update_post_meta( $page->ID, '_yoast_wpseo_metadesc', $description );
		update_post_meta( $page->ID, '_yoast_wpseo_title', $meta_title );
		update_post_meta( $page->ID, '_yoast_wpseo_canonical', $canonical_tag );
	} elseif (  is_page( $shutters_page)) {
			
		$producttypename = str_replace('-', ' ', get_query_var('ptn'));
		$producttypeid = get_query_var('ptid');
		$shutter = CallAPI('GET', array('mode'=>'GetShutterParameterTypeDetails', 'parametertypeid'=>$producttypeid));
		
		$meta_description = $shutter->meta_description;
		$meta_title =  $shutter->meta_title;
		$meta_keyword = $shutter->meta_keyword;
		$canonical_tag = $shutter->canonical_tag;
		
		if ('' != $meta_title) {
			echo '<meta name="meta_name" content="' . esc_attr($meta_title) . '" >' . "\n";
		} else {
			
			$response_title = $shutter->productTypeSubName;
			$meta_title= $response_title;
			echo '<meta name="meta_name" content="' . esc_attr($response_title) . '" >' . "\n";
		}
		
		if ('' != $meta_description) {
			$description = $meta_description;
			echo '<meta name="description" content="' . esc_attr($meta_description) . '" >' . "\n";
		} else {
			
			$description =$shutter->producttypedescription;
			echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
		}
		if ('' != $meta_keyword) {
			echo '<meta name="keywords" content="' . esc_attr($meta_keyword) . '" >' . "\n";
		} else {
			
			echo '<meta name="keywords" content="' . esc_attr($shutter->productTypeSubName) . '" >' . "\n";
		}
		if ('' != $canonical_tag) {
			echo '<link rel="canonical" href="' . esc_attr($canonical_tag) . '" />' . "\n";
		}
		$page = get_page_by_path($shutters_page);
	
		update_post_meta( $page->ID, '_yoast_wpseo_metadesc', $description );
		update_post_meta( $page->ID, '_yoast_wpseo_title', $meta_title );
		update_post_meta( $page->ID, '_yoast_wpseo_canonical', $canonical_tag );
		
	}
 
}
add_action( 'wp_head', 'gretathemes_meta_description', 1);

 add_filter( 'wpseo_json_ld_output', 'remove_multiple_yoast_meta_tags', 9999 );
add_filter( 'wpseo_robots', 'remove_multiple_yoast_meta_tags', 9999 );
add_filter( 'wpseo_canonical', 'remove_multiple_yoast_meta_tags', 9999 );
add_filter( 'wpseo_title', 'remove_multiple_yoast_meta_tags', 9999 );
add_filter( 'wpseo_metadesc', 'remove_multiple_yoast_meta_tags', 9999 );
add_filter( 'wpseo_opengraph_desc', 'remove_multiple_yoast_meta_tags', 9999 );
add_filter( 'wpseo_opengraph_title', 'remove_multiple_yoast_meta_tags', 9999 );

function remove_multiple_yoast_meta_tags( $myfilter ) {
	global $blinds_list;
	global $product_page;
	if ( is_page($blinds_list) || is_page( $product_page) ) {
		return false;
	}
	return $myfilter;
}
add_filter('pre_get_document_title', 'changeTitle', 9999);
function changeTitle( $title) {

	global $post;
	global $blinds_config;
	global $product_page;
	global $shutters_page;
	if ( is_page($blinds_config) ) {
	 
		$url_prduct_name = get_query_var('productname');
		$url_colorname = get_query_var('colorname');
		
		$productname1 = str_replace('-', ' ', get_query_var('productname'));
		$getallfilterproduct = get_option('productlist', true);
		$product_list_array = $getallfilterproduct->product_list;
		$id1 = array_search($productname1, array_column($product_list_array, 'productname_lowercase'));
		$product_code = $product_list_array[$id1]->product_no;
		
		$getresponseid = CallAPI('GET', array('mode'=>'fabriclist', 'productcode'=>$product_code, 'url_colorname'=>$url_colorname));
		$urlfcnamelist = $getresponseid->urlfcnamelist;
		$getid = array_search($url_colorname, array_column($urlfcnamelist, 'url_fcname'));
		
		$producttypeid = $urlfcnamelist[$getid]->producttypeid;
		$fabricid = $urlfcnamelist[$getid]->fabricid;
		$colorid = $urlfcnamelist[$getid]->colorid;
		$vendorid = $urlfcnamelist[$getid]->vendorid;
		$response = CallAPI('GET', array('mode'=>'getproductdetail', 'productcode'=>$product_code, 'producttypeid'=>$producttypeid, 'fabricid'=>$fabricid, 'colorid'=>$colorid, 'vendorid'=>$vendorid));
		$productname_arr = explode('(', $response->product_details->productname);
		$meta_title = $response->product_details->meta_title;
	
		if ('' != $meta_title) {
			$title = $meta_title;
		} else {
			$response_title = $response->product_details->colorname . ' ' . trim($productname_arr[0]);
			$title= $response_title;
		}
	} elseif ( is_page($product_page)) {
		$getallfilterproduct = get_option('productlist', true);
		$productname = str_replace('-', ' ', get_query_var('pc'));
		$product_list_array = $getallfilterproduct->product_list;
		$productname = strtolower($productname);
		$id = array_search($productname, array_column($product_list_array, 'productname_lowercase'));
		
		$meta_title =  $product_list_array[$id]->meta_title;

		if ('' != $meta_title) {
				$title = $meta_title;
		} else {
			$response_title = $product_list_array[$id]->productname;
			$title= $response_title;
		}
	} elseif (  is_page( $shutters_page)) {
		$producttypename = str_replace('-', ' ', get_query_var('ptn'));
		$producttypeid = get_query_var('ptid');
		$shutter = CallAPI('GET', array('mode'=>'GetShutterParameterTypeDetails', 'parametertypeid'=>$producttypeid));
		
		$meta_title =  $shutter->meta_title;
		
		if ('' != $meta_title) {
			$title = $meta_title;
		} else {
			$response_title = $shutter->productTypeSubName;
			$title= $response_title;
		}
		
	}
	return $title;
}

add_action('wp_ajax_woocommerce_apply_state', 'woocommerce_apply_state', 10 );
add_action('wp_ajax_nopriv_woocommerce_apply_state', 'woocommerce_apply_state', 10 );
function woocommerce_apply_state() {
	global $wpdb;

	$billing_postcode = isset($_REQUEST['billing_postcode']) ? wc_clean(wp_unslash($_REQUEST['billing_postcode'])):'';
	if ( '' != $billing_postcode ) {
		$postcode = $billing_postcode;

		
		$response = CallAPI('GET', array('mode'=>'getpostcodedelivery', 'postcode'=>$postcode));
		$output= array();
		if (10 == $response->ecommerce_default_deltype) {
			if (isset($response->deliverycostdetails) && '' != $response->deliverycostdetails) {
				$incvat = ( $response->deliverycostdetails->cost / 100 ) * $response->vaterate;
				$delivery_cost_incvat = $response->deliverycostdetails->cost+$incvat;
				$cost = number_format(round($delivery_cost_incvat, 2), 2);
				
				$installation_incvat = ( $response->deliverycostdetails->installation_charge / 100 ) * $response->vaterate;
				$installation_cost_incvat = $response->deliverycostdetails->installation_charge+$installation_incvat;
				$installation_cost = number_format(round($installation_cost_incvat, 2), 2);
				$output['installation_charge_checkbox'] = $response->deliverycostdetails->installation_charge_checkbox;
				$output['installation_charge'] = $installation_cost;
			} else {
				$cost = '';
			}
			if ('' != $cost) {
				WC()->session->set( 'delivery_charge', $cost );
				WC()->session->set( 'installation_charge', $output['installation_charge'] );
				echo json_encode( $output );
			} else {
				WC()->session->__unset( 'delivery_charge' );
				WC()->session->__unset( 'installation_charge' );
			}
		} else {
			WC()->session->__unset( 'delivery_charge' );
			WC()->session->__unset( 'installation_charge' );
		}
		WC()->session->__unset( 'country_tax_rate');
		if(isset($response->country_vat_data[0]) && !empty($response->country_vat_data[0])){
			$vat_object = $response->country_vat_data[0];
			if(is_object($vat_object) && !empty($vat_object->taxrate)){
				WC()->session->set( 'country_tax_rate', $vat_object->taxrate);
			}
		}
	} else {
		WC()->session->__unset( 'delivery_charge' );
		WC()->session->__unset( 'installation_charge' );
		WC()->session->__unset( 'country_tax_rate');
	}
	die(); // Alway at the end (to avoid server error 500)
}
add_action('wp_ajax_woocommerce_installation_charges', 'woocommerce_installation_charges_fn', 10 );
add_action('wp_ajax_nopriv_woocommerce_installation_charges', 'woocommerce_installation_charges_fn', 10 );
function woocommerce_installation_charges_fn() {
	global $wpdb;
	$installation_charge = isset($_REQUEST['installation_charge']) ? wc_clean(wp_unslash($_REQUEST['installation_charge'])):'';
	if ( $installation_charge) {
		if ( 'on' == $installation_charge) {
			WC()->session->set( 'installation_charge_staus', 'on' );
		} else {
			WC()->session->set( 'installation_charge_staus', 'off' );
				
		}
	}
	
	die(); // Alway at the end (to avoid server error 500)
}

add_action('woocommerce_cart_calculate_fees' , 'add_custom_discount', 20, 1);
function add_custom_discount( WC_Cart $cart) {
	if (!checkBlindProduct()) {
		return;
	}
	
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}

	$percent = WC()->session->get( 'delivery_charge' );

	if ( $percent > 0 ) {
		$cart->add_fee( __('Delivery', 'woocommerce' ), $percent);
	}
	$installation_charge = WC()->session->get( 'installation_charge' );
	$installation_charge_staus = WC()->session->get( 'installation_charge_staus' );
	
	if ( 'on' == $installation_charge_staus ) {
		if ( $installation_charge > 0 ) {
			$cart->add_fee( __('Installation Charge', 'woocommerce' ), $installation_charge);
		}
	} else {
		$fees = WC()->cart->get_fees();
		foreach ($fees as $key => $fee) {
			if ( __( 'Installation Charge') == $fees[$key]->name) {
				unset($fees[$key]);
			}
		}
	}
	$country_tax_rate = WC()->session->get( 'country_tax_rate' );
	$cart_contents = WC()->cart->get_cart();
	if(!empty($country_tax_rate) && !empty($cart_contents)){
		$tax_fees = array();
		foreach($cart_contents as $cart_value ){
			if(!isset($cart_value['blinds_order_item_data'])){
				continue;
			}
			
			$price = isset($cart_value['blinds_order_item_data']['single_product_price']) ? floatval($cart_value['blinds_order_item_data']['single_product_price']):0;
			if($price){
				$qty = isset($cart_value['quantity']) ? absint($cart_value['quantity']):1;
				$price_based_on_qty = $price*$qty;
				$tax_fees[] = $price_based_on_qty*$country_tax_rate/100;
			}
		}
		$total_tax_fees = array_sum($tax_fees);
		if($total_tax_fees){

			$blindmatrix_settings = get_option( 'option_blindmatrix_settings' );
			if(isset($blindmatrix_settings['vatoption']) && '2' == ($blindmatrix_settings['vatoption'] )){
			$cart->add_fee( 'VAT', $total_tax_fees);

			}
		}
	}else{
		$tax_fees = array();
		foreach($cart_contents as $cart_value ){
			if(!isset($cart_value['blinds_order_item_data'])){
				continue;
			}
			
			$tax = isset($cart_value['blinds_order_item_data']['single_product_vatvalue']) ? floatval($cart_value['blinds_order_item_data']['single_product_vatvalue']):0;
			if($tax){
				$qty = isset($cart_value['quantity']) ? absint($cart_value['quantity']):1;
				$tax_fees[] = $tax*$qty;
			}
		}
		
		$total_fees = array_sum($tax_fees);
		if(!$total_fees){
			return;
		}
		$blindmatrix_settings = get_option( 'option_blindmatrix_settings' );
		if(isset($blindmatrix_settings['vatoption']) && '2' == ($blindmatrix_settings['vatoption'] )){
			$cart->add_fee( 'VAT', $total_fees );
		}
		
	}
}
include 'page_template_fullwidth.php';



function filter_woocommerce_add_to_cart_validation( $passed, $product_id, $quantity, $variation_id = null, $variations = null ) {
	$blindproduct = get_option('blindproduct', true);
	$current_product_id = $variation_id > 0 ? $variation_id : $product_id;
	
		$currentCartProductids = array();
		
		$cart = WC()->cart;
	if ( ! $cart->is_empty() ) {
		foreach (WC()->cart->get_cart_contents() as $cart_single) {
			$currentCartProductids[] =  $cart_single['product_id'];
		}
		if (in_array( $blindproduct , $currentCartProductids) &&  $blindproduct == $current_product_id  ) {
			
			$passed = true;
				
		} else if (!in_array( $blindproduct , $currentCartProductids) && $blindproduct != $current_product_id ) {
			
			$passed = true;
				
		} else {
			if ( $blindproduct != $current_product_id  ) {
				  wc_add_notice( __( 'You cannot add another product, there is already a specific product in cart', 'woocommerce' ), 'error' );
			}
				$passed = false;
		}
	}

	return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'filter_woocommerce_add_to_cart_validation', 9999, 5 );

function checkBlindProduct( $order_id = false) {
	$blindproductID = get_option('blindproduct');
	if (!$blindproductID) {
		return false;
	}
	
	if ($order_id) {
		// For Order Validation
		$order = wc_get_order($order_id);
		if (!is_object($order)) {
			return false;
		}
		
		$order_items = $order->get_items();
		if (empty($order_items) && !is_array($order_items)) {
			return false;
		}
		
		$order_product_ids = array();
		foreach ($order_items as $order_item) {
			$order_product_ids[] = isset($order_item['product_id']) ? $order_item['product_id']:0;
		}
		
		if (!empty($order_product_ids) && in_array($blindproductID, $order_product_ids)) {
			return true;
		}
	} else {
		// For Cart Validation
		if ( WC()->cart->is_empty() ) {
			return false;
		}
	
		$currentCartProductids = array();
		$get_cart_contents = WC()->cart->get_cart_contents();
		foreach ($get_cart_contents as $cart_single) {
			if (!isset($cart_single['blinds_order_item_data'])) {
				continue;
			}
			$currentCartProductids[] =  isset($cart_single['product_id']) ? $cart_single['product_id']:0;
		}
	
		if (!empty($currentCartProductids) && in_array( $blindproductID , $currentCartProductids)) {
			return true;
		}
	}
	return false;
}

add_filter( 'manage_edit-shop_order_columns', 'custom_shop_order_column', 20 );
function custom_shop_order_column( $columns) {
	$reordered_columns = array();

	foreach ( $columns as $key => $column) {
		$reordered_columns[$key] = $column;
		if (  'order_status' == $key ) {
			$reordered_columns['bm_orderID'] = 'BM Order ID';
		}
	}
	return $reordered_columns;
}

add_action( 'manage_shop_order_posts_custom_column' , 'custom_orders_list_column_content', 20, 2 );
function custom_orders_list_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'bm_orderID':
			$my_var_one = get_post_meta( $post_id, '_order_number', true );
			if (!empty($my_var_one)) {
				echo wp_kses_post($my_var_one);

			} else {
				echo '<small>(<em>no value</em>)</small>';
			}
			break;
	}
}

add_action( 'init', 'blindmatrix_reset_options_on_load');
/**
 * Reset option on page load
 */
function blindmatrix_reset_options_on_load() {
	if (!isset($_GET['blindmatrix_reset_options']) || 'yes' != wc_clean(wp_unslash($_GET['blindmatrix_reset_options']))) {
			return;
	}
		
	delete_option('bmactive');
	delete_option('bmactive_timestamp');
	delete_option('bm_requested_post_id');
	delete_option('bmpremium_timestamp');
	delete_option('Api_Name');
	delete_option('Api_Url');
	delete_option('bm-appointment');
	
	$tabs = array('Blinds','Shutters','Curtains');
	foreach ($tabs as $tab) {
		blindmatrix_delete_menu_ids($tab);
	}
	
	$stored_settings = get_option('option_blindmatrix_settings');
	$stored_settings['blindslistproid'] = array();
	$stored_settings['curtainlistproid'] = array();
	$stored_settings['shutterlistproid'] = array();
	$stored_settings['menu_product_type'] = array();
	$stored_settings['menu_location_name'] = array();
	$stored_settings['bm_primary_color'] = '#00c2ff';
	update_option('option_blindmatrix_settings', $stored_settings);
	delete_option('bm_stored_curtains_menu_ids');
	delete_option('bm_stored_shutters_menu_ids');
	delete_option('bm_stored_blinds_menu_ids');
	delete_option('bmactive_dbconnect');
}

/**
 * Reset options when reaches premium status
 */
function bm_reset_options_reaches_premium_status() {
	$tabs = array('Blinds','Shutters','Curtains');
	foreach ($tabs as $tab) {
		blindmatrix_delete_menu_ids($tab);
	}
	
	$stored_settings = get_option('option_blindmatrix_settings');
	$stored_settings['menu_location_name'] = array();
	$stored_settings['blindslistproid'] = array();
	$stored_settings['curtainlistproid'] = array();
	$stored_settings['shutterlistproid'] = array();
	$stored_settings['menu_product_type'] = array();
	update_option('option_blindmatrix_settings', $stored_settings);
	delete_option('bm_stored_curtains_menu_ids');
	delete_option('bm_stored_shutters_menu_ids');
	delete_option('bm_stored_blinds_menu_ids');
}

function bm_remove_cart_item( $cart_item_key, $cart ) {
	$product_id = get_option('blindproduct');
	if ( $cart->cart_contents[ $cart_item_key ]['product_id'] == $product_id ) {
		$attach_id = $cart->cart_contents[ $cart_item_key ]['attach_id'];
		wp_delete_attachment($attach_id, true);
	}
};
add_action( 'woocommerce_remove_cart_item', 'bm_remove_cart_item', 10, 2 );

function blindmatrix_delete_menu_ids( $tab_name) {
	if ('Blinds' == $tab_name) {
		$stored_blinds_menu_item_ids = get_option('bm_stored_blinds_menu_ids');
		if (!empty($stored_blinds_menu_item_ids) && is_array($stored_blinds_menu_item_ids)) {
			foreach ($stored_blinds_menu_item_ids as $stored_blinds_menu_item_id) {
				wp_delete_post($stored_blinds_menu_item_id);
			}
		}
	}
	
	if ('Shutters' == $tab_name) {
		$stored_shutters_menu_item_ids = get_option('bm_stored_shutters_menu_ids');
		if (!empty($stored_shutters_menu_item_ids) && is_array($stored_shutters_menu_item_ids)) {
			foreach ($stored_shutters_menu_item_ids as $stored_shutters_menu_item_id) {
				wp_delete_post($stored_shutters_menu_item_id);
			}
		}
	}
	
	if ('Curtains' == $tab_name) {
		$stored_curtains_menu_item_ids = get_option('bm_stored_curtains_menu_ids');
		if (!empty($stored_curtains_menu_item_ids) && is_array($stored_curtains_menu_item_ids)) {
			foreach ($stored_curtains_menu_item_ids as $stored_curtains_menu_item_id) {
				wp_delete_post($stored_curtains_menu_item_id);
			}
		}
	}
}

function bm_create_menu_items() {
			$delete_product_types = array('Blinds','Shutters','Curtains');
	foreach ($delete_product_types as $delete_product_type) {
		blindmatrix_delete_menu_ids($delete_product_type);
	}
	
			$stored_settings = get_option('option_blindmatrix_settings');
			$location_names = isset($stored_settings['menu_location_name']) ?$stored_settings['menu_location_name']:array() ;
	if (empty($location_names)) {
		return;			
	}
	
			$blindmatrix_settings = get_option( 'option_blindmatrix_settings' );
	if (isset( $blindmatrix_settings['product_page'] ) && '' != $blindmatrix_settings['product_page'] ) {
		$product_page = $blindmatrix_settings['product_page'];
	} else {
		$product_page = 'blinds-list';
	}		
		
	if (isset( $blindmatrix_settings['shutters_page'] ) && '' != $blindmatrix_settings['shutters_page']) {
		$shutters_page = $blindmatrix_settings['shutters_page'];
	} else {
		$shutters_page = 'shutter-single-type';
	}
	
	if (isset( $blindmatrix_settings['shutters_type_page'] ) && '' != $blindmatrix_settings['shutters_type_page']) {
		$shutters_type_page = $blindmatrix_settings['shutters_type_page'];
	} else {
		$shutters_type_page = 'shutter-type';
	}
	
	if (isset( $blindmatrix_settings['curtains_single'] ) && '' != $blindmatrix_settings['curtains_single'] ) {
		$curtains_single_page = $blindmatrix_settings['curtains_single'];
	} else {
		$curtains_single_page = 'curtain-single';
	}
	
			$menu_locations_data    = get_nav_menu_locations();
			$menu_locations_checkboxes = $location_names;
			$menu_locations = get_nav_menu_locations();
			$created_menu_id = !empty(get_option('bm_created_menu_id')) ? get_option('bm_created_menu_id'):'';
			$custom_pages   = isset($blindmatrix_settings['menu_product_type']) ?$blindmatrix_settings['menu_product_type']:array() ;
			$menu_item_ids = array();
				
			$productlist_object = get_option('productlist', true);
			$blinds_product_data = is_object($productlist_object) ? $productlist_object->product_list:array();
			$shutters_product_data = is_object($productlist_object) ? $productlist_object->shutter_product_list:array();
			$curtain_products_data = is_object($productlist_object) ? $productlist_object->curtain_product_list:array();
			$blinds_url = site_url() . '/' . $product_page;
			$shutters_url = site_url() . '/' . $shutters_type_page;
			$error = true;
			$stored_menu_item_ids = get_option('bm_stored_menu_ids');
			$blinds_ids = array();
			$shutters_ids = array();
			$curtains_ids = array();
	if ( !empty($menu_locations_checkboxes) && is_array($menu_locations_checkboxes)) {
		// Blinds Data	
		$menu_sub_items_blinds_data = array(); 	
		if (!empty($blinds_product_data)) {	
				$inc_products = isset($stored_settings['blindslistproid']) && is_array($stored_settings['blindslistproid']) && !empty($stored_settings['blindslistproid']) ? array_keys($stored_settings['blindslistproid']): array();  
			foreach ($blinds_product_data as $blinds_product_object) {
				if (!isset($blinds_product_object->productid) || empty($inc_products) || !in_array($blinds_product_object->productid, $inc_products)) {
					  continue;
				}	
					
					$url = $blinds_url . '/' . str_replace(' ', '-', strtolower($blinds_product_object->productname));
					$product_name = $blinds_product_object->productname;
					$menu_sub_items_blinds_data[] = array('url' => $url,'product_name' => $product_name);
			}	
		}
				
				// Shutters Data
				$menu_sub_items_shutters_data = array(); 	
		if (!empty($shutters_product_data)) {		
			$inc_products = isset($stored_settings['shutterlistproid']) && is_array($stored_settings['shutterlistproid']) && !empty($stored_settings['shutterlistproid']) ? array_keys($stored_settings['shutterlistproid']): array();  
			foreach ($shutters_product_data as $shutters_product_object) {
				foreach ($shutters_product_object->GetShutterProductTypeList as $shutters_product_type_list_object) {
					if (!isset($shutters_product_type_list_object->parameterTypeId) || empty($inc_products) || !in_array($shutters_product_type_list_object->parameterTypeId, $inc_products)) {
						continue;
					}		
					  
					$product_type_sub_name = str_replace(' ', '-', strtolower($shutters_product_type_list_object->productTypeSubName));
					$url = site_url() . '/' . $shutters_page . '/' . $product_type_sub_name . '/' . $shutters_product_type_list_object->parameterTypeId;
					$url = alter_shutter_product_url($url,$shutters_product_type_list_object->parameterTypeId,$product_type_sub_name);
					$product_name = $shutters_product_type_list_object->productTypeSubName;
					$menu_sub_items_shutters_data[] = array('url' => $url,'product_name' => $product_name);
				}
			}
		}
				
				// Curtains Data
				$menu_sub_items_curtains_data = array(); 	
				$curtains_product_types = array();
		if (!empty($curtain_products_data)) {
			$curtains_product_types = array(
				'pencil-pleat' => 'Pencil Pleat',
				'eyelet' => 'Eyelet',
				'goblet' => 'Goblet',
				'goblet-buttoned' => 'Goblet Buttoned', 
				'double-pinch'  => 'Double Pinch',
				'double-pinch-buttoned' => 'Double Pinch Buttoned',
				'triple-pinch'   => 'Triple Pinch',
				'triple-pinch-buttoned'   => 'Triple Pinch Buttoned'
						); 
			$inc_products = isset($stored_settings['curtainlistproid']) && is_array($stored_settings['curtainlistproid']) && !empty($stored_settings['curtainlistproid']) ? array_keys($stored_settings['curtainlistproid']): array();
			foreach($curtain_products_data as $curtain){
				if (count($curtain->GetCurtainProductTypeList) > 0) {
					$curtain_product = array_unique(array_column($curtain->GetCurtainProductTypeList, 'curtain_type'));
					$unique_GetCurtainProductTypeList = array_intersect_key($curtain->GetCurtainProductTypeList, $curtain_product);
					foreach ($unique_GetCurtainProductTypeList as $key => $curtain_product) {
						$curtain_type = is_object($curtain_product) ? $curtain_product->curtain_type:'';
						if(!$curtain_type || !in_array($curtain_type, $inc_products) || !isset($curtains_product_types[$curtain_type])){
							continue;
						}

						$url = site_url() . '/' . $curtains_single_page . '/' . $curtain_type.'/'.$curtain_product->productid.'/'.$curtain_product->parameterTypeId;
						$menu_sub_items_curtains_data[] = array('url' => $url,'product_name' => $curtains_product_types[$curtain_type]);
					}
				}
			}		
		}

		foreach ($custom_pages as $custom_page) {
			foreach ($menu_locations_checkboxes as $menu_name => $checkbox) {
				$menu_id = isset($menu_locations_data[$menu_name])?$menu_locations_data[$menu_name]:'';
				$menu_object = wp_get_nav_menu_object($menu_id);
				$menu_id   = is_object($menu_object) ? $menu_id:$created_menu_id;
				if (!$menu_id) {
					continue;
				}
					
				$title = '';					
				if ('Blinds' == $custom_page) {
					$title =  __('Blinds');
				} else if ('Shutters' == $custom_page) {
					$title =  __('Shutters');
				} else if ('Curtains' == $custom_page) {
					$title = __('Curtains');
				}
					
				if (!$title) {
					continue;
				}
										
				if ('Blinds' == $custom_page && !empty($menu_sub_items_blinds_data)) {
					// Menu Item
					$menu_item_id = wp_update_nav_menu_item($menu_id, 0, array(
					'menu-item-title' =>  $title,
					'menu-item-classes' => 'bm-blind-activity',
					'menu-item-url' => '#', 
					'menu-item-status' => 'publish',
					'menu-item-parent-id' => 0
					)
					);
						
					foreach ($menu_sub_items_blinds_data as $menu_sub_items_value) {
						$url = $menu_sub_items_value['url'];
						if (!in_array($menu_item_id, $blinds_ids)) {
							$blinds_ids[] = $menu_item_id;
						}
							
						// Update Blinds Sub Item
						$blinds_ids[] = wp_update_nav_menu_item($menu_id, 0, array(
						'menu-item-title' =>  $menu_sub_items_value['product_name'],
						'menu-item-classes' => 'bm-blinds-sub-activity',
						'menu-item-url' => $url, 
						'menu-item-status' => 'publish',
						'menu-item-parent-id' => $menu_item_id
						)
						);
							
						$error = false;
					}
				} else if ('Shutters' == $custom_page && !empty($menu_sub_items_shutters_data)) {
					// Menu Item
					$menu_item_id = wp_update_nav_menu_item($menu_id, 0, array(
					'menu-item-title' =>  $title,
					'menu-item-classes' => 'bm-shutter-activity',
					'menu-item-url' => $shutters_url, 
					'menu-item-status' => 'publish',
					'menu-item-parent-id' => 0
					)
					);
					foreach ($menu_sub_items_shutters_data as $menu_sub_items_value) {
						$url = $menu_sub_items_value['url'];
						if (!in_array($menu_item_id, $shutters_ids)) {
							$shutters_ids[] = $menu_item_id;
						}
							
						// Update Shutters Sub Item
						$shutters_ids[] = wp_update_nav_menu_item($menu_id, 0, array(
						'menu-item-title' =>  $menu_sub_items_value['product_name'],
						'menu-item-classes' => 'bm-shutters-sub-activity',
						'menu-item-url' => $url, 
						'menu-item-status' => 'publish',
						'menu-item-parent-id' => $menu_item_id
						)
						);
							
						$error = false;
					}
				} else if ('Curtains' == $custom_page && !empty($menu_sub_items_curtains_data)) {
					// Menu Item
					$menu_item_id = wp_update_nav_menu_item($menu_id, 0, array(
						'menu-item-title' =>  $title,
						'menu-item-classes' => 'bm-curtain-activity',
						'menu-item-url' => '#', 
						'menu-item-status' => 'publish',
						'menu-item-parent-id' => 0
						)
					);
					foreach ($menu_sub_items_curtains_data as $menu_sub_items_value) {
						$url = $menu_sub_items_value['url'];
						if (!in_array($menu_item_id, $curtains_ids)) {
							$curtains_ids[] = $menu_item_id;
						}
						// Update Curtains Sub Item
						$curtains_ids[] = wp_update_nav_menu_item($menu_id, 0, array(
						'menu-item-title' =>  $menu_sub_items_value['product_name'],
						'menu-item-classes' => 'bm-curtains-sub-activity',
						'menu-item-url' => $url, 
						'menu-item-status' => 'publish',
						'menu-item-parent-id' => $menu_item_id
						)
						);
							
						$error = false;
					}
				}
					
				$menu = get_term_by( 'ID', $menu_id, 'nav_menu' );
				$locations = get_theme_mod('nav_menu_locations');
				$locations[$menu_name] = $menu->term_id;
				set_theme_mod( 'nav_menu_locations', $locations );
			}
		}
	}
				
			$stored_blinds_menu_item_ids = get_option('bm_stored_blinds_menu_ids');
	if (!$stored_blinds_menu_item_ids) {
		update_option('bm_stored_blinds_menu_ids', $blinds_ids);
	} else {
		update_option('bm_stored_blinds_menu_ids', array_merge($stored_blinds_menu_item_ids, $blinds_ids));
	}
		
			$stored_shutters_menu_item_ids = get_option('bm_stored_shutters_menu_ids');
	if (!$stored_shutters_menu_item_ids) {
		update_option('bm_stored_shutters_menu_ids', $shutters_ids);
	} else {
		update_option('bm_stored_shutters_menu_ids', array_merge($stored_shutters_menu_item_ids, $shutters_ids));
	}
		
			$stored_curtains_menu_item_ids = get_option('bm_stored_curtains_menu_ids');
	if (!$stored_curtains_menu_item_ids) {
		update_option('bm_stored_curtains_menu_ids', $curtains_ids);
	} else {
		update_option('bm_stored_curtains_menu_ids', array_merge($stored_curtains_menu_item_ids, $curtains_ids));
	}
}

function bm_locate_template( $template, $template_name, $template_path ) {
	$basename = basename( $template );

	if ( 'thankyou.php'  == $basename) {
		$template = trailingslashit( plugin_dir_path( __FILE__ ) ) . '/woocommerce/checkout/thankyou.php';
	}
	return $template;
}
add_filter( 'woocommerce_locate_template', 'bm_locate_template', 10, 3 );

add_action('wp_ajax_bm_reset_menu_action', function() {
	bm_create_menu_items();
	wp_send_json_success(true);
});

function blindmatrix_check_premium() {
	return 'premium' == get_option('bmactive');
}

function blinds_add_plugin_link( $plugin_actions, $plugin_file ) {
	$new_actions = array();
	if ( basename( plugin_dir_path( __FILE__ ) ) . '/blind-matrix-api.php' === $plugin_file ) {
		if('trial_activated' == get_option('bmactive') || 'premium' == get_option('bmactive')){
			$new_actions['blinds_settings'] = sprintf( '<a href="%s">Settings</a>', esc_url( admin_url( 'admin.php?page=bmsettings' ) ) );
			if (!blindmatrix_check_premium()) {
				$new_actions['blinds_premium'] = '<a href="#" class="blindmatrix-upgrade-premium-popup">Go Premium</a>';
			}
		}else{
			$new_actions['blinds_settings'] = sprintf( '<a href="%s">Settings</a>', esc_url( admin_url( 'admin.php?page=bm' ) ) );
		}
	}

	return array_merge( $new_actions, $plugin_actions );
}
add_filter( 'plugin_action_links', 'blinds_add_plugin_link', 10, 2 );


add_action('wp_ajax_bm_premium_query', 'bm_premium_query', 10 );
add_action('wp_ajax_nopriv_bm_premium_query', 'bm_premium_query', 10 );
function bm_premium_query() {
	$site_url = get_site_url();
	$result = BlindMatrix_User_Request::send_premium_request(
		array(
		'name'               => isset($_REQUEST['name']) ? wc_clean(wp_unslash($_REQUEST['name'])):'',
		'email'              => isset($_REQUEST['email']) ? wc_clean(wp_unslash($_REQUEST['email'])):'',
		'phone_number'       => isset($_REQUEST['number']) ? wc_clean(wp_unslash($_REQUEST['number'])):'',
		'company_name'       => isset($_REQUEST['company']) ? wc_clean(wp_unslash($_REQUEST['company'])):'',
		'site_url'      	 => isset($site_url) ? $site_url:'',
		)
		);
	if (isset($result->post_id)) {
		echo( 'success' );
	} else {
		echo( 'error' );
	};
	die();
}

add_action('wp_head',function(){
	if(!isset($_REQUEST['bme_system_status']) || !function_exists('wc')){
		return '';
	}
	
	$reports = wc()->api->get_endpoint_data( '/wc/v3/system_status' );
	echo '<pre>';
	var_dump($reports);
	echo '</pre>';
});

add_filter('woocommerce_rest_check_permissions', 'bme_alter_rest_check_permissions',999,4);
function bme_alter_rest_check_permissions($permission, $context, $value, $object){
	if(!isset($_REQUEST['bme_system_status']) || !function_exists('wc')){
		return $permission;
	}

	if(!$permission && ('reports' == $object || 'system_status' == $object)){
		return true;
	}
	
	return $permission;
}

function bm_is_flatsome_theme_activated(){
	$theme = wp_get_theme();
	if($theme->parent()){
		$name = $theme->parent()->get('TextDomain');
	}else{
		$name = $theme->get('TextDomain');
	}
	
	return 'flatsome' == $name;
}

function get_cart_item_blinds_plugin_data($cart_item_key){
	if(!$cart_item_key){
		return array();
	}
	
	$_cart_item = !empty($cart_item_key) ? WC()->cart->get_cart_item($cart_item_key):false;
	if(!isset($_cart_item['blinds_order_item_data']) || empty($_cart_item['blinds_order_item_data'])){
		return array();
	}
	
	return $_cart_item['blinds_order_item_data'];
}

function alter_shutter_product_url($url,$producttypeid,$producttypename){
	global $shutter_visualizer_page;
	$response = CallAPI("GET", $post=array("mode"=>"GetShutterParameterTypeDetails", "parametertypeid"=>$producttypeid));
	if(is_object($response) &&  is_array($response->producttype_price_list ) && count($response->producttype_price_list ) == 1 && isset($response->producttype_price_list[0])){
		$product_type_sub_sub_id = is_object($response->producttype_price_list[0]) ? $response->producttype_price_list[0]->parameterTypeSubSubId:'';
		if('' != $product_type_sub_sub_id){
			$url = site_url().'/'.$shutter_visualizer_page.'/'.$producttypename.'/'.$producttypeid.'/'.$product_type_sub_sub_id;
		}
	}
	return $url;
}
