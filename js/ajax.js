function get_portfolio($url,$param, $img_url,$div_num,$div_position){
	var ajaxRequest;  // The variable that makes Ajax possible!
	
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
	// Create a function that will receive data sent from the server
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
				var obj =  JSON.parse(ajaxRequest.responseText);
				
				var el = document.getElementById('description-'+$div_num+'-p');
				var inner_text = '';
				
				if(obj){	
					inner_text += '<div class="description">';
					inner_text += '<div id="updated_'+$param+'" class="project_img">';
					inner_text +=	'<img src="'+$img_url+'" alt="+obj.post_title+" id="'+$param+'"/>';
					inner_text += '</div>';
					inner_text += '<div class="project_desc">';
					inner_text +=	'<p class="title">'+obj.post_title+'</p>';
					inner_text +=	'<a href="#" id="close"><img src="/wp-content/themes/imdavidr/library/images/close.png" alt="close"/></a>';
					inner_text +=	'<p class="sub_title">Description</p><p>'+obj.post_content+'</p>';
					/* inner_text +=	'<p class="sub_title">Status</p><p>'+obj.post_meta.portfolio_current_state+'</p>'; */
					inner_text +=	'<p class="sub_title">Technologies</p><p>'+obj.post_meta.portfolio_technologies+'</p>';
					inner_text +=	'<p class="clear"></p>';
					inner_text +=	'<a href="'+obj.post_meta.portfolio_url+'" class="buttons" target="_blank">View Site</a>';
					inner_text += '</div>';
					inner_text += '</div>';
					
					if(jQuery(el).is(":visible")){
						el.innerHTML = inner_text; 
					}else{
						el.innerHTML = inner_text;
						jQuery(el).slideDown('slow');
					}
					
					//set bg image
					var desc = jQuery(el).children('div');
		
					if($div_position == "1"){
						bg_img = "url(/wp-content/themes/imdavidr/library/images/desc_left.png)";
					}else if($div_position == "2"){
						bg_img = "url(/wp-content/themes/imdavidr/library/images/desc_middle.png)";
					}else{
						bg_img = "url(/wp-content/themes/imdavidr/library/images/desc_right.png)";
					}
					jQuery(desc).css("background", bg_img);
					
				}else{
					el.innerHTML = "Sorry, no posts matched your criteria.";
				}
			}
		}
		var updated = document.getElementById('updated_'+$param);
		if(updated){
			return false;
		}
		if($param){
			var $url_param = "q=get_post&post_id="+$param
		}
		ajaxRequest.open("GET",$url+"?"+$url_param, true);
		ajaxRequest.send(); 
		
}
//-->

// Custom sorting plugin
(function($) {
  $.fn.sorted = function(customOptions) {
    var options = {
      reversed: false,
      by: function(a) { return a.text(); }
    };
    $.extend(options, customOptions);
    $data = $(this);
    arr = $data.get();
    arr.sort(function(a, b) {
      var valA = options.by($(a));
      var valB = options.by($(b));
      if (options.reversed) {
        return (valA < valB) ? 1 : (valA > valB) ? -1 : 0;				
      } else {		
        return (valA < valB) ? -1 : (valA > valB) ? 1 : 0;	
      }
    });
    return $(arr);
  };
})(jQuery);

// DOMContentLoaded
jQuery(function() {

  //save links to retreive value later.
  var $filterSort = jQuery('#filters li a');

  // get the first collection of content
  var $content = jQuery('#content');

  // clone content to get a second collection
  var $data = $content.clone();

  // attempt to call Quicksand on every link click
  $filterSort.click(function(e) {
    e.preventDefault();
    var searchTerm = jQuery(this).attr('data-value');
    if (jQuery(this).attr('data-value') == 'all') {
      var $filteredData = $data.find('.row');
    } else {
      var $filteredData = [];
      $data.children('div').each(function(){
			if(jQuery(this).data('id') != undefined){
				if(jQuery(this).data('id').indexOf(searchTerm) != -1){
					$filteredData.push(this);
				}
			}
		});
    }
   
	/*var $sortedData = $filteredData.sorted({
        by: function(v) {
          return jQuery(v).find('div.row_title a').text().toLowerCase();
        }
    });*/  

    // finally, call quicksand
    $content.quicksand($filteredData);

  });
  
  	jQuery('#slider').nivoSlider({
       	effect: 'easeInOutQuad',
       	pauseTime: 6000
	});
  

});