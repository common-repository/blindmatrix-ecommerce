<?php
define('BM_ECO_SLUG', 'blindmatrix-ecommerce');
define( 'BM_ECO_PLUGIN_DIR', dirname(__FILE__) . '/' );
define('BM_ECO_DIR', WP_PLUGIN_DIR . '/' . BM_ECO_SLUG);
define('BM_ECO_CONTROLLER', BM_ECO_DIR . '/control');
define('BM_ECO_MODEL', BM_ECO_DIR . '/model');
define('BM_ECO_VIEW', BM_ECO_DIR . '/view');
define('BM_ECO_URL', WP_PLUGIN_URL . '/blindmatrix-ecommerce');
include(BM_ECO_CONTROLLER . '/MainController.php');
include(BM_ECO_CONTROLLER . '/BlindWooc.php');
$blindwooc = new BlindWooc();

