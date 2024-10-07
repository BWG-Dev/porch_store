<?php
/**
 * Doors Categories page template
 * Template Name: Doors Categories Page
 */

get_header(); ?>

<?php get_template_part('partials/header-nav-solid'); ?>
<?php //get_template_part('partials/header-nav-with-background'); ?>

<?php // resolve if show sidebar layout
$sidebar = my_get_sidebar(); ?>

<div class="main-wrap <?php if($sidebar) echo 'has-sidebar has-sidebar-' . $sidebar; ?>">

	<?php if($sidebar): ?>
		<aside>
			<?php dynamic_sidebar($sidebar); ?>
		</aside>
	<?php endif; ?>

	<main>
		<section id="page-content">
			<div class="container">

				<?php 
				 $term = get_queried_object();

    $children = get_terms( 'product_cat', array(
        'parent'    => '189',
        'hide_empty' => false
    ) );

    if ( $children ) {
    	//echo '<ul class="doors_categories_section">';
		
		echo '<div class="vc_row wpb_row vc_inner vc_row-fluid">';
        foreach( $children as $subcat )
        {
			$thumbnail_id = get_woocommerce_term_meta( $subcat->term_id, 'thumbnail_id', true );
			$image = wp_get_attachment_image_src( $thumbnail_id, 'medium' );
			/* echo '<li><a href="' . esc_url(get_term_link($subcat, $subcat->taxonomy)) . '">';                                  			
			echo '<img src="' . $image[0] . '" alt="" />'; 
			echo '<h4>'.$subcat->name.'</h4>';  
			echo '<p>'.$subcat->description.'</p>';
            echo '</a></li>'; */
			?>
			<div class="wpb_column vc_column_container vc_col-sm-4">
				<div class="vc_column-inner ">
					<div class="wpb_wrapper">
						<div class="wpb_raw_code wpb_content_element wpb_raw_html">
							<div class="wpb_wrapper">
							<a href="<?php echo esc_url(get_term_link($subcat, $subcat->taxonomy)) ;?>"><h3 style="text-align: center;"> <?php echo $subcat->name;?></h3></a>
							<div class="wpb_single_image wpb_content_element vc_align_center">
								<figure class="wpb_wrapper vc_figure">
									<div class="vc_single_image-wrapper  vc_box_border_grey"><a href="<?php echo esc_url(get_term_link($subcat, $subcat->taxonomy)) ;?>"> <img src="<?php echo $image[0];?>" class="vc_single_image-img attachment-full" alt=""></a></div>
								</figure>
							</div>
							</div>
						</div>
						<div class="wpb_text_column wpb_content_element ">
							<div class="wpb_wrapper">							
							<p style="text-align: center;"><?php echo $subcat->description;?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
        }
		echo '</div>';
        //echo '</ul>';
    }
				?>

				<?php while(have_posts()):
					the_post(); ?>

					<?php if(is_archive() || is_home()): ?>
						<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php the_excerpt(); ?>
					<?php else: ?>
						<?php the_content(); ?>

					<?php endif; ?>

				<?php endwhile; ?>

			</div>
		</section>
	</main>

</div>
<?php get_footer();
