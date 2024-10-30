<?php

$blindmatrix_settings = get_option( 'option_blindmatrix_settings',true );
if(isset($blindmatrix_settings['menu_product_type']) && in_array( 'Blinds' , $blindmatrix_settings['menu_product_type'])){

global $product_page;
global $wp;
$currentpageurl = home_url( $wp->request );

$currentpageurl_exp =  explode("/", $currentpageurl);
$endparameter = end($currentpageurl_exp);
$getparameters = safe_decode($endparameter);
$getparameters_exp =  explode("/", $getparameters);

$urlpc  = $getparameters_exp[0];
$urlptid = $getparameters_exp[1];
$urlfid = $getparameters_exp[2];
$urlcid = $getparameters_exp[3];
$urlvid = $getparameters_exp[4];
$cart_item_key = isset($getparameters_exp[5]) ?$getparameters_exp[5]:'' ;
$_blinds_plugin_data = get_cart_item_blinds_plugin_data($cart_item_key);
$overall_cart_item_data = !empty($cart_item_key) ? WC()->cart->get_cart_item($cart_item_key):false;
$_style = !empty($overall_cart_item_data) ? 'display:none':'';
$cart_name = !empty($overall_cart_item_data) ? 'Update cart':'Add to cart';

//echo $urlpc.'--'.$urlptid.'--'.$urlfid.'--'.$urlcid.'--'.$urlvid;

$urlproductname = get_query_var("productname");
$urlfcname = get_query_var("colorname");

/*$urlpc  = safe_decode($_GET['pc']);
$urlptid = safe_decode($_GET['ptid']);
$urlfid = safe_decode($_GET['fid']);
$urlcid = safe_decode($_GET['cid']);
$urlvid = safe_decode($_GET['vid']);*/

$productname = str_replace('-',' ',get_query_var("productname"));
$colorname = str_replace('-',' ',get_query_var("colorname"));
$response = CallAPI("GET", $post=array("mode"=>"getProductParameterDetails", "productname"=>$productname, "productcode"=>$urlpc, "producttypeid"=>$urlptid, "fabricid"=>$urlfid, "colorid"=>$urlcid, "vendorid"=>$urlvid));
$product_code= $response->getproductdetails->product_no;
$producttypeid=$urlptid;
$fabricid=$urlfid;
$colorid=$urlcid;
$vendorid=$urlvid;
$getcategorydetails = isset($response->getfiltercategorylist) ? $response->getfiltercategorylist:array();

$product_detail_response = CallAPI("GET", $post=array("mode"=>"getproductdetail", "productcode"=>$urlpc, "producttypeid"=>$urlptid, "fabricid"=>$urlfid, "colorid"=>$urlcid, "vendorid"=>$urlvid));
$product_material_images = isset($product_detail_response->product_details->getmaterialimages->materialImages) ? $product_detail_response->product_details->getmaterialimages->materialImages:array();
$product_frame_image     = $product_detail_response->product_details->getproductframeimage;

function unitbasedcalculate($unit,$value){
    if($unit == 'cm'){
		$result = $value / 10;
	}elseif($unit == 'inch'){
		$result = round_up($value / 25.4,2);
	}else{
		$result = $value;
	}
	
	return $result;
}

function round_up ( $value, $precision ) { 
	$pow = pow ( 10, $precision );
	return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow; 
}

$res_maxprice = $response->product_details->product_details->getmaxprice;

$productimagepath = $response->product_details->product_details->imagepath;

$minWidth = unitbasedcalculate($response->product_details->product_details->default_unit_for_order,$response->product_details->product_details->minWidth);
$maxWidth = unitbasedcalculate($response->product_details->product_details->default_unit_for_order,$response->product_details->product_details->maxWidth);
$minDrop = unitbasedcalculate($response->product_details->product_details->default_unit_for_order,$response->product_details->product_details->minDrop);
$maxDrop = unitbasedcalculate($response->product_details->product_details->default_unit_for_order,$response->product_details->product_details->maxDrop);

$default_unit_for_order = $response->product_details->product_details->default_unit_for_order;
$bgframe = '/wp-content/plugins/blindmatrix-ecommerce/vendor/Shortcode-Source/image/fabric_1.png';
if($response->product_details->product_details->imagepath != ''){
	$productimagepath = $response->product_details->product_details->imagepath;
}else if($response->product_details->product_details->getmaterialimages->materialImages[0]->getimage != '' ){
	$productimagepath = $response->product_details->product_details->getmaterialimages->materialImages[0]->getimage;
}else{
	$productimagepath = blindmatrix_get_plugin_url() . '/vendor/Shortcode-Source/image/blinds/no-image.jpg'; 
	$bgframe='';
}

if(!empty($minWidth) && !empty($maxWidth)){
    $res_maxprice->widthmessage = "Min $minWidth $default_unit_for_order ~ Max $maxWidth $default_unit_for_order";
}

if(!empty($minDrop) && !empty($maxDrop)){
    $res_maxprice->dropmessage = "Min $minDrop $default_unit_for_order ~ Max $maxDrop $default_unit_for_order";
}

/*echo('<pre>');
print_r($response->product_details->product_details->product_details);
echo('</pre>');*/

if($response->getproductdetails == false){
	?>
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
		<?php echo do_shortcode( '[BlindMatrix source="BM-Products"] ' );?>
	</div>
	<?php
}else{
	$parameterarray=array();
	if(count($response->parameterdetails) > 0){
		foreach($response->parameterdetails as $ProductsParameter){
			$parameterarray[$ProductsParameter->parameterId] = $ProductsParameter->parameterName; 
		}
		$parameterarray['FabricName'] = 'FabricName';
		$parameterarray['ColourName'] = 'ColourName';
	} 


?>
<!--Formula calculation js files-->
<script crossorigin="anonymous" src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script crossorigin="anonymous" src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link crossorigin="anonymous" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script crossorigin="anonymous" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script src="/wp-content/plugins/blindmatrix-ecommerce/view/js/jstat.min.js"></script> 
<script src="/wp-content/plugins/blindmatrix-ecommerce/view/js/formula.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<form onkeydown="return event.key != 'Enter';" name="submitform" id="submitform"  class="tooltip-container variations_form cart" action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="stockcomponentid" id="stockcomponentid">
<input type="hidden" name="stockformulavalues[]" id="stockformulavalues">
<input type="hidden" name="componentformulavalues[]" id="componentformulavalues">
<input type="hidden" name="product_code" id="product_code" value="<?php echo $response->getproductdetails->product_no; ?>">
<input type="hidden" name="ecommerce_sample" id="ecommerce_sample" value="<?php echo $response->getproductdetails->ecommerce_sample ?>">
<input type="hidden" name="productid" id="productid" value="<?php echo $response->getproductdetails->productid; ?>">
<input type="hidden" name="productname" id="productname" value="<?php $productname_arr = explode("(", $response->getproductdetails->productname); echo trim($productname_arr[0]); ?>">
<input type="hidden" name="colorname" id="colorname" value="<?php echo $response->product_details->product_details->colorname; ?>">
<input type="hidden" name="imagepath" id="imagepath" value="<?php echo blindmatrix_get_plugin_url() . '/vendor/Shortcode-Source/image/blinds/no-image.jpg' ?>">
<input type="hidden" name="producttypeid" id="producttypeid" value="<?php echo $urlptid; ?>">
<input type="hidden" name="fabricid" id="fabricid" value="<?php echo $urlfid; ?>">
<input type="hidden" name="colorid" id="colorid" value="<?php echo $urlcid; ?>">
<input type="hidden" name="vendorid" id="vendorid" value="<?php echo $urlvid; ?>">
<input type="hidden" name="submitaddtobasket" id="submitaddtobasket" value="submit">
<input type="hidden" name="fraction" id="fraction" value="<?php echo $response->applicationsetup->fraction;?>">
<input type="hidden" name="mode" id="mode" value="">
<input type="hidden" name="company_name" id="company_name" value="<?php echo get_bloginfo( 'name' );?>">
<input type="hidden" name="productTypeSubName" id="productTypeSubName" value="<?php echo $response->product_details->product_details->productTypeSubName; ?>">
<input type="hidden" name="extra_offer" id="extra_offer" value="<?php echo $response->product_details->product_details->extra_offer; ?>">
<input type="hidden" name="type" id="type" value="custom_add_cart_blind">
<input type="hidden" name="action" id="action" value="blind_publish_process">
<input type="hidden" name="fabricparametername" id="fabricparametername" value="Fabric">
<input type="hidden" name="fabricparametervalue" id="fabricparametervalue" value="<?php echo $response->product_details->product_details->getfabricname; ?>">
<input type="hidden" name="colorparametername" id="colorparametername" value="Color">
<input type="hidden" name="colorparametervalue" id="colorparametervalue" value="<?php echo $response->product_details->product_details->getcolorname; ?>">
<input type="hidden"  id="fablicfilterarray" value="">
<input type="hidden"  id="catfilterarray" value="">
<input type="hidden"  id="searchFabric" value="">
<input type="hidden"  id="product_type_value" value="<?php if($response->getproductdetails->productcategory == "Create no sub sub parameter"){ echo('venetians'); }else{ echo('normal'); } ?>">	
<input type="hidden" id="blindsbackground" value="<?php echo blindmatrix_get_plugin_url() . '/vendor/Shortcode-Source/image/blindsback/'.$urlproductname.'.png' ?>">
<input type="hidden" id="defaultblindsbackground" value="<?php echo  blindmatrix_get_plugin_url() . '/vendor/Shortcode-Source/image/blindsback/default.png' ?>">
<input type="hidden" name="bm_bg_image_color" id="bm_bg_image_color" value="#808080">
<input type="hidden" id="stored_cart_item_key" name="stored_cart_item_key" value="<?php echo $cart_item_key; ?>">

<input type="hidden" name="fabricsupplier" id="fabricsupplier" value="<?php echo $response->product_details->product_details->fabricsupplier; ?>">
<input type="hidden" name="fabricsupplierid" id="fabricsupplierid" value="<?php echo $response->product_details->product_details->fabricsupplierid; ?>">
<input type="hidden" name="vendorname" id="vendorname" value="<?php echo $response->product_details->product_details->vendorname; ?>">

<?php if(count($response->parameterdetails) > 0):?>
<?php foreach($response->parameterdetails as $ProductsParameter):?>							
<?php if($ProductsParameter->parameterListId == 10): ?>
<input type="hidden" name="producttypeparametername" id="producttypeparametername" data-help="label_name" value="<?php echo $ProductsParameter->parameterName; ?>">
<input type="hidden" name="producttypeparametervalue" id="producttypeparametervalue" value="<?php echo $response->product_details->product_details->productTypeSubName; ?>">
<?php endif; ?>							
<?php endforeach; ?>
<?php endif; ?>

<input type="hidden" name="single_product_price" id="single_product_price">
<input type="hidden" name="vatoption" id="vatoption" value="<?php echo isset($blindmatrix_settings['vatoption']) ? ($blindmatrix_settings['vatoption'] ): ''; ?>">
<input type="hidden" name="vaterate" id="vaterate">
<input type="hidden" name="single_product_netprice" id="single_product_netprice">
<input type="hidden" name="single_product_itemcost" id="single_product_itemcost">
<input type="hidden" name="single_product_orgvat" id="single_product_orgvat">
<input type="hidden" name="single_product_vatvalue" id="single_product_vatvalue">
<input type="hidden" name="single_product_grossprice" id="single_product_grossprice">

<input type="hidden" name="getminWidth" id="getminWidth" value="">
<input type="hidden" name="getmaxWidth" id="getmaxWidth" value="">
<input type="hidden" name="getminDrop" id="getminDrop" value="">
<input type="hidden" name="getmaxDrop" id="getmaxDrop" value="">
<input type="hidden" id="minWidthfabric" value="<?php echo($response->product_details->product_details->minWidth); ?>">
<input type="hidden" id="maxWidthfabric" value="<?php echo($response->product_details->product_details->maxWidth); ?>">
<input type="hidden" id="minDropfabric" value="<?php echo($response->product_details->product_details->minDrop); ?>">
<input type="hidden" id="maxDropfabric" value="<?php echo($response->product_details->product_details->maxDrop); ?>">		
<input type="hidden" name="action" value="getprice">
<input type="hidden" name="blinds_image_key" id="blinds_image_key" value="">
<?php 
$domain_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
$urls = parse_url($domain_link);
?>
<input type="hidden" name="edit_product_url" value="<?php echo $domain_link; ?>">


<div class="row align-center blinds -container" id="row-981420196" style="max-width: 1300px;margin:auto;">
    
    <div class="cusprodname" style="padding: 10px 0 10px;">
		<a style="margin: 0;" href="/<?php echo($product_page); ?>/<?php echo(get_query_var("productname")); ?>"target="_self" class="button secondary is-link is-smaller lowercase">
			<i class="icon-angle-left"></i>  <span>Back to <?php echo $productname ?></span>
		</a>
        <h1 style="margin: 0;text-transform: capitalize;font-style: italic;" class="product-title product_title entry-title prodescprotitle"><span class="setcolorname"><?php echo $response->product_details->product_details->colorname; ?></span> <?php echo $productname;?> </h1>
    </div>
	<?php 
	$blindmatrix_settings = get_option( 'option_blindmatrix_settings',true );
	
	?>
	<div  style="display:none;" class="switch_style flex flex-col items-center py-16 bg-gray-100">
		<ul id="filter1" class="filter-switch inline-flex items-center relative h-10 p-1 space-x-1 bg-gray-200 rounded-md font-semibold text-blue-600 my-4">
			<li class="filter-switch-item flex relative h-8 bg-gray-300x">
				<input value="style1" type="radio" name="blindsstyle" id="blindsstyle-1" class="sr-only"  <?php if(isset($blindmatrix_settings['blinds_product_style']) && $blindmatrix_settings['blinds_product_style'] == 'style1'){ echo('checked="checked"'); } ?>>
				<label for="blindsstyle-1" class="h-8 py-1 px-2 text-sm leading-6 text-gray-600 hover:text-gray-800 bg-white rounded shadow">
					Style 1
				</label>
			<div aria-hidden="true" class="filter-active"></div>
			</li>
			<li class="filter-switch-item flex relative h-8 bg-gray-300x">
				<input value="style2" type="radio" name="blindsstyle" id="blindsstyle-2" class="sr-only" <?php if(isset($blindmatrix_settings['blinds_product_style']) && $blindmatrix_settings['blinds_product_style'] == 'style2'){ echo('checked="checked"'); } ?>>
				<label for="blindsstyle-2" class="h-8 py-1 px-2 text-sm leading-6 text-gray-600 hover:text-gray-800 bg-white rounded shadow">
					Style 2
				</label>
			</li>
		</ul>
	</div>

	<div id="configurator-root" style="position:relative;">

    <div class="configurator blinds bordered " >
            <div class="configurator-preview visible" style="position:relative; overflow:visible;">
                <div style="position:sticky; top:0;border: 1px dashed #ccc; padding: 10px;border-radius:10px">
                <div id="curtainspreview" class="configurator-preview-image">
					<div id="main-img" class="configuratorpreviewimage">
						<div class="configurator-main-fabric"></div>
						<div class="configurator-border-holder">
							<div class="configurator-border-fabric top"></div>
						</div>
						<div id="cover-spin"></div>
						<?php 
							$extra_offer = absint($response->product_details->product_details->extra_offer); 
							if(0 != $extra_offer):
							?>
							<div class="extra-off">
								<div class="badge-container absolute left top z-1 badege-view-page" style="margin:11px 0 !important;">
									<div class="callout badge badge-circle product-list-page"><div class="badge-inner secondary on-sale"><span class="onsale extra-text">Flat</span><br><span class="productlist_extra-val"><?php echo $extra_offer; ?><span> %</span></span><br><span class="sale-value">Sale</span></div></div>
								</div>
    						</div>
						<?php endif; ?>
						<?php 
						$blinds_image_key = isset($_blinds_plugin_data['blinds_image_key']) && !empty($_blinds_plugin_data['blinds_image_key']) ? $_blinds_plugin_data['blinds_image_key']:0;
						$_image = !empty($blinds_image_key) && isset( $response->frameImages[$blinds_image_key]->getimage) ? $response->frameImages[$blinds_image_key]->getimage :$response->frameImages[0]->getimage;
						?>
						<img crossorigin="anonymous" data-hexcode="#808080" class="configurator-main-headertype" src="<?php echo $_image; ?>"bigsrc="<?php echo $_image; ?>" style="border-radius:10px" alt="blinds image">
						<p class="preview-desc blinds">  Diagram is for illustration only. </p>
				    </div>
		
                </div>

                <?php if(count($response->frameImages) > 0):
					$blinds_image_key = isset($_blinds_plugin_data['blinds_image_key']) && !empty($_blinds_plugin_data['blinds_image_key']) ? $_blinds_plugin_data['blinds_image_key']:0;
					?>
				<div class="frame_container value mobile">
					<?php 
					$frames = is_array($response->frameImages) && !empty($response->frameImages) ? $response->frameImages:array();
				    end($frames);
				    $key = key($frames);
				    if(isset($frames[$key])){
				        unset($frames[$key]);
				    }
				    
					$_frame_images = array_merge((array)$frames,(array)$product_material_images);
					foreach($_frame_images as $key=>$image): ?>
					<div  data-blinds="<?php echo($key); ?>"  style="padding: 0!important;margin-top: 3px;margin-left: 5px!important; margin-bottom: 5px!important;background:url('<?php echo $image->getimage; ?>') " class=" product_list_frame_con product-small col has-hover product type-product post-149 status-publish first instock product_cat-tops product_cat-women has-post-thumbnail shipping-taxable purchasable product-type-simple row-box-shadow-2 row-box-shadow-3-hover">
						<a data-blinds="<?php echo($key); ?>" class="multiple-frame-list-button <?php if($blinds_image_key == $key){ echo("selected"); } ?> " >
							 <img style="visibility: hidden;" src="<?php echo $image->getimage; ?>" > 

						</a>
					</div>
					
					<?php endforeach; ?>
				</div>
                <div class="frame_container value desktop" style="margin:2% 0;border-top: 1px solid #ccc; padding-top: 5px;">
					<div class="row large-columns-4 medium-columns-3 small-columns-3 row-small slider row-slider slider-nav-reveal slider-nav-push"  data-flickity-options='{"imagesLoaded": true, "groupCells": "100%", "dragThreshold" : 5, "cellAlign": "left","wrapAround": false,"prevNextButtons": true,"percentPosition": true,"pageDots": false, "rightToLeft": false, "autoPlay" : false}'>
					<?php 
					$frames = is_array($response->frameImages) && !empty($response->frameImages) ? $response->frameImages:array();
				    end($frames);
				    $key = key($frames);
				    if($frames[$key]){
				        unset($frames[$key]);
				    }

					$_frame_images = array_merge((array)$frames,(array)$product_material_images);
					foreach($_frame_images as $key=>$image): ?>
					
					<?php
				// 		$lastframe = count($_frame_images) -1;
				// 		if($key == $lastframe){
				// 		  continue;
				// 		}
						$blinds_image_key = isset($_blinds_plugin_data['blinds_image_key']) && !empty($_blinds_plugin_data['blinds_image_key']) ? $_blinds_plugin_data['blinds_image_key']:0;
					?>
					<div style="padding: 0!important;padding-left: 10px!important" class="product-small col has-hover product type-product post-149 status-publish first instock product_cat-tops product_cat-women has-post-thumbnail shipping-taxable purchasable product-type-simple row-box-shadow-2 row-box-shadow-3-hover">
						<a data-blinds="<?php echo($key); ?>" class="multiple-frame-list-button <?php if($key == $blinds_image_key){ echo("selected"); } ?> " >
							 <img src="<?php echo $image->getimage; ?>" > 
						</a>
					</div>
					<?php endforeach; ?>
					</div>
				</div>
				<?php endif; ?>
				</div>
				
            </div>
		
        <div class="blinds cuspricevalue  configurator-controls product-info" style="padding: 10px 20px 0px;background: #f7f6f6; margin: 0 20px;position: relative;border-radius: 10px;display: inline-grid;align-content: space-between;">
		<div class="curtain-loder" style="">
						<img class="" src="<?php echo blindmatrix_get_plugin_url() . '/vendor/Shortcode-Source/image/blinds/blinds_loader.gif';?>" >
			</div>
			<?php $_unit = isset($_blinds_plugin_data['unit']) ?$_blinds_plugin_data['unit']:$response->applicationsetup->default_unit_for_order ; ?>
			<h3 style="text-align: center;">Please enter your measurements</h3>
				<div class="blinds-measurement" >
					<div colspan="2" class="value" style="border-top-left-radius: 10px; border-top-right-radius: 10px;border-radius: 25px; box-shadow: 0 0 1px 0 rgba(24, 94, 224, 0.15), 0 6px 12px 0 rgba(24, 94, 224, 0.15)">
						<span class="wpcf7-form-control-wrap radio-726">
							<span class="wpcf7-form-control wpcf7-radio">
								<span class="wpcf7-list-item first">
									<label><input name="unit" id="unit_0" class="js-unit" value="mm" <?php echo (isset($_REQUEST['unit']) && $_REQUEST['unit'] == 'mm') ? 'checked="checked"' : ''; ?><?php if($_unit =="mm"){ echo('checked');} ?> type="radio"><span class="wpcf7-list-item-label">mm</span></label>
								</span>
								<span class="wpcf7-list-item">
									<label><input name="unit" id="unit_1" class="js-unit" value="cm" <?php echo (isset($_REQUEST['unit']) && $_REQUEST['unit'] == 'cm') ? 'checked="checked"' : ''; ?><?php if($_unit =="cm"){ echo('checked');} ?> type="radio" ><span class="wpcf7-list-item-label">cm</span></label>
								</span>
								<span class="wpcf7-list-item last">
									<label><input name="unit" id="unit_2" class="js-unit" value="inch" <?php echo (isset($_REQUEST['unit']) && $_REQUEST['unit'] == 'inch') ? 'checked="checked"' : ''; ?><?php if($_unit =="inch"){ echo('checked');} ?> type="radio"><span class="wpcf7-list-item-label">inches</span></label>
								</span>
							</span>
						</span>
					</div>
				</div>
				
				<?php foreach($response->parameterdetails as $ProductsParameter): ?>
				<?php if($ProductsParameter->parameterListId == 4): ?>
				<div data-parameterlistid="<?php echo($ProductsParameter->parameterListId); ?>" class="showdetailscontainer width-container-blinds <?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>">
					<div class="label">
						<label for="<?php echo $ProductsParameter->parameterName; ?>" style="display: flex; align-items: center;">
							<?php echo $ProductsParameter->parameterName; ?><?php if($ProductsParameter->orderitemmandatory == 1): ?><font color="red">*</font><?php endif;?>
							<?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
								<img style="width: 12px;margin-left: 5px;" class="" src="<?php
								echo blindmatrix_get_plugin_url() . '/vendor/Shortcode-Source/image/info.png'; ?>"></button>
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
						</label>
					</div>
					<div class="value" style="display:block!important;">
						<span id="errmsg_width" data-text-color="alert" class="errmsg_widthdrop is-small"></span>
						<span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
						<?php 
						$_width= ((isset($_GET["width"]))?htmlspecialchars($_GET["width"]):"");	
						$_width = isset($_blinds_plugin_data['width']) ?$_blinds_plugin_data['width']:$_width ;
						?>
						<div class="width-input-container" style="display:flex;" >
							<div class="width-measure-icon" >
								<img src="<?php echo get_site_url(); ?>/wp-content/plugins/blindmatrix-ecommerce/vendor/Shortcode-Source/image/arrows.png" alt="Width Measurement" title="Width Measurement" loading="lazy"> 
							</div>
							<div class="width-measure-input" >
								<div class="clear"></div>
								<input type="hidden" name="widthplaceholdertext" id="widthplaceholdertext" value="<?php echo $ProductsParameter->parameterName; ?>">
								<input min="1" data-getparameterid="<?php echo $ProductsParameter->parameterId;?>" placeholder="<?php echo $res_maxprice->widthmessage; ?>" name="width" id="width" onkeyup="checkNumeric(event,this);" onkeydown="checkNumeric(event,this);" step="1" class="widthdrope <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?> mandatoryvalidate <?php endif;?> showorderdetails" autocomplete="off" type="number" value="<?php echo $_width; ?>" >
								<?php $stored_width_fraction = isset($_blinds_plugin_data['widthfraction']) ? $_blinds_plugin_data['widthfraction']:''; ?>
								<select name="widthfraction" id="widthfraction" style="<?php if(($response->applicationsetup->default_unit_for_order =="inch" && $response->applicationsetup->fraction =="on") || ('inch' == $_unit && !empty($_blinds_plugin_data['width']) )){ echo("display: block"); }else{ echo("display: none"); };?>" class="">
									<option value="">0</option>
									<option value="1" <?php if( '1' == $stored_width_fraction){ ?>selected="selected"<?php } ?>>1/8</option>
									<option value="2" <?php if( '2' == $stored_width_fraction){ ?>selected="selected"<?php } ?>>1/4</option>
									<option value="3" <?php if( '3' == $stored_width_fraction){ ?>selected="selected"<?php } ?>>3/8</option>
									<option value="4" <?php if( '4' == $stored_width_fraction){ ?>selected="selected"<?php } ?>>1/2</option>
									<option value="5" <?php if( '5' == $stored_width_fraction){ ?>selected="selected"<?php } ?>>5/8</option>
									<option value="6" <?php if( '6' == $stored_width_fraction){ ?>selected="selected"<?php } ?>>3/4</option>
									<option value="7" <?php if( '7' == $stored_width_fraction){ ?>selected="selected"<?php } ?>>7/8</option>
								</select>
								<input name="widthparameterId" id="widthparameterId" value="<?php echo $ProductsParameter->parameterId; ?>" type="hidden">
							</div>
						</div>
					</div>
				</div>
			    
			    <?php elseif($ProductsParameter->parameterListId == 5): ?>
				
				<div data-parameterlistid="<?php echo($ProductsParameter->parameterListId); ?>" class="showdetailscontainer drop-container-blinds <?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>">
					<div class="label">

						<label for="<?php echo $ProductsParameter->parameterName; ?>" style="display: flex; align-items: center;">
							<?php echo $ProductsParameter->parameterName; ?><?php if($ProductsParameter->orderitemmandatory == 1): ?><font color="red">*</font><?php endif;?>
													<?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
								<img style="width: 12px;margin-left: 5px;" class="" src="<?php
								echo blindmatrix_get_plugin_url(). '/vendor/Shortcode-Source/image/info.png'; ?>"></button>
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
						</label>
					</div>
					<div class="value" style="display:block!important;">
						<span id="errmsg_drope" data-text-color="alert" class="is-small errmsg_widthdrop"></span>
						<span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
						<div class="drop-input-container" style="display:flex;" >
							<div class="drop-measure-icon" >
								<img src="/wp-content/plugins/blindmatrix-ecommerce/vendor/Shortcode-Source/image/arrows.png" alt="Width Measurement" title="Width Measurement" loading="lazy"> 
							</div>
							<div class="drop-measure-input" >
								<div class="clear"></div>
								<input type="hidden" name="dropeplaceholdertext" id="dropeplaceholdertext" value="<?php echo $ProductsParameter->parameterName; ?>">
								<?php 
								$_drope = ((isset($_GET["drope"]))?htmlspecialchars($_GET["drope"]):"");	
								$_drope = isset($_blinds_plugin_data['drope']) ?$_blinds_plugin_data['drope']:$_drope ;
								?>
								<input min="1" data-getparameterid="<?php echo $ProductsParameter->parameterId;?>" placeholder="<?php echo $res_maxprice->dropmessage; ?>" name="drope" id="drope" onkeyup="checkNumeric(event,this);" onkeydown="checkNumeric(event,this);" step="1" class="widthdrope showorderdetails<?php if($ProductsParameter->orderitemmandatory == 1): ?> mandatoryvalidate <?php endif;?> " autocomplete="off" type="number" value="<?php echo $_drope; ?>" >
								<?php $stored_drop_fraction = isset($_blinds_plugin_data['dropfraction']) ? $_blinds_plugin_data['dropfraction']:''; ?>
								<select name="dropfraction" id="dropfraction" style="<?php if(($response->applicationsetup->default_unit_for_order =="inch" && $response->applicationsetup->fraction =="on") || ('inch' == $_unit && !empty($_blinds_plugin_data['drope']))){ echo("display: block"); }else{ echo("display: none"); };?>" class="">
									<option value="">0</option>
									<option value="1" <?php if( '1' == $stored_drop_fraction){ ?>selected="selected"<?php } ?>>1/8</option>
									<option value="2" <?php if( '2' == $stored_drop_fraction){ ?>selected="selected"<?php } ?>>1/4</option>
									<option value="3" <?php if( '3' == $stored_drop_fraction){ ?>selected="selected"<?php } ?>>3/8</option>
									<option value="4" <?php if( '4' == $stored_drop_fraction){ ?>selected="selected"<?php } ?>>1/2</option>
									<option value="5" <?php if( '5' == $stored_drop_fraction){ ?>selected="selected"<?php } ?>>5/8</option>
									<option value="6" <?php if( '6' == $stored_drop_fraction){ ?>selected="selected"<?php } ?>>3/4</option>
									<option value="7" <?php if( '7' == $stored_drop_fraction){ ?>selected="selected"<?php } ?>>7/8</option>
								</select>
								<input name="dropeparameterId" id="dropeparameterId" value="<?php echo $ProductsParameter->parameterId; ?>" type="hidden">
							</div>
						</div>
					</div>
				</div>	
				<?php endif; ?>
				<?php endforeach; ?>
					
					<?php if(count($response->parameterdetails) > 0){
					
						?> <div class="hide_fabric_color"> <?php 
						foreach($response->parameterdetails as $ProductsParameter){ 
								if($ProductsParameter->parameterListId == 10){ ?>
								<?php if($response->getproductdetails->productcategory == "Create no sub sub parameter"): ?>
								<div style="padding: 5px 15px 0px 5px; position: relative;" class="showdetailscontainer blindsparameterContianer fabric_scroll_blind_contianer color_blind_contianer">
										<div class="label">
											<label class="serach_input_fabric_label" style="width: 25%; display: inline-block;" for="<?php echo $response->defaultfabricname; ?>">
												<?php echo $response->defaultfabricname ?><?php if($ProductsParameter->orderitemmandatory == 1): ?><font color="red">*</font><?php endif;?>
											</label>
											<div class="serach_input_fabric_contianer" style="position: relative;width: 74%;    text-align: right; display: inline-block;">
												<span style="display:none;" class="fabricname_showbox"><i class="icon-checkmark"></i><span class="fabricname_showbox_value"> </span></span>
											  <input type="text" placeholder="Search" name="serach_input_fabric" class="serach_input_fabric" id="serach_input_fabric" style="margin-bottom: 0;padding-left: 35px;border-radius: 20px;font-size: 15px;width: 200px;font-weight: 500; color: black!important; background: #fff;">
											  <i style="position: absolute;right:170px;top: 10px;font-size: 14px;"  class="icon-search"></i>
										    </div>
										</div>
																			
										<span id="errormsg_producttypesub" data-text-color="alert" class="is-small errormsg"></span>
										<div id="coverspin" style="display: none;"></div>
										<div class="value">
											<?php foreach($ProductsParameter->getfabricdetails as  $getfabricdetails){ ?> 
										
											<input data-vendorid="<?php echo($getfabricdetails->vendorid); ?>" data-parameterTypeId="<?php echo($getfabricdetails->parameterTypeId); ?>" data-getparameterid="producttypesub" data-minWidth = "<?php echo($getfabricdetails->minWidth); ?>" data-maxWidth = "<?php echo($getfabricdetails->maxWidth); ?>" previousValue="<?php if($urlfid == $getfabricdetails->fabricid):?>true<?php endif;?>"  data-minDrop = "<?php echo($getfabricdetails->minDrop); ?>" data-maxDrop = "<?php echo($getfabricdetails->maxDrop); ?>" data-productname="<?php echo($getfabricdetails->productname); ?>" data-producttypeid="<?php echo($getfabricdetails->parameterTypeId); ?>" data-producttype="<?php echo($getfabricdetails->producttype); ?>" data-fabricsupplier="<?php echo($getfabricdetails->fabricsupplier); ?>" data-fabricsupplierid="<?php echo($getfabricdetails->fabricsupplierid); ?>" data-labelval="<?php echo($getfabricdetails->fabricname);?>"  data-fabricname="<?php echo($getfabricdetails->fabricname); ?>"   data-vendorname="<?php echo($getfabricdetails->vendorname); ?>"   class=" <?php if($response->getproductdetails->productcategory == "Create no sub sub parameter"){ echo('color_blind'); }?> showorderdetails fabric_blind radio blindsradio  <?php if($ProductsParameter->orderitemmandatory == 1){ echo('mandatory_validate'); }?>"  value="<?php echo($getfabricdetails->fabricid); ?>"  name="fabricnameck" id="productype<?php echo($getfabricdetails->fabricid); ?>" autocomplete="off" type="radio">
											<label  class="blindslabel radio image <?php if($urlfid == $getfabricdetails->fabricid):?>selected<?php endif;?>" id="<?php echo($getfabricdetails->fabricid); ?>" for="productype<?php echo($getfabricdetails->fabricid); ?>">
												<?php 
												if($getfabricdetails->imagepath != ""){
													$img = $getfabricdetails->imagepath;
												}else{
													$img =  blindmatrix_get_plugin_url() . '/vendor/Shortcode-Source/image/blinds/no-image.jpg';
												}
												?>
											    <img src="<?php echo($img); ?>" alt="" width="120" height="120"> <span class="fabricname"><?php echo($getfabricdetails->fabricname); ?></span>
											</label>
										
											<?php } ?> 
										</div>
								</div>	
    							<?php else: ?>
								<div  class="showdetailscontainer blindsparameterContianer color_blind_contianer"></div>
								<?php endif; ?>
								
								<?php }
								}
								?>
								</div>
								<?php	
								foreach($response->parameterdetails as $ProductsParameter){
									$parameterName = str_replace(' ', '_', $ProductsParameter->parameterName);
									if($ProductsParameter->parameterListId == 2){ ?>
										<div class="showdetailscontainer blindsparameterContianer dropdownContianer <?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>">
											<div class="label">
												<label data-label="<?php echo $ProductsParameter->parameterName; ?>" for="<?php echo $parameterName; ?><?php echo $ProductsParameter->parameterId;?>">
													<?php echo $ProductsParameter->parameterName; ?><?php if($ProductsParameter->orderitemmandatory == 1): ?><font color="red">*</font><?php endif;?>
												<?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
													<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
													<img style="width: 12px;" class="" src="<?php
								echo blindmatrix_get_plugin_url(). '/vendor/Shortcode-Source/image/info.png'; ?>"></button>
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
												</label>
											</div>
											<div class="value">
											<span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
													<?php 
													$stored_dropdown = isset($_blinds_plugin_data['ProductsParametervalue'][$ProductsParameter->parameterId]) ? $_blinds_plugin_data['ProductsParametervalue'][$ProductsParameter->parameterId]:array();
													?>		
											        <select class="showorderdetails <?php if($ProductsParameter->orderitemmandatory == 1){ echo('mandatoryvalidate'); }?> " data-getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" data-getparameterid="<?php echo $ProductsParameter->parameterId; ?>" id="<?php echo $parameterName; ?><?php echo $ProductsParameter->parameterId;?>" class="showorderdetails <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>mandatoryvalidate<?php endif;?>" name="ProductsParametervalue[<?php echo $ProductsParameter->parameterId; ?>]" >
														<option value="">Choose an option</option>
														<?php if(count($ProductsParameter->dropdownvalue) > 0): ?>												
														<?php foreach($ProductsParameter->dropdownvalue as $dropdownvalue):
															$_dropdown_value = $dropdownvalue->value."~".$dropdownvalue->text;
															?>
														<option value="<?php echo $dropdownvalue->value; ?>~<?php echo $dropdownvalue->text; ?>"  <?php if(isset($ProductsParameter->defaultValue) && $dropdownvalue->text == $ProductsParameter->defaultValue || $stored_dropdown == $_dropdown_value): ?> selected="selected" <?php endif; ?>><?php echo $dropdownvalue->text; ?></option>
														<?php endforeach;?>
														<?php endif;?>
													</select>
												<input type="hidden" name="ProductsParametername[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->parameterName; ?>">
												<input type="hidden" name="ProductsParameterhidden[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo 		$ProductsParameter->ecommerce_show1; ?>">
												<!--<div class="clear"></div>
												<span id="errmsg_drop" data-text-color="alert" class="is-small"></span>-->
											</div>
										</div>	
								
								<?php }elseif($ProductsParameter->parameterListId == 18){ ?>
								<?php $arrcomponentname = explode(',',$ProductsParameter->defaultValue); ?>
										<div id="<?php echo $ProductsParameter->parameterId; ?>" class="showdetailscontainer blindsparameterContianer <?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>">
											<div class="label">
												<label data-label="<?php echo $ProductsParameter->parameterName; ?>" for="<?php echo $parameterName; ?><?php echo $ProductsParameter->parameterId;?>">
													<?php echo $ProductsParameter->parameterName; ?><?php if($ProductsParameter->orderitemmandatory == 1): ?><font color="red">*</font><?php endif;?>
											<?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
													<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
													<img style="width: 12px;" class="" src="<?php echo blindmatrix_get_plugin_url(). '/vendor/Shortcode-Source/image/info.png'; ?>"></button>
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
												</label>
											</div>
											<div class="value Componentvalue">
											<?php
												$stored_component = isset($_blinds_plugin_data['Componentvalue'][$ProductsParameter->parameterId]) ? $_blinds_plugin_data['Componentvalue'][$ProductsParameter->parameterId]:array();
											?>	
											<span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
											
											
												<select data-getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" data-getparameterid="<?php echo $ProductsParameter->parameterId; ?>" id="<?php echo $parameterName; ?><?php echo $ProductsParameter->parameterId;?>" class="compontentlist showorderdetails maincomponent_<?php echo $ProductsParameter->parameterId; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>mandatoryvalidate<?php endif;?> <?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?><?php endif; ?>" onchange="<?php if($ProductsParameter->ecommerce_show1 == 1): ?>getComponentSubList(this,'<?php echo $ProductsParameter->parameterId; ?>');calculate_price(this);<?php endif; ?>" name="Componentvalue[<?php echo $ProductsParameter->parameterId; ?>][]"  <?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>multiple="multiple"<?php endif; ?> >
													 <?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''){ 
													  }else{
													?>
													<option value="">Choose an option</option>
													<?php  } ?>
													<?php foreach($ProductsParameter->Componentvalue as $Componentvalue):?>
													<?php 
														$args = !empty($Componentvalue->grouptype) ? explode(',',$Componentvalue->grouptype) : array();
														if(!empty($Componentvalue->grouptype) && !in_array($response->product_details->product_details->parameterTypeId,$args)){
															continue;
														}
														$_comp_value = $Componentvalue->priceid."~".$Componentvalue->componentname;							 
													?>
													<option data-priceid = <?php echo($Componentvalue->priceid); ?>  data-img=" <?php echo $Componentvalue->getComponentimgurl; ?>" data-com-qty="<?php echo $Componentvalue->qty; ?>" data-stock-com-id="<?php echo $Componentvalue->stockcomp_id; ?>" data-sub="<?php echo $Componentvalue->parameterid."~~".$Componentvalue->priceid; ?>" value="<?php echo $Componentvalue->priceid."~".$Componentvalue->componentname; ?>" <?php if(in_array($Componentvalue->componentname, $arrcomponentname) || in_array($_comp_value,$stored_component)): ?> selected="selected" <?php endif; ?> ><?php echo $Componentvalue->componentname; ?></option>
													<?php endforeach;?>
												</select>
												<input type="hidden" name="ComponentParametername[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->parameterName; ?>">
												<input type="hidden" name="ComponentParameterhidden[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->ecommerce_show1; ?>">
												<!--<div class="clear"></div>
												<span id="errmsg_drop" data-text-color="alert" class="is-small"></span>-->
											</div>
										</div>	
								<?php }else{ ?>
										<?php if($ProductsParameter->parameterListId != 2 && $ProductsParameter->parameterListId != 18 && $ProductsParameter->parameterListId != 10 && $ProductsParameter->parameterListId != 4 && $ProductsParameter->parameterListId != 5){ ?>
										<div class="showdetailscontainer blindsinputcon <?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>">
										
											<div class="label">
												<label data-label="<?php echo $ProductsParameter->parameterName; ?>" for="<?php echo $ProductsParameter->parameterName; ?>" style="display: flex; align-items: center;">
													<?php echo $ProductsParameter->parameterName; ?><?php if($ProductsParameter->orderitemmandatory == 1): ?><font color="red">*</font><?php endif;?>
												<?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
													<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
													<img style="width: 12px;margin-left: 5px;" class="" src="<?php echo blindmatrix_get_plugin_url() . '/vendor/Shortcode-Source/image/info.png'; ?>"></button>
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
												</label>
											</div>
											<div class="value">
												<?php 
												$stored_text = isset($_blinds_plugin_data['Othersvalue'][$ProductsParameter->parameterId]) ? $_blinds_plugin_data['Othersvalue'][$ProductsParameter->parameterId]:'';
												?>
												<span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
												<input data-getparameterid="<?php echo $ProductsParameter->parameterId;?>" id="<?php echo $ProductsParameter->parameterName; ?>" class="showorderdetails <?php if($ProductsParameter->orderitemmandatory == 1): ?>  othersvalue mandatoryvalidate <?php endif;?>"  name="Othersvalue[<?php echo $ProductsParameter->parameterId; ?>]" step="0.1"type="text" value="<?php echo $stored_text; ?>">
												<input type="hidden" name="OthersParametername[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->parameterName; ?>">
												<input type="hidden" name="OthersParameterhidden[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->ecommerce_show1; ?>">
											</div>
										</div>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						<?php } ?>
				
					<div class="single_variation_wrap text-center" style="margin-top:10px;">
						<div class="price_container" style="display:none;">
							<div>
								<div class="price havelock-blue align-centre italic margin-top-20 font-30 display-none product-price">
									<div style="color:#00c2ff;margin-bottom: 0.3em;" class="font-16 grey light-weight">Your Price</div>
									<div class="js-ajax-price margin-top-5 total_price_wrapper" style="margin-bottom:10px;display:none;">
										<del aria-hidden="true" style="margin:10px;">
										  <?php echo get_woocommerce_currency_symbol();?>
											<span class="total_price_val"></span>
										</del>
										<span class="extra_offer_val"></span>
									</div>
									
									<div class="js-ajax-price margin-top-5">
										<?php echo get_woocommerce_currency_symbol();?><span class="showprice"></span>
									</div>
								</div>
							</div>
						</div>
						<div style="display: none;" class="loading-spin"></div>

						<div style="margin-bottom: 10px;" class="woocommerce-variation-add-to-cart variations_button woocommerce-variation-add-to-cart-disabled">
							<?php 
							$stored_qty = isset($overall_cart_item_data['quantity']) ? $overall_cart_item_data['quantity']:'1';
							?>
							<div class="quantity buttons_added">
								<input style="margin-right: 0;" type="button" value="-" class="bm-minus button is-form">
								<input type="number" id="qty" class="input-text qty text" step="1" min="1" max="" name="qty" title="Qty" value="<?php echo $stored_qty; ?>" size="4" placeholder="" inputmode="numeric">
								<input type="button"  style="margin:0;"  value="+" class="bm-plus button is-form">
							</div>
							<button type="button" class=" single_add_to_cart_button button blinds alt js-add-cart relatedproduct" style="border-radius: 2em;"><i class="icon-shopping-cart"></i>&nbsp;<?php echo $cart_name; ?></button>
						</div>
					</div>
					
					<div class="product-option__more-info" style="clear: both;<?php echo $_style; ?>">
						<div class="accordion" rel="">
							<div class="accordion-item">
								<a href="#" class="accordion-title plain"><button class="toggle">
									<i style="font-weight: bold;font-size: 25px;line-height: 1.5;" class="icon-angle-down"></i>
									</button><span style="font-weight: bold;font-size: 15px;">Show Order Details</span>
								</a>
								<div class="accordion-inner" style="display: none;padding-top: 0;position: relative;background: none;">
									<div id="allparametervalue" style="font-size: 14px;color: black;"><table class="getprice_table"><tbody class="parameterstext"></tbody><tbody class="parameters"></tbody><tbody class="compmain components_radio"></tbody><tbody class="compmain components"></tbody></table></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div style="display:none; margin: 0px 20px; position: relative;">
					<?php 
					if('0' != $response->product_details->ecommerce_sample):?>
							<div class="cusordersample" style="text-align:center;background: rgb(247, 246, 246);border-radius: 10px;" >
								<span style="display: inline-block;" class="ordersampleimg">
								
								<img style="background-size: contain;background-image:url('<?php echo $productimagepath; ?>');" src="<?php echo($bgframe); ?>" alt="<?php echo isset($response->product_details->alt_text_tag) && $response->product_details->alt_text_tag ? $response->product_details->alt_text_tag:'';?>" width="247" height="296" class="attachment-woocommerce_thumbnail"></span>
								<?php
								ob_start();
								
								if(is_array($_SESSION['cart']) && count($_SESSION['cart']) > 0){
									$orderI_temId = $product_code.$producttypeid.$fabricid.$colorid.$vendorid;
									if(array_search($orderI_temId, array_column($_SESSION['cart'], 'sampleOrderItemId')) !== false){
									?>
									<button type="button" class="single_add_to_cart_button button alt" style="border-radius: 2em;background-color:#00B67A;margin:0px;margin-left:20px"><i class="icon-checkmark"></i><span style="padding: 0px !important;">Sample Added</span></button>
									<?php 
									}else{
									?>
									<button type="button" onclick="sampleOrder(this,'<?php echo($product_code); ?>','<?php echo($producttypeid); ?>','<?php echo($fabricid); ?>',' <?php echo($colorid); ?>',' <?php echo($vendorid); ?>')" class="single_add_to_cart_button button alt" style="color: #fff;border-radius: 2em;background-color:#00B67A;margin:0px;margin-left:20px"><span style="color: #fff;" class="freesample-button" style="padding: 0px !important;">Order Free Sample</span></button>
									<?php
									}
								}else{
									?>
									<button type="button" onclick="sampleOrder(this,'<?php echo($product_code); ?>','<?php echo($producttypeid); ?>','<?php echo($fabricid); ?>',' <?php echo($colorid); ?>',' <?php echo($vendorid); ?>')" class="single_add_to_cart_button button alt" style="color: #fff;border-radius: 2em;background-color:#00B67A;margin:0px;margin-left:20px"><span style="color: #fff;" class="freesample-button" style="padding: 0px !important;">Order Free Sample</span></button>
									<?php
								}
								$sampleButton = ob_get_contents();
								ob_end_clean();
								echo $sampleButton;
								?>
							</div>                   
						<?php endif;?>
			
				</div>
		

			</div>
		</div>
		<div class="product-footer" style="padding: 5px;">
			<div class="">
				 <div class="tabbed-content">
					<ul class="nav nav-tabs nav-uppercase nav-size-normal nav-left" style="justify-content: left;">
						<li class="tab has-icon active"><a href="#tab_tab-static-title"><span>Details</span></a></li>
						<?php if(count($response->product_details->product_details->getfabricdescription) > 0):?>
						<?php foreach($response->product_details->product_details->getfabricdescription as $fabricdescription): ?>
						<li class="tab has-icon"><a href="#tab_tab-<?php echo $fabricdescription->id; ?>-title"><span><?php echo $fabricdescription->name; ?></span></a></li>
						<?php endforeach; ?>
						<?php endif; ?>
					</ul>
					<div class="tab-panels product_tab_panels_bm">
						<div class="panel entry-content active" id="tab_tab-static-title">
							<table class="product_details_bm">
							<?php if(isset($response->product_details->product_details->productdescription ) && $response->product_details->product_details->productdescription !== ''){ ?>
								<tr><p><?php echo $response->product_details->product_details->productdescription; ?></p></tr>
							<?php } ?>
								<!--<tr>
									<td><b>Product Code</b></td><td> <?php echo $product_code; ?></br></td>
								</tr>-->
								<tr>
									<td><b>Code/Shade</b></td><td><span class="setcolorname"> <?php echo $response->product_details->product_details->colorname; ?></span> </br></td>
								</tr>
								<tr>
									<td><b>Product Type</b></td><td><span class="productTypeSubName"><?php echo $response->product_details->product_details->productTypeSubName; ?></span></td>
								</tr>
							</table>
						</div>
						
						<?php if(count($response->product_details->product_details->getfabricdescription) > 0):?>
						<?php foreach($response->product_details->product_details->getfabricdescription as $fabricdescription): ?>
						<div class="panel entry-content" id="tab_tab-<?php echo $fabricdescription->id; ?>-title">
							<p>
							     <?php echo html_entity_decode($fabricdescription->description); ?>
							 </p>
						</div>
						<?php endforeach; ?>
						<?php endif; ?>
						
					</div>
				</div>
			</div>
		</div>
	
		
</div>
</form>
<a id="Lightbox_errormsg" href="#errormsg" target="_self" class="button primary" style="display:none;"></a>
<div id="errormsg" class="lightbox-by-id lightbox-content lightbox-white mfp-hide" style="max-width:30%;padding:20px;text-align: center;"></div>
<?php
function background_colorpopup(){
	ob_start();
	?>
	<div class="bm-wall-color-popup-wrapper" style="padding:10px;">
			<label for="bm-wall-color-popup-label">Choose your own color
				<input type="color" class="bm-wall-color" value="#808080" style="margin-left:70px;margin-right:10px;cursor:pointer;">
				<input type="text" class="bm-wall-color-popup-hexcode" style="width:auto;border: 1px solid;border-radius: 10px;" value="#663300">
		   </label>
		   <div class="color-box-defaults">
		   <?php 
			$colors = array(
						"#ffffff" => "White",
						"#f9f5ea" => "OffWhite",
						"#c7af83" => "Beige",
						"#decba1" => "Natural",
						"#cccccc" => "Gray",
						"#999999" => "Silver",
						"#002466" => "Blue",
						"#663300" => "Brown",
						"#000000" => "Black",
						
					);
			foreach($colors as $key=>$color){
		   ?>
				 <div class="color-box-holder">
					<span class="color-box <?php echo(strtolower($color)); ?>" data-color="<?php echo($key); ?>" style="border: 1px solid <?php echo($key); ?>;background-color: <?php echo($key); ?> !important;"></span> <span class="color-name"><?php echo($color); ?></span>
				</div>
			<?php 
			}
			?>
			</div>
		   <div class="bm-color-buttons" style="margin-top:30px;">
				<a style="margin-bottom: 0;" href="#" class="button bm-set-default-color">Set Default Color</a>
				<a style="margin-bottom: 0;" href="#" class="button bm-set-selected-color">Set Selected Color</a>
		</div>
	</div>
	<?php
	$out = ob_get_contents();
	ob_end_clean();
	return $out;
}
$background_colorpopup = background_colorpopup();
$background_colorpopup = trim(preg_replace('/\s\s+/', ' ', $background_colorpopup));

?>
<script src="/wp-content/plugins/blindmatrix-ecommerce/view/js/dom-to-image.js"></script>
<link  rel="stylesheet" type="text/css"  media="all" href="/wp-content/plugins/blindmatrix-ecommerce/assets/css/curtain_configurator.css" />

<script>
	var formulafunctionlist =["DATE","DATEVALUE","DAY","DAYS","DAYS360","EDATE","EOMONTH","HOUR","MINUTE","ISOWEEKNUM","MONTH","NETWORKDAYS","NETWORKDAYSINTL","NOW","SECOND","TIME","TIMEVALUE","TODAY","WEEKDAY","YEAR","WEEKNUM","WORKDAY","WORKDAYINTL","YEARFRAC","ACCRINT","CUMIPMT","CUMPRINC","DB","DDB","DOLLARDE","DOLLARFR","EFFECT","FV","FVSCHEDULE","IPMT","IRR","ISPMT","MIRR","NOMINAL","NPER","NPV","PDURATION","PMT","PPMT","PV","RATE","BIN2DEC","BIN2HEX","BIN2OCT","BITAND","BITLSHIFT","BITOR","BITRSHIFT","BITXOR","COMPLEX","CONVERT","DEC2BIN","DEC2HEX","DEC2OCT","DELTA","ERF","ERFC","GESTEP","HEX2BIN","HEX2DEC","HEX2OCT","IMABS","IMAGINARY","IMARGUMENT","IMCONJUGATE","IMCOS","IMCOSH","IMCOT","IMCSC","IMCSCH","IMDIV","IMEXP","IMLN","IMLOG10","IMLOG2","IMPOWER","IMPRODUCT","IMREAL","IMSEC","IMSECH","IMSIN","IMSINH","IMSQRT","IMSUB","IMSUM","IMTAN","OCT2BIN","OCT2DEC","OCT2HEX","AND","false","IF","IFS","IFERROR","IFNA","NOT","OR","SWITCH","true","XOR","ABS","ACOS","ACOSH","ACOT","ACOTH","AGGREGATE","ARABIC","ASIN","ASINH","ATAN","ATAN2","ATANH","BASE","CEILING","CEILINGMATH","CEILINGPRECISE","COMBIN","COMBINA","COS","COSH","COT","COTH","CSC","CSCH","DECIMAL","ERF","ERFC","EVEN","EXP","FACT","FACTDOUBLE","FLOOR","FLOORMATH","FLOORPRECISE","GCD","INT","ISEVEN","ISOCEILING","ISODD","LCM","LN","LOG","LOG10","MOD","MROUND","MULTINOMIAL","ODD","POWER","PRODUCT","QUOTIENT","RADIANS","RAND","RANDBETWEEN","ROUND","ROUNDDOWN","ROUNDUP","SEC","SECH","SIGN","SIN","SINH","SQRT","SQRTPI","SUBTOTAL","SUM","SUMIF","SUMIFS","SUMPRODUCT","SUMSQ","SUMX2MY2","SUMX2PY2","SUMXMY2","TAN","TANH","TRUNC","AVEDEV","AVERAGE","AVERAGEA","AVERAGEIF","AVERAGEIFS","BETADIST","BETAINV","BINOMDIST","CORREL","COUNT","COUNTA","COUNTBLANK","COUNTIF","COUNTIFS","COUNTUNIQUE","COVARIANCEP","COVARIANCES","DEVSQ","EXPONDIST","FDIST","FINV","FISHER","FISHERINV","FORECAST","FREQUENCY","GAMMA","GAMMALN","GAUSS","GEOMEAN","GROWTH","HARMEAN","HYPGEOMDIST","INTERCEPT","KURT","LARGE","LINEST","LOGNORMDIST","LOGNORMINV","MAX","MAXA","MEDIAN","MIN","MINA","MODEMULT","MODESNGL","NORMDIST","NORMINV","NORMSDIST","NORMSINV","PEARSON","PERCENTILEEXC","PERCENTILEINC","PERCENTRANKEXC","PERCENTRANKINC","PERMUT","PERMUTATIONA","PHI","POISSONDIST","PROB","QUARTILEEXC","QUARTILEINC","RANKAVG","RANKEQ","RSQ","SKEW","SKEWP","SLOPE","SMALL","STANDARDIZE","STDEVA","STDEVP","STDEVPA","STDEVS","STEYX","TDIST","TINV","TRIMMEAN","VARA","VARP","VARPA","VARS","WEIBULLDIST","ZTEST","CHAR","CLEAN","CODE","CONCATENATE","EXACT","FIND","LEFT","LEN","LOWER","MID","NUMBERVALUE","PROPER","REGEXEXTRACT","REGEXMATCH","REGEXREPLACE","REPLACE","REPT","RIGHT","ROMAN","SEARCH","SPLIT","SUBSTITUTE","T","TRIM","UNICHAR","UNICODE","UPPER"];
	var returnfalsevalue = '';
	var parameterarray = <?php echo json_encode($parameterarray); ?>;
    var product_category = '<?= $response->getproductdetails->productcategory; ?>';
    var productimagepath = '<?=$response->product_details->product_details->imagepath;?>';
    
    var frame_height1 = document.querySelector('.frame_container').offsetHeight;
    var frame_height2 = document.querySelector('.configurator-preview-image').offsetHeight;
    var frame_height = (frame_height2 - frame_height1);
    if(frame_height > 0){
        //jQuery('.configurator-main-headertype').css({"height":frame_height,"object-fit":"initial"});
    }

	/*jQuery( document ).ajaxComplete(function( event,request, settings ) {
        
    });*/
		jQuery(".dropdownContianer  select").select2({
			templateResult: formatState,
			minimumResultsForSearch: -1
		});
		
		jQuery(".Componentvalue select").each(function(){
			var attr = jQuery(this).attr('multiple');
			if (typeof attr !== 'undefined' && attr !== false) {
			   var $eventSelect = jQuery(this);
				$eventSelect.select2({
					placeholder: $eventSelect.prop('multiple') ? "Choose the options":false,
					templateResult: formatState,
				});
				$eventSelect.on("select2:unselect", function (e) { 
					var id_ck = e.params.data.id;
					var arr = id_ck.split('~');
					var remove_par = arr[0];
					var inxComrwesub = []
						jQuery.each( showfieldCom, function( i, l ){
						if(remove_par == l.parameterId ){
							inxComrwesub.push(i);
						}
					});
					for (var i = inxComrwesub.length -1; i >= 0; i--){
							showfieldCom.splice(inxComrwesub[i],1);
					}
					if(jQuery(this).val() == ''){ 
					
						jQuery('.paramlablecomponentmain_'+remove_par ).remove();
					}
					jQuery('.paramlablecomponentsub_'+remove_par).remove();
					
				});
				$eventSelect.on('select2:opening select2:closing', function( event ) {
					var $searchfield = jQuery(this).parent().find('.select2-search__field');
					$searchfield.prop('disabled', true);
				});
			}else{
				var $eventSelect = jQuery(this);
				$eventSelect.select2({
					placeholder: $eventSelect.prop('multiple') ? "Choose the options":false,
					templateResult: formatState,
					minimumResultsForSearch: -1
				});	
			}
		});
	
	
		
		function formatState (opt) {
			if (!opt.id) {
				return opt.text;
			}
			
			var width = parseFloat(jQuery('#width').val()),
			drope = parseFloat(jQuery('#drope').val());

			var minwidth = parseFloat(jQuery(opt.element).attr('data-minwidth')) ,
				maxwidth = parseFloat(jQuery(opt.element).attr('data-maxwidth')),
				mindrop = parseFloat(jQuery(opt.element).attr('data-mindrop')),
				maxdrop = parseFloat(jQuery(opt.element).attr('data-maxdrop')),
				$display = 'yes';
				if(!isNaN(minwidth) && '' != minwidth && '' != width && !isNaN(width) ){
					if(width < minwidth){
						jQuery(opt).css("display","none");
						$display = 'no';
					}
				}

				if(!isNaN(maxwidth) && '' !=  maxwidth && '' != width && !isNaN(width)){
					if( width > maxwidth ){
						jQuery(opt).css("display","none");
						$display = 'no';
					}
				}

				if(!isNaN(mindrop) && '' != mindrop && '' != drope  && !isNaN(drope)){
					if(drope < mindrop){
						jQuery(opt).css("display","none");
						$display = 'no';
					}
				}

				if(!isNaN(maxdrop) && '' !=  maxdrop && '' != drope && !isNaN(drope)){
					if( drope > maxdrop ){
						jQuery(opt).css("display","none");
						$display = 'no';
					}
				}
				
			if($display == 'yes'){
			   var optimage = jQuery(opt.element).attr('data-img'); 
			   if(!optimage || optimage == " " ){
			      return opt.text;
			   } else {                    
				   var $opt = jQuery(
				     '<span><img style="display: inline-block; vertical-align: middle;" src="' + optimage + '" width="60px" /> ' + opt.text + '</span>'
				   );
				   return $opt;
			   }
			}
		};
	jQuery( document ).ready(function($) {

		$(".select2-search__field").prop("readonly", true);

		$(document).on('click','.bm-set-default-color',function(event){
			event.preventDefault();
			$('.configurator-main-headertype').css('background-color','#808080');
			$('.configurator-main-headertype').attr('data-hexcode','#808080');
		});
		$(document).on('click','.bm-set-selected-color',function(event){
			event.preventDefault();
			$hex_code = $('.bm-wall-color-popup-hexcode').val();
			$('.configurator-main-headertype').css('background-color',''+$hex_code+'');
			$('.configurator-main-headertype').attr('data-hexcode',$hex_code);
			$('#bm_bg_image_color').val($hex_code);
		});
		$(document).on('input','.bm-wall-color',function(event){
			event.preventDefault();
			$('.bm-wall-color-popup-hexcode').val($(this).val());
		});
		$(document).on('click','.color-box',function(event){
			event.preventDefault();
			$('.bm-wall-color').val($(this).data('color'));
			$('.bm-wall-color-popup-hexcode').val($(this).data('color'));
		});
		$(window).on('scroll', function () {
			var scrollTop = $(window).scrollTop();
			if ($(".blinds.configurator-controls.product-info").hasClass("style2")) {
				if (scrollTop > 450) {
					$(".bm-blinds-extra-filters").css("position","absolute");
					$(".bm-blinds-extra-filters").css("bottom","-21px");
					$(".blinds.configurator-controls.product-info.style2").css("height","95%");
				}
				else {
					 $(".bm-blinds-extra-filters").css("position","fixed");
					 $(".bm-blinds-extra-filters").css("bottom","0px");
					 $(".blinds.configurator-controls.product-info.style2").css("height","100%");
				}
			}
		});
		
		jQuery(document).on("click",'.bm-blinds-bg-image-icon' ,function(e) {
			  var $boxwidth='50%';
			  if (window.matchMedia("(max-width: 767px)").matches) {
				$boxwidth = '90%';
			  }
			$.dialog({
				title: 'Wall Color Customization',
				content:'<?php echo($background_colorpopup); ?>',
				boxWidth: $boxwidth,
				useBootstrap: false,
				 onOpenBefore: function () {
					// before the modal is displayed.
					var hexcode = $('.configurator-main-headertype').attr('data-hexcode');
					$(".bm-wall-color-popup-hexcode").val(hexcode);
					$('.bm-wall-color').val(hexcode);
				},
			});

		});
		var default_url = $(".configurator-main-headertype").attr("src");
		jQuery(document).on("change",'input[name=blindsstyle]' ,function(e) {
			var blindstylevalue =  jQuery('input[name="blindsstyle"]:checked').val();
			if(blindstylevalue == 'style2'){
				$(".row.align-center.blinds.-container").css('max-width','unset');
				$(".frame_container.value.desktop").css('display','none');
				$(".blinds.configurator-controls.product-info").addClass('style2');
				$(".configurator.blinds.bordered.cuspricevalue").addClass('style2');
				$(".configurator-preview.visible").addClass('style2');
				$(".cuspricevalue.blinds").css('border','0');
				var blindsblack = $("#blindsbackground").val();
				$(".configurator-main-headertype").attr("src",blindsblack);
				$(".configurator-main-headertype").attr("bigsrc",blindsblack);
				$(".configurator.blinds.bordered.cuspricevalue.style2 .configurator-preview-image img").css( "object-fit","contain");
				$(".single_variation_wrap.text-center").hide();
			}else{
				$(".row.align-center.blinds.-container").css('max-width','1250px');
				$(".frame_container.value.desktop").css('display','block');
				$(".configurator.blinds.bordered.cuspricevalue").removeClass('style2');
				$(".blinds.configurator-controls.product-info").removeClass('style2');
				$(".configurator-preview.visible").removeClass('style2');
				$(".cuspricevalue.blinds").css('border-top','4px solid #00c2ff');
				$(".configurator-main-headertype").attr("src",default_url);
				$(".configurator-main-headertype").attr("bigsrc",default_url);
				$(".configurator.blinds.bordered.cuspricevalue .configurator-preview-image img").css( "object-fit","cover");
				$(".single_variation_wrap.text-center").show();
			}
		});
		jQuery("input[name=blindsstyle]").change();
		jQuery(document).on("keyup change",'#footer_qty' ,function(e) {
			$('#qty').val($(this).val()); 
		});
		jQuery(document).on("keyup change",'#qty' ,function(e) {
			$('#footer_qty').val($(this).val()); 
		});
		
	    $(".frame_container.value.mobile").css('height',$(".configurator-main-fabric").height());
	    var fabricid = jQuery("#fabricid").val();
        var colorid = jQuery("#colorid").val();
        setTimeout(function () {
		    if(productimagepath != ''){
			    jQuery('.configurator-main-fabric').css('background-image', 'url("'+productimagepath+'")');
				if('undefined' == jQuery('.configurator-main-headertype').attr('src') || '' == jQuery('.configurator-main-headertype').attr('src')){
					jQuery('.configurator-main-headertype').attr('src',productimagepath);
					jQuery('.configurator-main-headertype').attr('bigsrc',productimagepath);
				}
		    }
            if(product_category == "Create no sub sub parameter"){
    	        if(fabricid != ''){
    	            jQuery('#' + fabricid).trigger('click');
    	        }
    	    }else{
    	        if(colorid != ''){
        			jQuery('#' + colorid).trigger('click');
        			jQuery('#productype' + colorid).attr('previousValue', true);
        		}
    	    }
    	    calculate_price(this);
        }, 100);
	    
	    if(product_category != "Create no sub sub parameter"){
	        configuratorfabricitem();
	    }
				
		jQuery(document).on('click', function(e) {
			var container = jQuery(".accordion-filter");
			if (jQuery(e.target).closest(".accordion-filter").length === 0) {
				jQuery(".accordion-filter .accordion-title").removeClass('active');
				jQuery(".accordion-filter .accordion-inner").hide();
			}
		});
		function removeItemOnce(arr, value) {
		  var index = arr.indexOf(value);
		  if (index > -1) {
			arr.splice(index, 1);
		  }
		  return arr;
		}
	function global_blind_add_cart(getcartdata){	
		var cartpage = "<?php echo( get_permalink( wc_get_page_id( 'cart' ))); ?>";
		//var user_page = page_current_chng;
		jQuery.ajax({
			type: 'POST',
			url: "<?php echo(admin_url( 'admin-ajax.php' )); ?>",
			data : {action:'blind_publish_process',cart:getcartdata},
			dataType: 'JSON',
			success:function(data, textStatus, XMLHttpRequest){
				//if(data=='yes'){ alert('yes'); }
				jQuery(".widget_shopping_cart_content").html(data.min_cart_content);
				jQuery("#header .cart-price").replaceWith(data.min_cart_price);
				jQuery(".header-button .icon-shopping-cart").replaceWith(data.min_cart_count);
				jQuery("#floating_cart_button .icon-shopping-cart").replaceWith(data.min_cart_count);
				jQuery(".header-button .icon-shopping-bag").replaceWith(data.min_cart_count);
				jQuery(".header-button .icon-shopping-basket").replaceWith(data.min_cart_count);
				
				jQuery(".cart-item").addClass("current-dropdown cart-active");
				jQuery(".shop-container").on("click", function () {
					jQuery(".cart-item").removeClass("current-dropdown cart-active");
				}),
				jQuery(".cart-item").hover(function () {
					jQuery(".cart-active").removeClass("cart-active");
				}),
				setTimeout(function () {
					jQuery(".cart-active").removeClass("current-dropdown");
				}, 4000);
				jQuery(".js-add-cart").prop("disabled", false);
				jQuery('.js-add-cart').removeClass("btn-disabled");
				jQuery(".blindmatrix-js-add-cart").removeAttr('disabled');
				jQuery('.loading-spin').css('display','none');
				jQuery('html, body').animate({scrollTop: 0});
				jQuery('.curtain-loder').css('display','none');
				jQuery.confirm({
					title: 'Success!',
					columnClass: 'col-md-4 col-md-offset-4',
					content: 'The product is successfully added to cart',
					type: 'blue',
					typeAnimated: true,
					boxWidth: '30%',
					useBootstrap: false,
					buttons: {
						 'Continue shopping': {
							btnClass: 'btn-blue',
							text: 'Continue shopping', // With spaces and symbols
							action: function () {
									history.go(0);
							}
						},
						 'Proceed to cart': {
							btnClass: 'btn-dark',             
							text: 'Proceed to cart', // With spaces and symbols
							action: function () {
								window.location = cartpage;
							}
						}
					}
				});
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
					alert(textStatus);
				}
			});	
		}
		$(document).on('click', '.samplecartatag', function(e) {
			var elem =$(this);
			var productcode =$(this).attr("data-productno");
			var producttypeid =$(this).attr("data-parametertypeid");
			var fabricid =$(this).attr("data-fabricid");
			var colorid =$(this).attr("data-colorid");
			var vendorid =$(this).attr("data-vendorid");
			var colorid =$(this).attr("data-colorid");
			e.preventDefault();
			e.stopPropagation();
			jQuery(elem).addClass('loading');
			jQuery.ajax(
			{
				url     : ajaxurl,
				data    : {mode:'sampleOrderItem',action:'sampleOrderItem',productcode:productcode,producttypeid:producttypeid,fabricid:fabricid,colorid:colorid,vendorid:vendorid},
				type    : "POST",
				dataType: 'JSON',
				success: function(response){
					jQuery(elem).removeClass('loading');
					if(response.success == 1){
						jQuery(elem).find("span").remove();
						jQuery(elem).prepend('<i class="icon-checkmark"></i><span class="samplecart" style="padding: 0px !important;margin:5px 0 !important;">Sample Added</span>');
						jQuery('.free-sample-cart').attr('data-icon-label', response.samplecartcount);
						//window.location.reload(); //Another possiblity
						//history.go(0);
						jQuery.confirm({
							title: 'Success!',
							columnClass: 'col-md-4 col-md-offset-4',
							content: 'The sample product is successfully added to free sample cart',
							type: 'blue',
							typeAnimated: true,
							boxWidth: '30%',
							useBootstrap: false,
							buttons: {
								okay: function () {
									//history.go(0);
								}
							}
						});
					}else if(response.success == 2){
					    jQuery(elem).find("span").remove();
					    jQuery(elem).find("i").remove();
						jQuery(elem).prepend('<span class="samplecart" style="padding: 0px !important;margin:5px 0 !important;">Order Free Sample</span>');
						jQuery('.free-sample-cart').attr('data-icon-label', response.samplecartcount);
					}else{
						jQuery('#errormsg').html(response.success);
						jQuery( "#Lightbox_errormsg" ).trigger('click');
					}
				}
			});
		});
		
		$(".single_add_to_cart_button.button.blinds").click(function() {
			check_mandatory();
			// if(returnfalsevalue == ""){
			// 	check_mandatory();
			// }else{
				jQuery('.errmsg_widthdrop').each(function() {
					var get_html = jQuery(this).html();
					if(get_html != ''){
						jQuery('html, body').animate({
							scrollTop: jQuery(this).offset().top -100
						}, 150);
						
						return false;
					}
				});
			// }
		});
		
		$(document).on('change', 'input[type=radio] ,select', function() {
		    var namerad = $(this).attr('name');
			//if(namerad != 'colornamesub' && namerad != 'fabricnameck'){
			    calculate_price(this);
			//}
		});
		
		//setup before functions
			var typingTimer;                //timer identifier
			var doneTypingInterval = 500; 
			var $input = jQuery('input[type=number].showorderdetails ,input[type=text].showorderdetails');

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
				calculate_price(this);
			}
			
			
		//setup before functions
		var typingTimerFabric;                //timer identifier
		var doneTypingIntervalFabric = 500; 
		var $inputFabric = jQuery('#serach_input_fabric');

		//on keyup, start the countdown
		$inputFabric.on('keyup', function () {
			clearTimeout(typingTimerFabric);
			//var valFabric = $(this).val();
			typingTimerFabric = setTimeout(doneTypingFabric, doneTypingIntervalFabric);
		});

		//on keydown, clear the countdown 
		$inputFabric.on('keydown', function () {
			clearTimeout(typingTimerFabric);
		});

		//user is "finished typing," do something
		function doneTypingFabric () {
			
			fabricSearch();
		}
			
		function fabricSearch(){
			var searchFabric =	jQuery('#serach_input_fabric').val();
			var parameteridwil = $('input[name="productype"]:checked').val();
			var fabricnameVal = $('input[name="fabricnameck"]:checked').val();
			var page=1;
			if($('input[name="getmaincategorylist').is(':checked')) {
				var parameteridwil = []; 
				var maincategorylist = $('input[name="getmaincategorylist"]:checked').val();
				$( ".producttype_blind label" ).each(function( index ) {
					var parameteridck = $(this).data('productcategoryid');
					if(maincategorylist == parameteridck){
						parameteridwil.push($(this).data('parametertypeid'));
					}
				});
			}
			if($("#fablicfilterarray").val() != '' ){
				var fablicfilterarray = $("#fablicfilterarray").val();
				var catfilterarray = $("#catfilterarray").val();
			}else{
				var fablicfilterarray = '';
				var catfilterarray ='';
			}
			$("#searchFabric").val(searchFabric);
			
			producttype(parameteridwil,page,'',fablicfilterarray,catfilterarray,searchFabric);
		}
		
		function getselparameteridval_list(unit,getselparameteridval,currency_name){
			var parameternamevalueobj = {};
			getselparameteridval["MEASUREMENT"] = unit;
			getselparameteridval["QTY"] = 1;
			getselparameteridval["COMPONENTQTY"] = 0;
			
			jQuery('.showorderdetails').each(function() {
				if (jQuery(this).attr('type') === 'text' || jQuery(this).attr('type') === 'number') {
					var getparameterid = jQuery(this).attr('data-getparameterid');
					var getparametervalue = jQuery(this).val();
					var get_parameter_name = jQuery(this).attr('name');
					if(get_parameter_name == 'width' || get_parameter_name == 'drope'){
						if (unit == 'mm') {
							getparametervalue = getparametervalue;
						}else if (unit == 'cm') {
							getparametervalue = (getparametervalue * 10);
						}else if (unit == 'inch') {
							getparametervalue = (getparametervalue * 25.4);
						}else if (unit == 'm') {
							getparametervalue = (getparametervalue * 1000);
						}
						if(currency_name == 'USA'){
							getparametervalue = (getparametervalue/25.4);
						}
					}
					if(jQuery(this).attr('type') === 'number'){
						parameternamevalueobj[getparameterid] = parseFloat(getparametervalue);
					}else{
						parameternamevalueobj[getparameterid] = getparametervalue;
					}
				}else{
					var name = jQuery(this).attr('name');
					var getparameterid ='';
					if(name == 'fabricnameck'){
						getparameterid = 'FabricName';
					}else if(name == 'colornamesub'){
						getparameterid = 'ColourName';
					}else{
						getparameterid =jQuery('input[name="' + name + '"]:checked').attr('data-getparameterid');
					}
					var getID = jQuery('input[name="' + name + '"]:checked').attr('id');
					
					var getparametervalue =jQuery('label[for="' + getID + '"]').text();
					
					var getparameterlistid = jQuery(this).attr('data-getparameterlistid');
					
					if(getparameterlistid == 18){
						var getcomponentqty = jQuery('input[name="' + name + '"]:checked').attr("data-com-qty");
						getselparameteridval["COMPONENTQTY"] = parseFloat(getcomponentqty);
					}
					parameternamevalueobj[getparameterid] = getparametervalue.trim();
				}
				
				
			});
			
			//Get selecte parameter value
			jQuery.each( parameterarray, function( key, value ) {
				var selparametername = value;
				selparametername = selparametername.toUpperCase();
				selparametername = replacespecialcharacter(selparametername);
				if(isKeyExists(parameternamevalueobj,key) == true){
					getselparameteridval[selparametername] = parameternamevalueobj[key];
				}else{
					getselparameteridval[selparametername] = '';
				}
			});
			return getselparameteridval;
		}
		function checkinfinity(Result){
			if(isNaN(Result) || Result == Infinity)
			{
				Result = 0;
			}
			return Result;
		}

		function stringevil(fn) {
		  return new Function('return ' + fn)();
		}

		function formulavariablereplace(formula,forobj){
			for(var i=0;i<5;i++){
				jQuery.each(forobj, function( index, value ) {
					if(new RegExp("\\b" + index + "\\b").test(formula)){
						formula = formula.replaceAll(index, "(" + value + ")");
					}
				});
			}
			
			return formula;
		}

		function formulacharacterappend(formula){
			var formulachar ='formulajs.';
			jQuery.each(formulafunctionlist, function( index, value ) {
				 if(new RegExp("\\b" + value + "\\b").test(formula)){    
					 var regex_search = new RegExp("\\b"+value+"\\b","g");
					formula = formula.replaceAll(regex_search, formulachar+value);
				 }
			});
			return formula;
		}

		function isKeyExists(obj,key){
			return key in obj;
		}

		function replacespecialcharacter(formula_parametername){
			var parmvar= formula_parametername.replace(/[^a-zA-Z]0-9/g,'');
			parmvar= parmvar.replace(/\s+/g, "").replace(/\s*[!%$@|&-+\/*\])}[{(]\s*/g, '');
			parmvar= parmvar.replace(/[\/]+/g, "");
			
			return parmvar;
		}
		jQuery(document).on("click",'input[type="radio"]' ,function(e) {
			var namerad = $(this).attr('name');
			var thisid = $(this).attr('id');
			var parametertype = $(this).attr('data-parametertype');
			if(namerad == "getmaincategorylist" || namerad == "unit"  ){
				return;
			}
			if(	namerad == "fabricnameck"){
				var minWidth = $(this).attr('data-minWidth');
				var maxWidth = $(this).attr('data-maxWidth');
				var minDrop = $(this).attr('data-minDrop');
				var maxDrop = $(this).attr('data-maxDrop');
				$("#getminWidth").val(minWidth);
				$("#getmaxWidth").val(maxWidth);
				$("#getminDrop").val(minDrop);
				$("#getmaxDrop").val(maxDrop);
				var unit = jQuery('input[name=unit]:checked').val();
				var producttypeid = $(this).attr('data-producttypeid');
				$("#productype"+producttypeid).prop("checked", true);
				//var producttypename = $("input[name=productype]:checked").val();
				var producttypename = $('#producttypeid').val();
				calcu_fabric_minmax_mesurement(minWidth,maxWidth,minDrop,maxDrop,unit,producttypename);
			
			}

			var checkedValca;
			if($(this).is(":checked")){
				checkedValca = true;
			}else{
				checkedValca = false;
			}
			if($(this).attr('previousValue') == "true"){
				
				checkedValca = false;

				var inxComtype = [];
				jQuery.each( showfieldCom, function( i, l ){
					if(namerad ==  l.name ){	
						inxComtype.push(i);
					}
				});
				for (var i = inxComtype.length -1; i >= 0; i--){
						showfieldCom.splice(inxComtype[i],1);
				}
		
				if(namerad == 'productype'){
					if($(this).is(":checked")){
						$(this).prop("checked",false);
						$(this).parent( ".value" ).find(".blindslabel.selected").removeClass('selected');
						$(".configurator-main-fabric").css('background-image','unset');
						
						var page=1;
						producttype('',page);
						var product_varient = ['productype','colornamesub','fabricnameck'];
						var inxproducttype = []
						jQuery.each( showfield, function( i, l ){
							if(product_varient.indexOf(l.name) !== -1){
								inxproducttype.push(i);
							}
						});
						for (var i = inxproducttype.length -1; i >= 0; i--){
								showfield.splice(inxproducttype[i],1);
						}
						$("#width").attr('placeholder','');
						$("#drope").attr('placeholder','');
						$(".productype_shw").remove();
						$(".fabricnameck_shw").remove();
						$(".colornamesub_shw").remove();
					}
				}else if(namerad == 'fabricnameck'){
					if($(this).is(":checked")){
						$(this).prop("checked",false);
						$(this).parent( ".value" ).find(".blindslabel.selected").removeClass('selected');
						var inxfab = []
						var product_varient = ['fabricnameck','colornamesub'];
						jQuery.each( showfield, function( i, l ){
							if(product_varient.indexOf(l.name) !== -1){
								inxfab.push(i);
							}
						});
						for (var i = inxfab.length -1; i >= 0; i--){
								showfield.splice(inxfab[i],1);
						}
						if(!$(this).hasClass("color_blind")){
							$(".color_blind_contianer").html('');
						}
						if ($(".blinds.configurator-controls.product-info").hasClass("style2")) {
							jQuery('.configurator-main-headertype').css('background-image', 'unset');
						}
						$(".configurator-main-fabric").css('background-image','unset');
						$(".fabricnameck_shw").remove();
						$(".colornamesub_shw").remove();
					}
				}else if($(this).hasClass("compontentlist")){
					if($(this).is(":checked")){
						$(this).prop("checked",false);
						$(this).parent( ".value" ).find(".blindslabel.selected").removeClass('selected');
						var parameterid = $(this).attr("data-getparameterid");
						$(".componentsub_"+parameterid).remove();
						$(".paramlablecomponentsub_"+parameterid).remove();
						$(".paramlablecomponentmain_"+parameterid).remove();
					}
				}else if(namerad == 'colornamesub'){
					if($(this).is(":checked")){
						$(this).prop("checked",false);
						$(this).parent( ".value" ).find(".blindslabel.selected").removeClass('selected');
						$(".colornamesub_shw").remove();
					if ($(".blinds.configurator-controls.product-info").hasClass("style2")) {
							var defaultblindsbackground = $("#defaultblindsbackground").val();
							$('.configurator-main-headertype').css('background-image',"url(\"" + defaultblindsbackground + "\")");
						}else{
							$('.configurator-main-headertype').css('background-image', 'unset');
						}
					
					}
				}else if(parametertype == "dropdown"){
					if($(this).is(":checked")){
						$(this).prop("checked",false);
						$(this).parent( ".value" ).find(".blindslabel.selected").removeClass('selected');
						var parameterid = $(this).attr("data-getparameterid");
						var inxdropdown = [];
						jQuery.each( showfield, function( i, l ){
							if(parameterid ==  l.parameterId){
								inxdropdown.push(i);
							}
						});
						for (var i = inxdropdown.length -1; i >= 0; i--){
								showfield.splice(inxdropdown[i],1);
						}
						$(".paramlablecomponentsub_"+parameterid).remove();
					}
				}else{
					$(this).prop("checked",false);
					$(this).parent( ".value" ).find(".blindslabel.selected").removeClass('selected');
					var parameterid = $(this).attr("data-getparameterid");
					$(".paramlablecomponentsub_"+parameterid).remove();
				}
				//if(namerad != 'colornamesub' && namerad != 'fabricnameck'){
			        calculate_price(this);
				//}
			} else {
				if(namerad == 'productype'){
					if ($(".blinds.configurator-controls.product-info").hasClass("style2")) {
						jQuery('.configurator-main-headertype').css('background-image', 'unset');
					}
					$(".configurator-main-fabric").css('background-image','unset');
					var product_varient = ['fabricnameck','colornamesub'];
					var inxproductype=[];
					jQuery.each( showfield, function( i, l ){
						if(product_varient.indexOf(l.name) !== -1){
							inxproductype.push(i);
						}
					});
					for (var i = inxproductype.length -1; i >= 0; i--){
							showfield.splice(inxproductype[i],1);
					}
					
					$(".fabricnameck_shw").remove();
					$(".colornamesub_shw").remove();
					var getparameterid = jQuery(this).attr('data-getparameterid');
					jQuery('#errormsg_'+getparameterid).html('');
					var page=1;
					producttype($(this).val(),page);
					var vendorid = $(this).data("vendorid");
					var unitVal = jQuery('input[name=unit]:checked').val();
					//get_maxmin_message($(this).val(),vendorid,unitVal);
					$('input[name="getmaincategorylist"]').prop("checked", false);
					$( ".maincategorylist" ).find(".blindslabel.selected").removeClass('selected');
					$( ".producttype_blind label" ).each(function( index ) {
						$(this).css("display","inline-block");			
					});
				}else if(namerad == 'fabricnameck'){
					jQuery('.producttype_blind_container .errormsg').html('');
					var getparameterid = jQuery(this).attr('data-getparameterid');
					jQuery('#errormsg_'+getparameterid).html('');
				
					if($(this).hasClass("color_blind")){
						var producttypeid = $(this).data('producttypeid');
						jQuery( ".producttype_blind" ).find(".blindslabel.selected").removeClass('selected');
						$(".blindslabel[for=productype"+producttypeid+"]").addClass('selected');
						$("#productype"+producttypeid).prop("checked", true);
						var thisidcolor = $(this).attr("id");
						var thislabel = $(this).parent(".value").find("label[for='"+thisidcolor+"']");
						var imgScCol= thislabel.find("img");
						var src = imgScCol.attr('src');
						if(noimage.trim() == src.trim()){
							$('.configurator-main-headertype').css('background-image','unset');
							
						}else{
							$('.configurator-main-headertype').css('background-image',"url(\"" + imgScCol.attr('src') + "\")");
						}
					}else{
						configuratorfabricitem();
					}
				}else if(namerad == 'colornamesub'){
					var getparameterid = jQuery(this).attr('data-getparameterid');
					jQuery('#errormsg_'+getparameterid).html('')
					var thisidcolor = $(this).attr("id");
					var thislabel = $(this).parent(".value").find("label[for='"+thisidcolor+"']");
					var imgScCol= thislabel.find("img");
					var src = imgScCol.attr('src');
					if(noimage.trim() == src.trim()){
						$('.configurator-main-headertype').css('background-image', 'unset');
							
					}else{
						$('.configurator-main-headertype').css('background-image',"url(\"" + imgScCol.attr('src') + "\")");
					}
				}else if($(this).hasClass("compontentlist")){
					var parameterid = $(this).attr("data-getparameterid");
					var inxComtype = [];
					jQuery.each( showfieldCom, function( i, l ){
						if(parameterid ==  l.parameterId && l.sub =='sub' ){	
							inxComtype.push(i);
						}
					});
					for (var i = inxComtype.length -1; i >= 0; i--){
							showfieldCom.splice(inxComtype[i],1);
					}
					
					$(".paramlablecomponentsub_"+parameterid).remove();
					
				}
				$('input[name="'+namerad+'"]').attr('previousValue', false);
				$(this).parent( ".value" ).find("input[for='"+thisid+"']").addClass('selected');
			}
			$(this).attr('previousValue', checkedValca);
		});
		
		var sort = [];
		var chkele = [];
		var names = [];
		var catidarray = [];

		function stockformulacalculation(unit,getselparameteridval,response){
				var result={};
				
				//Allowance Variables
				jQuery.each( response.allowance_variables, function( key, value ) {
					var allowancename = value.allowancename;
					allowancename = allowancename.toUpperCase();
					allowancename = replacespecialcharacter(allowancename);
					eval(allowancename +" = "+value.value);
				});
				
				//Formula parameters
				jQuery.each( response.formulaparameter, function( key, value ) {
					var formulaparametername = value.parameterName;
					formulaparametername = formulaparametername.toUpperCase();
					formulaparametername = replacespecialcharacter(formulaparametername);
					
					var getval = '';
					if(isKeyExists(getselparameteridval,formulaparametername) == true){
						getval = getselparameteridval[formulaparametername];
					}
					if(typeof getval === 'number'){
						eval(formulaparametername +" = "+getval);
					}else{
						getval = getval.toUpperCase();
						getval = getval.replaceAll(" ", "");
						eval(formulaparametername +" = "+"\"" + getval + "\"");
					}
				});
				
				var getallstockformulaobj = {};
				jQuery.each( response.stockformula, function( i, val ) {
					var formula1 = val.formula;
					var formula = formula1.indexOf('=') == 0 ? formula1.substring(1) : formula1;
					formula = formula.toUpperCase();
					formula = formula.replaceAll("ROUN_UP", "ROUNDUP");
					formula = formula.replaceAll("ROUN_DOWN", "ROUNDDOWN");
					formula = formula.replaceAll("CEIL", "ROUNDUP");
					formula = formula.replaceAll("<>", "!=");
					formula = formula.replaceAll("=", "==");
					formula = formula.replaceAll("<==", "<=");
					formula = formula.replaceAll(">==", ">=");
					formula = formula.replaceAll("<>", "!=");
					formula = formulacharacterappend(formula);
					
					var formulavariablename = replacespecialcharacter(val.variablename);
					formulavariablename = formulavariablename.toUpperCase();
					getallstockformulaobj[formulavariablename] = formula;
				});
				
				var getallcomponentformulaobj = {};
				jQuery.each( response.componentformula, function( i, val ) {
					var formula1 = val.formula;
					var formula = formula1.indexOf('=') == 0 ? formula1.substring(1) : formula1;
					formula = formula.toUpperCase();
					formula = formula.replaceAll("ROUN_UP", "ROUNDUP");
					formula = formula.replaceAll("ROUN_DOWN", "ROUNDDOWN");
					formula = formula.replaceAll("CEIL", "ROUNDUP");
					formula = formula.replaceAll("<>", "!=");
					formula = formula.replaceAll("=", "==");
					formula = formula.replaceAll("<==", "<=");
					formula = formula.replaceAll(">==", ">=");
					formula = formula.replaceAll("<>", "!=");
					formula = formulacharacterappend(formula);
					
					var formulavariablename = replacespecialcharacter(val.variablename);
					formulavariablename = formulavariablename.toUpperCase();
					getallcomponentformulaobj[formulavariablename] = formula;
				});

				var stockformulaobj = {};
				var getnetprice=0;
				var fabricstockresult=0;
				var fabricstockitemcost=0;
				var sellingprice = Number(response.stock_values.ecommercesellingprice);
				var costdiscount = Number(response.stock_values.costdiscount);
				var ecommercemarkup = Number(response.stock_values.ecommercemarkup);
				var unittype = response.stock_values.unittype;

				jQuery.each( response.stockformula, function( i, val ) {
					
						var formulavariablename = replacespecialcharacter(val.variablename);
						formulavariablename = formulavariablename.toUpperCase();

						var formula1 = val.formula;
						var formula = formula1.indexOf('=') == 0 ? formula1.substring(1) : formula1;
						formula = formula.toUpperCase();
						formula = formula.replaceAll("ROUN_UP", "ROUNDUP");
						formula = formula.replaceAll("ROUN_DOWN", "ROUNDDOWN");
						formula = formula.replaceAll("CEIL", "ROUNDUP");
						formula = formula.replaceAll("<>", "!=");
						formula = formula.replaceAll("=", "==");
						formula = formula.replaceAll("<==", "<=");
						formula = formula.replaceAll(">==", ">=");
						formula = formula.replaceAll("<>", "!=");
						formula = formulacharacterappend(formula);

						formula = formulavariablereplace(formula,getallstockformulaobj);
						formula = formula.replaceAll(",ROUNDUP", ",formulajs.ROUNDUP");
						formula = formula.replaceAll(",ROUNDDOWN", ",formulajs.ROUNDDOWN");
						formula = formula.replaceAll("formulajs.SPLIT", "split");
						var reverse_val = "REVERSE";
						var regexsearch = new RegExp("\\b"+reverse_val+"\\b","g");
						formula = formula.replaceAll(regexsearch, "reverse");
						
						if (typeof PILLOWS !== 'undefined' && PILLOWS == '') {
							formula = formula.replaceAll('.split(" ")[0]', '');
						};
					
					try{
						
						var getformula = stringevil(formula);
						var formula_result = '';
						if(typeof getformula === 'number'){
							eval(formulavariablename +" = "+getformula);
							formula_result = eval(getformula);
						}else{
							eval(formulavariablename +" = "+"\"" + getformula + "\"");
							formula_result = eval("\""+getformula+"\"");
						}
						if(formula_result == NaN || formula_result == Infinity)
						{
							formula_result = 0;
						}
						formula_result = jQuery.trim(formula_result);
						formula_result = parseFloat(formula_result);

						//var unit = jQuery("input[name='unit']:checked").val();
						if(unittype == 'mm'){
							fabricstockresult += parseFloat(((sellingprice / 1000) * formula_result));
							costdiscount = parseFloat(costdiscount / 1000);
						}
						if(unittype == 'cm'){
							fabricstockresult += parseFloat(( ((sellingprice / 1000) * 10) * formula_result));
							costdiscount = parseFloat(costdiscount / 100);
						}
						if(unittype == 'inch'){
							fabricstockresult += parseFloat(( ((sellingprice / 1000) * 25.4) * formula_result));
							costdiscount = parseFloat(costdiscount / 39.37);
						}
						if(unittype == 'm'){
							fabricstockresult += parseFloat(( ((sellingprice / 1000) * 1000) * formula_result));
							costdiscount = parseFloat(costdiscount);
						}
						
						fabricstockitemcost += formula_result * parseFloat(costdiscount).toFixed(2);

						stockformulaobj['productid'] = response.stock_values.productid;
						stockformulaobj['productname'] = jQuery("#productname").val();
						stockformulaobj['qty'] = QTY;
						stockformulaobj['vendorid'] = response.stock_values.vendorid;
						stockformulaobj['vendorname'] = response.stock_values.vendorname;
						stockformulaobj['width'] = parseFloat(jQuery("#width").val());
						stockformulaobj['drope'] = parseFloat(jQuery("#drope").val());
						stockformulaobj['producttype'] = response.stock_values.producttype;
						stockformulaobj['producttypename'] = response.stock_values.vendorname;
						stockformulaobj['fabricid'] = response.stock_values.fabricid;
						stockformulaobj['fabricname'] = response.stock_values.fabricname;
						stockformulaobj['colourid'] = response.stock_values.colourid;
						stockformulaobj['colourname'] = response.stock_values.colorname;
						stockformulaobj['stock'] = parseFloat(formula_result);

					}catch(err) {
						//console.log(err.message+'--'+formulavariablename+'--'+formula);
						//console.log(formulavariablename+'--'+formula);
					}
				});
				fabricstockresult = checkinfinity(fabricstockresult);
				fabricstockitemcost = checkinfinity(fabricstockitemcost);

				var componentformulaobj = {};
				var discountwithitemcost = 0;
				var com_markup_cal_price = 0;
				var billofmaterialresult = 0;
				var billofmaterialitemcost = 0;
				var stock_component_values_arr = response.stock_component_values;
				
				jQuery.each( response.componentformula, function( i, val ) {
					
					var stockcomp_id = val.stockCompId;

					if(isKeyExists(stock_component_values_arr,stockcomp_id) == true){

						var sel_stock_component_values_arr = stock_component_values_arr[stockcomp_id];
						var ecommercesellingprice = Number(sel_stock_component_values_arr.ecommercesellingprice);
						billofmaterialitemcost += Number(sel_stock_component_values_arr.comp_price);

						var formulavariablename = replacespecialcharacter(val.variablename);
						formulavariablename = formulavariablename.toUpperCase();
						
						var formula1 = val.formula;
						var formula = formula1.indexOf('=') == 0 ? formula1.substring(1) : formula1;
						formula = formula.toUpperCase();
						formula = formula.replaceAll("ROUN_UP", "ROUNDUP");
						formula = formula.replaceAll("ROUN_DOWN", "ROUNDDOWN");
						formula = formula.replaceAll("CEIL", "ROUNDUP");
						formula = formula.replaceAll("<>", "!=");
						formula = formula.replaceAll("=", "==");
						formula = formula.replaceAll("<==", "<=");
						formula = formula.replaceAll(">==", ">=");
						formula = formula.replaceAll("<>", "!=");
						formula = formulacharacterappend(formula);

						formula = formulavariablereplace(formula,getallcomponentformulaobj);
						formula = formula.replaceAll(",ROUNDUP", ",formulajs.ROUNDUP");
						formula = formula.replaceAll(",ROUNDDOWN", ",formulajs.ROUNDDOWN");
						formula = formula.replaceAll("formulajs.SPLIT", "split");
						var reverse_val = "REVERSE";
						var regexsearch = new RegExp("\\b"+reverse_val+"\\b","g");
						formula = formula.replaceAll(regexsearch, "reverse");
						
						if (typeof PILLOWS !== 'undefined' && PILLOWS == '') {
							formula = formula.replaceAll('.split(" ")[0]', '');
						};
					
						try{
							
							var getformula = stringevil(formula);
							var formula_result = '';
							if(typeof getformula === 'number'){
								eval(formulavariablename +" = "+getformula);
								formula_result = eval(getformula);
							}else{
								eval(formulavariablename +" = "+"\"" + getformula + "\"");
								formula_result = eval("\""+getformula+"\"");
							}
							if(formula_result == NaN || formula_result == Infinity)
							{
								formula_result = 0;
							}
							formula_result = jQuery.trim(formula_result);

							billofmaterialresult += Number(ecommercesellingprice) * Number(formula_result);

							//console.log(formulavariablename+'--'+formula_result+'--'+formula+'--'+billofmaterialresult+'--'+ecommercesellingprice);
							
							componentformulaobj[stockcomp_id] = { productname: jQuery("#productname").val(), productid: jQuery("#productid").val(), qty: QTY, vendorid: jQuery("#vendorid").val(), vendorname: sel_stock_component_values_arr.vendorname, width: parseFloat(jQuery("#width").val()), drope: parseFloat(jQuery("#drope").val()), stock: parseFloat(formula_result), stockcomponentname: sel_stock_component_values_arr.component_name,stockcomponentid: sel_stock_component_values_arr.id };

						}catch(err) {
							//console.log(formulavariablename+'--'+formula);
						}
					}
				});
				billofmaterialresult = checkinfinity(billofmaterialresult);
				billofmaterialitemcost = checkinfinity(billofmaterialitemcost);

				//Netprice
				getnetprice = Number(fabricstockresult) + Number(billofmaterialresult);
				getnetprice = getnetprice.toFixed(2);

				//Itemcost
				getitemcost = Number(fabricstockitemcost) + Number(billofmaterialitemcost);
				getitemcost = getitemcost.toFixed(2);

				var getvat = (getnetprice / 100) * Number(response.vaterate);
				var priceval = Number(getnetprice)+Number(getvat);
				var showprice = Number(priceval).toFixed(2);

				result['getnetprice'] = getnetprice;
				result['getitemcost'] = getitemcost;
				result['showprice'] = showprice;
				result['getvat'] = getvat;
				result['priceval'] = priceval;
				result['stockformulaobj'] = stockformulaobj;
				result['componentformulaobj'] = componentformulaobj;
				
				return result;
		}
			
		function check_mandatory(){
			jQuery('.errormsg').html('');
			jQuery('.mandatoryvalidate').each(function(i){
				var getparameterid = jQuery(this).attr('data-getparameterid');
					if(this.name == 'width' || this.name == 'drope'){
						if(this.value == 0){
							returnfalsevalue = 1;
							jQuery('#errormsg_'+getparameterid).html("This field can't be zero.")
						}
					}
				if(this.value == ''){
					returnfalsevalue = 1;
					jQuery('#errormsg_'+getparameterid).html('This field is required.');
				}
			});
			jQuery('.mandatory_validate').each(function() {
				var name = jQuery(this).attr('name');

				var parameterName = jQuery(this).val();
				var getparameterid = jQuery(this).attr('data-getparameterid');
				if(name != undefined ){
					if (jQuery('[name="' + name + '"]:checked').length < 1) {
						returnfalsevalue = 1;
						jQuery('#errormsg_'+getparameterid).html('This field is required.');
						
					}
				}
			});
			var error = 0;
			jQuery('.errormsg').each(function() {
				if($(this).text() != ''){
					error++;
					returnfalsevalue = 1;
				}
			});
			if(error == 0){
				returnfalsevalue = '';
			}
			
			if(returnfalsevalue == 1){
				jQuery('.errormsg').each(function() {
					var get_html = jQuery(this).html();
					if(get_html != ''){
						jQuery('html, body').animate({
							scrollTop: jQuery(this).offset().top -100
						}, 150);
						
						return false;
					}
				});
			}else{
				jQuery('#imagepath').val('');
				jQuery("input[name='productype']").attr('data-labelval');
				jQuery('.curtain-loder').css('display','flex');
				jQuery('html, body').animate({ scrollTop: jQuery('#configurator-root').height() / 2 }, 'slow');
				setTimeout(function(){
						
					if (jQuery(".multiple-frame-list").is(':visible')) {
						var visible = true;
						jQuery('a.multiple-frame-overflow-click').click();
					}
					jQuery(".multiple-frame-overflow").hide();
					jQuery(".preview-desc.blinds").hide();
					
					var node = document.getElementById('curtainspreview');
					// get the div that will contain the canvas
					var canvas = document.createElement('canvas');
					canvas.width = node.scrollWidth;
					canvas.height = node.scrollHeight;
					
					domtoimage.toJpeg(node).then(function (pngDataUrl) {
						var img = new Image();
						img.onload = function () {
							var context = canvas.getContext('2d');
							context.drawImage(img, 0, 0);
						};
						img.src = pngDataUrl;
						jQuery('#imagepath').val(pngDataUrl);
						jQuery('.woocommerce-error').html('');
						var result = calculate_price(this);
						if(result && result != ''){
							jQuery('#mode').val("addtocart");
							jQuery(".js-add-cart").prop("disabled", true);
							global_blind_add_cart(jQuery("#submitform").serialize());
						}else{
							jQuery.dialog({
								title: 'Error!',
								columnClass: 'col-md-4 col-md-offset-4',
								content: 'There is no price for these measurements',
								type: 'red',
								typeAnimated: true,
								boxWidth: '30%',
								useBootstrap: false,
							});
							
							jQuery('.curtain-loder').css('display','none');
							
						}
						jQuery(".multiple-frame-overflow").show();
						jQuery(".preview-desc.blinds").show();

					});
				}, 500);
			}
		}
		var productid = '<?= $response->getproductdetails->productid;  ?>';
		var productname = '<?= $response->getproductdetails->productname;  ?>';
		var productcategory = '<?= $response->getproductdetails->productcategory; ?>';
		var fraction = '<?= $response->applicationsetup->fraction; ?>';
		var countryid = '<?= $response->organizationdetails->countryid; ?>';
		var noimage = '<?=  blindmatrix_get_plugin_url() . "/vendor/Shortcode-Source/image/blinds/no-image.jpg "?>'; 
		var unitVal = jQuery('input[name=unit]:checked').val();
		var page = 1; 
		if(fraction == 'on' && unitVal == 'inch'){
			jQuery("#width,#drope").css({"width":"70%","float":"left"});
			jQuery("#widthfraction,#dropfraction").css({"width":"30%"});
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
			jQuery('#unit_type').html(this.value);
			if (this.value == 'cm') {
				jQuery("#width,#drope").removeAttr("style");
				jQuery("#width,#drope").css({"width":"100%"});
				jQuery('#widthfraction').hide();
				jQuery('#dropfraction').hide();
			}
			else if (this.value == 'mm') {
				if(widthTmp > 0)  jQuery('#width').val(parseInt(widthTmp));
				if(dropeTmp > 0)  jQuery('#drope').val(parseInt(dropeTmp));
				jQuery("#width,#drope").removeAttr("style");
				jQuery("#width,#drope").css({"width":"100%"});
				jQuery('#widthfraction').hide();
				jQuery('#dropfraction').hide();
			}
			else if (this.value == 'inch') {
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
		
		$('input[name="getmaincategorylist"]').click(function() {
			var categoryvalue = $(this).val();
			var checkedVal;
			if($(this).is(":checked")){
				checkedVal = true;
			}else{
				checkedVal = false;
			}
			if($(this).attr('previousValue') == 'true'){
				checkedVal = false;
				$(this).prop("checked",false);
				$(this).parent( ".value" ).find(".blindslabel.selected").removeClass('selected');
				$( ".producttype_blind_container").css("display","block");
				var page = 1;
				producttype('',page);
			} else {
				$( ".producttype_blind_container").css("display","none");
				$('input[name="getmaincategorylist"]').attr('previousValue', false);
					var parametertypeidblind = [ ]; 
				$( ".producttype_blind label" ).each(function( index ) {
					var producttypecat = $(this).data('productcategoryid');
					
					if(categoryvalue == producttypecat){
						//$(this).css("display","none");
						parametertypeidblind.push($(this).data('parametertypeid'));
					}else{
							//$(this).css("display","inline-block");
					}
					
				});
				var page=1;
				producttype(parametertypeidblind,page);
			}
			$(this).attr('previousValue', checkedVal);
		
		});
		
		$(".fabric_scroll_blind_contianer .value").bind('scroll', function() {
		    var scrollend=0;
		    if(jQuery.page > 0) page = jQuery.page;
		    if(jQuery.scrollend > 0) scrollend = jQuery.scrollend;
		    //console.log('page--'+page);
			if(($(this).scrollTop() + $(this).innerHeight() >=  ($(this)[0].scrollHeight)*0.9 ) && scrollend == 0) {
				page++;
				var parameteridwil = $('input[name="productype"]:checked').val();
				var fabricnameVal = $('input[name="fabricnameck"]:checked').val();
				
				if(searchFabric != ''){
						var searchFabric = $('#searchFabric').val();
				}else{
						var searchFabric = '';
				}
				if($('input[name="getmaincategorylist').is(':checked')) { 
					var parameteridwil = []; 
					
					var maincategorylist = $('input[name="getmaincategorylist"]:checked').val();
					$( ".producttype_blind label" ).each(function( index ) {
						var parameteridck = $(this).data('productcategoryid');
						if(maincategorylist == parameteridck){
							parameteridwil.push($(this).data('parametertypeid'));
						}
					});
				}
				if($("#fablicfilterarray").val() != '' ){
					var fablicfilterarray = $("#fablicfilterarray").val();
					var catfilterarray = $("#catfilterarray").val();
				if(fabricnameVal == undefined){
						producttype(parameteridwil,page,'',fablicfilterarray,catfilterarray,searchFabric);
				}else{
						producttype(parameteridwil,page,'valtrue',fablicfilterarray,catfilterarray,searchFabric);
					}
				}else{
					if(fabricnameVal == undefined){
						producttype(parameteridwil,page,'','','',searchFabric);
					}else{
						producttype(parameteridwil,page,'valtrue','','',searchFabric);
					}
				}
				
			}
			
		});
	

		jQuery(document).on("change",'input[name=unit]' ,function(e) {
			//clearwidthdrop();
			var parameteridwil = $('#producttypeid').val();
			var vendorid = $('#vendorid').val();
			var fabricnameval = $('input[name="fabricnameck"]:checked').val();
			var unit = $(this).val();
			var product_type_value = $("#product_type_value").val();
			//if(product_type_value != 'venetians'){
					
					var minWidth = $("#minWidthfabric").val();
					var maxWidth = $("#maxWidthfabric").val();
					var minDrop = $("#minDropfabric").val();
					var maxDrop = $("#maxDropfabric").val();
					calcu_fabric_minmax_mesurement(minWidth,maxWidth,minDrop,maxDrop,unit,parameteridwil);
					
			// }else{
			// 	if(parameteridwil != '' && parameteridwil != undefined ){
			// 		get_maxmin_message(parameteridwil,vendorid,unit);
			// 	}
			// }
			widthdroperror();
		});
		function widthdroperror(){
			$('.widthdrope').each(function( index ) {
				var value =jQuery(this).val(); 
				var thisname = jQuery(this).attr('name');
				if(thisname == "width" || thisname == "drope" ){
					if(jQuery(this).attr('placeholder') != ""){
						var placeholder = jQuery(this).attr('placeholder');
						var thenum = placeholder.replace( /^\D+/g, '');
						if(placeholder.includes("~")){
							var minmax = placeholder.split(" ~ ");
							var minarr = minmax[0].split(" ");
							var maxarr = minmax[1].split(" ");
							var minvalue = Number(minarr[1]);
							var unit = minarr[2];
							var maxvalue = Number(maxarr[1]);
							var valueText = Number(jQuery(this).val());
							if(valueText < minvalue  || valueText > maxvalue){
								returnfalsevalue = 1;
								$("#errmsg_"+thisname).text('Min Value '+minvalue+' '+unit+' & Max Value '+ maxvalue +' '+unit);
								$("#errmsg_"+thisname).show();
							}else{
								returnfalsevalue = '';
								$("#errmsg_"+thisname).text('');
								$("#errmsg_"+thisname).hide();
							}
						}else{
							var max = placeholder.split(" ");
							var maxno = Number(max[1]);
							var valueText = Number(jQuery(this).val());
							var unit = max[2];
							if(valueText > maxno){
								returnfalsevalue = 1;
								$("#errmsg_"+thisname).text('Maximum Value '+maxno+' '+unit);
								$("#errmsg_"+thisname).show();
							}else{
								returnfalsevalue = '';
								$("#errmsg_"+thisname).text('');
								$("#errmsg_"+thisname).hide();
							}
						}
						if(value == "" ){
							$("#errmsg_"+thisname).hide();
						}
					}
				}
			});
		}
	
		function calcu_fabric_minmax_mesurement(minWidth,maxWidth,minDrop,maxDrop,unit_type,producttype){
			var minWidth = unitbasedcalculate(unit_type,minWidth);
			var maxWidth = unitbasedcalculate(unit_type,maxWidth);
			var minDrop = unitbasedcalculate(unit_type,minDrop);
			var maxDrop = unitbasedcalculate(unit_type,maxDrop);
		
			var wminmax = 0;
				if(minWidth > 0 && maxWidth > 0){
					jQuery('#width').attr('placeholder',"Min "+minWidth+" "+unit_type+" ~ Max "+maxWidth+" "+unit_type);
					wminmax = 1
				}
			var dminmax = 0;
				if(minDrop > 0 && maxDrop > 0){
					jQuery('#drope').attr('placeholder',"Min "+minDrop+" "+unit_type+" ~ Max "+maxDrop+" "+unit_type);
					dminmax = 1
				}
			var vendorid = $('#vendorid').val();
		
			if((wminmax == 0  ||  dminmax == 0 ) ){
				get_maxmin_message(producttype,vendorid,unit_type,wminmax,dminmax);
			}
		widthdroperror();
		}
		function clearwidthdrop(){
			jQuery("input[name=width]").val('');
			jQuery("input[name=drope]").val('');
			jQuery("#errmsg_width").text('');
			jQuery("#errmsg_drope").text('');
		}
		function get_maxmin_message(parameterid,vendorid,unit,wminmax,dminmax){
			jQuery.ajax({
					url     : ajaxurl,
					data    : {mode:'getpricetablemaxprice',action:'getpricetablemaxprice',productid:productid,producttypeid:parameterid,vendorid:vendorid,unit:unit,countryid:countryid},
					type    : "POST",
					dataType: 'JSON',
					async: false,
					success: function(response){
						if(wminmax == undefined && dminmax  == undefined){
							$("input[name=width]").attr("placeholder", response.widthmessage);
							$("input[name=drope]").attr("placeholder", response.dropmessage);
						}
						if(wminmax ==0 ){
							$("input[name=width]").attr("placeholder", response.widthmessage);
						}
						if(dminmax ==0 ){
							$("input[name=drope]").attr("placeholder", response.dropmessage);
						}
						widthdroperror();
					}
				});
			
		}
		function producttype(parameterid,page,valtrue,fablicfilterarray,catfilterarray,searchFabric){
		    jQuery.page = page;
			var mandatory = $(".producttype_blind").attr("data-mandatory");
			var productno = $("#product_code").val();
			var ecommerce_sample = $("#ecommerce_sample").val();
			var num_of_rows =15;
			var colorid = $("#colorid").val();
			var fabricid = $("#fabricid").val();
			if(fabricid == ''){
				if(valtrue == undefined || valtrue == ''){
				if(productcategory == 'Create no sub sub parameter'){
					if(page == 1){
						$(".color_blind_contianer .value").html('');
							//$(".color_blind_contianer").hide();
					}
				}else{
					if(page == 1){
						$(".color_blind_contianer").html('');
							//$(".fabric_blind_contianer .value").html('');
							//$(".fabric_blind_contianer").hide();
						}
					}
				}
			}
			if(fablicfilterarray == undefined){
				fablicfilterarray ='';
			}
			if(catfilterarray == undefined){
				catfilterarray ='';
			}	
			if(searchFabric == undefined){
				if($("#searchFabric").val() != ''){
					var searchFabric = $("#searchFabric").val();
				}else{
					var searchFabric = '';
				}
			}
			jQuery.ajax(
			{
				url     : ajaxurl,
				data    : {mode:'getFabricList',action:'getFabricList',searchFabric:searchFabric,catfilterarray:catfilterarray,fablicfilterarray:fablicfilterarray,ecommerce_sample:ecommerce_sample,productno:productno,mandatory:mandatory,productid:productid,productname:productname,productcategory:productcategory,parameterid:parameterid,page:page,num_of_rows:num_of_rows},
				type    : "POST",
				dataType: 'JSON',
				async: false,
				success: function(response){
					if(response != ''){
						
							if(response.html_empty == 1){
							    if(productcategory == 'Create no sub sub parameter'){
									if(response.page ==  1 ){
										$('.color_blind_contianer .value').html(response.html);
									}
								}else{
									if(response.page ==  1 ){
										$('.fabric_blind_contianer .value').html(response.html);
										$(".color_blind_contianer").html('');
									}
								}	
								jQuery.scrollend=1;
							}else{
							    jQuery.scrollend=0;
								if(productcategory == 'Create no sub sub parameter'){
									var fabricid = $("#fabricid").val();
									if(fabricid != ''){
										if(response.page ==  1 ){
											$('.color_blind_contianer .value').html(response.html);
											$('#' + fabricid).addClass("selected")
											$('#productype' + fabricid).trigger('click');
										}else{
										    $('.color_blind_contianer .value').append(response.html);
											$('#' + fabricid).addClass("selected")
											$('#productype' + fabricid).trigger('click');
										}
									}else{
										if(response.page ==  1 ){
											$('.color_blind_contianer .value').html(response.html);
										}else{
											$('.color_blind_contianer .value').append(response.html);
										}
									}
									//$('.color_blind_contianer').show();
								
							    }else{

									var fabricid = $("#fabricid").val();
									if(fabricid != ''){
										var colorid = $("#colorid").val();
										
										if(response.page ==  1 ){
											$('.fabric_blind_contianer .value').html(response.html);
											$('#' + fabricid).addClass("selected")
											$('#productype' + fabricid).trigger('click');
										}else{
											$('.fabric_blind_contianer .value').append(response.html);
											$('#' + fabricid).addClass("selected")
											$('#productype' + fabricid).trigger('click');
										}
										
									}else{
										if(valtrue == undefined || valtrue == '' ){
										    $(".color_blind_contianer").html('');
    									}
    									if(response.page ==  1 ){
    										$('.fabric_blind_contianer .value').html(response.html);
    									}else{
    										$('.fabric_blind_contianer .value').append(response.html);
    									}
    									$('.fabric_blind_contianer').show();
    								}
								}
							}
					}else{
					    jQuery.scrollend=0;
						if(productcategory == 'Create no sub sub parameter'){
							$('.color_blind_contianer').hide();
						}else{
							$('.fabric_blind_contianer').hide();
							$('.color_blind_contianer').hide();
						}
						 
					}
				}
			});
		}
		
		function configuratorfabricitem(){
			var producttypeid = $('#producttypeid').val();
			var productname = $('#productname').val();
			var vendorname = $('#vendorname').val();
			var fabricsupplier = $('#fabricsupplier').val();
			var fabricsupplierid = $('#fabricsupplierid').val();
			var parameterTypeId = $('#producttypeid').val();
			var producttype = $('#productTypeSubName').val();
			var fabricname = $('#fabricparametervalue').val();
			var vendorid = $('#vendorid').val();
			var mandatory = '';
			var value = $('#fabricid').val();
			var productno = $("#product_code").val();
			var ecommerce_sample = $("#ecommerce_sample").val();
			jQuery.ajax(
			{
				url     : ajaxurl,
				data    : {mode:'getColorDetails',action:'getColorDetails',ecommerce_sample:ecommerce_sample,vendorid:vendorid,productno:productno,parametertypeid:parameterTypeId,mandatory:mandatory,fabricid:value,productname:productname,vendorname:vendorname,fabricsupplier:fabricsupplier,producttype:producttype,fabricname:fabricname,fabricsupplierid:fabricsupplierid},
				type    : "POST",
				dataType: 'JSON',
				async: false,
				success: function(response){
					if(response != ''){
						$('.color_blind_contianer').html(response);
						//$('.color_blind_contianer').show();
						var colorid = $("#colorid").val();
						if(colorid != ''){
							$('#' + colorid).addClass("selected")
							jQuery('#productype' + colorid).trigger('click');
						}
					}
				}
			});
		}
		$('a.multiple-frame-overflow-click').click(function(event) {
			event.preventDefault();
			$(".configurator.blinds .upward").hide();
			$(".configurator.blinds .downward").hide();
			$(".multiple-frame-list").slideToggle('400', function() {
				if ($(this).is(':visible')) {
						$(".configurator.blinds .downward").show();
						$(".multiple-frame-overflow").css('bottom','85px');
						jQuery(".slider").flickity("resize");
				} else {
					$(".configurator.blinds .upward").show();
					$(".multiple-frame-overflow").css('bottom','0px');
				}
			});
		});
		$(document).on('click','.frame_container.desktop a.multiple-frame-list-button',function(event) {
			event.preventDefault();
			jQuery("body").find(".multiple-frame-list-button.selected").removeClass('selected');
			var datablinds = $(this).data('blinds');
			$('.multiple-frame-list-button').each(function(){
				if ($(this).data('blinds') == datablinds) {
					jQuery(this).addClass('selected');
					$('#blinds_image_key').val(datablinds);
				}
			});
				var imgSc = $(this).find("img");
			$('.configurator-main-headertype').attr('src',imgSc.attr('src'));
			$('.configurator-main-headertype').attr('bigsrc',imgSc.attr('src'));
		});
		$('.frame_container.mobile .product_list_frame_con').click(function(event) {
			event.preventDefault();
			jQuery("body").find(".product_list_frame_con.selected").removeClass('selected');
			var datablinds = $(this).data('blinds');
			$('.product_list_frame_con').each(function(){
				if ($(this).data('blinds') == datablinds) {
					jQuery(this).addClass('selected');
				}
			});
			var imgSc = $(this).find("img");
			$('.configurator-main-headertype').attr('src',imgSc.attr('src'));
		});
		var showfield = [];
	  
		$(document).on('click', '.blindslabel', function() {
			showDetails(this);
		});
		
		
		$(document).on('change', 'select.showorderdetails', function() {
			
			showDetailsdropCom(this);
		});
		function showDetailsdropCom(thisval){
			var parameterId =$(thisval).data('getparameterid');
				var id_val = $(thisval).attr('id');
				var thisname = $(thisval).attr('name');
				var value = $('select[name="'+thisname+'"] option:selected').toArray().map(item => item.text).join(", ");
				
				var label = $(thisval).parents(".showdetailscontainer").find(".label label").attr("data-label");
				var sub;
				var title;
				var sub_parent;
				if($(thisval).hasClass('compontentlist') || $(thisval).hasClass('componentsub')){
					var parameterId =$('#'+id_val+" option:selected" ).data('priceid');
					if( $(thisval).hasClass('componentsub')){
						sub ='sub';
						title =$('#'+id_val+" option:selected" ).attr("title");
						sub_parent = $(thisval).data("parent_id");
					}
					if($(thisval).val() != '' ){
						
						if (typeof showfieldCom !== 'undefined' && showfieldCom.length > 0) {
							var check = true;
							$.each( showfieldCom, function( i, l ){
								if(l.name == thisname ){
									showfieldCom[i] ={value:value, label:l.label, name:l.name,parameterId:parameterId,sub:sub,title:title,sub_parent:sub_parent};
									check = false;
								   return false;
								}
								
							});
							if(check == true){
								showfieldCom.push({value:value, label:label, name:thisname,parameterId:parameterId,sub:sub,title:title,sub_parent:sub_parent});
							}
						}else{
							showfieldCom.push({value:value, label:label, name:thisname,parameterId:parameterId,sub:sub,title:title,sub_parent:sub_parent});
						}
					}else{
						var parameterId = $(thisval).attr('data-getparameterid'),
							$_name = $(thisval).attr('name'); 
						if (typeof showfieldCom !== 'undefined' && showfieldCom.length > 0 && typeof parameterId !== 'undefined'  ) {
							var check = true;
							var inxComtypevla = [];
							jQuery.each( showfieldCom, function( i, l ){
								if(l.parameterId == parameterId ){	
									inxComtypevla.push(i);
								}
								if($_name == l.name){
									inxComtypevla.push(i);
								}
								if(parameterId == l.sub_parent){
									inxComtypevla.push(i);
								}
							});
							for (var i = inxComtypevla.length -1; i >= 0; i--){
									showfieldCom.splice(inxComtypevla[i],1);
							}
						}
					
					}
					console.log(showfieldCom);
				}else{
				
					if($(thisval).val() != '' ){
						if (typeof showfield !== 'undefined' && showfield.length > 0) {
							var check = true;
							$.each( showfield, function( i, l ){
								if(l.name == thisname ){
									showfield[i] ={value:value, label:l.label, name:l.name,parameterId:l.parameterId};
									check = false;
								   return false;
								}
								
							}); 
							if(check == true){
								showfield.push({value:value, label:label, name:thisname,parameterId:parameterId});
							}
						}else{
							showfield.push({value:value, label:label, name:thisname,parameterId:parameterId});
						}
						
					}else{
						var inxtextdrop = [];
						jQuery.each( showfield, function( i, l ){
							if(thisname ==  l.name ){	
								inxtextdrop.push(i);
							}
						});
						for (var i = inxtextdrop.length -1; i >= 0; i--){
								showfield.splice(inxtextdrop[i],1);
						}
						
					}
				}
			var html = '';
			var htmlcompontentlistradio = '';
			if (typeof showfield !== 'undefined' && showfield.length > 0) {
			
				$.each( showfield, function( i, l ){
						html += '<tr class="paramlablecomponentsub_'+l.parameterId+' paramlable '+l.name+'_shw "><td>'+l.label+':</td><td><strong class="paramval">'+l.value+'</strong></td></tr>';
				
				});
			}
			if (typeof showfieldCom !== 'undefined' && showfieldCom.length > 0) {
				$.each( showfieldCom, function( i, l ){
					if(l.subcom == true || l.sub == 'sub'){
						htmlcompontentlistradio += '<tr class="'+ l.title +' paramlable paramlablecomponentsub_'+l.parameterId+'"><td>'+l.label+':</td><td><strong class="paramval">'+l.value+'</strong></td></tr>';
						}else{
						htmlcompontentlistradio += '<tr class="paramlable  paramlablecomponentmain_'+l.parameterId+'"><td>'+l.label+':</td><td><strong class="paramval">'+l.value+'</strong></td></tr>';
					}
					
				});
			}
			jQuery('#allparametervalue tbody.components').html(htmlcompontentlistradio);
			jQuery('#allparametervalue tbody.parameters').html(html);
		}
		function showDetails(thisval){
			if($(thisval).hasClass( "radio" )){
				$(thisval).parent( ".value" ).find(".blindslabel.selected").removeClass('selected');
				$(thisval).addClass('selected');
			}
			if($(thisval).hasClass('maincategory')){
				jQuery(".clear_curtain_all_filter").trigger('click');
			}
			if($(thisval).hasClass('maincategory') ||$(thisval).hasClass('checkbox')){
				 return;
			}
			var blindslabel  = $(thisval);
			var thisfor =$(thisval).attr('for');
			var parameterId =$(thisval).data('parameterid');
			var thisid = $(thisval).parent(".value").find("input[id='"+thisfor+"']");
			var thisname = thisid.attr('name');
			var value = $(thisval).text();
			if(thisname == 'fabricnameck'){
				value = $(thisval).find(".fabricname").text();
			}
			if(thisname == 'colornamesub'){
				value = $(thisval).find(".colorname_showdetails").text();
			}
			if(thisname == 'productype'){
				$("#colorid").val('');
				$("#fabricid").val('');
				jQuery("#producttypeid").val(thisid.val());
				jQuery(".clear_curtain_all_filter").trigger('click');
			}
			
			var label = $(thisval).parents(".showdetailscontainer").find(".label label").attr("for");
		
			var sub;
			if($(thisval).hasClass('compontentlist') || $(thisval).hasClass('componentsub')){
				if( $(thisval).hasClass('componentsub')){
					sub ='sub';
				}
				if (typeof showfieldCom !== 'undefined' && showfieldCom.length > 0) {
					var check = true;
					$.each( showfieldCom, function( i, l ){
						if(l.name == thisname ){
							showfieldCom[i] ={value:value, label:l.label, name:l.name,parameterId:l.parameterId,sub:sub};
							check = false;
						   return false;
						}
						
					});
					if(check == true){
						showfieldCom.push({value:value, label:label, name:thisname,parameterId:parameterId,sub:sub});
					}
				}else{
					showfieldCom.push({value:value, label:label, name:thisname,parameterId:parameterId,sub:sub});
				}
			}else{
				
				if (typeof showfield !== 'undefined' && showfield.length > 0) {
					var check = true;
					$.each( showfield, function( i, l ){
						if(l.name == thisname ){
							showfield[i] ={value:value, label:l.label, name:l.name,parameterId:l.parameterId};
							check = false;
						   return false;
						}
						
					});
					if(check == true){
						showfield.push({value:value, label:label, name:thisname,parameterId:parameterId});
					}
				}else{
					showfield.push({value:value, label:label, name:thisname,parameterId:parameterId});
				}
			}
			var html = '';
			var htmlcompontentlistradio = '';
			if (typeof showfield !== 'undefined' && showfield.length > 0) {
				
				$.each( showfield, function( i, l ){
						html += '<tr class="paramlablecomponentsub_'+l.parameterId+' paramlable '+l.name+'_shw "><td>'+l.label+':</td><td><strong class="paramval">'+l.value+'</strong></td></tr>';
				
				});
			}
			if (typeof showfieldCom !== 'undefined' && showfieldCom.length > 0) {
				$.each( showfieldCom, function( i, l ){
					if(l.subcom == true || l.sub == 'sub'){
						htmlcompontentlistradio += '<tr class="paramlable paramlablecomponentsub_'+l.parameterId+'"><td>'+l.label+':</td><td><strong class="paramval">'+l.value+'</strong></td></tr>';
						}else{
						htmlcompontentlistradio += '<tr class="paramlable  paramlablecomponentmain_'+l.parameterId+'"><td>'+l.label+':</td><td><strong class="paramval">'+l.value+'</strong></td></tr>';
					}
					
				});
			}
			jQuery('#allparametervalue tbody.components').html(htmlcompontentlistradio);
			jQuery('#allparametervalue tbody.parameters').html(html);
		}
/* 		$(".Componentvalue select").click(function() {
			var parameterId = $(this).data('parameterid');
			jQuery('.paramlablecomponentsub_'+parameterId).remove();
			if (typeof showfieldCom !== 'undefined' && showfieldCom.length > 0 && typeof parameterId !== 'undefined'  ) {
				var check = true;
				var inxComtypevla = [];
				jQuery.each( showfieldCom, function( i, l ){
					if(l.parameterId == parameterId &&  l.subcom == true){	
						inxComtypevla.push(i);
					}
				});
				for (var i = inxComtypevla.length -1; i >= 0; i--){
						showfieldCom.splice(inxComtypevla[i],1);
				}
			}
		}); */
		showfieldtext =[];

jQuery(document).on("click",'.dropdownContianer input,.Componentvalue input' ,function(e) {
	
	var thisname = jQuery(this).attr('name'); 
	if (jQuery('[name="' + thisname + '"]:checked')) {
		var getparameterid = jQuery(this).attr('data-getparameterid');
		jQuery('#errormsg_'+getparameterid).html('');
	}
});
	jQuery(document).on("keyup change",'select[name=dropfraction],select[name=ProductsParametervalue], select[name=widthfraction], input[name=unit], .showdetailscontainer input[type=text], .showdetailscontainer input[type=number]' ,function(e) {
		console.log('chrrer');
		if(jQuery(this).attr('type') != 'radio' && jQuery(this).hasClass( "showorderdetails" )){
			
			var value =jQuery(this).val(); 
			var thisname = jQuery(this).attr('name');
			
			if(thisname == "width" || thisname == "drope" ){
				if(jQuery(this).attr('placeholder') != ""){
					var placeholder = jQuery(this).attr('placeholder');
					var thenum = placeholder.replace( /^\D+/g, '');
					if(placeholder.includes("~")){
						var minmax = placeholder.split(" ~ ");
						var minarr = minmax[0].split(" ");
						var maxarr = minmax[1].split(" ");
						var minvalue = Number(minarr[1]);
						var unit = minarr[2];
						var maxvalue = Number(maxarr[1]);
						var valueText = Number(jQuery(this).val());
						if(valueText < minvalue  || valueText > maxvalue){
							returnfalsevalue == 1;
							$("#errmsg_"+thisname).text('Min Value '+minvalue+' '+unit+' && Max Value '+ maxvalue +' '+unit);
							$("#errmsg_"+thisname).show();
						}else{
							returnfalsevalue == '';
							$("#errmsg_"+thisname).text('');
							$("#errmsg_"+thisname).hide();
						}
					}else{
						var max = placeholder.split(" ");
						var maxno = Number(max[1]);
						var valueText = Number(jQuery(this).val());
						var unit = max[2];
						if(valueText > maxno){
							returnfalsevalue == 1;
							$("#errmsg_"+thisname).text('Maximum Value '+maxno+' '+unit);
							$("#errmsg_"+thisname).show();
						}else{
							returnfalsevalue == 0;
							$("#errmsg_"+thisname).text('');
							$("#errmsg_"+thisname).hide();
						}
					}
					if(value == "" ){
						$("#errmsg_"+thisname).hide();
					}
				}
			}
			var parameterlistid =jQuery(this).parents(".showdetailscontainer").data('parameterlistid');
			var label =jQuery(this).parents(".showdetailscontainer").find(".label label").attr("for");
			if(value != ''){
				var getparameterid = jQuery(this).attr('data-getparameterid');
				jQuery('#errormsg_'+getparameterid).html('');
				if (typeof showfieldtext !== 'undefined' && showfieldtext.length > 0) {
					var check = true;
					jQuery.each( showfieldtext, function( i, l ){
						if(l.name == thisname ){
							showfieldtext[i] ={value:value, label:l.label, name:l.name,subcom:true,parameterlistid:parameterlistid};
							check = false;
						   return false;
						} 
						
					});
					if(check == true){
						showfieldtext.push({value:value, label:label, name:thisname,subcom:true,parameterlistid:parameterlistid});
					}
				}else{
					showfieldtext.push({value:value, label:label, name:thisname,subcom:true,parameterlistid:parameterlistid});
				}
			}else{
				var inxtext = [];
				jQuery.each( showfieldtext, function( i, l ){
					if(thisname ==  l.name ){	
						inxtext.push(i);
					}
				});
				for (var i = inxtext.length -1; i >= 0; i--){
						showfieldtext.splice(inxtext[i],1);
				}
				
			}
		}else if(jQuery(this).hasClass( "serach_input_color" )){
		
			  var input = document.getElementById("serach_input_color");
			  var filter = input.value.toLowerCase();
			  var nodes = document.getElementsByClassName('blindslabelcolor');
			  var countnode = jQuery('.blindslabelcolor ').length;


		     var total_nodes = 0;
			  for (i = 0; i < nodes.length; i++) {
				 var main = nodes[i].getAttribute('data-text').toLowerCase();
				if (main.includes(filter)) {
				  nodes[i].style.display = "inline-block";
				   $(".no_products_div").hide();
				} else {
					total_nodes++;
					nodes[i].style.display = "none";
				}
			  }
			  if(total_nodes == countnode){
				$(".no_products_div").show();
			  }
		}
			var htmltxt = '';
			if (typeof showfieldtext !== 'undefined' && showfieldtext.length > 0) {
				jQuery.each( showfieldtext, function( i, l ){

						if(l.parameterlistid == 4 || l.parameterlistid == 5){
							var valuemessure = l.value;
							var unit_messure = jQuery('input[name=unit]:checked').val();
							
							if(unit_messure == 'inch'){
								var fractionVal= '';
								if(l.parameterlistid == 4){
									var fractionVal = jQuery("select[name=widthfraction] option:selected" ).text();
								}else{
									var fractionVal =  jQuery("select[name=dropfraction] option:selected" ).text();
								}
								if(fractionVal == 0){
									fractionVal ='';
								}
								if(valuemessure > 0){ 
									htmltxt += '<tr class="paramlable"><td>'+l.label+':</td><td><strong class="paramval">'+valuemessure+' '+fractionVal+' '+unit_messure+'</strong></td></tr>';
								}
							}else{
								if(valuemessure > 0){ 
									htmltxt += '<tr class="paramlable"><td>'+l.label+':</td><td><strong class="paramval">'+valuemessure+' '+unit_messure+'</strong></td></tr>';
								}
							}
						}else{
							htmltxt += '<tr class="paramlable"><td>'+l.label+':</td><td><strong class="paramval">'+l.value+'</strong></td></tr>';
						}
					
				});
			}
			
			jQuery('#allparametervalue tbody.parameterstext').html(htmltxt);
		});
	});
		var cart_key = "<?php echo $cart_item_key ?>";
		if('' != cart_key){
			jQuery('.compontentlist').change();
		}
		var showfieldCom = [];
		function getComponentSubList(dropdown,parameterId){
			$arr = {};
			jQuery('.componentsub').each(function(){
				jQuery(this).find(':selected').each(function(){
					var $subcomponent_id = jQuery(this).attr('title');
					$arr[$subcomponent_id] = jQuery(this).val();
				})
			});
			
			var maincomponent = [];
			var stockcomponentid = [];
			jQuery.each(jQuery(".maincomponent_"+parameterId+" option:selected"), function(){            
				maincomponent.push(jQuery(this).attr('data-sub'));
				stockcomponentid.push(jQuery(this).attr('data-stock-com-id'));
			});
			jQuery("#stockcomponentid").val(stockcomponentid);
		/* 	var valuesCurrent = [];
			jQuery('.componentsub_'+parameterId).each(function(){
				valuesCurrent[parameterId] =  jQuery(this).find('select').val();
			}); */
// 			console.log(valuesCurrent);
			jQuery('.componentsub_'+parameterId).remove();
			jQuery('.componentsub_end').remove();
			if(maincomponent && maincomponent.length > 0){
				jQuery.ajax(
				{
					url     : ajaxurl,
					data    : {mode:'getblindscomponentsublist',action:'getblindscomponentsublist',cart_item_key:cart_key,maincomponent:maincomponent,parameterId:parameterId,selected_args:$arr},
					type    : "POST",
					dataType: 'JSON',
					async: false,
					success: function(response){
						//console.log(response.result);
						if(response.result != ''){
							jQuery('#'+parameterId).after(response.ComponentSubList);
							console.log(response.component_id);
							jQuery("select.demo").each(function(){
								var $eventdemo = $(this);
								$eventdemo.select2();
								$eventdemo.on("select2:unselect", function (e) {
									if(jQuery(this).val().length == 0){
										var id_ck = e.params.data.title;
										var inxComrwesub = [];
										jQuery.each( showfieldCom, function( i, l ){
											if(id_ck == l.title ){
												inxComrwesub.push(i);
											}
										});
										for (var i = inxComrwesub.length -1; i >= 0; i--){
												showfieldCom.splice(inxComrwesub[i],1);
										}
										jQuery('.paramlable'+'.'+id_ck).remove();
									}
									
								});
								$eventdemo.on('select2:opening select2:closing', function( event ) {
									var $searchfielddemo = jQuery(this).parent().find('.select2-search__field');
									$searchfielddemo.prop('disabled', true);
								});
							});	
							jQuery("select.subdemo").each(function(){
								var $eventsubdemo = jQuery(this);
								$eventsubdemo.select2({
									placeholder: $eventsubdemo.prop('multiple') ? "Choose the options":false,
									minimumResultsForSearch: -1
								});
							});	
							jQuery(".select2-search__field").prop("readonly", true);
						}
						
					}
				});
			}
		}
		
	function unitbasedcalculate(unit,value){

		var value = parseFloat(value); 
		 
		if(unit == 'cm'){
			var result = (value / 10);
		}else if(unit == 'inch'){
			var n = value / 25.4;
			var result = round_up(n,2);
		}else{
			var result = value;
		}
		return result;
	}
	function round_up ( value, precision ) { 
		var pow = Math.pow(10,precision);
		return ( Math.ceil ( pow * value ) + Math.ceil ( pow * value - Math.ceil ( pow * value ) ) ) / pow; 
	}
	function checkNumeric(event,thisval) 
	{
		
		var unitVal = jQuery('input[name=unit]:checked').val();
		var fraction = jQuery('#fraction').val();
		
		var key = event.charCode || event.keyCode || 0;

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
	function showorderdetails(){
	}

	function filterdiselect(id){
		jQuery('#'+id).next('label').trigger('click');
		
	}
	function clear_curtain_all_filter(){
		jQuery( ".blinds_filter_cat.selected" ).each(function( index ) {
			jQuery(this).trigger('click');
		});
	}

	function calculate_price(thisval=''){
		var unit = jQuery('input[name="unit"]:checked').val();
	
		jQuery('#mode').val("getprice");
		
		var getfcname = jQuery(thisval).attr('name');
		//console.log('getfcname--'+getfcname);
		
		if(product_category == 'Create no sub sub parameter'){
			var getnamerad = jQuery('input[name="fabricnameck"]:checked').attr('name');
			var fabric_name = jQuery('input[name="fabricnameck"]:checked').attr('data-labelval');
			var fabric_id =  jQuery('input[name="fabricnameck"]:checked').val();
			jQuery("#fabricid").val(fabric_id);
			jQuery("#fabricparametervalue").val(fabric_name);
		    if(fabric_name != undefined && fabric_name != '' ){
				jQuery(".fabricname_showbox_value").text(fabric_name);
				jQuery(".fabricname_showbox").css("display","inline-block");
			}else{
				jQuery(".fabricname_showbox_value").text("");
				jQuery(".fabricname_showbox").hide();
			}
		}else{
		    var getnamerad = jQuery('input[name="colornamesub"]:checked').attr('name');
		    var fabric_name =  jQuery('#fabricparametervalue').val();
		    var color_name = jQuery('input[name="colornamesub"]:checked').attr('data-labelval');
			var color_id = jQuery('input[name="colornamesub"]:checked').val();
			

			jQuery("#colorid").val(color_id);
			jQuery("#colorparametervalue").val(color_name);
			
			if(color_name != undefined && color_name != ""){
				jQuery(".colorname_showbox_value").text(color_name);
				jQuery(".colorname_showbox").css("display","inline-block");
			}else{
				jQuery(".colorname_showbox_value").text("");
				jQuery(".colorname_showbox").hide();
			}
		}
		
		var colorname = '';
		if(color_name != undefined && color_name != ""){
			colorname = fabric_name+' '+color_name;
		}else{
			colorname = fabric_name;
		}

		jQuery("#colorname").val(colorname);
		jQuery(".setcolorname").html(colorname);
		
		var result_val =false;
		returnfalsevalue = '';
		jQuery('.mandatoryvalidate').each(function(i){
			var getparameterid = jQuery(this).attr('data-getparameterid');
			if(this.value == ''){
				returnfalsevalue = 1;
			}
		});
		
		jQuery('.mandatory_validate').each(function() {
			var name = jQuery(this).attr('name');
			var parameterName = jQuery(this).val();
			var getparameterid = jQuery(this).attr('data-getparameterid');
			if(name != undefined ){
				if (jQuery('[name="' + name + '"]:checked').length < 1) {
					returnfalsevalue = 1;
				}
			}
		});
		
		if(getfcname == 'colornamesub' || getfcname == 'fabricnameck'){
			
	        return false;
		}

		if(returnfalsevalue == ''){
		    jQuery.ajax({
				url     : ajaxurl,
				data    : jQuery("#submitform").serialize(),
				type    : "POST",
				async: false,
				dataType: 'JSON',
				success: function(response){
					if(response.costpricecomesfrom == '1'){
						var getselparameteridval = {};
						getselparameteridval = getselparameteridval_list(unit,getselparameteridval,response.currency_name);
						var getstockformularesult={};
						getstockformularesult = stockformulacalculation(unit,getselparameteridval,response);
						
						if(getstockformularesult.getnetprice > 0){
							jQuery("#stockformulavalues").val('');
							jQuery("#stockformulavalues").val(JSON.stringify(getstockformularesult.stockformulaobj));

							jQuery("#componentformulavalues").val('');
							jQuery("#componentformulavalues").val(JSON.stringify(getstockformularesult.componentformulaobj));
							jQuery('.product-price').show();
                            				var vatoption = jQuery('#vatoption').val();
							if(vatoption == 2){
								jQuery('.showprice').text(getstockformularesult.showprice+' (Excl. of VAT)');
							}else{
								jQuery('.showprice').text(getstockformularesult.showprice+' (Incl. of VAT)');
							}
							jQuery('#single_product_price').val(getstockformularesult.priceval);
							jQuery('#single_product_netprice').val(getstockformularesult.getnetprice);
							jQuery('#single_product_itemcost').val(getstockformularesult.getitemcost);
							jQuery('#single_product_orgvat').val(response.orgvat);
							jQuery('#single_product_vatvalue').val(getstockformularesult.getvat);
							jQuery('#single_product_grossprice').val(response.grossprice);
							jQuery('#vaterate').val(response.vaterate);
							jQuery('.showvat').text(getstockformularesult.priceval);
							jQuery('.price_container').show();
							if(returnfalsevalue == ''){
								jQuery(".js-add-cart").show();
							}
							result_val = true;
						}else{
							jQuery('.product-price').hide();
							jQuery('.price_container').hide();
							jQuery('#single_product_price').val('');
							jQuery('#single_product_netprice').val('');
							jQuery('#single_product_itemcost').val('');
							jQuery('#single_product_orgvat').val('');
							jQuery('#single_product_vatvalue').val('');
							jQuery('#single_product_grossprice').val('');
							jQuery('#vaterate').val('');
						}
				
					}else{
						if(response.success == true && response.priceval > 0){
							jQuery('.product-price').show();
							var vatoption = jQuery('#vatoption').val();
							if(vatoption == 2){
								jQuery('.showprice').text(response.showprice+' (Excl. of VAT)');
							}else{
								jQuery('.showprice').text(response.showprice+' (Incl. of VAT)');
							}
							var extra_offer = parseInt(jQuery('#extra_offer').val());
							jQuery('.total_price_wrapper').hide();
							if(extra_offer){
								$percent = 100 - extra_offer;
								var price_val = response.showprice;
								var $total_price = (parseFloat(price_val.replace(",",""))/$percent)*100;
								jQuery('.total_price_val').text($total_price.toFixed(2));
								jQuery('.extra_offer_val').text(' ( '+extra_offer+'% off)');
								jQuery('.total_price_wrapper').show();
							}
							
							jQuery('#single_product_price').val(response.priceval);
							jQuery('#single_product_netprice').val(response.netprice);
							jQuery('#single_product_itemcost').val(response.itemcost);
							jQuery('#single_product_orgvat').val(response.orgvat);
							jQuery('#single_product_vatvalue').val(response.vatvalue);
							jQuery('#single_product_grossprice').val(response.grossprice);
							jQuery('#vaterate').val(response.vaterate);
							jQuery('.showvat').text(response.priceval);
							jQuery('.price_container').show();
							if(returnfalsevalue == ''){
								jQuery(".js-add-cart").show();
							}
							result_val = true;
						}else{
							jQuery('.product-price').hide();
							jQuery('.price_container').hide();
							jQuery('#single_product_price').val('');
							jQuery('#single_product_netprice').val('');
							jQuery('#single_product_itemcost').val('');
							jQuery('#single_product_orgvat').val('');
							jQuery('#single_product_vatvalue').val('');
							jQuery('#single_product_grossprice').val('');
							jQuery('#vaterate').val('');
						}
					}
				}
			});
		}
		return result_val;
	}	
	jQuery('.quantity').on('click', '.bm-plus', function(e) {
			$input = jQuery(this).prev('input.qty');
			var val = parseInt($input.val());
			var step = $input.attr('step');
			step = 'undefined' !== typeof(step) ? parseInt(step) : 1;
			$input.val( val + step ).change();
		});

		jQuery('.quantity').on('click', '.bm-minus', 
			function(e) {
			$input = jQuery(this).next('input.qty');
			var val = parseInt($input.val());
			var step = $input.attr('step');
			step = 'undefined' !== typeof(step) ? parseInt(step) : 1;
			if (val > 1) {
				$input.val( val - step ).change();
			} 
		});	
		jQuery(document).on('click', '.showorderdetails.color_blind.blindsradio', function(){
            var sw_colorid = jQuery(this).val();
			var sw_product_code  = jQuery('#product_code').val();
			var sw_producttypeid  = jQuery('#producttypeid').val();
			var sw_fabricid  = jQuery('#fabricid').val();
			var sw_vendorid  = jQuery('#vendorid').val();
            var sw_producturl = jQuery("input[name=edit_product_url]").val();
            var  sw_productname = jQuery('#productname').val();
			jQuery.ajax({
				url : ajaxurl,
				data: {action:'material_image_action',productcode:sw_product_code,producttypeid:sw_producttypeid,fabricid:sw_fabricid, colorid:sw_colorid, vendorid:sw_vendorid , producturl:sw_producturl,productname:sw_productname},
				type :"POST",
				dataType:'JSON',
				async: false,
				success: function(response){
					if(response.key != ''){
						jQuery(".frame_container").html(response.key);
						jQuery('.frame_container.desktop:first a.multiple-frame-list-button:first').click();
					}
				}
			});
		});
	</script>
	<style>
	input+label {
		cursor: pointer;
	}
	#coverspin {
		position:absolute;
		width:100%;
		left:0;right:0;top:0;bottom:0;
		background-color: rgb(121 148 157 / 35%);
		z-index:9999;
		display:none;
	}
	#coverspin::after {
		content:'';
		display:block;
		position:absolute;
		left:48%;top:40%;
		width:40px;height:40px;
		border-style:solid;
		border-color:black;
		border-top-color:transparent;
		border-width: 4px;
		border-radius:50%;
		-webkit-animation: spin .8s linear infinite;
		animation: spin .8s linear infinite;
	}
	#cover-spin {
        position:absolute;
        width:100%;
        left:0;right:0;top:0;bottom:0;
        background-color: rgba(255,255,255,0.7);
        z-index:9999;
        display:none;
    }
    #cover-spin::after {
        content:'';
        display:block;
        position:absolute;
        left:48%;top:40%;
        width:40px;height:40px;
        border-style:solid;
        border-color:black;
        border-top-color:transparent;
        border-width: 4px;
        border-radius:50%;
        -webkit-animation: spin .8s linear infinite;
        animation: spin .8s linear infinite;
    }
    .configurator.bordered:not(.vmax):not(.eyelet) .configurator-main-fabric, .configurator.bordered:not(.vmax):not(.eyelet) .configurator-border-holder {
        height :100% !important;
    }
    body .configurator:not(.vmax) .configurator-preview-image {
        padding-bottom: 100% !important;
    }
   
	body .select2-container .selection .select2-selection--multiple{
		max-height: unset!important;
	}
	body .configurator.blinds.bordered:not(.vmax):not(.eyelet) .configurator-main-fabric {
	    width: 41.9%;
		height: 41.9%!important;
		left: 34%;
		background-size: contain;
		top: 26%;
		background-repeat: no-repeat;
	}
	main#main {
		padding: 0;
	}
	.select2-container .select2-search--inline .select2-search__field{
		height:30px;		
		font-size:90%;
		font-family:'Lato';
	}	
	body,
	div#page {
   	 overflow-x: visible !important;
	}
	</style>
<?php
  }
	}else{
		echo('Enable blinds in the settings to view the blinds product.');
	} 
?>
