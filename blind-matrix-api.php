<?php
/**
 * Plugin Name:     BlindMatrix eCommerce Solution
 * Description:     BlindMatrix eCommerce plugin is designed especially for window blinds, curtains and shutter businesses to sell their products online with ultimate ease. Window Blinds eCommerce plugin converts any window-covering informative website into an online shopping website offering freedom of product choices and a great buying experience to customers.
 * Version:         5.2.8
 * Author:          Blindmatrix
 * Author URI:      https://ecommerce.blindssoftware.com/
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     blindmatrix-ecommerce
 *
 */

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

add_action( 'plugins_loaded', 'blindmatrix_plugins_loaded_hook' );
function blindmatrix_plugins_loaded_hook() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action( 'admin_notices', 'blindmatrix_woocommerce_missing_wc_notice' );
		return;
	}

	if(!get_option('bmactive') ){
		add_action( 'admin_notices', 'blindmatrix_configure_notice' );
		return;
	}
}

function blindmatrix_configure_notice(){
	$link = sprintf('<a href="%s">Click here</a>', admin_url('admin.php?page=bm&bm_refresh_page=true'));
	echo wp_kses_post('<div class="notice notice-success"><p><strong>' . sprintf( '%s to configure Blindmatrix eCommerce plugin.', $link ) . '</strong></p></div>');
}

function blindmatrix_woocommerce_missing_wc_notice() {
	$link = sprintf('<a href="%s" target="_blank">WooCommerce</a>', site_url() . '/wp-admin/plugin-install.php?s=woocommerce&tab=search&type=term');
	echo wp_kses_post('<div class="error"><p><strong>' . sprintf( 'Blindmatrix eCommerce requires WooCommerce to be installed and active. You can install %s here.', $link ) . '</strong></p></div>');
}

function blindmatrix_check_is_woocommerce_active() {
	if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		return true;
	}
	
	return false;
}

if (!blindmatrix_check_is_woocommerce_active()) {
	return;
}

define( 'BM_ECO_PLUGIN_FILE', __FILE__ );
define( 'BM_ECO_ABSPATH' , dirname(__FILE__));

include(dirname( __FILE__ ) . '/vendor/Api/class-api-functions.php');
include(dirname( __FILE__ ) . '/common.php');
require( dirname( __FILE__ ) . '/functions.php' );
include(dirname( __FILE__ ) . '/vendor/Api/class-user-request.php');
include(dirname( __FILE__ ) . '/ajax.php');

add_action('in_admin_header', function () {
	if (!is_admin()) {
		return;
	} 
	
	$screen_object = function_exists('get_current_screen') ? get_current_screen():'';
	$screen_id = isset($screen_object->id) ? $screen_object->id:'';
	if (!$screen_id || !in_array($screen_id, array('toplevel_page_bm','blindmatrix-ecommerce_page_bmsettings'))) {
		return;
	}
	
	remove_all_actions('admin_notices');
	remove_all_actions('all_admin_notices');
}, 1000);

?>
<?php 
/**
 * Admin menu hook callback.
 */
function bm_options_page() {
	if (!class_exists('BlindMatrix_User_Request')) {
		include(dirname( __FILE__ ) . '/Api/class-user-request.php');
	}
	
		ob_start();
	if (isset($_REQUEST['Api_Name'])) {
		//update_option( 'Api_Url', $_POST['Api_Url']);
		//update_option( 'Api_Name', $_POST['Api_Name']);
		update_option('bmactive_dbconnect', 'Connected');
		$Api_currency = isset($_REQUEST['Api_currency']) ? wc_clean(wp_unslash($_REQUEST['Api_currency'])):'';
		if ($Api_currency) {
			update_option('woocommerce_currency', $Api_currency);
		}
	}
	if (isset($_GET['skip'])) {
		update_option('bmactive_dbconnect', 'Connected');
	}
	if (isset($_REQUEST['blindmatrix_activation']) && get_option('bmactive') == 'pending') {
			
		$timestamp=time();
		update_option('bmactive', 'trial_activated');
		add_option('bmactive_timestamp', $timestamp);
		if (get_option('bmactive_dbconnect') != 'Connected') {
			add_option('bmactive_dbconnect', 'notConnected');
		}
		$blinds_list = get_option('blinds_list');
			ob_start();
		if (get_option('woocommerce_email_from_address') != '') {
			$mail_email = get_option('woocommerce_email_from_address');
		} else {
			$mail_email = get_bloginfo('new_admin_email');
		}
		?>
					<div style="padding:0 10px;">
					<img src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BM_ECO_PLUGIN_FILE ))); ?>/assets/image/free-trial.jpg">
					<p style="margin: 3px 0;color: black;margin-top: 15px;"><strong>Regards,</strong></p>
					<p style="margin: 3px 0;color: black;">Blindmatrix Ecommerce</p>
					<img width="200" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BM_ECO_PLUGIN_FILE ))); ?>/assets/image/Logo.png">
					<div>
				<?php
				$message = ob_get_clean();
				$email_heading='Blindmatrix Trial Pack has been activated';
				$headers = array('Content-Type: text/html; charset=UTF-8');
				$mail = wp_mail($mail_email, $email_heading, $message, $headers );
			
				$post_id = BlindMatrix_User_Request::get_requested_post_id();
				if ($post_id) {
					$response = BlindMatrix_User_Request::send_request(array('id' =>$post_id,'plugin_activated_date' =>gmdate('Y-m-d H:i:s', $timestamp),'post_status' => 'free_trial'), 'POST');
				} else {
					$response = BlindMatrix_User_Request::send_request(array('plugin_activated_date' =>gmdate('Y-m-d H:i:s', $timestamp),'post_status' => 'free_trial'), 'POST');
					update_option('bm_requested_post_id', $response->post_id);
				}
			
				if (!$blinds_list) {
					$my_post = array(
					'post_title'    => wp_strip_all_tags( 'Blinds List' ),
					'post_content'  => '[BlindMatrix source="Blinds-Archive"]',
					'post_status'   => 'publish',
					'post_author'   => 1,
					'post_type'     => 'page',
					);
					$postID = wp_insert_post( $my_post );
					update_option('blinds_list', $postID);
					update_post_meta( $postID, '_wp_page_template', 'full-width-bm.php' );
				}
				$blinds_config = get_option('blinds_config');
				if (!$blinds_config) {
					$my_post = array(
					'post_title'    => wp_strip_all_tags( 'Blinds Config' ),
					'post_content'  => '[BlindMatrix source="blinds-config"]',
					'post_status'   => 'publish',
					'post_author'   => 1,
					'post_type'     => 'page',
					);
					$postID = wp_insert_post( $my_post );
					update_option('blinds_config', $postID);
					update_post_meta( $postID, '_wp_page_template', 'full-width-bm.php' );
				}	
				$sample_cart = get_option('sample_cart');
				if (!$sample_cart) {
					$my_post = array(
					'post_title'    => wp_strip_all_tags( 'Sample Cart' ),
					'post_content'  => '[BlindMatrix source="Sample-Cart"]',
					'post_status'   => 'publish',
					'post_author'   => 1,
					'post_type'     => 'page',
					);
					$postID = wp_insert_post( $my_post );
					update_option('sample_cart', $postID);
					update_post_meta( $postID, '_wp_page_template', 'default-bm.php' );
				}
				$quick_quote = get_option('quick_quote');
				if (!$quick_quote) {
					// $my_post = array(
					// 'post_title'    => wp_strip_all_tags( 'Quick Quote' ),
					// 'post_content'  => '[BlindMatrix source="Quick-Quote"]',
					// 'post_status'   => 'publish',
					// 'post_author'   => 1,
					// 'post_type'     => 'page',
					// );
					// $postID = wp_insert_post( $my_post );
					// update_option('quick_quote', $postID);
					// update_post_meta( $postID, '_wp_page_template', 'default-bm.php' );
				}
				$shutter_type = get_option('shutter_type');
				if (!$shutter_type) {
					$my_post = array(
					'post_title'    => wp_strip_all_tags( 'Shutter Type' ),
					'post_content'  => '[BlindMatrix source="shutter-type"]',
					'post_status'   => 'publish',
					'post_author'   => 1,
					'post_type'     => 'page',
					);
					$postID = wp_insert_post( $my_post );
					update_option('shutter_type', $postID);
					update_post_meta( $postID, '_wp_page_template', 'full-width-bm.php' );
				}
				$shutter_single_type = get_option('shutter_single_type');
				if (!$shutter_single_type) {
					$my_post = array(
					'post_title'    => wp_strip_all_tags( 'Shutter Single Type' ),
					'post_content'  => '[BlindMatrix source="shutter-type-styles"]',
					'post_status'   => 'publish',
					'post_author'   => 1,
					'post_type'     => 'page',
					);
					$postID = wp_insert_post( $my_post );
					update_option('shutter_single_type', $postID);
					update_post_meta( $postID, '_wp_page_template', 'full-width-bm.php' );
				}
				$shutter_config = get_option('shutter_config');
				if (!$shutter_config) {
					$my_post = array(
					'post_title'    => wp_strip_all_tags( 'Shutter Config' ),
					'post_content'  => '[BlindMatrix source="shutter-config"]',
					'post_status'   => 'publish',
					'post_author'   => 1,
					'post_type'     => 'page',
					);
					$postID = wp_insert_post( $my_post );
					update_option('shutter_config', $postID);
					update_post_meta( $postID, '_wp_page_template', 'full-width-bm.php' );
				}
				$curtains_single = get_option('curtains_single');
				if (!$curtains_single) {
					$my_post = array(
					'post_title'    => wp_strip_all_tags( 'Curtain Single' ),
					'post_content'  => '[BlindMatrix source="curtain-single"]',
					'post_status'   => 'publish',
					'post_author'   => 1,
					'post_type'     => 'page',
					);
					$postID = wp_insert_post( $my_post );
					update_option('curtains_single', $postID);
					update_post_meta( $postID, '_wp_page_template', 'full-width-bm.php' );
				}
				$curtain_config = get_option('curtain_config');
				if (!$curtain_config) {
					$my_post = array(
					'post_title'    => wp_strip_all_tags( 'Curtain Config' ),
					'post_content'  => '[BlindMatrix source="curtain-config"]',
					'post_status'   => 'publish',
					'post_author'   => 1,
					'post_type'     => 'page',
					);
					$postID = wp_insert_post( $my_post );
					update_option('curtain_config', $postID);
					update_post_meta( $postID, '_wp_page_template', 'full-width-bm.php' );
				}
			

				$blindproduct = get_option('blindproduct');
				if (!$blindproduct) {
					$my_post = array(
					'post_author' => 1, 
					'post_title' => wp_strip_all_tags( 'Blinds' ),
					'post_type' => 'product',
					'post_status' => 'publish' 
					);
				
					$postID = wp_insert_post( $my_post );
					if ( ! empty( $postID ) && function_exists( 'wc_get_product' ) ) {
						$product = wc_get_product( $postID );
						$product->set_sku( 'pre-' . $postID ); 
						$product->set_regular_price( '20' );
						$product->save(); 
					}

					update_option('blindproduct', $postID);
				}
				flush_rewrite_rules();
				header('Refresh:0');
	}
		$return_msg = '';
	if (isset($_REQUEST['purchase_code_activation'])) {
		$return_msg = purchase_code_activation_callback();
	}
	?>
		<?php
		$current_status = get_option('bmactive');
		if ('pending' == $current_status) {
			?>
			<form method="post"  action="">
				<div class="blind_admin_blocks_container">
					<div class="blind_admin_blocks">
					<h2 style="text-align: center;">14 Days Free Trial</h2>
						<table  class = "form-table">
							<tr valign="top">
								<th scope="row"><label for="Api_currency">Select Region</label></th>
								<td>
								<select name="Api_currency" class="bm-server-selection" style="width:100%;max-width: unset;">	
									<option value="GBP">United Kingdom</option>
									<option value="USD">United States</option>
									<option value="AUD">Australia / New Zealand</option>
									<option value="EUR">Rest of Europe</option>
								</select>
							</tr>
							 <tr style="display:none;" valign="top">
								<th scope="row"><label for="Api_Name">DATABASE NAME</label></th>
								<td><input class="api-name" required type="text" id="Api_Name" name="Api_Name" value="<?php echo wp_kses_post(get_option('Api_Name')); ?>" /></td>
							</tr> 
						</table>
						<div class="conntdb_footer_contianer">
						<input type="hidden" name="blindmatrix_activation">
						<span class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></span>
						 <span style="display:none;" class="skip"><a href="?page=bm&skip=true">Skip ⏭</a></span>
						</div>
					</div>
				</div>
			</form >
			<?php
			$out = ob_get_contents();
			ob_end_flush();
		} else if ('trial_activated' == $current_status || 'trial_expired' == $current_status ) {
			$timestamp = get_option('bmactive_timestamp');
			$now = time();
			$datediff = $now - $timestamp;
			$remaining_dates = 14 - round($datediff / ( 60 * 60 * 24 ));
			$checktime = 'days';
			if (0 == $remaining_dates) {
				$remaining_dates = round(24 * ( 14 - ( $datediff / ( 60 * 60 * 24 ) ) ));
				$checktime = 'hours';
				if (1 == $remaining_dates) {
					$checktime = 'hour';
				} else if (0 == $remaining_dates) {
					$remaining_dates ='few minutes';
					$checktime ='';
				}
			} elseif (1 == $remaining_dates) {
				$checktime = 'day';
			}
			// Purchase code submit 
		
			
			
			
			?>
		
					<div class="blind_admin_blocks_container">
						<div class="blind_admin_blocks">
							<h2>Welcome to Blindmatrix ECommerce</h2>
							<?php if ('trial_activated' == $current_status) { ?>
								<p  class="components">Your trial pack has been activated.<br><sub><em> <?php echo wp_kses_post($remaining_dates); ?></em> <?php echo wp_kses_post($checktime); ?> remaining for your free trial pack to expire.</sub> </p>
								<?php 
							} else { 
								$bmexpired_timestamp = get_option('bmexpired_timestamp');
								?>
								<p  class="components">Your trial pack has been expired at <?php echo wp_kses_post(gmdate('d/m/Y', $bmexpired_timestamp)); ?>.Activate premium to use this plugin features. </p>
							<?php } ?>
						</div>
					</div>
					<?php if ('trial_activated' == $current_status) { ?>
						<div style="padding: 9px; width: 68%; margin: 1% auto;" class="notice notice-success  ">
							<h2><a  href="<?php echo esc_url(admin_url('admin.php?page=bmsettings')); ?>"> Click here</a> to select your products.</h2>
						</div>
					<?php } ?>
					<div style="display:none;" class="blind_admin_blocks_container">
						<div class="blind_admin_blocks">
							<h2>Activate Premium </h2>
							<form method="post">
								<table class="form-table">
									<tr valign="top">
										<th scope="row" class="titledesc">
											<label for="activationcode">Purchase Code</label>
										</th>
										<td class="forminp forminp-text">
											<input name="activationcode" id="activationcode" type="text" style="" class="" placeholder=""> 
										</td>
									</tr>
								</table>
								<button type="submit" class="button-primary" name="purchase_code_activation">Activate </button>
								<?php if ('' != $return_msg) { ?>
								<div style="margin: 10px 0; padding: 9px;" class="notice 
									<?php 
									if ( 'error' == $return_msg['status']) {
										echo( 'notice-error' );
									} else {
										echo( 'notice-success' );} 
									?>
								  is-dismissible">
									<?php print_r($return_msg['msg']); ?>
								</div>
								<?php } ?>
							</form>
						</div>
					</div>
			
				<?php
		} else if ('premium' == $current_status ) {
			$bmpremium_timestamp = get_option('bmpremium_timestamp');
			$now = time();
			$datediff = $now - $bmpremium_timestamp;
			$remaining_dates = 365 - round($datediff / ( 60 * 60 * 24 ));
			?>
				<div style="padding: 9px; width: 40%; margin: 1% auto;" class="notice notice-success  ">
					<a  href="<?php echo esc_url(admin_url('admin.php?page=bmsettings')); ?>"> Click here</a> to customise your product settings.
				</div>
				<div class="blind_admin_blocks_container">
					<div class="blind_admin_blocks" style="width: 70%; margin:auto;text-align: center;">
						<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDUzIiBoZWlnaHQ9IjczIiB2aWV3Qm94PSIwIDAgNDUzIDczIiBmaWxsPSJub25lIgogICAgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICAgIDxyZWN0IHdpZHRoPSIzLjg4NTYxIiBoZWlnaHQ9IjEyLjIxMTkiIHJ4PSIxLjk0MjgxIiB0cmFuc2Zvcm09Im1hdHJpeCgtMC44MjU5NDMgMC41NjM3NTQgMC41NjM3NTUgMC44MjU5NDIgNDA0LjA0NyA0OC41OTQpIiBmaWxsPSIjNjRDQTQzIi8+CiAgICA8cmVjdCB3aWR0aD0iMy4wNTI2NCIgaGVpZ2h0PSI5LjU5NDAxIiByeD0iMS41MjYzMiIgdHJhbnNmb3JtPSJtYXRyaXgoLTAuNjcyMTc3IDAuNzQwMzkxIDAuNzQwMzkxIDAuNjcyMTc2IDQxNy45NyAxMy40OTk4KSIgZmlsbD0iI0ZGMkQ1NSIvPgogICAgPHJlY3Qgd2lkdGg9IjMuNjQyNzYiIGhlaWdodD0iMTEuNDQ4NyIgcng9IjEuODIxMzgiIHRyYW5zZm9ybT0ibWF0cml4KC0wLjYzODIgLTAuNzY5ODcxIC0wLjc2OTg3MiAwLjYzODE5OCAzOTEuNzk1IDI0Ljk1MDQpIiBmaWxsPSIjMTE3QUM5Ii8+CiAgICA8cmVjdCB3aWR0aD0iMy44ODU2MSIgaGVpZ2h0PSIxMi4yMTE5IiByeD0iMS45NDI4IiB0cmFuc2Zvcm09Im1hdHJpeCgtMC40MDQzNzIgLTAuOTE0NTk1IC0wLjkxNDU5NSAwLjQwNDM3MSAxNzAuOTQgMTQuNjkxNCkiIGZpbGw9IiNGRjgwODUiLz4KICAgIDxyZWN0IHdpZHRoPSI1LjM0MjcxIiBoZWlnaHQ9IjE2Ljc5MTQiIHJ4PSIyLjY3MTM2IiB0cmFuc2Zvcm09Im1hdHJpeCgwLjM5MjY0IDAuOTE5NjkyIDAuOTE5NjkyIC0wLjM5MjY0MiAzMjguMTE5IDUwLjYwNTUpIiBmaWxsPSIjRkY4MDg1Ii8+CiAgICA8Y2lyY2xlIHI9IjMuNDM0MjIiIHRyYW5zZm9ybT0ibWF0cml4KC0wLjk0OTE5MyAtMC4zMTQ2OTQgLTAuMzE0Njk0IDAuOTQ5MTkzIDQzMy42OTQgNjAuNTQ0KSIgZmlsbD0iI0YwQjg0OSIvPgogICAgPGVsbGlwc2Ugcng9IjIuMjg5NDgiIHJ5PSIyLjI4OTQ4IiB0cmFuc2Zvcm09Im1hdHJpeCgtMC45NDkxOTMgLTAuMzE0Njk1IC0wLjMxNDY5MyAwLjk0OTE5NCA0NDkuOTkzIDQ0LjAwMDgpIiBmaWxsPSIjQkY1QUYyIi8+CiAgICA8ZWxsaXBzZSByeD0iMS41MjYzMiIgcnk9IjEuNTI2MzIiIHRyYW5zZm9ybT0ibWF0cml4KC0wLjk0OTE5NCAtMC4zMTQ2OTIgLTAuMzE0Njk1IDAuOTQ5MTkzIDM3My4zMzkgNjMuMzEpIiBmaWxsPSIjQkY1QUYyIi8+CiAgICA8ZWxsaXBzZSByeD0iMi4yODk0OCIgcnk9IjIuMjg5NDgiIHRyYW5zZm9ybT0ibWF0cml4KC0wLjk0OTE5NCAtMC4zMTQ2OTIgLTAuMzE0Njk1IDAuOTQ5MTkzIDE2MC43MTMgNTQuMDk3KSIgZmlsbD0iIzA5QjU4NSIvPgogICAgPHJlY3QgeD0iMzE0LjI3MyIgeT0iMTcuMjE5MiIgd2lkdGg9IjUuMzQyNzEiIGhlaWdodD0iMTYuNzkxNCIgcng9IjIuNjcxMzYiIHRyYW5zZm9ybT0icm90YXRlKC01MS43OTU4IDMxNC4yNzMgMTcuMjE5MikiIGZpbGw9IiM5ODRBOUMiLz4KICAgIDxyZWN0IHdpZHRoPSIzLjg4NTYxIiBoZWlnaHQ9IjEyLjIxMTkiIHJ4PSIxLjk0MjgiIHRyYW5zZm9ybT0ibWF0cml4KDAuNjE4NDY1IC0wLjc4NTgxMiAwLjc4NTgxIDAuNjE4NDY3IDI3LjA2MSAzNC43NDEpIiBmaWxsPSIjNjRDQTQzIi8+CiAgICA8cmVjdCB3aWR0aD0iMy42NDI3NiIgaGVpZ2h0PSIxMS40NDg3IiByeD0iMS44MjEzOCIgdHJhbnNmb3JtPSJtYXRyaXgoLTAuOTg4ODgxIC0wLjE0ODcxMSAwLjE0ODcxNCAtMC45ODg4OCAyNjcuNjAyIDI3Ljg2MykiIGZpbGw9IiNFN0MwMzciLz4KICAgIDxyZWN0IHdpZHRoPSIzLjAwNjgyIiBoZWlnaHQ9IjkuNDUiIHJ4PSIxLjUwMzQxIiB0cmFuc2Zvcm09Im1hdHJpeCgwLjIyNjk3MSAwLjk3MzkwMiAtMC45NzM5MDIgMC4yMjY5NjggMjEyLjIwNCA1MSkiIGZpbGw9IiNFN0MwMzciLz4KICAgIDxyZWN0IHdpZHRoPSIzLjg4NTYxIiBoZWlnaHQ9IjEyLjIxMTkiIHJ4PSIxLjk0MjgiIHRyYW5zZm9ybT0ibWF0cml4KDAuNzg1ODEgMC42MTg0NjggLTAuNjE4NDY1IDAuNzg1ODEyIDI2OS4zOTYgNTYuODc4OSkiIGZpbGw9IiMzMzYxQ0MiLz4KICAgIDxjaXJjbGUgY3g9IjkwLjUyNyIgY3k9IjQ1LjY5MjYiIHI9IjMuNDM0MjIiIHRyYW5zZm9ybT0icm90YXRlKC0xLjc5NTc4IDkwLjUyNyA0NS42OTI2KSIgZmlsbD0iI0YwQjg0OSIvPgogICAgPGNpcmNsZSBjeD0iNTkuODU5NiIgY3k9IjI3LjExNTgiIHI9IjIuMjg5NDgiIHRyYW5zZm9ybT0icm90YXRlKC0xLjc5NTc2IDU5Ljg1OTYgMjcuMTE1OCkiIGZpbGw9IiNCRjVBRjIiLz4KICAgIDxjaXJjbGUgY3g9IjMwNy4xMDkiIGN5PSI2MC43NjYzIiByPSIxLjUyNjMyIiB0cmFuc2Zvcm09InJvdGF0ZSgtMS43OTU3NCAzMDcuMTA5IDYwLjc2NjMpIiBmaWxsPSIjRjBDOTMwIi8+CiAgICA8Y2lyY2xlIGN4PSIzNTcuMzExIiBjeT0iMjguNTQ0NCIgcj0iMS41MjYzMiIgdHJhbnNmb3JtPSJyb3RhdGUoLTEuNzk1NzQgMzU3LjMxMSAyOC41NDQ0KSIgZmlsbD0iI0YwQzkzMCIvPgogICAgPGVsbGlwc2UgY3g9IjIzNy4yNDgiIGN5PSI0Ny4zNjc0IiByeD0iMS41MjYzMiIgcnk9IjEuNTI2MzIiIHRyYW5zZm9ybT0icm90YXRlKC0xLjc5NTc4IDIzNy4yNDggNDcuMzY3NCkiIGZpbGw9IiMzMzYxQ0MiLz4KICAgIDxjaXJjbGUgY3g9IjI5MC44NjkiIGN5PSIzOS45MzI5IiByPSIxLjkwNzkiIHRyYW5zZm9ybT0icm90YXRlKC0xLjc5NTc3IDI5MC44NjkgMzkuOTMyOSkiIGZpbGw9IiMzN0U2ODgiLz4KICAgIDxyZWN0IHdpZHRoPSIzLjg4NTYxIiBoZWlnaHQ9IjEyLjIxMTkiIHJ4PSIxLjk0MjgiIHRyYW5zZm9ybT0ibWF0cml4KDAuMzM2NzM1IC0wLjk0MTU5OSAwLjk0MTU5OSAwLjMzNjczNyAxMDguNjg0IDYwLjc1MSkiIGZpbGw9IiM2NENBNDMiLz4KICAgIDxyZWN0IHg9IjEzMS4yNTIiIHk9IjI1LjEyODIiIHdpZHRoPSIzLjg4NTYxIiBoZWlnaHQ9IjEyLjIxMTkiIHJ4PSIxLjk0MjgiIHRyYW5zZm9ybT0icm90YXRlKDUuODE4NjkgMTMxLjI1MiAyNS4xMjgyKSIgZmlsbD0iIzMzNjFDQyIvPgogICAgPGVsbGlwc2Ugcng9IjMuNDM0MjIiIHJ5PSIzLjQzNDIyIiB0cmFuc2Zvcm09Im1hdHJpeCgwLjgyNzI2MiAtMC41NjE4MTYgMC41NjE4MTEgMC44MjcyNjYgMjEuNDU2OSA2Ny43NzUxKSIgZmlsbD0iI0YwQjg0OSIvPgogICAgPGNpcmNsZSBjeD0iMTk1LjgxOSIgY3k9IjMzLjE2NTQiIHI9IjIuMjg5NDgiIHRyYW5zZm9ybT0icm90YXRlKC0zNC4xODEzIDE5NS44MTkgMzMuMTY1NCkiIGZpbGw9IiNCRjVBRjIiLz4KICAgIDxjaXJjbGUgcj0iMS41MjYzMiIgdHJhbnNmb3JtPSJtYXRyaXgoMC44MjcyNjYgLTAuNTYxODEgMC41NjE4MTggMC44MjcyNjEgNjQuMjU0IDY1Ljk3NDUpIiBmaWxsPSIjMzM2MUNDIi8+CiAgICA8ZWxsaXBzZSByeD0iMS45MDc5IiByeT0iMS45MDc5IiB0cmFuc2Zvcm09Im1hdHJpeCgwLjgyNzI2NSAtMC41NjE4MTIgMC41NjE4MTUgMC44MjcyNjMgMi41ODcyNCA0OC4zMDMxKSIgZmlsbD0iIzM3RTY4OCIvPgogICAgPGVsbGlwc2Ugcng9IjEuOTA3OSIgcnk9IjEuOTA3OSIgdHJhbnNmb3JtPSJtYXRyaXgoMC44MjcyNjUgLTAuNTYxODEyIDAuNTYxODE1IDAuODI3MjYzIDI3Ljk3NjkgMTUuNjQ5MykiIGZpbGw9IiNGMEM5MzAiLz4KICAgIDxlbGxpcHNlIGN4PSIyMzEuMzY3IiBjeT0iMjEuMzM2IiByeD0iMi4yODk0OCIgcnk9IjIuMjg5NDgiIHRyYW5zZm9ybT0icm90YXRlKC0zNC4xODEzIDIzMS4zNjcgMjEuMzM2KSIgZmlsbD0iIzA5QjU4NSIvPgogICAgPGVsbGlwc2Ugcng9IjIuMjg5NDgiIHJ5PSIyLjI4OTQ4IiB0cmFuc2Zvcm09Im1hdHJpeCgwLjgyNzI2NyAtMC41NjE4MDkgMC41NjE4MTkgMC44MjcyNiAxMDAuMTY0IDE1LjQyNzEpIiBmaWxsPSIjRkYzQjMwIi8+Cjwvc3ZnPgo=" alt="Completed" class="wooocommerce-task-card__finished-header-image">
					<h2>Welcome to Blindmatrix ECommerce</h2>
						<p  class="components">Your premium pack has been activated at <?php echo wp_kses_post(gmdate('d/m/Y', $bmpremium_timestamp)); ?> </p>
						<p>Your premium will expire in <?php echo wp_kses_post($remaining_dates . ' days'); ?>
					</div>
				</div>
			<?php
		}else if(!$current_status){
			?>
			<div style="padding: 0px 10px; width: 68%; margin: 3% auto;" class="notice notice-success  ">
				<h2><a href="<?php echo esc_url(admin_url('admin.php?page=bm&bm_refresh_page=true')); ?>"> Click here</a> to refresh this page.</h2>
			</div>
			<?php
		}
		?>
		<div class="" style="padding: 9px; width: 70%;margin: 1% auto; overflow: hidden; text-align: right;"> 
			<a class="demo button button-primary" href="https://blindmatrix.com/ecommerce-for-retailers/" target="_blank">Schedule a demo</a>                       
		</div>
		<?php
}
/**
 * Admin menu hook - Check for Purchase code activation.
 */
function purchase_code_activation_callback() {
	if ('premium' == get_option('bmactive')) {
		return;
	}
	
	$empty_err_msg = 'Purchase code is left empty';
	$invalid_err_msg = 'Purchase code is invalid';
	$expired_err_msg = 'Purchase code is expired';
	$err_msg = 'Invalid Key';
	$activation_code = isset($_REQUEST['activationcode']) ? wc_clean(wp_unslash($_REQUEST['activationcode'])):'';
	if (!$activation_code) {
		return array('status' => 'error','msg' => $empty_err_msg);
	}
	
	$decoded_string = json_decode(base64_decode($activation_code));
	if (!$decoded_string || !is_object($decoded_string) || !$decoded_string->sec) {
		return array('status' => 'error','msg' => $invalid_err_msg);
	}
		
	$timestamp = strtotime('+1 day', $decoded_string->sec);
	if (time() > $timestamp) {
		return array('status' => 'error','msg' => $expired_err_msg);
	}
	
	$server_type = $decoded_string->server;
	$db_name = $decoded_string->name;
	$post_id = $decoded_string->id;
	if (!$post_id) {
		return array('status' => 'error','msg'=> $invalid_err_msg);
	}
	
	$requested_response = BlindMatrix_User_Request::send_request(array('id' => $post_id), 'GET');
	$response_object = isset($requested_response->post_data) && is_object($requested_response->post_data) ? $requested_response->post_data:false;
	if (!is_object($response_object) || !isset($response_object->post_status) || 'premium' == $response_object->post_status) {
		return array('status' => 'error','msg' => 'Purchase Key already used');
	}
	
	if (!is_object($response_object) || !isset($response_object->activation_key) || $response_object->activation_key != $activation_code) {
		return array('status' => 'error','msg' => 'Activation code is invalid');
	}
			
	$timestamp = time();
	$response = BlindMatrix_User_Request::send_request(array('id' =>$post_id,'post_status' =>'premium' ,'premium_activated_date' => gmdate('Y-m-d H:i:s', $timestamp)), 'POST', $response_object);
	if (!is_object($response) || !isset($response->post_id)) {
		return array('status' => 'error','msg' => $invalid_err_msg);
	}
	
	if ('uk' == $server_type) {
		$url = 'https://blindmatrix.biz/api/api-ecommerce-live.php';
	} else if ('us' == $server_type) {
		$url = 'https://blindmatrix.us/api/api-ecommerce-live.php';
	} else {
		$url = 'https://blindmatrix.au/api/api-ecommerce-live.php';
	}
	
	update_option('Api_Name', $db_name);
	update_option('Api_Url', $url );
	update_option('bmactive', 'premium');
	$tabs = array('Blinds','Shutters','Curtains');
	foreach ($tabs as $tab) {
		blindmatrix_delete_menu_ids($tab);
	}
	delete_option('option_blindmatrix_settings');
	delete_option('bm_stored_curtains_menu_ids');
	delete_option('bm_stored_shutters_menu_ids');
	delete_option('bm_stored_blinds_menu_ids');
	ob_start();
	if (get_option('woocommerce_email_from_address') != '') {
		$mail_email = get_option('woocommerce_email_from_address');
	} else {
		$mail_email = get_bloginfo('new_admin_email');
	}
	?>
		<div style="padding:0 10px;">
		<img src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BM_ECO_PLUGIN_FILE ))); ?>/assets/image/premium.jpg">
		<p style="margin: 3px 0;color: black;margin-top: 15px;"><strong>Regards,</strong></p>
		<p style="margin: 3px 0;color: black;">Blindmatrix Ecommerce</p>
		<img width="200" src="<?php echo esc_url(untrailingslashit( plugins_url( '/', BM_ECO_PLUGIN_FILE ))); ?>/assets/image/Logo.png">
		<div>
	<?php
	$message = ob_get_clean();
	$email_heading='Blindmatrix Premium Version has been activated';
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$mail = wp_mail($mail_email, $email_heading, $message, $headers );
	update_option('bm_requested_post_id', $post_id);
	update_option('bmpremium_timestamp', $timestamp);
	$blindproduct = get_option('blindproduct');
	wp_delete_post($blindproduct, true);
	delete_option('blindproduct');
	if (!get_option('blindproduct')) {
		$my_post = array(
			'post_author' => 1, 
			'post_title' => wp_strip_all_tags( 'Blinds' ),
			'post_type' => 'product',
			'post_status' => 'publish' 
		);
		
		$postID = wp_insert_post( $my_post );
		if ( ! empty( $postID ) && function_exists( 'wc_get_product' ) ) {
			$product = wc_get_product( $postID );
			$product->set_sku( 'pre-' . $postID ); 
			$product->set_regular_price( '20' );
			$product->save(); 
		}

		update_option('blindproduct', $postID);
	}
	$_SESSION['cart'] = array();
	
	header('Refresh:0');
	return array('status' => 'success','msg'=> 'Your premium has been successfully activated.');
}

register_activation_hook( __FILE__, 'blindmatrix_plugin_activation' );
function blindmatrix_plugin_activation() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	add_option( 'blinds_activation_redirect', true );	
	$current_status = get_option('bmactive');	
	
	if (!class_exists('BlindMatrix_User_Request')) {
		include(dirname( __FILE__ ) . '/Api/class-user-request.php');
	}
	
	if (!$current_status) {
		add_option('bmactive', 'pending');
	} else {
		// Blinds, Shutters & Curtains page creation.
		$blinds_list = get_option('blinds_list');
		if (!$blinds_list) {
			$my_post = array(
				'post_title'    => wp_strip_all_tags( 'Blinds List' ),
				'post_content'  => '[BlindMatrix source="Blinds-Archive"]',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_type'     => 'page',
			);
			$postID = wp_insert_post( $my_post );
			update_option('blinds_list', $postID);
			update_post_meta( $postID, '_wp_page_template', 'full-width-bm.php' );
		}
			$blinds_config = get_option('blinds_config');
		if (!$blinds_config) {
			$my_post = array(
				'post_title'    => wp_strip_all_tags( 'Blinds Config' ),
				'post_content'  => '[BlindMatrix source="blinds-config"]',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_type'     => 'page',
			);
			$postID = wp_insert_post( $my_post );
			update_option('blinds_config', $postID);
			update_post_meta( $postID, '_wp_page_template', 'full-width-bm.php' );
		}	
			$sample_cart = get_option('sample_cart');
		if (!$sample_cart) {
			$my_post = array(
				'post_title'    => wp_strip_all_tags( 'Sample Cart' ),
				'post_content'  => '[BlindMatrix source="Sample-Cart"]',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_type'     => 'page',
			);
			$postID = wp_insert_post( $my_post );
			update_option('sample_cart', $postID);
			update_post_meta( $postID, '_wp_page_template', 'default-bm.php' );
		}
			$quick_quote = get_option('quick_quote');
		if (!$quick_quote) {
			// $my_post = array(
			// 	'post_title'    => wp_strip_all_tags( 'Quick Quote' ),
			// 	'post_content'  => '[BlindMatrix source="Quick-Quote"]',
			// 	'post_status'   => 'publish',
			// 	'post_author'   => 1,
			// 	'post_type'     => 'page',
			// );
			// $postID = wp_insert_post( $my_post );
			// update_option('quick_quote', $postID);
			// update_post_meta( $postID, '_wp_page_template', 'default-bm.php' );
		}
			$shutter_type = get_option('shutter_type');
		if (!$shutter_type) {
			$my_post = array(
				'post_title'    => wp_strip_all_tags( 'Shutter Type' ),
				'post_content'  => '[BlindMatrix source="shutter-type"]',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_type'     => 'page',
			);
			$postID = wp_insert_post( $my_post );
			update_option('shutter_type', $postID);
			update_post_meta( $postID, '_wp_page_template', 'full-width-bm.php' );
		}
			$shutter_single_type = get_option('shutter_single_type');
		if (!$shutter_single_type) {
			$my_post = array(
				'post_title'    => wp_strip_all_tags( 'Shutter Single Type' ),
				'post_content'  => '[BlindMatrix source="shutter-type-styles"]',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_type'     => 'page',
			);
			$postID = wp_insert_post( $my_post );
			update_option('shutter_single_type', $postID);
			update_post_meta( $postID, '_wp_page_template', 'full-width-bm.php' );
		}
			$shutter_config = get_option('shutter_config');
		if (!$shutter_config) {
			$my_post = array(
				'post_title'    => wp_strip_all_tags( 'Shutter Config' ),
				'post_content'  => '[BlindMatrix source="shutter-config"]',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_type'     => 'page',
			);
			$postID = wp_insert_post( $my_post );
			update_option('shutter_config', $postID);
			update_post_meta( $postID, '_wp_page_template', 'full-width-bm.php' );
		}
			$curtains_single = get_option('curtains_single');
		if (!$curtains_single) {
			$my_post = array(
				'post_title'    => wp_strip_all_tags( 'Curtains Single' ),
				'post_content'  => '[BlindMatrix source="curtain-single"]',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_type'     => 'page',
			);
			$postID = wp_insert_post( $my_post );
			update_option('curtains_single', $postID);
			update_post_meta( $postID, '_wp_page_template', 'full-width-bm.php' );
		}
			$curtain_config = get_option('curtain_config');
		if (!$curtain_config) {
			$my_post = array(
				'post_title'    => wp_strip_all_tags( 'Curtain Config' ),
				'post_content'  => '[BlindMatrix source="curtain-config"]',
				'post_status'   => 'publish',
				'post_author'   => 1,
				'post_type'     => 'page',
			);
			$postID = wp_insert_post( $my_post );
			update_option('curtain_config', $postID);
			update_post_meta( $postID, '_wp_page_template', 'full-width-bm.php' );
		}
			

			$blindproduct = get_option('blindproduct');
		if (!$blindproduct) {
			$my_post = array(
				'post_author' => 1, 
				'post_title' => wp_strip_all_tags( 'Blinds' ),
				'post_type' => 'product',
				'post_status' => 'publish' 
			);
				
			$postID = wp_insert_post( $my_post );
			if ( ! empty( $postID ) && function_exists( 'wc_get_product' ) ) {
				$product = wc_get_product( $postID );
				$product->set_sku( 'pre-' . $postID ); 
				$product->set_regular_price( '20' );
				$product->save(); 
			}
			update_option('blindproduct', $postID);
		}
			flush_rewrite_rules();
	}
	// Data stored via API
	$post_id = BlindMatrix_User_Request::get_requested_post_id();
	if ($post_id) {
		$response = BlindMatrix_User_Request::send_request(array('id' =>$post_id,'plugin_status' =>'activated' ), 'POST');
	} else {
		$response = BlindMatrix_User_Request::send_request(array(), 'POST');
		update_option('bm_requested_post_id', $response->post_id);
	}
	
		bm_create_menu_items();
}

register_deactivation_hook(__FILE__, 'blindmatrix_plugin_deactivation');
function blindmatrix_plugin_deactivation() {
	$post_id = BlindMatrix_User_Request::get_requested_post_id();
	if ($post_id) {
		$response = BlindMatrix_User_Request::send_request(array('id' =>$post_id,'plugin_status' =>'deactivated' ), 'POST');
	}
	
	$ids = array();
	$ids['blinds_list'] = get_option('blinds_list');
	$ids['blinds_config'] = get_option('blinds_config');
	$ids['sample_cart'] = get_option('sample_cart');
	$ids['quick_quote'] = get_option('quick_quote');
	$ids['shutter_type'] = get_option('shutter_type');
	$ids['shutter_config'] = get_option('shutter_config');
	$ids['shutter_single_type'] = get_option('shutter_single_type');
	$ids['curtains_single'] = get_option('curtains_single');
	$ids['curtain_config'] = get_option('curtain_config');
	$ids['blindproduct'] = get_option('blindproduct');
	$ids['my_rules_have_been_flushed'] = get_option('my_rules_have_been_flushed');
	foreach ($ids as $key=>$id) {
		wp_delete_post($id, true);
		delete_option($key);
	}
	
	$tabs = array('Blinds','Shutters','Curtains');
	foreach ($tabs as $tab) {
		blindmatrix_delete_menu_ids($tab);
	}
}

function blinds_redirect() {
	flush_rewrite_rules();
	if ( get_option( 'blinds_activation_redirect', false ) ) {
		delete_option( 'blinds_activation_redirect' );
			wp_redirect( 'admin.php?page=bm' );
			exit;
	}
}
 add_action( 'admin_init', 'blinds_redirect' );
 add_action( 'init', 'blinds_verfication' );

function blinds_verfication() {
	if (!class_exists('BlindMatrix_User_Request')) {
		include(dirname( __FILE__ ) . '/Api/class-user-request.php');
	}

	if(isset($_GET['bm_refresh_page']) && true == $_GET['bm_refresh_page']){
		blindmatrix_plugin_activation();
		wp_redirect( 'admin.php?page=bm' );
		exit;
	}
	
	$bmactive = get_option('bmactive');
	if (!empty($bmactive) && 'trial_activated' == $bmactive) {
		$bmactive_timestamp = get_option('bmactive_timestamp');
		$now = time();
		$datediff = $now - $bmactive_timestamp;
		$remaining_dates = 7 - round($datediff / ( 60 * 60 * 24 ));
		if ($remaining_dates <= 0) {
			update_option('bmactive', 'trial_expired');
			$post_id = BlindMatrix_User_Request::get_requested_post_id();
			if ($post_id) {
				$response = BlindMatrix_User_Request::send_request(array('id' =>$post_id,'plugin_expired_date' =>gmdate('Y-m-d H:i:s', $now),'post_status' => 'expired'), 'POST');
			}
			
			add_option('bmexpired_timestamp', $now);
			$ids = array();
			$ids['blinds_list'] = get_option('blinds_list');
			$ids['blinds_config'] = get_option('blinds_config');
			$ids['sample_cart'] = get_option('sample_cart');
			$ids['quick_quote'] = get_option('quick_quote');
			$ids['shutter_type'] = get_option('shutter_type');
			$ids['shutter_config'] = get_option('shutter_config');
			$ids['shutter_single_type'] = get_option('shutter_single_type');
			$ids['curtains_single'] = get_option('curtains_single');
			$ids['curtain_config'] = get_option('curtain_config');
			$ids['blindproduct'] = get_option('blindproduct');
			$ids['my_rules_have_been_flushed'] = get_option('my_rules_have_been_flushed');
			foreach ($ids as $key=>$id) {
				wp_delete_post($id, true);
				delete_option($key);
			}
			$tabs = array('Blinds','Shutters','Curtains');
			foreach ($tabs as $tab) {
				blindmatrix_delete_menu_ids($tab);
			}
		} 
		
	}
}
add_action('init', function() {

	$blindproduct = get_option('blindproduct');
	if ( '1' !== get_option('my_rules_have_been_flushed') && $blindproduct ) {
		flush_rewrite_rules();
		update_option('my_rules_have_been_flushed', '1');
	}
	
});

add_action('woocommerce_cart_calculate_fees', 'blindmatrix_set_fees', 9999);
/**
 * Set Cart Fees.
 */
function blindmatrix_set_fees() {
	$cart_fees = WC()->cart->get_fees();
	if (!is_array($cart_fees) || empty($cart_fees) || !checkBlindProduct()) {
		return;
	}
	
	foreach (WC()->cart->get_fees() as $key => $fee) {
		if (!is_object($fee)) {
			continue;
		}
		
		if (!str_contains($fee->id, 'delivery') && !str_contains($fee->id, 'installation-charge') && !str_contains($fee->id, 'vat') ) {
			unset($cart_fees[$key]);
		}
	}
	
	WC()->cart->fees_api()->set_fees($cart_fees);
}

add_filter('woocommerce_product_is_taxable', 'blindmatrix_check_product_is_taxable', 999, 2);
/**
 * Check blinds/shutters/curtains Product is taxable or not.
 */
function blindmatrix_check_product_is_taxable( $is_tax, $product) {
	$blind_product_id = get_option('blindproduct');
	if (!$blind_product_id) {
		return $is_tax;
	}
		
	return $blind_product_id == $product->get_id() ? false:$is_tax;
}


add_filter('woocommerce_coupons_enabled', 'blindmatrix_check_coupons_enabled', 9999);
/**
 * Check Coupons Enabled for blinds/shutters/curtains product.
 */
function blindmatrix_check_coupons_enabled( $is_coupons_enabled) {
	if ( is_admin() || !checkBlindProduct() ) {
		return $is_coupons_enabled;
	}
	
	return false;
}

add_action( 'woocommerce_check_cart_items', 'blindmatrix_check_cart_coupons');
/**
 * Check Coupons applied for blinds/shutters/curtains produc in Cart Page
 */
function blindmatrix_check_cart_coupons() {
	if (empty( WC()->cart->get_applied_coupons() ) || !checkBlindProduct() ) {
		return;
	}
	
	foreach ( WC()->cart->get_applied_coupons() as $code ) {
		$coupon = new WC_Coupon( $code );
		if ( is_object($coupon) && $coupon->is_valid() ) {
			WC()->cart->remove_coupon( $code );
		}
	}
}
function hexToRgb( $hex, $alpha = false) {
	$hex      = str_replace('#', '', $hex);
	$length   = strlen($hex);
	$rgb['r'] = hexdec(6 == $length ? substr($hex, 0, 2) : ( 3 == $length ? str_repeat(substr($hex, 0, 1), 2) : 0 ));
	$rgb['g'] = hexdec(6 == $length ? substr($hex, 2, 2) : ( 3 == $length ? str_repeat(substr($hex, 1, 1), 2) : 0 ));
	$rgb['b'] = hexdec(6 == $length ? substr($hex, 4, 2) : ( 3 == $length ? str_repeat(substr($hex, 2, 1), 2) : 0 ));
	if ( $alpha ) {
		$rgb['a'] = $alpha;
	}
	return $rgb;
}
function plugin_primary_color() {
		$option_blindmatrix_settings = get_option( 'option_blindmatrix_settings' );
		$bm_primary_color = isset($option_blindmatrix_settings['bm_primary_color']) ? $option_blindmatrix_settings['bm_primary_color']:'';
	if (!isset($option_blindmatrix_settings['bm_primary_color']) || ( isset($option_blindmatrix_settings['bm_primary_color']) && '' == $option_blindmatrix_settings['bm_primary_color'] )) {
		$bm_primary_color = '#00c2ff';
	}
		$rgb = hexToRgb($bm_primary_color);
	
	?>
	<style type="text/css">
	    :root {
		  --bm-primary-color: <?php echo wp_kses_post($rgb['r']); ?>,<?php echo wp_kses_post($rgb['g']); ?>, <?php echo wp_kses_post($rgb['b']); ?>;
		}
		.wp-core-ui .demo.button-primary {
   			background: #48CC71;
    		border-color: #48CC71;
    		color: #fff;
    		text-decoration: auto;
    		text-shadow: none;
    		text-transform: uppercase;
    		font-weight: 500;
   			transition: background .2s;
		}
		.wp-core-ui .demo.button-primary:hover {
    		background: #27ae60;
   			border-color: #27ae60;
    		color: #fff;
		}
		.wp-core-ui .demo.button-primary:focus {
    		 box-shadow: unset;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'plugin_primary_color');
add_action( 'admin_head', 'plugin_primary_color');

function blindmatrix_get_plugin_url(){
    return plugin_dir_url(__FILE__);
}
?>
