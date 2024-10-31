<?php 
include($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
$item = $_REQUEST['q'];
$clean_item = strip_tags($item);
switch($clean_item){
	case 'get_post':
		global $wpdb;
		$the_post_meta = array();
		$post_id = $_REQUEST['post_id'];
		$the_post = wp_get_single_post($post_id);
		$wpdb->query(sprintf("SELECT `meta_key`, `meta_value`FROM $wpdb->postmeta WHERE `post_id` = %d",$post_id));
		foreach($wpdb->last_result as $k => $v){
			$the_post_meta[$v->meta_key] =   $v->meta_value;
		};
		$the_post->post_meta = $the_post_meta;
		echo json_encode($the_post);
		break;
	default:

		break;
};