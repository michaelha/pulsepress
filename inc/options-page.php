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
				
		$options = pulse_press_options();
		
		
		$update_options = false;
		if ( isset( $_POST[ 'action' ] ) && esc_attr( $_POST[ 'action' ] ) == 'update' )
			$update_options = true;
		
	
		foreach( $options as $option ):
		
			register_setting( 'pulse_pressops', 'pulse_press_'.$option );
			$set_option[$option] = get_option( 'pulse_press_'.$option );
			
			if($update_options):
			
				$set_option[$option] = ( isset($_POST[ 'pulse_press_'.$option ]) ? $_POST[ 'pulse_press_'.$option ] : 0 );
				update_option( 'pulse_press_'.$option, $set_option[$option] );
				
			endif;
			
		endforeach;
		
		if($update_options):
			?>
			<div class="updated"><p><strong><?php _e( 'Options saved.', 'pulse_press' ); ?></strong></p></div>
			<?php
		endif;
		?>
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

						<input id="pulse_press_allow_users_publish" type="checkbox" name="pulse_press_allow_users_publish" <?php  checked($set_option['allow_users_publish']); ?> value="1" />

						<label for="pulse_press_allow_users_publish"><?php _e( 'Allow any registered member to post', 'pulse_press' ); ?></label>

						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Hide Threads:', 'pulse_press' ); ?></th>
						<td>

						<input id="pulse_press_hide_threads" type="checkbox" name="pulse_press_hide_threads"  value="1" <?php  checked($set_option['hide_threads']); ?> />
						<label for="pulse_press_hide_threads"><?php _e( 'Hide comment threads by default', 'pulse_press' ); ?></label>

						</td>
					</tr>
					
					<tr>
						<th><?php _e( 'Disable Taging:', 'pulse_press' )?></th>
						<td>
							<input id="pulse_press_show_tagging" type="checkbox" name="pulse_press_show_tagging" <?php  checked($set_option['show_tagging']); ?> value="1" />
							<label for="pulse_press_show_tagging"><?php _e( 'Removes Tagging Interface', 'pulse_press' ); ?></label>
							
						</td>
					</tr>
					
					<tr>
						<th><?php _e( 'Twitter Style:', 'pulse_press' )?></th>
						<td>
							<input id="pulse_press_show_twitter" type="checkbox" name="pulse_press_show_twitter" <?php  checked($set_option['show_twitter']); ?> value="1" />
							<label for="pulse_press_show_twitter"><?php _e( 'Twitter Style Interface', 'pulse_press' ); ?></label>
							<ul>
								<li><?php _e( 'Post will be limited to 140 Characters', 'pulse_press' ); ?></li>
							</ul>
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
					
					<tr valign="top">
						<th scope="row"><?php _e( 'Sidebar display:', 'pulse_press' ); ?></th>
						<td>
							<input id="pulse_press_hide_sidebar" type="checkbox" name="pulse_press_hide_sidebar" <?php  checked($set_option['hide_sidebar']); ?> value="1" />
							<label for="pulse_press_hide_sidebar"><?php _e( 'Hide the Sidebar', 'pulse_press' ); ?></label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( 'Post prompt:', 'pulse_press' ); ?></th>
						<td>
							<input id="pulse_press_prompt_text" type="input" name="pulse_press_prompt_text" value="<?php echo ($set_option['prompt_text'] == __("Have your say!") ) ? __("Have your say!") : esc_attr( $set_option['prompt_text'] ); ?>" />
				 			(<?php _e( 'if empty, defaults to <strong>Have your say!</strong>', 'pulse_press' ); ?>)
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
function pulse_press_options() {
	return array(
			'allow_users_publish',
			'hide_threads',
			'show_titles',
			'show_tagging',
			'show_twitter',
			'hide_sidebar',
			'prompt_text'
		);

}
add_action( 'wp_before_admin_bar_render', 'pulse_press_admin_bar_render' );
/**
 * pulse_press_admin_bar_render function.
 * add the Theme Options into the admin bar
 * @access public
 * @return void
 */
function pulse_press_admin_bar_render() {
		global $wp_admin_bar;
		// we can remove a menu item, like the COMMENTS link
	    // just by knowing the right $id
		
		// we can add a submenu item too
		$wp_admin_bar->add_menu( array(
        	'parent' => 'appearance',
        	'id' => 'clf_theme',
        	'title' => __('Theme Options'),
        	'href' => admin_url( 'themes.php?page=pulse_press-options-page')
    	) );
}