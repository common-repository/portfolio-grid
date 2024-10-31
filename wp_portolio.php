<?php

/*
Plugin Name: Portfolio Grid
Plugin URI: http://www.davidmregister.com/portfolio-grid
Description: Display custom Portfolio items in a grid with an interactive filter menu
Author: David Register
Version: 1.2.1
Author URI: http://www.davidmregister.com
*/

add_action('init', 'portfolio_register');
//register portfolio on admin menu 
function portfolio_register() {
 
	$labels = array(
		'name' => _x('Portfolio', 'post type general name'),
		'singular_name' => _x('Portfolio', 'post type singular name'),
		'add_new' => _x('Add New', 'portfolio'),
		'add_new_item' => __('Add New Portfolio'),
		'edit_item' => __('Edit Portfolio'),
		'new_item' => __('New Portfolio'),
		'view_item' => __('View Portfolio'),
		'search_items' => __('Search Portfolio'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' => plugins_url('portfolio-grid') . '/images/Briefcase16.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		/*'taxonomies' => array('post_tag','category'),*/
		'supports' => array('title','editor','thumbnail')
	  ); 
 
	register_post_type( 'portfolio' , $args );
}
/**
 * Add custom taxonomies
 */
function add_custom_taxonomies() {
	// Add new "Locations" taxonomy to Posts
	register_taxonomy('filter_tags', 'portfolio', array(
		// Hierarchical taxonomy (like categories)
		'hierarchical' => true,
		// This array of options controls the labels displayed in the WordPress Admin UI
		'labels' => array(
			'name' => _x( 'Fitler Tags', 'taxonomy general name' ),
			'singular_name' => _x( 'Filter Tags', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Filter Tags' ),
			'all_items' => __( 'All Filter Tags' ),
			'parent_item' => __( 'Parent Filter Tag' ),
			'parent_item_colon' => __( 'Parent Filter Tag:' ),
			'edit_item' => __( 'Edit Filter Tag' ),
			'update_item' => __( 'Update Filter Tag' ),
			'add_new_item' => __( 'Add New Filter Tag' ),
			'new_item_name' => __( 'New Filter Tag' ),
			'menu_name' => __( 'Filter Tags' ),
		),
		// Control the slugs used for this taxonomy
		'rewrite' => array(
			'slug' => 'filter-tags', // This controls the base slug that will display before each term
			'with_front' => false, // Don't display the category base before "/locations/"
			'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
		),
	));
}
add_action( 'init', 'add_custom_taxonomies', 0 );

/*
Be very careful with this function!!!!! Only Run Once!!

function update_post_in_category(){
	global $wpdb;
	$query = new WP_Query('cat=1');
	// The Loop
	while ( $query->have_posts() ) : $query->the_post();
		$wpdb->update( 
			'wp_posts', 
			array( 
				'post_type' => 'portfolio',	// string
			), 
			array(
				'ID' => $query->post->ID
			), 
			array( 
				'%s',	// value1
			), 
			array(
				'%d'
			) 
		);

	endwhile;

	// Reset Post Data
	wp_reset_postdata();
	return;
}
add_action( 'init', 'update_post_in_category');
*/


add_action('post_edit_form_tag', 'portfolio_add_edit_form_multipart_encoding');
//open form multipart for file upload
function portfolio_add_edit_form_multipart_encoding() {

    echo ' enctype="multipart/form-data"';

}

function portfolio_render_image_attachment_box($post) {
	
    // See if there's an existing image. (We're associating images with posts by saving the image's 'attachment id' as a post meta value)
    // Incidentally, this is also how you'd find any uploaded files for display on the frontend.
    
    $portfolio_post_meta = json_decode(get_post_meta($post->ID,'portfolio_post_meta', true));
    $existing_image_id = $portfolio_post_meta->portfolio_attached_image;
    $portfolio_url = $portfolio_post_meta->portfolio_url;
    $portfolio_technologies = $portfolio_post_meta->portfolio_technologies;
	$portfolio_work_type = $portfolio_post_meta->portfolio_work_type;
    $portfolio_current_state = $portfolio_post_meta->portfolio_current_state;
    $portfolio_tags = $portfolio_post_meta->portfolio_tags;
    $portfolio_completion_date = $portfolio_post_meta->portfolio_completion_date
    ?>
   <ul id="upload_image">
    <?php	$existing_image_id_arr = array_filter(explode(',',$existing_image_id));
    		foreach($existing_image_id_arr as $item): 
	            $arr_existing_image = wp_get_attachment_image_src($item, 'medium');
	            $existing_image_url = $arr_existing_image[0];
	            echo '<li class="portfolio_uploaded_img_'.$item.'"><img src="' . $existing_image_url . '" /></li>';
	            echo '<li class="portfolio_uploaded_img_'.$item.'" onclick="portfolio_remove_image(\''.$item.'\')";>X_REMOVE_IMAGE</li>';
    		endforeach;?>
    </ul>
    <table>
    	<tr>
    		<td><label for="portfolio_image">Upload an image:</label><input type="button" id="upload_image_button" value="Select a File" style="margin-bottom:20px;"/></td>
    	</tr>
    	<tr>
    		<td><input type="hidden" name="upload_image_id_hidden" id="upload_image_id_hidden" value="<?php echo (($existing_image_id)?$existing_image_id:"");?>"/></td>
    	</tr>
    	<tr>
    		<td><label for="portfolio_completion_date">Completion Date:</label></td>
    	</tr>
    	<tr>	
    		<td><input type="text" name="portfolio_completion_date" id="portfolio_completion_date" value="<?php echo (($portfolio_completion_date)?$portfolio_completion_date:"");?>" style="margin-bottom:20px;"/></td>
    	</tr>
    	<tr>
    		<td><label for="portfolio_url">URL:</label></td>
    	</tr>
    	<tr>	
    		<td><input type="text" name="portfolio_url" id="portfolio_url" value="<?php echo (($portfolio_url)?$portfolio_url:"");?>" style="margin-bottom:20px;"/></td>
    	</tr>
    	<tr>
    		<td><label for="portfolio_technologies">Technologies:</label></td>
    	</tr>
    	<tr>	
    		<td><input type="text" name="portfolio_technologies" id="portfolio_technologies" value="<?php echo (($portfolio_technologies)?$portfolio_technologies:"");?>" style="margin-bottom:20px;"/></td>
    	</tr>
    	<tr>
			<td><label for="work_type">Work Type:</label><td>
    	</tr>
    	<tr>
    		<td>
    	<?php $worktype = array(
				'Select a WorkType' => 0,
				'Project' => 'Project',
				'Client' => 'Client'
			);
    		echo generateSelect('portfolio_work_type', $worktype, $portfolio_work_type);?>;
    		<td>
    	</tr>
    	<tr>
    		<td><label for="current_state">Current State:</label></td>
    	</tr>
    	<tr>
    		<td>
				<?php $currentstate = array(
					'Select Current State' => '',
					'Complete' => 'Complete',
					'In Progress' => 'In_Progress',
					'On Hold' => 'On_Hold'
				);
				echo generateSelect('portfolio_current_state', $currentstate, $portfolio_current_state);?>
			</td>
		</tr>
    	<tr>
    		<td></td>
    	</tr>
    </table>
   
   <?php 
    // Put in a hidden flag. This helps differentiate between manual saves and auto-saves (in auto-saves, the file wouldn't be passed).
    echo '<input type="hidden" name="manual_save_flag" value="true" />';
}

add_action('admin_init','portfolio_setup_meta_boxes');
function portfolio_setup_meta_boxes() {

    // Add the box to a particular custom content type page
    add_meta_box('portfolio_image_box', 'Upload Image', 'portfolio_render_image_attachment_box', 'portfolio', 'normal', 'high');

}

add_action('save_post','portfolio_update_post',1,2);
function portfolio_update_post($post_id, $post) {
	
    // Get the post type. Since this function will run for ALL post saves (no matter what post type), we need to know this.
    // It's also important to note that the save_post action can runs multiple times on every post save, so you need to check and make sure the
    // post type in the passed object isn't "revision"
    $post_type = $post->post_type;

    // Make sure our flag is in there, otherwise it's an autosave and we should bail.
    if($post_id && isset($_POST['manual_save_flag'])) { 

        // Logic to handle specific post types
        switch($post_type) {

            // If this is a post. You can change this case to reflect your custom post slug
            case 'portfolio':

					$portfolio_post_meta = array();
					
					if(!empty($_POST['upload_image_id_hidden'])){
						$new_ids = strip_tags($_POST['upload_image_id_hidden']);
						$new_ids = trim($new_ids,',');
						$new_ids = rtrim($new_ids,',');
						$portfolio_post_meta['portfolio_attached_image'] = $new_ids;
					}
					$portfolio_url = strip_tags($_POST['portfolio_url']);
					$portfolio_post_meta['portfolio_url'] = $portfolio_url;
					
					$portfolio_technologies = strip_tags($_POST['portfolio_technologies']);
					$portfolio_post_meta['portfolio_technologies'] = $portfolio_technologies;
					
					$portfolio_work_type = strip_tags($_POST['portfolio_work_type']);
					$portfolio_post_meta['portfolio_work_type'] = $portfolio_work_type;
					
					$portfolio_current_state = strip_tags($_POST['portfolio_current_state']);
    				$portfolio_post_meta['portfolio_current_state'] = $portfolio_current_state;
                	
                	$portfolio_tags = strip_tags($portfolio_tags);
                	$portfolio_tags = rtrim($portfolio_tags, ' ');
                	$portfolio_post_meta['portfolio_tags'] = $portfolio_tags;
                	
                	$portfolio_completion_date = strip_tags($_POST['portfolio_completion_date']);
    				$portfolio_post_meta['portfolio_completion_date'] = $portfolio_completion_date;
                	
                	$portfolio_post_meta = json_encode($portfolio_post_meta);

                	update_post_meta($post_id,'portfolio_post_meta', $portfolio_post_meta);

            break;
            default:
            

        } // End switch

    return;

} // End if manual save flag

    return;

}

add_action('admin_print_scripts', 'upload_admin_scripts');
add_action('admin_print_styles', 'upload_admin_styles');
function upload_admin_scripts() {
	enqueue_scripts('admin');
}

function upload_admin_styles() {
	enqueue_styles('admin');
}

add_action("manage_posts_custom_column",  "portfolio_custom_columns");
add_filter("manage_edit-portfolio_columns", "portfolio_edit_columns");
function portfolio_edit_columns($columns){
  $columns = array(
    "cb" => "<input type=\"checkbox\" />",
    "thumb" => "Portfolio Image",
    "title" => "Name",
    "published" => "Active",
    "description" => "Description",
    "url" => "URL"
  );
 
  return $columns;
}

function portfolio_custom_columns($column){
  global $post;
 
  switch ($column) {
    case "description":
      the_excerpt();
      break;
    case "thumb":

		echo '<div>';
		the_post_thumbnail('thumbnail');
		echo '</div>';
	
    break;
    case "url":
      	$portfolio_meta = json_decode(get_post_meta($post->ID,'portfolio_post_meta',true));
      	echo "<a href='".$portfolio_meta->portfolio_url."' target='_blank'>".$portfolio_meta->portfolio_url."</a>";
    break;
    case "published":
      	if($post->post_status == 'publish'){
      		echo "Yes";
      	}else{
      		echo "No";
      	}
    break;
  }
}

function generateSelect($name = '', $options = array(), $selected = '') {
	$html = '<select name="'.$name.'" style="margin-bottom:20px;">';
	$select = str_replace("_"," ",$selected);
	foreach ($options as $option => $value) {
		$html .= '<option value='.$value.' '.(($selected == $value)?'selected="selected"':'').'>'.$option.'</option>';
	}
	$html .= '</select>';
	return $html;
}

function get_attachment_id_from_src($image_src){
	global $wpdb;
	
	$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
	$id = $wpdb->get_var($query);
	return $id;
}

add_filter('sanitize_file_name', 'portfolio_filename_hash', 10);
function portfolio_filename_hash($filename) {
    $portfolio_encrypt_images = get_option('portfolio_encrypt_images');
    if($portfolio_encrypt_images){
    	$info = pathinfo($filename);
    	$ext  = empty($info['extension']) ? '' : '.' . $info['extension'];
    	$name = basename($filename, $ext);
    	return md5($name) . $ext;
    }else{
    	return $filename;
    }
}

function portfolio_admin(){
	global $pagenow;
	enqueue_styles('admin');
	include('portfolio_settings.php');
}

add_action('admin_menu', 'portfolio_admin_actions');
function portfolio_admin_actions() {
	add_submenu_page("edit.php?post_type=portfolio", "Portfolio Settings", "Settings", 1, "portfolio-settings","portfolio_admin");
}

add_filter('page_template', 'portfolio_page_template' );
function portfolio_page_template( $page_template ){
    if (is_page('portfolio')){
    	enqueue_styles('site');
    	enqueue_scripts('site');
    	$page_template = dirname( __FILE__ ) . '/page-portfolio.php';
    }
    return $page_template;
}

add_filter( "single_template", "portfolio_single_template" ) ;
function portfolio_single_template($single_template) {
     global $post;

     if ($post->post_type == 'portfolio') {
     	enqueue_styles('site');
        enqueue_scripts('site');
     	$single_template = dirname( __FILE__ ) . '/page-portfolio_single.php';
     }
     
     add_filter('nav_menu_css_class', 'current_type_nav_class', 10, 2 );
     
     return $single_template;
}

function enqueue_scripts($type=''){
	
	if($type == 'admin'){
		// enqueue the script
		wp_enqueue_script('jquery-ui-core');
		
		wp_enqueue_script('jquery-ui-datepicker');
		
		// enqueue the script
		wp_enqueue_script('media-upload');
		// enqueue the script
		wp_enqueue_script('thickbox');
		
		wp_register_script('my-upload', plugins_url('portfolio-grid/').'/js/media_upload.js', array('jquery','jquery-ui-core','media-upload','thickbox'));
		// enqueue the script
		wp_enqueue_script('my-upload');
	
	}elseif($type == 'site'){
		wp_enqueue_script('jquery-ui-core');
	
		// register your script location, dependencies and version
		wp_register_script('custom-ajax',plugins_url('portfolio-grid/') . '/js/ajax.js',array('jquery','jquery-ui-core'));
	   // enqueue the script
	   wp_enqueue_script('custom-ajax');
	   
	   wp_register_script('nivo-slider',plugins_url('portfolio-grid/') . '/js/jquery.nivo.slider.pack.js',array('jquery'));
	   // enqueue the script
	   wp_enqueue_script('nivo-slider');
	   
	   wp_register_script('easing',plugins_url('portfolio-grid/') . '/js/jquery.easing.js',array('jquery','jquery-ui-core'));
	   // enqueue the script
	   wp_enqueue_script('easing');
	   
	   wp_register_script('quicksand',plugins_url('portfolio-grid/') . '/js/jquery.quicksand.js',array('jquery','jquery-ui-core'));
	   // enqueue the script
	   wp_enqueue_script('quicksand');
   
   	}
}

function enqueue_styles($type = ''){
	
	if($type == 'admin'){
	
		$jquery_datpicker_css = plugins_url('', __FILE__).'/css/jquery.ui.datepicker.css'; // Respects SSL, Style.css is relative to the current file
		$jquery_datpicker_file = dirname(__FILE__) . '/css/jquery.ui.datepicker.css';
		if ( file_exists($jquery_datpicker_file) ) {
			wp_register_style('jquery_datpicker', $jquery_datpicker_css);
			wp_enqueue_style( 'jquery_datpicker');
		}
		
		$jquery_base_css = plugins_url('', __FILE__).'/css/jquery.ui.all.css'; // Respects SSL, Style.css is relative to the current file
		$jquery_base_file = dirname(__FILE__) . '/css/jquery.ui.all.css';
		if ( file_exists($jquery_base_file) ) {
			wp_register_style('jquery-base-css', $jquery_base_css);
			wp_enqueue_style( 'jquery-base-css');
		}
		
		wp_enqueue_style('thickbox');
	
	}elseif($type == 'site'){
		
		$nivo_style_css = plugins_url('', __FILE__).'/themes/nivo/nivo.css'; // Respects SSL, Style.css is relative to the current file
		$nivo_style_file = dirname(__FILE__) . '/themes/nivo/nivo.css';
		if ( file_exists($nivo_style_file) ) {
			wp_register_style('nivo-theme', $nivo_style_css);
			wp_enqueue_style( 'nivo-theme');
		}
	
	}
	
	$portfolio_style_css = plugins_url('', __FILE__).'/css/portfolio_style.css'; // Respects SSL, Style.css is relative to the current file
	$portfolio_style_file = dirname(__FILE__) . '/css/portfolio_style.css';
	if ( file_exists($portfolio_style_file) ) {
		wp_register_style('portfolioStyle', $portfolio_style_css);
		wp_enqueue_style( 'portfolioStyle');
	}
	
}

function current_type_nav_class($classes, $item) {
    //$post_type = get_post_type();
    if ($item->title != '' && $item->title == 'Portfolio') {
        array_push($classes, 'current_page_item');
    };
    return $classes;
}


function portfolio_admin_tabs( $current = 'homepage' ) { 
    $tabs = array( 'homepage' => 'Home', 'image_settings' => 'Image Settings', 'layout' => 'Layout','filter' => 'Filter'); //'tags' => 'Tags', removed custom tags Tab in settings
    $links = array();
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?post_type=portfolio&page=portfolio-settings&tab=$tab'>$name</a>";
    }
    echo '</h2>';
}