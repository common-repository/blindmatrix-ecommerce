<?php
class BlindSetting {

	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;
	
	/**
	 * Msg Data.
	 */
	private $msg_data;

	/**
	 * Start up
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
		add_filter('woocommerce_screen_ids', array($this,'set_screen_ids'), 999);
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {
	
		$bmactive = get_option('bmactive');
		if (( isset($bmactive) && 'trial_activated' == $bmactive ) ||( isset($bmactive) && 'premium' == $bmactive ) ) {
			if ('Connected' == get_option('bmactive_dbconnect')) {
				add_submenu_page('bm', 'Settings', 'Settings', 'manage_options', 'bmsettings', array( $this, 'create_admin_page' )  );	
			}
			if (!blindmatrix_check_premium()) {
				add_submenu_page('bm', '', '<div id="bm_premium_link" class="blindmatrix-upgrade-premium-popup"><span class="dashicons dashicons-star-filled" style="font-size: 17px"></span> ' . __('Go Premium', 'bm') . '</div>', 'manage_options', 'pridirect', array($this, 'display_external_redirects'));
			}
			
		}
	}


	/**
	 * Options page callback
	 */
	public function display_external_redirects() {
		wp_safe_redirect(admin_url( 'admin.php?page=bmsettings' ));
		exit;
	}
	
	public function create_admin_page() {
		// Set class property
		$this->options = get_option( 'option_blindmatrix_settings', array() );
		$stored_settings = get_option('option_blindmatrix_settings');
		?>

		<div class="wrap woocommerce">
		<?php
		$_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']):'';
		if (!isset($_GET['tab']) || ( isset($_GET['tab']) && 'shortcode' != $_tab ) ) {
			?>
			<form method="post" action="options.php">
			<?php
		}
		?>
				
			<nav class="bm_setting_page nav-tab-wrapper woo-nav-tab-wrapper">
				<?php
				$current_tab=  !isset($_GET['tab'] ) && empty( $_GET['tab'] ) ? 'products' : sanitize_title( wp_unslash( $_GET['tab'] ) );
				
				$tabs= array('products'=>'Products','shortcode'=>'Customize the Design','go_premium' => 'Go Premium' );
				if (blindmatrix_check_premium()) {
					unset($tabs['go_premium']);
				}
		
				$get_productlist = get_option('productlist', true);
				
				if (isset($get_productlist->product_list) && is_array($get_productlist->product_list) && count($get_productlist->product_list) == 0 && isset($get_productlist->shutter_product_list) && is_array($get_productlist->shutter_product_list) && count($get_productlist->shutter_product_list) == 0 && isset($get_productlist->curtain_product_list) && is_array($get_productlist->curtain_product_list) && count($get_productlist->curtain_product_list) == 0) {
					 unset($tabs['products']);
					 unset($tabs['shortcode']);
				} 
		
				foreach ( $tabs as $slug => $label ) {
					echo '<a href="' . esc_html( admin_url( 'admin.php?page=bmsettings&tab=' . esc_attr( $slug ) ) ) . '" class="nav-tab ' . ( $current_tab === $slug ? 'nav-tab-active' : '' ) . '">' . esc_html( $label ) . '</a>';
				}
				?>
			</nav>
			
			<?php
				settings_fields( 'blindmatrix-settings-group' );
			
				$_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']):'';
			if (isset($_GET['tab']) && 'products' == $_tab || empty($_tab)) {
				?>
				<div class="blinds continer_setting_page">
				<?php if(isset($_REQUEST['settings-updated']) && isset($_GET['page']) && 'bmsettings' == wc_clean(wp_unslash($_GET['page']))): ?>
						    <div id="message" class="updated inline"><p><strong>Your settings have been saved.</strong></p></div>
				<?php
					endif;
					$get_productlist = get_option('productlist', true);
				if (isset($get_productlist->product_list) && is_array($get_productlist->product_list) && count($get_productlist->product_list) > 0) {
					do_settings_sections( 'blindmatrix_blinds_settings_page' );
					do_settings_sections( 'blindmatrix_settings_page' );
				} 
				if (isset($get_productlist->shutter_product_list) && is_array($get_productlist->shutter_product_list) && count($get_productlist->shutter_product_list) > 0) {
					do_settings_sections( 'blindmatrix_shutter_settings_page' );
				}
				if (isset($get_productlist->curtain_product_list) && is_array($get_productlist->curtain_product_list) && count($get_productlist->curtain_product_list) > 0) {
					do_settings_sections( 'blindmatrix_curtain_settings_page' );
				} 
					do_settings_sections( 'blindmatrix_menu_location_settings_page' );
				?>
				</div>
				<?php
			} elseif (isset($_GET['tab']) && 'shortcode' == $_tab) {

				$shortcode_attr = array (
				  array('name'=>'Blinds List','usage'=>'Display the list of Blinds product.','shortcode'=>'[BlindMatrix source="BM-Products" products="Blinds"]'),
				  array('name'=>'Shutters List','usage'=>'Display the list of Shutters product.','shortcode'=>'[BlindMatrix source="BM-Products"  products="Shutters"]'),
				  array('name'=>'Curtains List','usage'=>'Display the list of Curtains product.','shortcode'=>'[BlindMatrix source="BM-Products"  products="Curtains"]'),
				  array('name'=>'All Products List','usage'=>'Display the list of All products.','shortcode'=>'[BlindMatrix source="BM-Products"]'),
				  array('name'=>'Footer Blinds List','usage'=>'Display the list of Blinds products one by one without image or description.<br>This is usually used in the footer menu.','shortcode'=>'[BlindMatrix source="Blinds-List"]'),
				  array('name'=>'Search Module','usage'=>'BM products are found using a search engine.<br>This is usually used in the header menu.','shortcode'=>'[BlindMatrix source="BM-Search"]'),
				  array('name'=>'Free Sample Cart Button','usage'=>'Direct you to Free Sample Cart Page.','shortcode'=>'[BlindMatrix source="freesample-button"]'),
				 
				);
				?>
			<div class="admin_shortcode container">
				<?php if(isset($_REQUEST['settings-updated']) && isset($_GET['page']) && 'bmsettings' == wc_clean(wp_unslash($_GET['page']))): ?>
					<div id="message" class="updated inline"><p><strong>Your settings have been saved.</strong></p></div>
				<?php
				endif;
				$blindmatrix_settings = get_option('option_blindmatrix_settings', true);
				if (!blindmatrix_check_premium()) :
					$img = plugin_dir_url(__FILE__) . 'assets/image/premium-image.jpg';
					$top = !empty($blindmatrix_settings['menu_product_type']) ? '5%':'7%';
					$style = "background-image:url('$img');top:$top;"
					?>
						<div class="blinds-premium-info blindmatrix-upgrade-premium-popup" style="<?php echo wp_kses_post($style); ?>"></div>
					<?php
				endif;
				if (!empty($blindmatrix_settings['menu_product_type'])) {
					?>
			   <div class="shortcode_generator_div">
					<h2 style="font-size: 24px;">Create your own shortcode </h2>
					<form id="generate_shortcode" method="post" >
					<div class="generate_shortcode_container_pre">
						<div class="option_shortcode_generate">
						  <div class="add_products_shortcode_generator shortcode_generator_sub">
								<h3>Add products</h3>
								<ul>
								<?php 
								foreach ($blindmatrix_settings['menu_product_type'] as $key=>$products) {
									?>
									  <li>
										<input  type="checkbox" id="products_<?php echo wp_kses_post($key); ?>" name="create_shortcode_product[]" value="<?php echo wp_kses_post($products); ?>" ><?php echo wp_kses_post($products); ?><br/>
										<label for="products_<?php echo wp_kses_post($key); ?>"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ )); ?>/vendor/Shortcode-Source/image/admin_shortcode_icons/<?php echo wp_kses_post(strtolower($products)); ?>.png" />
									  </li>
									<?php
								}
								?>
								</ul>
							</div>
							 <div class="add_arrguments_shortcode_generator shortcode_generator_sub">
								<h3>Add other details</h3>
								<ul>
								<?php
									$shortcode_arr =array('Title','Price','Description');
								foreach ($shortcode_arr as $keys => $arrgs) {
									?>
									  <li>
										<input checked type="checkbox" class="<?php echo wp_kses_post($arrgs); ?>" id="argg_<?php echo wp_kses_post($keys); ?>" name="create_shortcode_arrgs[]" value="<?php echo wp_kses_post($arrgs); ?>" ><?php echo wp_kses_post($arrgs); ?><br/>
										<label for="argg_<?php echo wp_kses_post($keys); ?>"><img src="<?php echo esc_url(plugin_dir_url( __FILE__ )); ?>/vendor/Shortcode-Source/image/admin_shortcode_icons/<?php echo wp_kses_post(strtolower($arrgs)); ?>.png" />
									  </li>
										<?php
								}
								?>
								</ul>
							 </div>
							 <div class="admin_shortcode generate_shortcode_container shortcode_generator_sub">
								<button class="generate_shortcode_button" type="submit"> Generate Shortcode </button>
								 <div class="display_shortcode" onclick='copyshort("","<?php echo( 'myTooltip_generate' ); ?>")' onmouseout='outFunc("<?php echo( 'myTooltip_generate' ); ?>")'>
									<span class="tooltip" id="<?php echo( 'myTooltip_generate' ); ?>">
										<span class="tooltiptext_generate_shortcode">[/..]</span>
										 <span id="copiedtooltiptext" class="tooltiptext ">Copy to clipboard</span>
									</span>
								</div>
							 </div>
							</div>
							<div class="preview_shortcode_generate">
							<div class="preview_shortcode_generate_header">preview</div>
							<div class="product-container-grid bmcsscn ">
							   <h2 class="divider donotcross">Title</h2>
							   <div class="grid shutter_container">
								  <article class="card-grid__item card-product step-up product type-product post-9433 status-publish instock product_cat-curtains product_cat-curtains-sheer taxable shipping-taxable purchasable product-type-simple">
									 <a href="#" class="card-product__link">
										<div class="card-product__top">
										   <div class="shutter_container card-product__hero lazyload loaded" style="background-image: url(&quot;<?php echo esc_url(plugin_dir_url( __FILE__ )) . '/assets/image/previewShortcode.webp'; ?>&quot;);"></div>
										   <div class="shutter_container card-product__copy">
											  <h3 style="color:white;">XXXX </h3>
										   </div>
										</div>
										<div class="card-product__meta">
										   <div class="na-price" style="display:none;">Pricing unavailable for your window dimensions.</div>
										   <div data-product="price" class="card-product__price">
											  <div> <strong>Price from</strong>
												 <span class="woocommerce-Price-amount amount fontfam">
												 <bdi>
												 <span class="woocommerce-Price-currencySymbol">£</span>
												 XX.XX						</bdi>
												 </span>
											  </div>
											  <div>  
												 <span class="card-product__price-disclaimer">Prices may vary depending on customisations*</span>
											  </div>
										   </div>
										   <div class="shuttertext">
											  <span style="text-align:center;"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's... </span>
										   </div>
										</div>
									 </a>
								  </article>
							   </div>
							</div>
							</div>
						</div>
					</form>
			   </div>
					<?php 
				} 
				?>
			   <table class="table widefat striped" style="margin-bottom:40px;">
				  <tr class="table-header">
					 <th class="header__item">Name</th>
					 <th class="header__item">Usage</th>
					 <th class="header__item">Shortcode</th>
				  </tr>
				  <tbody class="table-content">
				  <?php
					$i =0;
					foreach ($shortcode_attr as $short) {
						?>
					 <tr class="table-row">
						<?php
					
						foreach ($short as $key=>$type) {
							?>
						 <td class="table-data">
							<?php if ('shortcode' == $key) { ?>
								<span> <?php echo wp_kses_post($type); ?></span>
								<span class="copy_cn">
									<div class="tooltip">
										<button onclick='copyshort(`<?php echo wp_kses_post($type); ?>`,"<?php echo wp_kses_post('myTooltip' . $i); ?>")' onmouseout='outFunc("<?php echo wp_kses_post('myTooltip' . $i); ?>")'>
											<span class="tooltiptext" id="myTooltip<?php echo wp_kses_post($i); ?>">
												Copy to clipboard
											</span>
											Copy 
										</button>
									</div>
								</span>
							<?php } else { ?>
								 <?php echo wp_kses_post($type); ?>
							<?php } ?>
						 </td>
							<?php	
							$i++;					
						}
						?>
					 </tr>
						<?php
					}
					?>
				  </tbody>
			   </table>
			</div>
				<?php
				$_tab = isset($_GET['tab']) ? sanitize_title($_GET['tab']):'';
				if (isset($_GET['tab']) && 'shortcode' == $_tab) {
					?>
					<form method="post" action="options.php">
					
					<?php
					settings_fields( 'blindmatrix-settings-group' );
					do_settings_sections( 'blindmatrix_bm_settings_page' );
				}
			}
			
			$display = false;
			if(!isset($_GET['tab'])){
				$display = true;
			}
		
			if(isset( $_GET['tab']) && '' != $_GET['tab']){
				$display = true;
			}   
		
			if(isset($_GET['tab'] ) && 'go_premium' == $_GET['tab']){
				$display = false;
			}

			if ($display) {
				echo '<div style="margin-top:30px;">';
				echo '<input type="submit" name="submit" id="submit" class="button button-primary bm-submit" value="Publish" style="margin-right:15px;padding:4px 20px;font-weight:600;">';
				if (!blindmatrix_check_premium()) {
					echo '<a href="#" class="button upgrade-premium-button blindmatrix-upgrade-premium-popup"  style="background:#ffb818!important;color:#fff;padding:5px 20px;border-style:none;font-weight:600;">Upgrade Premium</a>';
				}
				echo '</div>';
				?>
				<?php
			}
			?>
			</form>
		</div>
		
		<?php
		$return_msg = '';
	if (isset($_REQUEST['purchase_code_activation'])) {
		$return_msg = purchase_code_activation_callback();
	}
			if(isset($_GET['tab']) && 'go_premium' == $_GET['tab']){
				?>
				<div style="" class="blind_admin_blocks_container">
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
									<?php 
									print_r($return_msg['msg']); 
									if ($return_msg['status'] && 'success' == $return_msg['status']) {
										wp_safe_redirect(admin_url('admin.php?page=bm'));
										exit();
									}
									?>
								</div>
								<?php } ?>
							</form>
						</div>
					</div>		
				<?php
			}
		
		$this->create_menus_on_save();
	}
	
	public function create_menus_on_save() {
		$stored_settings = get_option('option_blindmatrix_settings');
		if (!isset($_REQUEST['settings-updated'])) {
			return;
		}
			$stored_settings = get_option('option_blindmatrix_settings');
		if (empty($stored_settings) ) {
			return;
		}
		$_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']):'';	
		$_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']):'';
		if ( !isset($_GET['page']) || 'bmsettings' != $_page || ( isset( $_GET['tab'] ) && 'products' != $_tab )) {
			return;
		}
	
			bm_create_menu_items();
	}
	/**
	 * Register and add settings
	 */
	public function page_init() {
	
		$blindmatrix_settings = get_option( 'option_blindmatrix_settings', true );
		/*  if(isset($blindmatrix_settings['menu_product_type']) && in_array( 'Blinds' , $blindmatrix_settings['menu_product_type'])){
		$visibleBlinds = 'hide_while_disabled';
		}else{
		$visibleBlinds ='hide_while_disabled hide';
		}
		if(isset($blindmatrix_settings['menu_product_type']) && in_array( 'Shutters' , $blindmatrix_settings['menu_product_type'])){
		$visibleShutters ='hide_while_disabled';
		}else{
		$visibleShutters ='hide_while_disabled hide';
		}
		if(isset($blindmatrix_settings['menu_product_type']) && in_array( 'Curtains' , $blindmatrix_settings['menu_product_type'])){
		$visibleCurtains ='hide_while_disabled';
		}else{
		$visibleCurtains ='hide_while_disabled hide';
		} */
		$visibleBlinds ='';
		$visibleShutters = '';
		$visibleCurtains  = '';
		register_setting(
			'blindmatrix-settings-group', // Option group
			'option_blindmatrix_settings', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'setting_blinds_page_section_id',
			'Blinds', 
			array( $this, 'print_page_section_info' ), 
			'blindmatrix_blinds_settings_page',
			array( 'section_class' => 'section_heading' )
		);  

		add_settings_field(
			'check_blinds', 
			'Enable Blinds Product',
			array( $this, 'check_blinds_callback' ), 
			'blindmatrix_blinds_settings_page',
			'setting_blinds_page_section_id'            
		); 
		
		add_settings_field(
			'product_page', 
			'Blinds List Page Slug<span class="required">*</span>',
			array( $this, 'product_page_callback' ), 
			'blindmatrix_blinds_settings_page',
			'setting_blinds_page_section_id',
			array( 'class' => $visibleBlinds . ' trail_none'  )	            
		); 
		
		add_settings_field(
			'blinds_config', // ID
			'Blinds Visualizer Page Slug<span class="required">*</span>', // Title 
			array( $this, 'blinds_config_callback' ), // Callback
			'blindmatrix_blinds_settings_page', // Page
			'setting_blinds_page_section_id', // Section 
			array( 'class' => $visibleBlinds . ' trail_none'  )				
		); 
		
		 add_settings_section(
			'setting_page_shutter_section_id', // ID
			'Shutters', // Title
			array( $this, 'print_page_section_info' ), // Callback
			'blindmatrix_shutter_settings_page' // Page
		); 
		add_settings_field(
			'check_shutter', 
			'Enable Shutters Product',
			array( $this, 'check_shutter_callback' ), 
			'blindmatrix_shutter_settings_page',
			'setting_page_shutter_section_id'            
		); 
		add_settings_field(
			'shutters_type_page', // ID
			'Shutters Landing Page Slug<span class="required">*</span>', // Title 
			array( $this, 'shutters_type_page_callback' ), // Callback
			'blindmatrix_shutter_settings_page', // Page
			'setting_page_shutter_section_id',
			array( 'class' => $visibleShutters . ' trail_none'  )      
		); 
		add_settings_field(
			'shutters_page', // ID
			'Shutters Single Product Page Slug<span class="required">*</span>', // Title 
			array( $this, 'shutters_page_callback' ), // Callback
			'blindmatrix_shutter_settings_page', // Page
			'setting_page_shutter_section_id',
			array( 'class' => $visibleShutters . ' trail_none'  )       
		); 
		
		add_settings_field(
			'shutter_visualizer_page', // ID
			'Shutters Visualizer Page Slug<span class="required">*</span>', // Title 
			array( $this, 'shutter_visualizer_page_callback' ), // Callback
			'blindmatrix_shutter_settings_page', // Page
			'setting_page_shutter_section_id',
			array( 'class' =>$visibleShutters . ' trail_none'  )         
		); 
		
		 add_settings_section(
			'setting_page_curtain_section_id', // ID
			'Curtains', // Title
			array( $this, 'print_page_section_info' ), // Callback
			'blindmatrix_curtain_settings_page' // Page
		); 
		add_settings_field(
			'check_curtain', 
			'Enable Curtains Product',
			array( $this, 'check_curtain_callback' ), 
			'blindmatrix_curtain_settings_page',
			'setting_page_curtain_section_id'            
		); 
		
		 add_settings_field(
			'curtains_config', // ID
			'Curtains Visualizer Page Slug<span class="required">*</span>', // Title 
			array( $this, 'curtains_config_page_callback' ), // Callback
			'blindmatrix_curtain_settings_page', // Page
			'setting_page_curtain_section_id',
			array( 'class' => $visibleCurtains . ' trail_none'  )        
		);
		
		 add_settings_field(
			'curtains_single', // ID
			'Curtains Single Product Page Slug<span class="required">*</span>', // Title 
			array( $this, 'curtains_single_page_callback' ), // Callback
			'blindmatrix_curtain_settings_page', // Page
			'setting_page_curtain_section_id',
			array( 'class' => $visibleCurtains . ' trail_none'  )         
		);
		
		add_settings_section(
			'setting_page_menu_location_section_id', // ID
			'Menu Locations', // Title
			array( $this, 'print_page_section_info' ), // Callback
			'blindmatrix_menu_location_settings_page' // Page
		); 
		add_settings_field(
			'enable_menu_locations', 
			'Menu Locations<span class="required">*</span>', 
			array( $this, 'enable_menu_locations_callback' ), // Callback
			'blindmatrix_menu_location_settings_page', // Page
			'setting_page_menu_location_section_id' // Section           
		);
		 add_settings_section(
			'advanced', // ID
			'Advanced Settings', // Title
			array( $this, 'print_page_section_info' ), // Callback
			'blindmatrix_bm_settings_page' // Page
		);
		 add_settings_field(
			'bm_primary_color', // ID
			'Primary Color', // Title 
			array( $this, 'bm_primary_color_callback' ), // Callback
			'blindmatrix_bm_settings_page', // Page
			'advanced' // Section           
		);

        add_settings_field(
			'vatoption', // ID
			'VAT', // Title 
			array( $this, 'bm_vat_option_callback' ), // Callback
			'blindmatrix_bm_settings_page', // Page
			'advanced' // Section           
		);
        

		$blindmatrix_settings = get_option( 'option_blindmatrix_settings', true );
		if (isset($blindmatrix_settings['menu_product_type']) && in_array( 'Blinds' , $blindmatrix_settings['menu_product_type'])) {
			 add_settings_field(
				'blinds_archive_layout', // ID
				'Blinds Archive Layout', // Title 
				array( $this, 'blinds_archive_layout_callback' ), // Callback
				'blindmatrix_bm_settings_page', // Page
				'advanced' // Section           
			);
		}
		
		add_settings_field(
			'bm_reset_menu_items', // ID
			'Reset Menus', // Title 
			array( $this, 'bm_reset_menu_callback' ), // Callback
			'blindmatrix_bm_settings_page', // Page
			'advanced' // Section           
		);
		
		/* add_settings_section(
			'setting_section_id', // ID
			'Seasonal Image', // Title
			array( $this, 'print_section_info' ), // Callback
			'blindmatrix_settings_page' // Page
		);  


		add_settings_field(
			'seasonal_image_check', // ID
			'Enable/Disable', // Title 
			array( $this, 'check_seasonal_image_callback' ), // Callback
			'blindmatrix_settings_page', // Page
			'setting_section_id' // Section           
		);   
		
		add_settings_field(
			'seasonal_image_img', // ID
			'Add/Update Image', // Title 
			array( $this, 'seasonal_image_callback' ), // Callback
			'blindmatrix_settings_page', // Page
			'setting_section_id' // Section           
		);  */

		if (current_theme_supports( 'menus' ) && empty(wp_get_nav_menus())) {
			$created_menu_id = wp_create_nav_menu('Home');
			update_option('bm_created_menu_id', $created_menu_id);
		}
		$tab = isset($_REQUEST['tab']) ? wc_clean(wp_unslash($_REQUEST['tab'])):'';
		$submit = isset($_REQUEST['submit']) ? wc_clean(wp_unslash($_REQUEST['submit'])):'';
		if (isset($_REQUEST['tab'] , $_REQUEST['submit'] ) && 'blindmatrix_advanced' == $tab && $submit) {
			$this->msg_data = blindmatrix_save_menu_items($_REQUEST);
		}
	}

	public function update_slug($postid , $slug ){
		$post_up = wp_update_post( array(
			'ID' => $postid,
			'post_name' => sanitize_text_field( $slug ) 
		));
		return $post_up;
	}
	public function sanitize( $input ){
		$option_blindmatrix_settings = get_option( 'option_blindmatrix_settings' );
		$new_input = array();
		
			if( isset( $input['seasonal_image_check'] ) ){
				$option_blindmatrix_settings['seasonal_image_check'] = sanitize_text_field( $input['seasonal_image_check'] );
			}else{
				$option_blindmatrix_settings['seasonal_image_check'] ='';
			}	
			
			if( isset( $input['seasonal_image_img'] ) ){
				$option_blindmatrix_settings['seasonal_image_img'] = sanitize_text_field( $input['seasonal_image_img'] );
			}
			$request = isset($_REQUEST["option_blindmatrix_settings"]) ? wc_clean(wp_unslash($_REQUEST["option_blindmatrix_settings"])) :array();
			if( isset( $input['blindslistproid'] ) ){
				if(isset($request['blindslistproid'])){
					if(blindmatrix_check_premium()){
						$option_blindmatrix_settings['blindslistproid']= $request['blindslistproid'];
					}else{
						if(is_array($request['blindslistproid']) &&  count($request['blindslistproid']) <= 3){
							$option_blindmatrix_settings['blindslistproid']= $request['blindslistproid'];
						}
					}
				}else{
					$option_blindmatrix_settings['blindslistproid'] = array();
				}
			}
			if( isset( $input['shutterlistproid'] ) ){
				if(isset($request['shutterlistproid'])){
					if(blindmatrix_check_premium()){
							$option_blindmatrix_settings['shutterlistproid']= $request['shutterlistproid'];
					}else{
						if(is_array($request['shutterlistproid']) &&  count($request['shutterlistproid']) <= 2){
							$option_blindmatrix_settings['shutterlistproid']= $request['shutterlistproid'];
						}
					}
				}else{
					$option_blindmatrix_settings['shutterlistproid'] = array();
				}
			}
			if( isset( $input['curtainlistproid'] ) ){
				if(isset($request['curtainlistproid'])){
					if(blindmatrix_check_premium()){
							$option_blindmatrix_settings['curtainlistproid']= $request['curtainlistproid'];
					}else{
						if(is_array($request['curtainlistproid']) &&  count($request['curtainlistproid']) <= 2){
							$option_blindmatrix_settings['curtainlistproid']= $request['curtainlistproid'];
						}
					}
				}else{
					$option_blindmatrix_settings['curtainlistproid'] = array();
				}
			}


            if( isset( $input['vatoption'] ))
            {
				if(blindmatrix_check_premium()){
					$vatoption = $input['vatoption'];
				}else{
					$vatoption ='2';
				}
				$option_blindmatrix_settings['vatoption'] = sanitize_text_field( $vatoption );
			}
            
          

			if( isset( $input['bm_primary_color'] ) ){
				if(blindmatrix_check_premium()){
					$bm_primary_color = $input['bm_primary_color'];
				}else{
					$bm_primary_color ='#00c2ff';
				}
				$option_blindmatrix_settings['bm_primary_color'] = sanitize_text_field( $bm_primary_color );
			}
			if( isset( $input['blinds_archive_layout'] ) ){
				if(blindmatrix_check_premium()){
					$blinds_archive_layout = $input['blinds_archive_layout'];
				}else{
					$blinds_archive_layout ='4';
				}
				$option_blindmatrix_settings['blinds_archive_layout'] = sanitize_text_field( $blinds_archive_layout );
			}
			if( isset( $input['product_page'] ) ){
				if(blindmatrix_check_premium()){
					$blinds_list = $input['product_page'];
				}else{
					$blinds_list ='blinds-list';
				}
				$page_id = get_option("blinds_list");
				$page_up = $this->update_slug($page_id,$blinds_list);
				if ( is_wp_error( $page_up ) ) {
					 echo $page_up->get_error_message();
				}else{
					$option_blindmatrix_settings['product_page'] = sanitize_text_field( $blinds_list );
				}
			}
			if( isset( $input['blinds_config'] ) ){
				if(blindmatrix_check_premium()){
					$blinds_config = $input['blinds_config'];
				}else{
					$blinds_config ='blinds-config';
				}
				$page_id = get_option("blinds_config");
				$page_up = $this->update_slug($page_id,$blinds_config);
				if ( is_wp_error( $page_up ) ) {
					 echo wp_kses_post($page_up->get_error_message());
				}else{
					$option_blindmatrix_settings['blinds_config'] = sanitize_text_field( $blinds_config );
				}
			}
			if( isset( $input['shutters_type_page'] ) ){
				if(blindmatrix_check_premium()){
					$shutters_type_page = $input['shutters_type_page'];
				}else{
					$shutters_type_page ='shutter-type';
				}
				$page_id = get_option("shutter_type");
				$page_up = $this->update_slug($page_id,$shutters_type_page);
				if ( is_wp_error( $page_up ) ) {
					 echo wp_kses_post($page_up->get_error_message());
				}else{
					$option_blindmatrix_settings['shutters_type_page'] = sanitize_text_field( $shutters_type_page );
				}
			}
			if( isset( $input['shutters_page'] ) ){
				if(blindmatrix_check_premium()){
					$shutters_page = $input['shutters_page'];
				}else{
					$shutters_page ='shutter-single-type';
				}
				$page_id = get_option("shutter_single_type");
				$page_up = $this->update_slug($page_id,$shutters_page);
				if ( is_wp_error( $page_up ) ) {
					 echo wp_kses_post($page_up->get_error_message());
				}else{
					$option_blindmatrix_settings['shutters_page'] = sanitize_text_field( $shutters_page );
				}
			}
			if( isset( $input['shutter_visualizer_page'] ) ){
				if(blindmatrix_check_premium()){
					$shutter_visualizer_page = $input['shutter_visualizer_page'];
				}else{
					$shutter_visualizer_page ='shutter-visualizer';
				}
				$page_id = get_option("shutter_config");
				$page_up = $this->update_slug($page_id,$shutter_visualizer_page);
				if ( is_wp_error( $page_up ) ) {
					 echo wp_kses_post($page_up->get_error_message());
				}else{
					$option_blindmatrix_settings['shutter_visualizer_page'] = sanitize_text_field( $shutter_visualizer_page );
				}
			}
		
			if( isset( $input['curtains_single_page'] ) ){
				if(blindmatrix_check_premium()){
					$curtains_single_page = $input['curtains_single_page'];
				}else{
					$curtains_single_page ='curtain-single';
				}
				$page_id = get_option("curtains_single");
				$page_up = $this->update_slug($page_id,$shutter_visualizer_page);
				if ( is_wp_error( $page_up ) ) {
					 echo wp_kses_post($page_up->get_error_message());
				}else{
					$option_blindmatrix_settings['curtains_single_page'] = sanitize_text_field( $shutter_visualizer_page );
				}
			}
			if( isset( $input['curtains_config'] ) ){
				if(blindmatrix_check_premium()){
					$curtains_config = $input['curtains_config'];
				}else{
					$curtains_config ='curtain-config';
				}
				$page_id = get_option("curtain_config");
				$page_up = $this->update_slug($page_id,$curtains_config);
				if ( is_wp_error( $page_up ) ) {
					 echo wp_kses_post($page_up->get_error_message());
				}else{
					$option_blindmatrix_settings['curtains_config'] = sanitize_text_field( $curtains_config );
				}
			}
		
	

		if( isset( $input['menu_product_type'] ) ){
	
			if(empty($option_blindmatrix_settings['menu_product_type'])){
				$option_blindmatrix_settings['menu_product_type']=array();
			}
			$product_options_unq =array_unique($input['menu_product_type']);
			
			$option_blindmatrix_settings['menu_product_type'] = $product_options_unq;
			
		} else{
			 if( !isset($request['bm_primary_color']) ){
				 $option_blindmatrix_settings['menu_product_type'] =array();
			}
		}
		$request = isset( $_REQUEST["option_blindmatrix_settings"]) ? wc_clean(wp_unslash($_REQUEST["option_blindmatrix_settings"])):array();
		if(isset($input['menu_location_name'])){
			if(isset($request['menu_location_name'])){
					$option_blindmatrix_settings['menu_location_name'] = $request['menu_location_name'];
			}else{
					$option_blindmatrix_settings['menu_location_name'] = array();
			}
			
			if(isset($request['menu_location_id'])){
				$option_blindmatrix_settings['menu_location_id']= $request['menu_location_id'];
			}else{
					$option_blindmatrix_settings['menu_location_id'] = array();
			}
		}
		if( isset( $input['bmdirectory_email_heading'] ) ){
			$option_blindmatrix_settings['bmdirectory_email_heading'] = sanitize_text_field( $input['bmdirectory_email_heading'] );
		}
		if( isset( $input['bmdirectory_email_body'] ) ){
			
			$option_blindmatrix_settings['bmdirectory_email_body'] = wp_kses( $input['bmdirectory_email_body'] , true );
		}
		
		
		
		return $option_blindmatrix_settings;
	}

	/** 
	 * Print the Section text
	 */
	public function print_section_info()
	{
		print 'Click here if you need the view the Seasonal Image near your blinds list page';
	}
	/** 
	 * Print Page Section Info.
	 */
	public function print_page_section_info(){
		$_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']):''; 
		if(!blindmatrix_check_premium() && isset($_GET['tab']) && in_array($_tab,array('advanced','shutters','curtains'))){
			$img = plugin_dir_url(__FILE__) . 'assets/image/premium-image.jpg';
			$style = "background-image:url('$img');top:7%;";
			echo wp_kses_post(sprintf("<div class='blinds-premium-info blindmatrix-upgrade-premium-popup' style='%s'></div>",$style));
		}else{
			print '';
		}
	}
	/** 
	 * Get the settings option array and print one of its values
	 */

	public function check_seasonal_image_callback()
	{
		printf(
			'<input %s type="checkbox" id="seasonal_image_check" name="option_blindmatrix_settings[seasonal_image_check]" value="checked" />',
			isset( $this->options['seasonal_image_check'] ) ? esc_attr( $this->options['seasonal_image_check']) : ''
		);
	  
	}
	
	public function seasonal_image_callback(){
		$image_id = isset( $this->options['seasonal_image_img'] ) ? esc_attr( $this->options['seasonal_image_img']) : '';
		if( $image = wp_get_attachment_image_src( $image_id ,'full' ) ) {
		 
			echo '<a href="#" class="seasonal_image_upl"><img style="max-width:300px;max-height:200px;" src="' . $image[0] . '" /></a>
				  <a href="#" class="seasonal_image_rmv">Remove image</a>
				  <input type="hidden" id="seasonal_image_img" name="option_blindmatrix_settings[seasonal_image_img]" value="' . $image_id . '">';
		 
		} else {
		 
			echo '<a href="#" class="seasonal_image_upl button">Upload image</a>
				  <a href="#" class="seasonal_image_rmv" style="display:none">Remove image</a>
				  <input type="hidden"id="seasonal_image_img"  name="option_blindmatrix_settings[seasonal_image_img]" value="">';
		 
		}
	}
	public function check_blinds_callback(){
	ob_start();
	$get_productlist = get_option('productlist', true);
	$blindmatrix_settings = get_option( 'option_blindmatrix_settings',true );
	if(isset($blindmatrix_settings['menu_product_type']) && in_array( 'Blinds' , $blindmatrix_settings['menu_product_type'])){
		$visibleBlindssub = '';
	}else{
		$visibleBlindssub ='style="display:none;"';
	} 
	?>
	<label style="display: inline-block;" class="switch">
		<?php
		if (isset($blindmatrix_settings['menu_product_type']) && in_array( 'Blinds' , $blindmatrix_settings['menu_product_type'])) {
			$selec = 'Enabled';
		} else {
			$selec = 'Disabled';
		}
		?>
	  <input type="checkbox" class="enable_products_bm" id="menu_product_type_1" name="option_blindmatrix_settings[menu_product_type][]" value="Blinds" <?php echo( checked($this->check_inarray('Blinds'), 1, false) ); ?>/>
	  <span class="bm_slider round"></span>
	</label>
	<label style="display: inline-block;" class="switch_label"><?php echo wp_kses_post($selec); ?></label>
		<?php if (!blindmatrix_check_premium()) { ?>
		<p class="bm-notice blinds" <?php echo wp_kses_post($visibleBlindssub); ?> >Note: Only three products can be selected for free trial.</p>
	<?php } ?>
	<ul class="menu blinds_list_settings bmcsscn" <?php echo wp_kses_post($visibleBlindssub); ?>>
		<?php if (count($get_productlist->product_list) > 0) : ?>
			<?php foreach ($get_productlist->product_list as $key=>$product_list) : ?>
				<?php
				$productname_arr = explode('(', $product_list->productname);
				$get_productname = trim($productname_arr[0]);
				?>
		<li class="menu-item menu-item-type-post_type menu-item-object-page">
	<input onclick="listblind_settings(<?php echo wp_kses_post($key); ?>)" 
												  <?php 
													if (isset( $this->options['blindslistproid']) && array_key_exists($product_list->productid, $this->options['blindslistproid'])) :
														?>
		 checked="checked"<?php endif; ?>  type="checkbox" class="blindslistcheck 
				<?php 
				if (!blindmatrix_check_premium()) {
						echo( 'list_settings_productname' ); } 
				?>
" id="<?php echo wp_kses_post('pid_' . $product_list->productid); ?>" name="option_blindmatrix_settings[blindslistproid][<?php echo wp_kses_post($product_list->productid); ?>]">
			<label for="<?php echo wp_kses_post('pid_' . $product_list->productid); ?>"><?php echo wp_kses_post($get_productname); ?></label>
		</li>
		<?php endforeach; ?>
		<?php endif; ?>
	</ul>
		<?php
		$output = ob_get_contents();
		ob_end_flush();
	
		$html = '' . $output;
		//$html = '<input checked type="checkbox" id="menu_product_type_1" name="option_blindmatrix_settings[menu_product_type][0]" value="Blinds"/>'.$output;
	}
	public function check_shutter_callback() {
		ob_start();
		$get_productlist = get_option('productlist', true);
		$blindmatrix_settings = get_option( 'option_blindmatrix_settings', true );
		if (isset($blindmatrix_settings['menu_product_type']) && in_array( 'Shutters' , $blindmatrix_settings['menu_product_type'])) {
			$visibleShutterssub = '';
		} else {
			$visibleShutterssub ='style="display:none;"';
		} 
		
		if (isset($blindmatrix_settings['menu_product_type']) && in_array( 'Shutters' , $blindmatrix_settings['menu_product_type'])) {
			$selec = 'Enabled';
		} else {
			$selec = 'Disabled';
		}
		?>
		<label style="display: inline-block;" class="switch">
		  <input type="checkbox" class="enable_products_bm"  id="menu_product_type_2" name="option_blindmatrix_settings[menu_product_type][]" value="Shutters" <?php echo wp_kses_post(checked($this->check_inarray('Shutters'), 1, false)); ?>/>
		  <span class="bm_slider round"></span>
		</label>
		<label style="display: inline-block;" class="switch_label"><?php echo wp_kses_post($selec); ?></label>
		<?php if (!blindmatrix_check_premium()) { ?>
			<p class="bm-notice shutter"  <?php echo wp_kses_post($visibleShutterssub); ?>>Note: Only two products can be selected for free trial.</p>
		<?php } ?>
		<ul class="menu shutter_list_settings bmcsscn"  <?php echo wp_kses_post($visibleShutterssub); ?>>
			<?php if (count($get_productlist->shutter_product_list) > 0) : ?>
				<?php foreach ($get_productlist->shutter_product_list as $shutter_product_list) : ?>
					<?php if (count($shutter_product_list->GetShutterProductTypeList) > 0) : ?>
						<?php foreach ($shutter_product_list->GetShutterProductTypeList as $key=>$GetShutterProductTypeList) : ?>
				<li class="menu-item menu-item-type-post_type menu-item-object-page">
					<input onclick="listshuther_settings(<?php echo wp_kses_post($key); ?>)" 
																	<?php 
																	if (isset( $this->options['shutterlistproid']) && array_key_exists($GetShutterProductTypeList->parameterTypeId, $this->options['shutterlistproid'])) :
																		?>
						 checked="checked"<?php endif; ?>  type="checkbox" class="blindslistcheck 
							<?php 
							if (!blindmatrix_check_premium()) {
													echo( 'list_shutter_settings_productname' ); } 
							?>
" id="<?php echo wp_kses_post('pid_' . $GetShutterProductTypeList->parameterTypeId); ?>" name="option_blindmatrix_settings[shutterlistproid][<?php echo wp_kses_post($GetShutterProductTypeList->parameterTypeId); ?>]">
					<label for="<?php echo wp_kses_post('pid_' . $GetShutterProductTypeList->parameterTypeId); ?>"><?php echo wp_kses_post($GetShutterProductTypeList->productTypeSubName); ?></label>
				</li>
				<?php endforeach; ?>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php endif; ?>
		</ul>
		<?php
		$output = ob_get_contents();
		ob_end_flush();
	}
	public function check_curtain_callback() {
				ob_start();
		$get_productlist = get_option('productlist', true);
		$blindmatrix_settings = get_option( 'option_blindmatrix_settings', true );
		$visibleCurtainssub = '';
		if (isset($blindmatrix_settings['menu_product_type']) && in_array( 'Curtains' , $blindmatrix_settings['menu_product_type'])) {
			$visibleCurtainsssub = '';
		} else {
			$visibleCurtainssub ='style="display:none;"';
		} 
		if (isset($blindmatrix_settings['menu_product_type']) && in_array( 'Curtains' , $blindmatrix_settings['menu_product_type'])) {
			$selec = 'Enabled';
		} else {
			$selec = 'Disabled';
		}
		?>
		<label style="display: inline-block;" class="switch">
		  <input type="checkbox" class="enable_products_bm" id="menu_product_type_3" name="option_blindmatrix_settings[menu_product_type][]" value="Curtains" <?php echo( checked($this->check_inarray('Curtains'), 1, false) ); ?>/>
		  <span class="bm_slider round"></span>
		</label>
		<label style="display: inline-block;" class="switch_label"><?php echo wp_kses_post($selec); ?></label>
		<?php if (!blindmatrix_check_premium()) { ?>
			<p class="bm-notice curtian" <?php echo wp_kses_post($visibleCurtainssub); ?> >Note: Only two products can be selected for free trial.</p>
		<?php } ?>
		<ul class="menu curtian_list_settings bmcsscn" <?php echo wp_kses_post($visibleCurtainssub); ?>>
			<?php 
			$curtains = array(
					'pencil-pleat' => 'Pencil Pleat',
					'eyelet' => 'Eyelet',
					'goblet' => 'Goblet',
					'goblet-buttoned' => 'Goblet Buttoned', 
					'double-pinch'  => 'Double Pinch',
					'double-pinch-buttoned' => 'Double Pinch Buttoned',
					'triple-pinch'   => 'Triple Pinch',
					'triple-pinch-buttoned'   => 'Triple Pinch Buttoned'
				);
			if (count($get_productlist->curtain_product_list) > 0) :
				$i = 0;
				foreach ($curtains as $key=>$curtain) : 
					?>
				<li class="menu-item menu-item-type-post_type menu-item-object-page">
					<input onclick="listcurtain_settings(<?php echo wp_kses_post($i++); ?>)" 
																	<?php 
																	if (isset( $this->options['curtainlistproid']) && array_key_exists($key, $this->options['curtainlistproid'])) :
																		?>
						 checked="checked"<?php endif; ?>  type="checkbox" class="blindslistcheck 
						<?php 
						if (!blindmatrix_check_premium()) {
												echo( 'list_curtain_settings_productname' ); } 
						?>
" id="<?php echo wp_kses_post('pid_' . $key); ?>" name="option_blindmatrix_settings[curtainlistproid][<?php echo wp_kses_post($key); ?>]">
					<label for="<?php echo wp_kses_post('pid_' . $key); ?>"><?php echo wp_kses_post($curtain); ?></label>
				</li>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>
		<?php
		$output = ob_get_contents();
		ob_end_flush();
		
		//$html = '<input  style="display:none;" checked type="checkbox" id="menu_product_type_3" name="option_blindmatrix_settings[menu_product_type][2]" value="Curtains"/>'.$output;
	}
	public function product_page_callback() {
		  printf(
			'<input required  type="text"  id="product_page" name="option_blindmatrix_settings[product_page]" %s value="%s" />
			%s',
			wp_kses_post(blindmatrix_check_premium())? '' : 'disabled ',
			wp_kses_post(isset( $this->options['product_page'] ) && !empty( $this->options['product_page'] )  ? esc_attr( $this->options['product_page']) : 'blinds-list'),
			wp_kses_post(blindmatrix_check_premium())? '' : '<p class="bm-notice">Note: Available in premium version.</p>'
		
		);
	}
	
	public function check_inarray( $pro) {
		if (isset($this->options['menu_product_type']) && is_array($this->options['menu_product_type'])) {
			$checkarray = in_array( $pro, $this->options['menu_product_type'] );
		} else {
			$checkarray = false;
		}
		return $checkarray;
	}
	public function blinds_config_callback() {
		  printf(
			'<input required type="text" id="blinds_config" name="option_blindmatrix_settings[blinds_config]" %s value="%s"  />
			%s',
			wp_kses_post(blindmatrix_check_premium())? '' : 'disabled ',
			wp_kses_post(isset( $this->options['blinds_config'] ) && !empty( $this->options['blinds_config'] )  ? esc_attr( $this->options['blinds_config']) : 'blinds-config'),
			wp_kses_post(blindmatrix_check_premium())? '' : '<p class="bm-notice">Note: Available in premium version.</p> '
		);
	}
	public function shutters_page_callback() {
		  printf(
			'<input required type="text" id="shutters_page" name="option_blindmatrix_settings[shutters_page]" %s value="%s"   />
			%s',
			wp_kses_post(blindmatrix_check_premium())? '' : 'disabled ',
			wp_kses_post(isset( $this->options['shutters_page'] ) && !empty( $this->options['shutters_page'] )  ? esc_attr( $this->options['shutters_page']) : 'shutter-single-type'),
			wp_kses_post(blindmatrix_check_premium())? '' : '<p class="bm-notice">Note: Available in premium version.</p> '
		);
	}
	public function shutters_type_page_callback() {
		  printf(
			'<input required type="text" id="shutters_type_page" name="option_blindmatrix_settings[shutters_type_page]" %s value="%s"  />
			%s',
			wp_kses_post(blindmatrix_check_premium())? '' : 'disabled ',
			wp_kses_post(isset( $this->options['shutters_type_page'] ) && !empty( $this->options['shutters_type_page'] )  ? esc_attr( $this->options['shutters_type_page']) : 'shutter-type'),
			wp_kses_post(blindmatrix_check_premium())? '' : '<p class="bm-notice">Note: Available in premium version.</p> '
		);
	}
	public function shutter_visualizer_page_callback() {
		  printf(
			'<input required type="text" id="shutter_visualizer_page" name="option_blindmatrix_settings[shutter_visualizer_page]" %s value="%s"  />
			%s',
			wp_kses_post(blindmatrix_check_premium())? '' : 'disabled ',
			wp_kses_post(isset( $this->options['shutter_visualizer_page'] ) && !empty( $this->options['shutter_visualizer_page'] )  ? esc_attr( $this->options['shutter_visualizer_page']) : 'shutter-visualizer'),
			wp_kses_post(blindmatrix_check_premium())? '' : '<p class="bm-notice">Note: Available in premium version.</p> '
		);
	}
	
	public function curtains_single_page_callback() {
		  printf(
			'<input required type="text" id="curtains_single_page" name="option_blindmatrix_settings[curtains_single_page]"  %s value="%s"  />
			%s',
			wp_kses_post(blindmatrix_check_premium())? '' : 'disabled ',
			wp_kses_post(isset( $this->options['curtains_single_page'] ) && !empty( $this->options['curtains_single_page'] )  ? esc_attr( $this->options['curtains_single_page']) : 'curtain-single'),
			wp_kses_post(blindmatrix_check_premium())? '' : '<p class="bm-notice">Note: Available in premium version.</p> '
		);
	}
	public function curtains_config_page_callback() {
		  printf(
			'<input required type="text" id="curtains_config" name="option_blindmatrix_settings[curtains_config]" %s value="%s"  />
			%s',
			wp_kses_post(blindmatrix_check_premium())? '' : 'disabled ',
			wp_kses_post(isset( $this->options['curtains_config'] ) && !empty( $this->options['curtains_config'] )  ? esc_attr( $this->options['curtains_config']) : 'curtain-config'),
			wp_kses_post(blindmatrix_check_premium())? '' : '<p class="bm-notice">Note: Available in premium version.</p> '
		);
	}
	public function bm_primary_color_callback() {
		printf(
			( '<div class="viewpremiumpop  %s"></div><input type="text" id="bm_primary_color" class="bmcolorpickers" data-default-color="#00c2ff" name="option_blindmatrix_settings[bm_primary_color]" value="%s"  />' ),
			wp_kses_post(blindmatrix_check_premium())? '' : 'true',
			wp_kses_post(isset( $this->options['bm_primary_color'] ) && !empty( $this->options['bm_primary_color'] )  ? esc_attr( $this->options['bm_primary_color']) : '#00c2ff')
		);
	}
   

    public function bm_vat_option_callback() {
		printf
			('<table>
			<tbody>
				<tr>
                  <td>
                    <input type="radio" id="excludedVAT" name="option_blindmatrix_settings[vatoption]" %s value="2">
                    <label for="excludedVAT">Excluded VAT</label>
                  </td>	
			      <td>
			         <input type="radio" id="includedVAT" name="option_blindmatrix_settings[vatoption]" %s value="1">
			         <label for="includedVAT">Included VAT</label>
		          </td>
				</tr>
			</tbody>
		</table>',
            ( isset($this->options['vatoption']) && '2' == $this->options['vatoption'] ? 'checked' : '' ),
			( isset($this->options['vatoption']) && '1' == $this->options['vatoption']  ? 'checked' : '' ));
	}

	public function blinds_archive_layout_callback() {
			   $img_dir= plugin_dir_url( __FILE__ ) . '/vendor/Shortcode-Source/image/admin_settings/';
				printf('<div class="shortcode_generator_div"> 
							<div class="add_products_shortcode_generator shortcode_generator_sub">
								 <ul>
									<li class="advance_setting_img %s">
										<input  type="radio" id="layout_2" name="option_blindmatrix_settings[blinds_archive_layout]" %s value="2" >Two column layout<br/>
										<label for="layout_2"><img src="' . esc_url($img_dir) . '/layout-2.png" />
									</li>
									<li class="advance_setting_img %s">
										<input  type="radio" id="layout_3" name="option_blindmatrix_settings[blinds_archive_layout]" %s value="3" >Three column layout<br/>
										<label for="layout_3"><img src="' . esc_url($img_dir) . '/layout-3.png" />
									</li>
									<li class="advance_setting_img %s">
										<input  type="radio" id="layout_4" name="option_blindmatrix_settings[blinds_archive_layout]" %s value="4" > Four column layout<br/>
										<label for="layout_4"><img src="' . esc_url($img_dir) . '/layout-4.png" />
									</li>
								  </ul>
							</div>
						  </div>',
						  ( isset($this->options['blinds_archive_layout']) && '2' == $this->options['blinds_archive_layout']  ? 'selected' : '' ),
						  ( isset($this->options['blinds_archive_layout']) && '2' == $this->options['blinds_archive_layout'] ? 'CHECKED' : '' ),
						  ( isset($this->options['blinds_archive_layout']) && '3' == $this->options['blinds_archive_layout']  ? 'selected' : '' ),
						  ( isset($this->options['blinds_archive_layout']) && '3' == $this->options['blinds_archive_layout']  ? 'CHECKED' : '' ),
						  ( isset($this->options['blinds_archive_layout']) && '4' == $this->options['blinds_archive_layout']  ? 'selected' : '' ),
						  ( isset($this->options['blinds_archive_layout']) && '4' == $this->options['blinds_archive_layout']  ? 'CHECKED' : '' ));
		
	}
	public function bm_reset_menu_callback() {
		?>
		<a href="#" class="button bm-reset-menu-button">Reset</a>
		<?php 
	}
	public function enable_menu_locations_callback( $settings) {
		$locations      = get_registered_nav_menus();
		$menu_locations = get_nav_menu_locations();
		$option_blindmatrix_settings = get_option( 'option_blindmatrix_settings' );
		if ( !current_theme_supports( 'menus' ) ) :
			return '';	
		endif;
	
		$created_menu_id = '';	
		if (empty(wp_get_nav_menus())) {
			$created_menu_id = wp_create_nav_menu('Home');
			update_option('bm_created_menu_id', $created_menu_id);
		}
		
		$blindmatrix_settings = get_option('option_blindmatrix_settings');
		$location_names = isset($blindmatrix_settings['menu_location_name']) ?array_keys($blindmatrix_settings['menu_location_name']):array() ;
		$location_ids = isset($blindmatrix_settings['menu_location_id']) ?$blindmatrix_settings['menu_location_id']:array() ;
		?>
		<fieldset class="blindmatrix-menu-fieldset">
			  <?php
				$display_menu_location_checkboxes = !empty(get_option('blindmatrix_display_menu_location')) ? get_option('blindmatrix_display_menu_location'):array();
				foreach ( $locations as $location => $description ) :
					$location_id =  esc_attr(isset($menu_locations[$location]) ? $menu_locations[$location]:'' );
					$created_menu_id = !empty(get_option('bm_created_menu_id')) ? get_option('bm_created_menu_id'):'';
					$location_id = !$location_id ? $created_menu_id : $location_id;
					if (!$location_id) :
						continue;
					  endif;
					?>
					<div class="blindmatrix-menu-setting-location">
						<input type="checkbox" class="blindmatrix-menu-location-checkbox" name="option_blindmatrix_settings[menu_location_name][<?php echo wp_kses_post($location); ?>]" id="locations-<?php echo wp_kses_post($location); ?>"
																																						   <?php 
																																							if (in_array($location, $location_names)) :
																																								?>
							checked="checked"<?php endif; ?>>
						<input type="hidden" name="option_blindmatrix_settings[menu_location_id][<?php echo wp_kses_post($location); ?>]" value="<?php echo esc_attr($location_id); ?>">
							
						<label for="locations-<?php echo wp_kses_post($location); ?>"><?php echo wp_kses_post($description); ?></label>
					</div>
				  <?php endforeach; ?>
		 </fieldset>
		<?php
	}
	
	public function set_screen_ids( $screen_ids) {
		$screen_object = get_current_screen();
		$screen_id = $screen_object->id;
		$plugin_screen_ids = array('toplevel_page_bm','blindmatrix-ecommerce_page_bmsettings');
		if (!in_array($screen_object->id, $plugin_screen_ids)) {
			return $screen_ids;
		}

		return array_merge($screen_ids, $plugin_screen_ids);
	}
}

if ( is_admin() ) {
	$my_settings_page = new BlindSetting();
}
