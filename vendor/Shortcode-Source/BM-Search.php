<?php 
if(!blindmatrix_check_premium()){
	return;
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


<div class="bm_search_contianer bmcsscn">
	<div class="header search-form">
		<div class="header-search-form-wrapper">
			<div class="searchform-wrapper">
				  <div class="form-group has-search">
					<span class="fa fa-search form-control-feedback"></span>
					<input type="text" id="search_products" class="form-control" placeholder="Search..">
					<input type="hidden" value="<?php echo admin_url('admin-ajax.php');?>" id="ajax_url_input">
				  </div>
				  <div class="live-search-results text-left z-top">
					  <div class="autocomplete-suggestions">
					 </div>
				 </div>
			 </div>
		</div>
	</div>
</div>

<script>
	jQuery( document ).ready(function($) {
		
		
		var SearchtypingTimer;              
		var SearchdoneTypingInterval = 500; 
		var $input = jQuery(".bm_search_contianer #search_products");

		//on keyup, start the countdown
		$input.on('keyup', function () {
			var value = $(this).val();
			clearTimeout(SearchtypingTimer);
			SearchtypingTimer = setTimeout(SearchdoneTyping(value), SearchdoneTypingInterval);
		});

		//on keydown, clear the countdown 
		$input.on('keydown', function () {
			clearTimeout(SearchtypingTimer);
		});

		//user is "finished typing," do something
		function SearchdoneTyping (value) {
			
			if( value.length < 3 ){
				$(".autocomplete-suggestions").html('');
				return;
			} 
			var ajaxurl = jQuery('#ajax_url_input').val();
				jQuery.ajax({
					type:'POST',
					url: ajaxurl,
					data: {
						action: 'ajax_search_products',
						query:value
					},
					beforeSend: function(){
					},
					success:function(data){
						if (data.suggestions != null ||data.suggestions != '' ){ 
						   var html = '';
						   $.each(data.suggestions, function() {  
								html += '<a href ="'+this.url+'"><div class="autocomplete-suggestion">';
								if(this.img) html += '<img class="search-image" src="'+this.img+'">';
								html += '<div class="search-name">'+this.value +'</div>';
								if(this.price) html += '<span class="search-price">'+this.price+'<span>';
								html += '</div></a>';
							});
							$(".autocomplete-suggestions").html(html);
						}else{
							$(".autocomplete-suggestions").html('');
						}
					}
				});
			}
		});
</script>