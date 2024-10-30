<?php /* Template Name: Full Width BM */ 

get_header(); ?>

<?php 
/**
 * Hook:flatsome_before_page.
 *
 * @since 1.0
 */
do_action( 'flatsome_before_page' ); 
?>

<div id="content" role="main" class="bmcsscn content-area" style="width:100%;margin:0px;">

		<?php 
		while ( have_posts() ) :
			the_post(); 
			?>

			<?php the_content(); ?>
		
		<?php endwhile; // end of the loop. ?>
		
</div>

<?php 
/**
 * Hook:flatsome_after_page.
 *
 * @since 1.0
 */
do_action( 'flatsome_after_page' ); 
?>

<?php get_footer(); ?>
