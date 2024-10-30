<?php
if(!blindmatrix_check_premium()){
	return;
}

$sample_cart = get_option("sample_cart");
$sample_cart_link =  get_permalink($sample_cart);
if(isset($_SESSION['cart']) && is_array($_SESSION['cart'])){
	$count = count($_SESSION['cart']);
}else{
	$count =0;
}

?>
<div class="bmcsscn" style="display:inline-block;">
	<div class="header-wishlist-icon">
		<a href="<?php echo($sample_cart_link); ?>" class="wishlist-link is-small">
			<span class="header-wishlist-title" style="display:none;">Free Sample</span>
				<i class="wishlist-icon icon-shopping-basket free-sample-cart" data-icon-label="<?php echo $count;?>"></i>
		</a>
	</div>
</div>