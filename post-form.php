<?php
/**
 * @package WordPress
 * @subpackage PulsePress
 */
?>


<?php
// set default post type
$post_type = pulse_press_get_posting_type();
?>
<div id="postbox">
	
		<div class="avatar">
			<?php pulse_press_user_avatar( 'size=48' ); ?>
		</div>

		<div class="inputarea">
			<form id="new_post" name="new_post" method="post" action="<?php echo site_url(); ?>/">
				<?php if ( 'status' == pulse_press_get_posting_type() || '' == pulse_press_get_posting_type() ) : ?>
				<label for="posttext">
					<?php pulse_press_user_prompt(); ?>
				</label>
				<?php endif; ?>

				<textarea class="expand70-200" name="posttext" id="posttext" tabindex="1" rows="4" cols="60"></textarea>
				
				<label class="post-error" for="posttext" id="posttext_error"></label>
				
				<div class="postrow">
					<?php if(!get_option('pulse_press_show_tagging')): ?>
					<input id="tags" name="tags" type="text" tabindex="2" autocomplete="off"
						value="<?php esc_attr_e( 'Tag it', 'pulse_press' ); ?>"
						onfocus="this.value=(this.value=='<?php echo esc_js( __( 'Tag it', 'pulse_press' ) ); ?>') ? '' : this.value;"
						onblur="this.value=(this.value=='') ? '<?php echo esc_js( __( 'Tag it', 'pulse_press' ) ); ?>' : this.value;" /> 
					<?php endif; ?>
						<?php 
						// anonomouse is commneted out for now
						//<label id="anonymous"><input type="checkbox" checked="checked" value="1" /> anonymous</label> 
						
						?>
						
					<input id="submit" type="submit" tabindex="3" value="<?php esc_attr_e( 'Post it', 'pulse_press' ); ?>" />
					<span id="post-count">count</span>
				</div>
				<!-- <input type="hidden" name="post_cat" id="post_cat" value="<?php echo esc_attr( $post_type ); ?>" /> -->
				<span class="progress" id="ajaxActivity">
					<img src="<?php echo str_replace( WP_CONTENT_DIR, content_url(), locate_template( array( 'i/indicator.gif' ) ) ); ?>"
						alt="<?php esc_attr_e( 'Loading...', 'pulse_press' ); ?>" title="<?php esc_attr_e( 'Loading...', 'pulse_press' ); ?>"/>
				</span>
				<input type="hidden" name="action" value="post" />
				<?php wp_nonce_field( 'new-post' ); ?>
			</form>

		</div>

		<div class="clear"></div>

</div> <!-- // postbox -->