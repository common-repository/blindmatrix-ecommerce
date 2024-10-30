<?php 
/*$get_productlist = CallAPI("GET", $post=array("mode"=>"productlist"));
$product_lists=$get_productlist->product_list;*/

$get_productlist = get_option('productlist', true);
$product_lists=$get_productlist->product_list;

$currency = get_woocommerce_currency();

?>
<div class="row row-small align-center commonfont quickqoute">
	
<div class="col medium-12 large-12">
    <div class="row content-row mb-10 ">
        <div class="product-gallery col large-8" style="margin: auto; position: relative;padding: 0!important;">
            <div class="cuspricevalue">
                <table class="variations" cellspacing="0">
                   <tbody>
                      <tr>
                         <td class="label" colspan="2">
							<p class="quickqoute_text_cont">It’s easy to get a quote for custom-made Blinds. Enter your width and height dimensions below to see how much you can save!</p>
						 </td>
					 </tr>
					  <tr>
						 <td class="label" colspan="2">
							<h3 class="messubtitle">Please enter your measurements in <span id="unit_type">mm</span>:</h3>
							<span class="quickqoute-radio wpcf7-form-control-wrap radio-726">
								<span class="wpcf7-form-control wpcf7-radio">
								<span class="wpcf7-list-item first">
								<label><input name="unit" id="unit_0" class="js-unit" value="mm" checked="" type="radio"><span class="wpcf7-list-item-label">mm</span></label>
								</span>
								<span class="wpcf7-list-item">
								<label><input name="unit" id="unit_1" class="js-unit" value="cm" type="radio"><span class="wpcf7-list-item-label">cm</span></label>
								</span>
								<span class="wpcf7-list-item last">
								<label><input name="unit" id="unit_2" class="js-unit" value="inch" type="radio"><span class="wpcf7-list-item-label">inches</span></label>
								</span>
							</span>
                            </span>
                         </td>
                      </tr>
                	    <tr id="bm_product">
                         <td class="label">
                            <label  class="label_table_quotes" for="product">
                            <img class="lbl-icon" src="/wp-content/plugins/blindmatrix-ecommerce/assets/image/right-arrow.gif">
                            Select Blinds/Shutters<font color="red">*</font></label>
                         </td>
                         <td class="value">
                            <select name="product_id" id="product_id" onChange="getcolorcategories();">
                            
                                <option value="">Select Blinds or Shutters</option>
                                
                                <?php if(count($product_lists) > 0): ?>
                                <optgroup label="Shop our Blinds">
                                <?php foreach($product_lists as $product_list):?>
                                <?php
                                $productname_arr = explode("(", $product_list->productname);
    	                        $get_productname = trim($productname_arr[0]);
                                ?>
                                <option data-type="0" value="<?php echo($product_list->product_no); ?>"><?php echo($get_productname); ?></option>
                                <?php endforeach;?>
                                </optgroup>
                                <?php endif;?>
                                
                                <?php if(count($get_productlist->shutter_product_list) > 0): ?>
                                <optgroup label="Shop our Shutters">
                                <?php foreach ($get_productlist->shutter_product_list as $shutter_product_list): ?>
                                <?php if(count($shutter_product_list->GetShutterProductTypeList) > 0): ?>
                                <?php foreach ($shutter_product_list->GetShutterProductTypeList as $GetShutterProductTypeList): ?>
                                <option data-type="4" value="<?php echo $GetShutterProductTypeList->parameterTypeId; ?>"><?php echo $GetShutterProductTypeList->productTypeSubName; ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                                <?php endforeach;?>
                                </optgroup>
                                <?php endif;?>
                            
                            </select>
                            <div class="clear"></div>
                            <span id="errmsg_width" data-text-color="alert" class="is-small"></span>
                         </td>
                      </tr>
                    <tr class="shutter_style_container shutterdiv" style="display:none;">
                        <td class="label">
                            <label class="label_table_quotes" for="Colour">
                            <img class="lbl-icon" src="/wp-content/plugins/blindmatrix-ecommerce/assets/image/right-arrow.gif">
                            Shutter Style <font color="red">*</font>
                            </label>
                        </td>
                        <td class="value">
                            <select name="shutter_style" id="shutter_style" class="">
                                <option value="">Select the Shutter Style</option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr class="producttype_container blindsdiv" >
                         <td class="label">
                            <label class="label_table_quotes" for="Colour">
                            <img class="lbl-icon" src="/wp-content/plugins/blindmatrix-ecommerce/assets/image/right-arrow.gif">
                            Product Type
                            </label>
                         </td>
						 <td class="value">
                            <select name="set_producttype" id="set_producttype" class="">
							<option value="">Select the Product Type </option>
							</select>
						</td>
					</tr>
                    
                    <tr class="colour_category_container blindsdiv" >
                         <td class="label">
                            <label class="label_table_quotes" for="Colour">
                            <img class="lbl-icon" src="/wp-content/plugins/blindmatrix-ecommerce/assets/image/right-arrow.gif">
                            Colour/Material
                            </label>
                         </td>
						 <td class="value">
                            <select name="colour_category" id="colour_category" class="">
							    <option value="">Select the Colour/Material </option>
							</select>
						</td>
						</tr>
                    
                    <tr class="blindsdiv">
                         <td class="label">
                            <label class="label_table_quotes" for="Width">
                            <img class="lbl-icon" src="/wp-content/plugins/blindmatrix-ecommerce/assets/image/right-arrow.gif">
                            Width <font color="red">*</font>
                            </label>
                         </td>
                         <td class="value">
                            <input name="width" id="width" onkeyup="checkNumeric(event,this);" onkeydown="checkNumeric(event,this);" step="1" class="" autocomplete="off" type="text">
                            <select name="widthfraction" id="widthfraction"  style="display:none;" class="">
                               <option value="">0</option>
                               <option value="1">1/8</option>
                               <option value="2">1/4</option>
                               <option value="3">3/8</option>
                               <option value="4">1/2</option>
                               <option value="5">5/8</option>
                               <option value="6">3/4</option>
                               <option value="7">7/8</option>
                            </select>
                            <div class="clear"></div>
                            <span id="errmsg_width" data-text-color="alert" class="is-small"></span>
                         </td>
                      </tr>
                    <tr class="blindsdiv">
                         <td class="label">
                            <label  class="label_table_quotes" for="Length">
                            <img class="lbl-icon" src="/wp-content/plugins/blindmatrix-ecommerce/assets/image/right-arrow.gif">
                            Height <font color="red">*</font>
                            </label>
                         </td>
                         <td class="value">
                            <input  name="drope" id="drope" onkeyup="checkNumeric(event,this);" onkeydown="checkNumeric(event,this);" step="1" class="" autocomplete="off" type="text">
                            <select name="dropfraction" id="dropfraction"  style="display:none;" class="">
                               <option value="">0</option>
                               <option value="1">1/8</option>
                               <option value="2">1/4</option>
                               <option value="3">3/8</option>
                               <option value="4">1/2</option>
                               <option value="5">5/8</option>
                               <option value="6">3/4</option>
                               <option value="7">7/8</option>
                            </select>
                            <div class="clear"></div>
                            <span id="errmsg_drop" data-text-color="alert" class="is-small"></span>
                         </td>
                     </tr>
                      <tr>
                         <td colspan="2" style="text-align:center">
                            <button onClick="get_quote()"; type="button" id="calculateprice" class="single_add_to_cart_button button alt no-margin" style="background-color: #F49929;border-radius: 2em;">Get Quote</button>
							<span style="display: none;" class="loading-spin large inner_box"></span>
						 </td>
                      </tr>
                   </tbody>
                </table>
            </div>
            <div id="coverspin" style="display: none;"></div>
        </div>
    </div>

    <div class="col-inner">
        
        <div class="shop-page-title category-page-title page-title" id="quick_quote_div" style="display:none;">
        	<div class="page-title-inner flex-row  medium-flex-wrap container quick-quote-text-container" style="padding-top: 0px;">
        		<div class="flex-col flex-grow medium-text-center">
        			<div class="is-large">
        				<nav class="woocommerce-breadcrumb breadcrumbs uppercase">
        					<span>Quick quote</span> 
        					<span class="divider">/</span>
        					<span id="searchquickquote"></span>
        				</nav>
        			</div>
        		</div>
        	</div>
        </div>
        
        <div class="products row align-middle align-center ml-0" id="row-product-list"></div>
        <div style="display: none;" class="loading-spin large page"></div>
        <div class="container">
			<nav class="woocommerce-pagination pagination_div"></nav>
		</div>
    </div>

</div>
</div>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">

var page = 1;
var per_page = 16;
jQuery.total_pages = 0;
var scroll_enabled;
var search_type = 'color';
var currency = '<?=$currency;?>';

jQuery(document).ready(function () {

	jQuery('input[type=radio][name=unit]').change(function() {
	    
	    var widthTmp = jQuery('#width').val();
		var dropeTmp = jQuery('#drope').val();
		
		if(widthTmp == '')
		{
			widthTmp = 0;
		}
		if(dropeTmp == '')
		{
			dropeTmp = 0;
		}

		jQuery('#unit_type').html(this.value);
		if (this.value == 'cm') {
			jQuery("#width,#drope").css({"width":"100%"});
			jQuery('#widthfraction').hide();
			jQuery('#dropfraction').hide();
		}
		else if (this.value == 'mm') {
		    if(widthTmp > 0)  jQuery('#width').val(parseInt(widthTmp));
			if(dropeTmp > 0)  jQuery('#drope').val(parseInt(dropeTmp));
			jQuery("#width,#drope").css({"width":"100%"});
			jQuery('#widthfraction').hide();
			jQuery('#dropfraction').hide();
		}
		else if (this.value == 'inch') {
		    if(widthTmp > 0)  jQuery('#width').val(parseInt(widthTmp));
			if(dropeTmp > 0)  jQuery('#drope').val(parseInt(dropeTmp));
			jQuery('#widthfraction').show();
			jQuery('#dropfraction').show();
			jQuery("#width,#drope").css({"width":"75%","float":"left"});
			jQuery("#widthfraction,#dropfraction").css({"width":"25%"});
			jQuery('#unit_type').html('inches');
		}
	});
});

function getcolorcategories(){
    var productcode = jQuery('#product_id option:selected').val();
    var blindstype = jQuery('#product_id option:selected').attr('data-type');

    jQuery('.shutterdiv').hide();
    jQuery('.blindsdiv').show();
    if(blindstype == 4){
        jQuery('.blindsdiv').hide();
        jQuery('.shutterdiv').show();
    }
    jQuery('#coverspin').show();
    jQuery.ajax(
	{
		url     : ajaxurl,
		data    : {mode:'get_quick_quote_colorcategories',action:'get_quick_quote_colorcategories',productcode:productcode,blindstype:blindstype},
		type    : "POST",
		dataType: 'JSON',
		success: function(response){
		    jQuery('#coverspin').hide();
		    if(blindstype == 4){
                jQuery(".shutter_style_container #shutter_style").empty();
                jQuery(".shutter_style_container #shutter_style").append('<option value="">Select the Shutter Style </option>');
                jQuery.each(response.shutter_style,function(key, value)
    			{
    				jQuery(".shutter_style_container #shutter_style").append('<option data-price=' + value.itemPrice + ' value=' + value.parameterTypeSubSubId + '>' +  value.itemName + '</option>');
    			});
            }else{
    			//jQuery(".colour_category_container").show();
    			//console.log(response.colorcategories);
    			jQuery(".colour_category_container #colour_category").empty();
    			jQuery.each(response.colorcategories,function(key, value)
    			{
    				jQuery(".colour_category_container #colour_category").append('<option data-image=' +value.img_url + ' value=' + value.category_id + '>' +  value.category_name + '</option>');
    			});
    			
    			jQuery(".producttype_container #set_producttype").empty();
    			jQuery.each(response.producttypedetails,function(key, value)
    			{
    				jQuery(".producttype_container #set_producttype").append('<option value=' + value.producttypeid + '>' +  value.producttypename + '</option>');
    			});
    			
    			jQuery(".colour_category_container #colour_category").select2({
    				templateResult: formatState
    			});
			
			    function formatState (opt) {
				
				var optimage = jQuery(opt.element).attr('data-image'); 
				
					var $opt = jQuery(
					   '<span style="font-size: 12px;"><img style="margin-right:8px;" src="' + optimage + '" width="25px" /> ' + opt.text + '</span>'
					);
					return $opt;
    			}
            }
            
		}
	});
}

function get_quote(){
    var search_width = jQuery('#width').val();
    var search_drop = jQuery('#drope').val();
    var product_id = jQuery('#product_id option:selected').val();
    var blindstype = jQuery('#product_id option:selected').attr('data-type');
    var shutter_style = jQuery('#shutter_style option:selected').val();

	jQuery('#width').removeClass('wdalert');
	jQuery('#drope').removeClass('wdalert');
	jQuery('#product_id').removeClass('wdalert');
	jQuery('#shutter_style').removeClass('wdalert');
    
    if(blindstype == 4){
        if(shutter_style =='' || product_id ==''){
            if(product_id =='') jQuery('#product_id').addClass('wdalert');
        	else jQuery('#product_id').removeClass('wdalert');
        	if(shutter_style =='') jQuery('#shutter_style').addClass('wdalert');
        	else jQuery('#shutter_style').removeClass('wdalert');
        }else{
            jQuery('.loading-spin.inner_box').css('display','inline-block');
          
    		quick_quote_load(1);
        }
    }else{
    if(search_width =='' || search_drop =='' || product_id ==''){
		if(search_width =='') jQuery('#width').addClass('wdalert');
		else jQuery('#width').removeClass('wdalert');
		if(search_drop =='') jQuery('#drope').addClass('wdalert');
		else jQuery('#drope').removeClass('wdalert');
		if(product_id =='') jQuery('#product_id').addClass('wdalert');
		else jQuery('#product_id').removeClass('wdalert');
    }else{
    	//jQuery('#row-product-list').html('');
    	jQuery('.loading-spin.inner_box').css('display','inline-block');
      
		quick_quote_load(1);
	}
    }
} 

function pagination(page){
	jQuery('.loading-spin.page').css('display','block');
    quick_quote_load(page);
}

function quick_quote_load(page){
    var search_unitVal = jQuery('input[name=unit]:checked').val();
    var search_width = jQuery('#width').val();
    var search_drop = jQuery('#drope').val();
    
    if (search_unitVal == 'mm') {
        search_width = search_width;
        search_drop = search_drop;
    } else if (search_unitVal == 'cm') {
        search_width = (search_width * 10);
        search_drop = (search_drop * 10);
    } else if (search_unitVal == 'inch') {
        search_width = (search_width * 25.4);
        search_drop = (search_drop * 25.4);
    }
    if(currency == 'USD'){
    	search_width = jQuery('#width').val();
        search_drop = jQuery('#drope').val();
    }
    var url_search_width = jQuery('#width').val();
    var url_search_drop = jQuery('#drope').val();
    
    var search_text = jQuery('#colour_category option:selected').val();
    var productcode = jQuery('#product_id option:selected').val();
    var sel_producttype = jQuery('#set_producttype option:selected').val();
	var unitVal = jQuery('input[name=unit]:checked').val();
	var productname = jQuery('#product_id option:selected').text();
	
	var blindstype = jQuery('#product_id option:selected').attr('data-type');
	var shutter_style = jQuery('#shutter_style option:selected').val();
	var shutter_style_text = jQuery('#shutter_style option:selected').text();
	var shutter_style_price = jQuery('#shutter_style option:selected').attr('data-price');
	jQuery.ajax(
	{
		url     : ajaxurl,
		data    : {mode:'get_quick_quote',action:'get_quick_quote',productcode:productcode,search_text:search_text,search_type:search_type,url_search_width:url_search_width,url_search_drop:url_search_drop,search_width:search_width,search_drop:search_drop,search_unitVal:search_unitVal,page:page,per_page:per_page,blindstype:blindstype,shutter_style:shutter_style,shutter_style_price:shutter_style_price,productname:productname,sel_producttype:sel_producttype},
		type    : "POST",
		dataType: 'JSON',
		success: function(response){
		    jQuery('#quick_quote_div').show();
		    if(blindstype == 4){
			jQuery('#searchquickquote').html('<span data-text-color="secondary">Your '+productname+' Shutters in '+shutter_style_text+'</span>');
		    }else{
			jQuery('#searchquickquote').html(productname+' | <span data-text-color="secondary">'+url_search_width+' '+unitVal+'</span> W x <span data-text-color="secondary">'+url_search_drop+' '+unitVal+'</span> H');
		    }
			jQuery('.loading-spin').removeClass('centered');
			jQuery('.loading-spin').css('display','none');
			jQuery('#row-product-list').html('');
			jQuery('#row-product-list').append(response.html);
			jQuery('.pagination_div').html(response.pagination_html);
			jQuery.total_pages = response.total_pages;
			/*again enable loading on scroll... */
            scroll_enabled = true;
		}
	});
}

function checkNumeric(event,thisval) 
{
	
	var unitVal = jQuery('input[name=unit]:checked').val();
	var fraction = jQuery('#fraction').val();
	
	var key = event.charCode || event.keyCode || 0;
	
	if(unitVal == 'mm' || (unitVal =='inch' && fraction == 'on'))
	{
		if (event.shiftKey == true) {
			event.preventDefault();
        }
		
        if ((key >= 48 && key <= 57) || 
            (key >= 96 && key <= 105) || 
            key == 8 || key == 9 || key == 37 ||
            key == 39) {

        } else {
            event.preventDefault();
        }

        if(thisval.value.indexOf('.') !== -1)
            event.preventDefault(); 

	}else{
		if ( key == 46 || key == 8 || key == 9 ||key == 190 ||key == 110 || key == 27 || key == 13 || 
		// Allow: Ctrl+A
		(key == 65 && event.ctrlKey === true) || 
		// Allow: home, end, left, right
		(key >= 35 && key <= 39)) {
			// let it happen, don't do anything
			return;
		}
		else {
			// Ensure that it is a number and stop the keypress
			if (event.shiftKey || (key < 48 || key > 57) && (key < 96 || key > 105 )) {
				event.preventDefault();  
			}   
		}

	}
}
</script>

<style>
.pro_frame .flickity-prev-next-button {
	top: 20%;
}
#coverspin {
	position:absolute;
	width:100%;
	left:0;right:0;top:0;bottom:0;
	background-color: rgb(121 148 157 / 35%);
	z-index:9999;
	display:none;
}
#coverspin::after {
	content:'';
	display:block;
	position:absolute;
	left:48%;top:40%;
	width:40px;height:40px;
	border-style:solid;
	border-color:#00c2ff;
	border-top-color:transparent;
	border-width: 4px;
	border-radius:50%;
	-webkit-animation: spin .8s linear infinite;
	animation: spin .8s linear infinite;
}
span.select2-selection.select2-selection--single {
    border: 1px solid #ddd;
}
/*span.select2-search.select2-search--dropdown {
    padding: unset;
}*/
span.select2-dropdown.select2-dropdown--below {
    border: 1px solid #ddd;
}
.select2-container--default .select2-results__option--selected {
    background-color: #cfeef7;
}
.select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
    background-color: #cfeef7;
    color: rgba(17,17,17,.85);
}
.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid #ddd;
    height: 2em;
}
</style>
<noscript>
    <style>
        .woocommerce-product-gallery {
            opacity: 1 !important;
        }
    </style>
</noscript>