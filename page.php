<?php
/**
 * @package WordPress
 * @subpackage PulsePress
 */
?>
<?php get_header(); ?>

<div class="sleeve_main">

	<div id="main">
		<?php pulse_press_breadcrumbs(); ?>
		<?php if ( have_posts() ) : ?>
			
			<?php while ( have_posts() ) : the_post(); ?>
				<h1 id="page-title"><?php the_title(); ?></h1>
				<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
						<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
			<?php endwhile; ?>
		
		<?php endif; ?>
		
		
	</div> <!-- main -->

</div> <!-- sleeve -->

<?php get_footer(); ?>