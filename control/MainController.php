<?php 
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

abstract class MainController {

	public function __construct(){}
	public function loadview( $view_file_name, $varibles = array()) {
		if (file_exists(wp_kses_post(BM_ECO_VIEW) . '/' . wp_kses_post($view_file_name) . '.php')) {
			 extract($varibles);
			 include(wp_kses_post(BM_ECO_VIEW) . '/' . wp_kses_post($view_file_name) . '.php');
		} else {
			 die('File Not Found in the location.' . wp_kses_post(BM_ECO_VIEW) . '/' . wp_kses_post($view_file_name));
		}
	}
	
	public function loadmodel( $model_file_name) {
		if (file_exists(wp_kses_post(BM_ECO_MODEL) . '/' . wp_kses_post($model_file_name) . '.php')) {
			 include(wp_kses_post(BM_ECO_MODEL) . '/' . wp_kses_post($model_file_name) . '.php');
			 return new $model_file_name();
		} else {
			 die('File Not Found in the location.' . wp_kses_post(BM_ECO_MODEL) . '/' . wp_kses_post($model_file_name));
		}
	}
}


