<?php

add_action( 'admin_menu', array( 'PulsePressOptions', 'init' ) );

class PulsePressOptions {

	function init() {
		global $plugin_page;

		add_theme_page( __( 'Theme Options', 'pulse_press' ), __( 'Theme Options', 'pulse_press' ), 'edit_theme_options', 'pulse_press-options-page', array( 'PulsePressOptions', 'page' ) );

		if ( 'pulse_press-options-page' == $plugin_page ) {
			wp_enqueue_script( 'farbtastic' );
			wp_enqueue_style( 'farbtastic' );
			wp_enqueue_script( 'colorpicker' );
			wp_enqueue_style( 'colorpicker' );
		}
	}

	function page() {

		register_setting( 'pulse_pressops', 'prologue_show_titles' );
		register_setting( 'pulse_pressops', 'pulse_press_allow_users_publish' );
		register_setting( 'pulse_pressops', 'pulse_press_prompt_text' );
		register_setting( 'pulse_pressops', 'pulse_press_hide_sidebar' );
		register_setting( 'pulse_pressops', 'pulse_press_background_color' );
		register_setting( 'pulse_pressops', 'pulse_press_background_image' );
		register_setting( 'pulse_pressops', 'pulse_press_hide_threads' );

		$prologue_show_titles_val    = get_option( 'prologue_show_titles' );
		$pulse_press_allow_users_publish_val  = get_option( 'pulse_press_allow_users_publish' );
		$pulse_press_prompt_text_val          = get_option( 'pulse_press_prompt_text' );
		$pulse_press_hide_sidebar           	 = get_option( 'pulse_press_hide_sidebar' );
		$pulse_press_background_color      	 = get_option( 'pulse_press_background_color' );
		$pulse_press_background_image      	 = get_option( 'pulse_press_background_image' );
		$pulse_press_hide_threads    	  	 = get_option( 'pulse_press_hide_threads' );

		if ( isset( $_POST[ 'action' ] ) && esc_attr( $_POST[ 'action' ] ) == 'update' ) {

			if ( isset( $_POST[ 'prologue_show_titles' ] ) )
				$prologue_show_titles_val = intval( $_POST[ 'prologue_show_titles' ] );
			else
				$prologue_show_titles_val = 0;

			if ( isset( $_POST[ 'pulse_press_allow_users_publish' ] ) )
				$pulse_press_allow_users_publish_val = intval( $_POST[ 'pulse_press_allow_users_publish' ] );
			else
				$pulse_press_allow_users_publish_val = 0;

			if ( isset( $_POST[ 'pulse_press_background_color_hex' ] ) )
				$pulse_press_background_color = $_POST[ 'pulse_press_background_color_hex' ];

			if ( esc_attr( $_POST[ 'pulse_press_prompt_text' ] ) != __( "Whatcha' up to?" ) )
				$pulse_press_prompt_text_val = esc_attr( $_POST[ 'pulse_press_prompt_text' ] );

			if ( isset( $_POST[ 'pulse_press_hide_sidebar' ] ) )
				$pulse_press_hide_sidebar = intval( $_POST[ 'pulse_press_hide_sidebar' ] );
			else
				$pulse_press_hide_sidebar = false;

			if ( isset( $_POST[ 'pulse_press_hide_threads' ] ) )
				$pulse_press_hide_threads = $_POST[ 'pulse_press_hide_threads' ];
			else
				$pulse_press_hide_threads = false;

			if ( isset( $_POST['pulse_press_background_image'] ) )
				$pulse_press_background_image = $_POST[ 'pulse_press_background_image' ];
			else
				$pulse_press_background_image = 'none';

			update_option( 'prologue_show_titles', $prologue_show_titles_val );
			update_option( 'pulse_press_allow_users_publish', $pulse_press_allow_users_publish_val );
			update_option( 'pulse_press_prompt_text', $pulse_press_prompt_text_val );
			update_option( 'pulse_press_hide_sidebar', $pulse_press_hide_sidebar );
			update_option( 'pulse_press_background_color', $pulse_press_background_color );
			update_option( 'pulse_press_background_image', $pulse_press_background_image );
			update_option( 'pulse_press_hide_threads', $pulse_press_hide_threads );

		?>
			<div class="updated"><p><strong><?php _e( 'Options saved.', 'pulse_press' ); ?></strong></p></div>
		<?php

			} ?>

		<div class="wrap">
	    <?php echo "<h2>" . __( 'PulsePress Options', 'pulse_press' ) . "</h2>"; ?>

		<form enctype="multipart/form-data" name="form1" method="post" action="<?php echo esc_attr( str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ) ); ?>">

			<h3 style="font-family: georgia, times, serif; font-weight: normal; border-bottom: 1px solid #ddd; padding-bottom: 5px">
				<?php _e( 'Functionality Options', 'pulse_press' ); ?>
			</h3>

			<?php settings_fields( 'pulse_pressops' ); ?>

			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><?php _e( 'Posting Access:', 'pulse_press' ); ?></th>
						<td>

						<input id="pulse_press_allow_users_publish" type="checkbox" name="pulse_press_allow_users_publish" <?php if ( $pulse_press_allow_users_publish_val == 1 ) echo 'checked="checked"'; ?> value="1" />

						<?php if ( defined( 'IS_WPCOM' ) && IS_WPCOM )
								$msg = 'Allow any WordPress.com member to post';
							  else
							  	$msg = 'Allow any registered member to post';
						 ?>

						<label for="pulse_press_allow_users_publish"><?php _e( $msg, 'pulse_press' ); ?></label>

						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Hide Threads:', 'pulse_press' ); ?></th>
						<td>

						<input id="pulse_press_hide_threads" type="checkbox" name="pulse_press_hide_threads" <?php if ( $pulse_press_hide_threads == 1 ) echo 'checked="checked"'; ?> value="1" />
						<label for="pulse_press_hide_threads"><?php _e( 'Hide comment threads by default', 'pulse_press' ); ?></label>

						</td>
					</tr>
				</tbody>
			</table>

			<p>&nbsp;</p>

			<h3 style="font-family: georgia, times, serif; font-weight: normal; border-bottom: 1px solid #ddd; padding-bottom: 5px">
				<?php _e( 'Design Options', 'pulse_press' ); ?>
			</h3>
			
			<table class="form-table">
				<tbody>
					<!-- 
					<tr valign="top">
						<th scope="row"><?php _e( 'Custom Background Color:', 'pulse_press' ); ?></th>
						<td>
							<input id="pickcolor" type="button" class="button" name="pickcolor" value="<?php _e( 'Pick a Color', 'pulse_press' ); ?> "/>
							<input name="pulse_press_background_color_hex" id="pulse_press_background_color_hex" type="text" value="<?php esc_attr_e( $pulse_press_background_color ); ?>" />
							<div id="colorPickerDiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;"> </div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Background Image:', 'pulse_press' ); ?></th>
						<td>
							<input type="radio" id="bi_none" name="pulse_press_background_image" value="none"<?php if ( 'none' == $pulse_press_background_image ) : ?> checked="checked" <?php endif; ?>/> <label for="bi_none"><?php _e( 'None', 'pulse_press' ); ?></label><br />
							<input type="radio" id="bi_bubbles" name="pulse_press_background_image" value="bubbles"<?php if ( 'bubbles' == $pulse_press_background_image ) : ?> checked="checked" <?php endif; ?>/> <label for="bi_bubbles"><?php _e( 'Bubbles', 'pulse_press' ); ?></label><br />
							<input type="radio" id="bi_polka" name="pulse_press_background_image" value="dots"<?php if ( 'dots' == $pulse_press_background_image ) : ?> checked="checked" <?php endif; ?>/> <label for="bi_polka"><?php _e( 'Polka Dots', 'pulse_press' ); ?></label><br />
							<input type="radio" id="bi_squares" name="pulse_press_background_image" value="squares"<?php if ( 'squares' == $pulse_press_background_image ) : ?> checked="checked" <?php endif; ?>/> <label for="bi_squares"><?php _e( 'Squares', 'pulse_press' ); ?></label><br />
							<input type="radio" id="bi_plaid" name="pulse_press_background_image" value="plaid"<?php if ( 'plaid' == $pulse_press_background_image ) : ?> checked="checked" <?php endif; ?>/> <label for="bi_plaid"><?php _e( 'Plaid', 'pulse_press' ); ?></label><br />
							<input type="radio" id="bi_stripes" name="pulse_press_background_image" value="stripes"<?php if ( 'stripes' == $pulse_press_background_image ) : ?> checked="checked" <?php endif; ?>/> <label for="bi_stripes"><?php _e( 'Stripes', 'pulse_press' ); ?></label>
						</td>
					</tr>
					-->
					<tr valign="top">
						<th scope="row"><?php _e( 'Sidebar display:', 'pulse_press' ); ?></th>
						<td>
							<input id="pulse_press_hide_sidebar" type="checkbox" name="pulse_press_hide_sidebar" <?php if ( $pulse_press_hide_sidebar ) echo 'checked="checked"'; ?> value="1" />
							<label for="pulse_press_hide_sidebar"><?php _e( 'Hide the Sidebar', 'pulse_press' ); ?></label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Post prompt:', 'pulse_press' ); ?></th>
						<td>
							<input id="pulse_press_prompt_text" type="input" name="pulse_press_prompt_text" value="<?php echo ($pulse_press_prompt_text_val == __("Have your say!") ) ? __("Have your say!") : stripslashes( $pulse_press_prompt_text_val ); ?>" />
				 			(<?php _e( 'if empty, defaults to <strong>Have your say!</strong>', 'pulse_press' ); ?>)
						</td>
					</tr>
					
					<tr>
						<th><?php _e( 'Post Titles:', 'pulse_press' )?></th>
						<td>
							<input id="prologue_show_titles" type="checkbox" name="prologue_show_titles" <?php if ( $prologue_show_titles_val != 0 ) echo 'checked="checked"'; ?> value="1" />
							<label for="prologue_show_titles"><?php _e( 'Display titles', 'pulse_press' ); ?></label>
						</td>
					</tr>
					
				</tbody>
			</table>

			<p>
			</p>

			<p class="submit">
				<input type="submit" name="Submit" value="<?php esc_attr_e( 'Update Options', 'pulse_press' ); ?>" />
			</p>

		</form>
		</div>

<script type="text/javascript">
	var farbtastic;

	function pickColor(color) {
		jQuery('#pulse_press_background_color_hex').val(color);
		farbtastic.setColor(color);
	}

	jQuery(document).ready(function() {
		jQuery('#pickcolor').click(function() {
			jQuery('#colorPickerDiv').show();
		});

		jQuery('#hidetext' ).click(function() {
			toggle_text();
		});

		farbtastic = jQuery.farbtastic( '#colorPickerDiv', function(color) { pickColor(color); });
	});

	jQuery(document).mousedown(function(){
		// Make the picker disappear, since we're using it in an independant div
		hide_picker();
	});

	function colorDefault() {
		pickColor( '#<?php echo HEADER_TEXTCOLOR; ?>' );
	}

	function hide_picker(what) {
		var update = false;
		jQuery('#colorPickerDiv').each(function(){
			var id = jQuery(this).attr( 'id' );
			if (id == what) {
				return;
			}
			var display = jQuery(this).css( 'display' );
			if (display == 'block' ) {
				jQuery(this).fadeOut(2);
			}
		});
	}

	function toggle_text(force) {
		if (jQuery('#textcolor').val() == 'blank' ) {
			//Show text
			jQuery(buttons.toString()).show();
			jQuery('#textcolor').val( '<?php echo HEADER_TEXTCOLOR; ?>' );
			jQuery('#hidetext').val( '<?php _e( 'Hide Text' ); ?>' );
		}
		else {
			//Hide text
			jQuery(buttons.toString()).hide();
			jQuery('#textcolor').val( 'blank' );
			jQuery('#hidetext').val( '<?php _e( 'Show Text' ); ?>' );
		}
	}
</script>

<?php
	}
}