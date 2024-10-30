<?php
$blindmatrix_settings = get_option( 'option_blindmatrix_settings',true );
if(isset($blindmatrix_settings['menu_product_type']) && in_array( 'Shutters' , $blindmatrix_settings['menu_product_type'])){
	$get_productlist = get_option('productlist', true);

	global $shutters_page;
?>

<div class="bmcsscn">
	<div class="row row-small align-center row-box-shadow-2 row-box-shadow-4-hover">

		<?php if (count($get_productlist->shutter_product_list) > 0): ?> 	
		<?php $ptcount =0;?>
		<?php foreach ($get_productlist->shutter_product_list as $shutter_product_list): ?>
		
		<?php if(count($shutter_product_list->GetShutterProductTypeList) > 0): ?>
		<?php foreach ($shutter_product_list->GetShutterProductTypeList as $GetShutterProductTypeList): ?>
		
		<?php
		$shutter_imagepath = $GetShutterProductTypeList->imgurl;
		$url_productTypeSubName = str_replace(' ','-',$GetShutterProductTypeList->productTypeSubName);
		?>

		<div class="col medium-3 small-6 large-3">
			<div class="col-inner">
				<div class="box cusbox has-hover has-hover box-default box-text-bottom">
					<div class="box-image">
						<a href="<?php bloginfo('url'); ?>/<?php echo($shutters_page); ?>/<?php echo trim(strtolower($url_productTypeSubName));?>/<?php echo $GetShutterProductTypeList->parameterTypeId; ?>" target="_self">
							<div class="">
								<img width="1500" height="793" src="<?php echo $shutter_imagepath; ?>" class="attachment- size-" alt="" sizes="(max-width: 1500px) 100vw, 1500px">
							</div>
						</a>
					</div>
					<!-- box-image -->
					<div class="box-text text-center">
						<div class="box-text-inner">
							<h4><?php echo $GetShutterProductTypeList->productTypeSubName; ?></h4>
						</div>
						<div class="social-icons follow-icons">
							<a href="<?php bloginfo('url'); ?>/<?php echo($shutters_page); ?>/<?php echo trim(strtolower($url_productTypeSubName));?>/<?php echo $GetShutterProductTypeList->parameterTypeId; ?>" class="button2">
								<span style="padding: 0px !important;">Shop Now</span>
							</a>
						</div>
						<!-- box-text-inner -->
					</div>
					<!-- box-text -->
				</div>
				<!-- .box  -->
			</div>
		</div>
		<?php $ptcount++;?>
		<?php endforeach; ?>
		<?php endif; ?>

		<?php endforeach; ?>
		<?php endif; ?>

	</div>
</div>

<script type="text/javascript">
var ptcount = '<?=$ptcount;?>';
//console.log(ptcount);
if(ptcount == 0){
    jQuery('.shop_by_shutter_row').css('display','none');
}
</script>
<?php }else{
	echo('Enable shutter in the settings to view the shutter products.');
} ?>