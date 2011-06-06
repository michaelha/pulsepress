<?php
/**
 * Category Template
 *
 * This template is loaded when viewing a category archive.
 * It shows the category's description and latest posts.
 * @link http://codex.wordpress.org/Category_Templates
 *
 * @package Pulsepress
 * @subpackage Template
 */
 $tag_obj = $wp_query->get_queried_object();
get_header(); ?>
<div class="sleeve_main">
	<?php if ( pulse_press_user_can_post() && !is_archive() ) : ?>
		<?php locate_template( array( 'post-form.php' ), true ); ?>
	<?php endif; ?>
	<div id="main">
		<h2>
			
				<?php single_cat_title(); ?> <?php if ( pulse_press_get_page_number() > 1 ) printf( __( 'Page %s', 'pulse_press' ), pulse_press_get_page_number() ); ?><a class="rss" href="<?php echo get_category_feed_link( $tag_obj->term_id ); ?>">RSS</a>
			
	
			<span class="controls">
				<a href="#" id="togglecomments"> <?php _e( 'Toggle Comment Threads', 'pulse_press' ); ?></a> | <a href="#directions" id="directions-keyboard"><?php _e( 'Keyboard Shortcuts', 'pulse_press' ); ?></a>
			</span>
		</h2>
		
		<?php 
		
		if(isset($_GET['starred'])):
			$paged = pulse_press_get_page_number();	
			query_posts(array('post__in'=>pulse_press_get_user_starred_post_meta(), 'paged'=>$paged));
		endif; 
	
		?>
		
		<ul id="postlist">
		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>
	    		<?php pulse_press_load_entry() // loads entry.php ?>
			<?php endwhile; ?>

		<?php else : ?>
			
			<li class="no-posts">
		    	<h3><?php _e( 'No posts yet!', 'pulse_press' ); ?></h3>
			</li>
			
		<?php endif; ?>
		</ul>
		
		
		<?php if ( $wp_query->max_num_pages > 1 ) : ?>
		<div class="navigation">
			<p class="nav-older"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'pulse_press' ) ); ?></p>
			<p class="nav-newer"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'pulse_press' ) ); ?></p>
		</div>
		<?php endif; ?>
	
	</div> <!-- main -->

</div> <!-- sleeve -->
<?php get_footer(); ?>