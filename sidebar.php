<?php
/**
 * @package WordPress
 * @subpackage PulsePress
 */
?>
<?php if ( !pulse_press_get_hide_sidebar() ) : ?>
	<div id="sidebar">
		<ul>
			<li>
				<ul>
					<?php if(get_option( 'pulse_press_show_voting' )): ?>
					<li><a href="?popular"><?php echo ( !get_option( 'pulse_press_popular_text' ) || get_option( 'pulse_press_popular_text' ) == __("Popular") ) ? __("Popular") : esc_html( get_option( 'pulse_press_popular_text' ) ); ?></a></li>
					<?php 
					endif;
					if(current_user_can( 'read' ) && get_option( 'pulse_press_show_fav' )): ?>
					<li><a href="<?php echo home_url();?>/?starred" id="starred"><?php echo (! get_option( 'pulse_press_star_text' ) || get_option( 'pulse_press_star_text' ) == __("My Starred") ) ? __("My Starred") : esc_html( get_option( 'pulse_press_star_text' ) ); ?></a></li>
					<?php endif; ?>
				</ul>
			</li>
		</ul>
	
		<ul>
			<?php 
			if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar() ) {
				the_widget( 'PulsePress_Recent_Comments', array(), array( 'before_widget' => '<li> ', 'after_widget' => '</li>', 'before_title' =>'<h2>', 'after_title' => '</h2>' ) );
				the_widget( 'PulsePress_Recent_Tags', array(), array( 'before_widget' => '<li> ', 'after_widget' => '</li>', 'before_title' =>'<h2>', 'after_title' => '</h2>' ) );
			}
			?>
		</ul>
	
		<div class="clear"></div>
	
	</div> <!-- // sidebar -->
<?php endif; ?>