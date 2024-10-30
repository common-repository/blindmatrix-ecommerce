<?php
$get_productlist = get_option('productlist', true);
global $product_page;
?>

<ul class="menu">
	<?php $procount =1;?>
	<?php if(count($get_productlist->product_list) > 0): ?>
	<?php foreach ($get_productlist->product_list as $product_list): ?>
	<?php
	$productname_arr = explode("(", $product_list->productname);
	$get_productname = trim($productname_arr[0]);
	?>
	<?php if ($procount <= 7): ?>
	<li class="menu-item menu-item-type-post_type menu-item-object-page">
		<a href="<?php bloginfo('url'); ?>/<?php echo($product_page); ?>/<?php echo str_replace(' ','-',strtolower($get_productname)); ?>">
		<?php echo $get_productname; ?>
		</a>
	</li>
	<?php $procount++;?>
	<?php endif; ?>
	<?php endforeach; ?>
	<?php endif; ?>
</ul>