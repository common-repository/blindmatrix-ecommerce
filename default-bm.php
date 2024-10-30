<?php
/**
 * The template for displaying all pages.
 *
 * @package flatsome
 */




get_header();
?>
<div id="content" class="bmcsscn content-area page-wrapper" role="main" style="width:100%">
	<div class="row row-main">
		<div class="large-12 col">
			<div class="col-inner">
				
				<header class="entry-header">
					<h1 class="entry-title mb uppercase"><?php the_title(); ?></h1>
				</header>
				<?php 
				while ( have_posts() ) :
					the_post(); 
					?>
						<?php the_content(); ?>
				<?php endwhile; // end of the loop. ?>
			</div>
		</div>
	</div>
</div>

<?php
get_footer();

?>
