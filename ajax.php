<?php
$_mode = isset($_REQUEST['mode']) ? wp_kses_post($_REQUEST['mode']):'';
$actions = array(
	'getpricetablemaxprice' => true,
	'getFabricList' => true,
	'getColorDetails' => true,
	'getsubcurtainliningnew' => true,
	'getsubcurtainliningnewtwo' => true,
	'getcomponentsublist' => true,
	'getblindscomponentsublist' => true,
	'fabriclist' => true,
	'get_quick_quote_colorcategories' => true,
	'get_quick_quote' => true,
	'removeitem' => true,
	'sampleOrderItem' => true,
	'GetCurtainParameterTypeGroup' => true,
	'getprice'                     => true,
	'getparameterdetails'          => true,
	'getcurtainprice'              => true,
	'blindmatrix_copy_cart_item'   => true,
	'GetCurtainProductDetail'      => true,
	'product_category'             => true,
	'material_image_action'        => true,
); 

foreach ($actions as $_action => $no_priv) {
	$callback = 'bm_eco_' . $_action;
	add_action('wp_ajax_' . $_action, $callback);
	if ($no_priv) {
		add_action('wp_ajax_nopriv_' . $_action, $callback);
	}
}

function bm_eco_getpricetablemaxprice() {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$productid = isset($_REQUEST['productid']) ? wc_clean(wp_unslash($_REQUEST['productid'])) :'';
	$producttypeid = isset($_REQUEST['producttypeid']) ? wc_clean(wp_unslash($_REQUEST['producttypeid'])):'';
	$vendorid = isset($_REQUEST['vendorid']) ? wc_clean(wp_unslash($_REQUEST['vendorid'])):'';
	$unit = isset($_REQUEST['unit']) ? wc_clean(wp_unslash($_REQUEST['unit'])):'';
	$countryid = isset($_REQUEST['countryid']) ? wc_clean(wp_unslash($_REQUEST['countryid'])):'';
	$response = CallAPI('GET', array('mode'=>'getpricetablemaxprice', 'productid'=>$productid,'producttypeid'=>$producttypeid,'vendorid'=>$vendorid,'unit'=>$unit,'countryid'=>$countryid));
	$json_response = $response;
	echo wp_json_encode($json_response);
	exit;
}

function bm_eco_getFabricList() {
	
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$productid = isset($_REQUEST['productid']) ? wc_clean(wp_unslash($_REQUEST['productid'])):'';
	$productname = isset($_REQUEST['productname']) ? wc_clean(wp_unslash($_REQUEST['productname'])):'' ;
	$productcategory = isset($_REQUEST['productcategory']) ? wc_clean(wp_unslash($_REQUEST['productcategory'])):'';
	$productno = isset($_REQUEST['productno']) ? wc_clean(wp_unslash($_REQUEST['productno'])):'';
	$parameterid = isset($_REQUEST['parameterid']) ? wc_clean(wp_unslash($_REQUEST['parameterid'])):'' ;
	$ecommerce_sample = isset($_REQUEST['ecommerce_sample']) ? wc_clean(wp_unslash($_REQUEST['ecommerce_sample'])):'';
	$searchFabric = isset($_REQUEST['searchFabric']) ? wc_clean(wp_unslash($_REQUEST['searchFabric'])):'';
	$_page = isset($_REQUEST['page']) ? wc_clean(wp_unslash($_REQUEST['page'])):'' ;
	$mandatory = isset($_REQUEST['mandatory']) ? wc_clean(wp_unslash($_REQUEST['mandatory'])):'' ;
	$num_of_rows = isset($_REQUEST['num_of_rows']) ? wc_clean(wp_unslash($_REQUEST['num_of_rows'])):'';
	$fablicfilterarray = isset($_REQUEST['fablicfilterarray']) ? wc_clean(wp_unslash($_REQUEST['fablicfilterarray'])):'' ;
	$catfilterarray = isset($_REQUEST['catfilterarray']) ? wc_clean(wp_unslash($_REQUEST['catfilterarray'])):'';
	if ('' != $fablicfilterarray && !is_array($fablicfilterarray)) {
		$fablicfilterarray = explode(',', $fablicfilterarray);
	}
	if ('' != $catfilterarray && !is_array($catfilterarray)) {
		$catfilterarray = explode(',', $catfilterarray);
	}
	$response = CallAPI('GET', array('mode'=>'getFabricList','search_text'=>$searchFabric,'categoryarray'=>$catfilterarray,'fablicfilterarray'=>$fablicfilterarray, 'productid'=>$productid,'productname'=>$productname,'productcategory'=>$productcategory,'parameterid'=>$parameterid,'page'=>$_page,'num_of_rows'=>$num_of_rows));
	if (count($response) > 0) {
		ob_start();
		?>
		<span id="errormsg_producttypesub" data-text-color="alert" class="is-small errormsg"></span>
		<?php
		foreach ($response as  $getfabricdetails) { 
			?>
	 
			<?php		
			$orderItemId =  $productno . $getfabricdetails->parameterTypeId . $getfabricdetails->fabricid . $getfabricdetails->vendorid;
		
			ob_start();
			?>
		<a id="<?php echo wp_kses_post($orderItemId); ?> " style="display:block;margin:5px 0 !important" href="javascript:;" data-productno="<?php echo wp_kses_post($productno); ?>" data-parameterTypeId="<?php echo wp_kses_post($getfabricdetails->parameterTypeId); ?>" data-fabricid="<?php echo wp_kses_post($getfabricdetails->fabricid); ?>" data-colorid="" data-vendorid ="<?php echo wp_kses_post($getfabricdetails->vendorid); ?>" data-colorid="" class="button primary is-outline box-shadow-2 box-shadow-2-hover samplecartatag">
			<span class="samplecart" style="padding: 0px !important;margin:5px 0 !important;">Free Sample</span>
		</a>
			<?php
			$sampleCart = ob_get_contents();
			ob_end_clean();
			$cart = isset($_SESSION['cart']) ? wc_clean(wp_unslash($_SESSION['cart'])):'';
			if ($cart && is_array($cart)) {
				if (count($cart) > 0) {
					if (false !== array_search($orderItemId, array_column($cart, 'sampleOrderItemId'))) {
					
						ob_start();
						?>
						<a id="<?php echo wp_kses_post($orderItemId); ?>" style="display:block;margin:5px 0 !important" href="javascript:;"  data-productno="<?php echo wp_kses_post($productno); ?>" data-parameterTypeId="<?php echo wp_kses_post($getfabricdetails->parameterTypeId); ?>" data-fabricid="<?php echo wp_kses_post($getfabricdetails->fabricid); ?>" data-colorid="" data-vendorid ="<?php echo wp_kses_post($getfabricdetails->vendorid); ?>" class="button primary is-outline box-shadow-2 box-shadow-2-hover samplecartatag">
							<i class="icon-checkmark"></i>
							<span class="samplecart" style="padding: 0px !important;margin:5px 0 !important;">Sample Added</span>
						</a>
						<?php
						$sampleCart = ob_get_contents();
						ob_end_clean();
					}
				}
			}
			if ('0' == $ecommerce_sample) {
				$sampleCart = '';
			}
			$sampleCart = '';
			?>
		<input  data-vendorid="<?php echo wp_kses_post($getfabricdetails->vendorid); ?>" data-parameterTypeId="<?php echo wp_kses_post($getfabricdetails->parameterTypeId); ?>" data-getparameterid="producttypesub" data-minWidth = "<?php echo wp_kses_post($getfabricdetails->minWidth); ?>" data-maxWidth = "<?php echo wp_kses_post($getfabricdetails->maxWidth); ?>" data-minDrop = "<?php echo wp_kses_post($getfabricdetails->minDrop); ?>" data-maxDrop = "<?php echo wp_kses_post($getfabricdetails->maxDrop); ?>" data-productname="<?php echo wp_kses_post($getfabricdetails->productname); ?>" data-producttypeid="<?php echo wp_kses_post($getfabricdetails->parameterTypeId); ?>" data-producttype="<?php echo wp_kses_post($getfabricdetails->producttype); ?>" data-labelval="<?php echo wp_kses_post($getfabricdetails->fabricname); ?>" data-fabricsupplier="<?php echo wp_kses_post($getfabricdetails->fabricsupplier); ?>" data-fabricsupplierid="<?php echo wp_kses_post($getfabricdetails->fabricsupplierid); ?>" data-fabricname="<?php echo wp_kses_post($getfabricdetails->fabricname); ?>"   data-vendorname="<?php echo wp_kses_post($getfabricdetails->vendorname); ?>"  class="showorderdetails 	
										  <?php 
											if ('Create no sub sub parameter' == $productcategory) {
												echo 'color_blind';  } 
											
						echo('fabric_blind blindsradio');
		
			if (1 == $mandatory) {
						echo 'mandatory_validate'; } 
			?>
 " value="<?php echo wp_kses_post($getfabricdetails->fabricid); ?>"  name="fabricnameck" id="productype<?php echo wp_kses_post($getfabricdetails->fabricid); ?>" autocomplete="off" type="radio">
			<?php if ('Create no sub sub parameter' == $productcategory) { ?>
			<label title="<?php echo wp_kses_post($getfabricdetails->fabricname); ?>"   class="blindslabel image radio" id="<?php echo wp_kses_post($getfabricdetails->fabricid); ?>" for="productype<?php echo wp_kses_post($getfabricdetails->fabricid); ?>">
				<?php 
				if ('' != $getfabricdetails->imagepath) {
					$img = $getfabricdetails->imagepath;
				} else {
					$img =  untrailingslashit( plugins_url( '/', __FILE__ ) ) . '/vendor/Shortcode-Source/image/blinds/no-image.jpg';
				}
					
				?>
			<img src="<?php echo esc_url($img); ?>" alt="" width="120" height="120"> <span class="fabricname"> <?php echo wp_kses_post($getfabricdetails->fabricname); ?> </span> 
				<?php echo wp_kses_post($sampleCart); ?>
			</label>
		<?php } else { ?>
			<label  title="<?php echo wp_kses_post($getfabricdetails->fabricname); ?>" class="blindslabel radio" id="<?php echo wp_kses_post($getfabricdetails->fabricid); ?>"  for="productype<?php echo wp_kses_post($getfabricdetails->fabricid); ?>">
															 <?php 
																if ('' != $getfabricdetails->imagepath) {
																	$img = $getfabricdetails->imagepath;
																} else {
																	$img =  untrailingslashit( plugins_url( '/', __FILE__ ) ) . '/vendor/Shortcode-Source/image/blinds/no-image.jpg';
																}
					
																?>
			<!--<img src="<?php echo esc_url($img); ?>" alt="" width="120" height="120"> --><span class="fabricname"><?php echo wp_kses_post($getfabricdetails->fabricname); ?> </span></label>
		<?php } ?>

			<?php
		}
		$output['html'] = ob_get_contents();
		$output['html_empty'] = 0;
	} else {
		$output['html'] = '<p>no record found</p>';    
		$output['html_empty'] = 1;    
	}
	$output['response'] = $response;
	$output['count'] = count($response);
	$output['page'] = $_page; 
	ob_end_clean();
	$json_response = $output;
	echo wp_json_encode($json_response);
	exit;
}

function bm_eco_getColorDetails() {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash(( $_REQUEST['mode'] ))):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$fabricid = isset($_REQUEST['fabricid']) ? wc_clean(wp_unslash($_REQUEST['fabricid'])):'';
	$productname = isset($_REQUEST['productname']) ? wc_clean(wp_unslash($_REQUEST['productname'])):'';
	$parametertypeid = isset($_REQUEST['parametertypeid']) ? wc_clean(wp_unslash($_REQUEST['parametertypeid'])):'' ;
	$vendorname = isset($_REQUEST['vendorname']) ? wc_clean(wp_unslash($_REQUEST['vendorname'])):'' ;
	$fabricsupplier = isset($_REQUEST['fabricsupplier']) ? wc_clean(wp_unslash($_REQUEST['fabricsupplier'])):'';
	$fabricsupplierid = isset($_REQUEST['fabricsupplierid']) ? wc_clean(wp_unslash($_REQUEST['fabricsupplierid'])):'' ;
	$producttype = isset($_REQUEST['producttype']) ? wc_clean(wp_unslash($_REQUEST['producttype'])):'' ;
	$fabricname = isset($_REQUEST['fabricname']) ? wc_clean(wp_unslash($_REQUEST['fabricname'])):'';
	$mandatory = isset($_REQUEST['mandatory']) ? wc_clean(wp_unslash($_REQUEST['mandatory'])):'' ;
	$productno = isset($_REQUEST['productno']) ? wc_clean(wp_unslash($_REQUEST['productno'])):'' ;
	$vendorid = isset($_REQUEST['vendorid']) ? wc_clean(wp_unslash($_REQUEST['vendorid'])):'';
	$ecommerce_sample = isset($_REQUEST['ecommerce_sample']) ? wc_clean(wp_unslash($_REQUEST['ecommerce_sample'])):'';

	$response = CallAPI('GET', array('mode'=>'getColorDetails', 'fabricid'=>$fabricid,'productname'=>$productname,'vendorname'=>$vendorname,'fabricsupplier'=>$fabricsupplier,'producttype'=>$producttype,'fabricname'=>$fabricname,'fabricsupplierid'=>$fabricsupplierid));
	ob_start();
	?>
	<div class="label">
		<label class="serach_input_color_label" style="width:25%; display: inline-block;" for="<?php echo wp_kses_post($response->getcolorparameterdetails->colorparametername); ?>">
			<?php echo wp_kses_post($response->getcolorparameterdetails->colorparametername); ?>
		</label>
		<div class="serach_input_color_contianer" style="position: relative;width: 70%;text-align: right; display: inline-block;">
		<span style="display:none;" class="colorname_showbox"><i class="icon-checkmark"></i><span class="colorname_showbox_value"> </span></span>
		  <input type="text" placeholder="Search" class="serach_input_color" id="serach_input_color" style=" margin: 0;width: 200px;padding-left: 35px;border-radius: 20px;font-size: 15px; font-weight: 500; color: black!important; background: #fff;">
		  <i style="position: absolute;right: 170px;top: 10px;font-size: 14px;"class="icon-search"></i>
		</div>     
	</div>
	<span id="errormsg_producttypesubsub" data-text-color="alert" class="is-small errormsg"></span>
	<div class="value">
		<?php foreach ($response->getcolorparameterlist as  $getcolorparameterlist) { ?> 
			<?php		
			$orderItemId =  $productno . $parametertypeid . $fabricid . $getcolorparameterlist->colorid . $vendorid;
			ob_start();
			?>
			<a id="<?php echo wp_kses_post($orderItemId); ?> " style="display:block;margin:5px 0 !important" href="javascript:;"  data-productno="<?php echo wp_kses_post($productno); ?>" data-colorid="<?php echo wp_kses_post($getcolorparameterlist->colorid); ?>" data-parameterTypeId="<?php echo wp_kses_post($parametertypeid); ?>" data-fabricid="<?php echo wp_kses_post($fabricid); ?>"  data-vendorid ="<?php echo wp_kses_post($vendorid ); ?>"  class="button primary is-outline box-shadow-2 box-shadow-2-hover samplecartatag">
				<span class="samplecart" style="padding: 0px !important;margin:5px 0 !important;">Free Sample</span>
			</a>
			<?php
			$sampleCart = ob_get_contents();
			ob_end_clean();
			$cart = isset($_SESSION['cart']) ? wc_clean(wp_unslash($_SESSION['cart'])):'';
			if ($cart && is_array($cart) && count($cart) > 0) {
				if (false !== array_search($orderItemId, array_column($cart, 'sampleOrderItemId')) ) {
					
					ob_start();
					?>
						<a id="<?php echo wp_kses_post($orderItemId); ?>" style="display:block;margin:5px 0 !important" href="javascript:;"  data-productno="<?php echo wp_kses_post($productno); ?>" data-colorid="<?php echo wp_kses_post($getcolorparameterlist->colorid); ?>" data-parameterTypeId="<?php echo wp_kses_post($parametertypeid); ?>" data-fabricid="<?php echo wp_kses_post($fabricid); ?>"  data-vendorid ="<?php echo wp_kses_post($vendorid ); ?>" class="button primary is-outline box-shadow-2 box-shadow-2-hover samplecartatag">
							<i class="icon-checkmark"></i>
							<span class="samplecart" style="padding: 0px !important;margin:5px 0 !important;">Sample Added</span>
						</a>
					<?php
					$sampleCart = ob_get_contents();
					ob_end_clean();
				}
			}
			
			if ('0'  == $ecommerce_sample) {
				$sampleCart = '';
			}
			$sampleCart = '';
			?>
			<input data-getparameterid="producttypesubsub"  data-labelval="<?php echo wp_kses_post($getcolorparameterlist->colorname); ?>" class="showorderdetails color_blind blindsradio  
																					  <?php 
																						if (1 == $mandatory ) {
																							echo 'mandatory_validate '; } 
																						?>
			" value="<?php echo wp_kses_post($getcolorparameterlist->colorid); ?>"  name="colornamesub" id="productype<?php echo wp_kses_post($getcolorparameterlist->colorid); ?>" autocomplete="off" type="radio">
			
			<label title="<?php echo wp_kses_post($getcolorparameterlist->colorname); ?>" data-getparameterid="producttypesubsub" data-text="<?php echo wp_kses_post($getcolorparameterlist->colorname); ?>" class="blindslabel blindslabelcolor image radio" id="<?php echo wp_kses_post($getcolorparameterlist->colorid); ?>" for="productype<?php echo wp_kses_post($getcolorparameterlist->colorid); ?>">
			<?php 
			if ('' != $getcolorparameterlist->imagepath ) {
				$img = $getcolorparameterlist->imagepath;
			} else {
				$img =  untrailingslashit( plugins_url( '/', __FILE__ ) ) . '/vendor/Shortcode-Source/image/blinds/no-image.jpg';
			}
				
			?>
			<img src="<?php echo esc_url($img); ?>" alt="" width="120" height="120"> <span class="colorname_showdetails"> <?php echo wp_kses_post($getcolorparameterlist->colorname); ?> </span> 
			<?php echo wp_kses_post($sampleCart); ?>
			</label>
		<?php } ?> 
		<div style="display:none;text-align: center;font-weight: 500;text-transform: capitalize; color: rgb(187 26 26);" class="no_products_div"><?php echo 'No ' . wp_kses_post($response->getcolorparameterdetails->colorparametername) . ' Found'; ?></div>
	</div>
	<?php
	$output = ob_get_contents();
	ob_end_clean();
	$json_response = $output;
	echo wp_json_encode($json_response);
	exit;
}

function bm_eco_getsubcurtainliningnewtwo() {
	bm_eco_getsubcurtainliningnew();
}

function bm_eco_getsubcurtainliningnew() {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$liningid = isset($_REQUEST['liningid']) ? wc_clean(wp_unslash($_REQUEST['liningid'])):'';
	$parameterid = isset($_REQUEST['parameterid']) ? wc_clean(wp_unslash($_REQUEST['parameterid'])):'';
	$method = isset($_REQUEST['method']) ? wc_clean(wp_unslash($_REQUEST['method'])):'' ;
	
	$response = CallAPI('GET', array('mode'=>'GetSubCurtainlining', 'liningid'=>$liningid, 'parameterid'=>$parameterid, 'method'=>$method));
	
	$subcurtainclass = 'subcurtainliningnew';
	if ('2' == $method) {
		$subcurtainclass = 'subcurtainliningnewtwo';
	}
	
	$subcurtainlining_html='';
	if (count($response) > 0) {
		foreach ($response as $subcurtainlining) {
			ob_start();
			?>
				 <div data-role="collapsible" class="configurator-option border curtainliningsub_<?php echo wp_kses_post($subcurtainlining->parameterid); ?>" role="presentation" data-collapsible="true">
					<div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
						<h4 class="title">
							<span data-bind="text: title"><?php echo wp_kses_post($subcurtainlining->componentname); ?></span>
						</h4>
				</div>
			<?php		
			$content = ob_get_contents();
			ob_end_clean();
			
			$subcurtainlining_html .= $content;

			$sub_value = '';
			if (!empty($subcurtainlining->getsubcurtainliningsub)) {
				foreach ($subcurtainlining->getsubcurtainliningsub as $subcurtainliningsub) {
					$selected='';
					$sub_value .='<option data-jsevent="showorderdetails" getsubliningid="'.$subcurtainliningsub->priceid.'" getsubsubliningid="'.$subcurtainliningsub->componentsubid.'" getliningmethod="'.$method.'" getliningpermeter'.$method.'="'.$subcurtainliningsub->liningPrice.'" getmarkupperwidth'.$method.'="'.$subcurtainliningsub->valuetype.'" parametername="'.$subcurtainlining->componentname.'" getparametervalue="'.$subcurtainliningsub->componentName.'" type="select" id="curtainliningsub'.$subcurtainliningsub->componentsubid.''.$subcurtainliningsub->priceid.'" name="Curtainliningsubvalue'.$subcurtainliningsub->priceid.'" value="'.$subcurtainliningsub->liningPrice.'">'.$subcurtainliningsub->componentName.'</option>';
				}
			}
			ob_start();
			?>
			<div class="configurator-fabric-image configurator-option-content configurator-fabric-grid value showorderdetails" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
				<div class="option-grid ratio">
					<select class="blindmatrix-select2 action configurator-fabric-item <?php echo esc_attr($subcurtainclass); ?>"  onchange="showorderdetails();">
						<?php echo ($sub_value); ?>
					</select>
				</div>
			</div>
			<?php
			$content = ob_get_contents();
			ob_end_clean();
			$subcurtainlining_html .= $content;
		}
	}
	
	$json_response['result'] = $response;
	$json_response['CurtainliningSubList'] = $subcurtainlining_html;
	echo wp_json_encode($json_response);
	exit;
}
function bm_eco_getcomponentsublist() {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$maincomponent = isset($_REQUEST['maincomponent']) ? wc_clean(wp_unslash($_REQUEST['maincomponent'])):'';
	$parameter_Id = isset($_REQUEST['parameterId']) ? wc_clean(wp_unslash($_REQUEST['parameterId'])):'';
	$selected_args = isset($_REQUEST['parameterId']) ? wc_clean(wp_unslash($_REQUEST['selected_args'] )): '';
	
	$response = CallAPI('GET', array('mode'=>'GetComponentSubList', 'maincomponent'=>$maincomponent));
	$click_function = '';

	$blindstype = isset($_REQUEST['blindstype']) ? wc_clean(wp_unslash($_REQUEST['blindstype'])):'';
	if (4 == $blindstype || 0 == $blindstype) {
		$calculate_subcomponent = 'get_calculate_price()';
		if (4 == $blindstype) {
			$calculate_subcomponent = 'showorderdetails()';
		}
	} else {
		$calculate_subcomponent = '';
	}
	
	$component_sub_html='';
	if (isset($response->ComponentSubList) && is_array($response->ComponentSubList) && !empty($response->ComponentSubList) && count($response->ComponentSubList) > 0) {
		foreach ($response->ComponentSubList as $ComponentSubList) {
			
			$mandatory = '';
			$mandatory_class = '';
			$mandatory_class1 = '';
			$sel_multiple = '';
			
			if (1 == $ComponentSubList->component_sub_select_option) {
				$sel_multiple = 'multiple="multiple"';
				$mandatory_class .= 'demo ';
			}else {
				$mandatory_class .= 'subdemo ';
			}
			
			if (1 == $ComponentSubList->subcompmandatory) {
				$mandatory ='<font color="red">*</font>';
				$mandatory_class ='mandatoryvalidate';
				$mandatory_class1 ='mandatory_validate';
			}
			
			$right_arrow =  plugin_dir_url(__FILE__) . '/assets/image/right-arrow.gif';
			
			if (4 == $blindstype || 0 == $blindstype) {
				ob_start();
				?>
				<tr style="display:flex;" class="subchild shuttercomponentsub componentsub_<?php echo wp_kses_post($ComponentSubList->parameterid); ?>">
				<td class="label">
					<h4 for="<?php echo $ComponentSubList->componentname; ?>"><?php echo wp_kses_post($ComponentSubList->componentname); ?><?php echo wp_kses_post($mandatory); ?></h4>
				</td>	
				<?php
				$content = ob_get_contents();
				ob_end_clean();
				$component_sub_html .=$content;

				if ('1' == $ComponentSubList->fixedorpercentage || '15' == $ComponentSubList->fixedorpercentage) {
				
					$option_value = '<option value="">Choose an option</option>';
					if (!empty($ComponentSubList->ComponentSubvalue)) {
						foreach ($ComponentSubList->ComponentSubvalue as $componentsubvalue) {
						
							$selected='';
							if (1 == $componentsubvalue->defaultValue) {
								$selected = 'selected';
							}
							$option_value .= '<option '.$selected.' data-img=" " allowance="'.$componentsubvalue->allowance.'" price="'.$componentsubvalue->sellingprice.'" value="'.$componentsubvalue->sellingprice."~".$componentsubvalue->parametername."~".$componentsubvalue->componentprice."~".$componentsubvalue->componentsubid.'">'.$componentsubvalue->parametername.'</option>';
						}
					}
					
					ob_start();
					?>
					<td class="value" style="width: 60%;position: relative;">
						<select id="<?php echo wp_kses_post($ComponentSubList->componentname); ?>" data-parent_id="<?php echo wp_kses_post($ComponentSubList->parameterid); ?>" name="Componentsubvalue[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>][]" class="<?php echo wp_kses_post($mandatory_class); ?> blindmatrix-select2" <?php echo wp_kses_post($sel_multiple); ?> onchange="<?php echo wp_kses_post($calculate_subcomponent); ?>">
						<?php echo ($option_value); ?>
						</select>
						<input type="hidden" name="ComponentSubParametername[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>]" value="<?php echo wp_kses_post($ComponentSubList->componentname); ?>">
					</td>
					<?php 
					$content = ob_get_contents();
					ob_end_clean();
				
					$component_sub_html .= $content;
				} elseif ('11' == $ComponentSubList->fixedorpercentage) {
					ob_start();
					?>
					<td class="value" style="width: 60%;position: relative;">
						<input id="<?php echo wp_kses_post($ComponentSubList->componentname); ?>" name="Componentsubvalue[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>][]" class="<?php echo wp_kses_post($mandatory_class); ?>" type="number" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" onkeyup="<?php echo wp_kses_post($calculate_subcomponent); ?>">
						<input type="hidden" name="ComponentSubParametername[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>]" value="<?php echo wp_kses_post($ComponentSubList->componentname); ?>">
					</td>
					<?php
					$content = ob_get_contents();
					ob_end_clean();
					$component_sub_html .=$content;
				} elseif ('12' == $ComponentSubList->fixedorpercentage) {
					
					ob_start();
					?>
					<td class="value" style="width: 60%;position: relative;">
						<input id="<?php echo wp_kses_post($ComponentSubList->componentname); ?>" name="Componentsubvalue[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>][]" class="<?php echo wp_kses_post($mandatory_class); ?>" type="text" onkeyup="<?php echo wp_kses_post($calculate_subcomponent); ?>">
						<input type="hidden" name="ComponentSubParametername[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>]" value="<?php echo wp_kses_post($ComponentSubList->componentname); ?>">
					</td>
					<?php
					$content = ob_get_contents();
					ob_end_clean();	
					$component_sub_html .=$content;
				}
				
				ob_start();
				?>
					</tr>
					<tr style="display:flex;" class="subchild componentsub_end_<?php echo $ComponentSubList->parameterid; ?> componentsub_end" ><td colspan="2" style="padding: 0px;"><div class="product_atributes" style="padding: 0px;height: 5px;">&nbsp;</div></td></tr>
				<?php
				$content = ob_get_contents();
				ob_end_clean();
				$component_sub_html .=$content;
			} else {
				ob_start();
				?>
				 <div data-role="collapsible" class="configurator-option border componentsub_<?php echo wp_kses_post($ComponentSubList->parameterid); ?>" role="presentation" data-collapsible="true">
				<div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
					<h4 class="title">
						<span data-bind="text: title"><?php echo wp_kses_post($ComponentSubList->componentname); ?><?php echo wp_kses_post($mandatory); ?></span>
					</h4>
					<span id="errormsg_<?php echo wp_kses_post($ComponentSubList->priceid); ?>" data-text-color="alert" class="is-small errormsg"></span>
				</div>	
				<?php
				$content = ob_get_contents();
				ob_end_clean();
				$component_sub_html .=$content;
			
				if ('1' == $ComponentSubList->fixedorpercentage || '15' == $ComponentSubList->fixedorpercentage) {
				
					$class3 = 'componentsub';
					if ('15' == $ComponentSubList->fixedorpercentage) {
						$class3 = 'componentsuballowance';
					}
				
					$sub_value = '';
					if (!empty($ComponentSubList->ComponentSubvalue)) {
						foreach ($ComponentSubList->ComponentSubvalue as $componentsubvalue) {
						
							$data_position = strtolower($componentsubvalue->parametername);
							$data_value = strtolower($componentsubvalue->parametername);
							$data_id = 'main-componentsub-' . $componentsubvalue->componentsubid;
							if (false !== strpos(strtolower($componentsubvalue->parametername), 'left')) {
								$data_position = 'left';
								$data_value = 'single_left';
								$data_id = 'positionsingle_left';
							}
							if (false !== strpos(strtolower($componentsubvalue->parametername), 'right')) {
								$data_position = 'right';
								$data_value = 'single_right';
								$data_id = 'positionsingle_right';
							}
							if (false !== strpos(strtolower($componentsubvalue->parametername), 'pair') || false !== strpos(strtolower($componentsubvalue->parametername), 'center') ) {
								$data_position = 'center';
								$data_value = 'pair';
								$data_id = 'positionpair';
							}
							if (false !== strpos(strtolower($ComponentSubList->componentname), 'ratio') ) {
								$exp_radio = explode('/', $componentsubvalue->parametername);
								$data_id = 'border_ratios' . trim($exp_radio[1]);
								$data_value = trim($exp_radio[1]);
								$data_position = trim($exp_radio[1]);
								$click_function = 'borderratio(this);';
								$class3 = 'action primary borderratio';
							}
							if (false !== strpos(strtolower($ComponentSubList->componentname), 'fabric') ) {
								$data_id = 'main-componentsubfabric-' . $componentsubvalue->componentsubid;
								$data_value = $componentsubvalue->componentsubid;
							}
						
							$selected='';
							if (1 == $componentsubvalue->defaultValue) {
								$selected = 'selected';
							}
						
							$sel_multiple_input = 'radio';
							if (1 == $ComponentSubList->component_sub_select_option) {
								$sel_multiple_input = 'checkbox';
							}
						
							$datasubval = $componentsubvalue->sellingprice . '~' . $componentsubvalue->parametername . '~' . $componentsubvalue->componentprice . '~' . $componentsubvalue->componentsubid;
							$selected1='';
							if(in_array($datasubval, $selected_args)){ 
								 $selected1 = 'selected';
							}

							$sub_value .='<option '.$selected1.' data-jsevent="showorderdetails" class="configurator-fabric-item option-item '.$class3.'" for="'.$data_id.'" title="'.$componentsubvalue->componentsubid.'" getparameterid="'.$ComponentSubList->priceid.' "  parametername="'.$ComponentSubList->componentname.'" getparametervalue="'.$componentsubvalue->parametername.'" getparameterid="'.$ComponentSubList->priceid.'" type="select" name="Componentsubvalue['.$ComponentSubList->parameterid.']['.$ComponentSubList->priceid.'][]" id="'.$data_id.'" value="'.$datasubval.'" allowance="'.$componentsubvalue->allowance.'" price="'.$componentsubvalue->sellingprice.'" data-sub="'.$data_value.'" >'.$componentsubvalue->parametername.'</option>';
						}
					}
					ob_start();
					?>
					<div class="configurator-fabric-image value" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
						<div class="option-grid configurator-fabric-grid showorderdetails <?php echo ($mandatory_class1); ?>">
							<select class="option-grid blindmatrix-select2 ratio component_sub component_sub_<?php echo esc_attr($parameter_Id);?> showorderdetails <?php echo ($mandatory_class1);?>" onchange="showorderdetails();">
								<?php echo ($sub_value); ?>
							</select>	
						</div>
					</div> 
					<?php
					$content = ob_get_contents();
					ob_end_clean();
					$component_sub_html .=$content;
				} elseif ('11' == $ComponentSubList->fixedorpercentage) {
					ob_start();
					?>
					<div class="configurator-option-content value showorderdetails" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
					<input parametername="<?php echo wp_kses_post($ComponentSubList->componentname); ?>" getparameterid="<?php echo wp_kses_post($ComponentSubList->priceid); ?>" id="<?php echo wp_kses_post($ComponentSubList->componentname); ?>" name="Componentsubvalue[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>][]" class="<?php echo wp_kses_post($mandatory_class); ?> border border-1 border-silver white-back border-radius-10 othersvalue" type="number" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" onkeyup="showorderdetails();<?php echo wp_kses_post($calculate_subcomponent); ?>" onkeydown="showorderdetails();<?php echo wp_kses_post($calculate_subcomponent); ?>">
					<input type="hidden" name="ComponentSubParametername[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>]" value="<?php echo wp_kses_post($ComponentSubList->componentname); ?>">
				</div> 
					<?php
					$content = ob_get_contents();
					ob_end_clean();
					$component_sub_html .=$content;
				
				} elseif ('12' == $ComponentSubList->fixedorpercentage) {
					ob_start();
					?>
					<div class="configurator-option-content value showorderdetails" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
					<input parametername="<?php echo wp_kses_post($ComponentSubList->componentname); ?>" getparameterid="<?php echo wp_kses_post($ComponentSubList->priceid); ?>" id="<?php echo wp_kses_post($ComponentSubList->componentname); ?>" name="Componentsubvalue[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>][]" class="<?php echo wp_kses_post($mandatory_class); ?> border border-1 border-silver white-back border-radius-10 othersvalue" type="text" onkeyup="showorderdetails();{<?php echo wp_kses_post($calculate_subcomponent); ?>}" onkeydown="showorderdetails();<?php echo wp_kses_post($calculate_subcomponent); ?>">
					<input type="hidden" name="ComponentSubParametername[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>]" value="<?php echo wp_kses_post($ComponentSubList->componentname); ?>">
				</div> 
					<?php
					$content = ob_get_contents();
					ob_end_clean();
					$component_sub_html .=$content;
				}

				$component_sub_html .='</div>';

				
			}
		}
	}
	
	$json_response['result'] = $response;
	$json_response['ComponentSubList'] = $component_sub_html;
	echo wp_json_encode($json_response);
	exit;
}

function bm_eco_getblindscomponentsublist() {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$maincomponent = isset($_REQUEST['maincomponent']) ? wc_clean(wp_unslash($_REQUEST['maincomponent'])):'';
	$parameter_Id = isset($_REQUEST['parameterId']) ? wc_clean(wp_unslash($_REQUEST['parameterId']) ):'';
	$selected_args = isset($_REQUEST['selected_args']) ? wc_clean(wp_unslash($_REQUEST['selected_args']) ):'';
	$cart_item_key = isset($_REQUEST['cart_item_key']) ? wc_clean(wp_unslash($_REQUEST['cart_item_key'])):'';
	$_blinds_plugin_data = get_cart_item_blinds_plugin_data($cart_item_key);
	$overall_cart_item_data = !empty($cart_item_key) ? WC()->cart->get_cart_item($cart_item_key):false;
	$stored_component_sub_value = isset($_blinds_plugin_data['Componentsubvalue']) ?$_blinds_plugin_data['Componentsubvalue']:'' ;
	
	$response = CallAPI('GET', array('mode'=>'GetComponentSubList', 'maincomponent'=>$maincomponent));
	$calculate_subcomponent = '';
	$component_id = '';
	$component_sub_html='';
	if (isset($response->ComponentSubList) && is_array( $response->ComponentSubList) && count($response->ComponentSubList) > 0) {
		foreach ($response->ComponentSubList as $ComponentSubList) {
			
			$mandatory = '';
			$mandatory_class = '';
			$mandatory_class1 = '';
			$sel_multiple = '';
			$component_id = $ComponentSubList->component_id;
			$stored_values = isset($stored_component_sub_value[$ComponentSubList->parameterid][$ComponentSubList->priceid]) ? $stored_component_sub_value[$ComponentSubList->parameterid][$ComponentSubList->priceid]:array();
			
			if (1 == $ComponentSubList->component_sub_select_option) {
				$sel_multiple = 'multiple="multiple"';
				$mandatory_class .= 'demo ';
			}else{
				$mandatory_class .= 'subdemo';
			}
			
			if (1 == $ComponentSubList->subcompmandatory) {
				$mandatory ='<font color="red">*</font>';
				$mandatory_class .=' mandatoryvalidate';
				$mandatory_class1 .=' mandatory_validate';
			}

			$subparameterName = str_replace(' ', '_', $ComponentSubList->componentname);
			$right_arrow = plugin_dir_url(__FILE__) . '/assets/image/right-arrow.gif';
			$display_subcomponent = true;
			if($display_subcomponent){
				ob_start();
				?>
				<div data-role="collapsible" class="componentsubcontainer showdetailscontainer configurator-option border componentsub_<?php echo wp_kses_post($ComponentSubList->parameterid); ?>" role="presentation" data-collapsible="true">
					<div class="label configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
						<label data-label="<?php echo wp_kses_post($ComponentSubList->componentname); ?>" for="<?php echo wp_kses_post($subparameterName); ?>_<?php echo wp_kses_post($ComponentSubList->priceid); ?>"><?php echo wp_kses_post($ComponentSubList->componentname.$mandatory); ?></label>
				</div>
				<?php
	
				if($ComponentSubList->fixedorpercentage == '1' || $ComponentSubList->fixedorpercentage == '15'){
					?>
					<div class="value" style="position: relative;">
						<span id="errormsg_<?php echo wp_kses_post($ComponentSubList->priceid); ?>" data-text-color="alert" class="is-small errormsg"></span>
						<select data-priceid ="<?php echo wp_kses_post($ComponentSubList->component_id);?>"  data-parent_id="<?php echo wp_kses_post($ComponentSubList->parameterid); ?>" data-getparameterid="<?php echo wp_kses_post($ComponentSubList->priceid); ?>"  id="<?php echo wp_kses_post($subparameterName); ?>_<?php echo wp_kses_post($ComponentSubList->priceid); ?>" name="Componentsubvalue[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>][]" class="showorderdetails componentsub <?php echo wp_kses_post($mandatory_class); ?>" <?php echo wp_kses_post($sel_multiple); ?> onchange="<?php echo wp_kses_post($calculate_subcomponent); ?>">
					<?php
					if($sel_multiple == ''){
						?>
						<option value="">Choose an option</option>
						<?php
					}
					if(!empty($ComponentSubList->ComponentSubvalue)){
						foreach($ComponentSubList->ComponentSubvalue as $componentsubvalue){
							
							$selected='';
							if($componentsubvalue->defaultValue == 1 ){
								$selected = 'selected';
							}
							if(isset($selected_args[$componentsubvalue->componentsubid])){
								 $selected = 'selected';
							}
							if(is_array($stored_values) && in_array($componentsubvalue->sellingprice."~".$componentsubvalue->parametername."~".$componentsubvalue->componentprice."~".$componentsubvalue->componentsubid,$stored_values)){
								$selected = 'selected';
							}
							?>
							<option <?php echo wp_kses_post($selected); ?> title="<?php echo wp_kses_post($componentsubvalue->componentsubid); ?>" allowance="<?php echo wp_kses_post($componentsubvalue->allowance); ?>" data-priceid="<?php echo wp_kses_post($ComponentSubList->component_id); ?>" price="<?php echo wp_kses_post($componentsubvalue->sellingprice); ?>" value="<?php echo wp_kses_post($componentsubvalue->sellingprice."~".$componentsubvalue->parametername."~".$componentsubvalue->componentprice."~".wp_kses_post($componentsubvalue->componentsubid));?>"><?php echo wp_kses_post($componentsubvalue->parametername); ?></option>
							<?php
						}
					}
					?>
						</select>
						<input type="hidden" name="ComponentSubParametername[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>]" value="<?php echo wp_kses_post($ComponentSubList->componentname); ?>">
					</div>
					<?php 
				}elseif($ComponentSubList->fixedorpercentage == '11'){
					ob_start();
					?>
					<div class="value" style="position: relative;">
						<input id="<?php echo wp_kses_post($ComponentSubList->componentname); ?>" name="Componentsubvalue[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>][]" class="<?php echo wp_kses_post($mandatory_class); ?>" type="number" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" onkeyup="<?php echo wp_kses_post($calculate_subcomponent); ?>">
						<input type="hidden" name="ComponentSubParametername[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>]" value="<?php echo wp_kses_post($ComponentSubList->componentname); ?>">
					</div>
				   <?php 
				}elseif($ComponentSubList->fixedorpercentage == '12'){
					?>
					<div class="value" style="position: relative;">
						<input id="<?php echo wp_kses_post($ComponentSubList->componentname); ?>" name="Componentsubvalue[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>][]" class="<?php echo wp_kses_post($mandatory_class); ?>" type="text" onkeyup="<?php echo wp_kses_post($calculate_subcomponent); ?>">
						<input type="hidden" name="ComponentSubParametername[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>]" value="<?php echo wp_kses_post($ComponentSubList->componentname); ?>">
					</div>
					<?php 
				}
				?>
				</div>
				<div class="componentsub_end"><td colspan="2" style="padding: 0px;"><div class="product_atributes" style="padding: 0px;height: 5px;">&nbsp;</div></div></div>
				<?php 
				$component_sub_html.=ob_get_contents();
				ob_end_clean();
			}else{
			ob_start();
			?>
				<div data-role="collapsible" class="componentsubcontainer configurator-option border componentsub_<?php echo wp_kses_post($ComponentSubList->parameterid); ?>" role="presentation" data-collapsible="true">
				<div class="label configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
				   
						<label for="<?php echo wp_kses_post($ComponentSubList->componentname); ?>"><?php echo wp_kses_post($ComponentSubList->componentname); ?><?php echo wp_kses_post($mandatory); ?></label>
					
				</div>
				<span id="errormsg_<?php echo wp_kses_post($ComponentSubList->priceid); ?>" data-text-color="alert" class="is-small errormsg"></span>		 
			<?php
			$content = ob_get_contents();
			ob_end_clean();
			$component_sub_html .= $content;
			
			if ('1' == $ComponentSubList->fixedorpercentage || '15' ==$ComponentSubList->fixedorpercentage) {
				
				$class3 = 'componentsub';
				if ('15' == $ComponentSubList->fixedorpercentage) {
					$class3 = 'componentsuballowance';
				}
				
				$sub_value = '';
				if (!empty($ComponentSubList->ComponentSubvalue)) {
					foreach ($ComponentSubList->ComponentSubvalue as $componentsubvalue) {
						
						$data_position = strtolower($componentsubvalue->parametername);
						$data_value = strtolower($componentsubvalue->parametername);
						$data_id = 'main-componentsub-' . $componentsubvalue->componentsubid;
						if (false !== strpos(strtolower($componentsubvalue->parametername), 'left') ) {
							$data_position = 'left';
							$data_value = 'single_left';
							$data_id = 'positionsingle_left';
						}
						if (false !== strpos(strtolower($componentsubvalue->parametername), 'right')) {
							$data_position = 'right';
							$data_value = 'single_right';
							$data_id = 'positionsingle_right';
						}
						if (false !== strpos(strtolower($componentsubvalue->parametername), 'pair') || false !== strpos(strtolower($componentsubvalue->parametername), 'center')) {
							$data_position = 'center';
							$data_value = 'pair';
							$data_id = 'positionpair';
						}
						if (false !== strpos(strtolower($ComponentSubList->componentname), 'ratio')) {
							$exp_radio = explode('/', $componentsubvalue->parametername);
							$data_id = 'border_ratios' . trim($exp_radio[1]);
							$data_value = trim($exp_radio[1]);
							$data_position = trim($exp_radio[1]);
							$click_function = 'borderratio(this);';
							$class3 = 'action primary borderratio';
						}
						if (false !== strpos(strtolower($ComponentSubList->componentname), 'fabric') ) {
							$data_id = 'main-componentsubfabric-' . $componentsubvalue->componentsubid;
							$data_value = $componentsubvalue->componentsubid;
						}
						
						$selected='';
						$checked='';
						if (1 == $componentsubvalue->defaultValue) {
							$selected = 'selected';
							$checked = 'checked';
						}
						
						$sel_multiple_input = 'radio';
						if (1 == $ComponentSubList->component_sub_select_option) {
							$sel_multiple_input = 'checkbox';
						}
						
						
						$datasubval = $componentsubvalue->sellingprice . '~' . $componentsubvalue->parametername . '~' . $componentsubvalue->componentprice . '~' . $componentsubvalue->componentsubid;
						
						ob_start();
						?>
						<input {$checked} class="showorderdetails blindsradio <?php echo wp_kses_post($mandatory_class1); ?>" parametername="<?php echo wp_kses_post($ComponentSubList->componentname); ?>" getparametervalue="<?php echo wp_kses_post($componentsubvalue->parametername); ?>" data-getparameterid="<?php echo wp_kses_post($ComponentSubList->priceid); ?>" getparameterid="<?php echo wp_kses_post($ComponentSubList->priceid); ?>" radiobutton="Componentsubvalue[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>][]" type="<?php echo wp_kses_post($sel_multiple_input); ?>" name="Componentsubvalue[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>][]" id="<?php echo wp_kses_post($data_id); ?>" value="<?php echo wp_kses_post($datasubval); ?>" onclick="<?php echo wp_kses_post($calculate_subcomponent); ?>" allowance="<?php echo wp_kses_post($componentsubvalue->allowance); ?>" price="<?php echo wp_kses_post($componentsubvalue->sellingprice); ?>" data-sub="<?php echo wp_kses_post($data_value); ?>">
						<label data-ratio="<?php echo wp_kses_post($data_value); ?>" data-position="<?php echo wp_kses_post($data_position); ?>" class="blindslabel <?php echo wp_kses_post($sel_multiple_input); ?>  option-item <?php echo wp_kses_post($class3); ?> <?php echo wp_kses_post($selected); ?>" for="<?php echo wp_kses_post($data_id); ?>"><?php echo wp_kses_post($componentsubvalue->parametername); ?></label>
						<input type="hidden" name="ComponentSubParametername[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>]" value="<?php echo wp_kses_post($ComponentSubList->componentname); ?>">
						<?php
						$content = ob_get_contents();
						ob_end_clean();
						$sub_value .=$content;
					}
				}
				
				ob_start();
				?>
				<div class="value" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
					<div class="option-grid ratio value <?php echo wp_kses_post($mandatory_class1); ?>">
						<?php echo wp_kses_post($sub_value); ?>
					</div>
				</div>	
				<?php
				$content = ob_get_contents();
				ob_end_clean();
				$component_sub_html .=$content;
			} elseif ('11' == $ComponentSubList->fixedorpercentage) {
				ob_start();
				?>
				<div class="configurator-option-content value" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
					<input parametername="<?php echo wp_kses_post($ComponentSubList->componentname); ?>" data-getparameterid="<?php echo wp_kses_post($ComponentSubList->priceid); ?>" getparameterid="<?php echo wp_kses_post($ComponentSubList->priceid); ?>" id="<?php echo wp_kses_post($ComponentSubList->componentname); ?>" name="Componentsubvalue[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>][]" class="<?php echo wp_kses_post($mandatory_class); ?> border border-1  showorderdetails border-silver white-back border-radius-10 othersvalue" type="number" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" onkeyup="showorderdetails();<?php echo wp_kses_post($calculate_subcomponent); ?>" onkeydown="showorderdetails();<?php echo wp_kses_post($calculate_subcomponent); ?>">
					<input type="hidden" name="ComponentSubParametername[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>]" value="<?php echo wp_kses_post($ComponentSubList->componentname); ?>">
				</div>	
				<?php
				$content = ob_get_contents();
				ob_end_clean();
				$component_sub_html .=$content;
			} elseif ( '12' == $ComponentSubList->fixedorpercentage) {
				ob_start();
				?>
				<div class="configurator-option-content value" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
					<input parametername="<?php echo wp_kses_post($ComponentSubList->componentname); ?>" data-getparameterid="<?php echo wp_kses_post($ComponentSubList->priceid); ?>" getparameterid="<?php echo wp_kses_post($ComponentSubList->priceid); ?>" id="<?php echo wp_kses_post($ComponentSubList->componentname); ?>" name="Componentsubvalue[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>][]" class="<?php echo wp_kses_post($mandatory_class); ?> border border-1 showorderdetails border-silver white-back border-radius-10 othersvalue" type="text" onkeyup="showorderdetails();<?php echo wp_kses_post($calculate_subcomponent); ?>" onkeydown="showorderdetails();<?php echo wp_kses_post($calculate_subcomponent); ?>">
					<input type="hidden" name="ComponentSubParametername[<?php echo wp_kses_post($ComponentSubList->parameterid); ?>][<?php echo wp_kses_post($ComponentSubList->priceid); ?>]" value="<?php echo wp_kses_post($ComponentSubList->componentname); ?>">
				</div>	
				<?php
				$content = ob_get_contents();
				ob_end_clean();
				$component_sub_html .=$content;
			}

			$component_sub_html .='</div>';
		}
	  }
	}
	
	$json_response['result'] = $response;
	$json_response['ComponentSubList'] = $component_sub_html;
	$json_response['component_id'] =  $component_id;
	echo wp_json_encode($json_response);
	exit;
}

if ('login' == $_mode) {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$useremail = isset($_REQUEST['useremail']) ? wc_clean(wp_unslash($_REQUEST['useremail'])):'';
	$password = isset($_REQUEST['password']) ? wc_clean(wp_unslash($_REQUEST['password'])):'';
	$rememberme = isset($_REQUEST['rememberme']) ? wc_clean(wp_unslash($_REQUEST['rememberme'])):'';
	
	$json_response = CallAPI('POST', array('mode'=>'login', 'Email'=>$useremail, 'Password'=>$password, 'chkRememberMe'=>$rememberme));
	
	if ($json_response->customerid > 0) {
		unset($_SESSION['guestcustomerid']);
		$_SESSION['customerid'] = $json_response->customerid;
		$_SESSION['name'] = $json_response->FirstName . ' ' . $json_response->LastName;
		$_SESSION['FirstName'] = $json_response->FirstName;
		$_SESSION['LastName'] = $json_response->LastName;
		$_SESSION['Email'] = $json_response->Email;
		$_SESSION['MobileNumber'] = $json_response->MobileNumber;
		$_SESSION['apiuserkey'] = $json_response->apiuserkey;
		$_SESSION['chkMarketingOptOut'] = $json_response->chkMarketingOptOut;
		$cart = isset($_SESSION['cart']) ? wc_clean(wp_unslash($_SESSION['cart'])):''; 
		if ('' == $cart) {
			$_SESSION['cart'] = json_decode($json_response->ecommerce_cart, true) ;
		}
		$delivery_charges = isset($_SESSION['delivery_charges']) ? wc_clean(wp_unslash($_SESSION['delivery_charges'])):'';
		if ('' == $delivery_charges && is_array($delivery_charges) && count($delivery_charges) > 0) {
			$_SESSION['delivery_charges'] = $json_response->ecommerce_cart_delcost;
		}
		
		if (!empty($_REQUEST['rememberme'])) {
			$useremail = isset($_REQUEST['useremail']) ? wc_clean(wp_unslash($_REQUEST['useremail'])):'';
			$password = isset($_REQUEST['password']) ? wc_clean(wp_unslash($_REQUEST['password'])):'';
			setcookie ('member_login', $useremail, time()+ ( 10 * 365 * 24 * 60 * 60 ), '/');
			setcookie ('member_password', $password, time()+ ( 10 * 365 * 24 * 60 * 60 ), '/');
		} else {
			$cookie = $_COOKIE;
			if (isset($cookie['member_login'])) {
				setcookie ('member_login', '', time()-10, '/');
			}
			if (isset($cookie['member_password'])) {
				setcookie ('member_password', '', time()-10, '/');
			}
		}
		$cart = isset($_SESSION['cart']) ? wc_clean(wp_unslash($_SESSION['cart'])):'';
		$return_session = cart($cart);
		
		$json_response->Basketcount = count($cart);
	}
}

if ('RegistrationForm' == $_mode) {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$FirstName 			= isset($_REQUEST['FirstName']) ? wc_clean(wp_unslash($_REQUEST['FirstName'])):'';
	$LastName 			= isset($_REQUEST['LastName']) ? wc_clean(wp_unslash($_REQUEST['LastName'])):'';
	$MobileNumber 		= isset($_REQUEST['MobileNumber']) ? wc_clean(wp_unslash($_REQUEST['MobileNumber'])):'';
	$Email 				= isset($_REQUEST['Email']) ? wc_clean(wp_unslash($_REQUEST['Email'])):'';
	$Password 			= isset($_REQUEST['Password']) ? wc_clean(wp_unslash($_REQUEST['Password'])):'';
	$ConfirmPassword 	= isset($_REQUEST['ConfirmPassword']) ? wc_clean(wp_unslash($_REQUEST['ConfirmPassword'])):'' ;
	
	$json_response = CallAPI('POST', array('mode'=>'register', 'FirstName'=>$FirstName, 'LastName'=>$LastName, 'MobileNumber'=>$MobileNumber, 'Email'=>$Email, 'Password'=>$Password, 'ConfirmPassword'=>$ConfirmPassword));
	
	if ($json_response->customerid > 0) {
		unset($_SESSION['guestcustomerid']);
		$_SESSION['customerid'] = $json_response->customerid;
		$_SESSION['name'] = $json_response->FirstName . ' ' . $json_response->LastName;
		$_SESSION['FirstName'] = $json_response->FirstName;
		$_SESSION['LastName'] = $json_response->LastName;
		$_SESSION['Email'] = $json_response->Email;
		$_SESSION['MobileNumber'] = $json_response->MobileNumber;
		$_SESSION['apiuserkey'] = $json_response->apiuserkey;
	}
}

if ('GuestForm' == $_mode) {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$FirstName 			= isset($_REQUEST['FirstName']) ? wc_clean(wp_unslash($_REQUEST['FirstName'])):'';
	$LastName 			= isset($_REQUEST['LastName']) ? wc_clean(wp_unslash($_REQUEST['LastName'])):'';
	$MobileNumber 		= isset($_REQUEST['MobileNumber']) ? wc_clean(wp_unslash($_REQUEST['MobileNumber'])):'';
	$Email 				= isset($_REQUEST['Email']) ? wc_clean(wp_unslash($_REQUEST['Email'])):'';
	
	$json_response = CallAPI('POST', array('mode'=>'guestlogin', 'FirstName'=>$FirstName, 'LastName'=>$LastName, 'MobileNumber'=>$MobileNumber, 'Email'=>$Email));
	
	if ($json_response->customerid > 0) {
		unset($_SESSION['customerid']);
		$_SESSION['guestcustomerid'] = $json_response->customerid;
		$_SESSION['name'] = $json_response->FirstName . ' ' . $json_response->LastName;
		$_SESSION['FirstName'] = $json_response->FirstName;
		$_SESSION['LastName'] = $json_response->LastName;
		$_SESSION['Email'] = $json_response->Email;
		$_SESSION['MobileNumber'] = $json_response->MobileNumber;
		$_SESSION['apiuserkey'] = $json_response->apiuserkey;
	}
}

if ('ResetPassword' == $_mode) {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	$_user_login = isset($_REQUEST['user_login']) ? wc_clean(wp_unslash($_REQUEST['user_login'])):'';
	$json_response = CallAPI('GET', array('mode'=>'forgotpassword', 'CustomerEmail'=>$_user_login, 'siteurl'=>get_bloginfo('url')));
}

if ('Logout' == $_mode) {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$customerid = isset($_REQUEST['customerid']) ? wc_clean(wp_unslash($_REQUEST['customerid'])):'' ;

	$cart = isset($_SESSION['cart']) ? wc_clean(wp_unslash($_SESSION['cart'])):'';
	$ecommerce_cart = wp_json_encode($cart);
	$delivery_charges = isset($_SESSION['delivery_charges']) ? wc_clean(wp_unslash($_SESSION['delivery_charges'])):'';
	$delcost = $delivery_charges;
	
	$json_response = CallAPI('POST', array('mode'=>'Logout', 'customerid'=>$customerid,'deliverycharges'=>$delcost,'ecommercecart'=>$ecommerce_cart));
	
	if (true == $json_response->success) {
		session_unset();
		session_destroy();
	}
}

if ('changedetails' == $_mode) {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	$customer_id = isset($_REQUEST['customerid']) ? wc_clean(wp_unslash($_REQUEST['customerid'])):'';
	$CustomerFirstname = isset($_REQUEST['CustomerFirstname']) ? wc_clean(wp_unslash($_REQUEST['CustomerFirstname'])):'';
	$CustomerSurname = isset($_REQUEST['CustomerSurname']) ? wc_clean(wp_unslash($_REQUEST['CustomerSurname'])):'';
	$CustomerEmail = isset($_REQUEST['CustomerEmail']) ? wc_clean(wp_unslash($_REQUEST['CustomerEmail'])):'';
	$Email = isset($_REQUEST['Email']) ? wc_clean(wp_unslash($_REQUEST['Email'])):'';
	$CustomerTel = isset($_REQUEST['CustomerTel']) ? wc_clean(wp_unslash($_REQUEST['CustomerTel'])):'';
	$CustomerCompany = isset($_REQUEST['CustomerCompany']) ? wc_clean(wp_unslash($_REQUEST['CustomerCompany'])):'';
	$CustomerAddress = isset($_REQUEST['CustomerAddress']) ? wc_clean(wp_unslash($_REQUEST['CustomerAddress'])):'';
	$CustomerAddress2 = isset($_REQUEST['CustomerAddress2']) ? wc_clean(wp_unslash($_REQUEST['CustomerAddress2'])):'';
	$CustomerCity = isset($_REQUEST['CustomerCity']) ? wc_clean(wp_unslash($_REQUEST['CustomerCity'])):'';
	$CustomerCounty = isset($_REQUEST['CustomerCounty']) ? wc_clean(wp_unslash($_REQUEST['CustomerCounty'])):'';
	$CustomerPostcode = isset($_REQUEST['CustomerPostcode']) ? wc_clean(wp_unslash($_REQUEST['CustomerPostcode'])):'';
	$CustomerCountryId = isset($_REQUEST['CustomerCountryId']) ? wc_clean(wp_unslash($_REQUEST['CustomerCountryId'])):'';
	$json_response = CallAPI('POST', array('mode'=>'changedetails', 'customerid'=>$customer_id, 'CustomerFirstname'=>$CustomerFirstname, 'CustomerSurname'=>$CustomerSurname, 'CustomerEmail'=>$CustomerEmail, 'Email'=>$Email, 'CustomerTel'=>$CustomerTel, 'CustomerCompany'=>$CustomerCompany, 'CustomerAddress'=>$CustomerAddress, 'CustomerAddress2'=>$CustomerAddress2, 'CustomerCity'=>$CustomerCity, 'CustomerCounty'=>$CustomerCounty, 'CustomerPostcode'=>$CustomerPostcode, 'CustomerCountryId'=>$CustomerCountryId));
}

if ( 'ChangePassword' == $_mode) {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$customerid				= isset($_REQUEST['customerid']) ? wc_clean(wp_unslash($_REQUEST['customerid'])):'' ;
	$CustomerPassword 		= isset($_REQUEST['CustomerPassword']) ? wc_clean(wp_unslash($_REQUEST['CustomerPassword'])):'';
	$CustomerPasswordAgain	= isset($_REQUEST['CustomerPasswordAgain']) ? wc_clean(wp_unslash($_REQUEST['CustomerPasswordAgain'])):'';
	
	$json_response = CallAPI('POST', array('mode'=>'PasswordChange', 'customerid'=>$customerid, 'CustomerPassword'=>$CustomerPassword, 'CustomerPasswordAgain'=>$CustomerPasswordAgain));
}

if ('getAlternateDeliveryAddress' == $_mode) {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$accountid = isset($_REQUEST['accountid']) ? wc_clean(wp_unslash($_REQUEST['accountid'])):'';
	$_id	= isset($_REQUEST['id']) ? wc_clean(wp_unslash($_REQUEST['id'])):'';
	$json_response = CallAPI('POST', array('mode'=>'getAlternateDeliveryAddress', 'accountid'=>$accountid, 'id'=>$_id));
}

if ('place_order' == $_mode) {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$delivery_charges = isset($_SESSION['delivery_charges']) ? wc_clean(wp_unslash($_SESSION['delivery_charges'])):'';
	$order_item_val = isset($_REQUEST['orderitemval']) ? wc_clean(wp_unslash($_REQUEST['orderitemval'])):'';
	$dataString = serialize($order_item_val);

	$ocustomerid = isset($_REQUEST['customerid']) ? wc_clean(wp_unslash($_REQUEST['customerid'])):'';
	$salesorderid = isset($_REQUEST['salesorderid']) ? wc_clean(wp_unslash($_REQUEST['salesorderid'])):'';
	$billing_email = isset($_REQUEST['billing_email']) ? wc_clean(wp_unslash($_REQUEST['billing_email'])):'';
	$billing_first_name = isset($_REQUEST['billing_first_name']) ? wc_clean(wp_unslash($_REQUEST['billing_first_name'])):'';
	$billing_last_name = isset($_REQUEST['billing_last_name']) ? wc_clean(wp_unslash($_REQUEST['billing_last_name'])):'';
	$billing_company = isset($_REQUEST['billing_company']) ? wc_clean(wp_unslash($_REQUEST['billing_company'])):'';
	$billing_address_1 = isset($_REQUEST['billing_address_1']) ? wc_clean(wp_unslash($_REQUEST['billing_address_1'])):'';
	$billing_address_2 = isset($_REQUEST['billing_address_2']) ? wc_clean(wp_unslash($_REQUEST['billing_address_2'])):'';
	$billing_city = isset($_REQUEST['billing_city']) ? wc_clean(wp_unslash($_REQUEST['billing_city'])):'';
	$billing_postcode = isset($_REQUEST['billing_postcode']) ? wc_clean(wp_unslash($_REQUEST['billing_postcode'])):'';
	$billing_county = isset($_REQUEST['billing_county']) ? wc_clean(wp_unslash($_REQUEST['billing_county'])):'';
	$billing_phone = isset($_REQUEST['billing_phone']) ? wc_clean(wp_unslash($_REQUEST['billing_phone'])):'';
	$ship_diff = isset($_REQUEST['ship_diff']) ? wc_clean(wp_unslash($_REQUEST['ship_diff'])):'';
	$shipping_first_name = isset($_REQUEST['shipping_first_name']) ? wc_clean(wp_unslash($_REQUEST['shipping_first_name'])):'';
	$shipping_last_name = isset($_REQUEST['shipping_last_name']) ? wc_clean(wp_unslash($_REQUEST['shipping_last_name'])):'';
	$shipping_company = isset($_REQUEST['shipping_company']) ? wc_clean(wp_unslash($_REQUEST['shipping_company'])):'';
	$shipping_address_1 = isset($_REQUEST['shipping_address_1']) ? wc_clean(wp_unslash($_REQUEST['shipping_address_1'])):'';
	$shipping_address_2 = isset($_REQUEST['shipping_address_2']) ? wc_clean(wp_unslash($_REQUEST['shipping_address_2'])):'';
	$shipping_city = isset($_REQUEST['shipping_city']) ? wc_clean(wp_unslash($_REQUEST['shipping_city'])):'';
	$shipping_county = isset($_REQUEST['shipping_county']) ? wc_clean(wp_unslash($_REQUEST['shipping_county'])):'';
	$shipping_postcode = isset($_REQUEST['shipping_postcode']) ? wc_clean(wp_unslash($_REQUEST['shipping_postcode'])):'';
	$shipping_phone = isset($_REQUEST['shipping_phone']) ? wc_clean(wp_unslash($_REQUEST['shipping_phone'])):'';
	$shipping_country = isset($_REQUEST['shipping_country']) ? wc_clean(wp_unslash($_REQUEST['shipping_country'])):'';
	$AlternateDeliveryAddressID = isset($_REQUEST['AlternateDeliveryAddressID']) ? wc_clean(wp_unslash($_REQUEST['AlternateDeliveryAddressID'])):'';
	
	$json_response = CallAPI('POST', array('mode'=>'place_order', 'customerid'=>$customerid, 'salesorderid'=>$salesorderid, 'billing_email'=>$billing_email, 'billing_first_name'=>$billing_first_name, 'billing_last_name'=>$billing_last_name, 'billing_company'=>$billing_company, 'billing_address_1'=>$billing_address_1, 'billing_address_2'=>$billing_address_2, 'billing_city'=>$billing_city, 'billing_county'=>$billing_county, 'billing_postcode'=>$billing_postcode, 'billing_phone'=>$billing_phone, 'billing_country'=>$billing_county, 'ship_diff'=>$ship_diff, 'shipping_first_name'=>$shipping_first_name, 'shipping_last_name'=>$shipping_last_name, 'shipping_company'=>$shipping_company, 'shipping_address_1'=>$shipping_address_1, 'shipping_address_2'=>$shipping_address_2, 'shipping_city'=>$shipping_city, 'shipping_county'=>$shipping_county, 'shipping_postcode'=>$shipping_postcode, 'shipping_phone'=>$shipping_phone, 'shipping_country'=>$shipping_country, 'AlternateDeliveryAddressID'=>$AlternateDeliveryAddressID, 'delivery_charges'=>$delivery_charges, 'orderitemval'=>$dataString));
	
	$_SESSION['salesorderid'] = $json_response->salesorderid;
	$_SESSION['salesorder_no'] = $json_response->salesorder_no;
	
	if ($json_response->salesorderid > 0) {
		$_SESSION['sq_total_amount']='';
		$sq_total_amount = 0;
		if (count($json_response->orderinformation) > 0) {
			foreach ($json_response->orderinformation as $orderinformation_price) {
				$sq_total_amount += $orderinformation_price->price;
			}
			$delivery_charges_vat = isset($_SESSION['delivery_charges_vat']) ? wc_clean(wp_unslash($_SESSION['delivery_charges_vat'])):'';
			$sq_total_amount += $delivery_charges_vat;
			$_SESSION['sq_total_amount'] = $sq_total_amount;
			$_SESSION['orderinformation'] = $json_response->orderinformation;
		}
	}			
	
}


function bm_eco_fabriclist() {
	$html = '';
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$productcode = isset($_REQUEST['productcode']) ? wc_clean(wp_unslash($_REQUEST['productcode'])):'' ;
	$search_text = isset($_REQUEST['search_text']) ? wc_clean(wp_unslash($_REQUEST['search_text'])):'';
	$search_type = isset($_REQUEST['search_type']) ? wc_clean(wp_unslash($_REQUEST['search_type'])):'';
	$sort = isset($_REQUEST['sort']) ? wc_clean(wp_unslash($_REQUEST['sort'])):'' ;
	$_page = isset($_REQUEST['page']) ? wc_clean(wp_unslash($_REQUEST['page'])):'';
	$_per_page = isset($_REQUEST['per_page']) ? wc_clean(wp_unslash($_REQUEST['per_page'])):'';
	$categoryarray = isset($_REQUEST['categoryarray']) ? wc_clean(wp_unslash($_REQUEST['categoryarray'])):array() ;
	$sellistby = isset($_REQUEST['sellistby']) ? wc_clean(wp_unslash($_REQUEST['sellistby'])):'';
	
	$getproductdetailresponse = CallAPI('GET', array('mode'=>'getproductdetail', 'productcode'=>$productcode));

	if (blindmatrix_check_premium()) {
		$response = CallAPI('GET', array('mode'=>'fabriclist', 'productcode'=>$productcode, 'search_text'=>$search_text, 'search_type'=>$search_type, 'sort'=>$sort, 'page'=>$_page, 'rows'=>$_per_page, 'categoryarray'=>$categoryarray, 'sellistby'=>$sellistby));
	} else {
		$response = CallAPI('GET', array('mode'=>'fabriclist', 'productcode'=>$productcode, 'search_text'=>$search_text, 'search_type'=>$search_type, 'sort'=>$sort, 'page'=>1, 'rows'=>50, 'categoryarray'=>$categoryarray, 'sellistby'=>$sellistby));	
	}
	
	$fabric_list = is_object($response) && isset($response->fabric_list) ? $response->fabric_list:'';
	$json_response['query'] = is_object($response) && isset($response->query) ? $response->query : '';
	$json_response['total_pages'] = is_object($response) && isset($response->total_pages) ? $response->total_pages:'';
	//$json_response['fabric_list'] = $fabric_list;
	$json_response['total_rows'] = is_object($response) && isset($response->total_rows) ? $response->total_rows:'';
	$search_text_arr = array();
	$json_response['search_text_arr'] = $search_text_arr;
	$json_response['searcharrays'] = is_object($response) && isset($response->searcharrays) ? $response->searcharrays:'';
	
	if (blindmatrix_check_premium()) {
		$page = isset($_REQUEST['page']) ? wc_clean(wp_unslash($_REQUEST['page'])):'';
		$json_response['pagination_html'] = pagination($page, $_per_page, $response->total_rows);
	} else {
		$json_response['pagination_html'] = '';
	}
	
	if (blindmatrix_check_premium()) {
		$json_response['total_rows'] = $response->total_rows;
	} else {
		$total_rows = $response->total_rows;
		$json_response['total_rows'] = $total_rows < 50 ? $total_rows:50;
	}

	if (count($fabric_list) > 0) {
		$main_category_printed = array();
		$prevCategorry='';
		//for ($i = 0; $i < $_per_page; $i++){
		foreach ($fabric_list as $key=>$fabriclist) {	
		
			if (1 == $fabriclist->skipcolorfield) {
				$urlfcname = $fabriclist->colorname;
			} else {
				$fabric_name = isset($fabriclist->fabricname) ? $fabriclist->fabricname:'';
				$urlfcname = $fabric_name . '-' . $fabriclist->colorname;
			}
		
			if ('listbyfabric' == $sellistby) {
				$fabriclist->colorid = 0;
				$urlfcname = $fabriclist->colorname;
			}
		
			$productnamearr = explode('(', $fabriclist->productname);
			$get_productname = trim($productnamearr[0]);
		
			//$productviewurl = get_bloginfo('url').'/'.$blinds_config.'/'.str_replace(' ','-',strtolower($get_productname)).'/'.str_replace(' ','-',strtolower($urlfcname)).'/';
			//$productviewurl = get_bloginfo('url').'/'.$blinds_config.'/'.str_replace(' ','-',strtolower($fabriclist->productname)).'/'.str_replace(' ','-',strtolower($urlfcname)).'/?pc='.safe_encode($productcode).'&ptid='.safe_encode($fabriclist->producttypeid).'&fid='.safe_encode($fabriclist->fabricid).'&cid='.safe_encode($fabriclist->colorid).'&vid='.safe_encode($fabriclist->vendorid);
		
			$urlproname = str_replace(' ', '-', strtolower($fabriclist->productname));
			$urlfcname = str_replace(' ', '-', strtolower($urlfcname));
		
			$newurl = safe_encode($productcode . '/' . $fabriclist->producttypeid . '/' . $fabriclist->fabricid . '/' . $fabriclist->colorid . '/' . $fabriclist->vendorid);
		
			//$productviewurl = get_bloginfo('url').'/'.$blinds_config.'/'.$urlproname.'/'.$urlfcname.'/?pc='.safe_encode($productcode).'&ptid='.safe_encode($fabriclist->producttypeid).'&fid='.safe_encode($fabriclist->fabricid).'&cid='.safe_encode($fabriclist->colorid).'&vid='.safe_encode($fabriclist->vendorid);
			$productviewurl = get_bloginfo('url') . '/' . $blinds_config . '/' . $urlproname . '/' . $urlfcname . '/' . $newurl . '/';
	
			$main_category_name = $fabriclist->main_category_name;
	
			if ($prevCategorry != $fabriclist->main_category_name) {
				$prevCategorry = $fabriclist->main_category_name;	
				ob_start();
				?>
				<div class="box has-hover   has-hover box-text-bottom">
					<div class="box-text text-center">
						<div class="box-text-inner">
							<h3 class="uppercase" style="text-align: left;"><?php echo wp_kses_post($fabriclist->main_category_name); ?></h3>
						<p style="text-align: left;"><?php echo wp_kses_post($fabriclist->main_category_description); ?></p>
						</div><!-- box-text-inner -->
					</div><!-- box-text -->
				</div>
	
				<div style="clear:both;"></div>	
				<?php
				$content = ob_get_contents();
				ob_end_clean();
				$category_title =$content;
	
			} else {
				$category_title='';
			}

			$orderItemId = $productcode . $fabriclist->producttypeid . $fabriclist->fabricid . $fabriclist->colorid . $fabriclist->vendorid;
			ob_start();
			?>
			<a class="sample_addtocart_container" id="<?php echo wp_kses_post($orderItemId); ?>" style="display:block;margin:5px 0 !important" href="javascript:;" onclick="sampleOrder(this,'<?php echo wp_kses_post($productcode); ?>','<?php echo wp_kses_post($fabriclist->producttypeid); ?>','<?php echo wp_kses_post($fabriclist->fabricid); ?>','<?php echo wp_kses_post($fabriclist->colorid); ?>','<?php echo wp_kses_post($fabriclist->vendorid); ?>')" class="button primary is-outline box-shadow-2 box-shadow-2-hover">
				<span style="padding: 0px !important;margin:5px 0 !important">Free Sample</span>
			</a>		
			<?php
			$content = ob_get_contents();
			ob_end_clean();
			$sampleButton =$content;
			$cart = isset($_SESSION['cart']) ? wc_clean(wp_unslash($_SESSION['cart'])):'';
			if ($cart && is_array($cart) && count($cart) > 0) {
				if (false !== array_search($orderItemId, array_column($cart, 'sampleOrderItemId'))) {
					ob_start();
					?>
					<a class="sample_addtocart_container" id="<?php echo wp_kses_post($orderItemId); ?>" style="display:block;margin:5px 0 !important" href="javascript:;" onclick="sampleOrder(this,'<?php echo wp_kses_post($productcode); ?>','<?php echo wp_kses_post($fabriclist->producttypeid); ?>','<?php echo wp_kses_post($fabriclist->fabricid); ?>','<?php echo wp_kses_post($fabriclist->colorid); ?>','<?php echo wp_kses_post($fabriclist->vendorid); ?>')" class="button primary is-outline box-shadow-2 box-shadow-2-hover">
						<i class="icon-checkmark"></i>
						<span style="padding: 0px !important;">Sample Added</span>
					</a>
					<?php
					$content = ob_get_contents();
					ob_end_clean();
					$sampleButton =$content;
				}
			}
		
			if ( '0' == $fabriclist->ecommerce_sample) {
				$sampleButton = '';
			}
		
			if ('' != $fabriclist->imagepath ) {
			
				$productimagepath = $fabriclist->imagepath;
				//$productimagepath = replace_fabric_color_path($fabriclist->imagepath);
				$productframeimagepath = $fabriclist->getproductframeimage;
				$productframeimagepathtag = '<img src="' . $productframeimagepath . '" class="product-frame" style="position:absolute;z-index:1;width: 100%;height: 100%;object-fit: fill;">';
				//$productframeimagepath = replace_fabric_color_path($fabriclist->getproductframeimage);
				$option_blindmatrix_settings = get_option( 'option_blindmatrix_settings' );
				if ( 'checked' == $option_blindmatrix_settings['seasonal_image_check']) {
		
					$image_id = isset( $option_blindmatrix_settings['seasonal_image_img'] ) ? esc_attr( $option_blindmatrix_settings['seasonal_image_img']) : '';
					$image = wp_get_attachment_image_src( $image_id , 'full' );
					$offericonpath = $image[0];
					$offerswatchimg ='';
				} else {
					$offericonpath = '';
					$offerswatchimg = 'display:none;';
				}
				$swatch_img_class = 'swatch-img';
				$swatchimg = '';
			
				if($fabriclist->getproductframeimage == ''){
					$imgdiv = '<img src="'.$productimagepath.'" class=" product-frame frame_backgound" >';
				}else{
					$style = "background-image:url('$productimagepath');background-repeat: no-repeat;width: 100%;height: 100%;background-size: contain;";
					$imgdiv = sprintf('<img src="%s" class="product-frame frame_backgound" style="%s">',$productframeimagepath,$style);
				}
			} else {
				$productimagepath = untrailingslashit( plugins_url( '/', __FILE__ ) ) . '/vendor/Shortcode-Source/image/blinds/no-image.jpg';
				$productframeimagepath = '';
				$productframeimagepathtag = '';
				$offericonpath = '';
				$swatchimg = 'display:none;';
				$offerswatchimg = 'display:none;';
				$swatch_img_class = '';
				$imgdiv = '<img src="'.$productimagepath.'" class="product-frame frame_backgound">';
			}
			if ('' == $fabriclist->getproductframeimage) {
				$productframeimagepath = '';
			}
		
			if ('listbyfabric' == $sellistby) {
				$swatchimg = 'display:none;';
				$productframeimagepathtag = '';
				$sampleButton = '';
			}
		
			$extra_value='';
			if ($fabriclist->extra_offer > 0) {
				$extra_offer = $fabriclist->extra_offer;
				ob_start();
				?>
				<div class="badge-container absolute left top z-1 badege-view-page" >
					<div class="callout badge badge-circle product-list-page"><div class="badge-inner secondary on-sale"><span class="extra-text">Flat</span><br><span class="productlist_extra-val"><?php echo wp_kses_post($extra_offer); ?><span> %</span></span><br><span class="sale-value">Sale</span></div></div>
				</div>	
				<?php
				$content = ob_get_contents();
				ob_end_clean();
				$extra_value =$content;
			}
		
			$del = '';
			$inline_style= '';
			$extra_offer_val = absint($fabriclist->extra_offer);
			if (0 != $extra_offer_val) :		
				$percent = 100 - $extra_offer_val;
				$total_price = ( floatval($fabriclist->price)/$percent )*100;
				if (floatval($fabriclist->price) != $total_price) {
					$currency_symbol = get_woocommerce_currency_symbol();
					$del = sprintf('<del aria-hidden="true" style="margin: 5px 0;"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">%s</span>%s</bdi></span></del>', $currency_symbol, number_format($total_price, 2));	
					$inline_style = "style='display: block;margin: 5px 20px;'";
				}
			endif;
			
			ob_start();
			?>
			 <?php echo wp_kses_post($category_title); ?>
		<div class="product-small col has-hover product type-product post-149 status-publish first instock product_cat-tops product_cat-women has-post-thumbnail shipping-taxable purchasable product-type-simple row-box-shadow-2 row-box-shadow-3-hover">
			<div class="col-inner">
				<div class="product-small box ">
					<div class="extra-off">
						<?php echo wp_kses_post($extra_value); ?>
	
					</div>
					<div class="box-image">
						<div class="image-fade_in_back">
							<a href="<?php echo wp_kses_post($productviewurl); ?>">
								<img class="offer-icon offer-position-bl" alt="" src="<?php echo esc_url($offericonpath); ?>" style="<?php echo wp_kses_post($offerswatchimg); ?>">
								<?php 
									$productframeimagepath = $fabriclist->getproductframeimage;			
									?>
									<?php echo ($imgdiv); ?>
								<img src="<?php echo esc_url($productimagepath); ?>" class="product-backround frame_backgound" style="display:none">
							</a>
						</div>
					</div>
					<?php 
					$fabric_name = isset($fabriclist->fabricname) ? $fabriclist->fabricname:'';
					?>
				  <div class="product-info-container" >
					   <div class="product details product-item-details">
						  <h2 class="product name product-item-name"><a class="product-item-link" href="<?php echo esc_url($productviewurl); ?>"><?php echo wp_kses_post($fabriclist->fabricname.' '.$fabriclist->colorname); ?></a></h2>
						  <a href="<?php echo esc_url($productviewurl); ?>" title="<?php echo wp_kses_post($fabriclist->fabricname.$fabriclist->colorname); ?>" class="action more"><span class="price-container price-price_from tax weee" style="<?php echo wp_kses_post($fabriclist->price >= 1 ? $fabriclist->price : 'visibility:hidden;'); ?>"><span id="product-price-30931" data-price-amount="9.9" data-price-type="priceFrom" class="price-wrapper "><span class="price"><?php echo wp_kses_post(get_woocommerce_currency_symbol().$fabriclist->price); ?> </span></span></span></a>
					   </div>
					   <div class="small-product-img" >
						  <a href="<?php echo esc_url($productviewurl); ?>" title="<?php echo wp_kses_post($fabriclist->fabricname.$fabriclist->colorname); ?>" class="action more">
							 <div class="product-image-container"  style="position: relative;">
								 <img alt="<?php echo wp_kses_post($fabriclist->fabricname.$fabriclist->colorname); ?>" src="<?php echo( plugin_dir_url(__FILE__) . '/assets/image/fabric.png'); ?>" width="100" height="100" style="background-image:url('<?php echo esc_url($fabriclist->imagepath); ?>');background-size: contain;<?php echo wp_kses_post($swatchimg); ?> width: auto;height: 80px;z-index: 1;position: relative;min-width: 80px;margin-right: 5px;float: right;background-color: #DDFFF7;" class="product-image-photo <?php echo wp_kses_post($swatch_img_class); ?> ">
		
							 </div>
															 
																		   
						  </a>
					   </div>
					</div>	   
					<a href="<?php echo esc_url($productviewurl); ?>" style="border-color: rgba(var(--bm-primary-color));color:#fff;padding: 0px 0.3em;font-size: 11px; margin: 0 !important;background-color: rgba(var(--bm-primary-color));" class="button primary is-outline box-shadow-2 box-shadow-2-hover relatedproduct">
						<i class="icon-shopping-cart"></i> <span style="padding: 0px !important;margin:5px 0 !important">Buy Now</span>
					</a>
					<?php echo($sampleButton); ?>
				</div>
			</div>
		</div>		
			<?php
			$content = ob_get_contents();
			ob_end_clean();
			$html.=$content;
		}
	} else {
		ob_start();
		?>
		<div class="container section-title-container text-center">
			<p>No products were found matching your selection.</p>
		</div>
		<div style="clear:both;"></div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();
		$html =$content;
	}
	
			$json_response['html'] = $html;
			echo wp_json_encode($json_response);
			exit;
}

function bm_eco_product_category() {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	//print_r('$main_category_name');
	$search_text = isset($_REQUEST['search_text']) ? wc_clean(wp_unslash($_REQUEST['search_text']) ):'';
	$search_type = isset($_REQUEST['search_type']) ? wc_clean(wp_unslash($_REQUEST['search_type'])):'';
	$sort = isset($_REQUEST['sort']) ? wc_clean(wp_unslash($_REQUEST['sort'])) :'';
	$_page = isset($_REQUEST['page']) ? wc_clean(wp_unslash($_REQUEST['page'])):'' ;
	$_per_page = isset($_REQUEST['per_page']) ? wc_clean(wp_unslash($_REQUEST['per_page'])) :'';
	
	$response = CallAPI('GET', array('mode'=>'searchecommerce', 'search_text'=>$search_text, 'search_type'=>$search_type, 'sort'=>$sort, 'page'=>$_page, 'rows'=>$_per_page));
	
	$fabric_list = $response->fabric_list;
	$json_response['total_pages'] = $response->total_pages;
	$json_response['total_rows'] = $response->total_rows;
	$json_response['search_text_arr'] = $search_text_arr;
	$_page = isset($_REQUEST['page']) ? wc_clean(wp_unslash($_REQUEST['page'])):''; 
	$json_response['pagination_html'] = pagination($_page, $_per_page, $response->total_rows);
	
	if (count($fabric_list) > 0) {
		$allproductsarray = array();
		$prevCategorry='';
	
		foreach ($fabric_list as $key=>$fabriclist) {	
		
			if (1 == $fabriclist->skipcolorfield) {
				$urlfcname = $fabriclist->colorname;
			} else {
				$urlfcname = $fabriclist->fabricname . '-' . $fabriclist->colorname;
			}
		
			$productnamearr = explode('(', $fabriclist->productname);
			$get_productname = trim($productnamearr[0]);
		
			//$productviewurl = get_bloginfo('url').'/'.$blinds_config.'/'.str_replace(' ','-',strtolower($get_productname)).'/'.str_replace(' ','-',strtolower($urlfcname)).'/';
			//$productviewurl = get_bloginfo('url') . '/' . $blinds_config . '/' . str_replace(' ', '-', strtolower($fabriclist->productname)) . '/' . str_replace(' ', '-', strtolower($urlfcname)) . '/?pc=' . safe_encode($fabriclist->product_no) . '&ptid=' . safe_encode($fabriclist->producttypeid) . '&fid=' . safe_encode($fabriclist->fabricid) . '&cid=' . safe_encode($fabriclist->colorid) . '&vid=' . safe_encode($fabriclist->vendorid);
			
			$urlproname = str_replace(' ', '-', strtolower($fabriclist->productname));
			$urlfcname = str_replace(' ', '-', strtolower($urlfcname));
			$productcode = $fabriclist->product_no;
		
			$newurl = safe_encode($productcode . '/' . $fabriclist->producttypeid . '/' . $fabriclist->fabricid . '/' . $fabriclist->colorid . '/' . $fabriclist->vendorid);
		
			//$productviewurl = get_bloginfo('url').'/'.$blinds_config.'/'.$urlproname.'/'.$urlfcname.'/?pc='.safe_encode($productcode).'&ptid='.safe_encode($fabriclist->producttypeid).'&fid='.safe_encode($fabriclist->fabricid).'&cid='.safe_encode($fabriclist->colorid).'&vid='.safe_encode($fabriclist->vendorid);
			$productviewurl = get_bloginfo('url') . '/' . $blinds_config . '/' . $urlproname . '/' . $urlfcname . '/' . $newurl . '/';

			$main_category_name = $fabriclist->productname;
	
			
			if ($prevCategorry != $fabriclist->productname) {
				$allproductsarray[] = $fabriclist;
				$prevCategorry = $fabriclist->productname;
				ob_start();
				?>
				<div class="container section-title-container">	
					<h3 class="section-title section-title-center" id="product_id_<?php echo wp_kses_post($fabriclist->productid); ?>"><span class="section-title-main"><?php echo wp_kses_post($fabriclist->productname); ?></span></h3>
					<p><?php echo wp_kses_post($fabriclist->main_category_description); ?></p>
				</div>
				<div style="clear:both;"></div>	
							<?php
							$content = ob_get_contents();
							ob_end_clean();
							$category_title =$content;
	
			} else {
				$category_title='';
			}

			$orderItemId = $fabriclist->product_no . $fabriclist->producttypeid . $fabriclist->fabricid . $fabriclist->colorid . $fabriclist->vendorid;
			ob_start();
			?>
			<a id="<?php echo wp_kses_post($orderItemId); ?>" style="display:block;margin:5px 0 !important" href="javascript:;" onclick="sampleOrder(this,'<?php echo wp_kses_post($fabriclist->product_no); ?>','<?php echo wp_kses_post($fabriclist->producttypeid); ?>','<?php echo wp_kses_post($fabriclist->fabricid); ?>','<?php echo wp_kses_post($fabriclist->colorid); ?>','<?php echo wp_kses_post($fabriclist->vendorid); ?>')" class="button primary is-outline box-shadow-2 box-shadow-2-hover">
				<span style="padding: 0px !important;margin:5px 0 !important;">Free Sample</span>
			</a>		
						<?php
						$content = ob_get_contents();
						ob_end_clean();
						$sampleButton = $content;
						$cart = isset($_SESSION['cart']) ? wc_clean(wp_unslash($_SESSION['cart'])):'';
						if (is_array($cart) && count($cart) > 0) {
							if (false !== array_search($orderItemId, array_column($cart, 'sampleOrderItemId')) ) {
								ob_start();
								?>
					<a id="<?php echo wp_kses_post($orderItemId); ?>" style="display:block;margin:5px 0 !important" href="javascript:;" onclick="sampleOrder(this,'<?php echo wp_kses_post($fabriclist->product_no); ?>','<?php echo wp_kses_post($fabriclist->producttypeid); ?>','<?php echo wp_kses_post($fabriclist->fabricid); ?>','<?php echo wp_kses_post($fabriclist->colorid); ?>','<?php echo wp_kses_post($fabriclist->vendorid); ?>')" class="button primary is-outline box-shadow-2 box-shadow-2-hover">
						<i class="icon-checkmark"></i>
						<span style="padding: 0px !important;margin:5px 0 !important;">Sample Added</span>
					</a>
								<?php
								$content = ob_get_contents();
								ob_end_clean();
								$sampleButton =$content;
							}
						}
	
						if ('0' == $fabriclist->ecommerce_sample) {
							$sampleButton = '';
						}
	
						//$offericonpath = get_stylesheet_directory_uri().'/icon/tree1234.png';
	
						if ('' != $fabriclist->imagepath) {
							$productimagepath = $fabriclist->imagepath;
							//$productimagepath = replace_fabric_color_path($fabriclist->imagepath);
							$productframeimagepath = $fabriclist->getproductframeimage;
							//$productframeimagepath = replace_fabric_color_path($fabriclist->getproductframeimage);
							$offericonpath = '';
							$swatchimg = '';
							$swatch_img_class = 'swatch-img';
							$option_blindmatrix_settings = get_option( 'option_blindmatrix_settings' );
							if ('checked' == $option_blindmatrix_settings['seasonal_image_check']) {
								$image_id = isset( $option_blindmatrix_settings['seasonal_image_img'] ) ? esc_attr( $option_blindmatrix_settings['seasonal_image_img']) : '';
								$image = wp_get_attachment_image_src( $image_id , 'full' );
								$offericonpath = $image[0];
								$offerswatchimg ='';
							} else {
								$offericonpath = '';
								$offerswatchimg = 'display:none;';
							}
						} else {
							

							$productimagepath = untrailingslashit( plugins_url( '/', __FILE__ ) ) . '/vendor/Shortcode-Source/image/blinds/no-image.jpg';
							$productframeimagepath = '';
							$offericonpath = '';
							$swatchimg = 'display:none;';
							$offerswatchimg = 'display:none;';
							$swatch_img_class = '';
						}
	
						$extra_value='';
						if ($fabriclist->extra_offer > 0) {
							$extra_offer = $fabriclist->extra_offer;
							ob_start();
							?>
				<div class="badge-container absolute left top z-1 badege-view-page" >
					<div class="callout badge badge-circle product-list-page"><div class="badge-inner secondary on-sale"><span class="extra-text">Flat</span><br><span class="productlist_extra-val"><?php echo wp_kses_post($extra_offer); ?><span> %</span></span><br><span class="sale-value">Sale</span></div></div>
				</div>	
							<?php
							$content = ob_get_contents();
							ob_end_clean();
							$extra_value =$content;
						}
		
						$del = '';
						$extra_offer_val = absint($fabriclist->extra_offer);
						$inline_style = '';		
						if (0 != $extra_offer_val) :		
							$percent = 100 - $extra_offer_val;
							$total_price = ( floatval($fabriclist->price)/$percent )*100;
							$currencysymbol = get_woocommerce_currency_symbol();
							$del = sprintf('<del aria-hidden="true" style="margin: 5px 0;"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">%s</span>%s</bdi></span></del>', $currencysymbol, number_format($total_price, 2));		
							$inline_style = "style='display: block;margin: 5px 20px;'";
						endif;
						ob_start();
						?>
						<?php echo wp_kses_post($category_title); ?>
	<div class="product-small col has-hover product type-product post-149 status-publish first instock product_cat-tops product_cat-women has-post-thumbnail shipping-taxable purchasable product-type-simple row-box-shadow-2 row-box-shadow-3-hover">
		<div class="col-inner">
			<div class="product-small box ">
				<div class="extra-off">
						<?php echo wp_kses_post($extra_value); ?>

				</div>
				<div class="box-image">
					<div class="image-fade_in_back">
						<a href="<?php echo wp_kses_post($productviewurl); ?>">
							<img class="offer-icon offer-position-bl" alt="" src="<?php echo wp_kses_post($offericonpath); ?>" style="<?php echo wp_kses_post($offerswatchimg); ?>">
							<img src="<?php echo wp_kses_post($productframeimagepath); ?>" class="product-frame" style="position:absolute;
z-index:1;">
							<img src="<?php echo wp_kses_post($productimagepath); ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail lazy-load-active" alt="<?php echo wp_kses_post($fabriclist->alt_text_tag); ?>" loading="lazy">
						</a>
					</div>
				</div>
	
				<div class="box-text box-text-products" style="padding-bottom:unset;">
					<div class="title-wrapper" style="padding:.7em;">
						<p class="name product-title woocommerce-loop-product__title" >
							<a style="display:inline-block;font-weight:700;width: 140px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;" href="<?php echo esc_url($productviewurl); ?>"><?php echo wp_kses_post($fabriclist->fabricname); ?> <?php echo wp_kses_post($fabriclist->colorname); ?></a></p>
					</div>
					<div class="price-wrapper  cuspricewrapper">
						<span class="price">
						<i class="fa fa-tag" style="padding-right:5px"></i>
						<?php echo wp_kses_post($del); ?>
						<span class="woocommerce-Price-amount amount" <?php echo wp_kses_post($inline_style); ?>>
					<?php $currencysymbol = get_woocommerce_currency_symbol(); ?>
							<bdi><span class="woocommerce-Price-currencySymbol"><?php echo wp_kses_post($currencysymbol); ?></span><?php echo wp_kses_post($fabriclist->price); ?></bdi>
						</span>
						</span>
						
					   <div class="small-product-img" >
						  <a href="<?php echo esc_url($productviewurl); ?>" title="<?php echo wp_kses_post($fabriclist->fabricname.$fabriclist->colorname); ?>" class="action more">
							 <div class="product-image-container"  style="position: relative;">
								 <img alt="<?php echo wp_kses_post($fabriclist->fabricname.$fabriclist->colorname); ?>" src="<?php echo( plugin_dir_url(__FILE__) . '/assets/image/fabric.png'); ?>" width="100" height="100" style="background-image:url('<?php echo esc_url($fabriclist->imagepath); ?>');background-size: contain;<?php echo wp_kses_post($swatchimg); ?> width: auto;height: 80px;z-index: 1;position: relative;min-width: 80px;margin-right: 5px;float: right;background-color: #DDFFF7;" class="product-image-photo <?php echo wp_kses_post($swatch_img_class); ?> ">
		
							 </div>											   
						  </a>
					   </div>
					</div>
					
						<div class="social-icons follow-icons" style="display:none;">
							<?php echo wp_kses_post($sampleButton); ?>
						</div>
					<a href="<?php echo esc_url($productviewurl); ?>" style="width: 100%;border-color: #002746;color:#fff;padding: 0px 0.3em;font-size: 11px;margin: 0 !important;background-color: #002746;" class="button primary is-outline box-shadow-2 box-shadow-2-hover relatedproduct">
							<i class="icon-shopping-cart"></i> <span style="padding: 0px !important;margin:5px 0 !important">Buy Now</span>
					</a>
					
				</div>
			</div>
		</div>
	</div>			
						<?php
						$content = ob_get_contents();
						ob_end_clean();
						$html .=$content;

		}
	} else {
			ob_start();
		?>
		<div class="container section-title-container">
			<p>No products were found matching your selection.</p>
		</div>
		<div style="clear:both;"></div>
			<?php
			$content = ob_get_contents();
			ob_end_clean();
			$html =$content;
	}
			$json_response['html'] = $html;
			echo wp_json_encode($json_response);
			exit;
	
}

function bm_eco_get_quick_quote_colorcategories() {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$productcode = isset($_REQUEST['productcode']) ? wc_clean(wp_unslash($_REQUEST['productcode'])) :'';
	$blindstype = isset($_REQUEST['blindstype']) ? wc_clean(wp_unslash($_REQUEST['blindstype'])):'';
	
	if (4 == $blindstype) {
		$response = CallAPI('GET', array('mode'=>'GetShutterParameterTypeDetails', 'parametertypeid'=>$productcode));
		
		$json_response['shutter_style'] = $response->producttype_price_list;
	} else {
		$res = CallAPI('GET', array('mode'=>'getcategorydetails', 'productcode'=>$productcode));
		
		$colorcategories_array=array();
		$row = array();
		if (count($res->maincategorydetails) > 0) {
			foreach ($res->maincategorydetails as $maincategorydetails) {
				if (count($res->subcategorydetails) > 0) {
					foreach ($res->subcategorydetails as $categorydetails) {
						if ($maincategorydetails->category_id == $categorydetails->parent_id) {
							
							$row['img_url'] = $categorydetails->imagepath;
							$row['category_name'] = $categorydetails->category_name;
							$row['category_id'] = $categorydetails->category_id;
							
							$colorcategories_array[] = $row;
						}
					}
				}
			}
		}
		$json_response['colorcategories'] = $colorcategories_array;
		
		$res1 = CallAPI('GET', array('mode'=>'getproducttypedetails', 'productcode'=>$productcode));
		$producttypedetails_array=array();
		if (count($res1->producttypedetails) > 0) {
			foreach ($res1->producttypedetails as $producttypedetails) {
	
				$row1['producttypename'] = $producttypedetails->productTypeSubName;
				$row1['producttypeid'] = $producttypedetails->parameterTypeId;
				
				$producttypedetails_array[] = $row1;
			}
		}
		$json_response['producttypedetails'] = $producttypedetails_array;
	}
	echo wp_json_encode($json_response);
	exit;
}

function bm_eco_get_quick_quote() {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	ob_start();
	$productcode = isset($_REQUEST['productcode']) ? wc_clean(wp_unslash($_REQUEST['productcode'])):'';
	$search_width = isset($_REQUEST['search_width']) ? wc_clean(wp_unslash($_REQUEST['search_width'])) :'';
	$search_drop = isset($_REQUEST['search_drop']) ? wc_clean(wp_unslash($_REQUEST['search_drop'])) :'';
	$url_search_width = isset($_REQUEST['url_search_width']) ? wc_clean(wp_unslash($_REQUEST['url_search_width'])):'';
	$url_search_drop = isset($_REQUEST['url_search_drop']) ? wc_clean(wp_unslash($_REQUEST['url_search_drop'])):'';
	$search_unitVal = isset($_REQUEST['search_unitVal']) ? wc_clean(wp_unslash($_REQUEST['search_unitVal'])):'';
	$search_text = isset($_REQUEST['search_text']) ? wc_clean(wp_unslash($_REQUEST['search_text'])):'';
	$search_type = isset($_REQUEST['search_type']) ? wc_clean(wp_unslash($_REQUEST['search_type'])):'';
	$_page = isset($_REQUEST['page']) ? wc_clean(wp_unslash($_REQUEST['page'])):'';
	$_per_page = isset($_REQUEST['per_page']) ? wc_clean(wp_unslash($_REQUEST['per_page'])):'';
	$blindstype = isset($_REQUEST['blindstype']) ? wc_clean(wp_unslash($_REQUEST['blindstype'])) :'';
	$producttypepriceid = isset($_REQUEST['shutter_style']) ? wc_clean(wp_unslash($_REQUEST['shutter_style'])):'';
	$shutter_style_price = isset($_REQUEST['shutter_style_price']) ? wc_clean(wp_unslash($_REQUEST['shutter_style_price'])):'';
	$productname = isset($_REQUEST['productname']) ? wc_clean(wp_unslash($_REQUEST['productname'])):'';
	$sel_producttype = isset($_REQUEST['sel_producttype']) ? wc_clean(wp_unslash($_REQUEST['sel_producttype']) ):'';
	
	if (4 == $blindstype) {
		$response = CallAPI('GET', array('mode'=>'GetShutterProductDetail', 'parametertypeid'=>$productcode, 'parametertypepriceid'=>$producttypepriceid));
		$shuttercolorList = $response->product_details->shuttercolorlist->shuttercolorList;
		
		#pagenation start
		$_per_page = $_per_page;
		$total_rows = count($shuttercolorList);
		$_pages = ceil($total_rows / $_per_page);
		$current_page = isset($_page) ? $_page : 1;
		$current_page = ( $total_rows > 0 ) ? min($_pages, $current_page) : 1;
		$start = $current_page * $_per_page - $_per_page;

		$json_response['total_pages'] = $_pages;
		$json_response['total_rows'] = $total_rows;

		$_page = isset($_REQUEST['page']) ? wc_clean(wp_unslash($_REQUEST['page'])):'';
		$json_response['pagination_html'] = pagination($_page, $_per_page, $total_rows);

		$slice = array_slice($shuttercolorList, $start, $_per_page);

		$fabriclist = array();
		if (!empty($slice)) {
			foreach ($slice as $shuttercolorlist) {
				
				if ('' != $shuttercolorlist->imagepath) {
					$shuttercolorimagepath = $shuttercolorlist->imagepath;
				} else {
					$shuttercolorimagepath = untrailingslashit( plugins_url( '/', __FILE__ ) ) . '/vendor/Shortcode-Source/image/blinds/no-image.jpg';
				}
				
				$productviewurl = get_bloginfo('url') . '/' . $shutter_visualizer_page . '/' . str_replace(' ', '-', strtolower($productname)) . '/' . $productcode . '/' . $producttypepriceid . '/' . str_replace(' ', '-', $shuttercolorlist->fabric_name) . '/' . $search_unitVal;
				
				?>
								
<div class="col medium-6 small-12 large-6 box_shadow_old_col" >		
	<div class="col-inner">
	   <div class="row align-middle align-center box_shadow_old" style="box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%);">
		  <div style="padding: 10px!important;" class="col medium-4 small-12 large-4">
			 <div class="col-inner">
				<div class="img has-hover x md-x lg-x y md-y lg-y" id="image_1539068005">
				   <div class="img-inner dark">
					  <a href="<?php echo esc_url($productviewurl); ?>">
							<img src="<?php echo esc_url($shuttercolorimagepath); ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail lazy-load-active" alt="" loading="lazy">
					 </a>
				  </div>
				</div>
			 </div>
		  </div>
		  <div  class="col medium-8 small-12 large-8" style="padding: 15px!important;">
			 <div class="col-inner">
				<a  href="<?php echo esc_url($productviewurl); ?>"><h3 style="margin-bottom: 0.2em;"><?php echo wp_kses_post($shuttercolorlist->fabric_name); ?></h3></a></p>
				<div class="woocommerce-Price-amount amount">
					<?php $currencysymbol =get_woocommerce_currency_symbol(); ?>
					<div class="texthold red" style="font-size:18px;display: inline-block;" data-text-color="secondary"><strong>Our Price: <?php echo wp_kses_post($currencysymbol) . ' ' . wp_kses_post($shutter_style_price); ?></strong></div>
					<div class="products row align-middle" style="margin-top: 10px;">
						<div class="col medium-8 small-12 large-8" style="padding: 0!important;">	
							<div class="social-icons follow-icons" style="display:block;padding: 0 .7em;">
							<a href="<?php echo esc_url($productviewurl); ?>" style="display:block;margin:5px 0 !important;padding: 0 10px;" class="button primary is-outline box-shadow-2 box-shadow-2-hover relatedproduct">
								<i class="icon-shopping-cart"></i> <span style="font-size: 13px; padding: 0px !important;margin:5px 0 !important">Buy this shutter</span>
							</a>
							</div>
						</div>
					</div>
				</div>
			 </div>
		  </div>
	   </div>
	</div>
</div>

										<?php
			}
			$html = ob_get_contents();

			ob_end_clean();			    
				
		} else {
			ob_start();
			?>
			<div class="container section-title-container">
				<p>No products were found matching your selection.</p>
			</div>
			<div style="clear:both;"></div>		 
			<?php
			$content = ob_get_contents();
			ob_end_clean();
			$html =$content;
		}
		
		
	} else {
	
		$getproductdetailresponse = CallAPI('GET', array('mode'=>'getproductdetail', 'productcode'=>$productcode));

		$response = CallAPI('GET', array('mode'=>'fabriclist', 'get_quick_quote'=>'1', 'productcode'=>$productcode, 'search_text'=>$search_text, 'search_type'=>$search_type, 'search_width'=>$search_width, 'search_drop'=>$search_drop, 'sel_producttype'=>$sel_producttype, 'sort'=>'ASC', 'page'=>$_page, 'rows'=>$_per_page));
	
		$fabric_list = $response->fabric_list;
		$json_response['fabric_list'] = $fabric_list;
		$json_response['total_pages'] = $response->total_pages;
		$json_response['total_rows'] = $response->total_rows;
		$json_response['search_text_arr'] = $search_text_arr;
		$json_response['searcharrays'] = $response->searcharrays;
		$page = isset($_REQUEST['page']) ? wc_clean(wp_unslash($_REQUEST['page'])):'';
		$json_response['pagination_html'] = pagination($page, $_per_page, $response->total_rows);
	
		if (count($fabric_list) > 0) {
			foreach ($fabric_list as $key=>$fabriclist) {	
		
				if (1 == $fabriclist->skipcolorfield) {
					$urlfcname = $fabriclist->colorname;
				} else {
					$urlfcname = $fabriclist->fabricname . '/' . $fabriclist->colorname;
				}
		
				$productnamearr = explode('(', $fabriclist->productname);
				$get_productname = trim($productnamearr[0]);
		
				//$productviewurl = get_bloginfo('url').'/'.$blinds_config.'/'.str_replace(' ','-',strtolower($get_productname)).'/'.str_replace(' ','-',strtolower($urlfcname)).'/?width='.$url_search_width.'&height='.$url_search_drop.'&unit='.$search_unitVal;
				//$productviewurl = get_bloginfo('url').'/'.$blinds_config.'/'.str_replace(' ','-',strtolower($fabriclist->productname)).'/'.str_replace(' ','-',strtolower($urlfcname)).'/?pc='.safe_encode($productcode).'&ptid='.safe_encode($fabriclist->producttypeid).'&fid='.safe_encode($fabriclist->fabricid).'&cid='.safe_encode($fabriclist->colorid).'&vid='.safe_encode($fabriclist->vendorid).'&width='.$url_search_width.'&height='.$url_search_drop.'&unit='.$search_unitVal;
				$productviewurl = get_bloginfo('url') . '/' . $blinds_config . '/' . str_replace(' ', '-', strtolower($fabriclist->productname)) . '/' . str_replace(' ', '-', strtolower($urlfcname)) . '/' . $url_search_width . '/' . $url_search_drop . '/' . $search_unitVal;

				$orderItemId = $productcode . $fabriclist->producttypeid . $fabriclist->fabricid . $fabriclist->colorid . $fabriclist->vendorid;
					 ob_start();
				?>
						<a id="<?php echo wp_kses_post($orderItemId); ?>" style="display:block;margin:5px 0 !important; padding: 0px;" href="javascript:;" onclick="sampleOrder(this,'<?php echo wp_kses_post($productcode); ?>','<?php echo wp_kses_post($fabriclist->producttypeid); ?>','<?php echo wp_kses_post($fabriclist->fabricid); ?>','<?php echo wp_kses_post($fabriclist->colorid); ?>','<?php echo wp_kses_post($fabriclist->vendorid); ?>')" class="button primary is-outline box-shadow-2 box-shadow-2-hover">
			<span style="padding: 0px !important;font-size: 13px;margin:5px 0 !important">Free Sample</span>
		</a>	 
										<?php
										$content = ob_get_contents();
										ob_end_clean();
										$sampleButton = $content;
										$cart = isset($_SESSION['cart']) ? wc_clean(wp_unslash($_SESSION['cart'])):'';
										if (is_array($cart) && count($cart) > 0) {
											if (false !== array_search($orderItemId, array_column($cart, 'sampleOrderItemId')) ) {
												ob_start();
												?>
					 <a id="<?php echo wp_kses_post($orderItemId); ?>" style="display:block;margin:5px 0 !important; padding: 0px;" href="javascript:;" onclick="sampleOrder(this,'<?php echo wp_kses_post($productcode); ?>','<?php echo wp_kses_post($fabriclist->producttypeid); ?>','<?php echo wp_kses_post($fabriclist->fabricid); ?>','<?php echo wp_kses_post($fabriclist->colorid); ?>','<?php echo wp_kses_post($fabriclist->vendorid); ?>')" class="button primary is-outline box-shadow-2 box-shadow-2-hover">
			<i class="icon-checkmark"></i>
			<span style="padding: 0px !important;font-size: 13px;margin: 5px 0 !important;">Sample Added</span>
		</a>
												<?php
												$content = ob_get_contents();
												ob_end_clean();
												$sampleButton =$content;
											}
										}
		
										if ('0' == $fabriclist->ecommerce_sample) {
											$sampleButton = '';
										}
		
										if ('' != $fabriclist->imagepath) {
											$productimagepath = $fabriclist->imagepath;
											$productframeimagepath = $fabriclist->getproductframeimage;
											$offericonpath = get_stylesheet_directory_uri() . '/icon/tree1234.png';
											$swatchimg = '';
										} else {
											$productimagepath = untrailingslashit( plugins_url( '/', __FILE__ ) ) . '/vendor/Shortcode-Source/image/blinds/no-image.jpg';
											$productframeimagepath = '';
											$offericonpath = '';
											$swatchimg = 'display:none;';
										}

										$extra_value='';
										if ($fabriclist->extra_offer > 0) {
											$extra_offer = $fabriclist->extra_offer;
											ob_start();
											?>
					<div class="badge-container absolute left top z-1 badege-view-page" >
						<div class="callout badge badge-circle product-list-page"><div class="badge-inner secondary on-sale"><span class="extra-text">Flat</span><br><span class="productlist_extra-val"><?php echo wp_kses_post($extra_offer); ?><span> %</span></span><br><span class="sale-value">Sale</span></div></div>
				</div> 
											<?php
													$content = ob_get_contents();
													ob_end_clean();
													$extra_value = $content;
										}
										?>

<div class="col medium-6 small-12 large-6 box_shadow_old_col" >		
	<div class="col-inner">
	   <div class="row align-middle align-center box_shadow_old" style="box-shadow: 0 4px 8px 0 rgb(0 0 0 / 20%);">
		  <div style="padding: 10px!important;" class="col medium-4 small-12 large-4">
			 <div class="col-inner">
				<div class="img has-hover x md-x lg-x y md-y lg-y" id="image_1539068005">
										<?php echo wp_kses_post($extra_value); ?>
				   <div class="img-inner dark">
					  <a href="<?php echo esc_url($productviewurl); ?>">
							<!--<img src="<?php echo esc_url($productframeimagepath); ?>" class="product-frame" style="position:absolute; z-index:1;">-->
							<img src="<?php echo esc_url($productimagepath); ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail lazy-load-active" alt="<?php echo $fabriclist->alt_text_tag; ?>" loading="lazy">
					 </a>
				  </div>
				</div>
			 </div>
		  </div>
		  <div  class="col medium-8 small-12 large-8" style="padding: 15px!important;">
			 <div class="col-inner">
				<a  href="<?php echo esc_url($productviewurl); ?>"><h3 style="margin-bottom: 0.2em;"><?php echo wp_kses_post(( $fabriclist->fabricname ) . ' ' . ( $fabriclist->colorname ) . ' ' . ( $fabriclist->productname )); ?></h3></a></p>
				<p style="font-size:18px;margin:0;"><?php echo wp_kses_post($fabriclist->producttype); ?></p>
				<div class="woocommerce-Price-amount amount">
										<?php $currencysymbol = get_woocommerce_currency_symbol(); ?>
					<div class="texthold red" style="font-size:18px;display: inline-block;" data-text-color="secondary"><strong>Our Price: <?php echo wp_kses_post(( $currencysymbol ) . ' ' . ( $fabriclist->price )); ?></strong></div>
					<div class="products row align-middle align-center" style="margin-top: 10px;">
						<div class="col medium-6 small-12 large-6" style="padding: 0!important;">	
							<div class="social-icons follow-icons" style="display:block;padding: 0 .7em;">
							<a href="<?php echo esc_url($productviewurl); ?>" style="display:block;margin:5px 0 !important;padding: 0 10px;" class="button primary is-outline box-shadow-2 box-shadow-2-hover relatedproduct">
								<i class="icon-shopping-cart"></i> <span style="font-size: 13px; padding: 0px !important;margin:5px 0 !important">Buy this blind</span>
							</a>
							</div>
						</div>
						<div class="col medium-6 small-12 large-6" style="padding: 0!important;">	
							<div class="social-icons follow-icons" style="display:block;padding: 0 .7em;">
										<?php echo wp_kses_post($sampleButton); ?>
							</div>
						</div>
					</div>
				</div>
			 </div>
		  </div>
	   </div>
	</div>
</div>

										<?php
			}
			$html = ob_get_contents();

			ob_end_clean();
		} else {
			ob_start();
			?>
			<div class="container section-title-container">
				<p>No products were found matching your selection.</p>
			</div>
			<div style="clear:both;"></div>		
			<?php
			$content = ob_get_contents();
			ob_end_clean();
			$html =$content;
		}
	
	}
			
	$json_response['html'] = $html;
	echo wp_json_encode($json_response);
	exit;
}

function bm_eco_getparameterdetails() {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$componentvalue = array(); 

	$productid		= isset($_REQUEST['productid']) ? wc_clean(wp_unslash($_REQUEST['productid'])):'' ;
	$producttypepriceid	= isset($_REQUEST['producttypepriceid']) ? wc_clean(wp_unslash($_REQUEST['producttypepriceid'])):'' ;
	$unit 			= isset($_REQUEST['unit']) ? wc_clean(wp_unslash($_REQUEST['unit'])):'';
	$width 			= isset($_REQUEST['width']) ? wc_clean(wp_unslash($_REQUEST['width'])):'';
	$drope 			= isset($_REQUEST['drope']) ? wc_clean(wp_unslash($_REQUEST['drope'])):'';
	$widthfraction	= isset($_REQUEST['widthfraction']) ? wc_clean(wp_unslash($_REQUEST['widthfraction'])):'';
	$dropfraction	= isset($_REQUEST['dropfraction']) ? wc_clean(wp_unslash($_REQUEST['dropfraction'])):'' ;
	$fraction		= isset($_REQUEST['fraction']) ? wc_clean(wp_unslash($_REQUEST['fraction'])):'';
	$componentvalue	= isset($_REQUEST['Componentvalue']) ? wc_clean(wp_unslash($_REQUEST['Componentvalue'])):'' ;
	$extra_offer 	= isset($_REQUEST['extra_offer']) ? wc_clean(wp_unslash($_REQUEST['extra_offer'])):'';

	$widthparameterListId = isset($_REQUEST['widthparameterListId']) ? wc_clean(wp_unslash($_REQUEST['widthparameterListId'])):'';
	$dropeparameterListId = isset($_REQUEST['dropeparameterListId']) ? wc_clean(wp_unslash($_REQUEST['dropeparameterListId'])):'' ;
	
	$componentpriceid ='';
	if (!empty($componentvalue)) {
		$compid ='';
		foreach (call_user_func_array('array_merge', $componentvalue) as $keyval) {
			$comp 			= explode('~', $keyval);
			$compid 	.= $comp[0] . ',';
			$compname 		= $comp[1];
		}
		$componentpriceid = rtrim($compid , ','); 
	}
	
	$allparametervalue_html = '<table class="getprice_table">';
	
	$widthfraction_val = '';
	if ('inch' == $unit) {
		if (1 == $widthfraction ) {
			$widthfraction_val = '1/8';
		} elseif (2 == $widthfraction) {
			$widthfraction_val = '1/4';
		} elseif (3 == $widthfraction) {
			$widthfraction_val = '3/8';
		} elseif (4 == $widthfraction) {
			$widthfraction_val = '1/2';
		} elseif (5 == $widthfraction) {
			$widthfraction_val = '5/8';
		} elseif (6 == $widthfraction) {
			$widthfraction_val = '3/4';
		} elseif (7 == $widthfraction) {
			$widthfraction_val = '7/8';
		}
	}
	
	$dropfraction_val = '';
	if ('inch' == $unit) {
		if (1 == $dropfraction) {
			$dropfraction_val = '1/8';
		} elseif (2 == $dropfraction) {
			$dropfraction_val = '1/4';
		} elseif (3 == $dropfraction) {
			$dropfraction_val = '3/8';
		} elseif (4 == $dropfraction) {
			$dropfraction_val = '1/2';
		} elseif (5 == $dropfraction) {
			$dropfraction_val = '5/8';
		} elseif (6 == $dropfraction) {
			$dropfraction_val = '3/4';
		} elseif (7 == $dropfraction) {
			$dropfraction_val = '7/8';
		}
	}
			$widthplaceholdertext = isset($_REQUEST['widthplaceholdertext']) ? wc_clean(wp_unslash($_REQUEST['widthplaceholdertext'])) :'';
			$dropeplaceholdertext = isset($_REQUEST['dropeplaceholdertext']) ? wc_clean(wp_unslash($_REQUEST['dropeplaceholdertext'])):'';
			$producttypeparametername = isset($_REQUEST['producttypeparametername']) ? wc_clean(wp_unslash($_REQUEST['producttypeparametername'])):'';
			$producttypeparametervalue =isset($_REQUEST['producttypeparametervalue']) ? wc_clean(wp_unslash($_REQUEST['producttypeparametervalue'])) :'';
	if (!empty($width)) {		
		ob_start();
		?>
		<tr class="paramlable"><td><?php echo wp_kses_post($widthplaceholdertext); ?>:</td><td><strong class="paramval"><?php echo wp_kses_post($width); ?> <?php echo wp_kses_post($widthfraction_val); ?> <?php echo wp_kses_post($unit); ?></strong></td></tr>			
								<?php
								$content = ob_get_contents();
								ob_end_clean();
								$allparametervalue_html .=$content;
	}
	if (!empty($drope)) {
		ob_start();
		?>
		<tr class="paramlable"><td><?php echo wp_kses_post($dropeplaceholdertext); ?>:</td><td><strong class="paramval"><?php echo wp_kses_post($drope); ?> <?php echo wp_kses_post($dropfraction_val); ?> <?php echo wp_kses_post($unit); ?> </strong></td></tr>			
								<?php
								$content = ob_get_contents();
								ob_end_clean();
								$allparametervalue_html .=$content;
	}
	$ParameterTypehidden = isset($_REQUEST['ParameterTypehidden'] ) ? wc_clean(wp_unslash($_REQUEST['ParameterTypehidden'] )):'';
	if ($ParameterTypehidden && 1 == $ParameterTypehidden ) {
		ob_start();
		?>
		<tr class="paramlable"><td><?php echo wp_kses_post($producttypeparametername); ?>:</td><td><strong class="paramval"><?php echo wp_kses_post($producttypeparametervalue); ?></strong></td></tr>			
								<?php
								$content = ob_get_contents();
								ob_end_clean();
								$allparametervalue_html .=$content;
	}
			$productsParametername = isset($_REQUEST['ProductsParametername']) ? wc_clean(wp_unslash($_REQUEST['ProductsParametername'])):'';
			$ProductsParametervalues = isset($_REQUEST['ProductsParametervalue']) ? wc_clean(wp_unslash($_REQUEST['ProductsParametervalue'])):'';
	if (!empty($ProductsParametervalues)) {
		foreach ($ProductsParametervalues as $name=>$ProductsParametervalue) {
				$ppv = explode('~', $ProductsParametervalue);
				$ProductsParametertext	= $ppv[1];
				$ProductsParameterhidden = isset($_REQUEST['ProductsParameterhidden'][$name]) ? wc_clean(wp_unslash($_REQUEST['ProductsParameterhidden'][$name])):'';
			if ('' != $ProductsParametertext && 1 == $ProductsParameterhidden) {
				ob_start();
				?>
				<tr class="paramlable"><td><?php echo wp_kses_post($productsParametername[$name]); ?>:</td><td><strong class="paramval"><?php echo wp_kses_post($ProductsParametertext); ?></strong></td></tr>	
										<?php
										$content = ob_get_contents();
										ob_end_clean();
										$allparametervalue_html .=$content;
			}
		}
	}
	$shuttercolorvalue = isset($_REQUEST['shuttercolorvalue']) ? wc_clean(wp_unslash($_REQUEST['shuttercolorvalue'])):'';
	$shuttercolorname = isset($_REQUEST['shuttercolorname']) ? wc_clean(wp_unslash($_REQUEST['shuttercolorname'])):'';
	if (!empty($shuttercolorvalue)) {
		$scv = explode('~', $shuttercolorvalue);
		$shuttercolorname = $shuttercolorname;
		$shuttercolortext	= $scv[1];
		if ('' != $shuttercolortext) {
			ob_start();
			?>
			<tr class="paramlable"><td><?php echo wp_kses_post($shuttercolorname); ?>:</td><td><strong class="paramval"><?php echo wp_kses_post($shuttercolortext); ?></strong></td></tr>		
									<?php
									$content = ob_get_contents();
									ob_end_clean();
									$allparametervalue_html .=$content;
		}
	}
			
	$subcomponentprice = 0;
	$subcomponentcostprice = 0;
	$Componentvalues = isset($_REQUEST['Componentvalue']) ? wc_clean(wp_unslash($_REQUEST['Componentvalue'])):'';
	if (!empty($Componentvalues)) {
		foreach ($Componentvalues as $name=>$Componentvalue) {
			$compname=array();
			foreach ($Componentvalue as $Component_value) {
				$comp = explode('~', $Component_value);
				$compname[]= $comp[1];
			}
			
			$compname1 = implode(', ', $compname);
			
			$ComponentParameterhidden = isset($_REQUEST['ComponentParameterhidden'][$name]) ? wc_clean(wp_unslash($_REQUEST['ComponentParameterhidden'][$name])):'';

			if ('' != $compname1 && 1 == $ComponentParameterhidden) {
				$ComponentParametername = isset($_REQUEST['ComponentParametername']) ? wc_clean(wp_unslash($_REQUEST['ComponentParametername'])):'';
				ob_start();
				?>
				<tr class="paramlable"><td><?php echo wp_kses_post($ComponentParametername[$name]); ?>:</td><td><strong class="paramval"><?php echo wp_kses_post($compname1); ?></strong></td></tr>	
										<?php
										$content = ob_get_contents();
										ob_end_clean();
										$allparametervalue_html .=$content;
			}
			
			#get subcomponent details
			$Componentsubvalues = isset($_REQUEST['Componentsubvalue'][$name]) ? wc_clean(wp_unslash($_REQUEST['Componentsubvalue'][$name])):'';
			if (!empty($Componentsubvalues)) {
				foreach ($Componentsubvalues as $subname=>$Componentsubvalue) {
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
						$ComponentSubParametername = isset($_REQUEST['ComponentSubParametername']) ? wc_clean(wp_unslash($_REQUEST['ComponentSubParametername'])):'';
						ob_start();
						?>
						<tr class="paramlable"><td><?php echo wp_kses_post($ComponentSubParametername[$name][$subname]); ?>:</td><td><strong class="paramval"><?php echo wp_kses_post($compsubname1); ?></strong></td></tr>
												<?php
												$content = ob_get_contents();
												ob_end_clean();
												$allparametervalue_html .=$content;
					}
				}
			}
		}
	}
	
	$Othersvalue = isset($_REQUEST['Othersvalue']) ? wc_clean(wp_unslash($_REQUEST['Othersvalue']) ):'';
	if (!empty($Othersvalue)) {
		foreach ($Othersvalue as $name=>$Othersvalue) {
			if ('' != $Othersvalue) {
				if (strlen($Othersvalue) > 50) {
					$Othersvalue = substr($Othersvalue, 0, 50) . '...';
				} else {
					$Othersvalue = $Othersvalue;
				}
				$OthersParameterhidden = isset($_REQUEST['OthersParameterhidden'][$name]) ? wc_clean(wp_unslash($_REQUEST['OthersParameterhidden'][$name]) ):'';
				
				if ('' != $Othersvalue && 1 == $OthersParameterhidden) {
					$OthersParametername = isset($_REQUEST['OthersParametername']) ? wc_clean(wp_unslash($_REQUEST['OthersParametername'])):'';
					ob_start();
					?>
					 <tr class="paramlable"><td><?php echo wp_kses_post($OthersParametername[$name]); ?>:</td><td><strong class="paramval"><?php echo wp_kses_post($Othersvalue); ?></strong></td></tr>
											<?php
											$content = ob_get_contents();
											ob_end_clean();
											$allparametervalue_html .= $content;
				}
			}
		}
	}
	$allparametervalue_html .= '</table>';
	
	$response = CallAPI('GET', array('mode'=>'getshutterprice', 'productid'=>$productid, 'producttypepriceid'=>$producttypepriceid, 'unit'=>$unit, 'width'=>$width, 'drope'=>$drope, 'widthfraction'=>$widthfraction, 'dropfraction'=>$dropfraction, 'fraction'=>$fraction, 'componentpriceid'=>$componentpriceid, 'widthparameterListId'=>$widthparameterListId, 'dropeparameterListId'=>$dropeparameterListId));
	
	#subcomponent price added
	if (!empty($subcomponentprice) && $subcomponentprice > 0) {
		$response->price = $response->price + $subcomponentprice;
		if (!empty($subcomponentcostprice) && $subcomponentcostprice > 0) {
			$response->actualCost = $response->actualCost + $subcomponentcostprice;
		}
	}
	
	$vat = ( $response->price / 100 ) * $response->vaterate;
	$priceval = $response->price+$vat;
	
	$response->priceval = $priceval;
	$response->showprice = number_format($priceval, 2);
	$response->netprice = $response->price;
	$response->itemcost = $response->actualCost;
	$response->orgvat = $response->vaterate;
	$response->vatvalue = $vat;
	$response->grossprice = $priceval;
	
	$response->allparametervalue_html = $allparametervalue_html;
	
	$json_response = $response;
	echo wp_json_encode($json_response);
	exit;

}

function bm_eco_getcurtainprice() {
	$allparametervalue_html = '';
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$componentvalue = array();
	$productid		= isset($_REQUEST['productid']) ? wc_clean(wp_unslash($_REQUEST['productid'])):'';
	$producttypeid	= isset($_REQUEST['producttypeid']) ? wc_clean(wp_unslash($_REQUEST['producttypeid'])):'';
	$unit 			= isset($_REQUEST['unit']) ? wc_clean(wp_unslash($_REQUEST['unit'])):'';
	$width 			= isset($_REQUEST['width']) ? wc_clean(wp_unslash($_REQUEST['width'])):'';
	$drope 			= isset($_REQUEST['drope']) ? wc_clean(wp_unslash($_REQUEST['drope'])):'';
	$widthfraction	= isset($_REQUEST['widthfraction']) ? wc_clean(wp_unslash($_REQUEST['widthfraction'])):'';
	$dropfraction	= isset($_REQUEST['dropfraction']) ? wc_clean(wp_unslash($_REQUEST['dropfraction'])):'';
	$fraction		= isset($_REQUEST['fraction']) ? wc_clean(wp_unslash($_REQUEST['fraction']) ):'';
	$vendorid		= isset($_REQUEST['vendorid']) ? wc_clean(wp_unslash($_REQUEST['vendorid'])):'';
	$componentvalue	= isset($_REQUEST['Componentvalue']) ? wc_clean(wp_unslash($_REQUEST['Componentvalue'])):'';
	
	$componentpriceid ='';
	if (!empty($componentvalue)) {
		$compid ='';
		foreach (call_user_func_array('array_merge', $componentvalue) as $keyval) {
			$comp 			= explode('~', $keyval);
			$compid 	.= $comp[0] . ',';
			$compname 		= $comp[1];
		}
		$componentpriceid = rtrim($compid , ','); 
	}
	
	$subcomponentprice = 0;
	$subcomponentcostprice = 0;
	$Componentvalues = isset($_REQUEST['Componentvalue']) ? wc_clean(wp_unslash($_REQUEST['Componentvalue'])):'';
	if (!empty($Componentvalues)) {
		foreach ($Componentvalues as $name=>$Componentvalue) {
			$compname=array();
			foreach ($Componentvalue as $Component_value) {
				$comp = explode('~', $Component_value);
				$compname[]= $comp[1];
			}
			
			$compname1 = implode(', ', $compname);
			
			$ComponentParameterhidden = isset($_REQUEST['ComponentParameterhidden'][$name]) ? wc_clean(wp_unslash($_REQUEST['ComponentParameterhidden'][$name])):'' ;

			#get subcomponent details
			$Componentsubvalues = isset($_REQUEST['Componentsubvalue'][$name]) ? wc_clean(wp_unslash($_REQUEST['Componentsubvalue'][$name])):array() ;
			if (!empty($Componentsubvalues)) {
				foreach ($Componentsubvalues as $subname=>$Componentsubvalue) {
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
				}
			}
		}
	}
	
	$response = CallAPI('GET', array('mode'=>'getcurtainprice', 'productid'=>$productid, 'producttypeid'=>$producttypeid, 'unit'=>$unit, 'width'=>$width, 'drope'=>$drope, 'widthfraction'=>$widthfraction, 'dropfraction'=>$dropfraction, 'fraction'=>$fraction, 'componentpriceid'=>$componentpriceid, 'vendorid'=>$vendorid));
			
	if (1 == $response->success) {
		$response->pricetableprice = $response->price[0]->price;
		$price = $response->componentprice;
		$itemcost = $response->newcomponentprice;

		#subcomponent price added
		if (!empty($subcomponentprice) && $subcomponentprice > 0) {
			$price = $price + $subcomponentprice;
			if (!empty($subcomponentcostprice) && $subcomponentcostprice > 0) {
				$itemcost = $itemcost + $subcomponentcostprice;
			}
		}
		
		$vat = ( $price / 100 ) * $response->vaterate;
		$priceval = $price+$vat;
		
		$response->priceval = $priceval;//number_format(round($priceval, 2), 2);;
		$response->showprice = number_format($priceval, 2);
		$response->allparametervalue_html = $allparametervalue_html;

		$response->netprice = $price;
		$response->itemcost = $itemcost;
		$response->orgvat = $response->vaterate;
		$response->vatvalue = $vat;
		$response->grossprice = $priceval;
		
		$response->curtain_formulas = $response->curtain_formulas;
		$response->curtain_allowance_variables = $response->curtain_allowance_variables;

		$json_response = $response; 
	} else {
		$json_response = $response; 
	}
	echo wp_json_encode($json_response);
	exit;
}

function bm_eco_getprice() {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	//do your ajax task
	//don't forget to use sql injection prevention here.
	
	$componentvalue = array(); 
	$productid		= isset($_REQUEST['productid']) ? wc_clean(wp_unslash($_REQUEST['productid'])):'';
	$producttypeid	= isset($_REQUEST['producttypeid']) ? wc_clean(wp_unslash($_REQUEST['producttypeid'])):'' ;
	$unit 			= isset($_REQUEST['unit']) ? wc_clean(wp_unslash($_REQUEST['unit'])):'';
	$width 			= isset($_REQUEST['width']) ? wc_clean(wp_unslash($_REQUEST['width'])):'' ;
	$drope 			= isset($_REQUEST['drope']) ? wc_clean(wp_unslash($_REQUEST['drope'])):'' ;
	$widthfraction	= isset($_REQUEST['widthfraction']) ? wc_clean(wp_unslash($_REQUEST['widthfraction'])):'' ;
	$dropfraction	= isset($_REQUEST['dropfraction']) ? wc_clean(wp_unslash($_REQUEST['dropfraction'])):'';
	$fraction		= isset($_REQUEST['fraction']) ? wc_clean(wp_unslash($_REQUEST['fraction'])):'' ;
	$componentvalue	= isset($_REQUEST['Componentvalue']) ? wc_clean(wp_unslash($_REQUEST['Componentvalue'])):'' ;
	$vendorid		= isset($_REQUEST['vendorid']) ? wc_clean(wp_unslash($_REQUEST['vendorid'])):'';
	$extra_offer 	= isset($_REQUEST['extra_offer']) ? wc_clean(wp_unslash($_REQUEST['extra_offer'])):'';
	$fabricid       = isset($_REQUEST['fabricid']) ? wc_clean(wp_unslash($_REQUEST['fabricid'])):'' ;
	$colorid        = isset($_REQUEST['colorid']) ? wc_clean(wp_unslash($_REQUEST['colorid'])):'' ;
	$getminWidth    = isset($_REQUEST['getminWidth']) ? wc_clean(wp_unslash($_REQUEST['getminWidth'])):'' ;
	$getmaxWidth    = isset($_REQUEST['getmaxWidth']) ? wc_clean(wp_unslash($_REQUEST['getmaxWidth'])):'';
	$getminDrop     = isset($_REQUEST['getminDrop']) ? wc_clean(wp_unslash($_REQUEST['getminDrop'])):'' ;
	$getmaxDrop     = isset($_REQUEST['getmaxDrop']) ? wc_clean(wp_unslash($_REQUEST['getmaxDrop'])):'';
	$stockcomponentid = isset($_REQUEST['stockcomponentid']) ? wc_clean(wp_unslash($_REQUEST['stockcomponentid'])):'';
	$vatoption        = isset($_REQUEST['vatoption']) ? wc_clean(wp_unslash($_REQUEST['vatoption'])):'';

	
	$componentpriceid ='';
	if (!empty($componentvalue)) {
		$compid ='';
		foreach (call_user_func_array('array_merge', $componentvalue) as $keyval) {
			$comp 			= explode('~', $keyval);
			$compid 	.= $comp[0] . ',';
			$compname 		= $comp[1];
		}
		$componentpriceid = rtrim($compid , ','); 
	}
	
	$allparametervalue_html = '<table class="getprice_table">';
	
	$widthfraction_val = '';
	if ('inch' == $unit) {
		if (1 == $widthfraction) {
			$widthfraction_val = '1/8';
		} elseif (2 == $widthfraction ) {
			$widthfraction_val = '1/4';
		} elseif (3 == $widthfraction ) {
			$widthfraction_val = '3/8';
		} elseif (4 == $widthfraction) {
			$widthfraction_val = '1/2';
		} elseif (5 == $widthfraction) {
			$widthfraction_val = '5/8';
		} elseif (6 == $widthfraction) {
			$widthfraction_val = '3/4';
		} elseif (7 == $widthfraction) {
			$widthfraction_val = '7/8';
		}
	}
	
	$dropfraction_val = '';
	if ('inch' == $unit) {
		if (1 == $dropfraction) {
			$dropfraction_val = '1/8';
		} elseif (2 == $dropfraction) {
			$dropfraction_val = '1/4';
		} elseif (3 == $dropfraction) {
			$dropfraction_val = '3/8';
		} elseif (4 == $dropfraction) {
			$dropfraction_val = '1/2';
		} elseif (5 == $dropfraction) {
			$dropfraction_val = '5/8';
		} elseif (6 == $dropfraction) {
			$dropfraction_val = '3/4';
		} elseif (7 == $dropfraction) {
			$dropfraction_val = '7/8';
		}
	}
			$widthplaceholdertext = isset($_REQUEST['widthplaceholdertext']) ? wc_clean(wp_unslash($_REQUEST['widthplaceholdertext'])):'';
			$dropeplaceholdertext = isset($_REQUEST['dropeplaceholdertext']) ? wc_clean(wp_unslash($_REQUEST['dropeplaceholdertext'])):'';
			$producttypeparametername = isset($_REQUEST['producttypeparametername']) ? wc_clean(wp_unslash($_REQUEST['producttypeparametername'])):'';
			$producttypeparametervalue = isset($_REQUEST['producttypeparametervalue']) ? wc_clean(wp_unslash($_REQUEST['producttypeparametervalue'])):'';
			$productsParametername = isset($_REQUEST['ProductsParametername']) ? wc_clean(wp_unslash($_REQUEST['ProductsParametername'])):'';
	ob_start();
	?>
	<tr class="paramlable"><td>Size:</td><td><strong class="paramval"><?php echo wp_kses_post($width); ?> <?php echo wp_kses_post($widthfraction_val); ?> <?php echo wp_kses_post($unit); ?> <?php echo wp_kses_post($widthplaceholdertext); ?> x <?php echo wp_kses_post($drope); ?> <?php echo wp_kses_post($dropfraction_val); ?> <?php echo wp_kses_post($unit); ?> <?php echo wp_kses_post($dropeplaceholdertext); ?></strong></td></tr>				
							<?php
							$content = ob_get_contents();
							ob_end_clean();
							$allparametervalue_html .=$content;
							$ParameterTypehidden = isset($_REQUEST['ParameterTypehidden']) ? wc_clean(wp_unslash($_REQUEST['ParameterTypehidden'])):'';
							if ($ParameterTypehidden && 1 == $ParameterTypehidden) {
								ob_start();
								?>
		<tr class="paramlable"><td><?php echo wp_kses_post($producttypeparametername); ?>:</td><td><strong class="paramval"><?php echo wp_kses_post($producttypeparametervalue); ?></strong></td></tr>			
								<?php
								$content = ob_get_contents();
								ob_end_clean();
								$allparametervalue_html .=$content;
							}
							$ProductsParametervalues = isset($_REQUEST['ProductsParametervalue']) ? wc_clean(wp_unslash($_REQUEST['ProductsParametervalue'])):''; 		
							if (!empty($ProductsParametervalues)) {
								foreach ($ProductsParametervalues as $name=>$ProductsParametervalue) {
										$ppv = explode('~', $ProductsParametervalue);
										$ProductsParametertext	= $ppv[1];
										$ProductsParameterhidden = isset($_REQUEST['ProductsParameterhidden'][$name]) ? wc_clean(wp_unslash($_REQUEST['ProductsParameterhidden'][$name])):'';
									if ('' != $ProductsParametertext && 1 == $ProductsParameterhidden) {
										ob_start();
										?>
				<tr class="paramlable"><td><?php echo wp_kses_post($productsParametername[$name]); ?>:</td><td><strong class="paramval"><?php echo wp_kses_post($ProductsParametertext); ?></strong></td></tr>	
										<?php
										$content = ob_get_contents();
										ob_end_clean();
										$allparametervalue_html .= $content;
									}
								}
							}
			
							$subcomponentprice = 0;
							$subcomponentcostprice = 0;
							$Componentvalues = isset($_REQUEST['Componentvalue']) ? wc_clean(wp_unslash($_REQUEST['Componentvalue'])):'';
							if (!empty($Componentvalues)) {
								foreach ($Componentvalues as $name=>$Componentvalue) {
									$compname=array();
									foreach ($Componentvalue as $Component_value) {
										$comp = explode('~', $Component_value);
										$compname[]= $comp[1];
									}
			
									$compname1 = implode(', ', $compname);
			
									$ComponentParameterhidden = isset($_REQUEST['ComponentParameterhidden'][$name]) ? wc_clean(wp_unslash($_REQUEST['ComponentParameterhidden'][$name])):'';

									if ('' != $compname1 && 1 == $ComponentParameterhidden ) {
										$ComponentParametername = isset($_REQUEST['ComponentParametername']) ? wc_clean(wp_unslash($_REQUEST['ComponentParametername'])):'';
										ob_start();
										?>
				<tr class="paramlable"><td><?php echo wp_kses_post($ComponentParametername[$name]); ?>:</td><td><strong class="paramval"><?php echo wp_kses_post($compname1); ?></strong></td></tr>	
										<?php
										$content = ob_get_contents();
										ob_end_clean();
										$allparametervalue_html .=$content;
									}
			
									#get subcomponent details
									$Componentsubvalues = isset($_REQUEST['Componentsubvalue'][$name]) ? wc_clean(wp_unslash($_REQUEST['Componentsubvalue'][$name])):array();
									if (!empty($Componentsubvalues)) {
										foreach ($Componentsubvalues as $subname=>$Componentsubvalue) {
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
												$ComponentSubParametername = isset($_REQUEST['ComponentSubParametername']) ? wc_clean(wp_unslash($_REQUEST['ComponentSubParametername'])) :'';
												ob_start();
												?>
						<tr class="paramlable"><td><?php echo wp_kses_post($ComponentSubParametername[$name][$subname]); ?>:</td><td><strong class="paramval"><?php echo wp_kses_post($compsubname1); ?></strong></td></tr>
												<?php
												$content = ob_get_contents();
												ob_end_clean();
												$allparametervalue_html .=$content;
											}
										}
									}
								}
							}
							$others_value = isset($_REQUEST['Othersvalue']) ? wc_clean(wp_unslash($_REQUEST['Othersvalue'])):'';		
							if (!empty($others_value)) {
								foreach ($others_value as $name=>$Othersvalue) {
									if ('' != $Othersvalue) {
										if (strlen($Othersvalue) > 50) {
											$Othersvalue = substr($Othersvalue, 0, 50) . '...';
										} else {
											$Othersvalue = $Othersvalue;
										}
										$OthersParameterhidden = isset($_REQUEST['OthersParameterhidden'][$name]) ? wc_clean(wp_unslash($_REQUEST['OthersParameterhidden'][$name])):'';
				
										if ('' != $Othersvalue && 1 == $OthersParameterhidden) {
											$OthersParametername = isset($_REQUEST['OthersParametername']) ? wc_clean(wp_unslash($_REQUEST['OthersParametername'])):'';
											ob_start();
											?>
					<tr class="paramlable"><td><?php echo wp_kses_post($OthersParametername[$name]); ?>:</td><td><strong class="paramval"><?php echo wp_kses_post($Othersvalue); ?></strong></td></tr>
											<?php
											$content = ob_get_contents();
											ob_end_clean();
											$allparametervalue_html .=$content;
										}
									}
								}
							}
							$allparametervalue_html .= '</table>';
							$response = CallAPI('GET', array('mode'=>'getprice', 'productid'=>$productid, 'producttypeid'=>$producttypeid, 'unit'=>$unit, 'width'=>$width, 'drope'=>$drope, 'widthfraction'=>$widthfraction, 'dropfraction'=>$dropfraction, 'fraction'=>$fraction, 'componentpriceid'=>$componentpriceid, 'vendorid'=>$vendorid, 'fabricid'=>$fabricid, 'colorid'=>$colorid,'stockcomponentid'=>$stockcomponentid, 'getminWidth'=>$getminWidth, 'getmaxWidth'=>$getmaxWidth, 'getminDrop'=>$getminDrop, 'getmaxDrop'=>$getmaxDrop));
			
							if (1 == $response->success) {
								$res_price = isset($response->price[0]->price) ? $response->price[0]->price:0;
								$price = $res_price+$response->componentprice;
								$res_markup_price = isset($response->price[0]->notmarkupprice) ? $response->price[0]->notmarkupprice:0;
								$itemcost = $res_markup_price+$response->newcomponentprice;

								if ('' != $extra_offer) {
									$response->priceval_no_extra_offer_cal = number_format(round($price, 2), 2);
									#calculate extra offer 
									$extra_offer_cal = ( $price / 100 ) * $extra_offer;
									$price = $price - $extra_offer_cal;
								}
		
								#subcomponent price added
								if (!empty($subcomponentprice) && $subcomponentprice > 0) {
									$price = $price + $subcomponentprice;
									if (!empty($subcomponentcostprice) && $subcomponentcostprice > 0) {
										$itemcost = $itemcost + $subcomponentcostprice;
									}
								}
		
								$vat = ( $price / 100 ) * $response->vaterate;
								$priceval = $price+$vat;
								$excl_vat = true;
								if('2' == $vatoption){
									$priceval = $price;
								}else{

									$priceval = $price+$vat;
								}
		
								$response->priceval = $priceval;//number_format(round($priceval, 2), 2);;
								$response->showprice = number_format($priceval, 2);
								$response->allparametervalue_html = $allparametervalue_html;

								$response->netprice = $price;
								$response->itemcost = $itemcost;
								$response->orgvat = $response->vaterate;
								$response->vatvalue = $vat;
								$response->grossprice = $priceval;

								$response->componentpriceid = $componentpriceid;

								$json_response = $response; 
							} else {
								$json_response = $response; 
							}
							echo wp_json_encode($json_response);
							exit;
}

if ('addtocart' == $_mode) {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$updatetocart = isset($_REQUEST['updatetocart']) ? wc_clean(wp_unslash($_REQUEST['updatetocart'])):'';
	$delivery_id = isset($_REQUEST['delivery_id']) ? wc_clean(wp_unslash($_REQUEST['delivery_id'])):'';
	
	if ('updatetocart' == $updatetocart) {
		$arr_qty = isset($_REQUEST['arr_qty']) ? wc_clean(wp_unslash($_REQUEST['arr_qty'])):'';
	} else {
		$_SESSION['delivery_id'] = '';
		$_SESSION['cart'][] = blindmatrix_get_request();
	}
	
	$cart = isset($_SESSION['cart']) ? wc_clean(wp_unslash($_SESSION['cart'])):'';
	$return_session = cart($cart, $updatetocart, $arr_qty, '', $delivery_id);
	//$json_response['return_session'] = $return_session;
	
	$total_charges_vat = isset($_SESSION['total_charges_vat']) ? wc_clean(wp_unslash($_SESSION['total_charges_vat'])):'';
	$delivery_charges_vat = isset($_SESSION['delivery_charges_vat']) ? wc_clean(wp_unslash($_SESSION['delivery_charges_vat'])):'';
	$json_response['total_charges_vat'] = number_format($total_charges_vat, 2);
	$json_response['delivery_charges_vat'] = number_format($delivery_charges_vat, 2);
	$keyid = isset($_REQUEST['keyid'] ) ? wc_clean(wp_unslash($_REQUEST['keyid'] )):'';
	if ('' != $keyid ) {
		$cart = isset($_SESSION['cart']) ? wc_clean(wp_unslash($_SESSION['cart'])):'';
		$json_response['row_totalprice'] = number_format($cart[$keyid]['totalprice'], 2);
	}

}

function bm_eco_removeitem() {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$itemid = isset($_REQUEST['itemid']) ?wc_clean(wp_unslash($_REQUEST['itemid'])):'' ;
	if ('' != $itemid ) {
		unset($_SESSION['cart'][$itemid]);
		$cart = blindmatrix_get_session();
		$_SESSION['cart'] = array_values($cart);
		$cart = blindmatrix_get_session();
		$return_session = cart($cart);
		
		$json_response['success'] = 1;
	} else {
		$json_response['success'] = 0;
	}
	echo wp_json_encode($json_response);
	exit;
}

function bm_eco_sampleOrderItem() {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	$cart = isset($_SESSION['cart']) ? wc_clean(wp_unslash($_SESSION['cart'])):'';
	if (is_array($cart) && count($cart) > 0) {
		$sampleproduct = checkForSampleId(1, $cart);
		$productcode = isset($_REQUEST['productcode'] ) ? wc_clean(wp_unslash($_REQUEST['productcode'] )):'';
		$fabricid = isset($_REQUEST['fabricid'] ) ? wc_clean(wp_unslash($_REQUEST['fabricid'] )):'';
		$colorid = isset($_REQUEST['colorid'] ) ? wc_clean(wp_unslash($_REQUEST['colorid'] )):'';
		$checkSameId = $productcode . $fabricid . $colorid;
		$sameid = checkForSameId($checkSameId, $cart);
	}

	if (1 == $sameid) {
		/*$json_response['success'] = 2;
		*/
		$json_response['success'] = 'That sample has already been added to your free sample cart';
	} elseif (8 == $sampleproduct) {
		$json_response['success'] = 'You can only add 8 to your free sample cart';
	} else {
		$productcode = isset($_REQUEST['productcode'] ) ? wc_clean(wp_unslash($_REQUEST['productcode'] )):'';
		$producttypeid = isset($_REQUEST['producttypeid'] ) ? wc_clean(wp_unslash($_REQUEST['producttypeid'] )):'';
		$fabricid = isset($_REQUEST['fabricid'] ) ? wc_clean(wp_unslash($_REQUEST['fabricid'] )):'';
		$colorid = isset($_REQUEST['colorid'] ) ? wc_clean(wp_unslash($_REQUEST['colorid'] )):'';
		$vendorid = isset($_REQUEST['vendorid'] ) ? wc_clean(wp_unslash($_REQUEST['vendorid'] )):'';
		$response = CallAPI('GET', array('mode'=>'fabriclist', 'productcode'=>$productcode, 'producttypeid'=>$producttypeid, 'fabricid'=>$fabricid, 'colorid'=>$colorid, 'vendorid'=>$vendorid));
	
		$productname_arr = explode('(', $response->product_details->productname);
		
		if ('' != $response->product_details->imagepath) {
			$productimagepath = $response->product_details->imagepath;
		} else {
			$productimagepath = untrailingslashit( plugins_url( '/', __FILE__ ) ) . '/vendor/Shortcode-Source/image/blinds/no-image.jpg';
		}
		$_REQUEST['product_code'] = isset($_REQUEST['productcode']) ? wc_clean(wp_unslash($_REQUEST['productcode'])) :'';
		$_REQUEST['productid'] = $response->product_details->productid;
		$_REQUEST['productname'] = trim($productname_arr[0]);
		$supplier = ' (Supplier: ' . $response->product_details->fabricsupplier . ')';
		$_REQUEST['colorname'] = $response->product_details->colorname . $supplier;
		$_REQUEST['imagepath'] = $productimagepath;
		$_REQUEST['producttypeid'] = isset($_REQUEST['producttypeid']) ? wc_clean(wp_unslash($_REQUEST['producttypeid'])):'';
		$_REQUEST['fabricid'] = isset($_REQUEST['fabricid']) ? wc_clean(wp_unslash($_REQUEST['fabricid'])):'';
		$_REQUEST['colorid'] = isset($_REQUEST['colorid']) ? wc_clean(wp_unslash($_REQUEST['colorid'])):'';
		$_REQUEST['vendorid'] = isset($_REQUEST['vendorid']) ? wc_clean(wp_unslash($_REQUEST['vendorid'])):'';
		$_REQUEST['fraction'] = $response->product_details->fraction;
		$_REQUEST['productTypeSubName'] = $response->product_details->productTypeSubName;
		$_REQUEST['company_name'] = get_bloginfo( 'name' );
		$_REQUEST['qty'] = 1;
		$_REQUEST['sample'] = 1;
		$productcode = isset($_REQUEST['productcode']) ? wc_clean(wp_unslash($_REQUEST['productcode'])):'';
		$producttypeid = isset($_REQUEST['producttypeid']) ? wc_clean(wp_unslash($_REQUEST['producttypeid'])):'';
		$fabricid = isset($_REQUEST['fabricid']) ? wc_clean(wp_unslash($_REQUEST['fabricid'])):'';
		$colorid = isset($_REQUEST['colorid']) ? wc_clean(wp_unslash($_REQUEST['colorid'])):'';
		$vendorid = isset($_REQUEST['vendorid']) ? wc_clean(wp_unslash($_REQUEST['vendorid'])):'';
		$_REQUEST['sampleOrderItemId'] = $productcode . $producttypeid . $fabricid . $colorid . $vendorid;
		
		$_SESSION['cart'][] = blindmatrix_get_request();
		
		$cart = isset($_SESSION['cart']) ? wc_clean(wp_unslash($_SESSION['cart'])):'';
		$return_session = cart($cart, $updatetocart, $arr_qty, 'sample');
		$json_response['samplecartcount'] = count($cart);
		$json_response['success'] = 1;
	}
	
	echo wp_json_encode($json_response);
	exit;
}

if ('getmaxprice' == $_mode) {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	$productid = isset($_REQUEST['productid']) ? wc_clean(wp_unslash($_REQUEST['productid'])):'';
	$parameterTypeId = isset($_REQUEST['parameterTypeId']) ? wc_clean(wp_unslash($_REQUEST['parameterTypeId'])):'';
	$vendorid = isset($_REQUEST['vendorid']) ? wc_clean(wp_unslash($_REQUEST['vendorid'])):'';
	$unit = isset($_REQUEST['unit']) ? wc_clean(wp_unslash($_REQUEST['unit'])):'';
	$json_response = CallAPI('GET', array('mode'=>'getmaxprice', 'productid'=>$productid, 'parameterTypeId'=>$parameterTypeId, 'vendorid'=>$vendorid, 'unit'=>$unit));
}

function bm_eco_GetCurtainParameterTypeGroup() {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$parametertype = isset($_REQUEST['parametertype']) ? wc_clean(wp_unslash($_REQUEST['parametertype'])):'';
	$_id = isset($_REQUEST['id']) ? wc_clean(wp_unslash($_REQUEST['id'])):'';
	$productname = isset($_REQUEST['productname']) ? wc_clean(wp_unslash($_REQUEST['productname'])):'';

	$response = CallAPI('GET', array('mode'=>'GetCurtainParameterTypeGroup', 'parametertype'=>$productname));
	$curtainparametertypegroup = $response->curtainparametertypegroup;
	$json_response= array();
	$producttypedescription = $curtainparametertypegroup[$_id]->producttypedescription;
	$json_response['productTypeSubName'] = $curtainparametertypegroup[$_id]->productTypeSubName;
	$json_response['minprice'] = $curtainparametertypegroup[$_id]->minprice;
	$producttype_material_imgurl = $curtainparametertypegroup[$_id]->producttype_material_imgurl;
	ob_start();
	?>
	<div class="row row-small">
		<div class="col large-10" style="padding: 0!important;">
			<div class="product-images  relative mb-half has-hover woocommerce-product-gallery woocommerce-product-gallery--with-images woocommerce-product-gallery--columns-4 images" style="opacity: 1;">
				<figure class="woocommerce-product-gallery__wrapper product-gallery-slider has-image-zoom slider slider-nav-small mb-half " data-flickity-options='{
							"cellAlign": "center",
							"wrapAround": true,
							"autoPlay": false,
							"prevNextButtons":true,
							"adaptiveHeight": true,
							"imagesLoaded": true,
							"lazyLoad": 1,
							"dragThreshold" : 15,
							"pageDots": false,
							"rightToLeft": false       }'>
					
		<?php if (count($producttype_material_imgurl->images) > 0) : ?>
									<?php foreach ($producttype_material_imgurl->images as $key=>$images) : ?>	
										<?php
										$first_slide_class = '';
										if (0 == $key) {
											$first_slide_class = 'first';
										}
										?>
					<div class="curtain_product_slider woocommerce-product-gallery__image slide <?php echo wp_kses_post($first_slide_class); ?>">
										<?php if ('' != $images->getimage) : ?>
						<a href="<?php echo esc_url($images->getimage); ?>">
							<img  height="400" style="object-fit: none;" class="slider_img_view_tag ls-is-cached lazyloaded" src="<?php echo esc_url($images->getimage); ?>"  />
						</a>
						<?php endif; ?>
					</div>
					<?php endforeach; ?>
					<?php endif; ?>
				</figure>
			</div>
		</div>
		<div class="col large-2 large-col-first vertical-thumbnails pb-0" style="padding: 0 9.8px 19.6px!important;">
			<div class="product-thumbnails thumbnails slider-no-arrows slider row row-small row-slider slider-nav-small small-columns-4 is-draggable flickity-enabled slider-lazy-load-active" data-flickity-options='{
						  "cellAlign": "left",
						  "wrapAround": false,
						  "autoPlay": false,
						  "prevNextButtons": false,
						  "asNavFor": ".product-gallery-slider",
						  "percentPosition": true,
						  "imagesLoaded": true,
						  "pageDots": false,
						  "rightToLeft": false,
						  "contain": true
					  }'>

							<?php if (count($producttype_material_imgurl->images) > 0) : ?>
								<?php foreach ($producttype_material_imgurl->images as $images) : ?>
									<?php
									$first_slide_class = '';
									if (0 == $key) {
										$first_slide_class = 'first is-nav-selected is-selected';
									}
									?>
									<?php if ('' != $images->getimage) : ?>
				<div class="col <?php echo wp_kses_post($first_slide_class); ?>">
					<a href="javascript:;">
					<img src="<?php echo esc_url($images->getimage); ?>" width="100" height="100" class="attachment-woocommerce_thumbnail" />
					</a>
				</div>
				<?php endif; ?>
				<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
							<?php
							$json_response['image'] = ob_get_contents();
							ob_end_clean();
							ob_start();
		
							$string = $producttypedescription;
							echo wp_kses_post('<p style="display:none;" class="full_curtains_des">' . $string . '</p>');
							$string = strip_tags($string);
							if (strlen($string) > 500) {

								// truncate string
								$stringCut = substr($string, 0, 500);
								$endPoint = strrpos($stringCut, ' ');

								//if the string doesn't contain any space then it will cut without word basis.
								$string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
								$string .= '... <a class="curtains_des" href="javascript:void(0)">Read More</a>';
							}
							echo wp_kses_post('<p class="cut_curtains_des">' . $string . '</p>');
		
							$json_response['producttypedescription'] = ob_get_contents();
							ob_end_clean();
							echo wp_json_encode($json_response);
							exit;
}

						//echo wp_json_encode($json_response);

function cart( $cart, $updatetocart = '', $arr_qty = '', $orderitemtype = '', $delivery_id = '') {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$deliveryid = '';
	if ('' != $delivery_id) {
		$deliveryid = $delivery_id;
	} elseif (isset( $_SESSION['delivery_id']) && '' != wc_clean(wp_unslash($_SESSION['delivery_id']))) {
		$deliveryid = wc_clean(wp_unslash($_SESSION['delivery_id']));
	}
	
	$total=0;
	if (count($cart) > 0) {
		foreach ($cart as $key=>$i) {
			
			$componentvalue = array(); 
			$productid		= isset($_SESSION['cart'][$key]['productid']) ? wc_clean(wp_unslash($_SESSION['cart'][$key]['productid'])):'';
			$producttypeid	= isset($_SESSION['cart'][$key]['producttypeid'] ) ? wc_clean(wp_unslash($_SESSION['cart'][$key]['producttypeid'])):'';
			$vendorid		= isset($_SESSION['cart'][$key]['vendorid']) ? wc_clean(wp_unslash($_SESSION['cart'][$key]['vendorid'])):'';
			$unit 			= isset($_SESSION['cart'][$key]['unit']) ? wc_clean(wp_unslash($_SESSION['cart'][$key]['unit'])):'';
			$width 			= isset($_SESSION['cart'][$key]['width']) ? wc_clean(wp_unslash($_SESSION['cart'][$key]['width'])):'';
			$drope 			= isset($_SESSION['cart'][$key]['drope']) ? wc_clean(wp_unslash($_SESSION['cart'][$key]['drope'])):'';
			$widthfraction	= isset($_SESSION['cart'][$key]['widthfraction']) ? wc_clean(wp_unslash($_SESSION['cart'][$key]['widthfraction'])):'';
			$dropfraction	= isset($_SESSION['cart'][$key]['dropfraction']) ? wc_clean(wp_unslash($_SESSION['cart'][$key]['dropfraction'])):'';
			$fraction		= isset($_SESSION['cart'][$key]['fraction']) ? wc_clean(wp_unslash($_SESSION['cart'][$key]['fraction'])):'';
			$componentvalue	= isset($_SESSION['cart'][$key]['Componentvalue']) ? wc_clean(wp_unslash($_SESSION['cart'][$key]['Componentvalue'])):'';
			$extra_offer	= isset($_SESSION['cart'][$key]['extra_offer']) ? wc_clean(wp_unslash($_SESSION['cart'][$key]['extra_offer'])):'';
			$sample			= isset($_SESSION['cart'][$key]['sample']) ? wc_clean(wp_unslash($_SESSION['cart'][$key]['sample'])):'';
			
			$componentpriceid ='';
			if (!empty($componentvalue)) {
				$compid ='';
				foreach (call_user_func_array('array_merge', $componentvalue) as $keyval) {
					$comp 			= explode('~', $keyval);
					$compid 	.= $comp[0] . ',';
					$compname 		= $comp[1];
				}
				$componentpriceid = rtrim($compid , ','); 
			}
				
			$getprice_response = CallAPI('GET', array('mode'=>'getprice', 'productid'=>$productid, 'producttypeid'=>$producttypeid, 'unit'=>$unit, 'width'=>$width, 'drope'=>$drope, 'widthfraction'=>$widthfraction, 'dropfraction'=>$dropfraction, 'fraction'=>$fraction, 'componentpriceid'=>$componentpriceid, 'vendorid'=>$vendorid));
			$price = $getprice_response->price[0]->price+$getprice_response->componentprice;
			$itemcost = $getprice_response->price[0]->notmarkupprice+$getprice_response->componentprice;

			$priceval = $price;
			
			if ('' != $extra_offer) {
				$priceval_no_extra_offer_cal = round($priceval, 2);
				#calculate extra offer 
				$extra_offer_cal = ( $priceval / 100 ) * $extra_offer;
				$priceval = $priceval - $extra_offer_cal;
			}
			
			if ('updatetocart' == $updatetocart) {
				$_SESSION['cart'][$key]['qty'] = $arr_qty[$key];
			}
			
			$totalpriceval = ( $priceval ) * isset($_SESSION['cart'][$key]['qty']) ? $_SESSION['cart'][$key]['qty']:'';
			$vaterate = (int)$getprice_response->vaterate;
			if($totalpriceval != ""){
				$totalpriceval = (int)$totalpriceval;
			}else{
				$totalpriceval = 0;
			}
			$vat = ( $totalpriceval / 100 ) * $vaterate;
			$vat = round($vat, 2);
			
			$totalprice = $totalpriceval+$vat;
			
			$total = $total + $totalprice;				

			$_SESSION['cart'][$key]['priceval'] = round(( $priceval ), 2);
			$_SESSION['cart'][$key]['totalprice'] = round(( $totalprice ), 2);
			
			$netprice = $price;
			$vatvalue = $vat;
			$grossprice = $totalprice;
			
			$_SESSION['cart'][$key]['netprice'] = $price;
			$_SESSION['cart'][$key]['itemcost'] = $itemcost;
			$_SESSION['cart'][$key]['orgvat'] = $getprice_response->vaterate;
			$_SESSION['cart'][$key]['vatvalue'] = $vat;
			$_SESSION['cart'][$key]['grossprice'] = $totalprice;
		}
		
		$resdeliverydetails = CallAPI('GET', array('mode'=>'getdeliverycostdetails','sel_delivery_id'=>$deliveryid,'netprice'=>$total));
		
		if ('sample' != $orderitemtype) {
			$defaultdeliverydetails = $resdeliverydetails->defaultdeliverydetails->cost;
			$vat = ( $defaultdeliverydetails / 100 ) * $getprice_response->vaterate;
			$addvatdefaultdeliverydetails = $defaultdeliverydetails+$vat;	

			$_SESSION['total'] = round($total, 2); 
			$_SESSION['total_charges'] = round(( $total+$resdeliverydetails->defaultdeliverydetails->cost ), 2);
			$_SESSION['delivery_charges'] = round($resdeliverydetails->defaultdeliverydetails->cost, 2); 
			$_SESSION['total_charges_vat'] = round(( $total+$addvatdefaultdeliverydetails ), 2);
			$_SESSION['delivery_charges_vat'] = round($addvatdefaultdeliverydetails, 2);
			$_SESSION['delivery_charges_name'] = $resdeliverydetails->defaultdeliverydetails->name;
			$_SESSION['delivery_charges_id'] = $resdeliverydetails->defaultdeliverydetails->id;
			$_SESSION['delivery_id'] = $resdeliverydetails->defaultdeliverydetails->id;
		}
	} else {
		unset($_SESSION['total']);
		unset($_SESSION['total_charges']);
		unset($_SESSION['delivery_charges']);
		unset($_SESSION['total_charges_vat']);
		unset($_SESSION['delivery_charges_vat']);
	}
	
	$sampleproduct = checkForSampleId(1, $_SESSION['cart']);
	if (count($_SESSION['cart']) == $sampleproduct) {
		unset($_SESSION['total']);
		unset($_SESSION['total_charges']);
		unset($_SESSION['delivery_charges']);
		unset($_SESSION['total_charges_vat']);
		unset($_SESSION['delivery_charges_vat']);
	}
	
	return $resdeliverydetails;

}

function pagenation( $_page, $_per_page, $total_rows) {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	if (isset($_page) && !empty($_page)) {
		$currentPage = $_page;
	} else {
		$currentPage = 1;
	}
	$lastPage = ceil($total_rows/$_per_page);
	$firstPage = 1;
	$nextPage = $currentPage + 1;
	$previousPage = $currentPage - 1;
	
	if (isset($currentPage) && 1 != $currentPage) {
		$show_page = $currentPage;//it will telles the current page
		if ($show_page > 0 && $show_page <= $lastPage) {
			$start_record = ( $show_page - 1 ) * $_per_page;
			$end_record = $start_record + $_per_page;
	
		} else {
			// error - show first set of results
			$start_record = 0;
			$end_record = $_per_page;
		}
	} else {
		// if page isn't set, show first set of results
		$start_record = 0;
		$end_record = $_per_page;
	}
	if ($total_rows < $end_record) {
		$end_record = $total_rows;
	}
	
	$pagination_html = '<ul class="page-numbers nav-pagination links text-center">';
	if ($currentPage != $firstPage) {
		ob_start();
		?>
		<li><a href="javascript:;" class="prev page-number" onclick="pagination('<?php echo wp_kses_post($firstPage); ?>');"><i class="icon-angle-left"></i></a></li>			
								<?php
								$content = ob_get_contents();
								ob_end_clean();
								$pagination_html .= $content;
	}
	if ($currentPage >= 2) {
		ob_start();
		?>
		<li><a href="javascript:;" class="prev page-number" onclick="pagination('<?php echo wp_kses_post($previousPage); ?>');"><?php echo wp_kses_post($previousPage); ?></a></li>			
								<?php
								$content = ob_get_contents();
								ob_end_clean();
								$pagination_html .=$content;
	}
	ob_start();
	?>
	<li><a href="javascript:;" aria-current="page" onclick="pagination('<?php echo wp_kses_post($currentPage); ?>');" class="page-number current"><?php echo wp_kses_post($currentPage); ?></a></li>				
							<?php
							$content = ob_get_contents();
							ob_end_clean();
							$pagination_html .= $content ;
							if ($currentPage != $lastPage) {
								ob_start();
								?>
		<li><a href="javascript:;" class="page-number" onclick="pagination('<?php echo wp_kses_post($nextPage); ?>');"><?php echo wp_kses_post($nextPage); ?></a></li>
			<li><a href="javascript:;" class="next page-number" onclick="pagination('<?php echo wp_kses_post($lastPage); ?>');"><i class="icon-angle-right"></i></a></li>			
								<?php
								$content = ob_get_contents();
								ob_end_clean();
								$pagination_html .= $content;
							}
							$pagination_html .= '</ul>';

							return $pagination_html;
}

function pagination( $_page, $_per_page, $count) {
	$json_response = array();
	global $blinds_config;
	$_mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])):'';
	$https = isset($_SERVER['HTTPS']) ? wc_clean(wp_unslash($_SERVER['HTTPS'])):'';
	$http_host = isset($_SERVER['HTTP_HOST']) ? wc_clean(wp_unslash($_SERVER['HTTP_HOST'])):'';
	$request_uri = isset($_SERVER['REQUEST_URI']) ? wc_clean(wp_unslash($_SERVER['REQUEST_URI'])):'';
	$domain_link = $https && 'on' == $https ? 'https' : 'http' . "http://$http_host$request_uri"; 
	$get_site_url = get_site_url();
	
	$output = '<ul class="page-numbers nav-pagination links text-center">';
	if (!isset($_page)) {
		$_page = 1;
	}
	if (0 != $_per_page) {
		$_pages = ceil($count/$_per_page);
	}
	
	//if pages exists after loop's lower limit
	if ($_pages>1) {
		if (( $_page-3 )>0) {
			ob_start();
			?>
			<li><a href="javascript:;" class="prev page-number" onclick="pagination('1');"><i class="icon-angle-left"></i></a></li>		
			<?php
			$content = ob_get_contents();
			ob_end_clean();
			$output .= $content;
		}
		if (( $_page-3 )>1) {
			$output = $output . '...';
		}
	
		//Loop for provides links for 2 pages before and after current page
		for ($i=( $_page-3 ); $i<=( $_page+4 ); $i++) {
			if ($i<1) {
				continue;
			}
			if ($i>$_pages) {
				break;
			}
			if ($_page == $i) {
				ob_start();
				?>
				<li><a href="javascript:;" aria-current="page" onclick="pagination('<?php echo wp_kses_post($i); ?>');" class="page-number current"><?php echo wp_kses_post($i); ?></a></li>
										<?php
										$content  = ob_get_contents();
										ob_end_clean();
										$output .= $content;
			} else {			
				ob_start();
				?>
				<li><a href="javascript:;" aria-current="page" onclick="pagination('<?php echo wp_kses_post($i); ?>');" class="page-number"><?php echo wp_kses_post($i); ?></a></li>
										<?php
										$content  = ob_get_contents();
										ob_end_clean();
										$output .= $content;
			}
		}
	
		//if pages exists after loop's upper limit
		if (( $_pages-( $_page+2 ) )>1) {
			$output = $output . '...';
		}
		if (( $_pages-( $_page+2 ) )>0) {
			if ($_page == $_pages) {
				ob_start();
				?>
				<li><a href="javascript:;" aria-current="page" onclick="pagination('<?php echo wp_kses_post($_pages); ?>');" class="page-number current"><i class="icon-angle-right"></i></a></li>
										<?php
										$content = ob_get_contents();
										ob_end_clean();
										$output .= $content;
			} else {
				ob_start();
				?>
				<li><a href="javascript:;" aria-current="page" onclick="pagination('<?php echo wp_kses_post($_pages); ?>');" class="page-number"><i class="icon-angle-right"></i></a></li>
										<?php
										$content = ob_get_contents();
										ob_end_clean();
										$output .= $content;
			}
		}
	
	}
	$output .= '</ul>';
	return $output;
}

function bm_eco_blindmatrix_copy_cart_item(){
	try{
		if(!isset($_REQUEST)){
			throw new Exception( 'Invalid Data' );
		}
		
		$cart_item_key = isset($_REQUEST['cart_item_key']) ? wc_clean(wp_unslash($_REQUEST['cart_item_key'])):''; 
		if(!$cart_item_key){
			throw new Exception( 'Invalid Data' );
		}
		
		$cart_item = WC()->cart->get_cart_item($cart_item_key);
		if(empty($cart_item)){
			throw new Exception( 'Invalid Data' );
		}
		
		$product_id = isset($cart_item['product_id']) ? $cart_item['product_id']:0;
		if(!$product_id){
			throw new Exception( 'Invalid Data' );
		}
		$quantity =  isset($cart_item['quantity']) ? $cart_item['quantity']:0;
		$custom_price = isset($cart_item['my_new_price']) ? $cart_item['my_new_price']:0;
		$current_post_title = isset($cart_item['current_post_title']) ? $cart_item['current_post_title']:'';
		$product_my_blind_attr = isset($cart_item['product_my_blind_attr']) ? $cart_item['product_my_blind_attr']:'';
		$new_product_image_path = isset($cart_item['new_product_image_path']) ? $cart_item['new_product_image_path']:'';
		$new_product_url = isset($cart_item['new_product_url']) ? $cart_item['new_product_url']:'';
		$vaterate = isset($cart_item['vaterate']) ? $cart_item['vaterate']:'';
		$blinds_order_item_data = isset($cart_item['blinds_order_item_data']) ? $cart_item['blinds_order_item_data']:array();
		
		WC()->cart->add_to_cart($product_id,$quantity,'',false,array('my_new_price'=>$custom_price,'current_post_title'=>$current_post_title,'product_my_blind_attr'=>$product_my_blind_attr,'new_product_image_path'=>$new_product_image_path,'new_product_url'=>$new_product_url,'vaterate'=>$vaterate,'blinds_order_item_data'=>$blinds_order_item_data,'copy_product_timestamp' => time()));
		
		wp_send_json_success(array('success' => true));
		}catch(Exception $ex){
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
}

function bm_eco_GetCurtainProductDetail(){
	$url_producttypename = isset($_REQUEST['url_producttypename']) ? wc_clean(wp_unslash($_REQUEST['url_producttypename'])):'';
	$url_producttypeid = isset($_REQUEST['url_producttypeid']) ? wc_clean(wp_unslash($_REQUEST['url_producttypeid'])):'';
	$url_productid = isset($_REQUEST['url_productid']) ? wc_clean(wp_unslash($_REQUEST['url_productid'])):'';
	$url_frame = isset($_REQUEST['url_frame']) ? wc_clean(wp_unslash($_REQUEST['url_frame'])):'';
	$url_curtains_config = isset($_REQUEST['url_curtains_config']) ? wc_clean(wp_unslash($_REQUEST['url_curtains_config'])):'';
	$per_page = isset($_REQUEST['per_page']) ? wc_clean(wp_unslash($_REQUEST['per_page'])):'';
	$page = isset($_REQUEST['page']) ? wc_clean(wp_unslash($_REQUEST['page'])):'';
	
	$url_product_id = isset($_REQUEST['product_id']) ? wc_clean(wp_unslash($_REQUEST['product_id'])):'';
	$fabric_pid = isset($_REQUEST['fabric_pid']) ? wc_clean(wp_unslash($_REQUEST['fabric_pid'])):'';
	$getallfilterproduct = get_option('productlist', true);
	$product_list_array = $getallfilterproduct->curtain_product_list;
	$id = array_search($fabric_pid, array_column($product_list_array, 'productid'));
	$getcategorydetails = $product_list_array[$id]->getcategorydetails;
	
	$cate_value = array();	
	$json_response = array(); 
	if (count($getcategorydetails->subcategorydetails) > 0){
		foreach($getcategorydetails->subcategorydetails as $categorydetails){
		// if($url_product_id == $categorydetails->category_id){
			if(in_array($categorydetails->category_id,$url_product_id)){
				if($cate_value){
					$cate_value = array_merge($cate_value , (array)$categorydetails->category_values);
					}else{
					$cate_value = (array)$categorydetails->category_values;
				}
			}
		}
	}
	$cate_value = array_values(array_filter((array_unique($cate_value))));
	$response = CallAPI("GET", $post=array("mode"=>"GetCurtainProductDetail", "parametertypeid"=>$url_producttypeid, "productid"=>$url_productid));
 
	foreach($response->product_details->ProductsParameter as $ProductsParameter){
		 if($ProductsParameter->parameterListId == 16 && $ProductsParameter->ecommerce_show == 1){ 
			if(count($ProductsParameter->CurtainFabricvalue) > 0) {
				$CurtainFabric = $ProductsParameter->CurtainFabricvalue ;
				 
			}
		}
		elseif($ProductsParameter->parameterListId == 21 && $ProductsParameter->ecommerce_show == 1){
			if(count($ProductsParameter->CurtainFabricvalue1) > 0) {
				$CurtainFabric_cont = $ProductsParameter->CurtainFabricvalue1 ;
		 	}
		}
	}        

	$CurtainFabric_response = array_merge($CurtainFabric,$CurtainFabric_cont);
	
	if(is_array($cate_value) && !empty($cate_value) && !empty($CurtainFabric_response)){
		$CurtainFabric_res = array();
		foreach($CurtainFabric_response as $cF_res){
			foreach($cate_value as $ct_val){
				if($ct_val == $cF_res->fabricid.$cF_res->colorid){
					$CurtainFabric_res[] = $cF_res;
				}
			}
		}
		$CurtainFabric_response = $CurtainFabric_res;
	}

	// Calculate total number of records, and total number of pages
	$total_records = count($CurtainFabric_response);
	$total_rows   = ceil($total_records / $per_page);

	// Validation: Page to display can not be greater than the total number of pages
	if ($page > $total_rows) {
		$page = $total_rows;
	}

	// Validation: Page to display can not be less than 1
	if ($page < 1) {
		$page = 1;
	}

	// Calculate the position of the first record of the page to display
	$offset = ($page - 1) * $per_page;

	// Get the subset of records to be displayed from the array
	$data = array_slice($CurtainFabric_response, $offset, $per_page);		
		 
		$json_response['pagination_html'] = pagination($_POST['page'],$per_page,$total_records);
		
		ob_start();

		foreach($data as $curtainfabric):
		$url_productviewurl = get_bloginfo('url').'/'.$url_curtains_config.'/'.$url_producttypename.'/'.$url_productid.'/'.$url_producttypeid.'/'.$curtainfabric->fabricid.$curtainfabric->colorid.'/';					           
			if($curtainfabric->imagepath != ''){
				$curtainfabric->imagepath = $curtainfabric->imagepath;
				$curtainfabric_data_img = $curtainfabric->imagepath;
			}else{
				$curtainfabric->imagepath = get_stylesheet_directory_uri().'/icon/no-image.jpg';
				 $curtainfabric_data_img = '';
				}
		?>
		
			<div class="product-small col has-hover product type-product post-149 status-publish first instock product_cat-tops product_cat-women has-post-thumbnail shipping-taxable purchasable product-type-simple row-box-shadow-2 product_list_blinds_row enableSearch">
					<div class="product-small box " >
						<div class="box-image" >
							<div class="image-fade_in_back">
								<a href="<?echo esc_url($url_productviewurl);?>">
									<img src="<?php echo esc_url($url_frame);?>" alt="curtain image" style="background-image:url('<?php echo esc_url($curtainfabric->imagepath); ?>')" class="product-frame frame_backgound">
								</a>
							</div>
						</div>
						<div class="product-info-container">
								<div class="product details product-item-details">
										<h2 class="product name product-item-name" style="margin-bottom:0px">
											<a class="product-item-link" href="<?echo esc_url($url_productviewurl);?>" style="line-height:1.3;">
											<?php echo wp_kses_post($curtainfabric->fabricname); ?> <?php echo wp_kses_post($curtainfabric->colorname); ?></a>
										</h2>
								</div>
						</div>
						<a href="<?echo esc_url($url_productviewurl);?>" class="button primary is-outline box-shadow-2 box-shadow-2-hover relatedproduct" style="border-color: #002746;color:#fff;padding: 0px 0.3em;font-size: 11px; margin: 0 !important;background-color: #002746;">
							<i class="icon-shopping-cart"></i> <span style="padding: 0px !important;margin:5px 0 !important">Buy Now</span>
						</a>
					</div>
			</div>
		 <?php endforeach; 
		
		$json_response['html'] = ob_get_contents();
		ob_end_clean();
		echo wp_json_encode($json_response);
		exit;
}

function bm_eco_material_image_action(){
    $mode = isset($_REQUEST['mode']) ? wc_clean(wp_unslash($_REQUEST['mode'])) : '';
    $productcode = isset($_REQUEST['productcode']) ? wc_clean(wp_unslash($_REQUEST['productcode'])) : '';
    $producttypeid = isset($_REQUEST['producttypeid']) ? wc_clean(wp_unslash($_REQUEST['producttypeid'])) : '';
    $fabricid = isset($_REQUEST['fabricid']) ? wc_clean(wp_unslash($_REQUEST['fabricid'])) : '';
    $colorid = isset($_REQUEST['colorid']) ? wc_clean(wp_unslash($_REQUEST['colorid'])) : '';
    $vendorid = isset($_REQUEST['vendorid']) ? wc_clean(wp_unslash($_REQUEST['vendorid'])) : '';
    $producturl = isset($_REQUEST['producturl']) ? wc_clean(wp_unslash($_REQUEST['producturl'])) : '';
    $productname = isset($_REQUEST['productname']) ? wc_clean(wp_unslash($_REQUEST['productname'])) : '';

    $response = CallAPI("GET", $post = array("mode" => "getProductParameterDetails", "productname" => $productname, "productcode" => $productcode, "producttypeid" => $producttypeid, "fabricid" => $fabricid, "colorid" => $colorid, "vendorid" => $vendorid));
    if("Create no sub sub parameter" == $response->getproductdetails->productcategory){
		$fabricid = $colorid;
		$colorid = 0;
	}

	$product_detail_response = CallAPI("GET", $post = array("mode" => "getproductdetail", "productcode" => $productcode, "producttypeid" => $producttypeid, "fabricid" => $fabricid, "colorid" => $colorid, "vendorid" => $vendorid));
    $product_material_images = $product_detail_response->product_details->getmaterialimages->materialImages;
	if(!is_array($product_material_images) || empty($product_material_images)){
		echo wp_json_encode(array('key' => ''));
    	exit;
	}

    ob_start();
    ?>
    <div class="row large-columns-4 medium-columns-3 small-columns-3 row-small slider row-slider slider-nav-reveal slider-nav-push" data-flickity-options='{"imagesLoaded": true, "groupCells": "100%", "dragThreshold": 5, "cellAlign": "left", "wrapAround": false, "prevNextButtons": true, "percentPosition": true, "pageDots": false, "rightToLeft": false, "autoPlay": false}'>
    <?php
    $frames = is_array($response->frameImages) && !empty($response->frameImages) ? $response->frameImages : array();
    end($frames);
    $key = key($frames);
    if (isset($frames[$key])) {
        unset($frames[$key]);
    }

    $_frame_images = array_merge((array)$frames, (array)$product_material_images);
    foreach ($_frame_images as $key => $image): 
        $blinds_image_key = isset($_blinds_plugin_data['blinds_image_key']) && !empty($_blinds_plugin_data['blinds_image_key']) ? $_blinds_plugin_data['blinds_image_key'] : 0;
    ?>
    <div style="padding: 0!important;padding-left: 10px!important" class="product-small col has-hover product type-product post-149 status-publish first instock product_cat-tops product_cat-women has-post-thumbnail shipping-taxable purchasable product-type-simple row-box-shadow-2 row-box-shadow-3-hover">
        <a data-blinds="<?php echo($key); ?>" class="multiple-frame-list-button <?php if ($key == $blinds_image_key) { echo("selected"); } ?>" >
            <img src="<?php echo $image->getimage; ?>" >
        </a>
    </div>
    <?php endforeach; ?>
    </div>

    <?php
    $htmlcontent = ob_get_contents();
    ob_end_clean();

    echo wp_json_encode(array('key' => $htmlcontent));
    exit;
}
