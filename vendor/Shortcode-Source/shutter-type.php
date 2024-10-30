<?php
$blindmatrix_settings = get_option( 'option_blindmatrix_settings',true );
if(isset($blindmatrix_settings['menu_product_type']) && in_array( 'Shutters' , $blindmatrix_settings['menu_product_type'])){
$get_productlist = get_option('productlist', true);
global $shutters_page;
global $shutters_type_page;
global $shutter_visualizer_page;
$GetShutterProductTypeList = array();
foreach ($get_productlist->shutter_product_list as $shutter_product_list){
    if(count($shutter_product_list->GetShutterProductTypeList) > 0){
        foreach ($shutter_product_list->GetShutterProductTypeList as $GetShutterProductTypeList){
            $Get_ShutterProductTypeList[] = $GetShutterProductTypeList;
        }
    }
}

?>

<div class="shutter_type_container">
<?php
$x = 0;
if(count($shutter_product_list->GetShutterProductTypeList) > 0){
foreach ($Get_ShutterProductTypeList as $ShutterProductTypeList){
    if($ShutterProductTypeList->imgurl != ''){
        $ShutterProductTypeList->imgurl = $ShutterProductTypeList->imgurl;
    }else{
        $ShutterProductTypeList->imgurl = get_stylesheet_directory_uri().'/icon/no-image.jpg';
    }
    
    $url_productTypeSubName = str_replace(' ','-',$ShutterProductTypeList->productTypeSubName);
    
?>
  
<div class="row align-middle align-center shutter_type_row">
   <div id="col-733746767" class="col medium-6 small-12 large-6 shutter_order_col <?php if($x % 2 == 0){echo('right');}else{echo('left');}?>">
	  <div class="col-inner">
		 <div class="box has-hover imagebox_shutters  has-hover box-text-bottom">
			<div class="box-image">
			   <a href="<?php bloginfo('url'); ?>/<?php echo($shutters_page); ?>/<?php echo trim(strtolower($url_productTypeSubName));?>/<?php echo $ShutterProductTypeList->parameterTypeId; ?>" rel="noopener noreferrer">
				  <div class="">
				  <img width="460" height="333" src="<?php echo $ShutterProductTypeList->imgurl; ?>" class="attachment- size-" alt="" loading="lazy" sizes="(max-width: 460px) 100vw, 460px" />
				  </div>
			   </a>
			   <div class="price-lozenge">
					<div class="price-lozenge__inner">
						<p class="price-lozenge__label">From</p>
						<div class="amount">
							<span data-price-incl-discount="£15" class="price-lozenge__price"><?php echo get_woocommerce_currency_symbol();?><?php echo $ShutterProductTypeList->price; ?></span>
							<!--<span class="price-lozenge__unit" data-unit="ft<sup>2</sup>">m<sup>2</sup></span>-->
						</div>
					</div>
				</div>
			</div>
			<div class="box-text text-center">
			   <div class="box-text-inner">
				  <a rel="noopener noreferrer" href="<?php bloginfo('url'); ?>/<?php echo($shutters_page); ?>/<?php echo trim(strtolower($url_productTypeSubName));?>/<?php echo $ShutterProductTypeList->parameterTypeId; ?>" class="button secondary is-xlarge lowercase expand" style="padding:0 0px 0px 0px;">
				 <span class="bt_arrow_desktop button-shutter-style">
					 <?php if($x % 2 == 0){?>
					   <i class="icon-angle-left"></i>
					  <span>Buy <?php echo $ShutterProductTypeList->productTypeSubName; ?></span>
					 
					 <?php }else{ ?>
					 <span>Buy <?php echo $ShutterProductTypeList->productTypeSubName; ?></span>
					  <i class="icon-angle-right"></i>
					  <?php
					  }
					   ?>
				   </span>
				   <span class="bt_arrow_mobile" style="display:none;">
					 <span>Buy <?php echo $ShutterProductTypeList->productTypeSubName; ?></span>
					  <i class="icon-angle-right"></i>
				   </span>
				  </a>
			   </div>
			</div>
		 </div>
	  </div>
   </div>
   <div id="col-404214082" class="col medium-6 small-12 large-6 shutter_order_col <?php if($x % 2 == 0){echo('left');}else{echo('right');}?>">
	  <div class="col-inner">
		 <h3><?php echo $ShutterProductTypeList->productTypeSubName; ?></h3>
		 <p><?php echo truncate_description($ShutterProductTypeList->producttypedescription, 500); ?></p>
	  </div>
   </div>
</div>
<?php
$x++;
}
}
?>
</div>
<?php 
}else{
	echo('Enable shutters in the settings to view the shutter products.');
} ?>