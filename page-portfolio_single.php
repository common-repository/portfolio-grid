<?php

    // calling the header.php
    get_header();

?>
		<?php 
			$portfolio_images_settings = json_decode(get_option('portfolio_images_settings'));
			$portfolio_layout_settings = json_decode(get_option('portfolio_layout_settings'));
			
			$portfolio_use_full_width = $portfolio_layout_settings->portfolio_use_full_width;
			$portfolio_images_width = $portfolio_images_settings->portfolio_images_width;
			$portfolio_images_height = $portfolio_images_settings->portfolio_images_height;
		?>
		<div id="container" <?php echo (($portfolio_use_full_width)? "class='content_full_width'":"");?> class="grid_9 alpha">
			
			<div id="content" >
		
    	        <!-- Start the Loop. -->
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				
				<div class="post">
				
				 <!-- Display the Post's Content in a div box. -->
				 <div class="entry">
				 <?php
				 	$post_id = $post->ID;
				 	$portfolio_post_meta = json_decode(get_post_meta($post_id,'portfolio_post_meta', true));
					$portfolio_image_id_arr = array_filter(explode(',',$portfolio_post_meta->portfolio_attached_image));
					
				if(!empty($portfolio_image_id_arr)):?>
					 <div class="slider-wrapper theme-nivo">
						<div id="slider" class="nivoSlider">
							<?php foreach($portfolio_image_id_arr as $item):
									$arr_portfolio_image = wp_get_attachment_image_src($item, 'large');
									$portfolio_image_url = $arr_portfolio_image[0];?>
									<img src="<?php echo $portfolio_image_url; ?>" alt="<?php the_title(); ?>"/>
							<?php endforeach;?>
						</div>
					</div>
				<?php endif;?>
				
				<!-- Display the Title as a link to the Post's permalink. -->
				 <h3 style="margin-top:15px;"><?php the_title(); ?></h3>
				 	<div class="portfolio_info_side">
				 		<p><strong>Technologies:</strong></p><p><?php echo $portfolio_post_meta->portfolio_technologies;?></p>
				 		<p><strong>Status:</strong></p><p><?php echo str_replace('_', ' ', $portfolio_post_meta->portfolio_current_state);?></p>
				 		<p><strong>Completion Date:</strong></p><p><?php echo $portfolio_post_meta->portfolio_completion_date;?></p>
				 		<p><strong>URL:</strong></p><p><a href="<?php echo $portfolio_post_meta->portfolio_url;?>" target="_blank">View Site</a></p>
				 	</div>
				   	<div class="portfolio_info_main"><?php the_content();?></div>
				 	
				 	<div class="clear" style="clear:both;"></div>
				 </div><!-- close #entry -->
				 
				 </div> <!-- closes the first div box -->
				
				 <!-- Stop The Loop (but note the "else:" - see next line). -->
				 <?php endwhile; else: ?>
				
				 <!-- The very first "if" tested to see if there were any Posts to -->
				 <!-- display.  This "else" part tells what do if there weren't any. -->
				 <p>Sorry, no posts matched your criteria.</p>
				
				 <!-- REALLY stop The Loop. -->
				 <?php endif; ?>
		
			</div><!-- #content -->
			
		</div><!-- #container -->
		
<?php if(empty($portfolio_use_full_width)):?>
	<div id="primary" class="aside main-aside grid_3 omega">
		<ul>	
			<li id="portfolio_title_list">Other Projects</li>
				<?php
			
			// The Query
			query_posts('post_type=portfolio');
			
			// The Loop
			while ( have_posts() ) : the_post();?>
				<li <?php echo (($post_id == $post->ID)? 'class="portfolio_title_list_active"':'');?>><a href="<?php the_permalink();?>"><?php the_title();?></a></li>
			<?php endwhile;
			
			// Reset Query
			wp_reset_query();
		
		?>
		</ul>
	</div> 
<?php endif;?>

<?php if(!empty($portfolio_images_width) || !empty($portfolio_images_height)):?>
<style type="text/css">
	.theme-nivo.slider-wrapper,.theme-nivo #slider {
   		width: <?php echo (($portfolio_images_width)?$portfolio_images_width:'');?>px; /* Make sure your images are the same size */
    	height:<?php echo (($portfolio_images_height)?$portfolio_images_height:'');?>px; /* Make sure your images are the same size */
	}
</style>
<?php endif;
	
    	// calling footer.php
    	get_footer();
	?>