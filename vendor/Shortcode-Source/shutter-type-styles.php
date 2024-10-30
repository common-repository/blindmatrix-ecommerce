<?php
	$blindmatrix_settings = get_option( 'option_blindmatrix_settings',true );	
if(isset($blindmatrix_settings['menu_product_type']) && in_array( 'Shutters' , $blindmatrix_settings['menu_product_type'])){
	$producttypename = str_replace('-',' ',get_query_var("ptn"));
	$producttypeid = get_query_var("ptid");
	global $shutters_page;
	global $shutters_type_page;
	global $shutter_visualizer_page;
	$response = CallAPI("GET", $post=array("mode"=>"GetShutterParameterTypeDetails", "parametertypeid"=>$producttypeid));
	
	$get_productlist = get_option('productlist', true);
	$rescategory = $get_productlist->category_list;
	?>
	
	<div class="row row-main">
	   <div class="large-12">
		<div class="row" style="padding-left: 15px;" >
			<a style="margin: 0;" href="/<?php echo($shutters_type_page); ?>" target="_self" class="button secondary is-link is-smaller lowercase">
				<i class="icon-angle-left"></i>  <span>Back to All Styles</span>
			</a>
			<h1 style="font-size: 40px; margin: 0 0 0 1emx;" class="product-title product_title entry-title prodescprotitle"><?php echo $response->productTypeSubName;?></h1>
		</div>
		  <div class="col-inner">
				 <div class="product_type_desc">
					<!--<h1 class="product_type_desc_header">About <?php// echo $response->productTypeSubName;?></h1>-->
					<p> <?php echo $response->producttypedescription;?></p>
				</div>
				<div class="row large-columns-2 medium-columns-2 small-columns-1 "  >
				  <?php/*  if(count($response->producttype_material_imgurl->images) > 0):
						$newimagepath = array();
						foreach($response->producttype_material_imgurl->images as $key=>$images):
							 $newimagepath[] = $images->getimage;
							endforeach;
							endif;  */?>
				  <?php if(count($response->producttype_price_list) > 0):?>
				  <?php foreach($response->producttype_price_list as $price_list):?>
						<div class="shutter-type-styles product-small col has-hover product type-product post-149 status-publish first instock product_cat-tops product_cat-women has-post-thumbnail shipping-taxable purchasable product-type-simple row-box-shadow-2 ">
							<div class="col-inner">
								<div class="product-small box ">
									<div class="box-image">
										<div class="image-fade_in_back">
										<a href="<?php echo(site_url());?>/<?php echo($shutter_visualizer_page); ?>/<?php echo($producttypename);?>/<?php echo($producttypeid);?>/<?php echo($price_list->parameterTypeSubSubId); ?>">
											<img src="<?php echo blindmatrix_get_plugin_url(); ?>vendor/Shortcode-Source/image/shutter-single/<?php echo $response->productTypeSubName;?>/<?php echo $price_list->itemName; ?>.webp" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail lazy-load-active render-size" alt="" loading="lazy">
										</a>
										</div>
									</div>
									<div class="box-text box-text-products" style=" padding: 0px; ">
										<div class="title-wrapper" style="padding:.7em;">
											 <h3 class="product-option__title"><?php echo $price_list->itemName; ?></h3>
									<?php if($price_list->notes != ''){ ?>
											<p class="name product-title woocommerce-loop-product__title">
												<?php echo($price_list->notes); ?>...
											</p>
									 <?php }?>
										</div>
									</div>
									<a style="background-color: #002746;margin-bottom:0px;box-shadow: unset;min-height: 2.29em;line-height: 2.22em;" rel="noopener noreferrer" href="<?php echo(site_url());?>/<?php echo($shutter_visualizer_page); ?>/<?php echo strtolower(str_replace(' ','-',$producttypename));?>/<?php echo($producttypeid);?>/<?php echo($price_list->parameterTypeSubSubId); ?>" class="button singlecat_but secondary is-large lowercase expand" style="padding:0 0px 0px 0px;">
									 <span class="bt_arrow_mobile"> <span style="color: #fff;">Configure and buy!</span> <i class="icon-angle-right"></i></span>
									</a>
								</div>
							</div>
						</div>
				<?php endforeach; ?>
				<?php endif; ?>
					<div class="shutter-type-styles product-small col has-hover product type-product post-149 status-publish first instock product_cat-tops product_cat-women has-post-thumbnail shipping-taxable purchasable product-type-simple row-box-shadow-2 " style="display:none">
							<div class="col-inner"  style="background-image:url('<?php echo blindmatrix_get_plugin_url(); ?>vendor/Shortcode-Source/image/shutter-single/shutter_new.webp');background-size: contain;">
								<div class="product-small box ">
									<div class="box-image">
										<div class="image-fade_in_back">
											<img src="<?php echo blindmatrix_get_plugin_url(); ?>vendor/Shortcode-Source/image/shutter-single/shutter_new.webp" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail lazy-load-active " style="visibility:hidden" alt="" loading="lazy">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php if(count($get_productlist->shutter_product_list) > 0): ?>
				<div class="related related-products-wrapper product-section">
	
					<h3 style="text-transform: capitalize;" class="product-section-title product-section-title-related pt-half pb-half uppercase">Shop by window shutter style</h3>
	
					<div class="row large-columns-4 medium-columns-3 small-columns-2 row-small slider row-slider slider-nav-reveal slider-nav-push"  data-flickity-options='{"imagesLoaded": true, "groupCells": "100%", "dragThreshold" : 5, "cellAlign": "left","wrapAround": true,"prevNextButtons": true,"percentPosition": true,"pageDots": false, "rightToLeft": false, "autoPlay" : false}'>
					
						<?php foreach($get_productlist->shutter_product_list as $key=>$shutter_product_list): ?>
						<?php if(count($shutter_product_list->GetShutterProductTypeList) > 0): ?>
						<?php foreach ($shutter_product_list->GetShutterProductTypeList as $GetShutterProductTypeList): ?>
						
						<?php
						$url_productTypeSubName = str_replace(' ','-',$GetShutterProductTypeList->productTypeSubName);
						if($GetShutterProductTypeList->imgurl != ''){
							$imagepath = $GetShutterProductTypeList->imgurl;
						}else{
							$imagepath = blindmatrix_get_plugin_url().'Shortcode-Source/image/blinds/no-image.jpg';
						}
						?>
						<div class="shutter-type-styles product-small col has-hover product type-product post-149 status-publish first instock product_cat-tops product_cat-women has-post-thumbnail shipping-taxable purchasable product-type-simple row-box-shadow-2 ">
							<div class="col-inner">
								<div class="product-small box ">
									<div class="box-image">
										<div class="image-fade_in_back">
											<a href="<?php bloginfo('url'); ?>/<?php echo($shutters_page); ?>/<?php echo strtolower($url_productTypeSubName); ?>/<?php echo $GetShutterProductTypeList->parameterTypeId; ?>">
												<img src="<?php echo $imagepath;?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail lazy-load-active" alt="" loading="lazy">
											</a>
										</div>
									</div>
									<div class="box-text box-text-products">
										<div class="title-wrapper" style="padding:.7em;">
											<p class="name product-title woocommerce-loop-product__title">
												<a style="display:inline-block;font-weight:700;width: 140px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;" href="<?php bloginfo('url'); ?>/<?php echo($shutters_page); ?>/<?php echo trim(strtolower($url_productTypeSubName));?>/<?php echo $GetShutterProductTypeList->parameterTypeId; ?>"><?php echo $GetShutterProductTypeList->productTypeSubName; ?></a>
											</p>
											<p class="name product-title woocommerce-loop-product__title">
												<?php echo truncate_description($GetShutterProductTypeList->producttypedescription, 100); ?>
											</p>
											
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
						<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
				<?php endif; ?>
				
			 </div>
		  </div>
	   </div>
	
	<script>
	
	var producttypename = '<?=get_query_var("ptn"); ?>';
	var producttypeid = '<?=get_query_var("ptid"); ?>';
	 
	jQuery( document ).ready(function($) {
		/*$(".product-option__right input[type=radio]").change(function() {
			var current = $( this );
			//console.log(current);
			$( ".product-option__right input[type=radio]" ).each(function( index ) {
				var label = $( this ).parent('.product-option__right').find('label');
				label.removeClass('is-selected');
				label.text('Select');
			});
			var label = $(".product-option__right input[type=radio]:checked").parent('.product-option__right').find('label');
			label.addClass('is-selected');
			label.text('Selected');
		}).change();
		$('.singlecat_but').click(function(event) {
			event.preventDefault();
			var priceid = $('input[name="product_category"]:checked').val();
			window.location.href = '<?= site_url() ?>'+'/<?php echo($shutter_visualizer_page); ?>/'+producttypename+'/'+producttypeid+'/'+priceid;
		});*/
		
	}); 
	
	</script>	
<?php 	
}else{	
	echo('Enable shutters in the settings to view the shutter products.');	
} ?>
<style>
	.render-size {
    	object-fit: cover;
    	max-height: 350px;
	}
	.box-image>a, .box-image>div>a {
		display: block !important;	
	}
</style>
