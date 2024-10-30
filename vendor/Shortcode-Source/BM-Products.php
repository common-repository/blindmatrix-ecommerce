<?php
if(!blindmatrix_check_premium()){
	return;
}

$shortcode_arr =array();
if(isset($attrs['products']) && $attrs['products'] != '' ){
	if( strpos($attrs['products'], ',') !== false ) {
		$shortcode_arr= array_map('trim', explode(',', $attrs['products']));
	}else{
	  	$shortcode_arr[]=$attrs['products'];
	}
}else{
	$shortcode_arr = array('Blinds','Shutters','Curtains');
}
$blindmatrix_settings = get_option('option_blindmatrix_settings', true);
$get_productlist = get_option('productlist', true);
global $product_page;
global $shutters_page;
global $curtains_single_page;
$get_category_details = array();
$products = array();
if (isset($blindmatrix_settings['menu_product_type']) && in_array('Blinds', $blindmatrix_settings['menu_product_type']) && in_array('Blinds', $shortcode_arr)) {
    $blinds = array();
    if (is_array($get_productlist->product_list) && count($get_productlist->product_list) > 0) {
		$inc_products = isset($blindmatrix_settings['blindslistproid']) && is_array($blindmatrix_settings['blindslistproid']) && !empty($blindmatrix_settings['blindslistproid']) ? array_keys($blindmatrix_settings['blindslistproid']): array();  
		 foreach ($get_productlist->product_list as $key => $product_list) {
			if(!isset($product_list->productid) || empty($inc_products) || !in_array($product_list->productid,$inc_products)){
				  continue;
			 }	
            $blinds['blind_' . $key]['category_details'] = $get_category_details;
            $productname_arr = explode("(", $product_list->productname);
            $blinds['blind_' . $key]['name'] = trim($productname_arr[0]);
            $blinds['blind_' . $key]['url'] = get_bloginfo('url') . '/' . $product_page . '/' . str_replace(' ', '-', strtolower($product_list->productname)) . '/';
            if ($product_list->imagepath != '') {
                $blinds['blind_' . $key]['img'] = $product_list->imagepath;
                $blinds['blind_' . $key]['price'] = $product_list->price;
            } else {
                $blinds['blind_' . $key]['img'] = get_stylesheet_directory_uri() . '/icon/no-image.jpg';
            }
            $blinds['blind_' . $key]['des'] = $product_list->productdescription;
        }
        /* if (count($get_productlist->product_list) % 3 != 0) {
            $blinds['blinds_add']['name'] = '';
        } */
    }
    $products['blinds'] = $blinds;
}
if (isset($blindmatrix_settings['menu_product_type']) && in_array('Shutters', $blindmatrix_settings['menu_product_type']) && in_array('Shutters', $shortcode_arr)) {
    $shutter = array();
    if (is_array($get_productlist->shutter_product_list) && count($get_productlist->shutter_product_list) > 0) {
        foreach ($get_productlist->shutter_product_list as $shutter_product_list) {
            if (is_array($shutter_product_list->GetShutterProductTypeList) && count($shutter_product_list->GetShutterProductTypeList) > 0) {
                $inc_products = isset($blindmatrix_settings['shutterlistproid']) && is_array($blindmatrix_settings['shutterlistproid']) && !empty($blindmatrix_settings['shutterlistproid']) ? array_keys($blindmatrix_settings['shutterlistproid']): array();  
				foreach ($shutter_product_list->GetShutterProductTypeList as $keys => $GetShutterProductTypeList) {
					if(!isset($GetShutterProductTypeList->parameterTypeId) || empty($inc_products) || !in_array($GetShutterProductTypeList->parameterTypeId,$inc_products)){
					  continue;
				  	}
                    $url_productTypeSubName = str_replace(' ', '-', $GetShutterProductTypeList->productTypeSubName);
                    $shutter['shuther_' . $keys]['name'] = $GetShutterProductTypeList->productTypeSubName;
                    $url = get_bloginfo('url') . '/' . $shutters_page . '/' . trim(strtolower($url_productTypeSubName)) . '/' . $GetShutterProductTypeList->parameterTypeId;
                    $shutter['shuther_' . $keys]['url'] = alter_shutter_product_url($url,$GetShutterProductTypeList->parameterTypeId,trim(strtolower($url_productTypeSubName)));
                    $shutter['shuther_' . $keys]['img'] = $GetShutterProductTypeList->imgurl;
                    $shutter['shuther_' . $keys]['des'] = $GetShutterProductTypeList->producttypedescription;
                    if ($GetShutterProductTypeList->imgurl != '') {
                        $shutter['shuther_' . $keys]['img'] = $GetShutterProductTypeList->imgurl;
                        $shutter['shuther_' . $keys]['price'] = $GetShutterProductTypeList->price;
                    } else {
                        $shutter['shuther_' . $keys]['img'] = get_stylesheet_directory_uri() . '/icon/no-image.jpg';
                    }
                }
				/* 
				if (count($shutter_product_list->GetShutterProductTypeList) == 4) {
                    $shutter['shuther_4']['name'] = '';
                }
                if (count($shutter_product_list->GetShutterProductTypeList) == 7) {
                    $shutter['shuther_7']['name'] = '';
                } */
            }
        }
    }
    $products['shutter'] = $shutter;
}
if (isset($blindmatrix_settings['menu_product_type']) && in_array('Curtains', $blindmatrix_settings['menu_product_type']) && in_array('Curtains', $shortcode_arr)) {
    $curtain = array();
    if (is_array($get_productlist->curtain_product_list) && count($get_productlist->curtain_product_list) > 0) {
		 $inc_products = isset($blindmatrix_settings['curtainlistproid']) && is_array($blindmatrix_settings['curtainlistproid']) && !empty($blindmatrix_settings['curtainlistproid']) ? array_keys($blindmatrix_settings['curtainlistproid']): array();  
		 foreach ($get_productlist->curtain_product_list as $curtain_product_list) {
            if (is_array($curtain_product_list->GetCurtainProductTypeList) && count($curtain_product_list->GetCurtainProductTypeList) > 0) {
			   $GetCurtainProductTypeList = array_unique(array_column($curtain_product_list->GetCurtainProductTypeList, 'curtain_type'));
                $unique_GetCurtainProductTypeList = array_intersect_key($curtain_product_list->GetCurtainProductTypeList, $GetCurtainProductTypeList);
                foreach ($unique_GetCurtainProductTypeList as $_key => $GetCurtainProductTypeList) {
				   if(!in_array($GetCurtainProductTypeList->curtain_type,$inc_products)){
						continue;
					}
                    $curtain_productname = $GetCurtainProductTypeList->curtain_type;
                    $url_productTypeSubName = str_replace(' ', '-', $GetCurtainProductTypeList->curtain_type);
                    $curtain['curtain_' . $_key]['name'] = $GetCurtainProductTypeList->productTypeSubName;
                    $curtain['curtain_' . $_key]['url'] = get_bloginfo('url') . '/' . $curtains_single_page . '/' . trim(strtolower($url_productTypeSubName)) . '/' . $GetCurtainProductTypeList->productid  . '/' . $GetCurtainProductTypeList->parameterTypeId;
                    $curtain['curtain_' . $_key]['img'] = plugin_dir_url(__DIR__) . 'Shortcode-Source/image/curtains/' . $curtain_productname . '.webp';
                    $curtain['curtain_' . $_key]['price'] = $GetCurtainProductTypeList->minprice;
                    $curtain['curtain_' . $_key]['des'] = $GetCurtainProductTypeList->producttypedescription;
                }
                /* if (count($curtain_product_list->GetCurtainProductTypeList) == 12) {
                    $curtain['curtain_13']['name'] = '';
                } */
            }
        }
    }
    $products['curtain'] = $curtain;
}

foreach ($products as $keycx => $productssingle) {
    if ($keycx == 'blinds') {
        $title = "Shop Our Blinds";
    } elseif ($keycx == 'shutter') {
        $title = "Shop Our Shutter";
    } elseif ($keycx == 'curtain') {
        $title = "Shop Our Curtain";
    } else {
        $title = '';
    }
    if ($keycx == 'blinds') {
        $classes = "blinds_container";
    } elseif ($keycx == 'shutter') {
        $classes = "shutter_container";
    } elseif ($keycx == 'curtain') {
        $classes = "curtain_container";
    } else {
        $classes = '';
    }
?>

	<div class="product-container-grid bmcsscn ">
	<?php if($attrs['title'] == 'true'){ ?>
		<h2 class="divider donotcross"><?php echo ($title); ?></h2>
	<?php } ?>
		<div class="grid <?php echo $classes; ?>">
			<?php
    foreach ($productssingle as $keycs => $blindproduct) {
		
			if ($keycs == 'blinds_add') {
				if($attrs['price'] == 'true' && $attrs['desc'] == 'true'){
	?>
						<div style="background-size: cover; background-position: center;background-image: url('<?php echo (plugin_dir_url(__DIR__) . 'Shortcode-Source/image/blindbanner.jpg'); ?>');" class="product-blindimg-container-grid">
							<!--<img  src="<?php echo (plugin_dir_url(__DIR__) . 'Shortcode-Source/image/blindbanner.jpg'); ?>" >-->
						 <div class="overlay">
							<h1>Shop our Blinds</h1>
							 <p>You can add style to your windows by placing blinds with one of our fabrics. 
								view the full range of blinds and determine added collections from our modern Visualize.</p>
						  </div>
							<a target="_blank" href="/product/"></a>
						</div>
					<?php
				}
				continue;
			} elseif ($keycs == 'shuther_4') {
				if($attrs['price'] == 'true' && $attrs['desc'] == 'true'){
	?>	
						<div style="background-size: cover; background-position: center;background-image: url('<?php echo(plugin_dir_url( __DIR__ ).'Shortcode-Source/image/Shutter-Banner.jpg'); ?>');" class="product-img-container-grid">
							<img  src="<?php echo(plugin_dir_url( __DIR__ ).'Shortcode-Source/image/Shutter-Banner.jpg'); ?>" >
						</div>
					<?php
				}
				continue;
			} elseif ($keycs == 'curtain_13') {
				if($attrs['price'] == 'true' && $attrs['desc'] == 'true'){
	?>
					<article  class="card-grid__item card-product step-up product type-product post-9433 status-publish instock product_cat-curtains product_cat-curtains-sheer taxable shipping-taxable purchasable product-type-simple curtain-banner">
						<a target="_blank" href="/measuring-guides/">
						 <div class="quality-tile-new quality-tile-swift quality-tile-measure"> 
							 <div class="quality-tile-swift-content">
								  <div class="quality-tile-swift-title">Made to Measure <br> Blinds &amp; Curtains</div>
								  <div class="quality-tile-swift-text">Crafted to your exact requirements</div> 
							  </div> 
							  <div class="quality-tile-swift-img"> 
								<img src="<?php echo (plugin_dir_url(__DIR__) . 'Shortcode-Source/image/measure-tape.png'); ?>"> 
							  </div> 
						  </div>   
							
						</a>
					</article>
					
					<?php
				}
				continue;
			}
		
?>		
<article  class="card-grid__item card-product step-up product type-product post-9433 status-publish instock product_cat-curtains product_cat-curtains-sheer taxable shipping-taxable purchasable product-type-simple">
	<a href="<?php echo $blindproduct['url']; ?>" class="card-product__link">
		<div class="card-product__top">
			<div class="<?php echo ($classes); ?> card-product__hero lazyload loaded"  style="background-image: url(&quot;<?php echo $blindproduct['img']; ?>&quot;);"></div>                        
			<div class="<?php echo ($classes); ?> card-product__copy">
				<h3 style="color:white;"><?php echo $blindproduct['name']; ?> </h3>
			</div>
		</div>
		<div class="<?php if($attrs['price'] == 'false' && $attrs['desc'] == 'false'){ echo(''); }else{ echo('card-product__meta'); } ?>" >
		<?php if($attrs['price'] == 'true'){ ?>
			<div class="na-price" style="display:none;">Pricing unavailable for your window dimensions.</div>
			<div data-product="price" class="card-product__price">
              <?php if(isset($blindproduct['price']) && $blindproduct['price'] >= 1): ?> 
				<div>
                    <strong>Price from</strong>
                    <span class="woocommerce-Price-amount amount fontfam">
						<bdi>
							<span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol(); ?></span>
							<?php echo number_format((float)$blindproduct['price'], 2, '.', '');  ?>
						</bdi>
					</span>
				</div>  
              <?php endif; ?>                                                                                              
				<div>  
					<span class="card-product__price-disclaimer">Prices may vary depending on customisations*</span>
				</div>   
			</div>
		<?php } ?>
		<?php if($attrs['desc'] == 'true'){ ?>
			<div  class="shuttertext">
				<span style="text-align:center;"> <?php echo substr_replace($blindproduct['des'], "...", 100); ?> </span>
			</div>	
		<?php } ?>
		</div>
	</a>
</article>	 
					 
<?php  
	}
?>
	</div>
</div>
		<?php
    }
?>
	
<style>
@font-face {
  font-family: 'MedievalSharp';  
  src: url(<?php echo (plugin_dir_url(__DIR__)); ?>'/assets/fonts/MedievalSharp-Regular.ttf');  
  font-weight: normal;  
}
h2.donotcross {
text-align: center;
    font-size:30px; font-weight:500; color:#002746; letter-spacing:1px;
    text-transform: uppercase;

    display: grid;
    grid-template-columns: 1fr max-content 1fr;
    grid-template-rows: 27px 0;

    align-items: center;
	max-width: 1165px;
    margin: 30px auto 10px;
	 
}

h2.donotcrossmanual {
text-align: center;
    font-size:30px; font-weight:500; color:#002746; letter-spacing:1px;
    text-transform: uppercase;
    display: grid;
    grid-template-columns: 1fr max-content 1fr;
    grid-template-rows: 27px 0;
    align-items: center;
	max-width: 1165px;
    margin: 15px auto 10px;
}


h2.donotcross:after,h2.donotcross:before {
    content: " ";
    display: block;
    border-bottom: 1px solid #002746;
    border-top: 1px solid #002746;
    background-color: #002746;
   height: 3px;
}

h2.donotcrossmanual:after,h2.donotcrossmanual:before {
    content: " ";
    display: block;
    border-bottom: 1px solid #002746;
    border-top: 1px solid #002746;
    background-color: #002746;
   height: 3px;
}


.grid {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    padding: 0 4px;
    justify-content: flex-start;

	margin:auto;
}

.curtain_container {
        display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
   /* padding: 0 4px;*/
    justify-content: flex-start;
    max-width: 100%;
    margin: auto;
      
    
}
	 .grid-item {
		-ms-flex: 31.6%;
		flex: 31.6%;
		max-width: 31.6%;
		position: relative;
		margin: 10px;

	}
	.product-img-container-grid {
		display: flex;
		flex: 65%;
		max-width: 65%;
		position: relative;
		margin: 10px;
		box-shadow: 0 0 10px rgb(46 53 71 / 50%);
		transition: border-color .2s cubic-bezier(.26,.01,.73,.99);
		transition: 950ms;
	}
	.product-img-container-grid:hover {
		transform: translateY(-5px);
	}
	
		
	.product-blindimg-container-grid {
    display: flex;
    flex: auto;
    max-width: auto;
    position: relative;
    margin: 10px;
    box-shadow: 0 0 10px rgb(46 53 71 / 50%);
    transition: border-color .2s cubic-bezier(.26,.01,.73,.99);
    transition: 950ms;
    /*background-color: var(--bm-primary-color);*/
}

		.product-blindimg-container-grid:hover {
		transform: translateY(-5px);
	}
	
	.product-blindimg-container-grid a {
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  position: absolute;
  z-index: 1;
}
	

.overlay {
    background-color: #f5f5f5c4;
    position: absolute;
    height: auto;
    padding: 10px 10px 10px 10px;
    top: 10%;
    bottom: 10%;
    left: 10%;
    right: 10%;
    right: 10%;
    border: 1.5px solid #fff;
    /* box-shadow: 3px 3px 3px 3px #878787; */
    /* align-items: center; */
}


.overlay p {
    
    color: #000000;
    font-size: larger;
}


.overlay h3 {
    
    color: #000000;
    font-family: 'Raleway', Arial, sans-serif !important;
}

.gif1.giftext-center {
    display: flex;
    flex-direction: row;
    justify-content: center;
    width: 223px;
    margin: 90px auto 0;
    text-align: center;
    align-items: center;
}

.giftext-center{
    
    text-align: center!important;
}

.gif {
    width: 36%;
    margin-top: -55px;
}


.img-fluid {
    max-width: 100%;
    height: auto;
}

.gif1 img {
    vertical-align: middle;
    border-style: none;
}

	
	
@media screen and (max-width: 560px) {
    
    body {
    font-size: 100%;
    font-weight:300;
}
    
	.product-container-grid {
		max-width: 400px;
		margin: auto!important;
	}
	h2.donotcross{
		font-size:13px;
		font-weight:600;
	}
	 .grid-item {
		-ms-flex:44%;
		flex: 44%;
		max-width: 44%;
		position: relative;
		margin: 10px;

	}
	.product-img-container-grid{
		-ms-flex:100%;
		flex: 100%;
		max-width: 100%;
		position: relative;
		margin: 10px;
	}
	
	.product-blindimg-container-grid {
	visibility: hidden;
}

.curtain_container {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    /*padding: 0 4px;*/
    justify-content: flex-start;
    max-width: 100%;
    margin: auto;
    margin-left: 5px; 
    margin-right: 5px; 
}

}

@media screen and (min-width:570px) and (max-width:900px) {
 
 .grid-item {
    -ms-flex: 31.6%;
    flex: 28.6%;
    max-width: 31.6%;
	}
}

.tooltip .tooltiptext {
    visibility: hidden;
    width: auto;
    background-color: #002746;
    color: #fff;
    text-align: center;
    border-top-left-radius: 15px;
    border-bottom-right-radius: 15px;
    padding: 4px 8px 4px 8px;
    position: absolute;
    z-index: 1;
    top: -35px;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
}

.curtain-banner {
    border-radius: 8px 8px 8px 8px;
     background-color: #002746;
}

.shutterslider {
    width: 65%;

   margin: 30px 4px 15px 5px;

}

.quality-tile-swift-img {
    -webkit-flex: 1 1;
    -ms-flex: 1 1;
    flex: 1 1;
    position: relative;
    z-index: 1;
    overflow: hidden;
    
}

.quality-tile-swift {
    border-radius: 8px;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
}

.quality-tile-measure .quality-tile-swift-title {
    color: #fcfcfc;
}

.quality-tile-swift-title {
    font-size: 27px;
}

.quality-tile-swift-title {
    font-family: RecoletaAlt-Medium;
    line-height: 1.4;
    font-weight: 700;
    margin: 0 -8px 35px 0;
    letter-spacing: 3.180451px;
}

.quality-tile-swift-text {
    font-family: RecoletaAlt-Medium;
    font-size: 18px;
}

.quality-tile-swift-text {
    color: #fcfcfc;
    padding-top: 20px;
    font-weight: 150;
    line-height: 1.4;
    letter-spacing: 4.180451px;
}

.quality-tile-new {
    width: 100%;
    height: 100%;
    position: relative;
    text-align: left;
}

.quality-tile-swift-content {
    padding: 40px;
    text-align: center;
}

article.curtain-banner a {
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    z-index: 1;
}

.grid-item img {
    vertical-align: middle;
    width: 100%;
}
h4.grid-item-title {
    position: absolute;
    z-index: 9;
    bottom: 0%;
    text-align: center;
    background: #ffffff6e;
}
@import url(https://fonts.googleapis.com/css?family=Raleway:400,800);
figure.blind-grid-item {
	font-family: 'Raleway', Arial, sans-serif;
	position: relative;
	overflow: hidden;
	width: 100%;
	color: #ffffff;
	text-align: center;
	-webkit-box-shadow: 0 0 10px rgb(46 53 71 / 50%);
	box-shadow: 0 0 10px rgb(46 53 71 / 50%);
	transition: border-color .2s cubic-bezier(.26,.01,.73,.99);
	transition: 950ms;
	background-color: #CCF3FF;
}


figure.blind-grid-item:hover {
	transform: translateY(-5px);
}

figure.blind-grid-item img {
  max-width: 100%;
  position: relative;
}

figure.blind-grid-item figcaption {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}

figure.blind-grid-item h2 {
	
    position: absolute;
    left: 0;
    right: 40px;
    display: inline-block;
    padding: 12px 5px;
    margin: 0;
    bottom: 0;
    font-weight: 500;
}

figure.blind-grid-item h2 span {
  font-weight: 800;
}

figure.blind-grid-item a {
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  position: absolute;
  z-index: 1;
}

.product-blindimg-container-grid a {
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  position: absolute;
  z-index: 1;
}

figure.blind-grid-item.blue h2 {
  background:  #0a212fb8;
  color:#fff;
  font-size: 16px;
}
.product-container-grid {
    margin: 20px 0;
}

.product-img-container-grid img{
	visibility: hidden;
}
.gridtext{
color:red;}

.card-product__top {
    overflow: hidden;
    position: relative;
    padding-top: 355px;
    border-radius: 8px 8px 0 0;
    z-index: 1;
	
}

.card-product__hero {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-repeat: no-repeat;
    background-color: #9397996e;
    background-size: cover;
	display: block;
	display:flex;
   
}

.curtain_container.card-product__hero {
	 background-position: 10% 25%;
}

.curtain_container.card-product__copy {
	padding: 10px 10px 2px 10px !impartent;
}

.card-grid__item {
	width: calc(31.333%);
	margin: 10px;
}

.card-grid__item:hover
{
    transform: translateY(-5px);
}

.card-product__copy {
    position: absolute;
    bottom: 0;
    left: 0;
    
    padding: 10px 5px 2px 10px;
    color: #fff;
    z-index: 2;
    font-size: 1rem;
	background-color:#002746;
	border-top-right-radius: 25px;
	padding-right:15px
}
/*.card-product__link {
    background: #00c2ff33;
    display: block;
    color: #1e1e1f;
    border-radius: 8px;
    min-height: 100%;
}*/
.card-product__meta {
    position: relative;
    padding: 10px 25px 10px;
    width: 100%;
	border-bottom-right-radius: 8px;
    border-bottom-left-radius: 8px;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    z-index: 2;
    background: rgba(var(--bm-primary-color),0.2);
    display: block;
    color: #1e1e1f;
    font-weight: 400;
	
}
.colour-option__item {
    width: 26px;
    height: 26px;
    position: relative;
    margin: 4px 8px 8px 0;
    border-radius: 50%;
    background-color: #f5f4ee;
    -webkit-box-shadow: inset 0 0 3px rgb(0 0 0 / 10%), inset 0 0 0 1px rgb(0 0 0 / 7%);
    box-shadow: inset 0 0 3px rgb(0 0 0 / 10%), inset 0 0 0 1px rgb(0 0 0 / 7%);
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
}
.colour-options {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
}
.colour-option__item--more {
    margin-right: 0;
    background: #00c2ff;
	border-radius:0px;
	    margin-right: 0;
}
.tooltip {
 text-align:center;

}

.ptext p {
    text-align: justify;
}


.blindimage img {
    border-radius: 15px;
}
span.card-product__price-disclaimer {   
    font-size: 12px;
	color: #8c8c95;
}
.shuttertext {
    text-align: justify;
    font-size: 13px;
    margin-top: 10px;
}
.colorcont {
    margin-top: 10px;
}
	
@media screen and (max-width: 560px) {
	.card-grid__item {
    width: calc(100% - 2px);
    margin: 15px 2px 2px 2px;
    padding-top: 5px;
    padding-bottom: 5px;
    padding-left: 3px;
    padding-right: 3px;
  
}
.shutterslider {
   
    display: none;
}


}
@media (min-width:600px) and (max-width:850px) {
    	.card-grid__item {
    width: calc(55.333% - 79px);
    margin: 15px 20px 20px 20px;
    padding-top: 5px;
    padding-bottom: 5px;
    padding-left: 3px;
    padding-right: 3px;
  
}
.shutterslider {
   
    display: none;
}

}

</style>