<?php
/**
 * API functions.
 *
 */

defined( 'ABSPATH' ) || exit;

function CallAPI( $method, $data = false) {
	try {
		$DB_API_NAME = get_option('Api_Name', true);
		$DB_API_URL = get_option('Api_Url', true );
		$url = $DB_API_URL . '?company_name=' . $DB_API_NAME;
	
		$curl = curl_init();
	
		switch ($method) {
			case 'POST':
				curl_setopt($curl, CURLOPT_POST, 1);
	
				if ($data) {
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				}
				break;
			case 'PUT':
				curl_setopt($curl, CURLOPT_PUT, 1);
				break;
			default:
				if ($data) {
					$url = sprintf('%s&%s', $url, http_build_query($data));
				}
		}
		// Optional Authentication:
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_USERPWD, 'BlindMatrix:Welcome@2021');
	
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1); // don't use a cached version of the url
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
	
		$result = curl_exec($curl);
		
		if (curl_errno($curl)) {
			$error_msg = curl_error($curl);
			custom_logs($error_msg);
		}
		
		curl_close($curl);
	
		return json_decode($result);
	
	} catch (Exception $e) {
		$error_message = $e->getMessage();
		custom_logs($error_message);
	}
}

function blindmatrix_get_request(){
	return $_REQUEST;
}

function blindmatrix_get_session(){
	return isset($_SESSION['cart']) ? $_SESSION['cart']:'';
}