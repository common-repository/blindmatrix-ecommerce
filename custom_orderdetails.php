<?php



if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class OrderDetails_List extends WP_List_Table {

	/** Class constructor */
	public function __construct() {
		add_action( 'admin_head', array( &$this, 'admin_header' ) );
		parent::__construct( array(
			'singular' => __( 'Free Sample', 'sp' ), //singular name of the listed records
			'plural'   => __( 'Free Samples', 'sp' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		) );

	}


	/**
	 * Retrieve customers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_customers( $per_page = 5, $page_number = 1 ) {
		$order_by = isset($_REQUEST['orderby']) ? wc_clean(wp_unslash($_REQUEST['orderby'])):'';
		$order = isset($_REQUEST['order']) ? wc_clean(wp_unslash($_REQUEST['order'])):'';
		$json_response = CallAPI('POST', $post=array('mode'=>'get_bm_order_details','per_page'=>$per_page,'page_number'=>$page_number,'orderby'=>$order_by,'sort'=>$order));
		$json_response= json_decode(json_encode($json_response), true);
		return $json_response['get_bm_order_details'];
	}


	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_customer( $id ) {
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}customers",
			array( 'ID' => $id ),
			array( '%d' )
		);
	}


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;
		$json_response = CallAPI('POST', $post=array('mode'=>'get_bm_order_details','action'=>'count'));
		return $json_response->sample_order_count;
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		esc_html_e( 'No customers avaliable.', 'sp' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	
			
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'sno':
				return $item[ $column_name ];
			case 'salesorder_no':
				return $item[ $column_name ];
			case 'salesorderid':
				return $item[ $column_name ];
			case 'customer_name':
				 $name = $item[ 'firstname' ] . ' ' . $item[ 'surname' ];
				return $name;
			case 'email':
				return $item[ $column_name ];
			case 'mobile':
				return $item[ $column_name ];
			case 'paymentMethods':
				return $item[ $column_name ];
			case 'amount':
				return $item[ $column_name ];
			case 'creation_time_data':
				return $item[ $column_name ];
			case 'cus_createddate':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
		);
	}


	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	public function column_name( $item ) {

		$delete_nonce = wp_create_nonce( 'sp_delete_customer' );

		$title = '<strong>' . $item['name'] . '</strong>';
		$page = isset($_REQUEST['page'] ) ? wc_clean(wp_unslash($_REQUEST['page'] )):'';
		$actions = array(
			'delete' => sprintf( '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Delete</a>', $page, 'delete', absint( $item['ID'] ), $delete_nonce )
		);

		return $title . $this->row_actions( $actions );
	}


	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'sno' => __( 'S.No', 'sp' ),
			'salesorder_no' => __( 'Order Number', 'sp' ),
			'salesorderid'    => __( 'Order ID', 'sp' ),
			'customer_name'    => __( 'Customer Name', 'sp' ),
			'email'    => __( 'Customer Email', 'sp' ),
			'mobile'    => __( 'Mobile Number', 'sp' ),
			'paymentMethods'    => __( 'Payment Type', 'sp' ),
			'amount'    => __( 'Order Total', 'sp' ),
			'creation_time_data'    => __( 'Payment Date/Time', 'sp' ),
			'cus_createddate'    => __( ' Created Date/Time', 'sp' ),
		);

		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array();

		return $sortable_columns;
	}
	public function admin_header() {
		  $page = ( isset($_GET['page'] ) ) ? wc_clean(wp_unslash( $_GET['page'] )) : false;
		if ( 'ordersdetails' != $page ) {
			return;
		} 

		  echo '<style type="text/css">';
		  echo '.wp-list-table .column-sno { width: 5%; }';
		  echo '.wp-list-table .column-salesorder_no { width: 12%; }';
		  echo '.wp-list-table .column-salesorderid { width: 8%; }';
		  echo '.wp-list-table .column-customer_name { width: 15%; }';
		  echo '.wp-list-table .column-email { width: 25%; }';
		  echo '.wp-list-table .column-mobile { width: 15%; }';
		  echo '.wp-list-table .column-paymentMethods { width: 15%; }';
		  echo '.wp-list-table .column-amount { width: 10%; }';
		  echo '.wp-list-table .column-creation_time_data { width: 20%; }';
		  echo '.wp-list-table .column-cus_createddate { width: 20%; }';
		  echo '</style>';
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = array();

		return $actions;
	}


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'customers_per_page', 30  );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( array(
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		) );

		$this->items = self::get_customers( $per_page, $current_page );
	}

	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {
			$nonce = isset( $_REQUEST['_wpnonce'] ) ? wc_clean(wp_unslash( $_REQUEST['_wpnonce'] )):'';
			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $nonce);

			if ( ! wp_verify_nonce( $nonce, 'sp_delete_customer' ) ) {
				die( 'Go get a life script kiddies' );
			} else {
				$customer = isset($_GET['customer']) ? wc_clean(wp_unslash($_GET['customer'])):'';
				self::delete_customer( absint( $customer) );

						// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
						// add_query_arg() return the current url
						wp_redirect( esc_url_raw(add_query_arg()) );
				exit;
			}

		}

		// If the delete bulk action is triggered
		$action = isset( $_REQUEST['action'] ) ? wc_clean(wp_unslash($_REQUEST['action'] )):'';
		$action2 = isset($_REQUEST['action2']) ? wc_clean(wp_unslash($_REQUEST['action2'])):'';
		if ( ( $action && 'bulk-delete' == $action ) || ( $action2 && 'bulk-delete' == $action2 )) {

			$delete_ids = isset( $_REQUEST['bulk-delete']) ? wc_clean(wp_unslash( $_REQUEST['bulk-delete'])):array();

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_customer( $id );

			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
				// add_query_arg() return the current url
				wp_redirect( esc_url_raw(add_query_arg()) );
			exit;
		}
	}

}


class OrderDetails {

	// class instance
	public static $instance;

	// customer WP_List_Table object
	public $customers_obj;

	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', array( __CLASS__, 'set_screen' ), 10, 3 );
		add_action( 'admin_menu', array( $this, 'plugin_menu' ) );
	}


	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function plugin_menu() {
		$hook = add_submenu_page(
			'myplugin',
			'Order Details',
			'Order Details',
			'manage_options',
			'ordersdetails',
			array( $this, 'plugin_settings_page' )
			
		);
		add_action( "load-$hook", array( $this, 'screen_option' ) );

	}


	/**
	 * Plugin settings page
	 */
	public function plugin_settings_page() {
		?>
		<div class="wrap">
		<h2>Order Details</h2>
			
		<?php
		$this->customers_obj->prepare_items();
		$this->customers_obj->display(); 
		?>
	</div>
		
		<?php
	}

	/**
	 * Screen options
	 */
	public function screen_option() {

		$this->customers_obj = new OrderDetails_List();
	}


	/** Singleton instance */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}


add_action( 'plugins_loaded', function () {
	OrderDetails::get_instance();
} );
