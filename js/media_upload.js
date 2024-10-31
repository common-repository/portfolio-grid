jQuery(document).ready(function() {

	jQuery("#portfolio_completion_date").datepicker({dateFormat: 'yy-mm-dd' });

	var $default=window.send_to_editor;
	jQuery('#upload_image_button').click(function() {
		formfield = jQuery('#upload_image').attr('name');
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		
		window.send_to_editor = function(html) {
			$classes = jQuery('img', html).attr('class');
			$id = $classes.replace(/(.*?)wp-image-/, '');
			$hidden_div = jQuery('#upload_image_id_hidden');
			$hidden_div.val($hidden_div.val()+','+$id);
			$imgurl = jQuery('img',html).attr('src');
			jQuery('#upload_image').after("<li class='portfolio_uploaded_img_"+$id+"'><img src='"+$imgurl+"' alt=''/></li><li class='portfolio_uploaded_img_"+$id+"' onclick=\"portfolio_remove_image('"+$id+"');\">X_REMOVE_IMG</li>");
			tb_remove();
			window.send_to_editor=$default;
		}
		
		return false;
	});

});

//Remove image from custom field box below editor if uploaded
function portfolio_remove_image($id){
	jQuery('.portfolio_uploaded_img_'+$id).remove();
	
	$hidden_val = jQuery('#upload_image_id_hidden');
	$value = $hidden_val.val();
	
	$new_value = $value.replace($id,'');
	$hidden_val.val($new_value);
}

//add custom filter tags to portfolio **DEPRICATED** using custom taxonomies
function portfolio_project_add_tags(){
	$this = jQuery('#portfolio_project_tag');
	$hidden = jQuery('#portfolio_project_tag_hidden');
	$hidden_val = $hidden.val();
	$this_val = $this.val().replace(/[^a-z0-9.]/gi,' ').replace(/ /g,'_');
	jQuery('#portfolio_project_tags_list').append("<li id='portfolio_tag_"+$this_val+"'><a href='#' onclick=\"portfolio_remove_tags('portfolio_tag_"+$this_val+"');\">X</a> "+$this_val+"</li>");

	$hidden.val($hidden_val+""+$this_val+"-");
	$this.val('');
}

//remove custom filter tags to portfolio **DEPRICATED** using custom taxonomies
function portfolio_remove_tags($id){
	$this = jQuery("#"+$id);
	
	$hidden = jQuery('#portfolio_project_tag_hidden');
	$value = $hidden.val();
	
	$remove_text = $this.text().split(' ');
	
	$new_value = $value.replace($remove_text[1],'');
	$hidden.val($new_value);
	
	$this.remove();
}