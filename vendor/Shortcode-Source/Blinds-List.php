<?php
if(!blindmatrix_check_premium()){
	return;
}

$blindmatrix_settings = get_option( 'option_blindmatrix_settings',true );
if(isset($blindmatrix_settings['menu_product_type']) &&  in_array( 'Blinds' , $blindmatrix_settings['menu_product_type'])){
	$get_productlist = get_option('productlist', true);
	global $product_page;
	?>

	<ul class="menu bmcsscn">
		<?php if(count($get_productlist->product_list) > 0): ?>
		<?php 
		$inc_products = isset($blindmatrix_settings['blindslistproid']) && is_array($blindmatrix_settings['blindslistproid']) && !empty($blindmatrix_settings['blindslistproid']) ? array_keys($blindmatrix_settings['blindslistproid']): array();  
		?>
		<?php foreach ($get_productlist->product_list as $product_list): ?>
		<?php
		 if(!isset($product_list->productid) || empty($inc_products) || !in_array($product_list->productid,$inc_products)){
			  continue;
		 }	
		$productname_arr = explode("(", $product_list->productname);
		$get_productname = trim($productname_arr[0]);
		?>
		<li class="menu-item menu-item-type-post_type menu-item-object-page">
			<a href="<?php bloginfo('url'); ?>/<?php echo($product_page); ?>/<?php echo str_replace(' ','-',strtolower($get_productname)); ?>">
			<?php echo $get_productname; ?>
			</a>
		</li>
		<?php endforeach; ?>
		<?php endif; ?>
	</ul>
<?php }else{
		echo('Enable blinds in the settings to view the blinds product.');
	} 
?>