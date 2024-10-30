<?php
$blindmatrix_settings = get_option( 'option_blindmatrix_settings',true );
if(isset($blindmatrix_settings['menu_product_type']) && in_array( 'Curtains' , $blindmatrix_settings['menu_product_type'])){
    global $curtains_single_page;
    global $curtains_config;
    
    $domain_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
    $urls = parse_url($domain_link);
    $urls_path = isset($urls['path']) ? explode('/',$urls['path']):'';
    $urls_path_parameters = array_values(array_filter($urls_path));
    $url_curtaincolor_id = $urls_path_parameters[4];
    
    $producttypename = get_query_var("ptn");
    $productid = get_query_var("pid");
    $producttypeid = get_query_var("ptid");
    
    $response = CallAPI("GET", $post=array("mode"=>"GetCurtainProductDetail", "parametertypeid"=>$producttypeid, "productid"=>$productid));
    $curtains =array('double-pinch','double-pinch-buttoned','eyelet','goblet','goblet-buttoned','pencil-pleat','triple-pinch','triple-pinch-buttoned');
    
    if(strpos($urls_path_parameters[1],'-') != false){
         $parms = explode('-',$urls_path_parameters[1]);
         if(is_numeric($parms[0][0])){
             foreach($curtains as $curtain){
                    if (strpos($urls_path_parameters[1],$curtain) != false ) {
                        $varpath =  $curtain;
                    }
                }
         }else{
             $varpath =  $urls_path_parameters[1];
         }
    }else{
         $varpath =  $urls_path_parameters[1];
    }
    
    //$headertype_response = CallAPI("GET", $post=array("mode"=>"GetCurtainParameterTypeGroup", "parametertype"=>$varpath));
    
    
    $checkgetid = $producttypeid;
    $checkresponseid = $response->product_details->curtainparametertypedetails->parameterTypeId;
    
    $getallfilterproduct = get_option('productlist', true);
    $product_list_array = $getallfilterproduct->curtain_product_list;
    $id = array_search($productid, array_column($product_list_array, 'productid'));
    
    $getcategorydetails = $product_list_array[$id]->getcategorydetails;
    
    $parameterarray = $response->product_details->ProductsParameter;
    
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    
    <!--Formula calculation js files-->
    <script src="/wp-content/plugins/blindmatrix-ecommerce/view/js/jstat.min.js"></script> 
    <script src="/wp-content/plugins/blindmatrix-ecommerce/view/js/formula.min.js"></script>
    <link crossorigin="anonymous" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script crossorigin="anonymous" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <?php if(!bm_is_flatsome_theme_activated()): ?>
        <script src="<?php echo blindmatrix_get_plugin_url() . '/assets/js/flat.js'; ?>"></script>
    <?php endif; ?>
    
    
    <?php if($checkgetid == $checkresponseid):?>
    <form name="submitform" id="submitform"  class="tooltip-container variations_form cart" action="" method="post" enctype="multipart/form-data">
    
    
    <input type="hidden" name="blindstype" id="blindstype" value="<?php echo $response->product_details->blindstype; ?>">
    <input type="hidden" name="product_code" id="product_code" value="<?php echo $response->product_details->product_no; ?>">
    <input type="hidden" name="productid" id="productid" value="<?php echo $response->product_details->productid; ?>">
    <input type="hidden" name="productname" id="productname" value="<?php $productname_arr = explode("(", $response->product_details->productname); echo trim($productname_arr[0]); ?>">
    <input type="hidden" name="producttypeid" id="producttypeid" value="<?php echo $producttypeid; ?>">
    <input type="hidden" name="vendorid" id="vendorid" value="<?php echo $response->product_details->curtainparametertypedetails->vendorid;?>">
    <input type="hidden" name="imagepath" id="imagepath" value="<?php echo plugin_dir_url( __DIR__ ).'Shortcode-Source/image/blinds/no-image.jpg'; ?>">
    <input type="hidden" name="producttypename" id="producttypename" value="<?php echo $producttypename; ?>">
    <input type="hidden" name="fraction" id="fraction" value="<?php echo $response->product_details->fraction;?>">
    <input type="hidden" name="mode" id="mode" value="">
    <input type="hidden" name="company_name" id="company_name" value="<?php echo get_bloginfo( 'name' );?>">
    <input type="hidden" name="extra_offer" id="extra_offer" value="0">
    <input type="hidden" name="type" id="type" value="custom_add_cart_blind">
    <input type="hidden" name="action" id="action" value="blind_publish_process">
    <input type="hidden" name="fabricid" id="fabricid" value="">
    <input type="hidden" name="producttypesub" id="producttypesub">
    <input type="hidden" name="action" value="getcurtainprice">
    
    <div class="curtains-config-container">
    <div class="row align-center curtain -container" id="row-981420196" style="max-width: 1200px;">
        
        <div class="cusprodname">
            <a style="margin: 0;" href="/<?php echo($shutters_type_page); ?>" target="_self" class="button secondary is-link is-smaller lowercase">
                <i class="icon-angle-left"></i>  <span>All Styles</span>
            </a>
            &nbsp;
            <a style="margin: 0;" href="/<?php echo($curtains_single_page); ?>/<?php echo $response->product_details->curtainparametertypedetails->curtain_type;?>/<?php echo $urls_path_parameters[2]?>/<?php echo $urls_path_parameters[3]?>" target="_self" class="button secondary is-link is-smaller lowercase">
                <i class="icon-angle-left"></i>  <span>Back to <?php echo $response->product_details->curtainparametertypedetails->productTypeSubName;?></span>
            </a>
            <h1 style="margin: 0;" class="product-title product_title entry-title prodescprotitle prodescprotitle_curtain">Design your <?php echo $response->product_details->curtainparametertypedetails->productTypeSubName;?> curtains</h1>
        </div>
    
        <div id="configurator-root" style="position:relative;">
        <!-- <div class="curtain-whole-loader"><img style="width: 100px;" src="<php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/curtains_icons/Loading-GIF-on-Inspirationde.gif"></div> -->
            <div class="configurator curtain bordered cuspricevalue" style="visibility: hidden;height:80vh;">
                <div class="configurator-preview visible" style="position:relative; overflow:visible;">
                    <div class="configurator_preview_image" style="position: sticky; top: 0;">
                    <div id="curtainspreview" class="configurator-preview-image" style="top:0px;">
                        <div id="" class="" style="">
                            <div class="configurator-main-fabric"></div>
                            <div class="configurator-border-holder">
                                <div class="configurator-border-fabric top"></div>
                            </div>
                            <div id="cover-spin"></div>
                            <img crossorigin="anonymous" class="configurator-main-headertype" src="<?php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/header_type/pencil-pleat_1.webp" alt="curtain image" onload="javascript: jQuery('#cover-spin').hide();" style="border-radius: 10px;">
                            <p class="preview-desc curtains">  Diagram is for illustration only. </p>
                       </div>
                    </div>
                    </div>
                </div>
                <div class="configurator-controls product-info" style="position: relative;">
                <div class="curtain-loder" style="">
                    <img class="" src="<?php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/curtains_icons/curtain_loader.gif">
                </div>
                <div class="curtain_option_ui">
                    <div class="configurator-options" data-role="configurator-tabs" >
        <div class="tabs">  
            <div class="tab edit">
                <label class="tab-label" for="rd1"><span> <span class="config-count">1 </span>Choose your dimensions</span> <h3 class="config-heading edit"><i class="icon-pen-alt-fill"></i> Edit</h3></label>
                <div class="tab-content" id="rd1">	
                       <div class="configurator-option dimensions" style="text-align: center; padding: 10px;">
                            <span class="wpcf7-form-control-wrap radio-726">
                                <span class="wpcf7-form-control wpcf7-radio">
                                    <span class="wpcf7-list-item first">
                                        <input onclick="showorderdetails();" checked name="unit" id="unit_0" class="js-unit" value="mm" <?php echo $response->product_details->checkMm; ?> type="radio" style="display:inline;">
                                        <label for="unit_0">mm</label>
                                    </span>
                                    <span class="wpcf7-list-item">
                                        <input onclick="showorderdetails();" name="unit" id="unit_1" class="js-unit" value="cm" <?php echo $response->product_details->checkCm; ?> type="radio" style="display:inline;">
                                        <label for="unit_1">cm</label>
                                    </span>
                                    <span class="wpcf7-list-item last">
                                        <input onclick="showorderdetails();" name="unit" id="unit_2" class="js-unit" value="inch" <?php echo $response->product_details->checkInch; ?> type="radio" style="display:inline;">
                                        <label for="unit_2">inches</label>
                                    </span>
                                </span>
                            </span>
                        </div>
                        
                        <div class="configurator-option dimensions padding" >
                            <div class="configurator-option-content" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;width:100%;">
                                
                                <?php if(count($response->product_details->ProductsParameter) > 0):?>
                                <?php foreach($response->product_details->ProductsParameter as $ProductsParameter):?>
    
                                    <?php if($ProductsParameter->parameterListId == 14): ?>
                                    
                                        <?php if($ProductsParameter->ecommerce_show == 1): ?>
                                        <div style="width:50%;display: inline-block;" class="cpt-container <?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>">
                                            <div class="mobile_no_padding">
                                            <span id="errmsg_width" data-text-color="alert" class="is-small"></span>
                                            <div class="clear"></div>
                                            <h4 class="title">
                                                <span data-bind="text: title"><?php echo $ProductsParameter->parameterName; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?></span>
                                                <?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
                                                    <img style="width: 15px; margin: 12px 0px;" class="" src="<?php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/info.png"></button>
                                                    <div class="modal fade" id="info_<?php echo $ProductsParameter->parameterId;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel"><?php echo $ProductsParameter->parameterName; ?></h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                      <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <?php echo($ProductsParameter->ecommoreinfo); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                            </h4>	
                                            <input type="hidden" name="widthplaceholdertext" id="widthplaceholdertext" value="<?php echo $ProductsParameter->parameterName; ?>">
                                            <span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
                                            <input placeholder="<?php echo $ProductsParameter->parameterName; ?> (<?php echo $response->product_details->default_unit_for_order; ?>)" parameterName="<?php echo $ProductsParameter->parameterName; ?>" getparameterid="<?php echo $ProductsParameter->parameterId;?>" name="width" id="width" onkeyup="checkNumeric(event,this);" min="1" onkeydown="checkNumeric(event,this);" step="1" <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>class="mandatoryvalidate"<?php endif;?> autocomplete="off" type="number">
                                            <select name="widthfraction" id="widthfraction" onchange="showorderdetails();" style="<?php echo $response->product_details->fractionshow;?>" class="">
                                                <option value="">0</option>
                                                <option value="1">1/8</option>
                                                <option value="2">1/4</option>
                                                <option value="3">3/8</option>
                                                <option value="4">1/2</option>
                                                <option value="5">5/8</option>
                                                <option value="6">3/4</option>
                                                <option value="7">7/8</option>
                                            </select>
                                            <input name="widthparameterId" id="widthparameterId" value="<?php echo $ProductsParameter->parameterId; ?>" type="hidden">
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    
                                    <?php elseif($ProductsParameter->parameterListId == 15): ?>
                                        <?php if($ProductsParameter->ecommerce_show == 1): ?>
                                        <div style="width:49%;display: inline-block;" class="cpt-container <?php if($ProductsParameter->ecommerce_show1 != 1): ?>hideparameter<?php endif; ?>">
                                            <div class="mobile_no_padding">
                                            <span id="errmsg_drop" data-text-color="alert" class="is-small"></span>
                                            <div class="clear"></div>
                                            <h4 class="title">
                                                <span data-bind="text: title"><?php echo $ProductsParameter->parameterName; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?></span>
                                                <?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
                                                    <img style="width: 15px; margin: 12px 0px;" class="" src="<?php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/info.png"></button>
                                                    <div class="modal fade" id="info_<?php echo $ProductsParameter->parameterId;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel"><?php echo $ProductsParameter->parameterName; ?></h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                      <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <?php echo($ProductsParameter->ecommoreinfo); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </h4>	
                                            <input type="hidden" name="dropeplaceholdertext" id="dropeplaceholdertext" value="<?php echo $ProductsParameter->parameterName; ?>">
                                            <span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
                                            <input placeholder="<?php echo $ProductsParameter->parameterName; ?> (<?php echo $response->product_details->default_unit_for_order; ?>)" parameterName="<?php echo $ProductsParameter->parameterName; ?>" getparameterid="<?php echo $ProductsParameter->parameterId;?>" name="drope" id="drope" onkeyup="checkNumeric(event,this);" min="1" onkeydown="checkNumeric(event,this);" step="1" <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>class="mandatoryvalidate"<?php endif;?> autocomplete="off" type="number">
                                            <select name="dropfraction" id="dropfraction" onchange="showorderdetails();" style="<?php echo $response->product_details->fractionshow;?>" class="">
                                                <option value="">0</option>
                                                <option value="1">1/8</option>
                                                <option value="2">1/4</option>
                                                <option value="3">3/8</option>
                                                <option value="4">1/2</option>
                                                <option value="5">5/8</option>
                                                <option value="6">3/4</option>
                                                <option value="7">7/8</option>
                                            </select>
                                            <input name="dropeparameterId" id="dropeparameterId" value="<?php echo $ProductsParameter->parameterId; ?>" type="hidden">
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    
                                <?php endforeach; ?>
                                <?php endif; ?>
    
                            </div>    
                        </div>
                       
            </div>
          </div>
              
    <!--- Customise your curtain -->
          <div class="tab">
            <label class="tab-label" for="rd2"><span><span class="config-count">2 </span> Customise your curtain</span>  <h3 class="config-heading edit"><i class="icon-pen-alt-fill"></i> Edit</h3></label>
            <div class="tab-content" id="rd2">
            
               <?php if(count($response->product_details->ProductsParameter) > 0):?>
                        <?php foreach($response->product_details->ProductsParameter as $ProductsParameter):?>
                            <?php if($ProductsParameter->parameterListId == 10): ?>
                            <div class="configurator-option header-type" role="presentation" data-collapsible="true">
                                <div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
                                    <h4 class="title">
                                        <span data-bind="text: title"><?php echo $ProductsParameter->parameterName; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?></span>
                                  <?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
                                        <img style="width: 15px; margin: 12px 0px;" class="" src="<?php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/info.png"></button>
                                        <div class="modal fade" id="info_<?php echo $ProductsParameter->parameterId;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel"><?php echo $ProductsParameter->parameterName; ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php echo($ProductsParameter->ecommoreinfo); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                  </h4>
                                    <span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
                                </div>
                                <div class="configurator-option-content configurator-fabric-image" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
                                    <div class="option-grid header-type configurator-fabric-grid showorderdetails <?php if($ProductsParameter->orderitemmandatory == 1): ?>mandatory_validate<?php endif;?>">
                                        <?php if(count($ProductsParameter->ParameterTypevalue) > 0): ?>
                                        <?php
                                            $multiple = false;
                                            if('1' == $ProductsParameter->component_select_option){
                                                $multiple = true;
                                            }?>
                                            <select name="ProductsParametervalue[<?php echo $ProductsParameter->parameterId; ?>]" class="blindmatrix-dropdown-selection blindmatrix-select2 showorderdetails" <?php if($multiple){?>multiple="multiple" <?php } ?> >											   									
                                            <?php /*if(!$multiple): ?>	
                                            <option value="" data-parameter_id = "<?php echo $ProductsParameter->parameterId; ?>" type="select">Choose an option</option>
                                        <?php endif; */?>	
                                        <?php foreach($ProductsParameter->ParameterTypevalue as $ParameterTypevalue):?>
                                        
                                        <?php// foreach($headertype_response->curtainparametertypegroup as $curtainparametertypegroup):?>
                                        <?php// if($curtainparametertypegroup->parameterTypeId == $ParameterTypevalue->parameterTypeId):?>
                                        <?php
                                        if($ParameterTypevalue->imgurl != ''){
                                            $ParameterTypevalue->imgurl = $ParameterTypevalue->imgurl;
                                        }else{
                                            $ParameterTypevalue->imgurl = plugin_dir_url( __DIR__ ).'Shortcode-Source/image/blinds/no-image.jpg';
                                        }
                                        $replaced_values = array("(", ")");
                                        $product_type_sub_name = str_replace($replaced_values, '',  $ParameterTypevalue->productTypeSubName);
                                        $product_type_sub_name = str_replace(' ', '-',  $product_type_sub_name);
                                        ?>
                                        <?php
                                                    $selected = false;
                                                    if($checkgetid == $ParameterTypevalue->parameterTypeId){
                                                        $selected = true;
                                                    }
                                                    ?>
                                                    
                                        <option <?php if($selected){?> selected="selected" <?php };?>  type="select" id="header-type<?php echo $ParameterTypevalue->parameterTypeId; ?>" data-img="<?php echo $ParameterTypevalue->imgurl; ?>" data-value="<?php echo $ParameterTypevalue->parameterTypeId;?>" data-jsevent="changeheadertype" data-name="<?php echo $ParameterTypevalue->curtain_type; ?>" getvendorid="<?php echo $ParameterTypevalue->vendorid;?>" datatypename="<?php echo $ParameterTypevalue->productTypeSubName; ?>" data-type-name="<?php echo strtolower($product_type_sub_name); ?>" data-type-id="<?php echo $ParameterTypevalue->parameterTypeId; ?>" data-minprice="<?php echo $ParameterTypevalue->minprice; ?>"  getparametertypeallowance="<?php echo $ParameterTypevalue->parametertypeallowance;?>" getpricetableprice="<?php echo $ParameterTypevalue->pricetableprice;?>" getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" parametername="<?php echo $ProductsParameter->parameterName; ?>" getparametervalue="<?php echo $ParameterTypevalue->productTypeSubName;?>" get_parameter_value="<?php echo $ParameterTypevalue->parameterTypeId; ?>" getparameterid="<?php echo $ProductsParameter->parameterId;?>" type="radio" ><?php echo $ParameterTypevalue->productTypeSubName;?></option>
                                        
                                                <?php
                                                $display = false;
                                                if($display):
                                                ?>
                                        <input getparametertypeallowance="<?php echo $ParameterTypevalue->parametertypeallowance;?>" getpricetableprice="<?php echo $ParameterTypevalue->pricetableprice;?>" getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" parametername="<?php echo $ProductsParameter->parameterName; ?>" getparametervalue="<?php echo $ParameterTypevalue->productTypeSubName;?>" type="radio" name="header-type[<?php echo $ProductsParameter->parameterId;?>]" id="header-type<?php echo $ParameterTypevalue->parameterTypeId; ?>" value="<?php echo $ParameterTypevalue->parameterTypeId; ?>" get_parameter_value="<?php echo $ParameterTypevalue->parameterTypeId; ?>" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="header-type[<?php echo $ProductsParameter->parameterId;?>]">
                                        <label onclick="changeheadertype(this);showorderdetails();" data-name="<?php echo $ParameterTypevalue->curtain_type; ?>" getvendorid="<?php echo $ParameterTypevalue->vendorid;?>" datatypename="<?php echo $ParameterTypevalue->productTypeSubName; ?>" data-type-name="<?php echo strtolower($product_type_sub_name); ?>" data-type-id="<?php echo $ParameterTypevalue->parameterTypeId; ?>" data-minprice="<?php echo $ParameterTypevalue->minprice; ?>" class="option-item headertype configurator-fabric-item  <?php if($selected){?>active <?php } ?>" for="header-type<?php echo $ParameterTypevalue->parameterTypeId; ?>">
                                        <div class="option-item-image">
                                            <img class="fabric_img" style="visibility: hidden;" width="50" height="50" src="" data-img="<?php echo $ParameterTypevalue->imgurl;?>" alt="<?php echo $ParameterTypevalue->productTypeSubName;?>">
                                        </div>
                                        <div class="option-item-label config-label-text" data-bind="text: label"><?php echo $ParameterTypevalue->productTypeSubName;?></div>
                                        </label>
                                        <?php endif; ?>
                                        <?php// endif; ?>
                                        <?php// endforeach; ?>
                                        <?php endforeach; ?>
                                        </select>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php endforeach; ?>
                            <?php endif; ?>
            <div data-role="collapsible" class="configurator-option main-fabric" role="presentation" data-collapsible="true" style="display:block">
            <div style="display:flex;align-items: center;">
                           <div style="width: 25%; display: inline-block;" class="configurator-option-heading" data-role="title" role="tab" aria-selected="false" aria-expanded="true" tabindex="0">
                                <?php
                                    $fabricitemmandatory = 0;
                                    if(count($response->product_details->ProductsParameter) > 0){
                                    foreach($response->product_details->ProductsParameter as $ProductsParameter){
                                        if($ProductsParameter->ecommerce_show == 1){
                                            if( ($ProductsParameter->parameterListId == 16 || $ProductsParameter->parameterListId == 21)){
                                                if($ProductsParameter->orderitemmandatory == 1){
                                                    $fabricitemmandatory = 1;
                                                }
                                            }
                                        }
                                    }
                                    }
                                ?>
                                
                                <h4 class="title">
                                    <span data-bind="text: title">Colors	
                                    <?php if($fabricitemmandatory == 1): ?>
                                    <font color="red">*</font>
                                    <?php endif;?>
                                    </span>
                                </h4>
                                <span id="errormsg_fabricitemmandatory" data-text-color="alert" class="is-small errormsg"></span>
                                    
                            </div><div class="serach_input_color_contianer" style="position: relative;width: 74%;text-align: right; display: inline-block;">
                                  <input type="text" placeholder="Search" class="serach_input_color" id="serach_input_color" style=" margin: 0;width: 200px;padding-left: 35px;border-radius: 20px;font-size: 15px; font-weight: 500; color: black!important; background: #fff;">
                                  <i style="position: absolute;right: 170px;top: 10px;font-size: 14px;" class="icon-search"></i>
                            </div>
                            </div>
                            <div class="" data-role="content" role="tabpanel" aria-hidden="false">
                                <div data-bind="blockLoader: searchUpdating">
                                    
                                    <?php if (count($getcategorydetails->maincategorydetails) > 0): ?>
                                    <div class="accordion accordion-filter" rel="" style="display:none">
                                       <div class="accordion-item">
                                           <div class="selectedfilter"> </div>
                                          <a href="#" class="accordion-title plain" aria-expanded="true"><button class="toggle"><i style="font-weight: bold;" class="icon-angle-down"></i></button><span style="font-weight: bold;">Filter by Category</span></a>
                                          <div class="accordion-inner" style="display: none;">
                                            <div class="row row-collapse row-full-width align-equal" id="row-1247415022">
                                                <div id="col-149540596" class="col medium-4 small-12 large-4">
                                                    
                                                    
                                                    <?php foreach($getcategorydetails->maincategorydetails as $maincategorydetails): ?>
                                                    <div class="col-inner">
                                                        <h5><?php echo $maincategorydetails->category_name; ?></h5>
                                                        <?php if(count($getcategorydetails->subcategorydetails) > 0): ?>
                                                        <ul class="">
                                                            <?php foreach($getcategorydetails->subcategorydetails as $categorydetails): ?>
                                                            <?php if($maincategorydetails->category_id == $categorydetails->parent_id): ?>
                                                            <li>
                                                                <span style="display:none;"><?php print_r($categorydetails->categoryvalues); ?></span>
                                                                <input style="display:none;" type="checkbox" name="filter_curtain" id="main-filter-<?php echo $maincategorydetails->category_id; ?>-<?php echo $categorydetails->category_id; ?>" value="Anthology" >
                                                                <label  onclick="changefiltercurtain(this,'<?php print_r($categorydetails->categoryvalues);  ?>','<?php echo $categorydetails->category_id; ?>','<?php echo $categorydetails->category_name; ?>','main-filter-<?php echo $maincategorydetails->category_id; ?>-<?php echo $categorydetails->category_id; ?>');" for="main-filter-<?php echo $maincategorydetails->category_id; ?>-<?php echo $categorydetails->category_id; ?>" class="configurator-filter-item changefiltercurtain">
                                                                <p><?php echo $categorydetails->category_name; ?></p>
                                                                </label>
                                                            </li>
                                                            <?php endif;?>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                        <?php endif;?>
                                                    </div>
                                                    <?php endforeach; ?>
                                                    
                                               </div>
                                            </div>
                                          </div>
                                       </div>
                                    </div>
                                    <?php endif; ?>
                                
                                    <div class="configurator-fabric-grid showorderdetails <?php if($fabricitemmandatory == 1): ?>mandatory_validate<?php endif;?>">
                                    <div id="coverspin"></div>
                                    <?php if(count($response->product_details->ProductsParameter) > 0):?>
                                    <?php foreach($response->product_details->ProductsParameter as $ProductsParameter):?>
                                    
                                            <?php if($ProductsParameter->parameterListId == 16 && $ProductsParameter->ecommerce_show == 1): ?>
                                            <?php if(count($ProductsParameter->CurtainFabricvalue) > 0):?>
                                            <?php foreach($ProductsParameter->CurtainFabricvalue as $curtainfabric):?>
                                            <?php
                                            if($curtainfabric->imagepath != ''){
                                                $curtainfabric->imagepath = $curtainfabric->imagepath;
                                                $curtainfabric_data_img = $curtainfabric->imagepath;
                                            }else{
                                                $curtainfabric->imagepath = plugin_dir_url( __DIR__ ).'Shortcode-Source/image/blinds/no-image.jpg';
                                                $curtainfabric_data_img = '';
                                            }
                                            
                                            ?>
                                            
                                            <div data-text="<?php echo($curtainfabric->show_ecommerce); ?> <?php echo($curtainfabric->fabricname); ?> <?php echo($curtainfabric->colorname); ?>" class="configurator-fabric-image curtain-image-filter-box enableSearch" id="<?php echo($curtainfabric->fabricid.$curtainfabric->colorid); ?>">
                                                <input getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" getstandardformulavalue="Standard" getstandardformulaname="StandardContinuous" getscfabricname="<?php echo $curtainfabric->fabricname; ?>" getscpatternrepeat="<?php echo $curtainfabric->patternRepeat; ?>" getscweighted="<?php echo $curtainfabric->weighted; ?>" getscfabrictype="<?php echo $curtainfabric->fabric_type; ?>" getscpriceperMeter="<?php echo $curtainfabric->pricePerMeter; ?>" getscfabricWidth="<?php echo $curtainfabric->fabricWidth; ?>" parametername="<?php echo $ProductsParameter->parameterName; ?>" getparametervalue="<?php echo($curtainfabric->colorname); ?>" type="radio" name="scfabrictype" id="main-fabric-<?php echo($curtainfabric->colorid); ?>" value="<?php echo($curtainfabric->colorid); ?>" get_parameter_value="<?php echo($curtainfabric->colorid); ?>" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="scfabrictype"   >
                                                <label onclick="showorderdetails();" class="configurator-fabric-item mainfabric <?php if($curtainfabric->fabricid.$curtainfabric->colorid == $url_curtaincolor_id){
                                                ?> selected  default-fabric-type-selected<?php }?>" data-img="<?php echo($curtainfabric_data_img); ?>" for="main-fabric-<?php echo($curtainfabric->colorid); ?>" >
                                                    <div class="configurator-fabric-swatch">
                                                        <img class="fabric_img" style="visibility: hidden;" src="" data-img="<?php echo($curtainfabric->imagepath); ?>" alt="<?php echo($curtainfabric->colorname); ?>">
                                                    </div>
                                                    <div class="configurator-fabric-item-name">
                                                        <h4 data-bind="text: colour"><?php //echo($curtainfabric->fabricname); ?> <?php echo($curtainfabric->colorname); ?></h4>
                                                    </div>
                                                </label>
                                            </div>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                    
                                            <?php if($ProductsParameter->parameterListId == 21 && $ProductsParameter->ecommerce_show == 1): ?>
                                            <?php if(count($ProductsParameter->CurtainFabricvalue1) > 0):?>
                                            <?php foreach($ProductsParameter->CurtainFabricvalue1 as $curtainfabric):?>
                                            <?php
                                            if($curtainfabric->imagepath != ''){
                                                $curtainfabric->imagepath = $curtainfabric->imagepath;
                                                $curtainfabric_data_img = $curtainfabric->imagepath;
                                            }else{
                                                $curtainfabric->imagepath = plugin_dir_url( __DIR__ ).'Shortcode-Source/image/blinds/no-image.jpg';
                                                $curtainfabric_data_img = '';
                                            }
                                            ?>
                                            <div data-text="<?php echo($curtainfabric->colorname); ?>" class="configurator-fabric-image curtain-image-filter-box enableSearch" id="<?php echo($curtainfabric->fabricid.$curtainfabric->colorid); ?>">
                                                <input getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" getcontinuousformulavalue="Continuous" getcontinuousformulaname="StandardContinuous" getscfabricname="<?php echo $curtainfabric->fabricname; ?>" getscpriceperMeter="<?php echo $curtainfabric->pricePerMeter; ?>" getscfabricdrop="<?php echo $curtainfabric->fabricdrop; ?>" parametername="<?php echo $ProductsParameter->parameterName; ?>" getparametervalue="<?php echo($curtainfabric->colorname); ?>" type="radio" name="scfabrictype" id="main-fabric-<?php echo($curtainfabric->colorid); ?>" value="<?php echo($curtainfabric->colorid); ?>" get_parameter_value="<?php echo($curtainfabric->colorid); ?>" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="scfabrictype">
                                                <label onclick="showorderdetails();" class="configurator-fabric-item mainfabric <?php if($curtainfabric->fabricid.$curtainfabric->colorid == $url_curtaincolor_id){
                                                ?>selected default-fabric-type-selected<?php }?>" data-img="<?php echo($curtainfabric_data_img); ?>" for="main-fabric-<?php echo($curtainfabric->colorid); ?>"  >
                                                    <div class="configurator-fabric-swatch">
                                                        <img class="fabric_img" style="visibility: hidden;" src="" data-img="<?php echo($curtainfabric->imagepath); ?>" alt="<?php echo($curtainfabric->colorname); ?>">
                                                    </div>
                                                    <div class="configurator-fabric-item-name">
                                                        <h4 data-bind="text: colour"><?php echo($curtainfabric->colorname); ?></h4>
                                                    </div>
                                                </label>
                                            </div>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                    
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                    </div>
                                        <div style="display:none;text-align: center;font-weight: 500;text-transform: capitalize; color: rgb(187 26 26);" class="no_products_div"><?php echo 'No Fabric Type Found' ?></div>
                                </div>
                            </div>
                        </div>
            
                        <?php if(count($response->product_details->ProductsParameter) > 0):?>
                        <?php foreach($response->product_details->ProductsParameter as $ProductsParameter):?>
                              <?php if($ProductsParameter->parameterListId != 3 && $ProductsParameter->parameterListId != 17 && $ProductsParameter->parameterListId != 25 && $ProductsParameter->parameterListId != 27 && $ProductsParameter->parameterListId != 14 && $ProductsParameter->parameterListId != 15 && $ProductsParameter->parameterListId != 16 && $ProductsParameter->parameterListId != 21 && $ProductsParameter->parameterListId != 10 && $ProductsParameter->parameterListId != 18): ?>
                        <?php
                        
                        $class1 = '';
                        $class2 = 'configurator-fabric-image';
                        $class3 = 'dropdownparameter-'.$ProductsParameter->parameterId;
                        $click_function = 'dropdownparameter(this,'.$ProductsParameter->parameterId.');';
                        if(strpos(strtolower($ProductsParameter->parameterName), 'position') !== false){
                            $class1 = 'position';
                            $click_function = 'setposition(this);';
                            $class3 = 'headerposition';
                        }
                        else if(strpos(strtolower($ProductsParameter->parameterName), 'border') !== false){
                            $class1 = 'border';
                            $click_function = 'borderposition(this);';
                            $class3 = 'borderposition';
                        }
                        if(strpos(strtolower($ProductsParameter->parameterName), 'ratio') !== false){
                            $class1 = 'ratio';
                            $click_function = 'borderratio(this);';
                            $class3 = 'action primary borderratio';
                        }
                        if(strpos(strtolower($ProductsParameter->parameterName), 'fabric') !== false){
                            $class1 = 'main-fabric';
                            $class2 = 'configurator-option-content configurator-fabric-image';
                            $click_function = 'changebordercolor(this);';
                            $class3 = 'bordercolor';
                        }
                        ?>
                        <div id="<?php echo $ProductsParameter->parameterId; ?>" data-role="collapsible" class="configurator-option <?php echo $class1;?>" role="presentation" data-collapsible="true"  <?php if($class3 == 'bordercolor' ){?>style="display:block"<?php }?>>
                            <div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0" <?php if($class3 == 'bordercolor' ){?>style="width:unset; margin-bottom: 10px;"<?php }?>>
                                <h4 class="title <?php echo $ProductsParameter->parameterListId;?>">
                                    <span data-bind="text: title"><?php echo $ProductsParameter->parameterName; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?></span>
                                <?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
                                    <img style="width: 15px; margin: 12px 0px;" class="" src="<?php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/info.png"></button>
                                    <div class="modal fade" id="info_<?php echo $ProductsParameter->parameterId;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel"><?php echo $ProductsParameter->parameterName; ?></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php echo($ProductsParameter->ecommoreinfo); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </h4>
                                <span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
                            </div>
                            <div class="<?php echo $class2;?>" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
                                <div class="option-grid <?php echo $class1;?> configurator-fabric-grid showorderdetails <?php if($ProductsParameter->orderitemmandatory == 1): ?>mandatory_validate<?php endif;?>" <?php if($class3 != 'bordercolor' ){?>style="grid-template-columns: unset !important;"<?php }?>>
                                    
                                    <?php if($ProductsParameter->parameterListId == 2):?>
                                    
                                    <?php if(count($ProductsParameter->ProductsParametervalue) > 0): ?>
                                    <?php
                                    if($class3 != 'bordercolor' ){
                                                    $multiple = false;
                                                    if('1' == $ProductsParameter->component_select_option){
                                                        $multiple = true;
                                                    }
                                                ?>
                                    <select name="<?php echo $class3;?>" class="<?php echo $class3;?> blindmatrix-select2 single_select" <?php if($multiple){?>multiple="multiple" <?php } ?>>
                                        <?php if(!$multiple): ?>	
                                        <option value="" type="select" data-parameter_id = "<?php echo $ProductsParameter->parameterId; ?>">Choose an option</option>
                                    <?php endif; }?>	
                                    <?php foreach($ProductsParameter->ProductsParametervalue as $ProductsParametervalue):?>
                                    
                                    <?php
                                    $data_position = strtolower($ProductsParametervalue->text);
                                    $data_value = strtolower($ProductsParametervalue->text);
                                    $data_id = 'positionpair-'.$ProductsParametervalue->value;
                                    if (strpos(strtolower($ProductsParametervalue->text), 'left') !== false){
                                        $data_position = 'left';
                                        $data_value = 'single_left';
                                        $data_id = 'positionsingle_left';
                                    }
                                    if(strpos(strtolower($ProductsParameter->parameterName), 'border') !== false){
                                        $data_id = 'positionpair_'.$ProductsParametervalue->value;
                                    }
                                    if (strpos(strtolower($ProductsParametervalue->text), 'right') !== false){
                                        $data_position = 'right';
                                        $data_value = 'single_right';
                                        $data_id = 'positionsingle_right';
                                    }
                                    if (strpos(strtolower($ProductsParametervalue->text), 'pair') !== false || strpos(strtolower($ProductsParametervalue->text), 'center') !== false){
                                        $data_position = 'center';
                                        $data_value = 'pair';
                                        $data_id = 'positionpair_'.$ProductsParametervalue->value;
                                    }
                                    if(strpos(strtolower($ProductsParameter->parameterName), 'ratio') !== false){
                                        $exp_radio = explode('/',$ProductsParametervalue->text);
                                        $data_id = 'border_ratios'.trim($exp_radio[1]);
                                        $data_value = trim($exp_radio[1]);
                                        $data_position = trim($exp_radio[1]);
                                    }
                                    if(strpos(strtolower($ProductsParameter->parameterName), 'fabric') !== false){
                                        $data_id = 'main-border-fabric-'.$ProductsParametervalue->value;
                                        $data_value = $ProductsParameter->parameterId;
                                    }
                                    
                                    if($ProductsParametervalue->getEditableListimgurl != ''){
                                        $ProductsParameterimgurl = $ProductsParametervalue->getEditableListimgurl;
                                        $data_img = $ProductsParametervalue->getEditableListimgurl;
                                    }else{
                                        $ProductsParameterimgurl = plugin_dir_url( __DIR__ ).'Shortcode-Source/image/blinds/no-image.jpg';
                                        $data_img = '';
                                    }
                                    if($class3 != 'bordercolor' ){
                                    ?>
                                    <option class="configurator-fabric-item option-item <?php echo $class3;?> " data-jsevent="<?php echo str_replace('(this);','',$click_function); ?>" data-ratio="<?php echo $data_value;?>" data-position="<?php echo $data_position;?>" data-img="<?php echo $data_img; ?>" get_parameter_value="<?php echo $ProductsParametervalue->value; ?>" getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" parametername="<?php echo $ProductsParameter->parameterName; ?>" getparametervalue="<?php echo $ProductsParametervalue->text; ?>" name="parameter_name[<?php echo $ProductsParameter->parameterId;?>]" id="<?php echo $data_id;?>" type="select" value="<?php echo $data_value;?>" data-sub="<?php echo $data_value;?>" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="parameter_name[<?php echo $ProductsParameter->parameterId;?>]" ><?php echo $ProductsParametervalue->text; ?></option>
                                    <?php
                                    
                                            $display = false;
                                    }else{
                                            $display = true;
                                    }
                                                if($display):
                                                ?>
                                    <input get_parameter_value="<?php echo $ProductsParametervalue->value; ?>" getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" parametername="<?php echo $ProductsParameter->parameterName; ?>" getparametervalue="<?php echo $ProductsParametervalue->text; ?>" type="radio" name="parameter_name[<?php echo $ProductsParameter->parameterId;?>]" id="<?php echo $data_id;?>" value="<?php echo $data_value;?>" data-sub="<?php echo $data_value;?>" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="parameter_name[<?php echo $ProductsParameter->parameterId;?>]" <?php if($ProductsParametervalue->text == $ProductsParameter->defaultValue): ?> checked="checked" <?php endif; ?>>
                                    <label onclick="<?php echo $click_function;?>showorderdetails();" data-ratio="<?php echo $data_value;?>" data-position="<?php echo $data_position;?>" data-img="<?php echo $data_img; ?>" class="configurator-fabric-item option-item <?php echo $class3;?> <?php if($ProductsParametervalue->text == $ProductsParameter->defaultValue): ?> selected <?php endif; ?>" for="<?php echo $data_id;?>">
                                    <div class="option-item-image">
                                    <img class="fabric_img" style="visibility: hidden;" src="" data-img="<?php echo $ProductsParameterimgurl; ?>" alt="<?php echo $ProductsParametervalue->text; ?>">
                                    </div>
                                    <div class="option-item-label config-label-text" data-bind="text: label"><?php echo $ProductsParametervalue->text; ?></div>
                                    </label>
    
                                    <?php endif; ?>
                                    <?php endforeach; 
                                    if($class3 != 'bordercolor' ){ ?>
                                    </select>
                                    <?php } endif; ?>
                                    
                                    <?php elseif($ProductsParameter->parameterListId == 18): ?>
                                    
                                    <?php if(count($ProductsParameter->Componentvalue) > 0): ?>
                                    <?php
                                        $multiple = false;
                                        if('1' == $ProductsParameter->component_select_option){
                                            $multiple = true;
                                        }
                                    ?>
                                    <select name="Componentvalue[<?php echo $ProductsParameter->parameterId; ?>][]" id="maincomponent_<?php echo $ProductsParameter->parameterId; ?>" class="<?php echo $class3;?> blindmatrix-select2 maincomponentitem multi_select " <?php if($multiple){?>multiple="multiple" <?php } ?>>
                                        <?php if(!$multiple): ?>	
                                            <option value="" type="select" data-parameter_id = "<?php echo $ProductsParameter->parameterId; ?>">Choose an option</option>
                                        <?php endif; ?>	
                                    <?php foreach($ProductsParameter->Componentvalue as $Componentvalue):?>
                                    
                                    <?php
                                    $data_position = strtolower($Componentvalue->componentname);
                                    $data_value = strtolower($Componentvalue->componentname);
                                    $data_id = 'main-component-fabric-'.$Componentvalue->priceid;
                                    if (strpos(strtolower($Componentvalue->componentname), 'left') !== false){
                                        $data_position = 'left';
                                        $data_value = 'single_left';
                                        $data_id = 'positionsingle_left';
                                    }
                                    if(strpos(strtolower($ProductsParameter->parameterName), 'border') !== false){
                                        $data_id = 'positionpair_'.$ProductsParametervalue->value;
                                    }
                                    if (strpos(strtolower($Componentvalue->componentname), 'right') !== false){
                                        $data_position = 'right';
                                        $data_value = 'single_right';
                                        $data_id = 'positionsingle_right';
                                    }
                                    if (strpos(strtolower($Componentvalue->componentname), 'pair') !== false || strpos(strtolower($Componentvalue->componentname), 'center') !== false){
                                        $data_position = 'center';
                                        $data_value = 'pair';
                                        $data_id = 'positionpair';
                                    }
                                    if(strpos(strtolower($ProductsParameter->parameterName), 'ratio') !== false){
                                        $exp_radio = explode('/',$Componentvalue->componentname);
                                        $data_id = 'border_ratios'.trim($exp_radio[1]);
                                        $data_value = trim($exp_radio[1]);
                                        $data_position = trim($exp_radio[1]);
                                    }
                                    if(strpos(strtolower($ProductsParameter->parameterName), 'fabric') !== false){
                                        $data_id = 'main-border-fabric-'.$Componentvalue->priceid;
                                        $data_value = $Componentvalue->priceid;
                                    }
                                    
                                    $component_qty = 'ComponentQty';
                                    if(strpos(strtolower($ProductsParameter->parameterName), 'tiebacks') !== false){
                                        $component_qty = 'Tiebacks_Qty';
                                    }
                                    if(strpos(strtolower($ProductsParameter->parameterName), 'pillows') !== false){
                                        $component_qty = 'Pillows_Qty';
                                    }
                                    
                                    if($Componentvalue->getComponentimgurl != ''){
                                        $ProductsParameterimgurl = $Componentvalue->getComponentimgurl;
                                        $data_img = $Componentvalue->getComponentimgurl;
                                    }else{
                                        $ProductsParameterimgurl = plugin_dir_url( __DIR__ ).'Shortcode-Source/image/blinds/no-image.jpg';
                                        $data_img = '';
                                    }
                                    
                                    ?>
                                    <option  checkmultipleinput="<?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>1<?php else: ?>0<?php endif; ?>" checkmultiplelabel="<?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>1<?php else: ?>0<?php endif; ?>" data-jsevent="getComponentSubList" data-ratio="<?php echo $data_value;?>" data-parameterId="<?php echo $ProductsParameter->parameterId; ?>" data-position="<?php echo $data_position;?>" data-img="<?php echo $data_img; ?>" class="configurator-fabric-item maincomponentitem option-item <?php echo $class3;?> main_component_<?php echo $ProductsParameter->parameterId; ?> maincomponent_<?php echo $ProductsParameter->parameterId; ?> dropdownparameter-<?php echo $Componentvalue->priceid; ?>" for="<?php echo $data_id;?>" datasub="<?php echo $Componentvalue->parameterid."~~".$Componentvalue->priceid; ?>" get_component_qty_name="<?php echo $component_qty; ?>" get_component_qty="<?php echo $Componentvalue->qty; ?>" get_parameter_value="<?php echo $Componentvalue->priceid; ?>" getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" parametername="<?php echo $ProductsParameter->parameterName; ?>" getparametervalue="<?php echo $Componentvalue->componentname; ?>" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="Componentvalue[<?php echo $ProductsParameter->parameterId; ?>][]" id="<?php echo $data_id;?>" type="select" value="<?php echo $Componentvalue->priceid."~~".$Componentvalue->componentname; ?>" data-sub="<?php echo $data_value;?>" ><?php echo $Componentvalue->componentname; ?></option>
                                    <?php
                                                $display = false;
                                                if($display):
                                                ?>
                                    <input checkmultipleinput="<?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>1<?php else: ?>0<?php endif; ?>" get_component_qty_name="<?php echo $component_qty; ?>" get_component_qty="<?php echo $Componentvalue->qty; ?>" get_parameter_value="<?php echo $Componentvalue->priceid; ?>" getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" parametername="<?php echo $ProductsParameter->parameterName; ?>" getparametervalue="<?php echo $Componentvalue->componentname; ?>" onclick="<?php if($ProductsParameter->ecommerce_show1 == 1): ?>getComponentSubList(this,'<?php echo $ProductsParameter->parameterId; ?>');<?php endif; ?>" type="<?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>checkbox<?php else: ?>radio<?php endif; ?>" name="Componentvalue[<?php echo $ProductsParameter->parameterId; ?>][]" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="Componentvalue[<?php echo $ProductsParameter->parameterId; ?>][]" id="<?php echo $data_id;?>" value="<?php echo $Componentvalue->priceid."~~".$Componentvalue->componentname; ?>" datasub="<?php echo $Componentvalue->parameterid."~~".$Componentvalue->priceid; ?>" data-sub="<?php echo $data_value;?>" class="maincomponent_<?php echo $ProductsParameter->parameterId; ?>">
                                    <label checkmultiplelabel="<?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>1<?php else: ?>0<?php endif; ?>" onclick="<?php echo $click_function;?>showorderdetails();" data-ratio="<?php echo $data_value;?>" data-position="<?php echo $data_position;?>" data-img="<?php echo $data_img; ?>" class="configurator-fabric-item maincomponentitem option-item <?php echo $class3;?> main_component_<?php echo $ProductsParameter->parameterId; ?>" for="<?php echo $data_id;?>">
                                    <div class="option-item-image">
                                    <img class="fabric_img" style="visibility: hidden;" src="" data-img="<?php echo $ProductsParameterimgurl; ?>" alt="<?php echo $Componentvalue->componentname; ?>">
                                    </div>
                                    <div class="option-item-label config-label-text" data-bind="text: label"><?php echo $Componentvalue->componentname; ?></div>
                                    </label>
                                    <?php endif;?>
                                    <?php endforeach; ?>
                                    </select>
                                    <?php endif;?>
                                    
                                    <?php else: ?>
                                    
                                    <?php endif;?>
    
                                </div>
                            </div>
                        </div>	
                
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php endif; ?>
    
            </div>
          </div>	
          <!-- tab -->
                        <?php if(count($response->product_details->ProductsParameter) > 0):?>
                        <?php foreach($response->product_details->ProductsParameter as $ProductsParameter):?>
                        <?php if($ProductsParameter->ecommerce_show == 1): ?>
                        
                        <?php if($ProductsParameter->parameterListId != 3 && $ProductsParameter->parameterListId != 17 && $ProductsParameter->parameterListId != 25 && $ProductsParameter->parameterListId != 27 && $ProductsParameter->parameterListId != 14 && $ProductsParameter->parameterListId != 15 && $ProductsParameter->parameterListId != 16 && $ProductsParameter->parameterListId != 21 && $ProductsParameter->parameterListId != 10 && $ProductsParameter->parameterListId != 2): ?>
                        <?php
                        
                        $class1 = '';
                        $class2 = 'configurator-fabric-image';
                        $class3 = 'dropdownparameter-'.$ProductsParameter->parameterId;
                        $click_function = 'dropdownparameter(this,'.$ProductsParameter->parameterId.');';
                        if(strpos(strtolower($ProductsParameter->parameterName), 'position') !== false){
                            $class1 = 'position';
                            $click_function = 'setposition(this);';
                            $class3 = 'headerposition';
                        }
                        else if(strpos(strtolower($ProductsParameter->parameterName), 'border') !== false){
                            $class1 = 'border';
                            $click_function = 'borderposition(this);';
                            $class3 = 'borderposition';
                        }
                        if(strpos(strtolower($ProductsParameter->parameterName), 'ratio') !== false){
                            $class1 = 'ratio';
                            $click_function = 'borderratio(this);';
                            $class3 = 'action primary borderratio';
                        }
                        if(strpos(strtolower($ProductsParameter->parameterName), 'fabric') !== false){
                            $class1 = 'main-fabric';
                            $class2 = 'configurator-option-content configurator-fabric-image';
                            $click_function = 'changebordercolor(this);';
                            $class3 = 'bordercolor';
                        }
                        ?>
                        <div id="<?php echo $ProductsParameter->parameterId; ?>" data-role="collapsible" class="configurator-option <?php echo $class1;?>" role="presentation" data-collapsible="true">
                            <div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
                                <h4 class="title">
                                    <span data-bind="text: title"><?php echo $ProductsParameter->parameterName; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?></span>
                                <?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
                                    <img style="width: 15px; margin: 12px 0px;" class="" src="<?php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/info.png"></button>
                                    <div class="modal fade" id="info_<?php echo $ProductsParameter->parameterId;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel"><?php echo $ProductsParameter->parameterName; ?></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php echo($ProductsParameter->ecommoreinfo); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </h4>
                                <span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
                            </div>
                            <div class="<?php echo $class2;?>" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
                                <div class="option-grid <?php echo $class1;?> configurator-fabric-grid showorderdetails <?php if($ProductsParameter->orderitemmandatory == 1): ?>mandatory_validate<?php endif;?>" style="grid-template-columns: unset !important;">
                                    
                                    <?php if($ProductsParameter->parameterListId == 2):?>
                                    
                                    <?php if(count($ProductsParameter->ProductsParametervalue) > 0): ?>
                                    <?php
                                                    $multiple = false;
                                                    if('1' == $ProductsParameter->component_select_option){
                                                        $multiple = true;
                                                    }
                                                ?>
                                    <select name="<?php echo $class3;?>" class="<?php echo $class3;?> blindmatrix-select2 single_select" <?php if($multiple){?>multiple="multiple" <?php } ?>>
                                        <?php if(!$multiple): ?>	
                                        <option value=""  type="select" data-parameter_id = "<?php echo $ProductsParameter->parameterId; ?>">Choose an option</option>
                                        <?php endif; ?>	
                                    <?php foreach($ProductsParameter->ProductsParametervalue as $ProductsParametervalue):?>
                                    
                                    <?php
                                    $data_position = strtolower($ProductsParametervalue->text);
                                    $data_value = strtolower($ProductsParametervalue->text);
                                    $data_id = 'positionpair-'.$ProductsParametervalue->value;
                                    if (strpos(strtolower($ProductsParametervalue->text), 'left') !== false){
                                        $data_position = 'left';
                                        $data_value = 'single_left';
                                        $data_id = 'positionsingle_left';
                                    }
                                    if(strpos(strtolower($ProductsParameter->parameterName), 'border') !== false){
                                        $data_id = 'positionpair_'.$ProductsParametervalue->value;
                                    }
                                    if (strpos(strtolower($ProductsParametervalue->text), 'right') !== false){
                                        $data_position = 'right';
                                        $data_value = 'single_right';
                                        $data_id = 'positionsingle_right';
                                    }
                                    if (strpos(strtolower($ProductsParametervalue->text), 'pair') !== false || strpos(strtolower($ProductsParametervalue->text), 'center') !== false){
                                        $data_position = 'center';
                                        $data_value = 'pair';
                                        $data_id = 'positionpair_'.$ProductsParametervalue->value;
                                    }
                                    if(strpos(strtolower($ProductsParameter->parameterName), 'ratio') !== false){
                                        $exp_radio = explode('/',$ProductsParametervalue->text);
                                        $data_id = 'border_ratios'.trim($exp_radio[1]);
                                        $data_value = trim($exp_radio[1]);
                                        $data_position = trim($exp_radio[1]);
                                    }
                                    if(strpos(strtolower($ProductsParameter->parameterName), 'fabric') !== false){
                                        $data_id = 'main-border-fabric-'.$ProductsParametervalue->value;
                                        $data_value = $ProductsParameter->parameterId;
                                    }
                                    
                                    if($ProductsParametervalue->getEditableListimgurl != ''){
                                        $ProductsParameterimgurl = $ProductsParametervalue->getEditableListimgurl;
                                        $data_img = $ProductsParametervalue->getEditableListimgurl;
                                    }else{
                                        $ProductsParameterimgurl = plugin_dir_url( __DIR__ ).'Shortcode-Source/image/blinds/no-image.jpg';
                                        $data_img = '';
                                    }
                                    ?>
                                    <option class="configurator-fabric-item option-item <?php echo $class3;?> " data-jsevent="<?php echo str_replace('(this);','',$click_function); ?>" data-ratio="<?php echo $data_value;?>" data-position="<?php echo $data_position;?>" data-img-new="" data-img="<?php echo $data_img; ?>" get_parameter_value="<?php echo $ProductsParametervalue->value; ?>" getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" parametername="<?php echo $ProductsParameter->parameterName; ?>" getparametervalue="<?php echo $ProductsParametervalue->text; ?>" name="parameter_name[<?php echo $ProductsParameter->parameterId;?>]" id="<?php echo $data_id;?>" type="select" value="<?php echo $data_value;?>" data-sub="<?php echo $data_value;?>" getparameterid="<?php echo $ProductsParameter->parameterId;?>" ><?php echo $ProductsParametervalue->text; ?></option>
                                    <?php
                                                $display = false;
                                                if($display):
                                                ?>
                                    <input get_parameter_value="<?php echo $ProductsParametervalue->value; ?>" getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" parametername="<?php echo $ProductsParameter->parameterName; ?>" getparametervalue="<?php echo $ProductsParametervalue->text; ?>" type="radio" name="parameter_name[<?php echo $ProductsParameter->parameterId;?>]" id="<?php echo $data_id;?>" value="<?php echo $data_value;?>" data-sub="<?php echo $data_value;?>" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="parameter_name[<?php echo $ProductsParameter->parameterId;?>]" <?php if($ProductsParametervalue->text == $ProductsParameter->defaultValue): ?> checked="checked" <?php endif; ?>>
                                    <label onclick="<?php echo $click_function;?>showorderdetails();" data-ratio="<?php echo $data_value;?>" data-position="<?php echo $data_position;?>" data-img="<?php echo $data_img; ?>" class="configurator-fabric-item option-item <?php echo $class3;?> <?php if($ProductsParametervalue->text == $ProductsParameter->defaultValue): ?> selected <?php endif; ?>" for="<?php echo $data_id;?>">
                                    <div class="option-item-image">
                                    <img class="fabric_img" style="visibility: hidden;" src="" data-img="<?php echo $ProductsParameterimgurl; ?>" alt="<?php echo $ProductsParametervalue->text; ?>">
                                    </div>
                                    <div class="option-item-label config-label-text" data-bind="text: label"><?php echo $ProductsParametervalue->text; ?></div>
                                    </label>
    
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                    </select>
                                    <?php endif; ?>
                                    
                                    <?php elseif($ProductsParameter->parameterListId == 18): ?>
                                    
                                    <?php if(count($ProductsParameter->Componentvalue) > 0): ?>
                                    <?php
                                        $multiple = false;
                                        if('1' == $ProductsParameter->component_select_option){
                                            $multiple = true;
                                        }
                                    ?>
                                    <select name="Componentvalue[<?php echo $ProductsParameter->parameterId; ?>][]" id="maincomponent_<?php echo $ProductsParameter->parameterId; ?>" class="<?php echo $class3;?> blindmatrix-select2 maincomponentitem multi_select" <?php if($multiple){?>multiple="multiple" <?php } ?>>
                                        <?php if(!$multiple): ?>	
                                            <option value="" type="select" data-parameter_id = "<?php echo $ProductsParameter->parameterId; ?>">Choose an option</option>
                                        <?php endif; ?>	
                                    <?php foreach($ProductsParameter->Componentvalue as $Componentvalue):?>
                                    
                                    <?php
                                    $data_position = strtolower($Componentvalue->componentname);
                                    $data_value = strtolower($Componentvalue->componentname);
                                    $data_id = 'main-component-fabric-'.$Componentvalue->priceid;
                                    if (strpos(strtolower($Componentvalue->componentname), 'left') !== false){
                                        $data_position = 'left';
                                        $data_value = 'single_left';
                                        $data_id = 'positionsingle_left';
                                    }
                                    if(strpos(strtolower($ProductsParameter->parameterName), 'border') !== false){
                                        $data_id = 'positionpair_'.$ProductsParametervalue->value;
                                    }
                                    if (strpos(strtolower($Componentvalue->componentname), 'right') !== false){
                                        $data_position = 'right';
                                        $data_value = 'single_right';
                                        $data_id = 'positionsingle_right';
                                    }
                                    if (strpos(strtolower($Componentvalue->componentname), 'pair') !== false || strpos(strtolower($Componentvalue->componentname), 'center') !== false){
                                        $data_position = 'center';
                                        $data_value = 'pair';
                                        $data_id = 'positionpair';
                                    }
                                    if(strpos(strtolower($ProductsParameter->parameterName), 'ratio') !== false){
                                        $exp_radio = explode('/',$Componentvalue->componentname);
                                        $data_id = 'border_ratios'.trim($exp_radio[1]);
                                        $data_value = trim($exp_radio[1]);
                                        $data_position = trim($exp_radio[1]);
                                    }
                                    if(strpos(strtolower($ProductsParameter->parameterName), 'fabric') !== false){
                                        $data_id = 'main-border-fabric-'.$Componentvalue->priceid;
                                        $data_value = $Componentvalue->priceid;
                                    }
                                    
                                    $component_qty = 'ComponentQty';
                                    if(strpos(strtolower($ProductsParameter->parameterName), 'tiebacks') !== false){
                                        $component_qty = 'Tiebacks_Qty';
                                    }
                                    if(strpos(strtolower($ProductsParameter->parameterName), 'pillows') !== false){
                                        $component_qty = 'Pillows_Qty';
                                    }
                                    
                                    if($Componentvalue->getComponentimgurl != ''){
                                        $ProductsParameterimgurl = $Componentvalue->getComponentimgurl;
                                        $data_img = $Componentvalue->getComponentimgurl;
                                    }else{
                                        $ProductsParameterimgurl = plugin_dir_url( __DIR__ ).'Shortcode-Source/image/blinds/no-image.jpg';
                                        $data_img = '';
                                    }
                                    
                                    ?>
                                    <option  checkmultipleinput="<?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>1<?php else: ?>0<?php endif; ?>" checkmultiplelabel="<?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>1<?php else: ?>0<?php endif; ?>" data-jsevent="getComponentSubList" data-ratio="<?php echo $data_value;?>" data-parameterId="<?php echo $ProductsParameter->parameterId; ?>" data-position="<?php echo $data_position;?>" data-img="<?php echo $data_img; ?>" class="configurator-fabric-item maincomponentitem option-item <?php echo $class3;?> main_component_<?php echo $ProductsParameter->parameterId; ?> maincomponent_<?php echo $ProductsParameter->parameterId; ?> dropdownparameter-<?php echo $Componentvalue->priceid; ?>" for="<?php echo $data_id;?>" datasub="<?php echo $Componentvalue->parameterid."~~".$Componentvalue->priceid; ?>" get_component_qty_name="<?php echo $component_qty; ?>" get_component_qty="<?php echo $Componentvalue->qty; ?>" get_parameter_value="<?php echo $Componentvalue->priceid; ?>" getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" parametername="<?php echo $ProductsParameter->parameterName; ?>" getparametervalue="<?php echo $Componentvalue->componentname; ?>" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="Componentvalue[<?php echo $ProductsParameter->parameterId; ?>][]" id="<?php echo $data_id;?>" type="select" value="<?php echo $Componentvalue->priceid."~~".$Componentvalue->componentname; ?>"data-sub="<?php echo $data_value;?>" ><?php echo $Componentvalue->componentname; ?></option>
                                    <?php
                                                $display = false;
                                                if($display):
                                                ?>
                                   <input checkmultipleinput="<?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>1<?php else: ?>0<?php endif; ?>" get_component_qty_name="<?php echo $component_qty; ?>" get_component_qty="<?php echo $Componentvalue->qty; ?>" get_parameter_value="<?php echo $Componentvalue->priceid; ?>" getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" parametername="<?php echo $ProductsParameter->parameterName; ?>" getparametervalue="<?php echo $Componentvalue->componentname; ?>" onclick="<?php if($ProductsParameter->ecommerce_show1 == 1): ?>getComponentSubList(this,'<?php echo $ProductsParameter->parameterId; ?>');<?php endif; ?>" type="<?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>checkbox<?php else: ?>radio<?php endif; ?>" name="Componentvalue[<?php echo $ProductsParameter->parameterId; ?>][]" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="Componentvalue[<?php echo $ProductsParameter->parameterId; ?>][]" id="<?php echo $data_id;?>" value="<?php echo $Componentvalue->priceid."~~".$Componentvalue->componentname; ?>" datasub="<?php echo $Componentvalue->parameterid."~~".$Componentvalue->priceid; ?>" data-sub="<?php echo $data_value;?>" class="maincomponent_<?php echo $ProductsParameter->parameterId; ?>">
                                    <label checkmultiplelabel="<?php if($ProductsParameter->component_select_option == 1 || $ProductsParameter->component_select_option == ''): ?>1<?php else: ?>0<?php endif; ?>" onclick="<?php echo $click_function;?>showorderdetails();" data-ratio="<?php echo $data_value;?>" data-position="<?php echo $data_position;?>" data-img="<?php echo $data_img; ?>" class="configurator-fabric-item maincomponentitem option-item <?php echo $class3;?> main_component_<?php echo $ProductsParameter->parameterId; ?>" for="<?php echo $data_id;?>">
                                    <div class="option-item-image">
                                    <img class="fabric_img" style="visibility: hidden;" src="" data-img="<?php echo $ProductsParameterimgurl; ?>" alt="<?php echo $Componentvalue->componentname; ?>">
                                    </div>
                                    <div class="option-item-label config-label-text" data-bind="text: label"><?php echo $Componentvalue->componentname; ?></div>
                                    </label>
                                    <?php endif;?>
                                    <?php endforeach; ?>
                                    </select>
                                    <?php endif;?>
                                    
                                    <?php else: ?>
                                    
                                    <?php endif;?>
    
                                </div>
                            </div>
                        </div>	
                
                        <?php elseif($ProductsParameter->parameterListId == 17):?>
    <div class="tab">
            <label class="tab-label" for="rd3"><span><span class="config-count">3 </span> Choose others</span>  <h3 class="config-heading edit"><i class="icon-pen-alt-fill"></i> Edit</h3></label>
            <div class="tab-content" id="rd3">
               
                        <div data-role="collapsible" class="configurator-option border" role="presentation" data-collapsible="true">
                            <div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
                                <h4 class="title">
                                    <span data-bind="text: title"><?php echo $ProductsParameter->parameterName; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?></span>
                                    <?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
                                        <img style="width: 15px; margin: 12px 0px;" class="" src="<?php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/info.png"></button>
                                        <div class="modal fade" id="info_<?php echo $ProductsParameter->parameterId;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel"><?php echo $ProductsParameter->parameterName; ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php echo($ProductsParameter->ecommoreinfo); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </h4>
                                <span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
                            </div>
                            <div class="configurator-option-content configurator-fabric-grid configurator-fabric-image" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
                                
                                <?php if(count($ProductsParameter->CurtainLining) > 0): ?>
                                <div class="option-grid ratio showorderdetails <?php if($ProductsParameter->orderitemmandatory == 1): ?>mandatory_validate<?php endif;?>">
                                <?php
                                        $multiple = false;
                                        if('1' == $ProductsParameter->component_select_option){
                                            $multiple = true;
                                        }
                                    ?>
                                <select name="curtainlining" class="curtainlining blindmatrix-select2 single_select" <?php if($multiple){?>multiple="multiple" <?php } ?>>
                                        <?php if(!$multiple): ?>	
                                    <option value="" type="select" data-parameter_id = "<?php echo $ProductsParameter->parameterId; ?>">Choose an option</option>
                                        <?php endif; ?>	
                                <?php foreach($ProductsParameter->CurtainLining as $CurtainLining):?>
                                    <option data-jsevent="showorderdetails"  get_parameter_value="<?php echo $CurtainLining->liningPriceId; ?>" getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" getliningpermeter="<?php echo $CurtainLining->liningPrice; ?>" getmarkupperwidth="<?php echo $CurtainLining->valuetype; ?>" getliningmarkup="<?php echo $CurtainLining->liningecommarkup; ?>" parametername="<?php echo $ProductsParameter->parameterName; ?>" getparametervalue="<?php echo $CurtainLining->componentName; ?>" id="curtainlining<?php echo $CurtainLining->liningPriceId;?>" name="curtainlining" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="curtainlining" type="select" value="<?php echo $CurtainLining->liningPriceId;?>"><?php echo $CurtainLining->componentName; ?></option>
                                    <?php
                                                $display = false;
                                                if($display):
                                                ?>
                                <input get_parameter_value="<?php echo $CurtainLining->liningPriceId; ?>" getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" getliningpermeter="<?php echo $CurtainLining->liningPrice; ?>" getmarkupperwidth="<?php echo $CurtainLining->valuetype; ?>" getliningmarkup="<?php echo $CurtainLining->liningecommarkup; ?>" parametername="<?php echo $ProductsParameter->parameterName; ?>" getparametervalue="<?php echo $CurtainLining->componentName; ?>" type="radio" id="curtainlining<?php echo $CurtainLining->liningPriceId;?>" name="curtainlining" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="curtainlining" value="<?php echo $CurtainLining->liningPriceId;?>">
                                <label onclick="showorderdetails();" class="action primary configurator-fabric-item curtainlining" for="curtainlining<?php echo $CurtainLining->liningPriceId;?>"><?php echo $CurtainLining->componentName; ?></label>
                                <?php endif; ?>
                                <?php endforeach; ?>
                                </select>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php elseif($ProductsParameter->parameterListId == 25):?>
                        
                        <div id="<?php echo $ProductsParameter->parameterId; ?>" data-role="collapsible" class="configurator-option border" role="presentation" data-collapsible="true">
                            <div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
                                <h4 class="title">
                                    <span data-bind="text: title"><?php echo $ProductsParameter->parameterName; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?></span>
                               <?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
                                        <img style="width: 15px; margin: 12px 0px;" class="" src="<?php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/info.png"></button>
                                        <div class="modal fade" id="info_<?php echo $ProductsParameter->parameterId;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel"><?php echo $ProductsParameter->parameterName; ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php echo($ProductsParameter->ecommoreinfo); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                               </h4>
                                <span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
                            </div>
                            <div class="configurator-option-content configurator-fabric-grid configurator-fabric-image" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
                                
                                <?php if(count($ProductsParameter->CurtainLiningNew) > 0): ?>
                                <div class="option-grid ratio showorderdetails <?php if($ProductsParameter->orderitemmandatory == 1): ?>mandatory_validate<?php endif;?>">
                                <?php
                                        $multiple = false;
                                        if('1' == $ProductsParameter->component_select_option){
                                            $multiple = true;
                                        }
                                    ?>
                                    <select name="curtainliningnew" class="curtainliningnew  blindmatrix-select2 single_select" <?php if($multiple){?>multiple="multiple" <?php } ?>>
                                        <?php if(!$multiple): ?>	
                                            <option class="action primary configurator-fabric-item curtainliningnew" data-jsevent="getsubcurtainliningnew" type="select" value="" data-para-id="<?php echo $ProductsParameter->parameterId; ?>" data-parameter_id = "<?php echo $ProductsParameter->parameterId; ?>">Choose an option</option>
                                        <?php endif; ?>	
                                        <?php foreach($ProductsParameter->CurtainLiningNew as $CurtainLiningNew):?>
                                        <option class="action primary configurator-fabric-item curtainliningnew"  id="curtainliningnew<?php echo $CurtainLiningNew->priceid;?>" data-jsevent="getsubcurtainliningnew" data-price-id="<?php echo $CurtainLiningNew->priceid;?>" data-parameterid="<?php echo $ProductsParameter->parameterId; ?>" data-para-id="<?php echo $ProductsParameter->parameterId; ?>" get_parameter_value="<?php echo $CurtainLiningNew->priceid;?>" getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" parametername="<?php echo $ProductsParameter->parameterName; ?>" getparametervalue="<?php echo $CurtainLiningNew->componentname; ?>" name="curtainliningnew" getparameterid="<?php echo $ProductsParameter->parameterId;?>" type="select" value="<?php echo $CurtainLiningNew->priceid;?>"><?php echo $CurtainLiningNew->componentname; ?></option>
                                            <?php
                                                $display = false;
                                                if($display):
                                            ?>
                                        <input get_parameter_value="<?php echo $CurtainLiningNew->priceid;?>" getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" parametername="<?php echo $ProductsParameter->parameterName; ?>" getparametervalue="<?php echo $CurtainLiningNew->componentname; ?>" onclick="getsubcurtainliningnew('<?php echo $CurtainLiningNew->priceid;?>','<?php echo $ProductsParameter->parameterId; ?>',this);showorderdetails();" type="radio" id="curtainliningnew<?php echo $CurtainLiningNew->priceid;?>" name="curtainliningnew" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="curtainliningnew" value="<?php echo $CurtainLiningNew->priceid;?>">
                                        <label class="action primary configurator-fabric-item curtainliningnew" for="curtainliningnew<?php echo $CurtainLiningNew->priceid;?>"><?php echo $CurtainLiningNew->componentname; ?></label>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php elseif($ProductsParameter->parameterListId == 27):?>
                        
                        <div id="<?php echo $ProductsParameter->parameterId; ?>" data-role="collapsible" class="configurator-option border" role="presentation" data-collapsible="true">
                            <div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
                                <h4 class="title">
                                    <span data-bind="text: title"><?php echo $ProductsParameter->parameterName; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?></span>
                                    <?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
                                        <img style="width: 15px; margin: 12px 0px;" class="" src="<?php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/info.png"></button>
                                        <div class="modal fade" id="info_<?php echo $ProductsParameter->parameterId;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel"><?php echo $ProductsParameter->parameterName; ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php echo($ProductsParameter->ecommoreinfo); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </h4>
                                <span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
                            </div>
                            <div class="configurator-option-content configurator-fabric-grid configurator-fabric-image" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
                                
                                <?php if(count($ProductsParameter->CurtainLiningNewtwo) > 0): ?>
                                <div class="option-grid ratio showorderdetails <?php if($ProductsParameter->orderitemmandatory == 1): ?>mandatory_validate<?php endif;?>">
                                <?php
                                        $multiple = false;
                                        if('1' == $ProductsParameter->component_select_option){
                                            $multiple = true;
                                        }
                                    ?>
                                <select name="curtainliningnewtwo" class="curtainliningnewtwo blindmatrix-select2 single_select" <?php if($multiple){?>multiple="multiple" <?php } ?>>
                                    <?php if(!$multiple): ?>	
                                        <option class="action primary configurator-fabric-item curtainliningnewtwo" data-jsevent="getsubcurtainliningnewtwo" value="" type="select" data-para-id="<?php echo $ProductsParameter->parameterId; ?>" data-parameter_id = "<?php echo $ProductsParameter->parameterId; ?>">Choose an option</option>
                                    <?php endif; ?>	
                                    <?php foreach($ProductsParameter->CurtainLiningNewtwo as $CurtainLiningNewtwo):?>
                                        <option class="action primary configurator-fabric-item curtainliningnewtwo"  data-jsevent="getsubcurtainliningnewtwo" data-price-id="<?php echo $CurtainLiningNewtwo->priceid;?>" data-parameterid="<?php echo $ProductsParameter->parameterId; ?>" data-para-id="<?php echo $ProductsParameter->parameterId; ?>" id="curtainliningnewtwo<?php echo $CurtainLiningNewtwo->priceid;?>" get_parameter_value="<?php echo $CurtainLiningNewtwo->priceid;?>" getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" parametername="<?php echo $ProductsParameter->parameterName; ?>" getparametervalue="<?php echo $CurtainLiningNewtwo->componentname; ?>" name="curtainliningnewtwo" getparameterid="<?php echo $ProductsParameter->parameterId;?>" type="select" value="<?php echo $CurtainLiningNewtwo->priceid;?>"><?php echo $CurtainLiningNewtwo->componentname; ?></option>
                                    <?php
                                        $display = false;
                                        if($display):
                                    ?>
                                        <input get_parameter_value="<?php echo $CurtainLiningNewtwo->priceid;?>" getparameterlistid="<?php echo $ProductsParameter->parameterListId; ?>" parametername="<?php echo $ProductsParameter->parameterName; ?>" getparametervalue="<?php echo $CurtainLiningNewtwo->componentname; ?>" onclick="getsubcurtainliningnewtwo('<?php echo $CurtainLiningNewtwo->priceid;?>','<?php echo $ProductsParameter->parameterId; ?>',this);showorderdetails();" type="radio" id="curtainliningnewtwo<?php echo $CurtainLiningNewtwo->priceid;?>" name="curtainliningnewtwo" getparameterid="<?php echo $ProductsParameter->parameterId;?>" radiobutton="curtainliningnewtwo" value="<?php echo $CurtainLiningNewtwo->priceid;?>">
                                        <label class="action primary configurator-fabric-item curtainliningnewtwo" for="curtainliningnewtwo<?php echo $CurtainLiningNewtwo->priceid;?>"><?php echo $CurtainLiningNewtwo->componentname; ?></label>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php elseif($ProductsParameter->parameterListId == 3):?>
                        
                        <div data-role="collapsible" class="configurator-option" role="presentation" data-collapsible="true">
                            <div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
                                <h4 class="title">
                                    <span data-bind="text: title"><?php echo $ProductsParameter->parameterName; ?> <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?><font color="red">*</font><?php endif;?></span>
                                    <?php if(!empty($ProductsParameter->ecommoreinfo) ){ ?>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#info_<?php echo $ProductsParameter->parameterId;?>">
                                        <img style="width: 15px; margin: 12px 0px;" class="" src="<?php echo plugin_dir_url( __DIR__ );?>Shortcode-Source/image/info.png"></button>
                                        <div class="modal fade" id="info_<?php echo $ProductsParameter->parameterId;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel"><?php echo $ProductsParameter->parameterName; ?></h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <?php echo($ProductsParameter->ecommoreinfo); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </h4>
                            </div>
                            <div class="configurator-option-content showorderdetails" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
                                <span id="errormsg_<?php echo $ProductsParameter->parameterId;?>" data-text-color="alert" class="is-small errormsg"></span>
                                <input parametername="<?php echo $ProductsParameter->parameterName; ?>" getparameterid="<?php echo $ProductsParameter->parameterId;?>" name="Othersvalue[<?php echo $ProductsParameter->parameterId; ?>]" class="border border-1 border-silver white-back border-radius-10 othersvalue <?php if($ProductsParameter->orderitemmandatory == 1 && $ProductsParameter->ecommerce_show1 == 1): ?>mandatoryvalidate<?php endif;?>" type="text">
                                <input type="hidden" name="OthersParametername[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->parameterName; ?>">
                                <input type="hidden" name="OthersParameterhidden[<?php echo $ProductsParameter->parameterId; ?>]" value="<?php echo $ProductsParameter->ecommerce_show1; ?>">
                            </div>
                        </div>
                        <?php endif;?>
    
                        <?php endif;?>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <div class="configurator-option" role="presentation" data-collapsible="true">
                            <div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
                                <h4 class="title">
                                    <span data-bind="text: title">Pair/Single</span>
                                </h4>
                            </div>
                             <div class="configurator-option-content configurator-fabric-image option-grid header-type configurator-fabric-grid showorderdetails" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
                                <select name="curtaintype" class=" pairsingle blindmatrix-select2 single_select" >
                                    <option class="option-item configurator-fabric-item pairsingle " data-jsevent="showorderdetails" value="" >Choose an option</option>
                                    <option get_parameter_value="0" selected="selected" parametername="Pair/Single" getparametervalue="N/A" data-jsevent="showorderdetails();" name="curtaintype" class="option-item configurator-fabric-item pairsingle selected"  id="curtaintype_na" type="select" value="N/A" for="curtaintype_na">N/A</option>
                                    <option get_parameter_value="2" parametername="Pair/Single" getparametervalue="Pair" data-jsevent="showorderdetails();" name="curtaintype" class="option-item configurator-fabric-item pairsingle" id="curtaintype_pair" type="select" value="N/A" for="curtaintype_na">Pair</option>
                                    <option get_parameter_value="1" parametername="Pair/Single" getparametervalue="N/A" data-jsevent="showorderdetails();" name="curtaintype" class="option-item configurator-fabric-item pairsingle"  id="curtaintype_Single" type="select" value="N/A" for="curtaintype_na">Single</option>
                                    <?php
                                        $display = false;
                                        if($display):
                                    ?>
                                    <input get_parameter_value="0" checked parametername="Pair/Single" getparametervalue="N/A" type="radio" name="curtaintype" id="curtaintype_na" value="N/A" radiobutton="curtaintype">
                                    <label onclick="showorderdetails();" class="option-item configurator-fabric-item pairsingle selected" for="curtaintype_na">
                                    <div class="option-item-label config-label-text" data-bind="text: label">N/A</div>
                                    </label>
                                    <input get_parameter_value="2" parametername="Pair/Single" getparametervalue="Pair" type="radio" name="curtaintype" id="curtaintype_pair" value="Pair" radiobutton="curtaintype">
                                    <label onclick="showorderdetails();" class="option-item configurator-fabric-item pairsingle" for="curtaintype_pair">
                                    <div class="option-item-label config-label-text" data-bind="text: label">Pair</div>
                                    </label>
                                    <input get_parameter_value="1" parametername="Pair/Single" getparametervalue="Single" type="radio" name="curtaintype" id="curtaintype_Single" value="Single" radiobutton="curtaintype">
                                    <label onclick="showorderdetails();" class="option-item configurator-fabric-item pairsingle" for="curtaintype_Single">
                                    <div class="option-item-label config-label-text" data-bind="text: label">Single</div>
                                    </label>
                                    <?php endif;?>
                                    </select>
                            </div>
                        </div>
              </div>
          </div>
                        
                        <div style="display:none !important;" data-role="collapsible" class="configurator-option configurator-option-two-design" role="presentation" data-collapsible="true">
                            <div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
                                <h4 class="title">
                                    <span data-bind="text: title">Left return</span>
                                </h4>
                            </div>
                            <div class="configurator-option-content" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
                                <input parametername="Left return" name="left_return_cur" value="0" class="border border-1 border-silver white-back border-radius-10 othersvalue" type="text">
                            </div>
                        </div>
                        
                        <div style="display:none !important;" data-role="collapsible" class="configurator-option configurator-option-two-design" role="presentation" data-collapsible="true">
                            <div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
                                <h4 class="title">
                                    <span data-bind="text: title">Right return</span>
                                </h4>
                            </div>
                            <div class="configurator-option-content" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
                                <input parametername="Right return" name="right_return_cur" value="0" class="border border-1 border-silver white-back border-radius-10 othersvalue" type="text">
                            </div>
                        </div>
                        
                        <div style="display:none !important;" data-role="collapsible" class="configurator-option configurator-option-two-design" role="presentation" data-collapsible="true">
                            <div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
                                <h4 class="title">
                                    <span data-bind="text: title">Overlap</span>
                                </h4>
                            </div>
                            <div class="configurator-option-content" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
                                <input parametername="Overlap" name="overlap_cur" value="0" class="border border-1 border-silver white-back border-radius-10 othersvalue" type="text">
                            </div>
                        </div>
                        
                        <div style="display:none !important;" data-role="collapsible" class="configurator-option configurator-option-two-design" role="presentation" data-collapsible="true">
                            <div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
                                <h4 class="title">
                                    <span data-bind="text: title">Suspension point to wall</span>
                                </h4>
                            </div>
                            <div class="configurator-option-content" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
                                <input parametername="Suspension point to wall" name="suspension_point_cur" value="0" class="border border-1 border-silver white-back border-radius-10 othersvalue" type="text">
                            </div>
                        </div>
                        
                        <div style="display:none !important;" data-role="collapsible" class="configurator-option configurator-option-two-design" role="presentation" data-collapsible="true">
                            <div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
                                <h4 class="title">
                                    <span data-bind="text: title">Extra fabric to order</span>
                                </h4>
                            </div>
                            <div class="configurator-option-content" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
                                <input parametername="Extra fabric to order" name="extra_fabric_cur" value="0" class="border border-1 border-silver white-back border-radius-10 othersvalue" type="text">
                            </div>
                        </div>
                        
                        <div style="display:none !important;" data-role="collapsible" class="configurator-option configurator-option-two-design" role="presentation" data-collapsible="true">
                            <div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
                                <h4 class="title">
                                    <span data-bind="text: title">Fabric Mark Up %</span>
                                </h4>
                            </div>
                            <div class="configurator-option-content" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
                                <input parametername="Fabric Mark Up %" name="markup_total_fabric_cur" value="0" class="border border-1 border-silver white-back border-radius-10 othersvalue" type="text">
                            </div>
                        </div>
                        
                        <div style="display:none !important;" data-role="collapsible" class="configurator-option configurator-option-two-design" role="presentation" data-collapsible="true">
                            <div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
                                <h4 class="title">
                                    <span data-bind="text: title">Override Fabric Price</span>
                                </h4>
                            </div>
                            <div class="configurator-option-content" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
                                <input parametername="OverrideFabricPrice" name="override_fabric_price" value="0" class="border border-1 border-silver white-back border-radius-10 othersvalue" type="text">
                            </div>
                        </div>
                        
                        <?php if($response->product_details->overridewidths == 1):?>
                        <div style="display:none !important;" data-role="collapsible" class="configurator-option configurator-option-two-design" role="presentation" data-collapsible="true">
                            <div class="configurator-option-heading" data-role="title" role="tab" aria-selected="true" aria-expanded="true" tabindex="0">
                                <h4 class="title">
                                    <span data-bind="text: title">Override Widths</span>
                                </h4>
                            </div>
                            <div class="configurator-option-content" data-role="content" role="tabpanel" aria-hidden="false" style="display: block;">
                                <input parametername="Override Widths" name="overridewidths_cur" value="0" class="border border-1 border-silver white-back border-radius-10 othersvalue" type="text">
                            </div>
                        </div>
                        <?php endif;?>
                        
        </div>
                        <div class="product-option__more-info" style="clear: both;">
                            <div class="accordion" rel="">
                                <div class="accordion-item">
                                    <a href="#" class="accordion-title plain"><button class="toggle">
                                        <i style="font-weight: bold;font-size: 25px;line-height: 1.5;" class="icon-angle-down"></i>
                                        </button><span style="font-weight: bold;font-size: 15px;">Show Order Details</span>
                                    </a>
                                    <div class="accordion-inner" style="display: none;padding-top: 0;position: relative;background: none;">
                                        <p id="allparametervalue" style="font-size: 14px;color: black;"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="single_variation_wrap text-center">
                        <div class="price_container" style="display:none;">
                            <div>
                                <div class="price havelock-blue align-centre italic margin-top-20 font-30 display-none product-price">
                                    <div style="" class="font-16 grey light-weight">Your Price</div>
                                    <div class="js-ajax-price margin-top-5">
                                        <?php echo $_SESSION['currencysymbol'];?><span class="showprice"><?php echo $response->product_details->curtainparametertypedetails->minprice; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="display: none;" class="loading-spin"></div>
                        <div class="woocommerce-variation-add-to-cart variations_button woocommerce-variation-add-to-cart-disabled">
                            <button onclick="getprice();" type="button" class="single_add_to_cart_button button curtains alt js-add-cart relatedproduct" style="border-radius: 10px;"><i class="icon-shopping-cart"></i>&nbsp;Add to cart</button>
                        </div>
                    </div>
                </div>
               </div>
            </div>
        
        </div>
        
    </div>
    </div>
    
    <input type="hidden" id="borderposition"/>
    <input type="hidden" id="borderratio"/>
    <input type="hidden" id="borderfabric"/>
    <input type="hidden" id="headertypename" value="<?php echo $response->product_details->curtainparametertypedetails->curtain_type;?>"/>
    
    <input type="hidden" name="single_product_price" id="single_product_price">
    <input type="hidden" name="vaterate" id="vaterate">
    <input type="hidden" name="single_product_netprice" id="single_product_netprice">
    <input type="hidden" name="single_product_itemcost" id="single_product_itemcost">
    <input type="hidden" name="single_product_orgvat" id="single_product_orgvat">
    <input type="hidden" name="single_product_vatvalue" id="single_product_vatvalue">
    <input type="hidden" name="single_product_grossprice" id="single_product_grossprice">
    
    <input type="hidden" id="blindmatrix-js-add-cart" class="blindmatrix-js-add-cart">
    
    <input type="hidden" id="showorderitemlist" name="orderitem[]">
    <input type="hidden" id="getparameteridvallist" name="getparameteridvallist[]">
    <input type="hidden" id="curtainformulavalues" name="curtainformulavalues[]">
    <input type="hidden" id="getallcurtainliningnew" name="getallcurtainliningnew[]">
    <input type="hidden" id="getallcurtainliningnew2" name="getallcurtainliningnew2[]">
    <input type="hidden" id="sel_sub_product" name="sel_sub_product">
    
    <span id="headstyle"></span>
    </form>
    <?php else:?>
    <main id="main" class="site-main container pt" role="main" style="max-width: 1010px;">
        <div class="row cusprodname">
            <div class="col">
                <div class="col-inner">
                    <h3 class="lead">Page cannot be found</h3>
                    <ul>
                        <li>We're sorry but the page you were looking for could not be found.</li>
                        <li>Simply <a href="<?php bloginfo('url'); ?>" class="clr-red">click here</a> to get redirected and back on track.</li>
                        <li>Follow the product links below.</li>
                    </ul>
                </div>
            </div>
            <?php echo do_shortcode( '[BlindMatrix source="BM-Shutters"] ' );?>
        </div>
     </div>
    <?php endif;?>
    
    <script src="/wp-content/plugins/blindmatrix-ecommerce/view/js/dom-to-image.js"></script>
    
    <link  rel="stylesheet" type="text/css"  media="all" href="/wp-content/plugins/blindmatrix-ecommerce/assets/css/curtain_configurator.css" />
    
    <script>
    
    jQuery(function($){
        jQuery('.mainfabric.selected').trigger('click');
        var checkInch = '<?=$response->product_details->checkInch;?>';
        if( checkInch == 'checked' ){
            jQuery("#width,#drope").css({"width":"75%","float":"left","border-top-right-radius":"0px","border-bottom-right-radius":"0px"});
        }
        
        
        /*jQuery(".default-fabric-type-selected").click(function(){
            jQuery(this).removeClass("test");
            if(jQuery(this).hasClass("selected")){
                    jQuery(this).addClass('test');
                }
                
        });*/
    });
    // document.addEventListener('contextmenu', event => event.preventDefault());
    // document.onkeydown = function(e) {
      // if(event.keyCode == 123) {
         // return false;
      // }
      // if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
         // return false;
      // }
      // if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
         // return false;
      // }
      // if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
         // return false;
      // }
      // if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
         // return false;
      // }
    // }
    
    var url_producttypename = '<?=$producttypename;?>';
    var url_productid = '<?=$productid;?>';
    
    var url_producttypeid = '<?=$producttypeid;?>';
    var url_curtains_config = '<?=$curtains_config;?>';
    var parameterarray = <?php echo json_encode($parameterarray); ?>;
    jQuery(window).on('load', function() {
        jQuery(".fabric_img").each(function (e) {
            var dataimg = jQuery(this).attr('data-img');
            jQuery(this).attr('src', dataimg);
        });
        jQuery('.fabric_img').css('visibility','visible');
        jQuery('.configurator.curtain.bordered.cuspricevalue').css('visibility','visible');
       // jQuery('.curtain-whole-loader').css('display','none');
        jQuery('.configurator.curtain.bordered.cuspricevalue').css('height','auto');
    });
    jQuery(document).ready(function ($) {
        
        jQuery('.headertype.active').trigger('click');
        
        var fraction = jQuery('#fraction').val();
        var unitVal = jQuery('input[name=unit]:checked').val();
        if(fraction == 'on' && unitVal == 'inch'){
            jQuery("#width,#drope").css({"width":"75%","float":"left"});
            jQuery("#widthfraction,#dropfraction").css({"width":"25%"});
        }
        
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
            
            var widthplaceholdertext = jQuery('#widthplaceholdertext').val();
            var dropeplaceholdertext = jQuery('#dropeplaceholdertext').val();
            if (this.value == 'cm') {
                jQuery('#width').attr('placeholder',widthplaceholdertext+' (cm)');
                jQuery('#drope').attr('placeholder',dropeplaceholdertext+' (cm)');
                jQuery("#width,#drope").removeAttr("style");
                jQuery("#width,#drope").css({"width":"100%"});
                jQuery('#widthfraction').hide();
                jQuery('#dropfraction').hide();
            }
            else if (this.value == 'mm') {
                jQuery('#width').attr('placeholder',widthplaceholdertext+' (mm)');
                jQuery('#drope').attr('placeholder',dropeplaceholdertext+' (mm)');
                if(widthTmp > 0)  jQuery('#width').val(parseInt(widthTmp));
                if(dropeTmp > 0)  jQuery('#drope').val(parseInt(dropeTmp));
                jQuery("#width,#drope").removeAttr("style");
                jQuery("#width,#drope").css({"width":"100%"});
                jQuery('#widthfraction').hide();
                jQuery('#dropfraction').hide();
            }
            else if (this.value == 'inch') {
                jQuery('#width').attr('placeholder',widthplaceholdertext+' (inch)');
                jQuery('#drope').attr('placeholder',dropeplaceholdertext+' (inch)');
                if(widthTmp > 0)  jQuery('#width').val(parseInt(widthTmp));
                if(dropeTmp > 0)  jQuery('#drope').val(parseInt(dropeTmp));
                if(fraction == 'on'){
                    jQuery('#widthfraction').show();
                    jQuery('#dropfraction').show();
                    jQuery("#width,#drope").css({"width":"75%","float":"left","border-top-right-radius":"0px","border-bottom-right-radius":"0px"});
                    jQuery("#widthfraction,#dropfraction").css({"width":"25%"});
                }else{
                    jQuery('#widthfraction').hide();
                    jQuery('#dropfraction').hide();
                }
            }
        });
    
        jQuery(".configurator-fabric-grid").each(function (e) {
            var dataimgArray = [];
            jQuery(this).find("label").each(function(i){
                var dataimg = jQuery(this).attr('data-img');
                if(dataimg != ''){
                    dataimgArray.push(dataimg);
                }
            });
            jQuery(this).find("select").each(function(i){
                var dataimg = jQuery(this).attr('data-img');
                if(dataimg != ''){
                    dataimgArray.push(dataimg);
                }
            });
            if(dataimgArray.length == 0){
                jQuery(this).find(".option-item-image").hide();
            }
        });
        
        
    });
    
    var plugin_dir_url = '<?=plugin_dir_url( __DIR__ ); ?>';
    var configuratormainheadertype = {
            "pencil-pleat": {
                left: plugin_dir_url+"Shortcode-Source/image/header_type/pencil-pleat-left.webp",
                center: plugin_dir_url+"Shortcode-Source/image/header_type/pencil-pleat_1.webp",
                right: plugin_dir_url+"Shortcode-Source/image/header_type/pencil-pleat-right.webp"
            },        
            "double-pinch": {
                left: plugin_dir_url+"Shortcode-Source/image/header_type/double-pinch-left.webp",
                center: plugin_dir_url+"Shortcode-Source/image/header_type/double-pinch.webp",
                right: plugin_dir_url+"Shortcode-Source/image/header_type/double-pinch-right.webp"
            },
            "triple-pinch": {
                left: plugin_dir_url+"Shortcode-Source/image/header_type/triple-pinch-left.webp",
                center: plugin_dir_url+"Shortcode-Source/image/header_type/triple-pinch.webp",
                right: plugin_dir_url+"Shortcode-Source/image/header_type/triple-pinch-right.webp"
            },
            "double-pinch-buttoned": {
                left: plugin_dir_url+"Shortcode-Source/image/header_type/double-pinch-buttoned-left.webp",
                center: plugin_dir_url+"Shortcode-Source/image/header_type/double-pinch-buttoned.webp",
                right: plugin_dir_url+"Shortcode-Source/image/header_type/double-pinch-buttoned-right.webp"
            },
            "triple-pinch-buttoned": {
                left: plugin_dir_url+"Shortcode-Source/image/header_type/triple-pinch-buttoned-left.webp",
                center: plugin_dir_url+"Shortcode-Source/image/header_type/triple-pinch-buttoned.webp",
                right: plugin_dir_url+"Shortcode-Source/image/header_type/triple-pinch-buttoned-right.webp"
            },
            "goblet": {
                left: plugin_dir_url+"Shortcode-Source/image/header_type/goblet-left.webp",
                center: plugin_dir_url+"Shortcode-Source/image/header_type/goblet.webp",
                right: plugin_dir_url+"Shortcode-Source/image/header_type/goblet-right.webp"
            },
            "goblet-buttoned": {
                left: plugin_dir_url+"Shortcode-Source/image/header_type/goblet-buttoned-left.webp",
                center: plugin_dir_url+"Shortcode-Source/image/header_type/goblet-buttoned.webp",
                right: plugin_dir_url+"Shortcode-Source/image/header_type/goblet-buttoned-right.webp"
            },
            "eyelet": {
                left: plugin_dir_url+"Shortcode-Source/image/header_type/eyelet-left.webp",
                center: plugin_dir_url+"Shortcode-Source/image/header_type/eyelet.webp",
                right: plugin_dir_url+"Shortcode-Source/image/header_type/eyelet-right.webp"
            }
    };
    
    //var formulafunctionlist =["DATE(","DATEVALUE(","DAY(","DAYS(","DAYS360(","EDATE(","EOMONTH(","HOUR(","MINUTE(","ISOWEEKNUM(","MONTH(","NETWORKDAYS(","NETWORKDAYSINTL(","NOW(","SECOND(","TIME(","TIMEVALUE(","TODAY(","WEEKDAY(","YEAR(","WEEKNUM(","WORKDAY(","WORKDAYINTL(","YEARFRAC(","ACCRINT(","CUMIPMT(","CUMPRINC(","DB(","DDB(","DOLLARDE(","DOLLARFR(","EFFECT(","FV(","FVSCHEDULE(","IPMT(","IRR(","ISPMT(","MIRR(","NOMINAL(","NPER(","NPV(","PDURATION(","PMT(","PPMT(","PV(","RATE(","BIN2DEC(","BIN2HEX(","BIN2OCT(","BITAND(","BITLSHIFT(","BITOR(","BITRSHIFT(","BITXOR(","COMPLEX(","CONVERT(","DEC2BIN(","DEC2HEX(","DEC2OCT(","DELTA(","ERF(","ERFC(","GESTEP(","HEX2BIN(","HEX2DEC(","HEX2OCT(","IMABS(","IMAGINARY(","IMARGUMENT(","IMCONJUGATE(","IMCOS(","IMCOSH(","IMCOT(","IMCSC(","IMCSCH(","IMDIV(","IMEXP(","IMLN(","IMLOG10(","IMLOG2(","IMPOWER(","IMPRODUCT(","IMREAL(","IMSEC(","IMSECH(","IMSIN(","IMSINH(","IMSQRT(","IMSUB(","IMSUM(","IMTAN(","OCT2BIN(","OCT2DEC(","OCT2HEX(","AND(","false(","IF(","IFS(","IFERROR(","IFNA(","NOT(","OR(","SWITCH(","true(","XOR(","ABS(","ACOS(","ACOSH(","ACOT(","ACOTH(","AGGREGATE(","ARABIC(","ASIN(","ASINH(","ATAN(","ATAN2(","ATANH(","BASE(","CEILING(","CEILINGMATH(","CEILINGPRECISE(","COMBIN(","COMBINA(","COS(","COSH(","COT(","COTH(","CSC(","CSCH(","DECIMAL(","ERF(","ERFC(","EVEN(","EXP(","FACT(","FACTDOUBLE(","FLOOR(","FLOORMATH(","FLOORPRECISE(","GCD(","INT(","ISEVEN(","ISOCEILING(","ISODD(","LCM(","LN(","LOG(","LOG10(","MOD(","MROUND(","MULTINOMIAL(","ODD(","POWER(","PRODUCT(","QUOTIENT(","RADIANS(","RAND(","RANDBETWEEN(","ROUND(","ROUNDDOWN(","ROUNDUP(","SEC(","SECH(","SIGN(","SIN(","SINH(","SQRT(","SQRTPI(","SUBTOTAL(","SUM(","SUMIF(","SUMIFS(","SUMPRODUCT(","SUMSQ(","SUMX2MY2(","SUMX2PY2(","SUMXMY2(","TAN(","TANH(","TRUNC(","AVEDEV(","AVERAGE(","AVERAGEA(","AVERAGEIF(","AVERAGEIFS(","BETADIST(","BETAINV(","BINOMDIST(","CORREL(","COUNT(","COUNTA(","COUNTBLANK(","COUNTIF(","COUNTIFS(","COUNTUNIQUE(","COVARIANCEP(","COVARIANCES(","DEVSQ(","EXPONDIST(","FDIST(","FINV(","FISHER(","FISHERINV(","FORECAST(","FREQUENCY(","GAMMA(","GAMMALN(","GAUSS(","GEOMEAN(","GROWTH(","HARMEAN(","HYPGEOMDIST(","INTERCEPT(","KURT(","LARGE(","LINEST(","LOGNORMDIST(","LOGNORMINV(","MAX(","MAXA(","MEDIAN(","MIN(","MINA(","MODEMULT(","MODESNGL(","NORMDIST(","NORMINV(","NORMSDIST(","NORMSINV(","PEARSON(","PERCENTILEEXC(","PERCENTILEINC(","PERCENTRANKEXC(","PERCENTRANKINC(","PERMUT(","PERMUTATIONA(","PHI(","POISSONDIST(","PROB(","QUARTILEEXC(","QUARTILEINC(","RANKAVG(","RANKEQ(","RSQ(","SKEW(","SKEWP(","SLOPE(","SMALL(","STANDARDIZE(","STDEVA(","STDEVP(","STDEVPA(","STDEVS(","STEYX(","TDIST(","TINV(","TRIMMEAN(","VARA(","VARP(","VARPA(","VARS(","WEIBULLDIST(","ZTEST(","CHAR(","CLEAN(","CODE(","CONCATENATE(","EXACT(","FIND(","LEFT(","LEN(","LOWER(","MID(","NUMBERVALUE(","PROPER(","REGEXEXTRACT(","REGEXMATCH(","REGEXREPLACE(","REPLACE(","REPT(","RIGHT(","ROMAN(","SEARCH(","SPLIT(","SUBSTITUTE(","T(","TRIM(","UNICHAR(","UNICODE(","UPPER("];
    var formulafunctionlist =["DATE","DATEVALUE","DAY","DAYS","DAYS360","EDATE","EOMONTH","HOUR","MINUTE","ISOWEEKNUM","MONTH","NETWORKDAYS","NETWORKDAYSINTL","NOW","SECOND","TIME","TIMEVALUE","TODAY","WEEKDAY","YEAR","WEEKNUM","WORKDAY","WORKDAYINTL","YEARFRAC","ACCRINT","CUMIPMT","CUMPRINC","DB","DDB","DOLLARDE","DOLLARFR","EFFECT","FV","FVSCHEDULE","IPMT","IRR","ISPMT","MIRR","NOMINAL","NPER","NPV","PDURATION","PMT","PPMT","PV","RATE","BIN2DEC","BIN2HEX","BIN2OCT","BITAND","BITLSHIFT","BITOR","BITRSHIFT","BITXOR","COMPLEX","CONVERT","DEC2BIN","DEC2HEX","DEC2OCT","DELTA","ERF","ERFC","GESTEP","HEX2BIN","HEX2DEC","HEX2OCT","IMABS","IMAGINARY","IMARGUMENT","IMCONJUGATE","IMCOS","IMCOSH","IMCOT","IMCSC","IMCSCH","IMDIV","IMEXP","IMLN","IMLOG10","IMLOG2","IMPOWER","IMPRODUCT","IMREAL","IMSEC","IMSECH","IMSIN","IMSINH","IMSQRT","IMSUB","IMSUM","IMTAN","OCT2BIN","OCT2DEC","OCT2HEX","AND","false","IF","IFS","IFERROR","IFNA","NOT","OR","SWITCH","true","XOR","ABS","ACOS","ACOSH","ACOT","ACOTH","AGGREGATE","ARABIC","ASIN","ASINH","ATAN","ATAN2","ATANH","BASE","CEILING","CEILINGMATH","CEILINGPRECISE","COMBIN","COMBINA","COS","COSH","COT","COTH","CSC","CSCH","DECIMAL","ERF","ERFC","EVEN","EXP","FACT","FACTDOUBLE","FLOOR","FLOORMATH","FLOORPRECISE","GCD","INT","ISEVEN","ISOCEILING","ISODD","LCM","LN","LOG","LOG10","MOD","MROUND","MULTINOMIAL","ODD","POWER","PRODUCT","QUOTIENT","RADIANS","RAND","RANDBETWEEN","ROUND","ROUNDDOWN","ROUNDUP","SEC","SECH","SIGN","SIN","SINH","SQRT","SQRTPI","SUBTOTAL","SUM","SUMIF","SUMIFS","SUMPRODUCT","SUMSQ","SUMX2MY2","SUMX2PY2","SUMXMY2","TAN","TANH","TRUNC","AVEDEV","AVERAGE","AVERAGEA","AVERAGEIF","AVERAGEIFS","BETADIST","BETAINV","BINOMDIST","CORREL","COUNT","COUNTA","COUNTBLANK","COUNTIF","COUNTIFS","COUNTUNIQUE","COVARIANCEP","COVARIANCES","DEVSQ","EXPONDIST","FDIST","FINV","FISHER","FISHERINV","FORECAST","FREQUENCY","GAMMA","GAMMALN","GAUSS","GEOMEAN","GROWTH","HARMEAN","HYPGEOMDIST","INTERCEPT","KURT","LARGE","LINEST","LOGNORMDIST","LOGNORMINV","MAX","MAXA","MEDIAN","MIN","MINA","MODEMULT","MODESNGL","NORMDIST","NORMINV","NORMSDIST","NORMSINV","PEARSON","PERCENTILEEXC","PERCENTILEINC","PERCENTRANKEXC","PERCENTRANKINC","PERMUT","PERMUTATIONA","PHI","POISSONDIST","PROB","QUARTILEEXC","QUARTILEINC","RANKAVG","RANKEQ","RSQ","SKEW","SKEWP","SLOPE","SMALL","STANDARDIZE","STDEVA","STDEVP","STDEVPA","STDEVS","STEYX","TDIST","TINV","TRIMMEAN","VARA","VARP","VARPA","VARS","WEIBULLDIST","ZTEST","CHAR","CLEAN","CODE","CONCATENATE","EXACT","FIND","LEFT","LEN","LOWER","MID","NUMBERVALUE","PROPER","REGEXEXTRACT","REGEXMATCH","REGEXREPLACE","REPLACE","REPT","RIGHT","ROMAN","SEARCH","SPLIT","SUBSTITUTE","T","TRIM","UNICHAR","UNICODE","UPPER"];
    
    jQuery('.configurator-fabric-item').click(function() {
        configuratorfabricitem(this);
    });
    
    function configuratorfabricitem(thisval){
        var getidval = jQuery(thisval).attr("for");
        var getclassname = jQuery(thisval).attr("class");
        var getclassnamesplit = getclassname.split(' ');
        
        
        if(jQuery.inArray("maincomponentitem", getclassnamesplit) !== -1){
            
            return false;
        }
        
        if(jQuery.inArray("headertype", getclassnamesplit) !== -1){
            jQuery(thisval).addClass('selected');
        }else{
                
            if(jQuery.inArray("selected", getclassnamesplit) !== -1){
                jQuery("#"+getidval).click(function(){
                    jQuery(this).prop( "checked", false );
                });
                 
            if(!jQuery(thisval).hasClass("default-fabric-type-selected")){
                jQuery(thisval).removeClass('selected');
            }else{
                jQuery(thisval).removeClass("default-fabric-type-selected")
            }
    
                
                /*if(jQuery(thisval).hasClass("default-fabric-type-selected")){
                    jQuery(thisval).addClass('selected');
                }*/
                
                if(jQuery.inArray("mainfabric", getclassnamesplit) !== -1){
                    jQuery('.configurator-main-fabric').css('background-image', 'none');
                }
                if(jQuery.inArray("borderposition", getclassnamesplit) !== -1){
                    jQuery('#borderposition').val('');
                }
                if(jQuery.inArray("borderratio", getclassnamesplit) !== -1){
                    jQuery('#borderratio').val('');
                }
                if(jQuery.inArray("bordercolor", getclassnamesplit) !== -1){
                    jQuery('#borderfabric').val('');
                }
                
            }else{
                jQuery("#"+getidval).click(function(){
                    jQuery(this).prop( "checked", true );
                });
                if(jQuery.inArray("curtainlining", getclassnamesplit) !== -1){
                    jQuery('label.curtainlining').removeClass('selected');
                }
                if(jQuery.inArray("curtainliningnew", getclassnamesplit) !== -1){
                    jQuery('label.curtainliningnew').removeClass('selected');
                }
                if(jQuery.inArray("curtainliningnewtwo", getclassnamesplit) !== -1){
                    jQuery('label.curtainliningnewtwo').removeClass('selected');
                }
                if(jQuery.inArray("subcurtainliningnew", getclassnamesplit) !== -1){
                    jQuery('label.subcurtainliningnew').removeClass('selected');
                }
                if(jQuery.inArray("subcurtainliningnewtwo", getclassnamesplit) !== -1){
                    jQuery('label.subcurtainliningnewtwo').removeClass('selected');
                }
                if(jQuery.inArray("pairsingle", getclassnamesplit) !== -1){
                    jQuery('label.pairsingle').removeClass('selected');
                }
                if(jQuery.inArray("componentsub", getclassnamesplit) !== -1){
                    jQuery('label.componentsub').removeClass('selected');
                }
                if(jQuery.inArray("componentsuballowance", getclassnamesplit) !== -1){
                    jQuery('label.componentsuballowance').removeClass('selected');
                }
                if(jQuery.inArray("borderposition", getclassnamesplit) !== -1){
                    jQuery('label.borderposition').removeClass('selected');
                }
                if(jQuery.inArray("borderratio", getclassnamesplit) !== -1){
                    jQuery('label.borderratio').removeClass('selected');
                }
                if(jQuery.inArray("mainfabric", getclassnamesplit) !== -1){
                    jQuery('label.mainfabric').removeClass('selected');
                }
                if(jQuery.inArray("headerposition", getclassnamesplit) !== -1){
                    jQuery('label.headerposition').removeClass('selected');
                }
                if(jQuery.inArray("bordercolor", getclassnamesplit) !== -1){
                    jQuery('label.bordercolor').removeClass('selected');
                }
                jQuery(thisval).addClass('selected');
            }
        }
        if(jQuery.inArray("mainfabric", getclassnamesplit) !== -1){
            changecolor(thisval);
        }
    }
    
    function getComponentSubList(thisval,parameterId){
        var getcheckmultipleinput = jQuery(thisval).attr("checkmultipleinput");
        var getclassname = jQuery(thisval).attr("class");
        var get_parameter_value = jQuery(thisval).attr("get_parameter_value");
        var getclassnamesplit = getclassname.split(' ');
        
        var $arr = {};
         jQuery('.component_sub').each(function(){
            var $subcomponent_id = jQuery(this).find(':selected').attr('title');
                    $arr[$subcomponent_id] = jQuery(this).find(':selected').val();
        })
         
         if(getcheckmultipleinput == 0){
            if(jQuery.inArray("selected", getclassnamesplit) == -1 || getclassnamesplit == ""){
                jQuery('.componentsub_'+parameterId).remove();
                jQuery('.componentsub_end').remove();
                return false;
            }
        }
        var blindstype = jQuery('#blindstype').val();
      
    
        jQuery('.componentsub_'+parameterId).remove();
      //  jQuery('.componentsub_end').remove();
        
        
        var maincomponent = [];
        jQuery('.maincomponent_'+parameterId+'.selected').each(function(i, e) {
            maincomponent.push(jQuery(this).attr('datasub'));
            if(getcheckmultipleinput == 1){
                jQuery(this).addClass('selected');
    
            }
        });
        if(maincomponent && maincomponent.length > 0){
            jQuery.ajax(
            {
                url     : ajaxurl,
                data    : {mode:'getcomponentsublist',action:'getcomponentsublist',maincomponent:maincomponent,blindstype:blindstype,parameterId:parameterId,selected_args:$arr},
                type    : "POST",
                dataType: 'JSON',
                async: false,
                success: function(response){
                    if(response.result != ''){
                        jQuery('#'+parameterId).after(response.ComponentSubList);
                        jQuery('.componentsub').click(function() {
                            configuratorfabricitem(this);
                        });
                        /* jQuery('.component_sub').change(function() {
                            jQuery(this).find('.selected').removeClass('selected');
                            jQuery(this).find(':selected').addClass('selected');
                        }); */
                        
                        jQuery(".component_sub_"+parameterId).each(function(){
                            
                            var attr = jQuery(this).attr('multiple');
                            if (typeof attr !== 'undefined' && attr !== false) {
                               var $eventsubSelect = jQuery(this).select2();
                                $eventsubSelect.on('select2:opening select2:closing', function( event ) {
                                    var $searchsubfield = jQuery(this).parent().find('.select2-search__field');
                                    $searchfield.prop('disabled', true);
                                });
                            }else{
                                var $eventsubSelect = jQuery(this);
                                $eventsubSelect.select2({
                                    minimumResultsForSearch: -1
                                });	
                            }
                        });
                        jQuery('.component_sub').select2({minimumResultsForSearch: -1});
                    }
                    
                }
            });
        }
    }
    
    function getsubcurtainliningnew(liningid,parameterid,thisval){
        var getidval = jQuery(thisval).attr("id");
        var getclassname = jQuery(thisval).attr("class");
        var getclassnamesplit = getclassname.split(' ');
       // if(jQuery.inArray("selected", getclassnamesplit) == -1){
       //     jQuery('.curtainliningsub_'+parameterid).remove();
       // }else{
            jQuery('.curtainliningsub_'+parameterid).remove();
            if(liningid != ''){
                jQuery.ajax(
                {
                    url     : ajaxurl,
                    data    : {mode:'getsubcurtainliningnew',action:'getsubcurtainliningnew',liningid:liningid,parameterid:parameterid,method:'1'},
                    type    : "POST",
                    dataType: 'JSON',
                    async: false,
                    success: function(response){
                        if(response.result != ''){
                            jQuery('#'+parameterid).after(response.CurtainliningSubList);
                            jQuery('.subcurtainliningnew').click(function() {
                                configuratorfabricitem(this);
                            });
                        jQuery(".subcurtainliningnew").each(function(){
                            var attr = jQuery(this).attr('multiple');
                            if (typeof attr !== 'undefined' && attr !== false) {
                               var $eventnewSelect = jQuery(this).select2();
                                $eventnewSelect.on('select2:opening select2:closing', function( event ) {
                                    var $searchnewfield = jQuery(this).parent().find('.select2-search__field');
                                    $searchnewfield.prop('disabled', true);
                                });
                            }else{
                                var $eventnewSelect = jQuery(this);
                                $eventnewSelect.select2({
                                    minimumResultsForSearch: -1
                                });	
                            }
                        });
                        jQuery('.subcurtainliningnew').select2({minimumResultsForSearch: -1});
                        }
                    }
                });
            }
        //}
    }
    
    function getsubcurtainliningnewtwo(liningid,parameterid,thisval){
        var getidval = jQuery(thisval).attr("id");
        var getclassname = jQuery(thisval).attr("class");
        var getclassnamesplit = getclassname.split(' ');
       // if(jQuery.inArray("selected", getclassnamesplit) == -1){
       //     jQuery('.curtainliningsub_'+parameterid).remove();
       // }else{
            jQuery('.curtainliningsub_'+parameterid).remove();
            if(liningid != ''){
                jQuery.ajax(
                {
                    url     : ajaxurl,
                    data    : {mode:'getsubcurtainliningnewtwo',action:'getsubcurtainliningnewtwo',liningid:liningid,parameterid:parameterid,method:'2'},
                    type    : "POST",
                    dataType: 'JSON',
                    async: false,
                    success: function(response){
                        if(response.result != ''){
                            jQuery('#'+parameterid).after(response.CurtainliningSubList);
                            jQuery('.subcurtainliningnewtwo').click(function() {
                                configuratorfabricitem(this);
                            });
                        jQuery(".subcurtainliningnewtwo").each(function(){
                            var attr = jQuery(this).attr('multiple');
                            if (typeof attr !== 'undefined' && attr !== false) {
                               var $eventtwoSelect = jQuery(this).select2();
                                $eventtwoSelect.on('select2:opening select2:closing', function( event ) {
                                    var $searchtwofield = jQuery(this).parent().find('.select2-search__field');
                                    $searchtwofield.prop('disabled', true);
                                });
                            }else{
                                var $eventtwoSelect = jQuery(this);
                                $eventtwoSelect.select2({
                                    minimumResultsForSearch: -1
                                });	
                            }
                        });
                        jQuery('.subcurtainliningnewtwo').select2({minimumResultsForSearch: -1});
                        }
                    }
                });
            }
       // }
    }
    
    function changecolor(thisval){ 
    
        /*jQuery('label.mainfabric').removeClass(' selected');
        jQuery(thisval).addClass(' selected');*/
            if(jQuery(thisval).hasClass('selected') ){
                
                var dataimg = jQuery(thisval).attr('data-img');
                jQuery('.configurator-main-fabric').css('background-image', 'url('+dataimg+')');
            }else{
                
                jQuery('.configurator-main-fabric').css('background-image', 'none');
            }
        
        
    }
    var sort = [];
    var chkele = [];
    var names = [];
    function filterdiselect(id){
      jQuery('#'+id).next('label').trigger('click');
    }
    function clear_curtain_all_filter(){
        jQuery( ".changefiltercurtain.selected" ).each(function( index ) {
            jQuery(this).trigger('click');
        });
    }
    function changefiltercurtain(thisval,value,key,name,id){
        jQuery('#coverspin').show();
        if(jQuery("#serach_input_color").val() != '' ){
            jQuery("#serach_input_color").val('');
            jQuery("#serach_input_color").trigger('keyup');
        }
        jQuery('.accordion-title').removeClass('active');
        jQuery('.accordion-inner').css('display','none');
            var valArr = value.split(",");	
            jQuery(thisval).toggleClass(' selected');
            jQuery(thisval).closest("li").toggleClass(' selected');
            if(jQuery(thisval).hasClass('selected')){
                names.push(name+'~~'+id);
                for (var i = 0; i < valArr.length; i++) {
                    sort.push(valArr[i]+'~~'+key);
                }
            }else{
                for (var i = 0; i < valArr.length; i++) {
                    removeItemOnce(sort, valArr[i]+'~~'+key);
                    removeItemOnce(names, name+'~~'+id);
                }
            }
        
        setTimeout(function(){
            var plugindir = '<?php echo plugin_dir_url( __DIR__ );?>';
            jQuery('.curtain-image-filter-box').css('display','none');
            jQuery('.curtain-image-filter-box').removeClass('filtered');
            if(names.length > 0){
                var nametext ='';
                jQuery.each(names, function( index, nameobj ) {
                    var nameobj_Arr = nameobj.split("~~");
                    //nametext += '<span class="selectedfiltersub">'+nameobj+' <span onclick="filterdiselect('/"'+id+'"/');">x</span></span>';
                    //nametext += "<span class='selectedfiltersub'>"+nameobj_Arr[0]+"<span onclick='filterdiselect(\"" + nameobj_Arr[1] + "\");'><img src="+plugindir+"Shortcode-Source/image/curtains_icons/close_15-1.png'  width='12' height='12'></span></span>";
                });
                nametext +="<span onclick='clear_curtain_all_filter();' class='clear_curtain_all_filter'>Clear all</span>";
                
                jQuery('.selectedfilter').html(nametext);
            }else{
                jQuery('.selectedfilter').html('');
            }
            if(sort.length > 0){
                jQuery.each(sort, function( index, value ) {
                    var val_Arr = value.split("~~");	
                    jQuery('#'+val_Arr[0]).css('display','block');
                    jQuery('#'+val_Arr[0]).addClass('filtered');
                });	
            }else{
                jQuery('.curtain-image-filter-box').css('display','block');
            }
            jQuery('#coverspin').hide();
        }, 3000);
    }
    function removeItemOnce(arr, value) {
      var index = arr.indexOf(value);
      if (index > -1) {
        arr.splice(index, 1);
      }
      return arr;
    }
    function borderratio(thisval){
        /*jQuery('label.borderratio').removeClass(' selected');
        jQuery(thisval).addClass(' selected');*/
        var borderratio = jQuery(thisval).attr('data-ratio');
        jQuery('#borderratio').val(borderratio);
        changeborder();
    }
    
    function borderposition(thisval){
        /*jQuery('label.borderposition').removeClass(' selected');
        jQuery(thisval).addClass(' selected');*/
        var borderposition = jQuery(thisval).attr('data-position');
        jQuery('#borderposition').val(borderposition);
        changeborder();
    }
    
    function changebordercolor(thisval){
        /*jQuery('label.bordercolor').removeClass(' selected');
        jQuery(thisval).addClass(' selected');*/
        var dataimg = jQuery(thisval).attr('data-img');
        jQuery('#borderfabric').val(dataimg);
        changeborder();
    }
    
    function changeborder(){
        var borderratio = jQuery('#borderratio').val();
        var borderposition = jQuery('#borderposition').val();
        var borderfabric = jQuery('#borderfabric').val();
        
        jQuery('.configurator-border-fabric').attr('style', '');
        if(borderfabric != '' && borderposition != '' && borderratio != '') jQuery('.configurator-border-fabric').css('background-image', 'url('+borderfabric+')');
        if(borderratio != '') jQuery('.configurator-border-fabric').css('height', borderratio+'%');
        
        if(borderposition != ''){
            if(borderposition == 'bottom'){
                var calposition = 100-borderratio;
                jQuery('.configurator-border-fabric').css('top', 'calc('+calposition+'%)');
            }else{
                jQuery('.configurator-border-fabric').css(borderposition, '0px');
            }
        }
    }
    
    function changeheadertype(thisval){
        jQuery('label.headertype').removeClass(' selected');
        jQuery(thisval).addClass(' selected');
        jQuery('#cover-spin').show();
        jQuery('.headertype').removeClass('active');
        jQuery('.headerposition').removeClass('active');
        jQuery(thisval).addClass('active');
        var headertypename = jQuery(thisval).attr('data-name');
        jQuery('#headertypename').val(headertypename);
        if(headertypename == 'eyelet'){
            jQuery('.configurator.curtain.bordered').addClass('eyelet');
        }else{
            jQuery('.configurator.curtain.bordered').removeClass('eyelet');
        }
        updateposition();
        
        var minprice = jQuery(thisval).attr('data-minprice');
        jQuery('.showprice').html(minprice);
        
        jQuery('#vendorid').val(jQuery(thisval).attr('getvendorid'));
        
        var producttypename = jQuery(thisval).attr('datatypename');
        jQuery('.prodescprotitle_curtain').html('Design your '+producttypename+' curtains');
        url_producttypename = jQuery(thisval).attr('data-name');
        url_producttypeid = jQuery(thisval).attr('data-type-id');
        
        jQuery('#producttypeid').val(url_producttypeid);
        
        jQuery('label.headerposition').removeClass('selected');
        jQuery('input', '.position').each(function() {
            if (jQuery(this).attr('type') === 'radio' || jQuery(this).attr('type') === 'checkbox'){
                if(jQuery(this).is(':checked')) {
                    jQuery(this).prop('checked', false);
                }
            }
        });
        var url_curtaincolor_id = '<?=$url_curtaincolor_id?>';
        var currentURL = window.location.protocol + "//" + window.location.host+'/'+url_curtains_config+'/'+url_producttypename+'/'+url_productid+'/'+url_producttypeid+'/'+url_curtaincolor_id+'/';
        window.history.pushState({ path: currentURL }, '', currentURL);
    }
    
    function updateposition(){
        var headertypename = jQuery('#headertypename').val();
        if(jQuery(".headerposition").length > 0){
        jQuery(".headerposition").each(function (e) {
            var dataposition = jQuery(this).attr('data-position');
            
            for (key in configuratormainheadertype) {
                if(headertypename == key){
                    for (subkey in configuratormainheadertype[key]) { 
                        if(subkey == dataposition){
                            jQuery(this).attr('data-img-new',configuratormainheadertype[key][subkey]);
                        }
                        jQuery(".configurator-main-headertype").attr('src', configuratormainheadertype[key]['center']);
                    }
                }
            }
        });
        }else{
            jQuery(".configurator-main-headertype").attr('src', configuratormainheadertype[headertypename]['center']);
            jQuery('#cover-spin').hide();
        }
    }
    
    function dropdownparameter(thisval,id){
        var getcheckmultiple = jQuery(thisval).attr("checkmultiplelabel");
        if(getcheckmultiple == 0){
            var getidval = jQuery(thisval).attr("for");
            var getclassname = jQuery(thisval).attr("class");
            var getclassnamesplit = getclassname.split(' ');
            if(jQuery.inArray("selected", getclassnamesplit) !== -1){
                jQuery("#"+getidval).click(function(){
                    jQuery(this).prop( "checked", false );
                });
                jQuery('.dropdownparameter-'+id).removeClass('selected');
            }else{
                jQuery("#"+getidval).click(function(){
                    jQuery(this).prop( "checked", true );
                });
                jQuery('.dropdownparameter-'+id).removeClass(' selected');
                jQuery(thisval).addClass(' selected');
            }
        }else{
            jQuery('.dropdownparameter-'+id).removeClass(' selected');
            jQuery(thisval).addClass(' selected');
        }
    }
    
    function setposition(thisval){
        /*jQuery('label.headerposition').removeClass(' selected');
        jQuery(thisval).addClass(' selected');*/
        jQuery('#cover-spin').show();
        jQuery('.headerposition').removeClass('active');
        jQuery(thisval).addClass('active');
        var dataposition = jQuery(thisval).attr('data-position');
        var dataimg = jQuery(thisval).attr('data-img-new');
        if(dataimg != '') jQuery(".configurator-main-headertype").attr('src', dataimg);
    }
    
    //setup before functions
    var typingTimer;                //timer identifier
    var doneTypingInterval = 500; 
    var $input = jQuery('#width, #drope, .othersvalue');
    
    //on keyup, start the countdown
    $input.on('keyup', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });
    
    //on keydown, clear the countdown 
    $input.on('keydown', function () {
        clearTimeout(typingTimer);
    });
    
    //user is "finished typing," do something
    function doneTyping () {
        //do something
        showorderdetails();
    }
    
    function showorderdetails(){
        jQuery('.loading-spin').css('display','block');
        
        setTimeout(function(){	
            var unit = jQuery('input[name="unit"]:checked').val();
            var widthparametername = jQuery('#width').attr('parametername');
            var getwidthparameterid = jQuery('#width').attr('getparameterid');
            var width = jQuery('#width').val();
            var widthfraction = jQuery('#widthfraction').val();
            var dropeparametername = jQuery('#drope').attr('parametername');
            var getdropeparameterid = jQuery('#drope').attr('getparameterid');
            var drope = jQuery('#drope').val();
            var dropfraction = jQuery('#dropfraction').val();
            var html ='';
            var showorderitemobj = {};
            var parameternamevalueobj = {};
            var curtainliningnewobj = {};
            var curtainliningnew2obj = {};
            var getparameteridval = {};
            if(width != ''){
                 var widthfraction_val='';
                 if(unit == 'inch'){
                    if(widthfraction == 1){
                        widthfraction_val = " 1/8";
                    }else if(widthfraction == 2){
                        widthfraction_val = " 1/4";
                    }else if(widthfraction == 3){
                        widthfraction_val = " 3/8";
                    }else if(widthfraction == 4){
                        widthfraction_val = " 1/2";
                    }else if(widthfraction == 5){
                        widthfraction_val = " 5/8";
                    }else if(widthfraction == 6){
                        widthfraction_val = " 3/4";
                    }else if(widthfraction == 7){
                        widthfraction_val = " 7/8";
                    }
                }
                 html += '<tr class="paramlable"><td>'+widthparametername+':</td><td><strong class="paramval">'+width+widthfraction_val+' '+unit+'</strong></td></tr>';
                 showorderitemobj[widthparametername] = width+widthfraction_val+' '+unit;
                 if (typeof getwidthparameterid !== 'undefined' && getwidthparameterid) {
                    getparameteridval[getwidthparameterid] = width;
                 }
                 widthparametername = replacespecialcharacter(widthparametername);
                 parameternamevalueobj[widthparametername] = parseFloat(width);
            }
            if(drope != ''){
                
                 var dropfraction_val='';
                 if(unit == 'inch'){
                    if(dropfraction == 1){
                        dropfraction_val = " 1/8";
                    }else if(dropfraction == 2){
                        dropfraction_val = " 1/4";
                    }else if(dropfraction == 3){
                        dropfraction_val = " 3/8";
                    }else if(dropfraction == 4){
                        dropfraction_val = " 1/2";
                    }else if(dropfraction == 5){
                        dropfraction_val = " 5/8";
                    }else if(dropfraction == 6){
                        dropfraction_val = " 3/4";
                    }else if(dropfraction == 7){
                        dropfraction_val = " 7/8";
                    }
                }
                
                html += '<tr class="paramlable"><td>'+dropeparametername+':</td><td><strong class="paramval">'+drope+dropfraction_val+' '+unit+'</strong></td></tr>';
                showorderitemobj[dropeparametername] = drope+dropfraction_val+' '+unit;
                if (typeof getdropeparameterid !== 'undefined' && getdropeparameterid) {
                    getparameteridval[getdropeparameterid] = drope;
                 }
                dropeparametername = replacespecialcharacter(dropeparametername);
                parameternamevalueobj[dropeparametername] = parseFloat(drope);
            }
            parameternamevalueobj["MEASUREMENT"] = unit;
            parameternamevalueobj["Qty"] = 1;
            parameternamevalueobj["ComponentQty"] = 0;
            parameternamevalueobj["Pillows_Qty"] = 0;
            parameternamevalueobj["Tiebacks_Qty"] = 0;
            
            var prevparametername='';
            var get_parameter_value='';
            var displayparametername='';
            var displayparametervalue='';
            var showcheckboxtemobj = [];
            jQuery('.showorderdetails input, .blindmatrix-select2, .showorderdetails').each(function() {
                if(jQuery(this).hasClass('blindmatrix-select2')){
                    var current_value = jQuery(this).find(':selected');
                }else{
                    var current_value = jQuery(this);
                }
                if (current_value.attr('type') === 'radio' || current_value.attr('type') === 'checkbox' || current_value.attr('type') === 'text' || current_value.attr('type') === 'number' || current_value.attr('type') === 'select' ) {
                    var parametername = current_value.attr('parametername');
                    var getallparameterid = current_value.attr('getparameterid');
    
                    if (current_value.attr('type') === 'text' || current_value.attr('type') === 'number') {
                        var getparametervalue = current_value.val();
                        if(getparametervalue != ''){
                            html += '<tr class="paramlable"><td>'+parametername+':</td><td><strong class="paramval">'+getparametervalue+'</strong></td></tr>';
                            showorderitemobj[parametername] = getparametervalue;
                            if (typeof getallparameterid !== 'undefined' && getallparameterid) {
                            getparameteridval[getallparameterid] = getparametervalue;
                            }
                            
                            parametername = replacespecialcharacter(parametername);
                            if(current_value.attr('type') === 'number'){
                                parameternamevalueobj[parametername] = parseFloat(getparametervalue);
                            }else{
                                parameternamevalueobj[parametername] = getparametervalue;
                            }
                        }
                    }else{
                        
                        var getparametervalue = current_value.attr('getparametervalue');
                        var getparameterlistid = current_value.attr('getparameterlistid');
                        var get_parameter_value = current_value.attr('get_parameter_value');
    
                        var name = current_value.attr('radiobutton');
                        if(current_value.is(':selected') || current_value.is(':checked')) {
                            
                            if(current_value.attr('type') === 'checkbox' || jQuery(this).hasClass('multi_select')){
                                
                                if(prevparametername != parametername){
                                    prevparametername = parametername;
                                    displayparametername = parametername+':';
                                }else{
                                    displayparametername = '';
                                }
                                getparametervalue = jQuery(this).find(':selected').toArray().map(item => item.text).join(", ");
                                showcheckboxtemobj.push({"parametername": parametername, "parametervalue": getparametervalue, "getallparameterid": getallparameterid});
                                
                                html += '<tr class="paramlable maincom_'+getallparameterid+'"><td>'+displayparametername+'</td><td><strong class="paramval">'+getparametervalue+'</strong></td></tr>';
                            }else if(typeof parametername !== 'undefined' && typeof getparametervalue !== 'undefined'){
                                html += '<tr class="paramlable"><td>'+parametername+':</td><td><strong class="paramval">'+getparametervalue+'</strong></td></tr>';
                            }
                            showorderitemobj[parametername] = getparametervalue;
                            if (typeof getallparameterid !== 'undefined' && getallparameterid) {
                            getparameteridval[getallparameterid] = get_parameter_value;
                            }
                            if(typeof parametername != 'undefined'){
                                parametername = replacespecialcharacter(parametername);
                            }
                            parameternamevalueobj[parametername] = getparametervalue;
                            
                            if(getparameterlistid == 18){
                                var getcomponentqtyname = current_value.attr('get_component_qty_name');
                                var getcomponentqty = current_value.attr('get_component_qty');
                                parameternamevalueobj[getcomponentqtyname] = parseFloat(getcomponentqty);
                                
                            }
                            
                            if(getparameterlistid == 10){
                                var getpricetableprice = current_value.attr('getpricetableprice');
                                parameternamevalueobj["PriceTablePrice"] = parseFloat(getpricetableprice);
                                var getparametertypeallowance = current_value.attr('getparametertypeallowance');
                                parameternamevalueobj["DefaultFullness"] = parseFloat(getparametertypeallowance);
                                
                            }
                            
                            if(getparameterlistid == 17){
                                var getliningpermeter = current_value.attr('getliningpermeter');
                                parameternamevalueobj["LiningPerMeter"] = parseFloat(getliningpermeter);
                                var getmarkupperwidth = current_value.attr('getmarkupperwidth');
                                parameternamevalueobj["MakeupPerWidth"] = parseFloat(getmarkupperwidth);
                                var getliningmarkup = current_value.attr('getliningmarkup');
                                parameternamevalueobj["Liningmarkup"] = parseFloat(getliningmarkup);
                            }
    
                            if(getparameterlistid == 16 || getparameterlistid == 21){
                                var getstandardformulaname = current_value.attr('getstandardformulaname');
                                var getcontinuousformulaname = current_value.attr('getcontinuousformulaname');
                                var getstandardformulavalue = current_value.attr('getstandardformulavalue');
                                var getcontinuousformulavalue = current_value.attr('getcontinuousformulavalue');
                                var getscfabricname = current_value.attr('getscfabricname');
                                var getscpricepermeter = current_value.attr('getscpricepermeter');
                                parameternamevalueobj["Pricepermeter"] = parseFloat(getscpricepermeter);
                                if(getparameterlistid == 16){
                                    var getscpatternrepeat = current_value.attr('getscpatternrepeat');
                                    var getscweighted = current_value.attr('getscweighted');
                                    var getscfabrictype = current_value.attr('getscfabrictype');
                                    var getscfabricwidth = current_value.attr('getscfabricwidth');
                                    parameternamevalueobj["StandardContinuous"] = "Standard";
                                    jQuery('#sel_sub_product').val(1);
                                    parameternamevalueobj["PatternRepeat"] = parseFloat(getscpatternrepeat);
                                    parameternamevalueobj["Weighted"] = parseFloat(getscweighted);
                                    parameternamevalueobj["FabricType"] = getscfabrictype;
                                    parameternamevalueobj["FabricWidth"] = parseFloat(getscfabricwidth);
                                    parameternamevalueobj["FabricDrop"] = 0;
                                }
                                if(getparameterlistid == 21){
                                    var getscfabricdrop = current_value.attr('getscfabricdrop');
                                    parameternamevalueobj["StandardContinuous"] = "Continuous";
                                    jQuery('#sel_sub_product').val(2);
                                    parameternamevalueobj["PatternRepeat"] = 0;
                                    parameternamevalueobj["Weighted"] = 0;
                                    parameternamevalueobj["FabricType"] = 0;
                                    parameternamevalueobj["FabricWidth"] = 0;
                                    parameternamevalueobj["FabricDrop"] = getscfabricdrop;
                                }
                            }
                            
                            if(getparameterlistid == 25){
                                curtainliningnewobj["liningnewparameterlistid"] = getparameterlistid;
                                var getparameterid = current_value.attr('getparameterid');
                                curtainliningnewobj["liningnewparameterid"] = getparameterid;
                            }
                            
                            if(getparameterlistid == 27){
                                curtainliningnew2obj["liningnewparameterlistid"] = getparameterlistid;
                                var getparameterid = current_value.attr('getparameterid');
                                curtainliningnew2obj["liningnewparameterid"] = getparameterid;
                            }
    
                            var getliningmethod = current_value.attr('getliningmethod');
                            if(getliningmethod == 1){
                                var getliningpermeter1 = current_value.attr('getliningpermeter1');
                                var getmarkupperwidth1 = current_value.attr('getmarkupperwidth1');
                                parameternamevalueobj["TotalLiningPerMeter"] = parseFloat(getliningpermeter1);
                                parameternamevalueobj["TotalMakeupPerWidth"] = parseFloat(getmarkupperwidth1);
                                
                                var getsubliningid = current_value.attr('getsubliningid');
                                curtainliningnewobj["liningnewsubliningid"] = getsubliningid;
                                var getsubsubliningid = current_value.attr('getsubsubliningid');
                                curtainliningnewobj["liningnewsubsubliningid"] = getsubsubliningid;
                            }
                            if(getliningmethod == 2){
                                var getliningpermeter2 = current_value.attr('getliningpermeter2');
                                var getmarkupperwidth2 = current_value.attr('getmarkupperwidth2');
                                parameternamevalueobj["TotalLiningPerMeter2"] = parseFloat(getliningpermeter2);
                                parameternamevalueobj["TotalMakeupPerWidth2"] = parseFloat(getmarkupperwidth2);
                                var getsubliningid = current_value.attr('getsubliningid');
                                curtainliningnew2obj["liningnewsubliningid"] = getsubliningid;
                                var getsubsubliningid = current_value.attr('getsubsubliningid');
                                curtainliningnew2obj["liningnewsubsubliningid"] = getsubsubliningid;
                            }
                            
                        }
                    }
                }
                
            });
            parameternamevalueobj["ActualFullness"] = 0;
            parameternamevalueobj["SubComponentQty"] = 0;
            parameternamevalueobj["Fabricqty"] = '12.6';
            parameternamevalueobj["Leftreturn"] = 0;
            parameternamevalueobj["Rightreturn"] = 0;
            parameternamevalueobj["Overlap"] = 0;
            parameternamevalueobj["Suspensionpointtowall"] = 0;
            parameternamevalueobj["Extrafabrictoorder"] = 0;
            parameternamevalueobj["FabricMarkUp"] = 0;
            parameternamevalueobj["OverrideWidths"] = 0;
            parameternamevalueobj["OVERRIDEFABRICPRICE"] = 0;
    
            var sel_html = '<table class="getprice_table"><tbody>';
            sel_html += html;
            sel_html += '</tbody></table>';
            jQuery('#allparametervalue').html(sel_html);
            
            var showcheckboxtemobject = groupByKey(showcheckboxtemobj, 'parametername');
            var parameternamecheck={};
            jQuery.each( showcheckboxtemobject, function( parametername, showcheckboxtemarray ) {
                var parametervalue='';
                var getmaincomid='';
                var getcomselsetails='';
                jQuery.each( showcheckboxtemarray, function( key, value ) {
                    if(key == 0){
                        parametervalue += value.parametervalue;
                    }else{
                        parametervalue += ', '+value.parametervalue;
                    }
                    getmaincomid = value.getallparameterid;
                });
                showorderitemobj[parametername] = parametervalue;
                jQuery("tbody > .maincom_" + getmaincomid + " ~ .maincom_" + getmaincomid).remove();
                    getcomselsetails = '<td>'+parametername+'</td><td><strong class="paramval">'+parametervalue+'</strong></td>';
                jQuery('.maincom_'+getmaincomid).html(getcomselsetails);
            });
            
            jQuery("#showorderitemlist").val('');
            jQuery("#showorderitemlist").val(JSON.stringify(showorderitemobj));
    
            jQuery("#getallcurtainliningnew").val('');
            jQuery("#getallcurtainliningnew").val(JSON.stringify(curtainliningnewobj));
            jQuery("#getallcurtainliningnew2").val('');
            jQuery("#getallcurtainliningnew2").val(JSON.stringify(curtainliningnew2obj));
            
            jQuery('#mode').val("getcurtainprice");
            jQuery.ajax(
            {
                url     : ajaxurl,
                data    : jQuery("#submitform").serialize(),
                type    : "POST",
                dataType: 'JSON',
                success: function(response){
                    jQuery('#errmsg_width').html('');
                    jQuery('#errmsg_drop').html('');
                    jQuery('.loading-spin').css('display','none');
                    if(response.success == true && response.pricetableprice > 0 && response.pricetableprice != null){
                    
                        parameternamevalueobj["PriceTablePrice"] = parseFloat(response.pricetableprice);
    
                        //Curtain Allowance Variables
                        jQuery.each( response.curtain_allowance_variables, function( key, value ) {
                            var allowancename = value.allowancename;
                            allowancename = allowancename.toUpperCase();
                            allowancename = replacespecialcharacter(allowancename);
                            if (unit == 'mm') {
                                var cutval = value.value;
                            } else if (unit == 'cm') {
                                var cutval = (value.value / 10);
                            } else if (unit == 'inch') {
                                var cutval = (value.value / 25.4);
                            }
                            eval(allowancename +" = "+cutval);
                        });
    
                        //Get Curtain Parameters
                        var getselparameteridval = {};
                        var parameternameobj = ['MEASUREMENT','Qty','ComponentQty','Pillows_Qty','Tiebacks_Qty','SubComponentQty','PairSingle','StandardContinuous','LeftReturn','RightReturn','Overlap','ActualFullness','DefaultFullness','Fabricqty','Extrafabrictoorder','FabricMarkUp','MakeupPerWidth','LiningPerMeter','TotalMakeupPerWidth','TotalLiningPerMeter','TotalMakeupPerWidth2','TotalLiningPerMeter2','PriceTablePrice','PatternRepeat','Pricepermeter','Weighted','FabricType','FabricWidth','FabricDrop','Suspensionpointtowall','Liningmarkup','OverrideWidths','OverrideFabricPrice'];
                        jQuery.each( parameterarray, function( i, val ) {
                            var formula_parametername = val.parameterName;
                            formula_parametername = replacespecialcharacter(formula_parametername);
                            parameternameobj.push(formula_parametername);
                            var defaultparameterId = val.parameterId;
                            if(isKeyExists(getparameteridval,defaultparameterId) == true){
                                getselparameteridval[defaultparameterId] = getparameteridval[defaultparameterId];
                            }else{
                                getselparameteridval[defaultparameterId] = '';
                            }
                        });
    
                        jQuery("#getparameteridvallist").val('');
                        jQuery("#getparameteridvallist").val(JSON.stringify(getselparameteridval));
                        
                        jQuery.each( parameternameobj, function( get_key, get_val ) {
                            get_val = get_val.toUpperCase();
                            eval(get_val +" = "+"\"" + '' + "\"");
                        });
    
                        jQuery.each( parameternamevalueobj, function( ikey, getval ) {
                            ikey = ikey.toUpperCase();
                            var valexist = checkValueinarray(ikey, parameternameobj);
                            if(valexist == 1){
                                if(typeof getval === 'number'){
                                    eval(ikey +" = "+getval);
                                }else{
                                    getval = getval.toUpperCase();
                                    if(ikey != 'PILLOWS'){
                                    getval = getval.replaceAll(" ", "");
                                    }
                                    eval(ikey +" = "+"\"" + getval + "\"");
                                }
                            }
                        });
    
                        var getallformulaobj = {};
                        jQuery.each( response.curtain_formulas, function( i, val ) {
                            var formula1 = val.formula;
                            var formula = formula1.indexOf('=') == 0 ? formula1.substring(1) : formula1;
                            formula = formula.toUpperCase();
                            formula = formula.replaceAll("ROUN_UP", "ROUNDUP");
                            formula = formula.replaceAll("ROUN_DOWN", "ROUNDDOWN");
                            //formula = formula.replaceAll(" ", "");
                            formula = formula.replaceAll("CEIL", "ROUNDUP");
                            formula = formula.replaceAll("<>", "!=");
                            formula = formula.replaceAll("=", "==");
                            formula = formula.replaceAll("<==", "<=");
                            formula = formula.replaceAll(">==", ">=");
                            formula = formula.replaceAll("<>", "!=");
                            formula = formulacharacterappend(formula);
                            
                            var formulavariablename = replacespecialcharacter(val.variablename);
                            formulavariablename = formulavariablename.toUpperCase();
                            getallformulaobj[formulavariablename] = formula;
                        });
    
                        var curtainformulavaluesobj = {};
                        jQuery.each( response.curtain_formulas, function( i, val ) {
                            
                                var formulavariablename = replacespecialcharacter(val.variablename);
                                formulavariablename = formulavariablename.toUpperCase();
                                
                                var formula1 = val.formula;
                                var formula = formula1.indexOf('=') == 0 ? formula1.substring(1) : formula1;
                                formula = formula.toUpperCase();
                                formula = formula.replaceAll("ROUN_UP", "ROUNDUP");
                                formula = formula.replaceAll("ROUN_DOWN", "ROUNDDOWN");
                                //formula = formula.replaceAll(" ", "");
                                formula = formula.replaceAll("CEIL", "ROUNDUP");
                                formula = formula.replaceAll("<>", "!=");
                                formula = formula.replaceAll("=", "==");
                                formula = formula.replaceAll("<==", "<=");
                                formula = formula.replaceAll(">==", ">=");
                                formula = formula.replaceAll("<>", "!=");
                                formula = formulacharacterappend(formula);
    
                                formula = formulavariablereplace(formula,getallformulaobj);
                                formula = formula.replaceAll(",ROUNDUP", ",formulajs.ROUNDUP");
                                formula = formula.replaceAll(",ROUNDDOWN", ",formulajs.ROUNDDOWN");
                                formula = formula.replaceAll("formulajs.SPLIT", "split");
                                var reverse_val = "REVERSE";
                                var regexsearch = new RegExp("\\b"+reverse_val+"\\b","g");
                                formula = formula.replaceAll(regexsearch, "reverse");
                                
                                if (typeof PILLOWS !== 'undefined' && PILLOWS == '') {
                                    formula = formula.replaceAll('.split(" ")[0]', '');
                                };
                            
                            try{
                                
                                var getformula = stringevil(formula);
                                var formula_result = '';
                                if(typeof getformula === 'number'){
                                    eval(formulavariablename +" = "+getformula);
                                    formula_result = eval(getformula);
                                }else{
                                    eval(formulavariablename +" = "+"\"" + getformula + "\"");
                                    formula_result = eval("\""+getformula+"\"");
                                }
                                if(formula_result == NaN || formula_result == Infinity)
                                {
                                    formula_result = 0;
                                }
                                formula_result = jQuery.trim(formula_result);
                                //jQuery('#'+formulavariablename).val(formula_result);
                                
                                //console.log(formulavariablename+'--'+formula_result+'--'+formula);
                                
                                val['formulavalue'] = parseFloat(formula_result);
                                curtainformulavaluesobj[i] = val;
                            }catch(err) {
                                //console.log(err.message+'--'+formulavariablename+'--'+formula);
                                //console.log(formulavariablename+'--'+formula);
                            }
                        });
    
                        jQuery("#curtainformulavalues").val('');
                        jQuery("#curtainformulavalues").val(JSON.stringify(curtainformulavaluesobj));
                        
                        jQuery('#single_product_orgvat').val(response.orgvat);
                        jQuery('#vaterate').val(response.vaterate);
    
                        //Net price
                        var getnetprice = response.netprice;
                        if (typeof MAKEUPCHARGEOFFABRIC !== 'undefined' && MAKEUPCHARGEOFFABRIC > 0) {
                            MAKEUPCHARGEOFFABRIC = checkinfinity(MAKEUPCHARGEOFFABRIC);
                            getnetprice += MAKEUPCHARGEOFFABRIC;
                        };
                        if (typeof PRICEOFTOTALFABRIC !== 'undefined' && PRICEOFTOTALFABRIC > 0) {
                            PRICEOFTOTALFABRIC = checkinfinity(PRICEOFTOTALFABRIC);
                            getnetprice += PRICEOFTOTALFABRIC;
                        };
                        if (typeof MAKEUPCHARGEOFLINING !== 'undefined' && MAKEUPCHARGEOFLINING > 0) {
                            MAKEUPCHARGEOFLINING = checkinfinity(MAKEUPCHARGEOFLINING);
                            getnetprice += MAKEUPCHARGEOFLINING;
                        };
                        if (typeof LININGPRICE !== 'undefined' && LININGPRICE > 0) {
                            LININGPRICE = checkinfinity(LININGPRICE);
                            getnetprice += LININGPRICE;
                        };
                        if (typeof TOTAL_HEADING_TAPE_PRICE !== 'undefined' && TOTAL_HEADING_TAPE_PRICE > 0) {
                            TOTAL_HEADING_TAPE_PRICE = checkinfinity(TOTAL_HEADING_TAPE_PRICE);
                            getnetprice += TOTAL_HEADING_TAPE_PRICE;
                        };
                        
                        //Item cost
                        var getitemcost = response.itemcost;
                        if (typeof PRICEOFTOTALFABRIC !== 'undefined' && PRICEOFTOTALFABRIC > 0) {
                            PRICEOFTOTALFABRIC = checkinfinity(PRICEOFTOTALFABRIC);
                            getitemcost += PRICEOFTOTALFABRIC;
                        };
                        if(response.pricetableprice > 0){
                            getitemcost += response.pricetableprice;
                        }
                        
                        var vatvalue = 0;
                        if(response.vatvalue > 0){
                            vatvalue += response.vatvalue;
                        }
                        if(getnetprice > 0){
                            if ( !$('#width').val() || !$('#drope').val()) {
                               jQuery('.price_container').hide();
                            }else{
                                jQuery('.price_container').show();
                            }
                           // jQuery('.price_container').show();
                            //Show price
                            var getvat = (getnetprice / 100) * response.vaterate;
                            var priceval = getnetprice+getvat;
                            var excl_vat = true;
                            var _priceval = priceval;
                            if(excl_vat){
                                _priceval = getnetprice;
                            }
                            var showprice = _priceval.toFixed(2);
                            
                            //jQuery('.showprice').text(showprice);
                            jQuery('.showprice').text(showprice+' (Excl. of VAT)');
                            jQuery('#single_product_price').val(_priceval);
                            jQuery('#single_product_netprice').val(getnetprice);
                            jQuery('#single_product_itemcost').val(getitemcost);
                            jQuery('#single_product_grossprice').val(priceval);
                            if(getvat > 0){
                                vatvalue += getvat;
                            }
                        }
                        jQuery('#single_product_vatvalue').val(vatvalue);
    
                    }else{
                        jQuery('.price_container').hide();
                        jQuery('#errmsg_width').html(response.widthmessage);
                        jQuery('#errmsg_drop').html(response.dropmessage);
                    }
                }
            });
            
        }, 2000);
    }
    
    function checkinfinity(Result){
        if(Result == NaN || Result == Infinity)
        {
            Result = 0;
        }
        return Result;
    }
    
    function groupByKey(array, key) {
       return array
         .reduce((hash, obj) => {
           if(obj[key] === undefined) return hash; 
           return Object.assign(hash, { [obj[key]]:( hash[obj[key]] || [] ).concat(obj)})
         }, {})
    }
    
    function isKeyExists(obj,key){
        return key in obj;
    }
    
    function getprice(){
        jQuery('.loading-spin').css('display','block');
        jQuery('.errormsg').html('');
    
        var returnfalsevalue = '';
        jQuery('.mandatoryvalidate').each(function(i){
            var parameterName = jQuery(this).attr('parameterName');
            var getparameterid = jQuery(this).attr('getparameterid');
            if(this.value == ''){
                returnfalsevalue = 1;
                jQuery('#errormsg_'+getparameterid).html('The field is required.');
            }
        });
    
        jQuery('input', '.mandatory_validate').each(function() {
            var name = jQuery(this).attr('radiobutton');
            var parameterName = jQuery(this).val();
            var getparameterid = jQuery(this).attr('getparameterid');
            var getparameterlistid = jQuery(this).attr('getparameterlistid');
            if (jQuery('[name="' + name + '"]:checked').length < 1) {
                returnfalsevalue = 1;
                if(getparameterlistid == 16 || getparameterlistid == 21){
                    jQuery('#errormsg_fabricitemmandatory').html('The field is required.');
                }else{
                    jQuery('#errormsg_'+getparameterid).html('The field is required.');
                }
            }
        });
        
        var single_product_netprice = jQuery('#single_product_netprice').val();
    
        if(returnfalsevalue == 1 || single_product_netprice < 0){
            jQuery('.loading-spin').css('display','none');
            jQuery('.errormsg').each(function() {
                var get_html = jQuery(this).html();
                if(get_html != ''){
                    jQuery('html, body').animate({
                        scrollTop: jQuery(this).offset().top -100
                    }, 150);
                    return false;
                }
            });
        }else{
            jQuery('.loading-spin').css('display','none');
            //jQuery('.curtain-loder').css('display','flex');
           // jQuery('.curtain-whole-loader').css('display','flex');
            jQuery('#imagepath').val('');
            setTimeout(function(){
                var node = document.getElementById('curtainspreview');
                // get the div that will contain the canvas
                var canvas = document.createElement('canvas');
                canvas.width = node.scrollWidth;
                canvas.height = node.scrollHeight;
                
                domtoimage.toJpeg(node).then(function (pngDataUrl) {
                    var img = new Image();
                    img.onload = function () {
                        var context = canvas.getContext('2d');
                        context.drawImage(img, 0, 0);
                    };
                    img.src = pngDataUrl;
                    jQuery('#imagepath').val(pngDataUrl);
                    jQuery('#blindmatrix-js-add-cart').trigger('click');
                });
            }, 500);
        }
    }
    
    function formulavariablereplace(formula,forobj){
        for(var i=0;i<5;i++){
            jQuery.each(forobj, function( index, value ) {
                //if(formula.includes(index)){
                if(new RegExp("\\b" + index + "\\b").test(formula)){
                    formula = formula.replaceAll(index, "(" + value + ")");
                }
            });
        }
        
        return formula;
    }
    
    function formulacharacterappend(formula){
        var formulachar ='formulajs.';
        jQuery.each(formulafunctionlist, function( index, value ) {
             //if(formula.includes(value)){
             if(new RegExp("\\b" + value + "\\b").test(formula)){    
                 var regex_search = new RegExp("\\b"+value+"\\b","g");
                formula = formula.replaceAll(regex_search, formulachar+value);
             }
        });
        return formula;
    }
    
    function stringevil(fn) {
      return new Function('return ' + fn)();
    }
    
    function checkValueinarray(value,arr){
        var status = 0;
        for(var i=0; i<arr.length; i++){
            var name = arr[i];
            name = name.toUpperCase();
            if(name == value){
                status = 1;
                break;
            }
        }
        
        return status;
    }
    
    function replacespecialcharacter(formula_parametername){
        var parmvar= formula_parametername.replace(/[^a-zA-Z]0-9/g,'');
        parmvar= parmvar.replace(/\s+/g, "").replace(/\s*[!%$@|&-+\/*\])}[{(]\s*/g, '');
        parmvar= parmvar.replace(/[\/]+/g, "");
        
        return parmvar;
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
    jQuery(document).on("keyup",'#serach_input_color' ,function(e) {
          var input = document.getElementById("serach_input_color");
          var filter = input.value.toLowerCase();
        
          var nodes = document.getElementsByClassName('filtered');
          
          if(nodes.length == 0){
             var nodes = document.getElementsByClassName('enableSearch');
          }
          var countnode = jQuery('.enableSearch.filtered').length;
        
        if (countnode == 0){
             var countnode = jQuery('.enableSearch').length;
          };
         var total_nodes = 0;
          for (i = 0; i < nodes.length; i++) {
        
              var main = nodes[i].getAttribute('data-text').toLowerCase();
            if (main.includes(filter)) {
              nodes[i].style.display = "inline-block";
              total_nodes++;
            } else {
                jQuery(".no_products_div").hide();
                nodes[i].style.display = "none";
            }
          }
          if(total_nodes == 0){
            jQuery(".no_products_div").show();
          }else{
              jQuery(".no_products_div").hide();
          }
    });
    jQuery('.input-text.qty.text').on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
           event.preventDefault();
           return false;
        }
    });
    
    jQuery('.blindmatrix-select2').change(function(){	
        var $this = jQuery(this),
            $curtain_event = $this.find(':selected').data('jsevent');
        if('setposition' == $curtain_event){
            setposition($this.find(':selected'));
        }else if('borderposition' == $curtain_event){
            borderposition($this.find(':selected'));
        }else if('borderratio' == $curtain_event){
            borderratio($this.find(':selected')); 
        }else if('getComponentSubList' == $curtain_event){
            $pid = $this.find(':selected').data('parameterid');
            dropdownparameter($this.find(':selected'),$pid); 
            getComponentSubList($this.find(':selected'),$pid); 
        }else if('changebordercolor' == $curtain_event){
            changebordercolor($this.find(':selected'));
        }else if('changeheadertype' == $curtain_event){
            changeheadertype($this.find(':selected'));
        }else if('getsubcurtainliningnew' == $curtain_event){
            $lid = $this.find(':selected').data('price-id');
            $pid = $this.find(':selected').data('para-id');
            getsubcurtainliningnew($lid,$pid,$this.find(':selected'));
        }else if('getsubcurtainliningnewtwo' == $curtain_event){
            $lid = $this.find(':selected').data('price-id');
            $pid = $this.find(':selected').data('para-id');
            getsubcurtainliningnewtwo($lid,$pid,$this.find(':selected'));
        }else if('showorderdetails' == $curtain_event){
        showorderdetails();
        }
        showorderdetails();
    });	
    
    $('.single_select').on('select2:unselect', function (e) {
        var dat = e.params.data;
        var id = dat.element.dataset.parameterid ;
        if(id !=""){
        jQuery('.dropdownparameter-'+id).removeClass(' selected');
        jQuery('.componentsub_'+id).remove();
        }
        
    });
    $('.multi_select').on('select2:unselect', function (e) {
        var data = e.params.data;
        var id = data.element.attributes.get_parameter_value.nodeValue ;
        if(id !=""){
        jQuery('.dropdownparameter-'+id).removeClass(' selected');
        jQuery('.componentsub_'+id).remove();
        }
        
    });
    
    jQuery(document).ready(function () {
        
        jQuery(".blindmatrix-select2").each(function(){
            var $this = jQuery(this),
            $curtain_event = $this.find(':selected').data('jsevent');
             if('changeheadertype' == $curtain_event){
                 changeheadertype($this.find(':selected'));
             }
        });
        
        jQuery(".blindmatrix-select2").each(function(){
                var attr = jQuery(this).attr('multiple');
                if (typeof attr !== 'undefined' && attr !== false) {
                   var $eventSelect = jQuery(this);
                    $eventSelect.select2({
                        templateResult: formatState,
                    });
                    $eventSelect.on('select2:opening select2:closing', function( event ) {
                        var $searchfield = jQuery(this).parent().find('.select2-search__field');
                        $searchfield.prop('disabled', true);
                    });
                
                    
                }else{
                    var $eventSelect = jQuery(this);
                    $eventSelect.select2({
                        templateResult: formatState,
                        minimumResultsForSearch: -1
                    });	
                }
            });
    
    function formatState (opt) {
        
        if (!opt.id) {
            return opt.text;
        } 
    
        var optimage = jQuery(opt.element).attr('data-img'); 
                
        if(!optimage || optimage == " " ){
            return opt.text;
        } else {                    
            var $opt = jQuery(
                '<span class="select2_option"><img  src="' + optimage + '" width="50px" style="border-radius:10px;" /> ' + opt.text + '</span>'
            );
            return $opt;
        }
    };
    
    $("label.tab-label").click(function(){
      
        if($(this).parents(".tab").hasClass('edit')){
                        jQuery(this).parents(".tab").find('.tab-content').slideToggle('fast', function() {
                            if ($(this).is(':visible'))
                                $(this).css('display','block');
                        });
                      $(this).parents(".tab").removeClass("edit");
                    } else {
                        jQuery('.tab-content').each(function(i, obj) {
                            $(this).slideUp("fast");
                        });
                        jQuery(".tab.edit").each(function() {
                            $(this).removeClass('edit');
                        });
                        jQuery(this).parents(".tab").find('.tab-content').slideToggle('slow', function() {
                            if ($(this).is(':visible'))
                            $(this).css('display','block');
                        });
                       $(this).parents(".tab").addClass("edit");
                           
                    }
    });
    
    });
    
    </script>
    
    <style>
    
    #cover-spin {
        position:absolute;
        width:100%;
        left:0;right:0;top:0;bottom:0;
        background-color: rgba(255,255,255,0.7);
        z-index:9999;
        display:none;
    }
    @-webkit-keyframes spin {
        from {-webkit-transform:rotate(0deg);}
        to {-webkit-transform:rotate(360deg);}
    }
    
    @keyframes spin {
        from {transform:rotate(0deg);}
        to {transform:rotate(360deg);}
    }
    
    #cover-spin::after {
        content:'';
        display:block;
        position:absolute;
        left:48%;top:40%;
        width:40px;height:40px;
        border-style:solid;
        border-color:black;
        border-top-color:transparent;
        border-width: 4px;
        border-radius:50%;
        -webkit-animation: spin .8s linear infinite;
        animation: spin .8s linear infinite;
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
        border-color:black;
        border-top-color:transparent;
        border-width: 4px;
        border-radius:50%;
        -webkit-animation: spin .8s linear infinite;
        animation: spin .8s linear infinite;
    }
    .wpcf7-list-item [type=radio] {
        display: inline-block;
    }
    .configurator [type=checkbox] {
        display: none;
    }
    .cuspricevalue {
        border: 1px solid #ccc;
        border-top: 4px solid #e83b68;
    }
    /*19.06.23*/
    label.tab-label span {
        width: 70%;
    }
    .edit h3.config-heading.edit {
        visibility: hidden;
    }
    h3.config-heading.edit {
        display: inline-block;
        width: 16%;
        font-size: 14px;
        text-align: right;
        cursor: pointer;
        margin: unset;
    }
    .option-grid.header-type.configurator-fabric-grid.showorderdetails.mandatory_validate {
        grid-template-columns: unset !important;
    }
    span.select2.select2-container.select2-container--default {
        width: 100% !important;
    }
    
    body .configurator-option-content {
        padding-bottom: 0px;
    }
    body .configurator-fabric-grid {
        padding: 10px 10px;
    }
    .curtain.cuspricevalue {
        border-top: unset;
        border: unset;
    }
    .product-info {
        padding-top: unset;
    }
    .configurator-option-heading .title {
        margin-bottom: 0px;
    }
    .configurator-option {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .configurator-option-heading {
        width: 30%;
        padding: unset;
    }
    h4.title span {
        font-size: .9em;
    }
    span.config-count {
        margin-right: 10px;
    }
    .accordion-title{
        border-top:unset;
    }
    .curtain_option_ui {
        border: 1px solid #d4d3d3;
        background: #f7f6f6;
        border-radius: 10px;
    }
    .configurator-options {
        margin: unset;
    }
    .configurator-option .configurator-fabric-grid {
        margin-top: unset;
    }
    .configurator.curtain .accordion-inner{
        z-index:unset;
    }
    .configurator-option-content input {
        border-radius: 10px;
    }
    select#widthfraction, select#dropfraction {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }
    span.select2-selection {
        border-radius: 10px !important;
    }
    .select2-container--open span {
        border-radius: unset!important;
    }
    .configurator-main-fabric {
        background-size: 40%;
    }
    .configurator-border-fabric.top {
        background-size: 20%;
    }
    .curtain .configurator-preview.visible .configurator_preview_image {
        box-shadow: 1px 0 5px #ccc;
        padding: 15px 10px 30px;
        border-radius: 10px;
    }
    body #configurator-root .single_add_to_cart_button.curtains {
        background-color: #002746;
    }
    .curtain input#width, .curtain input#drope {
        margin: 0px 0px 1em;
    }
    .tab-content .configurator-option:first-child{
        margin-top: 0.5em;
    }
    .tab-content .configurator-option:last-child {
        margin-bottom: 1em;
     }
    @media(max-width:767px){
        .configurator-preview.visible {
            margin: unset;
        }
        .configurator-preview.visible {
            width: 100%;
        }
        .configurator-controls.product-info {
            width: 100%;
            padding: unset !important;
            margin-top: 20px;
        }
        .option-grid{
            padding-left:unset;
        }
        span.select2_option {
            font-size: .8em;
        }
    
    }
    @media(min-width:768px) and (max-width:1024px){
        .configurator-option-content{
            padding:unset;
        }
    .configurator.curtain.bordered.cuspricevalue {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }
    .configurator-preview.visible {
        width: 48%;
    }
    .configurator-controls.product-info {
        width: 50%;
        padding: unset !important;
    }
    .configurator-option-content .cpt-container {
        width: 100% !important;
    }
    .option-grid.configurator-fabric-grid.showorderdetails {
        margin-top: unset;
    }
    }
    
    /* Accordion styles */
    
    .tabs {
      border-radius: 10px 10px 0px 0px ;
      overflow: hidden;
    }
    .tab {
      width: 100%;
      color: white;
      overflow: hidden;
    }
    .tab-label {
      display: flex;
      justify-content: space-between;
      padding: 0.5rem 1.3rem;
      background: #e8e8e8;
      font-weight: bold;
      cursor: pointer;
      color: #222;
      font-size: 1.0rem;
    }
    .tab-content {
      padding: 0 1em;
      color: #2c3e50;
      border: 1px solid #ececec;
      background-color: #f7f6f6;
      display:none;
    }
    .edit .tab-label {
      background: #002746;
      color: #fff;
      align-items: center;
      position: relative;
    }
    .edit .tab-content {
      display:block;
    }
    .edit .tab-content .configurator-option {
        padding: 0.3rem 0.5rem;
    }
    .tabs label.tab-label {
        width: 100%;
        display: flex;
        margin:0px;
        border-bottom: 1px solid #d2d2d2;
    }
    .tab.edit label.tab-label:before {
        opacity: 1;
        border-top-color: #002746;
    }
    .accordion-title {
         border-top: unset;
    }
    label.tab-label:before {
        content: "";
        position: absolute;
        top: 100%;
        left: 16px;
        height: 0px;
        width: 0px;
        border: 14px solid transparent;
        border-top-color: #002746;
        -webkit-transition: border .15s;
        -o-transition: border .15s;
        transition: border .15s;
        opacity: 0;
    }
    /*10.7.23*/
    .configurator-fabric-swatch {
        border-radius: 50%;
        overflow: hidden !important;
        width: 70%;
    }
    label.configurator-fabric-item.mainfabric {
        padding: 10px 5px;
        display: flex !important;
        flex-direction: column;
        align-items: center;
        box-shadow: 2px 2px 0 rgb(46 53 71 / 20%);
        transition: border-color .2s cubic-bezier(.26,.01,.73,.99);
        will-change: border-color;
        background-color: #fff;
        border-radius: 5%;
        border: 2px solid transparent;
    }
    
    .configurator-fabric-image {
        width: 100%;
    }
    .configurator-fabric-grid {
        max-height: 320px !important;
    }
    
    .configurator-option.main-fabric .configurator-fabric-grid.showorderdetails{
        justify-content: center;
    }
    label.configurator-fabric-item.option-item.bordercolor {
        display: flex !important;
        flex-direction: column;
        align-items: center;
        box-shadow: 2px 2px 0 rgb(46 53 71 / 20%);
        transition: border-color .2s cubic-bezier(.26,.01,.73,.99);
        will-change: border-color;
        background-color: #fff;
        border-radius: 5%;
        border: 2px solid transparent;
    }
    .option-item-image {
        border-radius: 50%;
        overflow: hidden !important;
        width: 70%;
        flex:unset;
    }
    @media(max-width:767px){
        .configurator-fabric-grid.showorderdetails {
            grid-template-columns: repeat(2,1fr)!important;
            grid-row-gap: 0px!important;
        }
        h4.title span {
        font-size: .8em !important;
        }
        .configurator.curtain.bordered.cuspricevalue {
        display: flex;
        flex-wrap: wrap;
        flex-direction: column-reverse;
        }
    }
    </style>
<?php }else{
	echo('Enable curtains in the settings to view the curtain products.');
} ?>