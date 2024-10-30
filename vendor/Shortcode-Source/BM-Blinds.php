<?php
	$blindmatrix_settings = get_option( 'option_blindmatrix_settings',true );
if(isset($blindmatrix_settings['menu_product_type']) && in_array( 'Blinds' , $blindmatrix_settings['menu_product_type'])){
	$get_productlist = get_option('productlist', true);
	$pro_count = count($get_productlist->product_list);

	function truncate($text, $chars = 25) {
		if (strlen($text) <= $chars) {
			return $text;
		}
		$text = $text." ";
		$text = substr($text,0,$chars);
		$text = substr($text,0,strrpos($text,' '));
		$text = $text."...";
		return $text;
	}
		global $product_page;
		global $product_category_page;
		global $blinds_config;
		global $shutters_page;
		global $shutter_visualizer_page;
	?>


	<div class="bmcsscn row row-small align-center row-box-shadow-2 row-box-shadow-4-hover" id="row-1778499989">
		<?php $pro =1;?>
		
		<?php if (count($get_productlist->product_list) > 0): ?> 	
		<?php foreach ($get_productlist->product_list as $product_list): ?>
		
		<?php
		$product_imagepath = $product_list->imagepath;
		if($product_imagepath != ''){
			$product_imagepath = $product_imagepath;
		}else{
			$product_imagepath = get_stylesheet_directory_uri().'/icon/no-image.jpg';
		}
		$productname_arr = explode("(", $product_list->productname);
		$get_productname = trim($productname_arr[0]);
		?>

		<div class="col medium-3 small-6 large-3">
			<div class="col-inner">
				<div class="box cusbox has-hover has-hover box-default box-text-bottom">
					<div class="box-image">
						<a href="<?php bloginfo('url'); ?>/<?php echo($product_page); ?>/<?php echo str_replace(' ','-',strtolower($get_productname)); ?>" target="_self">
							<div class="">
								<img width="1500" height="793" src="<?php echo $product_imagepath; ?>" class="attachment- size-" alt="" sizes="(max-width: 1500px) 100vw, 1500px">
							</div>
						</a>
					</div>
					<!-- box-image -->
					<div class="box-text text-center">
						<div class="box-text-inner">
							<h4><?php echo $get_productname; ?></h4>
						 <!-- <?php  if($product_list->productdescription != ''):?>
							<p style="min-height: 3em;">&nbsp;<?php echo truncate($product_list->productdescription, 70); ?></p>
							<?php endif;?> -->
						</div>
						<div class="social-icons follow-icons">
							<a href="<?php bloginfo('url'); ?>/<?php echo($product_page); ?>/<?php echo str_replace(' ','-',strtolower($get_productname)); ?>" class="button2">
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
		<?php $pro++;?>

		<?php endforeach; ?>
		<?php endif; ?>
		
		<style scope="scope">
		</style>
	</div>
<?php }else{
	echo('Enable blinds in the settings to view the blinds product.');
} ?>