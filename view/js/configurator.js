const st = {};

st.flap = document.querySelector('#flap');
st.toggle = document.querySelector('.toggle_slats');

st.choice1 = document.querySelector('#choice1');
st.choice2 = document.querySelector('#choice2');

st.flap.addEventListener('transitionend', () => {

    if (st.choice1.checked) {
        st.toggle.style.transform = 'rotateY(-15deg)';
        setTimeout(() => st.toggle.style.transform = '', 400);
    } else {
        st.toggle.style.transform = 'rotateY(15deg)';
        setTimeout(() => st.toggle.style.transform = '', 400);
    }

})

st.clickHandler = (ee) => {
    //console.log(ee.target.attributes[0].textContent);
    if (ee.target.tagName === 'LABEL' && ee.target.attributes[0].textContent === 'slatslabel') {
        setTimeout(() => {
            st.flap.children[0].textContent = ee.target.textContent;
        }, 250);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    st.flap.children[0].textContent = st.choice2.nextElementSibling.textContent;
});

document.addEventListener('click', (ee) => st.clickHandler(ee));

var loadingafter;

if( jQuery(".SlatWidth").length != 0 ){
	var slastsize = jQuery(".SlatWidth").val();
}else{
	var slastsize = 0;
}
if( jQuery(".midrails").length != 0 ){
	var midrails = jQuery(".midrails").val();
}else{
	var midrails = 0;
}
if( jQuery(".NumberOfPanels").length != 0 ){
	var NumberOfPanels = jQuery(".NumberOfPanels").val();
}else{
	var NumberOfPanels = 0;
}

 var select_color_image = jQuery('#select_color_image').val();
 if (select_color_image !== undefined){
	 //console.log('ckcks');
	update_Panel(NumberOfPanels);
	midpane(midrails,slastsize);
	resizeimagepreview();
 }

var shuttertype = jQuery('#set_shuttertype').val();
var n13 = shuttertype.toLowerCase().indexOf("full solid");
if(n13 > -1){
    jQuery(".tiltrod").val('hidden');
    tiltrod('hidden');
}

jQuery(window).resize(function () {
 var select_color_image = jQuery('#select_color_image').val();
 if (select_color_image !== undefined){
    resizeimagepreview();
}
});

jQuery('body').click(function(e) {
    var get_sel_id = e.target.id;
    var getselid = get_sel_id.toLowerCase().indexOf("radio_");
    if (getselid > -1 || jQuery(e.target).parents(".no_of_panels_elements").length) {
        configuratorpreview();
    }
});

switch (document.readyState) {
  case "loading":
    // The document is still loading.
    loadingafter = false;
  case "interactive":
    // The document has finished loading. We can now access the DOM elements.
    loadingafter = false;
  case "complete":
    configuratorpreview();
    loadingafter = false;
}


function configuratorpreview(){
    var select_color_image = jQuery('#select_color_image').val();
    
    if(isValidImageURL(select_color_image) === false){
        jQuery('.configurator-preview').hide();
    }else{
        jQuery('.configurator-preview').show();
        resizeimagepreview();
		
    }
    if(jQuery.tierontier == 1){
        jQuery('.configurator-preview').hide();
    }
}

function isValidImageURL(str){
    if ( typeof str !== 'string' ) return false;
    return !!str.match(/\w+\.(jpg|jpeg|gif|webp|png|tiff|bmp)$/gi);
}

function resizeimagepreview() {
    
    var panelscontainer_width = jQuery('.panel').width();
    jQuery('.panels').css('width',panelscontainer_width);
    
    jQuery('.slats-pushrod').each(function()
    {
        var innerHeight = jQuery(this).innerHeight();
        jQuery(this).find('.pushrod').height(innerHeight);
        jQuery(this).find('.pushrod-offset').height(innerHeight);
    })
    
    var select_color = jQuery('#select_color').val();
    var select_color_image = jQuery('#select_color_image').val();
    
    change_color(select_color,select_color_image);
    
    var t = jQuery(".js-shutters-configurator"),
            a = t.find(".panels"),
            o = 0,
            n = "",
            i = "";
    
    jQuery(".panels").removeAttr("style"), t.find(".panels + .panels").length;
    var o = g(t.find(".configurator-preview")),
        n = 0.5 * window.innerHeight,
        i = a.outerWidth(),
        s = 0;
    
    t.find(".scalingWrapper").each(function () {
        s += jQuery('#shutterspreview').outerHeight();
        
    });
    var r,
        l = s - n,
        d = i - o;
		 var width_dec = (window.innerWidth > 0) ? window.innerWidth : screen.width;
	if(width_dec < 550){
		l > d && s > n ? (r = n / s) : l <= d && i > o && (r = o / i),
	    t.find(".panels-container").css({ "-webkit-transform": "scale(" + r + ")", "-moz-transform": "scale(" + r + ")", "-ms-transform": "scale(" + r + ")", "-o-transform": "scale(" + r + ")", transform: "scale(" + r + ")" });
	}else{
		l > d && s > n ? (r = n / i) : l <= d && i > o && (r = o / i),
		r = r+0.010000;
	    t.find(".panels-container").css({ "-webkit-transform": "scale(" + r + ")", "-moz-transform": "scale(" + r + ")", "-ms-transform": "scale(" + r + ")", "-o-transform": "scale(" + r + ")", transform: "scale(" + r + ")" });
	}

    var c = s * (r || 1),
        p = i * (r || 1);
    (c = Math.ceil(c)), t.find(".preview").css("height", c + "px");
	
		var u = g(t.find(".configurator-preview:visible").last()),
        f = Math.ceil((u - p) / 2);
		(f = f > 0 ? f : 0), t.find(".panels").css("marginLeft", f + "px");
	
}

function g(e) {
    return jQuery(".configurator-preview").width() - parseInt(jQuery(".configurator-preview").css("padding-left")) - parseInt(jQuery(".configurator-preview").css("padding-right"));
}

function midpane(midrail,slastsize){
    var html = '';
    var minheight = 20 + parseFloat(slastsize);
    
    var paneSection = 20;
    var shuttertype = jQuery('#set_shuttertype').val();
    var n1 = shuttertype.toLowerCase().indexOf("half");
    var n2 = shuttertype.toLowerCase().indexOf("tier");
    var n3 = shuttertype.toLowerCase().indexOf("full solid");
    if(n1 > -1 || n2 > -1){
        midrail = 0;
        paneSection = 20;
    }
    
    if(midrail > 0){

        if(midrail == 1){
            
            if(n3 > -1){
                midrail = Number(midrail)+Number(1);
                var midrail_height = Number(30)*Number(1);
                midrail_height = 480 - midrail_height;
                var minheight = (midrail_height/midrail);
                html +='<div class="paneSection raisedPanel" style="min-height: '+minheight+'px;"></div>';
                html += midRail_html();
                html +='<div class="paneSection raisedPanel" style="min-height: '+minheight+'px;"></div>';
            }else{
                slastsize = slastsize/2;
                html += panesectionli(10,minheight,slastsize);
                html += midRail_html();
                html += panesectionli(8,minheight,slastsize);
            }

        }else if(midrail == 2){
            if(n3 > -1){
                midrail = Number(midrail)+Number(1);
                var midrail_height = Number(30)*Number(2);
                midrail_height = 480 - midrail_height;
                var minheight = (midrail_height/midrail);
                html +='<div class="paneSection raisedPanel" style="min-height: '+minheight+'px;"></div>';
                html += midRail_html();
                html +='<div class="paneSection raisedPanel" style="min-height: '+minheight+'px;"></div>';
                html += midRail_html();
                html +='<div class="paneSection raisedPanel" style="min-height: '+minheight+'px;"></div>';
            }else{
                slastsize = slastsize/3;
                html += panesectionli(5,minheight,slastsize);
                html += midRail_html();
                html += panesectionli(5,minheight,'');
                html += midRail_html();
                html += panesectionli(5,minheight,slastsize);
            }
        }else if(midrail == 3){
            if(n3 > -1){
                midrail = Number(midrail)+Number(1);
                var midrail_height = Number(30)*Number(3);
                midrail_height = 480 - midrail_height;
                var minheight = (midrail_height/midrail);
                html +='<div class="paneSection raisedPanel" style="min-height: '+minheight+'px;"></div>';
                html += midRail_html();
                html +='<div class="paneSection raisedPanel" style="min-height: '+minheight+'px;"></div>';
                html += midRail_html();
                html +='<div class="paneSection raisedPanel" style="min-height: '+minheight+'px;"></div>';
                html += midRail_html();
                html +='<div class="paneSection raisedPanel" style="min-height: '+minheight+'px;"></div>';
            }else{
                slastsize = slastsize/4;
        
                html += panesectionli(5,minheight,slastsize);
                html += midRail_html();
                html += panesectionli(3,minheight,slastsize);
                html += midRail_html();
                html += panesectionli(3,minheight,'');
                html += midRail_html();
                html += panesectionli(5,minheight,slastsize);
            }
        }else if(midrail == 4){
            if(n3 > -1){
                midrail = Number(midrail)+Number(1);
                var midrail_height = Number(30)*Number(4);
                midrail_height = 480 - midrail_height;
                var minheight = (midrail_height/midrail);
                html +='<div class="paneSection raisedPanel" style="min-height: '+minheight+'px;"></div>';
                html += midRail_html();
                html +='<div class="paneSection raisedPanel" style="min-height: '+minheight+'px;"></div>';
                html += midRail_html();
                html +='<div class="paneSection raisedPanel" style="min-height: '+minheight+'px;"></div>';
                html += midRail_html();
                html +='<div class="paneSection raisedPanel" style="min-height: '+minheight+'px;"></div>';
                html += midRail_html();
                html +='<div class="paneSection raisedPanel" style="min-height: '+minheight+'px;"></div>';
            }else{
                slastsize = slastsize/5;
                
                html += panesectionli(4,minheight,slastsize);
                html += midRail_html();
                html += panesectionli(3,minheight,slastsize);
                html += midRail_html();
                html += panesectionli(3,minheight,slastsize);
                html += midRail_html();
                html += panesectionli(3,minheight,slastsize);
                html += midRail_html();
                html += panesectionli(4,minheight,slastsize);
            }
        }
        jQuery('.midpane-fill').html('');
        jQuery('.midpane-fill').html(html);
    }else{
        
        if(n2 > -1){
            tierontier();
        }else if(n3 > -1){
            html +='<div class="paneSection raisedPanel" style="min-height: 480px;"></div>';
            jQuery('.midpane-fill').html('');
            jQuery('.midpane-fill').html(html);
        }else{
            html += panesectionli(paneSection,minheight,slastsize);

            jQuery('.midpane-fill').html('');
            jQuery('.midpane-fill').html(html);
            
            if(n1 > -1){
                html = '<div class="midRail">';
                html += '<span class="rail-bg" style="min-height: 30px; height: 30px;"></span>';
                html += '<span class="mouseHole-bottom"></span>';
                html += '</div>';
                html += '<div class="paneSection raisedPanel" style="min-height: 100.2px;"></div>';
                jQuery('.slats-pushrod').after(html);
            }
        }
    }
	

}

function odd(n) {if (n%2 == 0) {return true} else {return false;}}

function tierontier(){
    var html = '';
    var NumberOfPanels = jQuery('.NumberOfPanels').val();
    var slastsize = jQuery(".SlatWidth").val();
    if (slastsize == undefined){
        slastsize=0;
    }
    var minheight = 20 + parseFloat(slastsize);
    
    if(odd(NumberOfPanels) === true){
        slastsize = slastsize/(NumberOfPanels-1);
        
        var div_noofpanel = '';
        var panel_minwidth = '330'+'px';
        if(NumberOfPanels == 2){
            div_noofpanel = (NumberOfPanels/NumberOfPanels);
        }else{
            div_noofpanel = (NumberOfPanels/2);
            var panel_minwidth = (330/div_noofpanel)+'px';
        }
        
        var inside_html='';
        for(i=0;i<div_noofpanel;i++){
        inside_html +='<div class="panel" style="min-width:'+panel_minwidth+'">';
        inside_html +='<div class="midpane">';
        inside_html +='<div class="topRail">';
        inside_html +='<span class="rail-bg" style="min-height: 30px; height: 30px;"></span>';
        inside_html +='<span class="mouseHole-top"></span>';
        inside_html +='</div>';
        
        inside_html +='<div class="midpane-fill">';
        inside_html += panesectionli(18,minheight,slastsize);
        inside_html +='</div>';
        
        inside_html +='<div class="bottomRail">';
        inside_html +='<span class="rail-bg" style="min-height: 30px; height: 30px;"></span>';
        inside_html +='<span class="mouseHole-bottom" style="display:unset;"></span>';
        inside_html +='</div>';
        inside_html +='</div>';
        inside_html +='</div>';
        }
        html +='<div class="panels">';
        html +=inside_html;
        html +='</div>';
        
        var inside_html='';
        for(i=0;i<div_noofpanel;i++){
        inside_html +='<div class="panel" style="min-width:'+panel_minwidth+'">';
        inside_html +='<div class="midpane">';
        inside_html +='<div class="topRail">';
        inside_html +='<span class="rail-bg" style="min-height: 30px; height: 30px;"></span>';
        inside_html +='<span class="mouseHole-top"></span>';
        inside_html +='</div>';
        
        inside_html +='<div class="midpane-fill">';
        inside_html += panesectionli(6,minheight,slastsize);
        inside_html +='</div>';
        
        inside_html +='<div class="bottomRail">';
        inside_html +='<span class="rail-bg" style="min-height: 30px; height: 30px;"></span>';
        inside_html +='<span class="mouseHole-bottom" style="display:unset;"></span>';
        inside_html +='</div>';
        inside_html +='</div>';
        inside_html +='</div>';
        }
        html +='<div class="panels">';
        html +=inside_html;
        html +='</div>';

        jQuery('.panels-container').html('');
        jQuery('.panels-container').html(html);
        
        if(NumberOfPanels >2){
            jQuery('.panel').removeClass('hingeLeft panel--hinge-left');
        }else{
            jQuery('.panel').addClass('hingeLeft panel--hinge-left');
        }
        
        jQuery('.panels+.panels').css('clear','both');
        jQuery('.panels').css('float','left');
    jQuery.tierontier='';
    }else{
        console.log('Something went wrong. Please refresh the page and try again.');
        jQuery.tierontier=1;
    }
}

function midRail_html(){
    html = '<div class="midRail">';
    html += '<span class="rail-bg" style="min-height: 30px; height: 30px;"></span>';
    html += '<span class="mouseHole-bottom"></span>';
    html += '<span class="mouseHole-top"></span>';
    html += '</div>';
    
    return html;
}

function panesectionli(count,minheight,slastsize){
    var html='';
    var li_list_count = count-slastsize;
    
    html +='<div class="slats-pushrod">';
    html +='<ul class="paneSection slats">';
    for(var i = 0; i < li_list_count; i++){
        var zindex = 99 - i;
        html +='<li><span style="min-height: '+minheight+'px; padding: 0px; margin: 0px;"></span></li>';
    }
    html +='</ul>';
    html +='<div class="pushrod"></div>';
    html +='</div>';
            
    return html;
}

function midRail(thisval){
    var midrail = jQuery(thisval).attr('data-value');

    jQuery('.midrails').val(midrail);
    var slastsize = jQuery(".SlatWidth").val();
    midpane(midrail,slastsize);
    
    var PushRod = jQuery(".tiltrod").val();
    tiltrod(PushRod);
    resizeimagepreview();
}

function slats(slat){
    jQuery('.midpane-fill').removeClass('slats-close');
    if(slat == 'close'){
        jQuery('.midpane-fill').addClass('slats-close');
		jQuery('.slats-close .slats li:first span').css('box-shadow', 'rgb(5 5 5 / 15%) 0.1em 0.1em 0.1em inset, rgb(5 5 5 / 15%) 0px 8px 10px inset');
    }
}

function update_Panel(panel){
    
    var shuttertype = jQuery('#set_shuttertype').val();
    var n = shuttertype.toLowerCase().indexOf("tier");
    if(n > -1){
        tierontier();	
		resizeimagepreview();
    }else{
    
        jQuery('.panel').each(function(i){
            if(i > 0){
                var classes = jQuery(this).attr('class');
                jQuery(this).remove();
            }
        });
        
        if(panel >1){
            jQuery('.panel').removeClass('hingeLeft panel--hinge-left');
        }else{
            jQuery('.panel').addClass('hingeLeft panel--hinge-left');
        }
        
        var panel_html ='';
        panel_html += jQuery('.panels').html();
    
        var panels_width = 330;
        for(var i = 1; i < panel; i++){
            jQuery('.panels').append(panel_html);
        }
        if(panel > 1){
            panels_width = (panels_width/panel);
            jQuery('.panel').css('min-width',panels_width);
        }else{
            jQuery('.panel').css('min-width',panels_width);
        }
        resizeimagepreview();
    }
}

function updatePanel(thisval){
    
    var panel = jQuery(thisval).attr('data-value');
    jQuery('.NumberOfPanels').val(panel);
    update_Panel(panel);
}

function slatsize(thisval){
    var slastsize = jQuery(thisval).attr('data-value');
    jQuery('.js-slatSize').removeClass('is-selected');
    jQuery(thisval).addClass('is-selected');

    jQuery('.SlatWidth').val(slastsize);
    var midrails = jQuery('.midrails').val();
    midpane(midrails,slastsize);
    
    var PushRod = jQuery(".tiltrod").val();
    tiltrod(PushRod);
    resizeimagepreview();
}

function tiltrod(getvalue){
    
    var shuttertype = jQuery('#set_shuttertype').val();
    var n3 = shuttertype.toLowerCase().indexOf("full solid");
    if(n3 > -1){
        getvalue = 'hidden';
    }
    
    jQuery('.tiltrod').val(getvalue);
    jQuery('.pushrod').show();
    jQuery('.pushrod-offset').show();
    jQuery('.mouseHole-top').show();
    jQuery('.mouseHole-top-offset').show();
    jQuery('.mouseHole-bottom').show(); 
    if(getvalue == 'central'){
        jQuery('.slats-pushrod div').removeClass('pushrod-offset');
        jQuery('.topRail span:nth-child(2)').removeClass('mouseHole-top-offset');
        jQuery('.midRail span:nth-child(3)').removeClass('mouseHole-top-offset');
        jQuery('.slats-pushrod div').addClass('pushrod');
        jQuery('.topRail span:nth-child(2)').addClass('mouseHole-top');
        jQuery('.midRail span:nth-child(3)').addClass('mouseHole-top');
    }else if(getvalue == 'offset'){
        jQuery('.slats-pushrod div').removeClass('pushrod');
        jQuery('.topRail span:nth-child(2)').removeClass('mouseHole-top');
        jQuery('.midRail span:nth-child(3)').removeClass('mouseHole-top');
        jQuery('.slats-pushrod div').addClass('pushrod-offset');
        jQuery('.topRail span:nth-child(2)').addClass('mouseHole-top-offset');
        jQuery('.midRail span:nth-child(3)').addClass('mouseHole-top-offset');
    }else if(getvalue == 'hidden'){
        jQuery('.mouseHole-top').hide();
        jQuery('.mouseHole-top-offset').hide();
        if ( jQuery('.slats-pushrod div').hasClass('pushrod') ){
            jQuery('.pushrod').hide();
        }
        if ( jQuery('.slats-pushrod div').hasClass('pushrod-offset') ){
            jQuery('.pushrod-offset').hide();
        }
        jQuery('.mouseHole-bottom').hide();
    }else{
        if ( jQuery('.topRail span:nth-child(2)').hasClass('mouseHole-top-offset') ){
            jQuery('.topRail span:nth-child(2)').removeClass('mouseHole-top-offset');
            jQuery('.topRail span:nth-child(2)').addClass('mouseHole-top');
        }
        if ( jQuery('.midRail span:nth-child(3)').hasClass('mouseHole-top-offset') ){
            jQuery('.midRail span:nth-child(3)').removeClass('mouseHole-top-offset');
            jQuery('.midRail span:nth-child(3)').addClass('mouseHole-top');
        }
        if ( jQuery('.slats-pushrod div').hasClass('pushrod') ){
            jQuery('.pushrod').hide();
        }
        if ( jQuery('.slats-pushrod div').hasClass('pushrod-offset') ){
            jQuery('.pushrod-offset').hide();
        }
    }
}

function pushrod(thisval){
    
    var getvalue = jQuery(thisval).attr('data-value');
    jQuery('.js-tiltrod').removeClass('is-selected');
    jQuery(thisval).addClass('is-selected');
    var get_value = getvalue.toLowerCase();
    tiltrod(get_value);
}

function change_color(color,imageurl){

    jQuery('#select_color').val(color);
    jQuery('#select_color_image').val(imageurl);

    jQuery('.panel').css('background-image', 'url('+imageurl+'),url('+imageurl+')');
    jQuery('.panel').css('background-position-x', 'right, left');
    jQuery('.panel').css('background-position-y', 'top, top');
    jQuery('.panel').css('background-repeat-x', 'no-repeat, no-repeat');
    jQuery('.panel').css('background-repeat-y', 'repeat, repeat');
    jQuery('.panel').css('background-size','16px');
    
    jQuery('.midRail').css('background', 'url('+imageurl+') no-repeat bottom left,url('+imageurl+') no-repeat bottom right');
    jQuery('.midRail .rail-bg').css('background', 'url('+imageurl+') no-repeat bottom center');
    jQuery('.pushrod, .pushrod-offset').css('background', 'url('+imageurl+') repeat-y');
    jQuery('.topRail').css('background', 'url('+imageurl+') no-repeat bottom left,url('+imageurl+') no-repeat bottom right');
    jQuery('.topRail .rail-bg').css('background', 'url('+imageurl+') no-repeat bottom center');
    jQuery('.slats li span').css('background', 'url('+imageurl+')');
    jQuery('.bottomRail').css('background', 'url('+imageurl+') no-repeat bottom left,url('+imageurl+') no-repeat bottom right');
    jQuery('.bottomRail .rail-bg').css('background', 'url('+imageurl+') no-repeat bottom center');

    jQuery('.topRail, .bottomRail, .midRail').css('border-width', '0px 1px');
    //jQuery('.topRail, .bottomRail, .midRail').css('border-style', 'solid');
    jQuery('.topRail, .bottomRail, .midRail').css('border-style', 'unset');
    jQuery('.topRail, .bottomRail, .midRail').css('border-color', '#03030380');
    
    jQuery('.panel').css('border-left-width', '0px');
    jQuery('.panel').css('border-right-width', '0px');
    
    //jQuery('.pushrod, .pushrod-offset').css('box-shadow', 'rgb(5 5 5 / 8%) 0.1em 0.1em 0.1em inset, rgb(0 0 0 / 8%) -1px -1px 1px inset');
	jQuery('.pushrod, .pushrod-offset').css({"box-shadow": "rgb(5 5 5 / 8%) 0.1em 0.1em 0.1em inset, rgb(0 0 0 / 8%) -1px -1px 1px inset"});
	//jQuery('.slats li span').css({"box-shadow": "rgb(5 5 5 / 15%) 0.1em 0.1em 0.1em inset, rgb(0 0 0 / 15%) -1px -1px 1px inset", "filter": "contrast(1.25)"});
	//jQuery('.slats li span').css('box-shadow', 'rgb(5 5 5 / 15%) 0.1em 0.1em 0.1em inset, rgb(0 0 0 / 15%) -1px -1px 1px inset');
	
	if(jQuery('.midpane-fill').hasClass( 'slats-close')){
		jQuery('.slats li span').css('box-shadow', 'rgb(5 5 5 / 15%) 0.1em 0.1em 0.1em inset, rgb(0 0 0 / 15%) 0px 8px 10px inset');
		jQuery('.slats li:first span').css('box-shadow', 'rgb(5 5 5 / 15%) 0.1em 0.1em 0.1em inset, rgb(5 5 5 / 15%) 0px 8px 10px  inset');
    }else{
		jQuery('.slats li span').css('box-shadow', 'rgb(5 5 5 / 15%) 0.1em 0.1em 0.1em inset, rgb(0 0 0 / 15%) 0px 8px 10px inset');
	}
    
    var shuttertype = jQuery('#set_shuttertype').val();
    var n1 = shuttertype.toLowerCase().indexOf("half");
    var n2 = shuttertype.toLowerCase().indexOf("full solid");
    if(n1 > -1 || n2 > -1){
        jQuery('.raisedPanel').css('background', 'url('+imageurl+') no-repeat top left');
        jQuery('.raisedPanel').css('background-size', '100% 100%');
        jQuery('.raisedPanel').css('filter', 'blur(5px)');
    }
    
    //if(loadingafter === true){
      //  var select_imgid = jQuery("#select_imgid").val();
      //  get_rgb(select_imgid);
    //}
}

function changecolor(thisval){
    var color = jQuery(thisval).attr('data-colorname');
    var imageurl = jQuery(thisval).attr('data-img');
    var select_imgid = jQuery(thisval).attr('data-id');
    jQuery("#select_imgid").val(select_imgid);
    
    jQuery('.js-config-color').removeClass('is-selected');
    jQuery(thisval).addClass('is-selected');
    change_color(color,imageurl);
}

function change_hinge_color(imageurl){
    
    jQuery('.select_hingecolor_image').val(imageurl);
    
    jQuery("#headstyle").html('');
    
    jQuery("#headstyle").html('<style>.shutters-configurator .configurator-preview .panel:last-child:after, .shutters-configurator .configurator-preview .panel:last-child:before, .shutters-configurator .configurator-preview .panel:first-child:after, .shutters-configurator .configurator-preview .panel:first-child:before{background:url('+imageurl+') no-repeat}</style>');
}

function changehingecolor(thisval){
    
    var imageurl = jQuery(thisval).attr('data-img');
    change_hinge_color(imageurl);
}

function get_rgb(id){
    getBase64Image(document.getElementById("imgid_"+id));
    setTimeout(function(){
        var r = document.getElementsByClassName('image_class');
        var rgb = [];
        for (p = 0 ; p < r.length ; p++ ){
           rgb.push(getAverageRGB(r[p]));
        }
        rgb.forEach((el,i) => {
           if(el.r != '0' && el.g != '0' && el.b != '0'){
           jQuery('.slats li span').css('background', 'none');
           jQuery('.slats li span').css('background-color', 'rgb('+el.r+','+el.g+','+el.b+')');
           }
           var r_c = el.r - 50;
           var g_c = el.g - 50;
           var b_c = el.b - 50;
           jQuery('.topRail, .bottomRail, .midRail, .panel').css('border-color', 'rgb('+r_c+','+g_c+','+b_c+')');
        })
    }, 300);
}

function getBase64Image(img) {
    var canvas = document.createElement("canvas");
    img.crossOrigin = "anonymous";
    canvas.width = img.width;
    canvas.height = img.height;
    setTimeout(function(){
        var ctx = canvas.getContext("2d");
        ctx.drawImage(img, 0, 0);
        var dataURL = canvas.toDataURL("image/png");
        jQuery('.image_class').attr('src', dataURL);
    }, 150);
}

function convert_canvas(id_div){

    var node = document.getElementById(id_div);
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
}

function getAverageRGB(imgEl) {
    
    var blockSize = 5, // only visit every 5 pixels
        defaultRGB = {r:0,g:0,b:0}, // for non-supporting envs
        canvas = document.createElement('canvas'),
        context = canvas.getContext && canvas.getContext('2d'),
        data, width, height,
        i = -4,
        length,
        rgb = {r:0,g:0,b:0},
        count = 0;
        
    if (!context) {
        return defaultRGB;
    }
    
    height = canvas.height = imgEl.naturalHeight || imgEl.offsetHeight || imgEl.height;
    width = canvas.width = imgEl.naturalWidth || imgEl.offsetWidth || imgEl.width;
    
    context.drawImage(imgEl, 0, 0);
    
    try {
        data = context.getImageData(0, 0, width, height);
    } catch(e) {
        /* security error, img on diff domain */alert('x');
        return defaultRGB;
    }
    
    length = data.data.length;
    
    while ( (i += blockSize * 4) < length ) {
        ++count;
        rgb.r += data.data[i];
        rgb.g += data.data[i+1];
        rgb.b += data.data[i+2];
    }
    
    // ~~ used to floor values
    rgb.r = ~~(rgb.r/count);
    rgb.g = ~~(rgb.g/count);
    rgb.b = ~~(rgb.b/count);
    
    return rgb;
    
}