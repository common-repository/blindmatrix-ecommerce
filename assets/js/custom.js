// Declaring required variables
var digits = "0123456789";
// non-digit characters which are allowed in phone numbers
var phoneNumberDelimiters = "()- ";
// characters which are allowed in international phone numbers
// (a leading + is OK)
var validWorldPhoneChars = phoneNumberDelimiters + "+";
// Minimum no of digits in an international phone no.
var minDigitsInIPhoneNumber = 10;

function checkInternationalPhone(strPhone){
	var bracket=3
	strPhone=trim(strPhone)
	if(strPhone.indexOf("+")>1) return false
	if(strPhone.indexOf("-")!=-1)bracket=bracket+1
	if(strPhone.indexOf("(")!=-1 && strPhone.indexOf("(")>bracket)return false
	var brchr=strPhone.indexOf("(")
	if(strPhone.indexOf("(")!=-1 && strPhone.charAt(brchr+2)!=")")return false
	if(strPhone.indexOf("(")==-1 && strPhone.indexOf(")")!=-1)return false
	s=stripCharsInBag(strPhone,validWorldPhoneChars);
	return (isInteger(s) && s.length >= minDigitsInIPhoneNumber);
}

function trim(s)
{   var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not a whitespace, append to returnString.
    for (i = 0; i < s.length; i++)
    {   
        // Check that current character isn't whitespace.
        var c = s.charAt(i);
        if (c != " ") returnString += c;
    }
    return returnString;
}

function stripCharsInBag(s, bag)
{   var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++)
    {   
        // Check that current character isn't whitespace.
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function isInteger(s)
{   var i;
    for (i = 0; i < s.length; i++)
    {   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function noSpace(e)
{
	var keynum;
	if(window.event) // IE
	{
		keynum = e.keyCode;
	}
	else if(e.which) // Netscape/Firefox/Opera
	{
		keynum = e.which;
	}
	if(keynum==32)
	return false;
}

jQuery(document).on('click','.bme-remove-sample-cart',function(event){
	var itemid = jQuery(this).data('key');
	jQuery.ajax(
	{
		url     : ajaxurl,
		data    : {mode:'removeitem',action:'removeitem',itemid:itemid},
		type    : "POST",
		dataType: 'JSON',
		success: function(response){
			if(response.success == 1){
				window.location.reload(); //Another possiblity
				history.go(0);
			}
		}
	});
	return false;
});

function sampleOrder(elem,productcode,producttypeid,fabricid,colorid,vendorid){
	console.log('dsadsadasd');
	jQuery(elem).addClass('loading');
	jQuery.ajax(
	{
		url     : ajaxurl,
		data    : {mode:'sampleOrderItem',action:'sampleOrderItem',productcode:productcode,producttypeid:producttypeid,fabricid:fabricid,colorid:colorid,vendorid:vendorid},
		type    : "POST",
		dataType: 'JSON',
		success: function(response){
			jQuery(elem).removeClass('loading');
			if(response.success == 1){
				console.log('success');
			    jQuery(elem).find("span").remove();
			    jQuery(elem).prepend('<i class="icon-checkmark"></i><span style="padding: 0px !important;margin:5px 0 !important;">Sample Added</span>');
			    jQuery('.free-sample-cart').attr('data-icon-label', response.samplecartcount);
			    //window.location.reload(); //Another possiblity
				//history.go(0);
				jQuery.confirm({
                    title: 'Success!',
                    columnClass: 'col-md-4 col-md-offset-4',
                    content: 'The sample product is successfully added to free sample cart',
                    type: 'blue',
                    typeAnimated: true,
                    boxWidth: '30%',
                    useBootstrap: false,
                    buttons: {
                        okay: function () {
                            //history.go(0);
                        }
                    }
                });
			}else{
				jQuery('#errormsg').html(response.success);
				jQuery( "#Lightbox_errormsg" ).trigger('click');
			}
		}
	});
}