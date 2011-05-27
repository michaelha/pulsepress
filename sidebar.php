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
					<li><a href="?popular">Popular</a></li>
					<?php if(current_user_can( 'read' ) && get_option( 'pulse_press_show_fav' )): ?>
					<li><a href="?starred" id="starred">Starred </a></li>
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