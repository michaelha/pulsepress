<?php
/**
 * @package WordPress
 * @subpackage PulsePress
 */
 get_sidebar(); ?>
	<div class="clear"></div>
	
</div> <!-- // wrapper -->

<div id="footer">
	<p>
		
	    <?php printf( __( 'PulsePress developed by <a href="http://ctlt.ubc.ca" title="Center for Teaching, Learning and Technology - University of British Columbia">CTLT</a> inspired by <a href="http://p2theme.com/" title="Go to P2">P2</a>', 'pulse_press' ) ); ?> -  <?php echo prologue_poweredby_link(); ?>
	</p>
</div>

<div id="notify"></div>

<div id="help">
	<dl class="directions">
		<dt>c</dt><dd><?php _e( 'compose new post', 'pulse_press' ); ?></dd>
		<dt>j</dt><dd><?php _e( 'next post/next comment', 'pulse_press' ); ?></dd>
		<dt>k</dt> <dd><?php _e( 'previous post/previous comment', 'pulse_press' ); ?></dd>
		<dt>r</dt> <dd><?php _e( 'reply', 'pulse_press' ); ?></dd>
		<?php if ( current_user_can( 'edit_post', get_the_id() ) ) : ?>
						<dt>e</dt> <dd><?php _e( 'edit', 'pulse_press' ); ?></dd>
		<?php endif; ?>
		<dt>o</dt> <dd><?php _e( 'show/hide comments', 'pulse_press' ); ?></dd>
		<dt>t</dt> <dd><?php _e( 'go to top', 'pulse_press' ); ?></dd>
		<dt>l</dt> <dd><?php _e( 'go to login', 'pulse_press' ); ?></dd>
		<dt>h</dt> <dd><?php _e( 'show/hide help', 'pulse_press' ); ?></dd>
		<dt>esc</dt> <dd><?php _e( 'cancel', 'pulse_press' ); ?></dd>
	</dl>
</div>
</div>
<?php wp_footer(); ?>

</body>
</html>