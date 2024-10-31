<?php

    // calling the header.php
    get_header();

?>	
<?php
	$taxonomies = 'filter_tags';
	$args = array('hide_empty' => false);
	$terms = get_terms($taxonomies,$args);
	
	$portfolio_tags_settings = json_decode(get_option('portfolio_tags_settings'));
	$portfolio_use_portfolio_filter = $portfolio_tags_settings->portfolio_use_portfolio_filter;
	if(!empty($portfolio_use_portfolio_filter)):?>
		
		<ul id="filters" class="filter_by">
			<li>FILTER BY:</li>
			<li><a href="#" data-value="all">show all</a></li>
		<?php

			if(!empty($terms)):
				foreach($terms as $item):
					$classname = str_replace('.','_',$item->name);
					$classname = str_replace(' ','_',$classname);
					?>
					<li><a href="#" data-value="<?php echo $classname;?>"><?php echo $item->name;?></a></li>
				<?php endforeach;
			else:?>
				<li>Please visit the Settings page to enable filter tags</li>
			<?php endif;?>
		</ul>
	<?php endif;?>
		<div style="clear:both"> </div>
		<div id="portfolio_container" class="grid_12 portfolio_full_width">
			<div id="content" class="portfolio_full_width image-grid">
	            <?php 
		            $portfolio_images_settings = json_decode(get_option('portfolio_images_settings'));
		            $portfolio_images_num_display = $portfolio_images_settings->portfolio_images_num_display;
		            query_posts('post_type=portfolio&posts_per_page='.intval($portfolio_images_num_display));
		                
					//Start the Loop.
					if ( have_posts() ) : while ( have_posts() ) : the_post();
						
						$post_id = $post->ID;
						
						$portfolio_feat_image = wp_get_attachment_url(get_post_thumbnail_id($post_id));
						
						//Returns Array of Term Names for "my_term"
						$term_list = wp_get_post_terms($post_id, 'filter_tags', array("fields" => "names"));
						$postclasses = '';
						$posttags = '';
						foreach($term_list as $item):
							$posttags .= $item.", ";
							$postclasses .= str_replace(" ","_",$item)."-";
						endforeach;
						
				?>
				
				    <div id="post_<?php echo $post_id ?>" class="row" data-id="<?php echo rtrim($postclasses,'-');?>">
            		
					<a href="<?php the_permalink();?>"><img src="<?php echo $portfolio_feat_image;?>" alt="<?php the_title();?>"/></a>
					
					<div class="row_title"><a href="<?php the_permalink();?>"><?php the_title(); ?></a></div>
					

					</div> <!-- closes the first div box -->
				 <!-- Stop The Loop (but note the "else:" - see next line). -->
				 <?php endwhile; else: ?>
				
				 <!-- The very first "if" tested to see if there were any Posts to -->
				 <!-- display.  This "else" part tells what do if there weren't any. -->
				 <p>Sorry, no posts matched your criteria.</p>
				
				 <!-- REALLY stop The Loop. -->
				 <?php endif; ?> 
				
				<div class="clear" style="clear:both"></div>
			</div><!-- #content -->
		</div><!-- #portfolio_container -->
<div class="clear" style="clear:both"></div>
<?php

	// calling the sidebar.php
	//get_sidebar();

    // calling footer.php
    get_footer();

?>