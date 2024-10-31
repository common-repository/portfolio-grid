<?php
	if($_POST['portfolio_settings_hidden'] == 'Y' && check_admin_referer('portfolio_update_settings','portfolio-settings-page')) :
		//Form data sent
		if($pagenow == 'edit.php' && isset($_GET['tab'])){
			$tab = $_GET['tab'];
		}else{
			$tab = 'homepage';
		}
		
		switch($tab){
			case 'image_settings':
				$portfolio_image_settings = array();
				$portfolio_encrypt_images = $_POST['portfolio_encrypt_images'];
				$portfolio_image_settings['portfolio_encrypt_images'] = $portfolio_encrypt_images;
				
				$portfolio_images_num_display = $_POST['portfolio_images_num_display'];
				$portfolio_images_num_display = intval($portfolio_images_num_display);
				$portfolio_image_settings['portfolio_images_num_display'] = $portfolio_images_num_display;
				
				$portfolio_images_width = $_POST['portfolio_images_width'];
				$portfolio_images_width = intval($portfolio_images_width);
				$portfolio_image_settings['portfolio_images_width'] = $portfolio_images_width;
				
				$portfolio_images_height = $_POST['portfolio_images_height'];
				$portfolio_images_height = intval($portfolio_images_height);
				$portfolio_image_settings['portfolio_images_height'] = $portfolio_images_height;
				
				$portfolio_image_settings = json_encode($portfolio_image_settings);
				update_option('portfolio_images_settings', $portfolio_image_settings);
				
			break;
			
			case 'filter':
				
				$portfolio_use_portfolio_filter = $_POST['portfolio_use_portfolio_filter'];
				$portfolio_tags_settings['portfolio_use_portfolio_filter'] = $portfolio_use_portfolio_filter;
				
				$portfolio_tags_settings = json_encode($portfolio_tags_settings);
				update_option('portfolio_tags_settings', $portfolio_tags_settings);
			break;
			
			case 'layout':
				$portfolio_layout_settings = array();
				$portfolio_use_full_width = $_POST['portfolio_use_full_width'];
				$portfolio_layout_settings['portfolio_use_full_width'] = $portfolio_use_full_width;
				
				$portfolio_layout_settings = json_encode($portfolio_layout_settings);
				update_option('portfolio_layout_settings', $portfolio_layout_settings);
			break;
			
			case 'homepage':?>
				
		<?php break;
		}?>
		
		<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
	<?php else:
		//Normal page display
		$portfolio_images_settings = json_decode(get_option('portfolio_images_settings'));
		$portfolio_encrypt_images = $portfolio_images_settings->portfolio_encrypt_images;
		$portfolio_images_width = $portfolio_images_settings->portfolio_images_width;
		$portfolio_images_height = $portfolio_images_settings->portfolio_images_height;
		$portfolio_images_num_display = $portfolio_images_settings->portfolio_images_num_display;
		
		$portfolio_tags_setting = json_decode(get_option('portfolio_tags_settings'));
		$portfolio_project_tag_hidden = $portfolio_tags_setting->portfolio_project_tag_hidden;
		$portfolio_use_portfolio_filter = $portfolio_tags_setting->portfolio_use_portfolio_filter;
		
		$portfolio_layout_setting = json_decode(get_option('portfolio_layout_settings'));
		$portfolio_use_full_width = $portfolio_layout_setting->portfolio_use_full_width;
		
		
	endif;
?>
<div class="wrap">
	<?php echo "<h2>" . __( 'Portfolio Settings' ) . "</h2>";
		
		if($pagenow == 'edit.php' && isset($_GET['tab'])){
			portfolio_admin_tabs($_GET['tab']); 	
		}else{
			portfolio_admin_tabs('homepage');
		}
	?>
	<div id="poststuff">
		<form method="post" action="<?php admin_url( 'themes.php?page=theme-settings' ); ?>">
			<?php
			wp_nonce_field("portfolio_update_settings","portfolio-settings-page"); 
			
			if ( $_GET['page'] == 'portfolio-settings' ){ 
			
				if (isset ( $_GET['tab'])){
					$tab = $_GET['tab'];
				}else{
					$tab = 'homepage'; 
				}?>
				<table style="width:50%;padding:5px;" cellpadding="5">
			<?php switch ( $tab ){
					case 'image_settings':?>
						<tr>
							<td></td>
						</tr>
						<tr>
							<td><?php _e("Encrypt Uploaded Images: " ); ?><br /><span style="color: #aaaaaa;text-shadow: white 0 1px 0;">Encrypts the image titles for security reasons.</span></td>
							<td><input type="checkbox" name="portfolio_encrypt_images" <?php echo (($portfolio_encrypt_images)? "CHECKED":""); ?>></td>
						</tr>
						<tr>
							<td><?php _e("Number of Projects to Display: " ); ?></td>
							<td><input type="text" name="portfolio_images_num_display" size="3" value="<?php echo (($portfolio_images_num_display)?$portfolio_images_num_display:"");?>"></td>
						</tr>
						<tr>
							<td colspan="2"><h3>Sets the settings for the slider on the Portfolio Single. Setting the image sizes will not adjust the sizes of the actual images, it will only adjust the size of the slider. Please set this to the sizes of your images.</h3></td>
						</tr>
						<tr>
							<td><?php _e("Set Image Slider Sizes " ); ?> (default: 700px by 230px)<br /><span style="color: #aaaaaa;text-shadow: white 0 1px 0;">Leave blank to use default</span></td>
							<td>Width: <input type="text" name="portfolio_images_width" size="4" value="<?php echo (($portfolio_images_width)?$portfolio_images_width:"");?>"/>px &nbsp;&nbsp; Height: <input type="text" name="portfolio_images_height" size="4" value="<?php echo (($portfolio_images_height)?$portfolio_images_height:"");?>"/>px</td>
						</tr>
						<?php
					break; 
					case 'filter' : ?>
						<tr>
							<td colspan="2"><h3>Setup custom tags for your projects. These tags will be used to filter the project using the filter by on the Portfolio page.</h3></td>
						</tr>
						<tr>
							<td width="400px"><?php _e("Use Filter By: " ); ?><br /><span style="color: #aaaaaa;text-shadow: white 0 1px 0;">If not checked, the tags will not show up on the Porfolio page for the filter.</span></td>
							<td><input type="checkbox" name="portfolio_use_portfolio_filter" <?php echo (($portfolio_use_portfolio_filter)? "CHECKED":"");?> /></td>
						</tr>
				<?php break;
					case 'layout':?>
						<tr>
							<td></td>
						</tr>
						<tr>
							<td colspan="2"><h3>Setup custom layout for the Single Portfolio page. If this is checked it will not use the sidebar and use a full width container.</h3></td>
						</tr>
						<tr>
							<td>
								<?php _e("Use Fullwidth Layout for Single Page (no sidebar): " ); ?>
								<input type="checkbox" name="portfolio_use_full_width" <?php echo (($portfolio_use_full_width)? "CHECKED":"");?>/>
							</td>
						</tr>
						<?php
					break;
					case 'homepage':?>
						<tr>
							<td>The place where you can update the setting for the theme. ***MAKE SURE TO UPDATE POST BEFORE LEAVING THE TABS OR THESETTINGS WILL NOT SAVE***</td>
						</tr>
						<?php
					break;
				}?>
				</table>
		<?php } ?>
			<p class="submit" style="clear: both;">
			<?php if($tab != 'homepage'):?>
				<input type="submit" name="Submit"  class="button-primary" value="Update Settings" />
			<?php endif;?>
				<input type="hidden" name="portfolio_settings_hidden" value="Y"/>
			</p>
		</form>
	</div>
</div>