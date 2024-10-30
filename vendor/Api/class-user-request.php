<?php
/**
 * User Request.
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class BlindMatrix_User_Request
 */
class BlindMatrix_User_Request {
	/**
	 * Send user request
	 */
	public static function send_request( $extra_args = array(), $method = 'GET', $response = false) {
		$url = 'https://plugin.blindssoftware.com/';
		if (site_url() == $url) {
			return;
		}

		$url = untrailingslashit($url) . '/wp-json/bm/v1/userslist';
		$user_name_and_pwd = 'admin:Welcome@2021';

		$args = array(
			'url_info' => site_url(), 
			'ip_address' => self::get_ip_address(),
			'user_info' => self::get_user_info(),
			'plugin_activated_date' => '',
			'plugin_status' => 'activated',
			'reports' => self::get_reports(),
			'id'  =>'',
		);
		
		if (is_object($response) && $response->url_info) {
			unset($args['url_info']);
		}
		
		if (is_object($response) && $response->ip_address) {
			unset($args['ip_address']);
		}
		
		if (is_object($response) && $response->user_info) {
			unset($args['user_info']);
		}
		
		if (is_object($response) && 'premium' == $response->post_status) {
			$args['premium_site_url'] = site_url();
			$args['premium_user_info'] = self::get_ip_address();
			$args['premium_ip_address'] = self::get_user_info();
		}
		
		if (!empty($extra_args) && is_array($extra_args)) {
			$args = array_merge($args, $extra_args);
		}

		if('GET' == $method){
			unset($args['reports']);
		}

		$curl = curl_init();
		switch ($method) {
			case 'POST':
			case 'PUT':
				curl_setopt($curl, CURLOPT_POST, 1);
				if ($args) {
					curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
				}
				break;
			default:
				if ($args) {
					$url = sprintf('%s?%s', $url, http_build_query($args));
				}
		}

		// Optional Authentication.
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, $user_name_and_pwd);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		if (curl_errno($curl)) {
			return;
		}

		curl_close($curl);

		return json_decode($result);
	}
	/**
	 * Get IP Address.
	 */
	public static function get_ip_address() {
		$server = $_SERVER;
		if ( isset( $server['HTTP_X_REAL_IP'] ) ) {
			return sanitize_text_field( wp_unslash( $server['HTTP_X_REAL_IP'] ) );
		} elseif ( isset( $server['HTTP_X_FORWARDED_FOR'] ) ) {
			// Proxy servers can send through this header like this: X-Forwarded-For: client1, proxy1, proxy2
			// Make sure we always only send through the first IP in the list which should always be the client IP.
			return (string) rest_is_ip_address( trim( current( preg_split( '/,/', sanitize_text_field( wp_unslash( $server['HTTP_X_FORWARDED_FOR'] ) ) ) ) ) );
		} elseif ( isset( $server['REMOTE_ADDR'] ) ) {
			return sanitize_text_field( wp_unslash( $server['REMOTE_ADDR'] ) );
		}
		return '';
	}

	/**
	 * Get user info.
	 */
	public static function get_user_info() {
		return serialize(array(
			'userid'       => get_current_user_id(),
			'from_name'    => !empty(get_option('woocommerce_email_from_name')) ? get_option('woocommerce_email_from_name'):get_bloginfo('name'),
			'from_address' => !empty(get_option('woocommerce_email_from_address')) ? get_option('woocommerce_email_from_address'): get_bloginfo('admin_email'),
		));
	}
	/**
	 * Get reports.
	 */
	public static function get_reports(){
    	if(!function_exists('wc')){
			return '';
		}
        
    	$reports = wc()->api->get_endpoint_data( '/wc/v3/system_status' );
        if(!empty($reports)){
        	return json_encode($reports);
        }
    }
	
	/**
	 * Get requested post id.
	 */
	public static function get_requested_post_id() {
		$response = self::send_request(array('url_info' =>wp_unslash(site_url())), 'GET');
		$post_id = get_option('bm_requested_post_id');
		if (is_object($response) && isset($response->post_id)) {
			return $response->post_id;
		}
		
		return $post_id;
	}
	
	/**
	 * Send premium request.
	 */
	public static function send_premium_request( $args = array(), $method = 'PUT') {
		$url = 'https://plugin.blindssoftware.com/';
		if (site_url() == $url) {
			return;
		}

		$url = untrailingslashit($url) . '/wp-json/bm/v1/premiumrequest';
		$user_name_and_pwd = 'admin:Welcome@2021';

		$args = array(
			'name'               => isset($args['name']) ? $args['name']:'',
			'email'              => isset($args['email']) ? $args['email']:'',
			'phone_number'       => isset($args['phone_number']) ? $args['phone_number']:'',
			'company_name'  	 => isset($args['company_name']) ? $args['company_name']:'',
			'site_url'           => isset($args['site_url']) ? $args['site_url']:'',
			'parent_id'  		 => self::get_requested_post_id(),
		);

		$curl = curl_init();
		switch ($method) {
			case 'POST':
			case 'PUT':
				curl_setopt($curl, CURLOPT_POST, 1);
				if ($args) {
					curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
				}
				break;
			default:
				if ($args) {
					$url = sprintf('%s?%s', $url, http_build_query($args));
				}
		}

		// Optional Authentication.
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, $user_name_and_pwd);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		if (curl_errno($curl)) {
			return;
		}
		
		curl_close($curl);

		return json_decode($result);
	}
	
}
