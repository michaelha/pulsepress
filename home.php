<?php
/**
 * @package WordPress
 * @subpackage PulsePress
 */
?>
<?php get_header(); ?>

<div class="sleeve_main">
	<?php if ( pulse_press_user_can_post() ) : ?>
		<?php locate_template( array( 'post-form.php' ), true ); ?>
	<?php endif; ?>
	<div id="main">
		<h2>
			<?php if ( pulse_press_get_page_number() > 1 ) printf( __( 'Page %s', 'pulse_press' ), pulse_press_get_page_number() ); ?>
			<a class="rss" href="<?php bloginfo( 'rss2_url' ); ?>">RSS</a>

			<span class="controls">
				<a href="#" id="togglecomments"> <?php _e( 'Toggle Comment Threads', 'pulse_press' ); ?></a> | <a href="#directions" id="directions-keyboard"><?php _e( 'Keyboard Shortcuts', 'pulse_press' ); ?></a>
			</span>
		</h2>
		<?php 
		/* Archives uncommented
		<div id="date-range">
			<form action="#">
			
			 
			<fieldset> 
			<label for="valueA">Start Date:</label> 
			<select name="valueA" id="range-start">
				<?php pulse_press_date_range(get_earliest_post_date(),get_last_post_date(),'2 weeks ago'); ?>
			</select> 
	
			<label for="valueB">End Date:</label> 
			<select name="valueB" id="range-end"> 
				<?php pulse_press_date_range(get_earliest_post_date(),'now','now'); ?>
			</select> 
		</fieldset> 
		</form> 
		</div>
		*/
		?>
		
		<ul id="postlist">
		<?php 
		
		if(isset($_GET['starred'])):
			$paged = pulse_press_get_page_number();	
			query_posts(array('post__in'=>pulse_press_get_user_starred_post_meta(), 'paged'=>$paged));
		endif; 
		
		if(isset($_GET['popular'])):
		$paged = pulse_press_get_page_number();
			query_posts('meta_key=updates_votes&orderby=meta_value&order=DESC&paged='.$paged);
		endif;
		?>
		
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