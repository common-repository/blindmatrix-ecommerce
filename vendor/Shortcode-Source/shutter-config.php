<?php
$blindmatrix_settings = get_option( 'option_blindmatrix_settings',true );	
if(isset($blindmatrix_settings['menu_product_type']) && in_array( 'Shutters' , $blindmatrix_settings['menu_product_type'])){
global $shutters_page;
global $shutters_type_page;
global $shutter_visualizer_page;
$producttypename = str_replace('-',' ',get_query_var("ptn"));
$producttypeid = get_query_var("ptid");
$producttypepriceid = get_query_var("ptpid");

$url_exp = explode('/',$_SERVER['REQUEST_URI']);
$search_color = str_replace('-',' ',$url_exp['5']);
$search_unit = isset($url_exp['6']) ? $url_exp['6']:'';

$response = CallAPI("GET", $post=array("mode"=>"GetShutterProductDetail", "parametertypeid"=>$producttypeid, "parametertypepriceid"=>$producttypepriceid));

$shutter_type = $response->product_details->shutterparametertypedetails->shutter_type;

$producttype_price = '';
$producttype_price_name = '';
$producttype_price_list = $response->product_details->shutterparametertypedetails->producttype_price_list;
if(!empty($producttype_price_list)){
    foreach($producttype_price_list as $producttype_price_list){
        if($producttype_price_list->parameterTypeSubSubId == $producttypepriceid){
            $producttype_price = $producttype_price_list->itemPrice;
            $producttype_price_name = $producttype_price_list->itemName;
        }
    }
}


$shuttercolorList = $response->product_details->shuttercolorlist->shuttercolorList;

$index = array_search($search_color, array_column($shuttercolorList, 'fabric_name'));
if ($index !== false){
    $default_fabricid = $shuttercolorList[$index]->fabricid;
    $default_parameterName = $shuttercolorList[$index]->parameterName;
    $default_imagepath = $shuttercolorList[$index]->imagepath;
}else{
    $default_fabricid = $shuttercolorList[0]->fabricid;
    $default_parameterName = $shuttercolorList[0]->parameterName;
    $default_imagepath = $shuttercolorList[0]->imagepath;
}

$checkgetid = $producttypeid;
$checkresponseid = $response->product_details->shutterparametertypedetails->parameterTypeId;

$producttypename = $response->product_details->shutterparametertypedetails->productTypeSubName;
$show_in_ecommerce = false;
?>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script src="/wp-content/plugins/blindmatrix-ecommerce/assets/js/pace-master/pace.js"></script>
<link href="/wp-content/plugins/blindmatrix-ecommerce/assets/js/pace-master/themes/blue/pace-theme-minimal.css" rel="stylesheet" />


<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<link rel="stylesheet" href="/wp-content/plugins/blindmatrix-ecommerce/assets/css/configurator.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?php if($checkgetid == $checkresponseid):?>
<form name="submitform" id="submitform"  class="tooltip-container variations_form cart" action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="blindstype" id="blindstype" value="<?php echo $response->product_details->blindstype; ?>">
    <input type="hidden" name="product_code" id="product_code" value="<?php echo $response->product_details->product_no; ?>">
    <input type="hidden" name="productid" id="productid" value="<?php echo $response->product_details->productid; ?>">
    <input type="hidden" name="productname" id="productname" value="<?php $productname_arr = explode("(", $response->product_details->productname); echo trim($productname_arr[0]); ?>">
    <input type="hidden" name="producttypepriceid" id="producttypepriceid" value="<?php echo $producttypepriceid;?>">
    <input type="hidden" name="producttypeid" id="producttypeid" value="<?php echo $producttypeid; ?>">
    <input type="hidden" name="imagepath" id="imagepath" value="<?php echo plugin_dir_url( __DIR__ ).'Shortcode-Source/image/blinds/no-image.jpg'; ?>">
    <input type="hidden" name="producttypename" id="producttypename" value="<?php echo $producttypename; ?>">
    <input type="hidden" name="fraction" id="fraction" value="<?php echo $response->product_details->fraction;?>">
    <input type="hidden" name="mode" id="mode" value="">
    <input type="hidden" name="company_name" id="company_name" value="<?php echo get_bloginfo( 'name' );?>">
    <input type="hidden" name="extra_offer" id="extra_offer" value="<?php echo $response->product_details->extra_offer; ?>">
    <input type="hidden" name="type" id="type" value="custom_add_cart_blind">
    <input type="hidden" name="action" id="action" value="blind_publish_process">
    <input type="hidden" name="fabricid" id="fabricid" value="<?php echo $producttypeid;?>">
    <input type="hidden" name="shutterproduct" id="shutterproduct" value="Yes">
    <input type="hidden" name="producttypesub" id="producttypesub">
    <?php if(count($response->product_details->ProductsParameter) > 0):?>
    <?php foreach($response->product_details->ProductsParameter as $ProductsParameter):?>							
    <?php if($ProductsParameter->parameterListId == 10): ?>
    <input type="hidden" name="producttypeparametername" id="producttypeparametername" value="<?php echo $ProductsParameter->parameterName; ?>">
    <input type="hidden" name="producttypeparametervalue" id="producttypeparametervalue" value="<?php echo $producttypename; ?>">
    <input type="hidden" class="shuttertype" name="set_shuttertype" id="set_shuttertype" value="<?php echo $shutter_type; ?>">
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endif; ?>
    <input type="hidden" name="producttype_price_name" id="producttype_price_name" value="<?php echo $producttype_price_name;?>">
	<input type="hidden" name="action" value="getparameterdetails">
	<div class="headContainer" >
		<div class="grid row" style="padding: 0px;">
			<div class="row head row-collapse"><a href="/shop" class="back configuratorback"> </a><a style="margin: 0;" href="/shutter-type" target="_self" class="button secondary is-link is-smaller lowercase">
				<i class="icon-angle-left"></i>  <span>All Styles</span>
			</a>
			<a style="margin: 0;" href="<?php echo site_url(); ?>/<?php echo $shutters_page; ?>/<?php echo get_query_var("ptn");	?>/<?php echo $producttypeid;?>" target="_self" class="button secondary is-link is-smaller lowercase">
				<i class="icon-angle-left"></i>  <span>Back to <?php echo $producttypename; ?></span>
			</a>
					<h1 class="heading" style="font-size: 2.3rem;">Design Your  Shutters In 4 Easy Steps</h1>
				<p style="font-size: 14px;line-height: 2rem;">Simply tell us what size of shutters you want, the number of panels, and the colour. You can customise your shutter to suit your room or interior style.
	<br>Not measured up yet? Check out our easy to follow guides to get you started</p>
			</div>
		</div>
	</div>
 <div class="row cusprodname" style="padding-left: 10px;margin:auto;" >
        <h1 style="margin: 0;" class="product-title product_title entry-title prodescprotitle prodescprotitle_shutter">Your <?php echo $response->product_details->shutterparametertypedetails->productTypeSubName;?> Shutters in <?php echo $producttype_price_name;?></h1>
    </div>
    <div class="row col-inner configurator-options-shutter" style="margin:auto;">
        <div class="row row-full-width configurator shutters-configurator js-shutters-configurator cuspricevalue" style="padding-top: 0px;padding-bottom: 0px;margin:auto;">
            <div class="col medium-6 small-12 large-6 right-shuttercol" style="padding: 0px !important;">
                <div class="col-inner configurator-preview-col-inner product-info summary col-fit col entry-summary product-summary">
                    <div class="configurator-preview">
                        <div class="configurator-toggle-slats js-toggleShutters">
							<div class="toggle_slats">
                                <input type="radio" id="choice1" name="choice" value="close" onclick="slats('close');">
                                <label  slatsclass="slatslabel" for="choice1">Close slats</label>
                                
                                <input type="radio" id="choice2" name="choice" value="open" onclick="slats('open');">
                                <label slatsclass="slatslabel" for="choice2">Open slats</label>
                                
                                <div style="font-size: 14px;border-radius: 0px 10px 10px 0;" id="flap"><span class="content">open</span></div>
                            </div>
                            
                        </div>
                        <!-- Shutters Preview -->
                        <div class="preview" style="height: 329px;">
                            <div class="scalingWrapper">
                                <div id="shutterspreview" class="panels-container" style="float:left;">
                                    <div class="panels" data-panels="1">
                                        <div class="panel hingeLeft panel--hinge-left" style="min-width: 330px;">
                                            <div class="midpane">
                                                <div class="topRail">
                                                    <span class="rail-bg" style="min-height: 30px; height: 30px;"></span>
                                                    <span class="mouseHole-top"></span>
                                                </div>
                                                
                                                <div class="midpane-fill"></div>
                                                <div class="bottomRail">
                                                    <span class="rail-bg" style="min-height: 30px; height: 30px;"></span>
                                                    <span class="mouseHole-bottom" style="display:unset;"></span>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
						<p class="preview-desc">  Diagram is for illustration only. Exact number of slats may change. </p>
                    </div>
                    
                </div>
            </div>
			<div class="col medium-6 small-12 large-6 left-shuttercol" style="background: #f7f6f6;">
                <div class="col-inner">
                    <ul class="woocommerce-error message-wrapper" style="display:none;" role="alert"></ul>
					<table style="margin-bottom: 0;" class="variations" cellspacing="0">
							<tbody  class="configurator-options-dimensions is-active" data-row="0">
								<tr class="headertable measure">
									<td class="config-heading-td heading-td-bottom" colspan="2" > <h3 class="config-heading">
										<span class="config-count">1 </span>
										Choose your dimensions
									</h3><h3 class="config-heading edit"><i class="icon-pen-alt-fill"></i> Edit</h3></td>
								</tr>
								<tr style="display:flex;" class="subchild measure">
									<td colspan="2" class="value" style="text-align: center;padding: 15px 15px 0;">
										<span class="wpcf7-form-control-wrap radio-726">
											<span class="wpcf7-form-control wpcf7-radio">
												<span class="wpcf7-list-item first">
													<input onclick="showorderdetails();" checked name="unit" id="unit_0" class="js-unit" value="mm" <?php echo $response->product_details->checkMm; ?> type="radio">
													<label for="unit_0">mm</label>
												</span>
												<span class="wpcf7-list-item">
													<input onclick="showorderdetails();" name="unit" id="unit_1" class="js-unit" value="cm" <?php echo $response->product_details->checkCm; ?> type="radio">
													<label for="unit_1">cm</label>
												</span>
												<span class="wpcf7-list-item last">
													<input onclick="showorderdetails();" name="unit" id="unit_2" class="js-unit" value="inch" <?php echo $response->product_details->checkInch; ?> type="radio">
													<label for="unit_2">inches</label>
												</span>
											</span>
										</span>
									</td>
								</tr>
								
								<tr style="display:flex;" class="subchild widthdroptd">
									<?php if(count($response->product_details->ProductsParameter) > 0):?>
									<?php foreach($response->product_details->ProductsParameter as $ProductsParameter):?>
									<?php if($ProductsParameter->parameterListId == 6 || $ProductsParameter->parameterListId == 22): ?>
									<?php if($ProductsParameter->ecommerce_show == 1): ?>
									<td style="width:45%;" class="widthdroptd <?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>">
										<div class="mobile_no_padding" style="padding: 10px;">
										<input type="hidden" name="widthplaceholdertext" id="widthplaceholdertext" value="<?php echo $ProductsParameter->parameterName; ?>">
										 <h4><?php echo $ProductsParameter->parameterName; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?>
										<?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
												<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
												<img style="width:12px; vertical-align: text-top;" class="" src="<?php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/info.png"></button>
												<div class="modal fade" id="info_<?php echo $ProductsParameter->parameterId;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
												  <div class="modal-dialog" role="document">
													<div class="modal-content">
													  <div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel"><?php echo $ProductsParameter->parameterName; ?></h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														  <span aria-hidden="true">&times;</span>
														</button>
													  </div>
													  <div class="modal-body">
															<?php echo($ProductsParameter->ecommoreinfo); ?>
													  </div>
													</div>
												  </div>
												</div>
												
										<?php } ?>
										</h4>
										<input placeholder="<?php echo $ProductsParameter->parameterName; ?> (<?php echo $response->product_details->default_unit_for_order; ?>)" parameterName="Width" getparameterid="<?php echo $ProductsParameter->parameterId;?>" name="width" id="width" onkeyup="checkNumeric(event,this);" onkeydown="checkNumeric(event,this);" step="1" <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>class="mandatoryvalidate"<?php endif;?> autocomplete="off" type="number">
										<select name="widthfraction" id="widthfraction" onchange="showorderdetails();" style=" margin-bottom: 1em; <?php echo $response->product_details->fractionshow;?>" class="">
											<option value="">0</option>
											<option value="1">1/8</option>
											<option value="2">1/4</option>
											<option value="3">3/8</option>
											<option value="4">1/2</option>
											<option value="5">5/8</option>
											<option value="6">3/4</option>
											<option value="7">7/8</option>
										</select>
										<input name="widthparameterId" id="widthparameterId" value="<?php echo $ProductsParameter->parameterId; ?>" type="hidden">
										<input name="widthparameterListId" id="widthparameterListId" value="<?php echo $ProductsParameter->parameterListId; ?>" type="hidden">
										<div class="clear"></div>
										<span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
										</div>
									</td>
									<?php endif; ?>
									<?php elseif($ProductsParameter->parameterListId == 7 || $ProductsParameter->parameterListId == 23): ?>
									<?php if($ProductsParameter->ecommerce_show == 1): ?>
									<td style="width:45%;" class="widthdroptd <?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>">
										<div class="mobile_no_padding" style="padding: 10px;">
										<input type="hidden" name="dropeplaceholdertext" id="dropeplaceholdertext" value="<?php echo $ProductsParameter->parameterName; ?>">
										 <h4><?php echo $ProductsParameter->parameterName; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?>
											<?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
														<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
														<img style="width:12px; vertical-align: text-top;" class="" src="<?php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/info.png"></button>
														<div class="modal fade" id="info_<?php echo $ProductsParameter->parameterId;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
														  <div class="modal-dialog" role="document">
															<div class="modal-content">
															  <div class="modal-header">
																<h5 class="modal-title" id="exampleModalLabel"><?php echo $ProductsParameter->parameterName; ?></h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																  <span aria-hidden="true">&times;</span>
																</button>
															  </div>
															  <div class="modal-body">
																	<?php echo($ProductsParameter->ecommoreinfo); ?>
															  </div>
															</div>
														  </div>
														</div>
												<?php } ?>
										</h4>
										<input placeholder="<?php echo $ProductsParameter->parameterName; ?> (<?php echo $response->product_details->default_unit_for_order; ?>)" parameterName="Drope" getparameterid="<?php echo $ProductsParameter->parameterId;?>" name="drope" id="drope" onkeyup="checkNumeric(event,this);" onkeydown="checkNumeric(event,this);" step="1" <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>class="mandatoryvalidate"<?php endif;?> autocomplete="off" type="number">
										<select name="dropfraction" id="dropfraction" onchange="showorderdetails();" style="margin-bottom: 1em; <?php echo $response->product_details->fractionshow;?>" class="">
											<option value="">0</option>
											<option value="1">1/8</option>
											<option value="2">1/4</option>
											<option value="3">3/8</option>
											<option value="4">1/2</option>
											<option value="5">5/8</option>
											<option value="6">3/4</option>
											<option value="7">7/8</option>
										</select>
										<input name="dropeparameterId" id="dropeparameterId" value="<?php echo $ProductsParameter->parameterId; ?>" type="hidden">
										<input name="dropeparameterListId" id="dropeparameterListId" value="<?php echo $ProductsParameter->parameterListId; ?>" type="hidden">
										<div class="clear"></div>
										<span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
										</div>
									</td>
									<?php endif; ?>
								</tr>	
								<?php endif; ?>
								
								<?php endforeach; ?>
								<?php endif; ?>
								</tbody>
								
							
							<?php if(count($response->product_details->ProductsParameter) > 0):?>
							<?php foreach($response->product_details->ProductsParameter as $ProductsParameter):?>
							
							<?php
							
							if( ( (strpos(strtolower($shutter_type), 'tier') !== false) || (strpos(strtolower($shutter_type), 'half') !== false) ) && strpos(strtolower($ProductsParameter->parameterName), 'mid') !== false){
							    continue;
							}
							
							if(strpos(strtolower($shutter_type), 'full solid') !== false && ( (strpos(strtolower($ProductsParameter->parameterName), 'slat') !== false) || (strpos(strtolower($ProductsParameter->parameterName), 'tilt') !== false) ) ){
							    continue;
							}
							
							$i=0;
                            $js_function = '';
                            $class_name1 = '';
						    $class_name2 = '';
						    $default_value = '0';
							$class_name_color ='';
							$img_width = '120';
						    if (strpos(strtolower($ProductsParameter->parameterName), 'panel') !== false){
							    $js_function = 'updatePanel(this);';
							    $class_name2 = 'NumberOfPanels';
							    $default_value = '1';
							}
							if (strpos(strtolower($ProductsParameter->parameterName), 'mid') !== false){
							    $js_function = 'midRail(this);';
							    $class_name2 = 'midrails';
							}
							if (strpos(strtolower($ProductsParameter->parameterName), 'slat') !== false){
							    $js_function = 'slatsize(this);';
							    $class_name1 = 'js-slatSize';
							    $class_name2 = 'SlatWidth';
								if(is_array($ProductsParameter->ProductsParametervalue)){
									sort($ProductsParameter->ProductsParametervalue);
								}
								if(isset($ProductsParameter->Componentvalue) && is_array($ProductsParameter->Componentvalue)){
									sort($ProductsParameter->Componentvalue);
								}
							}
							if (strpos(strtolower($ProductsParameter->parameterName), 'tilt') !== false){
							    $js_function = 'pushrod(this);';
							    $class_name2 = 'tiltrod';
							    $default_value = 'central';
							}
							if (strpos(strtolower($ProductsParameter->parameterName), 'hinge') !== false){
							    $js_function = 'changehingecolor(this);';
							    $class_name1 = 'shutter_color_cl';
							    $class_name2 = 'select_hingecolor_image';
							    $default_value = 'central';
								$class_name_color = 'shutter_color_container';
								$img_width = '100';
							}
							
							?>

							<?php if($ProductsParameter->parameterListId == 2 && $ProductsParameter->ecommerce_show == 1): ?>
							<?php 
							if( false !== strpos(strtolower($ProductsParameter->parameterName), 'slat') || false !== strpos(strtolower($ProductsParameter->parameterName), 'panel') || false !== strpos(strtolower($ProductsParameter->parameterName), 'tilt')  || false !== strpos(strtolower($ProductsParameter->parameterName), 'mid') ){
							
							ob_start();
							?>
							<tr class="subchild panels_and_slats <?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>" style="display:none">
								<td colspan="2" class="value">
                                    <div class="product_atributes <?php echo($class_name_color); ?>">
                                        <h4><?php echo $ProductsParameter->parameterName; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?>
											<?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
													<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
													<img style="width:12px; vertical-align: text-top;" class="" src="<?php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/info.png"></button>
													<div class="modal fade" id="info_<?php echo $ProductsParameter->parameterId;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													  <div class="modal-dialog" role="document">
														<div class="modal-content">
														  <div class="modal-header">
															<h5 class="modal-title" id="exampleModalLabel"><?php echo $ProductsParameter->parameterName; ?></h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															  <span aria-hidden="true">&times;</span>
															</button>
														  </div>
														  <div class="modal-body">
																<?php echo($ProductsParameter->ecommoreinfo); ?>
														  </div>
														</div>
													  </div>
													</div>
											<?php } ?>
										</h4>
                                       <span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
                                        <div class="product_atributes_value">
                                            <?php if(count($ProductsParameter->ProductsParametervalue) > 0): ?>
												<?php
												$multiple = false;
												if('1' == $ProductsParameter->component_select_option){
													$multiple = true;
												}
										?>
											<select name="ProductsParametervalue[<?php echo $ProductsParameter->parameterId; ?>]" class="blindmatrix-dropdown-selection blindmatrix-select2" <?php if($multiple){?>multiple="multiple" <?php } ?> >		
    										<?php if(!$multiple): ?>	
												<option value="" data-parameter_id = "<?php echo $ProductsParameter->parameterId; ?>">Choose an option</option>
											<?php endif; ?>	
											<?php foreach($ProductsParameter->ProductsParametervalue as $ProductsParametervalue):?>
    										
    										<?php if(strpos(strtolower($shutter_type), 'tier') !== false && strpos(strtolower($ProductsParameter->parameterName), 'panel') !== false):?>
    										<?php if ($ProductsParametervalue->text%2 != 0):?>
    										<?php continue;?>
    										<?php endif;?>
    										<?php endif;?>
    
    										<?php
                							if($ProductsParametervalue->getEditableListimgurl != ''){
                							    $ProductsParametervalue->getEditableListimgurl = $ProductsParametervalue->getEditableListimgurl;
                							    $data_img = $ProductsParametervalue->getEditableListimgurl;
                							}else{
                							    $ProductsParametervalue->getEditableListimgurl = plugin_dir_url( __DIR__ ).'Shortcode-Source/image/blinds/no-image.jpg';
                							    $data_img = '';
                							}
                							
                							if($ProductsParametervalue->text == $ProductsParameter->defaultValue){
                							    $default_value = strtolower($ProductsParametervalue->text);
                							}
                							$data_value = strtolower($ProductsParametervalue->text);
                							if (strpos(strtolower($ProductsParameter->parameterName), 'slat') !== false){
                							    $data_value = $i;
                							    if($ProductsParametervalue->text == $ProductsParameter->defaultValue){
                    							    $default_value = $data_value;
                    							}
                							}
                							
                							if(strpos(strtolower($shutter_type), 'tier') !== false && strpos(strtolower($ProductsParameter->parameterName), 'panel') !== false){
    										    $ProductsParameter->defaultValue = '2';
    										    $default_value = '2';
                							}
                							?>
    										<option type="radio" id="radio_<?php echo $ProductsParametervalue->value;?>" <?php if($ProductsParametervalue->text == $ProductsParameter->defaultValue): ?>selected<?php endif; ?> data-img="<?php echo $data_img; ?>" data-value="<?php echo $data_value;?>" data-jsevent="<?php echo str_replace('(this);','',$js_function); ?>" value="<?php echo $ProductsParametervalue->value; ?>~<?php echo $ProductsParametervalue->text; ?>"><?php echo $ProductsParametervalue->text; ?></option>
											<?php
											$display = false;
											if($display):
                							?>
                                            <input type="radio" name="ProductsParametervalue[<?php echo $ProductsParameter->parameterId; ?>]" id="radio_<?php echo $ProductsParametervalue->value; ?>" style="display:none;" value="<?php echo $ProductsParametervalue->value; ?>~<?php echo $ProductsParametervalue->text; ?>" <?php if($ProductsParametervalue->text == $ProductsParameter->defaultValue): ?>checked<?php endif; ?> />
                                            <label onclick="<?php echo $js_function;?>showorderdetails();" data-img="<?php echo $data_img; ?>" data-value="<?php echo $data_value;?>" class="no_of_panels_elements <?php echo $class_name1;?> <?php if($ProductsParametervalue->text == $ProductsParameter->defaultValue): ?>selected<?php endif; ?>" for="radio_<?php echo $ProductsParametervalue->value; ?>">
                                               
											    <?php if (strpos(strtolower($ProductsParameter->parameterName), 'slat') !== false):?>
											    <?php else:?>
											    <div class="" parameter_img="<?php echo $data_img; ?>" parameter_img_id="productsparameter_<?php echo $ProductsParametervalue->value; ?>">
                                                <img style="display:none;" src="<?php echo $ProductsParametervalue->getEditableListimgurl; ?>" width="<?php echo $img_width;?>" height="<?php echo $img_width;?>" />
                                                </div>
												<?php endif;?>
												
                                                <h4 class="customiser-card-title"><?php echo $ProductsParametervalue->text; ?></h4>
                                            </label>
                                            <?php endif; ?>
                                            <?php $i++; ?>
                                            <?php endforeach;?>
											</select>
    										<?php endif;?>
    										
    										<div class="<?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>mandatory_validate<?php endif;?>">
    									    <input type="hidden" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="ProductsParametervalue[<?php echo $ProductsParameter->parameterId; ?>]" name="ProductsParametername[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->parameterName; ?>">
    									    </div>
    									    <input type="hidden" name="ProductsParameterhidden[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->ecommerce_show1; ?>">
    									    <input type="hidden" class="<?php echo $class_name2;?>" value="<?php echo $default_value;?>">
									    </div>
                                    </div>
								</td>
							</tr>
							
						<?php $panels_and_slats .= ob_get_contents();
						  ob_end_clean();
						}else{
							if(false !== strpos(strtolower($ProductsParameter->parameterName), 'hinge')){
								continue;
							}
							ob_start();
							?>
							<tr class="panels_and_slats subchild <?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>" style="display:none">
								<td colspan="2" class="value">
                                    <div class="product_atributes <?php echo($class_name_color); ?>">
                                        <h4><?php echo $ProductsParameter->parameterName; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?>
											<?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
													<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
													<img style="width:12px; vertical-align: text-top;" class="" src="<?php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/info.png"></button>
													<div class="modal fade" id="info_<?php echo $ProductsParameter->parameterId;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													  <div class="modal-dialog" role="document">
														<div class="modal-content">
														  <div class="modal-header">
															<h5 class="modal-title" id="exampleModalLabel"><?php echo $ProductsParameter->parameterName; ?></h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															  <span aria-hidden="true">&times;</span>
															</button>
														  </div>
														  <div class="modal-body">
																<?php echo($ProductsParameter->ecommoreinfo); ?>
														  </div>
														</div>
													  </div>
													</div>
											<?php } ?>
										</h4>
                                       <span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
                                        <div class="product_atributes_value">
                                            <?php if(count($ProductsParameter->ProductsParametervalue) > 0): ?>
												<?php
												$multiple = false;
												if('1' == $ProductsParameter->component_select_option){
													$multiple = true;
												}
												?>
											<select name="ProductsParametervalue[<?php echo $ProductsParameter->parameterId; ?>]" class="blindmatrix-dropdown-selection blindmatrix-select2" <?php if($multiple){?>multiple="multiple" <?php } ?> >											   									
											<?php if(!$multiple): ?>	
												<option value="" data-parameter_id = "<?php echo $ProductsParameter->parameterId; ?>">Choose an option</option>
											<?php endif; ?>	
											<?php foreach($ProductsParameter->ProductsParametervalue as $ProductsParametervalue):?>
    										
    										<?php if(strpos(strtolower($shutter_type), 'tier') !== false && strpos(strtolower($ProductsParameter->parameterName), 'panel') !== false):?>
    										<?php if ($ProductsParametervalue->text%2 != 0):?>
    										<?php continue;?>
    										<?php endif;?>
    										<?php endif;?>
    
    										<?php
                							if($ProductsParametervalue->getEditableListimgurl != ''){
                							    $ProductsParametervalue->getEditableListimgurl = $ProductsParametervalue->getEditableListimgurl;
                							    $data_img = $ProductsParametervalue->getEditableListimgurl;
                							}else{
                							    $ProductsParametervalue->getEditableListimgurl = plugin_dir_url( __DIR__ ).'Shortcode-Source/image/blinds/no-image.jpg';
                							    $data_img = '';
                							}
                							
                							if($ProductsParametervalue->text == $ProductsParameter->defaultValue){
                							    $default_value = strtolower($ProductsParametervalue->text);
                							}
                							$data_value = strtolower($ProductsParametervalue->text);
                							if (strpos(strtolower($ProductsParameter->parameterName), 'slat') !== false){
                							    $data_value = $i;
                							    if($ProductsParametervalue->text == $ProductsParameter->defaultValue){
                    							    $default_value = $data_value;
                    							}
                							}
                							
                							if(strpos(strtolower($shutter_type), 'tier') !== false && strpos(strtolower($ProductsParameter->parameterName), 'panel') !== false){
    										    $ProductsParameter->defaultValue = '2';
    										    $default_value = '2';
                							}
                							?>
    										<option id="radio_<?php echo $ProductsParametervalue->value;?>" <?php if($ProductsParametervalue->text == $ProductsParameter->defaultValue): ?>selected<?php endif; ?> data-img="<?php echo $data_img; ?>" data-value="<?php echo $data_value;?>" data-jsevent="<?php echo str_replace('(this);','',$js_function); ?>" value="<?php echo $ProductsParametervalue->value; ?>~<?php echo $ProductsParametervalue->text; ?>"><?php echo $ProductsParametervalue->text; ?></option>
											<?php
											$display = false;
											if($display):
                							?>
                                            <input type="radio" name="ProductsParametervalue[<?php echo $ProductsParameter->parameterId; ?>]" id="radio_<?php echo $ProductsParametervalue->value; ?>" style="display:none;" value="<?php echo $ProductsParametervalue->value; ?>~<?php echo $ProductsParametervalue->text; ?>" <?php if($ProductsParametervalue->text == $ProductsParameter->defaultValue): ?>checked<?php endif; ?> />
                                            <label onclick="<?php echo $js_function;?>showorderdetails();" data-img="<?php echo $data_img; ?>" data-value="<?php echo $data_value;?>" class="no_of_panels_elements <?php echo $class_name1;?> <?php if($ProductsParametervalue->text == $ProductsParameter->defaultValue): ?>selected<?php endif; ?>" for="radio_<?php echo $ProductsParametervalue->value; ?>">
                                               
											    <?php if (strpos(strtolower($ProductsParameter->parameterName), 'slat') !== false):?>
											    <?php else:?>
											    <div class="sample_image_shutter" parameter_img="<?php echo $data_img; ?>" parameter_img_id="productsparameter_<?php echo $ProductsParametervalue->value; ?>">
                                                <img src="<?php echo $ProductsParametervalue->getEditableListimgurl; ?>" width="<?php echo $img_width;?>" height="<?php echo $img_width;?>" />
                                                </div>
												<?php endif;?>
												
                                                <h4 class="customiser-card-title"><?php echo $ProductsParametervalue->text; ?></h4>
                                            </label>
											<?php endif; ?>
                                            
                                            <?php $i++; ?>
                                            <?php endforeach;?>
											</select>
    										<?php endif;?>
    										
    										<div class="<?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>mandatory_validate<?php endif;?>">
    									    <input type="hidden" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="ProductsParametervalue[<?php echo $ProductsParameter->parameterId; ?>]" name="ProductsParametername[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->parameterName; ?>">
    									    </div>
    									    <input type="hidden" name="ProductsParameterhidden[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->ecommerce_show1; ?>">
    									    <input type="hidden" class="<?php echo $class_name2;?>" value="<?php echo $default_value;?>">
									    </div>
                                    </div>
								</td>
							</tr>
							<?php $others .= ob_get_contents();
								  ob_end_clean();
							} ?> 
							<?php elseif($ProductsParameter->parameterListId == 18 && $ProductsParameter->ecommerce_show == 1): ?>
							<?php 
								if(false !== strpos(strtolower($ProductsParameter->parameterName), 'panel') || false !== strpos(strtolower($ProductsParameter->parameterName), 'slat') || false !== strpos(strtolower($ProductsParameter->parameterName), 'tilt')|| false !== strpos(strtolower($ProductsParameter->parameterName), 'mid')){
							
								ob_start(); 
							?>
							<?php $arrcomponentname = explode(',',$ProductsParameter->defaultValue); ?>
							<tr class="subchild panels_and_slats <?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>" id="<?php echo $ProductsParameter->parameterId; ?>" style="display:none">
							    
							    <td colspan="2" class="value">
							        <div class="product_atributes <?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>product_atributes2<?php endif;?>">
                                	<h4><?php echo $ProductsParameter->parameterName; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?>
											<?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
													<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
													<img style="width:12px; vertical-align: text-top;" class="" src="<?php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/info.png"></button>
													<div class="modal fade" id="info_<?php echo $ProductsParameter->parameterId;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													  <div class="modal-dialog" role="document">
														<div class="modal-content">
														  <div class="modal-header">
															<h5 class="modal-title" id="exampleModalLabel"><?php echo $ProductsParameter->parameterName; ?></h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															  <span aria-hidden="true">&times;</span>
															</button>
														  </div>
														  <div class="modal-body">
																<?php echo($ProductsParameter->ecommoreinfo); ?>
														  </div>
														</div>
													  </div>
													</div>
											<?php } ?>
										</h4>
                               	<span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
                                	<div class="product_atributes_value">
									<?php
												$multiple = false;
												if('1' == $ProductsParameter->component_select_option){
													$multiple = true;
												}
										?>
										<select name="Componentvalue[<?php echo $ProductsParameter->parameterId; ?>][]" class="blindmatrix-component-selection blindmatrix-select2" <?php if($multiple){?>multiple="multiple" <?php } ?> >			
					
										<?php if(!$multiple): ?>	
											<option value="" data-parameter_id = "<?php echo $ProductsParameter->parameterId; ?>">Choose an option</option>
										<?php endif; ?>	
										
                                	    <?php foreach($ProductsParameter->Componentvalue as $Componentvalue):
												$group_types = '' != $Componentvalue->grouptype ? explode(',',$Componentvalue->grouptype) : array();
												if($Componentvalue->grouptype && !in_array($producttypeid,$group_types)):
													continue;
												endif;
										?>
                                	    
                                	    <?php if(strpos(strtolower($shutter_type), 'tier') !== false && strpos(strtolower($ProductsParameter->parameterName), 'panel') !== false):?>
										<?php if ($ProductsParametervalue->text%2 != 0):?>
										<?php continue;?>
										<?php endif;?>
										<?php endif;?>
                                	    
                                	    <?php
            							if($Componentvalue->getComponentimgurl != ''){
            							    $Componentvalue->getComponentimgurl = $Componentvalue->getComponentimgurl;
            							    $data_img = $Componentvalue->getComponentimgurl;
            							}else{
            							    $Componentvalue->getComponentimgurl = plugin_dir_url( __DIR__ ).'Shortcode-Source/image/blinds/no-image.jpg';
            							    $data_img = '';
            							}
            							
            							if(in_array($Componentvalue->componentname, $arrcomponentname)){
            							    $default_value = strtolower($Componentvalue->componentname);
            							}
            							$data_value = strtolower($Componentvalue->componentname);
            							if (strpos(strtolower($ProductsParameter->parameterName), 'slat') !== false){
            							    $data_value = $i;
            							    if(in_array($Componentvalue->componentname, $arrcomponentname)){
                							    $default_value = $data_value;
                							}
            							}
            							?>
										
										<option id="radio_<?php echo $Componentvalue->priceid; ?>" data-img="<?php echo $Componentvalue->getComponentimgurl; ?>" data-sub="<?php echo $Componentvalue->parameterid."~~".$Componentvalue->priceid; ?>" type="<?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>checkbox<?php else: ?>radio<?php endif; ?>" data-parameter_id = "<?php echo $ProductsParameter->parameterId; ?>" data-value="<?php echo $data_value;?>" data-jsevent="<?php echo str_replace('(this);','',$js_function); ?>" value="<?php echo $Componentvalue->priceid."~".$Componentvalue->componentname; ?>" <?php if(in_array($Componentvalue->componentname, $arrcomponentname)): ?>selected<?php endif; ?>><?php echo $Componentvalue->componentname; ?></option>
											<?php 
											$display = false;
											$show_in_ecommerce = $ProductsParameter->ecommerce_show1;
											if($display): ?>
											
                                	    <input style="display:none;" onclick="<?php if($ProductsParameter->ecommerce_show1 == 1): ?>getComponentSubList(this,'<?php echo $ProductsParameter->parameterId; ?>');<?php endif; ?>" type="<?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>checkbox<?php else: ?>radio<?php endif; ?>" class="maincomponent_<?php echo $ProductsParameter->parameterId; ?>" name="Componentvalue[<?php echo $ProductsParameter->parameterId; ?>][]" id="radio_<?php echo $Componentvalue->priceid; ?>" data-sub="<?php echo $Componentvalue->parameterid."~~".$Componentvalue->priceid; ?>" value="<?php echo $Componentvalue->priceid."~".$Componentvalue->componentname; ?>" <?php if(in_array($Componentvalue->componentname, $arrcomponentname)): ?>checked<?php endif; ?> />
                                        <label onclick="<?php echo $js_function;?>showorderdetails();" data-img="<?php echo $data_img; ?>" data-value="<?php echo $data_value;?>" class="main_component_<?php echo $ProductsParameter->parameterId; ?> maincomlabel no_of_panels_elements <?php echo $class_name1;?> <?php if(in_array($Componentvalue->componentname, $arrcomponentname)): ?>selected<?php endif; ?>" for="radio_<?php echo $Componentvalue->priceid; ?>">
                                           
                                            <?php if (strpos(strtolower($ProductsParameter->parameterName), 'slat') !== false):?>
											<?php else:?>
										    <div class="" parameter_img="<?php echo $data_img; ?>" parameter_img_id="component_<?php echo $Componentvalue->priceid; ?>">
                                            <img style="display:none;" src="<?php echo $Componentvalue->getComponentimgurl; ?>" width="120" height="120" />
                                            </div>
                                            <?php endif;?>

                                            <h4 class="customiser-card-title"><?php echo $Componentvalue->componentname; ?></h4>
                                        </label>
											<?php endif; ?>
                                	    <?php endforeach;?>
                                	    </select>
                                	    
                                	    <div class="<?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>mandatory_validate<?php endif;?>">
                                		<input type="hidden" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="Componentvalue[<?php echo $ProductsParameter->parameterId; ?>][]" name="ComponentParametername[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->parameterName; ?>">
                                		</div>
                                		<input type="hidden" name="ComponentParameterhidden[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->ecommerce_show1; ?>">
                                	    <input type="hidden" class="<?php echo $class_name2;?>" value="<?php echo $default_value;?>">
                                	</div>    
                                </div>    
							    </td>
							</tr>
							<?php 
							
								$panels_and_slats .= ob_get_contents();
								  ob_end_clean();
								}else{ 
								 ob_start();
								?>
								
								<?php $arrcomponentname = explode(',',$ProductsParameter->defaultValue); ?>
								<tr class="panels_and_slats  subchild <?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>" id="<?php echo $ProductsParameter->parameterId; ?>" style="display:none">
							    
							    <td colspan="2" class="value">
							        <div class="product_atributes <?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>product_atributes2<?php endif;?>">
                                	<h4><?php echo $ProductsParameter->parameterName; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?>
											<?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
													<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
													<img style="width:12px; vertical-align: text-top;" class="" src="<?php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/info.png"></button>
													<div class="modal fade" id="info_<?php echo $ProductsParameter->parameterId;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													  <div class="modal-dialog" role="document">
														<div class="modal-content">
														  <div class="modal-header">
															<h5 class="modal-title" id="exampleModalLabel"><?php echo $ProductsParameter->parameterName; ?></h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															  <span aria-hidden="true">&times;</span>
															</button>
														  </div>
														  <div class="modal-body">
																<?php echo($ProductsParameter->ecommoreinfo); ?>
														  </div>
														</div>
													  </div>
													</div>
											<?php } ?>
										</h4>
									<span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
										<div class="product_atributes_value">
										<?php
												$multiple = false;
												if('1' == $ProductsParameter->component_select_option){
													$multiple = true;
												}
										?>
										<select name="Componentvalue[<?php echo $ProductsParameter->parameterId; ?>][]" class="blindmatrix-component-selection blindmatrix-select2" <?php if($multiple){?>multiple="multiple" <?php } ?> >			
										
										<?php if(!$multiple): ?>	
											<option value="" data-parameter_id = "<?php echo $ProductsParameter->parameterId; ?>">Choose an option</option>
										<?php endif; ?>	
											<?php foreach($ProductsParameter->Componentvalue as $Componentvalue):
													$group_types = '' != $Componentvalue->grouptype ? explode(',',$Componentvalue->grouptype) : array();
													if($Componentvalue->grouptype && !in_array($producttypeid,$group_types)):
														continue;
													endif;
											?>
											
											<?php if(strpos(strtolower($shutter_type), 'tier') !== false && strpos(strtolower($ProductsParameter->parameterName), 'panel') !== false):?>
											<?php if ($ProductsParametervalue->text%2 != 0):?>
											<?php continue;?>
											<?php endif;?>
											<?php endif;?>
											
											<?php
											if($Componentvalue->getComponentimgurl != ''){
												$Componentvalue->getComponentimgurl = $Componentvalue->getComponentimgurl;
												$data_img = $Componentvalue->getComponentimgurl;
											}else{
												$Componentvalue->getComponentimgurl = plugin_dir_url( __DIR__ ).'Shortcode-Source/image/blinds/no-image.jpg';
												$data_img = '';
											}
											
											if(in_array($Componentvalue->componentname, $arrcomponentname)){
												$default_value = strtolower($Componentvalue->componentname);
											}
											$data_value = strtolower($Componentvalue->componentname);
											if (strpos(strtolower($ProductsParameter->parameterName), 'slat') !== false){
												$data_value = $i;
												if(in_array($Componentvalue->componentname, $arrcomponentname)){
													$default_value = $data_value;
												}
											}
											?>
											<option id="radio_<?php echo $Componentvalue->priceid; ?>" data-img="<?php echo $Componentvalue->getComponentimgurl; ?>" data-sub="<?php echo $Componentvalue->parameterid."~~".$Componentvalue->priceid; ?>" type="<?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>checkbox<?php else: ?>radio<?php endif; ?>" data-parameter_id = "<?php echo $ProductsParameter->parameterId; ?>" data-value="<?php echo $data_value;?>" data-jsevent="<?php echo str_replace('(this);','',$js_function); ?>" value="<?php echo $Componentvalue->priceid."~".$Componentvalue->componentname; ?>" <?php if(in_array($Componentvalue->componentname, $arrcomponentname)): ?>selected<?php endif; ?>><?php echo $Componentvalue->componentname; ?></option>
											<?php 
											$display = false;
											$show_in_ecommerce = $ProductsParameter->ecommerce_show1;
											if($display): ?>
											<input style="display:none;" onclick="<?php if($ProductsParameter->ecommerce_show1 == 1): ?>getComponentSubList(this,'<?php echo $ProductsParameter->parameterId; ?>');<?php endif; ?>" type="<?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>checkbox<?php else: ?>radio<?php endif; ?>" class="maincomponent_<?php echo $ProductsParameter->parameterId; ?>" name="Componentvalue[<?php echo $ProductsParameter->parameterId; ?>][]" id="radio_<?php echo $Componentvalue->priceid; ?>" data-sub="<?php echo $Componentvalue->parameterid."~~".$Componentvalue->priceid; ?>" value="<?php echo $Componentvalue->priceid."~".$Componentvalue->componentname; ?>" <?php if(in_array($Componentvalue->componentname, $arrcomponentname)): ?>checked<?php endif; ?> />
											<label onclick="<?php echo $js_function;?>showorderdetails();" data-img="<?php echo $data_img; ?>" data-value="<?php echo $data_value;?>" class="main_component_<?php echo $ProductsParameter->parameterId; ?> maincomlabel no_of_panels_elements <?php echo $class_name1;?> <?php if(in_array($Componentvalue->componentname, $arrcomponentname)): ?>selected<?php endif; ?>" for="radio_<?php echo $Componentvalue->priceid; ?>">
											   
												<?php if (strpos(strtolower($ProductsParameter->parameterName), 'slat') !== false):?>
												<?php else:?>
												<div class="sample_image_shutter" parameter_img="<?php echo $data_img; ?>" parameter_img_id="component_<?php echo $Componentvalue->priceid; ?>">
												<img src="<?php echo $Componentvalue->getComponentimgurl; ?>" width="120" height="120" />
												</div>
												<?php endif;?>

												<h4 class="customiser-card-title"><?php echo $Componentvalue->componentname; ?></h4>
											</label>
											
											<?php endif; ?>
                                	    <?php endforeach;?>
                                	    </select>
											<div class="<?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>mandatory_validate<?php endif;?>">
											<input type="hidden" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="Componentvalue[<?php echo $ProductsParameter->parameterId; ?>][]" name="ComponentParametername[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->parameterName; ?>">
											</div>
											<input type="hidden" name="ComponentParameterhidden[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->ecommerce_show1; ?>">
											<input type="hidden" class="<?php echo $class_name2;?>" value="<?php echo $default_value;?>">
										</div>    
									</div>    
									</td>
								</tr>
								<?php 	
								$others .= ob_get_contents();
								  ob_end_clean();
								}
							
							?>
							<?php else: 
							ob_start();
							?>
							<?php if($ProductsParameter->ecommerce_show == 1 && $ProductsParameter->parameterListId != 2 && $ProductsParameter->parameterListId != 10 && $ProductsParameter->parameterListId != 6 && $ProductsParameter->parameterListId != 7 && $ProductsParameter->parameterListId != 22 && $ProductsParameter->parameterListId != 23): ?>
							<tr class="OthersParameter product_atributes subchild <?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>" style="display:none">
								<td class="label">
									<h4 for="<?php echo $ProductsParameter->parameterName; ?>">
										<?php echo $ProductsParameter->parameterName; ?><?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?>
										<?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
												<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
												<img style="width:12px; vertical-align: text-top;" class="" src="<?php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/info.png"></button>
												<div class="modal fade" id="info_<?php echo $ProductsParameter->parameterId;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
												  <div class="modal-dialog" role="document">
													<div class="modal-content">
													  <div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel"><?php echo $ProductsParameter->parameterName; ?></h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														  <span aria-hidden="true">&times;</span>
														</button>
													  </div>
													  <div class="modal-body">
															<?php echo($ProductsParameter->ecommoreinfo); ?>
													  </div>
													</div>
												  </div>
												</div>
										<?php } ?>
									</h4>
								</td>
								<td class="value" style="width:60%;">
									<input onkeyup="showorderdetails();" parameterName="<?php echo $ProductsParameter->parameterName; ?>" getparameterid="<?php echo $ProductsParameter->parameterId;?>" <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>class="mandatoryvalidate"<?php endif;?> name="Othersvalue[<?php echo $ProductsParameter->parameterId; ?>]" class="border border-1 border-silver white-back border-radius-10" type="text">
									<div class="clear"></div>
									<span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
									<input type="hidden" name="OthersParametername[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->parameterName; ?>">
									<input type="hidden" name="OthersParameterhidden[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->ecommerce_show1; ?>">
								</td>
							</tr>
							<?php 
								$others .= ob_get_contents();
								ob_end_clean();
							?>
							<?php endif; ?>
							<?php endif; ?>
							<?php endforeach; ?>
							<?php endif; ?>
							<tbody  class="configurator-options-dimensions" data-row="0">
							<tr class="headertable measure">
							<td class="config-heading-td heading-td-bottom" colspan="2"> 
								<h3 class="config-heading">
									<span class="config-count">2 </span>
									Choose panels and slats
								</h3>
								<h3 class="config-heading edit"><i  class="icon-pen-alt-fill"></i> Edit</h3>
							</td>
							</tr>
							<?php 
								print_r($panels_and_slats);
							?>
							</tbody>
							<tbody  class="configurator-options-dimensions" data-row="0">
							<tr class="headertable measure">
								<td class="config-heading-td heading-td-bottom" colspan="2"> 
									<h3 class="config-heading">
										<span class="config-count">3 </span>
										Choose a colour or finish
									</h3>
									<h3 class="config-heading edit"><i class="icon-pen-alt-fill"></i> Edit</h3>
								</td>
							</tr>
							<?php if(!empty($shuttercolorList)):?>
								<tr class="subchild" style="display:none">
									<td colspan="2" class="value">
										<div class="product_atributes shutter_color_container">
											<h4>Choose a shutter colour</h4>
											<div class="product_atributes_value colors">
												<?php if(count($shuttercolorList) > 0): ?>												
												<?php foreach($shuttercolorList as $shuttercolorlist):?>
		
												<?php
												if($shuttercolorlist->imagepath != ''){
													$shuttercolorimagepath = $shuttercolorlist->imagepath;
													$data_img = $shuttercolorlist->imagepath;
												}else{
													$shuttercolorimagepath = plugin_dir_url( __DIR__ ).'Shortcode-Source/image/blinds/no-image.jpg';
													$data_img = '';
												}
												?>
												
												<input type="radio" name="shuttercolorvalue" id="radio_<?php echo $shuttercolorlist->fabricid; ?>" style="display:none;" value="<?php echo $shuttercolorlist->fabricid; ?>~<?php echo $shuttercolorlist->fabric_name; ?>" <?php if($shuttercolorlist->fabricid == $default_fabricid): ?>checked<?php endif; ?>/>
												<label onclick="changecolor(this);showorderdetails();" data-id="<?php echo $shuttercolorlist->fabricid; ?>" data-colorname="<?php echo $shuttercolorlist->fabric_name; ?>" data-img="<?php echo $data_img; ?>" class="shutter_color_cl no_of_panels_elements <?php if($shuttercolorlist->fabricid == $default_fabricid): ?>selected<?php endif; ?>" for="radio_<?php echo $shuttercolorlist->fabricid; ?>">
													<div class="sample_image_shutter" style="width:80px;height:80px;">
														<img crossorigin="anonymous" id="imgid_<?php echo $shuttercolorlist->fabricid; ?>" src="<?php echo $shuttercolorimagepath; ?>" width="100" height="100" />
													</div>
													<h4 class="customiser-card-title"><?php echo $shuttercolorlist->fabric_name; ?></h4>
												</label>
												
												<?php endforeach;?>
												<?php endif;?>
												
												<input type="hidden" name="shuttercolorname" value="<?php echo $default_parameterName;?>">
												<input type="hidden" id="select_color" value="">
												<input type="hidden" id="select_color_image" value="<?php echo $default_imagepath;?>">
												<input type="hidden" id="select_imgid" value="<?php echo $default_fabricid;?>">
												
												<img class="image_class" style="display:none;">

											</div>
										</div>
									</td>
								</tr>
							
						<?php if(count($response->product_details->ProductsParameter) > 0):?>
						<?php foreach($response->product_details->ProductsParameter as $ProductsParameter):?>
						<?php if( ( (strpos(strtolower($shutter_type), 'tier') !== false) || (strpos(strtolower($shutter_type), 'half') !== false) ) && strpos(strtolower($ProductsParameter->parameterName), 'mid') !== false){
							continue;
						}
										
						if(strpos(strtolower($shutter_type), 'full solid') !== false && ( (strpos(strtolower($ProductsParameter->parameterName), 'slat') !== false) || (strpos(strtolower($ProductsParameter->parameterName), 'tilt') !== false) ) ){
							continue;
						}

						?>
						<?php if($ProductsParameter->parameterListId == 2 && $ProductsParameter->ecommerce_show == 1): 
								if(false !== strpos(strtolower($ProductsParameter->parameterName), 'hinge')){
									$i=0;
									$js_function = '';
									$class_name1 = '';
									$class_name2 = '';
									$default_value = '0';
									$class_name_color ='';
									$img_width = '120';

									$js_function = 'changehingecolor(this);';
									$class_name1 = 'shutter_color_cl';
									$class_name2 = 'select_hingecolor_image';
									$default_value = 'central';
									$class_name_color = 'shutter_color_container';
									$img_width = '100';
								 ?>
								<tr class="subchild <?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>" style="display:none">
								<td colspan="2" class="value">
                                    <div class="product_atributes <?php echo($class_name_color); ?>" style=" display: flex; align-items: center; justify-content: space-around; ">
                                        <h4 style=" width: 30%; "><?php echo $ProductsParameter->parameterName; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?>
											<?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
													<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>" >
													<img style="width:12px; vertical-align: text-top;" class="" src="<?php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/info.png"></button>
													<div class="modal fade" id="info_<?php echo $ProductsParameter->parameterId;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													  <div class="modal-dialog" role="document">
														<div class="modal-content">
														  <div class="modal-header">
															<h5 class="modal-title" id="exampleModalLabel"><?php echo $ProductsParameter->parameterName; ?></h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															  <span aria-hidden="true">&times;</span>
															</button>
														  </div>
														  <div class="modal-body">
																<?php echo($ProductsParameter->ecommoreinfo); ?>
														  </div>
														</div>
													  </div>
													</div>
											<?php } ?>
										</h4>
                                       <span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
                                        <div class="product_atributes_value colors" style=" width: 60%; ">

                                            <?php if(count($ProductsParameter->ProductsParametervalue) > 0): ?>
											<?php
												$multiple = false;
												if('1' == $ProductsParameter->component_select_option){
													$multiple = true;
												}
											?>
    										<select name="ProductsParametervalue[<?php echo $ProductsParameter->parameterId; ?>]" class="blindmatrix-dropdown-selection blindmatrix-select2" <?php if($multiple){?>multiple="multiple" <?php } ?>>											
    										<?php if(!$multiple): ?>	
												<option value="" data-parameter_id = "<?php echo $ProductsParameter->parameterId; ?>">Choose an option</option>
											<?php endif; ?>	
											<?php foreach($ProductsParameter->ProductsParametervalue as $ProductsParametervalue):?>
    										
    										<?php if(strpos(strtolower($shutter_type), 'tier') !== false && strpos(strtolower($ProductsParameter->parameterName), 'panel') !== false):?>
    										<?php if ($ProductsParametervalue->text%2 != 0):?>
    										<?php continue;?>
    										<?php endif;?>
    										<?php endif;?>
    
    										<?php
                							if($ProductsParametervalue->getEditableListimgurl != ''){
                							    $ProductsParametervalue->getEditableListimgurl = $ProductsParametervalue->getEditableListimgurl;
                							    $data_img = $ProductsParametervalue->getEditableListimgurl;
                							}else{
                							    $ProductsParametervalue->getEditableListimgurl = plugin_dir_url( __DIR__ ).'Shortcode-Source/image/blinds/no-image.jpg';
                							    $data_img = '';
                							}
                							
                							if($ProductsParametervalue->text == $ProductsParameter->defaultValue){
                							    $default_value = strtolower($ProductsParametervalue->text);
                							}
                							$data_value = strtolower($ProductsParametervalue->text);
                							if (strpos(strtolower($ProductsParameter->parameterName), 'slat') !== false){
                							    $data_value = $i;
                							    if($ProductsParametervalue->text == $ProductsParameter->defaultValue){
                    							    $default_value = $data_value;
                    							}
                							}
                							
                							if(strpos(strtolower($shutter_type), 'tier') !== false && strpos(strtolower($ProductsParameter->parameterName), 'panel') !== false){
    										    $ProductsParameter->defaultValue = '2';
    										    $default_value = '2';
                							}
                							?>
    										<option id="radio_<?php echo $ProductsParametervalue->value;?>" <?php if($ProductsParametervalue->text == $ProductsParameter->defaultValue): ?>selected<?php endif; ?> data-img="<?php echo $data_img; ?>" data-value="<?php echo $data_value;?>" data-jsevent="<?php echo str_replace('(this);','',$js_function); ?>" value="<?php echo $ProductsParametervalue->value; ?>~<?php echo $ProductsParametervalue->text; ?>"><?php echo $ProductsParametervalue->text; ?></option>
											<?php
											$display = false;
											if($display):
                							?>
											
                                            <input type="radio" name="ProductsParametervalue[<?php echo $ProductsParameter->parameterId; ?>]" id="radio_<?php echo $ProductsParametervalue->value; ?>" style="display:none;" value="<?php echo $ProductsParametervalue->value; ?>~<?php echo $ProductsParametervalue->text; ?>" <?php if($ProductsParametervalue->text == $ProductsParameter->defaultValue): ?>checked<?php endif; ?> />
                                            <label onclick="<?php echo $js_function;?>showorderdetails();" data-img="<?php echo $data_img; ?>" data-value="<?php echo $data_value;?>" class="no_of_panels_elements <?php echo $class_name1;?> <?php if($ProductsParametervalue->text == $ProductsParameter->defaultValue): ?>selected<?php endif; ?>" for="radio_<?php echo $ProductsParametervalue->value; ?>">
                                               
											    <?php if (strpos(strtolower($ProductsParameter->parameterName), 'slat') !== false):?>
											    <?php else:?>
											    <div style="height: 80px; width: 80px;" class="sample_image_shutter" parameter_img="<?php echo $data_img; ?>" parameter_img_id="productsparameter_<?php echo $ProductsParametervalue->value; ?>">
                                                <img src="<?php echo $ProductsParametervalue->getEditableListimgurl; ?>" width="<?php echo $img_width;?>" height="<?php echo $img_width;?>" />
                                                </div>
												<?php endif;?>
												
                                                <h4 class="customiser-card-title"><?php echo $ProductsParametervalue->text; ?></h4>
                                            </label>
    										<?php endif;?>
                                            
                                            <?php $i++; ?>
                                            <?php endforeach;?>
											</select>
    										<?php endif;?>
    										
    										<div class="<?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>mandatory_validate<?php endif;?>">
    									    <input type="hidden" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="ProductsParametervalue[<?php echo $ProductsParameter->parameterId; ?>]" name="ProductsParametername[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->parameterName; ?>">
    									    </div>
    									    <input type="hidden" name="ProductsParameterhidden[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->ecommerce_show1; ?>">
    									    <input type="hidden" class="<?php echo $class_name2;?>" value="<?php echo $default_value;?>">
									    </div>
                                    </div>
								</td>
							</tr>
								
								<?php 
								}
								endif;
								endforeach;
								endif;
								endif; ?>
							</tbody>
							<?php if($others != ''):?>
							<tbody  class="configurator-options-dimensions" data-row="0">
							<tr class="headertable measure">
								<td class="config-heading-td" colspan="2"> 
									<h3 class="config-heading">
										<span class="config-count">4 </span>
										Choose others
									</h3>
									<h3 class="config-heading edit"><i  class="icon-pen-alt-fill"></i> Edit</h3>
								</td>
							</tr>
							<?php
								print_r($others);
							?>
							</tbody>
							<?php endif;?>
					</table>	
                </div>
				
				<div class="product-info" style="padding-top:0px">
                    <div class="product-option__more-info" style="clear: both;">
                        <div class="accordion" rel="">
                            <div class="accordion-item">
                                <a href="#" class="accordion-title plain"><button class="toggle">
                                    <i style="font-size: 25px;line-height: 1.5;" class="icon-angle-down"></i>
                                    </button><span style="font-size: 15px;font-weight:600">Show order details</span>
                                </a>
                                <div class="accordion-inner" style="display: none;padding-top: 0;">
                                    <p id="allparametervalue" style="font-size: 14px;color: black;"></p>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="single_variation_wrap text-center">
						<div class="price_container">
							<div>
								<div class="price havelock-blue align-centre italic margin-top-20 font-30 display-none product-price">
									<div class="font-16 grey light-weight">Your Price</div>
									<div class="js-ajax-price margin-top-5">
										<?php echo get_woocommerce_currency_symbol();?><span class="showprice"><?php echo $producttype_price; ?></span>
									</div>
								</div>
							</div>
						</div>
						<div style="display: none;" class="loading-spin"></div>
						<div class="woocommerce-variation-add-to-cart variations_button woocommerce-variation-add-to-cart-disabled">
							<button onclick="getprice();" type="button" class="single_add_to_cart_button button alt js-add-cart relatedproduct" style="border-radius: 10px;"><i class="icon-shopping-cart"></i>&nbsp;Add to cart</button>
						</div>
					</div>
				</div>
			</div>
            
        </div>
	<div style="margin: 10px 10px 0;" >
		<h3 id="product_dtl" style="border-bottom: 1px solid #dddcdc;">Product Details</h2>
		<p style="font-size: 14px;"><?php echo($response->product_details->shutterparametertypedetails->producttypedescription); ?></p>
	</div>
 </div>
<input type="hidden" name="single_product_price" id="single_product_price">
<input type="hidden" name="vaterate" id="vaterate">
<input type="hidden" name="single_product_netprice" id="single_product_netprice">
<input type="hidden" name="single_product_itemcost" id="single_product_itemcost">
<input type="hidden" name="single_product_orgvat" id="single_product_orgvat">
<input type="hidden" name="single_product_vatvalue" id="single_product_vatvalue">
<input type="hidden" name="single_product_grossprice" id="single_product_grossprice">

<input type="hidden" id="blindmatrix-js-add-cart" class="blindmatrix-js-add-cart">

<span id="headstyle"></span>
</form>
<?php else:?>
<main id="main" class="site-main container pt" role="main" style="     max-width: 1010px;">
    <div class="row cusprodname">
    	<div class="col">
    		<div class="col-inner">
    		    <h3 class="lead">Page cannot be found</h3>
    			<ul>
    				<li>We're sorry but the page you were looking for could not be found.</li>
    				<li>Simply <a href="<?php bloginfo('url'); ?>" class="clr-red">click here</a> to get redirected and back on track.</li>
    				<li>Follow the product links below.</li>
    			</ul>
    		</div>
    	</div>
    	<?php echo do_shortcode( '[BlindMatrix source="BM-Shutters"] ' );?>
    </div>
 </div>
<?php endif;?>

<script src="/wp-content/plugins/blindmatrix-ecommerce/view/js/configurator.js"></script>
<script src="/wp-content/plugins/blindmatrix-ecommerce/view/js/dom-to-image.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script>

// if(jQuery(".blindmatrix-select2").length > 0){	
// var $eventSelect = jQuery(".blindmatrix-select2");
// $eventSelect.select2({
	// templateResult: formatState,
	// minimumResultsForSearch: -1
// });	

jQuery(".blindmatrix-select2").each(function(){
			var attr = jQuery(this).attr('multiple');
			if (typeof attr !== 'undefined' && attr !== false) {
			   var $eventSelect = jQuery(this);
				$eventSelect.select2({
					templateResult: formatState,
				});
				$eventSelect.on('select2:opening select2:closing', function( event ) {
					var $searchfield = jQuery(this).parent().find('.select2-search__field');
					$searchfield.prop('disabled', true);
				});
			}else{
				var $eventSelect = jQuery(this);
				$eventSelect.select2({
					templateResult: formatState,
					minimumResultsForSearch: -1
				});	
			}
		});

function formatState (opt) {
	
	if (!opt.id) {
		return opt.text;
	} 

	var optimage = jQuery(opt.element).attr('data-img'); 
			
	if(!optimage || optimage == " " ){
		return opt.text;
	} else {                    
		var $opt = jQuery(
			'<span><img src="' + optimage + '" width="50px" style="display: inline-block; vertical-align: middle;border-radius:10px;" /> ' + opt.text + '</span>'
		);
		return $opt;
	}
};


/* 
document.addEventListener('contextmenu', event => event.preventDefault());
document.onkeydown = function(e) {
  if(event.keyCode == 123) {
     return false;
  }
  if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
     return false;
  }
  if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
     return false;
  }
  if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
     return false;
  }
  if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
     return false;
  }
} */

var default_unitValmm = '<?=$response->product_details->checkMm; ?>';
var default_unitValcm = '<?=$response->product_details->checkCm; ?>';
var default_unitValinch = '<?=$response->product_details->checkInch; ?>';

window.onbeforeunload = function() {
    if(default_unitValmm == 'checked') document.getElementById("unit_0").checked = true;
    if(default_unitValcm == 'checked') document.getElementById("unit_1").checked = true;
    if(default_unitValinch == 'checked') document.getElementById("unit_2").checked = true;
};
 jQuery(document).ajaxStart(function() { Pace.restart(); });
jQuery(document).ready(function ($) {
    
    showorderdetails();
	var fraction = jQuery('#fraction').val();
	var unitVal = jQuery('input[name=unit]:checked').val();
	if(fraction == 'on' && unitVal == 'inch'){
		jQuery("#width,#drope").css({"width":"75%","float":"left"});
		jQuery("#widthfraction,#dropfraction").css({"width":"25%"});
	}
	
	jQuery('input[type=radio][name=unit]').change(function() {

		var widthTmp = jQuery('#width').val();
		var dropeTmp = jQuery('#drope').val();
		
		if(widthTmp == '')
		{
			widthTmp = 0;
		}
		if(dropeTmp == '')
		{
			dropeTmp = 0;
		}
		
		var widthplaceholdertext = jQuery('#widthplaceholdertext').val();
		var dropeplaceholdertext = jQuery('#dropeplaceholdertext').val();
		if (this.value == 'cm') {
		    jQuery('#width').attr('placeholder',widthplaceholdertext+' (cm)');
			jQuery('#drope').attr('placeholder',dropeplaceholdertext+' (cm)');
			jQuery("#width,#drope").removeAttr("style");
			jQuery("#width,#drope").css({"width":"100%"});
			jQuery('#widthfraction').hide();
			jQuery('#dropfraction').hide();
		}
		else if (this.value == 'mm') {
		    jQuery('#width').attr('placeholder',widthplaceholdertext+' (mm)');
			jQuery('#drope').attr('placeholder',dropeplaceholdertext+' (mm)');
		    if(widthTmp > 0)  jQuery('#width').val(parseInt(widthTmp));
			if(dropeTmp > 0)  jQuery('#drope').val(parseInt(dropeTmp));
			jQuery("#width,#drope").removeAttr("style");
			jQuery("#width,#drope").css({"width":"100%"});
			jQuery('#widthfraction').hide();
			jQuery('#dropfraction').hide();
		}
		else if (this.value == 'inch') {
		    jQuery('#width').attr('placeholder',widthplaceholdertext+' (inch)');
			jQuery('#drope').attr('placeholder',dropeplaceholdertext+' (inch)');
		    if(widthTmp > 0)  jQuery('#width').val(parseInt(widthTmp));
			if(dropeTmp > 0)  jQuery('#drope').val(parseInt(dropeTmp));
			if(fraction == 'on'){
				jQuery('#widthfraction').show();
				jQuery('#dropfraction').show();
					jQuery("#width,#drope").css({"width":"70%","float":"left","border-top-right-radius":"0px","border-bottom-right-radius":"0px"});
				jQuery("#widthfraction,#dropfraction").css({"width":"30%"});
			}else{
				jQuery('#widthfraction').hide();
				jQuery('#dropfraction').hide();
			}
		}
	});
	
	loadingafter = true;
	
    jQuery('.product_atributes input:radio').addClass('input_hidden');
    jQuery('.product_atributes label').click(function() {
        var getidval = jQuery(this).attr("for");
        var getclassname = jQuery(this).attr("class");
        var getclassnamesplit = getclassname.split(' ');
        
        if(jQuery.inArray("maincomlabel", getclassnamesplit) !== -1){
            
        }else{
            if(jQuery.inArray("selected", getclassnamesplit) !== -1){
                jQuery(this).removeClass('selected');
                jQuery("#"+getidval).click(function(){
                    jQuery(this).prop( "checked", false );
                });
            }else{
                jQuery(this).addClass('selected').siblings().removeClass('selected');
                jQuery("#"+getidval).click(function(){
                    jQuery(this).prop( "checked", true );
                });
            }
        }
        //jQuery(this).addClass('selected').siblings().removeClass('selected');
    });
    
    jQuery('input[name="shuttercolorvalue"]').trigger('change');
    
    var myimgArray = [];
    var i = 0;
    jQuery(".product_atributes_value").each(function (e) {
        var emptyimgArray = [];
        jQuery(this).find(".sample_image_shutter").each(function (e) {
            var get_parameter_image = jQuery(this).attr("parameter_img");
            var get_parameter_img_id = jQuery(this).attr('parameter_img_id');
            emptyimgArray.push(get_parameter_image+'~~'+get_parameter_img_id);
        });
        myimgArray[i] = emptyimgArray;
    ++i;
    });
    
    jQuery.each(myimgArray, function (index, value) {
        var counter = value.length;
        var emptyimgArray = [];
        jQuery.each(value, function (key, val) {
            var split_val = val.split('~~');
            if(split_val[0] == ''){
                emptyimgArray.push(split_val[1]);
            }
        });
        if(counter == emptyimgArray.length){
            jQuery.each(emptyimgArray, function (k, v) {
                jQuery("div[parameter_img_id="+v+"]").hide();
            });
        }
    });
});

jQuery('input[name="shuttercolorvalue"]').change(function () {
    var shuttercolorvalue = jQuery('input[name="shuttercolorvalue"]:checked').val();
    var fabricid_exp = shuttercolorvalue.split('~');
    jQuery('#producttypesub').val(fabricid_exp[0]);
});


jQuery('.blindmatrix-dropdown-selection').change(function(){
	var $this = jQuery(this),
		$shutter_event = $this.find(':selected').data('jsevent');
	if('shuttercolorvalue' == $this.attr('name')){
		changecolor($this.find(':selected'));
	}
	
	if('updatePanel' == $shutter_event){
		updatePanel($this.find(':selected'));
	}else if('slatsize' == $shutter_event){
		slatsize($this.find(':selected'));
	}else if('pushrod' == $shutter_event){
		pushrod($this.find(':selected')); 
	}else if('changehingecolor' == $shutter_event){
		changehingecolor($this.find(':selected'));
	}else if('midRail' == $shutter_event){
		midRail($this.find(':selected'));
	}
	showorderdetails();
});	
	
jQuery('.blindmatrix-component-selection').change(function(){
	var $this = jQuery(this);
	var show_in_ecommerce="<?php echo $show_in_ecommerce; ?>";
	var $shutter_event = $this.find(':selected').data('jsevent');
	if('midRail' == $shutter_event){
		midRail($this.find(':selected'));
	}else if('updatePanel' == $shutter_event){
		updatePanel($this.find(':selected'));
	}else if('slatsize' == $shutter_event){
		slatsize($this.find(':selected'));
	}else if('pushrod' == $shutter_event){
		pushrod($this.find(':selected')); 
	}else if('changehingecolor' == $shutter_event){
		changehingecolor($this.find(':selected'));
	}
	
	
	if(show_in_ecommerce == 1){
		getComponentSubList($this,$this.find(':selected').data('parameter_id'),'select');
	}
	
	showorderdetails();
});		

function getComponentSubList(thisval,parameterId,type=''){
	
    	var blindstype = jQuery('#blindstype').val();
	var maincomponent = [];
    if( 'select' != type){
    
    var getidval = jQuery(thisval).attr("id");
    var gettype = jQuery(thisval).attr("type");
    var getclassname = jQuery(thisval).next('label').attr("class");
    var getclassnamesplit = getclassname.split(' ');
    
    console.log(jQuery.inArray("selected", getclassnamesplit)+'--'+gettype);
    
    if(gettype == 'radio'){
        if(jQuery.inArray("selected", getclassnamesplit) !== -1){
            jQuery("#"+getidval).click(function(){
                jQuery(this).prop( "checked", false );
            });
            jQuery('.componentsub_'+parameterId).remove();
            jQuery('.componentsub_end').remove();
            jQuery('.main_component_'+parameterId).removeClass('selected');
        }else{
            jQuery("#"+getidval).click(function(){
                jQuery(this).prop( "checked", true );
            });
            jQuery('.componentsub_'+parameterId).remove();
            jQuery('.componentsub_end').remove();
            jQuery('.main_component_'+parameterId).removeClass('selected');
            jQuery('.maincomponent_'+parameterId+':checked').each(function(i, e) {
                maincomponent.push(jQuery(this).attr('data-sub'));
                jQuery(this).next('label').addClass('selected');
            });
        }
    }else{
        jQuery('.componentsub_'+parameterId).remove();
        jQuery('.componentsub_end').remove();
        jQuery('.main_component_'+parameterId).removeClass('selected');
        jQuery('.maincomponent_'+parameterId+':checked').each(function(i, e) {
            maincomponent.push(jQuery(this).attr('data-sub'));
            jQuery(this).next('label').addClass('selected');
        });
      }
	}else{
		if('undefined' != jQuery(thisval).find(':selected').attr('data-sub') || null != jQuery(thisval).find(':selected').attr('data-sub') ){
			maincomponent.push(jQuery(thisval).find(':selected').attr('data-sub'));
		}
		if(jQuery('.componentsub_'+parameterId).length > 0){
			jQuery('.componentsub_'+parameterId).each(function(){
				jQuery(this).remove();
				jQuery(this).next('.componentsub_end').remove();
			});
		}
		if(jQuery('.componentsub_end_'+parameterId).length > 0){
				jQuery('.componentsub_end_'+parameterId).each(function(){
					jQuery(this).remove();
				});
		}
	}

    if(maincomponent && maincomponent.length > 0){
		
        jQuery.ajax(
        {
        	url     : ajaxurl,
        	data    : {mode:'getcomponentsublist',action:'getcomponentsublist',maincomponent:maincomponent,blindstype:blindstype},
        	type    : "POST",
        	dataType: 'JSON',
        	async: false,
        	success: function(response){
				
        		if(response.result != ''){
            		jQuery('#'+parameterId).after(response.ComponentSubList);
            		
					jQuery("select.demo").each(function(){
								var $eventdemo = $(this);
								$eventdemo.select2();
								$eventdemo.on('select2:opening select2:closing', function( event ) {
									var $searchfielddemo = jQuery(this).parent().find('.select2-search__field');
									$searchfielddemo.prop('disabled', true);
								});
							});	
							jQuery("select.subdemo").each(function(){
								var $eventsubdemo = jQuery(this);
								$eventsubdemo.select2({
									minimumResultsForSearch: -1
								});
							});	
							jQuery(".select2-search__field").prop("readonly", true);
				}
				
        	},
		
        });;
    }
}

//setup before functions
var typingTimer;                //timer identifier
var doneTypingInterval = 500; 
var $input = jQuery('#width, #drope');

//on keyup, start the countdown
$input.on('keyup', function () {
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval);
});

//on keydown, clear the countdown 
$input.on('keydown', function () {
  clearTimeout(typingTimer);
});

//user is "finished typing," do something
function doneTyping () {
  //do something
  showorderdetails();
}

function showorderdetails(){
	
	resizeimagepreview();
    jQuery('#mode').val("getparameterdetails");
    setTimeout(function(){
        jQuery.ajax(
    	{
    		url     : ajaxurl,
    		data    : jQuery("#submitform").serialize(),
    		type    : "POST",
    		dataType: 'JSON',
    		success: function(response){
    		    
    			jQuery('#allparametervalue').html(response.allparametervalue_html);
    			
    			if(response.priceval > 0){
    				jQuery('.showprice').text(response.showprice);
    				jQuery('#single_product_price').val(response.priceval);
    				jQuery('#single_product_netprice').val(response.netprice);
    				jQuery('#single_product_itemcost').val(response.itemcost);
    				jQuery('#single_product_orgvat').val(response.orgvat);
    				jQuery('#single_product_vatvalue').val(response.vatvalue);
    				jQuery('#single_product_grossprice').val(response.grossprice);
    				jQuery('#vaterate').val(response.vaterate);
    			}else{
    				jQuery('#single_product_price').val('');
    				jQuery('#single_product_netprice').val('');
    				jQuery('#single_product_itemcost').val('');
    				jQuery('#single_product_orgvat').val('');
    				jQuery('#single_product_vatvalue').val('');
    				jQuery('#single_product_grossprice').val('');
    				jQuery('#vaterate').val('');
    			}
    		}
    	});
    }, 150);
}

function getprice(){
    jQuery('.loading-spin').css('display','block');
    jQuery('.woocommerce-error').html('');
    jQuery('.errormsg').html('');
    jQuery('#mode').val("getshutterprice");
    jQuery('#imagepath').val('');
    
    var returnfalsevalue = '';
	var emtarrlist="<li><div class='message-container container alert-color text-center'><span class='message-icon icon-close'></span><strong>Error: </strong>Information required...</div></li>";
	jQuery('.mandatoryvalidate').each(function(i){
	    var parameterName = jQuery(this).attr('parameterName');
	    var getparameterid = jQuery(this).attr('getparameterid');
		if(this.value == ''){
			returnfalsevalue = 1;
			jQuery('#errormsg_'+getparameterid).html(parameterName+' is a required field.');
		}
    });

    jQuery('input', '.mandatory_validate').each(function() {
        if (jQuery(this).attr('type') === 'hidden') {
            var name = jQuery(this).attr('radiobutton');
            var parameterName = jQuery(this).val();
            var getparameterid = jQuery(this).attr('getparameterid');
            if (jQuery('[name="' + name + '"]:checked').length < 1) {
			    returnfalsevalue = 1;
			    jQuery('#errormsg_'+getparameterid).html(parameterName+' is a required field.');
            }
        }
    });

    if(returnfalsevalue == 1){
        jQuery('.loading-spin').css('display','none');
		jQuery('.woocommerce-error').html(emtarrlist);
		jQuery('html, body').animate({
			scrollTop: jQuery(".woocommerce-error").offset().top -150
		}, 150);
	}else{
	    var select_color_image = jQuery('#select_color_image').val();
	    if(select_color_image == ''){
	        var noimgpath = '<?php echo plugin_dir_url( __DIR__ ).'Shortcode-Source/image/blinds/no-image.jpg'; ?>';
	        jQuery('#imagepath').val(noimgpath);
            jQuery('#blindmatrix-js-add-cart').trigger('click');
	    }else{
	        slats('open');
            setTimeout(function(){
                convert_canvas("shutterspreview");
            }, 500);
	    }
	}
}

function checkNumeric(event,thisval) 
{
	
	var unitVal = jQuery('input[name=unit]:checked').val();
	var fraction = jQuery('#fraction').val();
	
	var key = event.charCode || event.keyCode || 0;
	
	if(unitVal == 'mm' || (unitVal =='inch' && fraction == 'on'))
	{
		if (event.shiftKey == true) {
			event.preventDefault();
        }
		
        if ((key >= 48 && key <= 57) || 
            (key >= 96 && key <= 105) || 
            key == 8 || key == 9 || key == 37 ||
            key == 39) {

        } else {
            event.preventDefault();
        }

        if(thisval.value.indexOf('.') !== -1)
            event.preventDefault(); 

	}else{
		if ( key == 46 || key == 8 || key == 9 ||key == 190 ||key == 110 || key == 27 || key == 13 || 
		// Allow: Ctrl+A
		(key == 65 && event.ctrlKey === true) || 
		// Allow: home, end, left, right
		(key >= 35 && key <= 39)) {
			// let it happen, don't do anything
			return;
		}
		else {
			// Ensure that it is a number and stop the keypress
			if (event.shiftKey || (key < 48 || key > 57) && (key < 96 || key > 105 )) {
				event.preventDefault();  
			}   
		}

	}
}

jQuery('.input-text.qty.text').on('keypress', function (event) {
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false; 
    }
});

jQuery(function($){
	jQuery(".config-heading-td").click(function() {
		if($(this).parents(".configurator-options-dimensions").hasClass('is-active')){
			jQuery(this).parents(".configurator-options-dimensions").find('.subchild').slideToggle('fast', function() {
				if ($(this).is(':visible'))
					$(this).css('display','flex');
			});
			jQuery(this).parents(".configurator-options-dimensions").removeClass("is-active");
		}
		else{
			jQuery('.subchild').each(function(i, obj) {
				$(this).slideUp("fast");
			});
			jQuery(".configurator-options-dimensions").each(function(i, obj) {
				$(this).removeClass('is-active');
			});
			jQuery(this).parents(".configurator-options-dimensions").find('.subchild').slideToggle('slow', function() {
				if ($(this).is(':visible'))
					$(this).css('display','flex');
			});
			jQuery(this).parents(".configurator-options-dimensions").addClass('is-active');
		}
	});
});
</script>
<style>
.hideparameter{
    display: none !important;
}
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
.subchild{
	display:none;
	
}
td input#width,td input#drope, td select{
	
    margin: 5px 0px 1em;
}
.col-inner>p {
    display: none;
}

.col.medium-11.small-12.large-11 {
    margin: auto;
}

table.variations{
	display: flex;
	justify-content: center;
	    flex-direction: column;
}
table.variations .configurator-options-dimensions{
	display: flex;
	justify-content: center;
	    flex-direction: column;
}
table.variations .configurator-options-dimensions tr{
	display: flex;
    justify-content: center;
    flex-direction: row;
    align-items: center;
    justify-content: center;
}
table.variations .configurator-options-dimensions .measure td {
    display: flex;
    flex-direction: row;
    align-items: center;
	justify-content: center;
	padding: .5em 1em;
	font-size: 100%;
	overflow: visible;
}
tbody:first-child tr:first-child td:first-child {
    border-radius: 10px 10px 0px 0px;
}
tbody:not(:first-child) tr:nth-child(2) td {
    margin-top: 1rem;
}
.variations .value {
    width: 100%;
	padding: 0px;

}
.shutters-configurator .product_atributes_value.colors .customiser-card-title {
    min-height: 47px;
}
span.select2.select2-container {
    width: 100% !important;
}
tbody.configurator-options-dimensions span {
    border-radius: 10px !important;
}
tbody.configurator-options-dimensions .select2-container--open span {
    border-radius: unset!important;
}
tbody.configurator-options-dimensions.is-active input {
    border-radius: 10px;
	margin: 0px;
}
tbody.configurator-options-dimensions select#widthfraction,tbody.configurator-options-dimensions select#dropfraction {
    border-top-right-radius: 10px;
    border-bottom-right-radius: 10px;
}
.row.row-full-width.configurator.shutters-configurator.js-shutters-configurator.cuspricevalue {
    max-width: 1300px!important;
}
@media(max-width:767px){
.right-shuttercol {
  width:95% !important;
  margin:10px;
  max-width: 100%;
}
.col.left-shuttercol {
    margin-top: 20px;
    max-width: 100%;
    width: 95% !important;
}
.headContainer h1, .headContainer p {
    display: none;
}
}
.row {
    max-width: 1250px !important;
}
.col {
    padding: 0px !important;
}
.configurator-preview {
	margin:auto;
}
.product_atributes.shutter_color_container {
    padding: 1em 0em;
	text-align:center;
}
.subchild h4 {
    margin-bottom: 0px;
}

h3.config-heading.edit i.icon-pen-alt-fill {
    margin-top: -2px;
    font-size: 11px;
}
table.variations .is-active h3.config-heading.edit {
	visibility:hidden;
}
td.config-heading-td {
    cursor: pointer;
}

.toggle_slats,
.bmcsscn .accordion-title.active {
    border: solid 1px #002746 !important;
}
.toggle_slats>label {
    color: #002746 !important;
    font-weight: 800 !important;
}
.toggle_slats>#flap,
.shutters-configurator .wpcf7-form-control-wrap [type="radio"]:checked + label:after, .shutters-configurator .wpcf7-form-control-wrap [type="radio"]:not(:checked) + label:after,
.js-shutters-configurator.cuspricevalue button.single_add_to_cart_button.button.alt.js-add-cart.relatedproduct {
    background: #002746 !important;
}
.product_atributes .no_of_panels_elements.selected,
.product_atributes label.no_of_panels_elements:hover {
    border: 2px solid #002746 !important;
}
button.single_add_to_cart_button.button.alt.js-add-cart.relatedproduct {
    font-weight: 800;
    text-transform: none;
}
.product_atributes.shutter_color_container h4 {
    text-align: center !important;
}
@media (max-width: 550px) {
	.configurator.shutters-configurator.js-shutters-configurator table.variations td.widthdroptd {
    	width: 100%!important;
    	display: block;
	}
	.configurator.shutters-configurator.js-shutters-configurator table.variations .widthdroptd {
		flex-direction: column !important;
		flex-flow: column nowrap;
	}
}
</style>
<?php 
}else{
	echo('Enable shutters in the settings to view the shutter products.');
} ?>
