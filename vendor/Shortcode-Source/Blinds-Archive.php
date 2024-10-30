<?php
$blindmatrix_settings = get_option( 'option_blindmatrix_settings',true );
if(isset($blindmatrix_settings['menu_product_type']) && in_array( 'Blinds' , $blindmatrix_settings['menu_product_type'])){
global $product_category_page;
global $product_page;

$token = md5(rand(1000,9999)); //you can use any encryption
$_SESSION['token'] = $token; //store it as session variable
$site_url = site_url();

$productname = str_replace('-',' ',get_query_var("pc"));

$getallfilterproduct = get_option('productlist', true);
$product_list_array = $getallfilterproduct->product_list;
$id = array_search($productname, array_column($product_list_array, 'productname_lowercase'));

if($id === ''){
	echo('product is disabled from the application ');
	?>
  <div id="primary" class="content-area">
        		<main id="main" class="site-main container pt" role="main">
        			<section class="error-404 not-found mt mb">
        				<div class="bmcsscn row" style="margin:auto;">
                        	<?php echo do_shortcode( '[BlindMatrix source="BM-Blinds"] ' );?>
                        </div>
        			</section>
        		</main>
        	</div>
<?php	

}else{
//$resprocode = CallAPI("GET", $post=array("mode"=>"getproductcode", "productname"=>$productname));
$productcode = $product_list_array[$id]->product_no;
$product_description = $product_list_array[$id]->productdescription;
$product_imagepath = $product_list_array[$id]->imagepath;
$productcategory = $product_list_array[$id]->productcategory;

if( $productcategory == "Create no sub sub parameter"){
	$addcssnosubsub = "display:none;";
}else{
	$addcssnosubsub = "";
}

$header_tag = $product_list_array[$id]->header_tag;
if($header_tag != ''){
	$heading =	$header_tag;
}else{
	$heading = 'h1';
}
//$response = CallAPI("GET", $post=array("mode"=>"getproductdetail", "productcode"=>$productcode));
//$product_details = $response->product_details;

$res = CallAPI("GET", $post=array("mode"=>"getcategorydetails", "productcode"=>$productcode));
//$res = $product_list_array[$id]->getcategorydetails;

$categoryidarray = array('001');
if (count($res->maincategorydetails) > 0){
	foreach($res->maincategorydetails as $maincategorydetails){
		$categoryidarray[] = $maincategorydetails->category_id;
	}
}

?>

<div class="bmcsscn row row-small align-center commonfont listpage" style="margin:auto;">
	
	<div class="col medium-12 large-12">
	    
	    <?php if($productname == ''):?>
	        
	        <div id="primary" class="content-area">
        		<main id="main" class="site-main container pt" role="main">
        			<section class="error-404 not-found mt mb">
        				<div class="bmcsscn row" style="margin:auto;">
                        	<?php echo do_shortcode( '[BlindMatrix source="BM-Products"] ' );?>
                        </div>
        			</section>
        		</main>
        	</div>
	    
	    <?php else:?>
	    
	    <?php
	    
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
        ?>
	
	<!-- <a style="margin: 5px 0;font-size: 12px;" href="<?php bloginfo('url'); ?>" target="_self" class="button secondary is-link is-smaller lowercase">
		<i style="top: -1px;" class="icon-angle-left"></i>  <span>Back to Home</span>
	</a> -->
	<div class="bmcsscn products row row-small" style="margin: auto;">
			<div class="box has-hover   has-hover box-text-bottom">
				<?php if($product_imagepath != ''): ?>
				<div class="box-image" style="display:none;">
					<div class="">
						<img src="<?php echo $product_imagepath; ?>" class="attachment- size-" alt="" sizes="(max-width: 3826px) 100vw, 3826px" width="3826" height="4000">
					</div>
				</div><!-- box-image -->
				<?php endif; ?>

				<div class="box-text text-center">
					<div class="box-text-inner">
						<<?php echo($heading); ?> class="uppercase" style="margin-bottom: 15px;"><?php $productname_arr = explode("(", $productname); echo trim($productname_arr[0]); ?><span class="searchtext"></span></<?php echo($heading); ?>>
						<p class="blindslistdescription"><?php echo truncate($product_description, 500); ?></p>
					</div><!-- box-text-inner -->
				</div><!-- box-text -->
			</div>
		</div>
		<div class="shop-page-title category-page-title page-title category-page-title-container">
			<div class="page-title-inner flex-row  medium-flex-wrap nws" style="padding-top: 0px;">
			
			 
				<div   <?php if (count($res->maincategorydetails) == 0){ ?> style="visibility:hidden;" <?php } ?> class="first-col" data-filter="open" data-click="">
					<span class ="" >Filter By<i class="fa fab fa-minus"></i></span>
				</div>
				
				<div class="sce-col">
					
				        <span class="swatch_thumbnails_container">
    						<label style="display: inline-block;" class="switch_label">Fabric View</label>
    						<label style="display: inline-block;" class="switch">
    						  <input type="checkbox" id="Swatch_Thumbnails">
    						  <span class="bm_slider round"></span>
    						</label>
						</span>
					<span style="<?php echo($addcssnosubsub); ?>" class="fabriclist_listby_container">
						<div class="btn-container nws">
							<label class="btn-color-mode-switch">
								<input type="checkbox"  onchange="fabriclist_listby(this);" id="listbypflist"  value="1">
								<label for="listbypflist" data-on="List by fabric" data-off="View all products" class="btn-color-mode-switch-inner"></label>
							</label>
						</div>
					</span>
					<div <?php if(!blindmatrix_check_premium()){  echo('style="display:none;"'); } ?> class="woocommerce-ordering hidemobile">
						<select name="orderby" class="orderby" onchange="fabriclist_sort(this.value);">
							<option value="">Default sorting</option>
							<option value="ASC">Price - Low to High</option>
							<option value="DESC">Price - High to Low</option>
							<option value="BESTSELLING">Best Selling</option>
							<option value="ATOZ">Alphabetical (A to Z)</option>
						</select>
					</div>
					<div class="filtertab-last custabchild nws">
						<span class="woocommerce-result-count"></span>
					</div>
					<?php

						$after = 'data-visible-after="true"';
						$class = 'show-for-medium';
						

						$custom_filter_text = get_theme_mod( 'category_filter_text' );
						$filter_text = $custom_filter_text ? $custom_filter_text : __( 'Filter By', 'woocommerce' );
						?>
					 <?php if (count($res->maincategorydetails) > 0){ ?> 
						<div style="text-align: center;" class="category-filtering category-filter-row <?php echo $class ?>">
							<a href="#" data-open="#shop-sidebar" <?php echo $after ?> data-pos="left" class="filter-button uppercase plain">
								<i class="icon-equalizer"></i>
								<strong><?php echo $filter_text ?></strong>
							</a>
						</div>
					 <?php } ?>
					<div class="woocommerce-ordering hideinrest" style="display:none;">
						<select name="orderby" class="orderby" onchange="fabriclist_sort(this.value);">
							<option value="">Default sorting</option>
							<option value="ASC">Price - Low to High</option>
							<option value="DESC">Price - High to Low</option>
							<option value="BESTSELLING">Best Selling</option>
							<option value="ATOZ">Alphabetical (A to Z)</option>
						</select>
					</div>
				</div>
		
			</div>
		</div>
		
		<div class="cointainer_product_list">
			<div class="filterconadd width220" style="transition: all 0.3s ease 0s;">
				<div id="shop-sidebar-home" class="sidebar-inner col-inner" >
				
					<div class="clear_all_filter"><a style="display:none;" onclick="clearAll();" ref="javascript:;">clear all</a></div>
					<div class="hide-for-medium">
				<div id="shop-sidebar" class="sidebar-inner col-inner">
						<div class="clear_all_filter mobile"><a style="display:none;" onclick="clearAll();" ref="javascript:;">clear all</a></div>
					<aside class="widget woocommerce widget_product_categories">
					<ul class="product-categories">
						
						   <?php if (count($res->maincategorydetails) > 0): ?>
							<?php foreach($res->maincategorydetails as $key=>$maincategorydetails): ?>
							<li id="tab_<?php echo $maincategorydetails->category_id; ?>" class="cat-item_cat cat-item-80 cat-parent has-child <?php if($key == 0){ echo('active'); } ?>" aria-expanded="false">
								<a href="javascript:;"><?php echo $maincategorydetails->category_name; ?>&nbsp;&nbsp;</a>
							<?php if (count($res->subcategorydetails) > 0): ?>
							<ul class="children">
							<?php foreach($res->subcategorydetails as $categorydetails): ?>
							<?php if($maincategorydetails->category_id == $categorydetails->parent_id): ?>
							<?php
								$maincategory_name = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $maincategorydetails->category_name)));
								$category_name = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $categorydetails->category_name)));
								$exp_val = isset($_GET[$maincategory_name]) ? explode(',',sanitize_text_field($_GET[$maincategory_name])):array();
								if(in_array(strtolower($category_name),$exp_val)){
									$checked ='checked';
								}else{
									$checked='';
								}
							?>
							<li class="cat-item color-menu-item-type-post_type">
								<input <?php echo $checked; ?> id="chk_<?php echo $categorydetails->category_id; ?>" name="chk" data-main-cat-id="<?php echo $maincategorydetails->category_id; ?>" data-main-cat-name="<?php echo $maincategory_name; ?>" data-slug-name="<?php echo $category_name; ?>" data-name="<?php echo $categorydetails->category_name; ?>" value="<?php echo $categorydetails->category_id; ?>" class="category_all" type="checkbox" style="display:none;">
								<a href="javascript:;" id="subcat_<?php echo $categorydetails->category_id; ?>" onclick="fabriclist_cat_sort('<?php echo $categorydetails->category_id; ?>','<?php echo $categorydetails->category_name; ?>');" style="padding: 0px;">
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
						<?php if(count($getallfilterproduct->product_list) > 0): ?>
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
			
			
				<div class="bmcsscn products row row-small large-columns-<?php if(isset($blindmatrix_settings['blinds_archive_layout'])){echo($blindmatrix_settings['blinds_archive_layout']); }else{echo('4'); } ?> medium-columns-3 small-columns-2" id="row-product-list" style="margin:auto;"></div>
				<div style="display: none;" class="loading-spin large"></div>
				<div class="container" style="margin: 1.5em 0;">
					<nav class="woocommerce-pagination pagination_div"></nav>
				</div>
			</div>
		</div>
        <?php endif;?>
			
	</div>
</div>

<input type="hidden" id="sel_sort"/>
<input type="hidden" id="sellistby"/>
<input type="hidden" id="sel_category"/>

<a id="Lightbox_errormsg" href="#errormsg" target="_self" class="button primary" style="display:none;"></a>
<div id="errormsg" class="lightbox-by-id lightbox-content lightbox-white mfp-hide" style="max-width:30%;padding:20px;text-align: center;"></div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script type="text/javascript">
var site_url = '<?=$site_url;?>';
var page = 1;
var per_page = 36;
var productcode = '<?=$productcode;?>';
var search_type = 'color';
jQuery.total_pages = 0;
var categoryidarray = <?php echo json_encode($categoryidarray); ?>;
var scroll_enabled;

jQuery(".category-page-title-container .first-col").click(function(){
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
});
window.onbeforeunload = function() {
    jQuery('#Swatch_Thumbnails').prop('checked', false);
    jQuery(".orderby option[value='']").attr('selected', true);
    jQuery(".listbypflist option[value='listbyproduct']").attr('selected', true);
};

jQuery(function() {
	
    jQuery('.loading-spin').css('display','block');
    fabriclist_load(page);
});

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
	//jQuery('#row-product-list').html('');
	jQuery('.loading-spin').css('display','block');
	jQuery('.loading-spin').addClass('centered');
	jQuery('.loading-spin').css('top','2%');
	jQuery('.cat-item').removeClass('current-cat');
	fabriclist_load(1);
    page = 1;
	jQuery.categoryId = '';
}

function fabriclist_cat_sort(categoryId,category_name){
	
    if (jQuery('#chk_'+categoryId).is(":checked")) {
        document.getElementById("chk_"+categoryId).checked = false;
    }else{
        document.getElementById("chk_"+categoryId).checked = true;
    }
    
	jQuery('.cusfiltertabs li').removeClass('filtertabactive');
	jQuery('.filtersubclass').hide();
	//if(jQuery.categoryId != categoryId){
		//jQuery('.filtersubclass .cat-item').removeClass('current-cat active');
		jQuery('.mfp-close').trigger( "click" );
		var id = jQuery(this).attr("id");
		jQuery('#subcat_'+categoryId).closest('li').addClass('current-cat active');
		jQuery('#shop_title').removeAttr("style");
		jQuery('#sel_category').val(categoryId);
		//jQuery('#row-product-list').html('');
		jQuery('.loading-spin').css('display','block');
		jQuery('.loading-spin').addClass('centered');
		jQuery('.loading-spin').css('top','2%');
		jQuery(".searchtext").html('');
		jQuery("#searchtext").html('');
		/*jQuery("#searchtext").html('<span class="divider">/</span>'+category_name);
		jQuery(".searchtext").html(' <span class="divider">/ </span>'+category_name);*/
		fabriclist_load(1);
		page = 1;
		jQuery.categoryId = categoryId;
	/*}else{
		clearAll();		
	}*/
}

function fabriclist_sort(sort){
	jQuery('#sel_sort').val(sort);
	//jQuery('#row-product-list').html('');
	jQuery('.loading-spin').css('display','block');
	jQuery('.loading-spin').addClass('centered');
	jQuery('.loading-spin').css('top','2%');
	fabriclist_load(1);
        page = 1;
}

/* function fabriclist_listby(listby){
	jQuery('#sellistby').val(listby);
	jQuery('.loading-spin').css('display','block');
	jQuery('.loading-spin').addClass('centered');
	jQuery('.loading-spin').css('top','2%');
	fabriclist_load(1);
    page = 1;
}
 */
 
 function fabriclist_listby(valthis){
	var filter_pos = jQuery(".category-page-title-container .first-col").attr('data-filter');
	if(valthis.checked) {
		jQuery(".category-page-title-container .first-col").css('visibility','hidden');
		jQuery(".category-page-title-container .category-filtering.category-filter-row.show-for-medium").hide();
	
		if(filter_pos == 'open'){
			jQuery(".category-page-title-container .first-col").trigger("click");
		}
		jQuery('.swatch_thumbnails_container').hide();
		jQuery('#sellistby').val('listbyfabric');
		
		jQuery(".category_all").each(function () {
			if (jQuery(this).is(":checked")) {
				document.getElementById(this.id).checked = false;
			}
		});
		jQuery('#sel_sort').val('');
		jQuery('#sel_category').val('');
		jQuery(".searchtext").html('');
		jQuery('.cat-item').removeClass('current-cat');
		jQuery.categoryId = '';
	}else{
		 <?php if (count($res->maincategorydetails) > 0){ ?> 
		jQuery(".category-page-title-container .first-col").css('visibility','visible');
		jQuery(".category-page-title-container .category-filtering.category-filter-row.show-for-medium").css('display','inline-block');

		if(filter_pos == 'close'){
			jQuery(".category-page-title-container .first-col").trigger("click");
		}
		 <?php } ?>
		jQuery('.swatch_thumbnails_container').show();
		jQuery('#sellistby').val('listbyproduct');
	}
	jQuery('.loading-spin').css('display','block');
	jQuery('.loading-spin').addClass('centered');
	jQuery('.loading-spin').css('top','2%');
	fabriclist_load(1);
    page = 1;
}

function pagination(page){
	jQuery('.loading-spin').css('display','block');
	//jQuery('#row-product-list').html('');
    fabriclist_load(page);
    jQuery("html, body").animate({ scrollTop: 10 }, "slow");
    return false;
}

function fabriclist_load(page){
	
    checkedcategoryname = [];
    categorycheckedElems = [];
    var prevmainCategorry='';
    var getpara='';
    jQuery(".category_all").each(function () {
        if (jQuery(this).is(":checked")) {
            categorycheckedElems.push(jQuery(this).attr("value")+'~'+jQuery(this).attr("data-main-cat-id"));
            checkedcategoryname.push(jQuery(this).attr("data-name"));
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
    
	var search_text = jQuery('#sel_category').val();
	
	<?php 
	if(blindmatrix_check_premium()){
	?>
		var sort = jQuery('#sel_sort').val();
	<?php	
	}else{ 
	?>
		var sort = 'BESTSELLING';
	<?php
	}
	?>
	var sellistby = jQuery('#sellistby').val();
	if(categorycheckedElems.length === 0 ){
		jQuery(".clear_all_filter a").hide();
	}else{
		jQuery(".clear_all_filter a").show();
	}
	jQuery.ajax(
	{
		url     :ajaxurl,
		data    : {mode:'fabriclist',action:'fabriclist',productcode:productcode,search_text:search_text,categoryarray:categorycheckedElems,search_type:search_type,sort:sort,sellistby:sellistby,page:page,per_page:per_page,token:'<?=$token; ?>'},
		type    : "POST",
		dataType: 'JSON',
		success: function(response){
		    //jQuery("ul.product-categories li:first .toggle").click();
		    if(checkedcategoryname != ''){
    		    checkedcategoryname.join(",");
    		    //jQuery("#searchtext").html('<span class="divider">/</span>'+checkedcategoryname);
    		    jQuery(".searchtext").html(' / <span class="divider" data-text-color="secondary">'+checkedcategoryname+'</span>');
		    }
		    
			jQuery('.loading-spin').removeClass('centered');
			jQuery('#row-product-list').html('');
			jQuery('.loading-spin').css('display','none');
			jQuery('.woocommerce-result-count').html(response.total_rows+' Items');
			jQuery('.pagination_div').html(response.pagination_html);
			jQuery('#row-product-list').append(response.html);
			jQuery.total_pages = response.total_pages;
			/*again enable loading on scroll... */
            scroll_enabled = true;
			jQuery('#Swatch_Thumbnails').prop('checked', false);
			setTimeout(function(){
			    setframeheight();
			}, 200);
		}
	});
}

function setframeheight(){
    var img = document.getElementsByClassName('imageid')[0];
    if(img != undefined){
    //or however you get a handle to the IMG
    var width = img.clientWidth;
    var height = img.clientHeight;
    //console.log(width+'--'+height);
    if(width > 0 && height > 0){
        jQuery('.product-img').css({"height":height,"width":width});
    }
    }
}

/*jQuery(window).bind('scroll', function() {
	if (scroll_enabled) {
		if(jQuery(window).scrollTop() >= (jQuery('#row-product-list').offset().top + jQuery('#row-product-list').outerHeight()-window.innerHeight)*1 && page <= jQuery.total_pages && jQuery.total_pages > 1){
			scroll_enabled = false;  
			jQuery('.loading-spin').css('display','block');
			if(page == 1) page=2;
			fabriclist_load(page);
			page++;
		}
	}
});*/

function filter_tab(categoryid){
	checkValue(categoryid,categoryidarray);
}
 
function checkValue(value,arr){
	for(var i=0; i<arr.length; i++){
		var name = arr[i];
		if(name == value){
			jQuery("#cuscolorsubclass_"+value).toggle();
			jQuery("#tab_"+value).addClass('filtertabactive');
			if ( jQuery("#cuscolorsubclass_"+value).css('display') == 'none' || jQuery("#cuscolorsubclass_"+value).css("visibility") == "hidden"){
				jQuery("#tab_"+value).removeClass('filtertabactive');
			}
		}else{
			jQuery("#cuscolorsubclass_"+name).hide();
			jQuery("#tab_"+name).removeClass('filtertabactive');
		}
	}
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
	 <?php if (count($res->maincategorydetails) == 0){ ?> 
 	var filter_pos = jQuery(".category-page-title-container .first-col").attr('data-filter');
	if(filter_pos == 'open'){
		jQuery(".category-page-title-container .first-col").trigger("click");
	}
 <?php } ?>
});

jQuery(window).scroll(function(){
  var sticky = jQuery('.filtersection'),
      scroll =  jQuery(window).scrollTop();

  if (scroll >= 300){
		sticky.addClass('headerfixed');
		if (categoryidarray !== undefined){
			for(var i=0; i<categoryidarray.length; i++){
				var name = categoryidarray[i];
				jQuery("#cuscolorsubclass_"+name).hide();
				jQuery("#tab_"+name).removeClass('filtertabactive');
			}
		}
	} 
	else{
		sticky.removeClass('headerfixed');
	}

});

jQuery(document).mouseup(function (e) {
	var filtersection = jQuery(".filtersection");
	if (!jQuery('.filtersection').is(e.target) && !filtersection.is(e.target) && filtersection.has(e.target).length == 0) {
		jQuery('.filtersubclassli').removeClass('filtertabactive');
		jQuery('.filtersubclass').hide();
	}
 });
 
jQuery('#Swatch_Thumbnails').click(function() {
    if (this.checked) {
        jQuery('.product-frame').hide(); // If checked enable item
        jQuery('.swatch-img').hide();
        jQuery('.product-backround').show();
    } else {
        jQuery('.product-frame').show(); // If checked disable item
        jQuery('.swatch-img').show();
        jQuery('.product-backround').hide();
    }
});

</script>
   <style>
            @media(min-width: 768px) {
                .frame_backgound {
                    background-size:100% calc(100% - 30px);
                }
            }

            @media(max-width: 767px) {
                .frame_backgound {
                    background-size:100% calc(100% - 10px);
                }
            }
        </style>
<?php 
}
}else{
	echo('Enable blinds in the settings to view the blinds product.');
}

?>
