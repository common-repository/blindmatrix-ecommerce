<?php
$blindmatrix_settings = get_option( 'option_blindmatrix_settings',true );
if(isset($blindmatrix_settings['menu_product_type']) && in_array( 'Curtains' , $blindmatrix_settings['menu_product_type'])){
	global $curtains_single_page;
	global $curtains_config;
	
	$domain_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
	$urls = parse_url($domain_link);
	$urls_path = isset($urls['path']) ? explode('/',$urls['path']):'';
	$urls_path_parameters = array_values(array_filter($urls_path));
	$fabric_pid = $urls_path_parameters[2];
	if($urls_path_parameters[3]){
		$url_parameterTypeId = $urls_path_parameters[3];
	}
	
	$producttypename = $urls_path_parameters[1];
	$producttypename_1 = $producttypename;
	$producttypename = str_replace('-',' ',$producttypename);
	
	$response = CallAPI("GET", $post=array("mode"=>"GetCurtainParameterTypeGroup", "parametertype"=>$producttypename_1));
	$getallfilterproduct = get_option('productlist', true);
	$product_list_array = $getallfilterproduct->curtain_product_list;
	$id = array_search($fabric_pid, array_column($product_list_array, 'productid'));
	
	$getcategorydetails = $product_list_array[$id]->getcategorydetails;
	
	
	$curtainparametertypegroup = $response->curtainparametertypegroup;
	$id = array_search(1, array_column($curtainparametertypegroup, 'defaultValue'));
	
	
	$producttypedescription = $curtainparametertypegroup[$id]->producttypedescription;
	$productTypeSubName = $curtainparametertypegroup[$id]->curtain_type;
	$producttype_material_imgurl = $curtainparametertypegroup[$id]->producttype_material_imgurl;
	$minprice = $curtainparametertypegroup[$id]->minprice;
	$productid = $curtainparametertypegroup[$id]->productid;
	
	?>
	<div class="row row-small align-center commonfont listpage">
		
		<div class="col medium-12 large-12">
			
		<div class="products row row-small" style="margin: auto;">
				<div class="box has-hover   has-hover box-text-bottom">
					
					<div class="box-text text-center">
						<div class="box-text-inner">
							<h1 class="uppercase" style="text-transform: capitalize;margin-bottom: 15px;"><?php echo $productTypeSubName  ?><span class="searchtext"></span></h1>
							<p class="blindslistdescription"><?php echo substr_replace($producttypedescription,"...",400); ?></p>
						</div><!-- box-text-inner -->
					</div><!-- box-text -->
				</div>
			</div>
	
	
			<div  class="shop-page-title category-page-title page-title category-page-title-container">
				<div class="page-title-inner flex-row  medium-flex-wrap nws" style="padding-top: 0px;">
					<div  style="width: 16.2%" class="first-col" data-filter="open" data-click="">
						<span class ="" >Filter By<i class="fa fab fa-minus" style="display:none"></i></span>
					</div>
					<div class="sce-col">
					<?php
							$layout = get_theme_mod( 'category_sidebar', 'left-sidebar' );
							
	
							$after = 'data-visible-after="true"';
							$class = 'show-for-medium';
							if ( 'off-canvas' === $layout ) {
								$after = '';
								$class = '';
							}
	
							$custom_filter_text = get_theme_mod( 'category_filter_text' );
							$filter_text = $custom_filter_text ? $custom_filter_text : __( 'Filter By', 'woocommerce' );
							?>
						
							<div style="text-align: center;" class="category-filtering category-filter-row ">
								<a href="#" data-open="#shop-sidebar" <?php echo $after ?> data-pos="left" class="filter-button uppercase plain"style=" display: inline-flex; align-items: center; ">
									<i class="icon-equalizer"></i>
									<strong><?php echo $filter_text ?></strong>
								</a>
							</div>
							
					</div>
				</div>
			</div>
			<?php 	
			/* curtain header type list mode
			$fabric_color = CallAPI("GET", $post=array("mode"=>"GetCurtainProductDetail", "parametertypeid"=>$fabric_ptypeid, "productid"=>$fabric_pid));
			*///print_r(json_encode($getcategorydetails->subcategorydetails));
			?>
			<div class="cointainer_product_list">
				<div class="filterconadd width220" style="width:200px; transition: all 0.3s ease 0s;">
					<div id="shop-sidebar-home" class="sidebar-inner col-inner" >
					
						<div class="clear_all_filter"><a style="display:none;" onclick="clearAll();" ref="javascript:;">clear all</a></div>
						<div class="hide-for-medium">
					<div id="shop-sidebar" class="sidebar-inner col-inner">
							<div class="clear_all_filter mobile"><a style="display:none;" onclick="clearAll();" ref="javascript:;">clear all</a></div>
						<aside class="widget woocommerce widget_product_categories">
						<ul class="product-categories">
							
							   <?php if (count($getcategorydetails->maincategorydetails) > 0): ?>
								<?php foreach($getcategorydetails->maincategorydetails as $key=>$maincategorydetails): ?>
								<li id="tab_<?php echo $maincategorydetails->category_id; ?>" class="cat-item_cat cat-item-80 cat-parent has-child <?php if($key == 0){ echo('active'); } ?>" aria-expanded="false">
									<a href="javascript:;"><?php echo $maincategorydetails->category_name; ?>&nbsp;&nbsp;</a>
								<?php if (count($getcategorydetails->subcategorydetails) > 0): ?>
								<ul class="children">
								<?php foreach($getcategorydetails->subcategorydetails as $categorydetails): ?>
								<?php if($maincategorydetails->category_id == $categorydetails->parent_id): ?>
								<?php
									$maincategory_name = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $maincategorydetails->category_name)));
									$category_name = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $categorydetails->category_name)));
									$exp_val = isset($_GET[$maincategory_name]) ? explode(',',$_GET[$maincategory_name]):array();
									if(in_array(strtolower($category_name),$exp_val)){
										$checked ='checked';
									}else{
										$checked='';
									}
								?>
								<li id="filter" class="cat-item color-menu-item-type-post_type" >
									<input <?php echo $checked; ?> id="chk_<?php echo $categorydetails->category_id; ?>" name="chk" data-main-cat-id="<?php echo $maincategorydetails->category_id; ?>" data-main-cat-name="<?php echo $maincategory_name; ?>" data-slug-name="<?php echo $category_name; ?>" data-name="<?php echo $categorydetails->category_name; ?>" value="<?php echo $categorydetails->category_id; ?>" class="category_all" type="checkbox" data-id="<?php echo $categorydetails->category_id; ?>" style="display:none;">
									<a href="javascript:;" id="subcat_<?php echo $categorydetails->category_id; ?>" onclick="fabriclist_cat_sort('<?php echo $categorydetails->category_id; ?>');" style="padding: 0px;">
										<?php if($categorydetails->imagepath != ''):?>
										<img src="<?php echo $categorydetails->imagepath; ?>" alt="<?php echo $categorydetails->category_name; ?>" title="<?php echo $categorydetails->category_name; ?>" style="box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);border-radius: 4px;">
							<?php endif; ?>
										&nbsp;&nbsp;&nbsp;<?php echo $categorydetails->category_name; ?>
									</a>
								</li>
									
								 <?php endif;?>
						<?php endforeach; ?>
								</ul>
						<?php endif;?>
								</li>
					<?php endforeach; ?>
					<?php endif; ?>
						  <li style="display:none;" class="cat-item cat-item-73 cat-parent has-child" aria-expanded="false">
							<a ref="javascript:;">Product</a><button class="toggle"></button>
							<?php if(count($getallfilterproduct->product_list) > 0): 
							
							?>
							<ul class="children">
								<?php foreach ($getallfilterproduct->product_list as $product_list): ?>
								<?php
								$productname_arr = explode("(", $product_list->productname);
								$get_productname = trim($productname_arr[0]);
								?>
								<li class="cat-item color-menu-item-type-post_type">
									<a href="<?php bloginfo('url'); ?>/<?php echo($product_page); ?>/<?php echo str_replace(' ','-',strtolower($get_productname)); ?>" id="subcat_<?php echo $categorydetails->category_id; ?>" style="padding: 0px;">
										<?php
										$product_icon = getproducticon(trim(strtolower(substr(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $productname_arr[0])), 0, 3))),'blinds_icon');
										/*$product_icon = getproducticon(trim(strtolower(substr(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '', $get_productname)), 0, 3))),'blinds');*/
										?>
										<img src="<?php echo $product_icon; ?>"  class="menu-image menu-image-title-after icon-test" alt="<?php echo $get_productname; ?>" title="<?php echo $get_productname; ?>" style="width:26px;">&nbsp;&nbsp;<?php echo $get_productname; ?>
									</a>
								</li>
								<?php endforeach; ?>
							</ul>
							  <?php endif; ?>
							</li>
							</ul>
							</aside>
						</div>
						</div>
					</div>
				</div>
				<div class="col-inner mt-half clearfix flex-1" style="transition: all 0.3s ease 0s;">
						<?php 
							foreach($curtainparametertypegroup as $key=>$value){ 
							$curtain_type = $value->curtain_type ;
							$frame = plugin_dir_url(__DIR__) . 'Shortcode-Source/image/curtains/single_frame/' . $curtain_type . '.webp';
						}?>
						<?php
						/*	 curtain header type button
				<div class="products row large-columns-4 medium-columns-3 small-columns-2" style="margin:auto;justify-content: center;" style="display:none;">
							
							<input id="fabric_type" data-type-name="<?php echo strtolower($lowerproductname);?>" data-name="<?php echo $value->productTypeSubName; ?>" value="<?php echo $value->parameterTypeId; ?>" class="fabric_type" type="redio" style="display:none;">								
							<a href="javascript:;" data-type-name="<?php echo strtolower($lowerproductname);?>"  value="<?php echo $value->parameterTypeId; ?>" onclick="changeheadertype(page);" class="button primary is-outline box-shadow-2 box-shadow-2-hover relatedproduct fabric_type <?php if($url_curtain_Type) { if ($url_curtain_Type == strtolower($lowerproductname)) {?>is-active <?php } }elseif ($key == 0 ){?> is-active <?php } ?>" >
							<?php echo($value->productTypeSubName) ?></a> 
										
					</div>
						<?php  */?>
					
					
					<div class="products row large-columns-4 medium-columns-3 small-columns-2" id="row-product-list" style=" margin:auto;">
	
								
					</div>
					<div style="display: none;" class="loading-spin large"></div>
					<div class="container" style="margin: 1.5em 0;">
						<nav class="woocommerce-pagination pagination_div"></nav>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<style>
	.fabric_type {
		font-size: .8em;
		margin: 0px 5px;
		border-radius: 5px;
	}
	.fabric_type.is-active {
		background-color: #002746;
		border-color: #002746;
		color: #fff;
	}
	img.product-frame.frame_backgound {
		background-size: 50%;
		background-position: center;
	}
	.product-small.box {
		box-shadow: 0 3px 6px -4px rgba(0,0,0,.16), 0 3px 6px rgba(0,0,0,.23);
	}
	@media (max-width: 768px){
	.cointainer_product_list .product-item-name {
		max-width: unset; 
		min-height: unset;
	}
	}
	.category-page-title-container {
		background-color: unset;
	}
	@media screen and (min-width: 941px){	
	.cointainer_product_list .col-inner.clearfix.flex-1 {
		margin-top: -3em;
	}
	.filterconadd.width220.width0+div {
		left: 9%;
	}
	.sce-col {
		display: none;
	}
	}
	</style>
	
	<script>
	
	var page = 1;
	var per_page = 24;
	jQuery.total_pages = 0;
	var test = <?php echo json_encode($getcategorydetails); ?>;
	jQuery(function($){
		jQuery('.loading-spin').css('display','block');
		jQuery('.loading-spin').addClass('centered');
		jQuery('.loading-spin').css('top','3%');
		fabriclist_load(page);
	});
	function pagination(page){
		jQuery('.loading-spin').css('display','block');
		jQuery('.loading-spin').addClass('centered');
		jQuery('.loading-spin').css('top','3%');
		//jQuery('#row-product-list').html('');
			   fabriclist_load(page);
			 
		jQuery("html, body").animate({ scrollTop: 10 }, "slow");
		return false;
	}
	
	
	//filter
	
	/* jQuery(".category-page-title-container .first-col").click(function(){
		jQuery(".filterconadd").toggleClass("width0");
		 var clicks = jQuery(".category-page-title-container .first-col").data('clicks');
		  if (clicks) {
			   jQuery(".category-page-title-container .first-col").attr('data-filter','open');
				jQuery(".first-col .fa.fab").removeClass('fa-plus');
				jQuery(".first-col .fa.fab").addClass('fa-minus');
				jQuery("#shop-sidebar-home").fadeOut();
		  } else {
			  jQuery(".category-page-title-container .first-col").attr('data-filter','close');
				  jQuery(".first-col .fa.fab").removeClass('fa-minus');
				jQuery(".first-col .fa.fab").addClass('fa-plus');
				jQuery("#shop-sidebar-home").fadeIn();
		  }
		jQuery(this).data("clicks", !clicks);
		jQuery("#shop-sidebar-home").toggleClass("visible");
	}); */
	
	
	function clearAll(){
		jQuery("#listbypflist").prop('checked', false);
		jQuery(".swatch_thumbnails_container").show();
		jQuery(".category_all").each(function () {
			if (jQuery(this).is(":checked")) {
				document.getElementById(this.id).checked = false;
			}
		});
		jQuery('.category_all').parents("li").removeClass('current-cat active');
		jQuery('#shop_title').css('color','#000');
		jQuery('#sel_sort').val('');
		jQuery(".orderby option[value='']").attr('selected', true);
		jQuery('#sellistby').val('');
		jQuery(".listbypflist option[value='listbyproduct']").attr('selected', true);
		jQuery('#sel_category').val('');
		jQuery(".searchtext").html('');
		jQuery("#searchtext").html('');
		jQuery('#row-product-list').html('');
		jQuery('.loading-spin').css('display','block');
		jQuery('.loading-spin').addClass('centered');
		jQuery('.loading-spin').css('top','3%');
		jQuery('.cat-item').removeClass('current-cat');
		fabriclist_load(1);
		page = 1;
		jQuery.categoryId = '';
	}
	
	function fabriclist_cat_sort(categoryId){
		
		if (jQuery('#chk_'+categoryId).is(":checked")) {
			document.getElementById("chk_"+categoryId).checked = false;
		}else{
			document.getElementById("chk_"+categoryId).checked = true;
		}
		
		jQuery('.cusfiltertabs li').removeClass('filtertabactive');
		jQuery('.filtersubclass').hide();
		
		jQuery('.mfp-close').trigger( "click" );
			jQuery('#subcat_'+categoryId).closest('li').addClass('current-cat active');
			jQuery('#shop_title').removeAttr("style");
			jQuery('#sel_category').val(categoryId);
			jQuery('.loading-spin').css('display','block');
			jQuery('.loading-spin').addClass('centered');
			jQuery('.loading-spin').css('top','5%');
			 jQuery(".searchtext").html('');
			 jQuery("#searchtext").html('');
			 
		fabriclist_load(1);
			page = 1;
			jQuery.categoryId = categoryId;
		/*}else{
			clearAll();		
		}*/
		
	}
	
	
	function fabriclist_load(page){
		
		 checkedcategoryname = [];
		categorycheckedElems = [];
		product_id = [];
		var prevmainCategorry='';
		var getpara='';
		jQuery(".category_all").each(function () {
			if (jQuery(this).is(":checked")) {
				categorycheckedElems.push(jQuery(this).attr("value")+'~'+jQuery(this).attr("data-main-cat-id"));
				checkedcategoryname.push(jQuery(this).attr("data-name"));
				product_id.push(jQuery(this).attr("value"));
				jQuery(this).parents("li.cat-item").addClass('current-cat active');
				
				var datatype = jQuery(this).attr("data-main-cat-id");
				var maincategoryname = jQuery(this).attr("data-main-cat-name");
				var categoryname = jQuery(this).attr("data-slug-name");
	
				if(prevmainCategorry != maincategoryname){
					prevmainCategorry = maincategoryname;
					getpara += '~~'+prevmainCategorry+'=';
				}
				getpara += categoryname+',';
				
			}else{
				jQuery(this).parents("li.cat-item").removeClass('current-cat active');
			}
		});
		var getpara = getpara.substring(2);
		var getpara_exp = getpara.split('~~');
		var getpara_arr=[];
		getpara_exp.forEach((value, index) => {
			var strVal = value.replace(/,(\s+)?$/, '');
			getpara_arr.push(strVal);
		});
		var getpara_join= getpara_arr.join('&')
		
		var currentURL = window.location.protocol + "//" + window.location.host + window.location.pathname;
		if(getpara_join != ''){
			window.history.pushState({ path: currentURL }, '', currentURL + '?'+getpara_join);
		}else{
			window.history.pushState({ path: currentURL }, '', currentURL);
		}
		if(categorycheckedElems.length === 0 ){
			jQuery(".clear_all_filter a").hide();
		}else{
			jQuery(".clear_all_filter a").show();
		}
		
		var url_curtains_config = '<?=$curtains_config;?>';
		var url_producttypename = '<?=$producttypename_1;?>';
		var url_producttypeid = '<?=$url_parameterTypeId?>';
		var url_productid = '<?=$productid;?>';
		var url_frame = '<?=$frame;?>';
		var fabric_pid = '<?=$fabric_pid;?>';
		
		jQuery.ajax(
		{
			url     : ajaxurl,
			data    : {mode:'GetCurtainProductDetail',action:'GetCurtainProductDetail',url_curtains_config:url_curtains_config,url_producttypename:url_producttypename,url_producttypeid:url_producttypeid,url_productid:url_productid,url_frame:url_frame,page:page,per_page:per_page,product_id:product_id,fabric_pid:fabric_pid},
			type    : "POST",
			dataType: 'JSON',
			success: function(response){
			   
				 jQuery('.loading-spin').removeClass('centered');
				jQuery('#row-product-list').html('');
				jQuery('.loading-spin').css('display','none');
				jQuery('.pagination_div').html(response.pagination_html);
				jQuery('#row-product-list').append(response.html);
				jQuery.total_pages = response.total_pages;
				
			}
		});
		
	}
	
	jQuery( document ).ready(function() {
		jQuery( ".filtersubclass" ).hide();
		jQuery('.header-bottom').addClass('hide-for-sticky');
		
		jQuery(document).on('click','.widget .toggle',function(){
			if(jQuery(this).parent('li').hasClass('active')){
				jQuery(".widget .toggle").each(function( index ) {
					if(jQuery(this).parent('li').hasClass('active')){
						jQuery(this).parent('li').removeClass('active');
					};
				});
				jQuery(this).parent('li').addClass('active');
			}
		});
	});
	
	</script>
<?php }else{
	echo('Enable curtains in the settings to view the curtain products.');
} ?>