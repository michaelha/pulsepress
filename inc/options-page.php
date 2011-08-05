<?php

add_action( 'admin_menu', array( 'PulsePressOptions', 'init' ) );

class PulsePressOptions {

	function init() {
		// global $plugin_page;
		add_theme_page( __( 'Theme Options', 'pulse_press' ), __( 'Theme Options', 'pulse_press' ), 'edit_theme_options', 'pulse_press-options-page', array( 'PulsePressOptions', 'page' ) );
		
	}
	
		
	function page() {
				
		$options = pulse_press_options();
		

		$update_options = false;
		if ( isset( $_POST[ 'action' ] ) && esc_attr( $_POST[ 'action' ] ) == 'update' && wp_verify_nonce($_POST['_wpnonce'], 'pulse_pressops-options') )
			$update_options = true;
		
	
		foreach( $options as $option ):
		
			register_setting( 'pulse_pressops', 'pulse_press_'.$option);
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
						<th scope="row"><?php _e( 'Remove Frontend Posting:', 'pulse_press' ); ?></th>
						<td>
							<input id="pulse_press_remove_frontend_post" type="checkbox" name="pulse_press_remove_frontend_post" <?php  checked($set_option['remove_frontend_post']); ?> value="1" />
							<label for="pulse_press_remove_frontend_post"><?php _e( 'Remove the frontend posting form', 'pulse_press' ); ?></label>
						</td>
					</tr>
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
					<tr valign="top">
						<th scope="row"><?php _e( 'Enable Reply:', 'pulse_press' ); ?></th>
						<td>

						<input id="pulse_press_show_reply" type="checkbox" name="pulse_press_show_reply"  value="1" <?php  checked($set_option['show_reply']); ?> />
						<label for="pulse_press_show_reply"><?php _e( 'Allow members to post a reply', 'pulse_press' ); ?></label>
						<br />
						<span><em><?php  if(get_option('default_comment_status') == 'open') : ?>
						<?php _e( 'Currently you are allowing comments on new posts by default. Change it in the','pulse_press');?> <a href="<?php echo admin_url('options-discussion.php'); ?>"><?php _e( 'Discussion Settings','pulse_press');?></a>.
						<?php else : ?>
						<?php _e( 'Currently you are <strong>NOT</strong> allowing replys on new post by default. Change it in the','pulse_press');?>  <a href="<?php echo admin_url('options-discussion.php'); ?>"><?php _e( 'Discussion Settings','pulse_press');?></a>.
						<?php endif; ?></em></span>
						</td>
					</tr>
					
					<tr>
						<th><?php _e( 'Enable Anonymous Posting:', 'pulse_press' )?></th>
						<td>
							<input id="pulse_press_show_anonymous" type="checkbox" name="pulse_press_show_anonymous" <?php checked($set_option['show_anonymous']); ?> value="1" />
							<label for="pulse_press_show_anonymous"><?php _e( 'Enable Anonymous Interface', 'pulse_press' ); ?></label><br />
							<span><em><?php _e( 'Allows logged in users to posts anonymously, their post will appear as anonymouse on the front end but as an admin you will be able to see posted.', 'pulse_press' ); ?></em></span><br />

						</td>
					</tr>
					<tr>
						<th><?php _e( 'Enable Favouriting:', 'pulse_press' )?></th>
						<td>
							<input id="pulse_press_show_fav" type="checkbox" name="pulse_press_show_fav" <?php checked($set_option['show_fav']); ?> value="1" />
							<label for="pulse_press_show_fav"><?php _e( 'Enable Favouriting Interface', 'pulse_press' ); ?></label><br />
							<span><em><?php _e( 'Allows logged in users to STAR ( favorite ) posts, and view them.', 'pulse_press' ); ?></em></span><br />

						</td>
					</tr>
					
					<tr>
						<th><?php _e( 'Enable Voting:', 'pulse_press' )?></th>
						<td>
							<input id="pulse_press_show_voting" type="checkbox" name="pulse_press_show_voting" <?php  checked($set_option['show_voting']); ?> value="1" />
							<label for="pulse_press_show_voting"><?php _e( 'Enable Voting Interface', 'pulse_press' ); ?></label>
							<br />
							<div>
							<input id="pulse_press_voting_type_1" type="radio" name="pulse_press_voting_type" <?php  checked($set_option['voting_type'],"one"); ?> value="one" />
							<label for="pulse_press_voting_type_1"><?php _e( 'Vote Up - User are only able to vote posts up', 'pulse_press' ); ?></label><br />
							<input id="pulse_press_voting_type_2" type="radio" name="pulse_press_voting_type" <?php  checked($set_option['voting_type'],"two"); ?> value="two" />
							<label for="pulse_press_voting_type_2"><?php _e( 'Two Way Voting - User can choose to vote items both up or down', 'pulse_press' ); ?></label>
							
							</div>
							<br />
							<input id="pulse_press_show_unpopular" type="checkbox" name="pulse_press_show_unpopular" <?php  checked($set_option['show_unpopular']); ?> value="1" />
							<label for="pulse_press_show_unpopular"><?php _e( 'Display Unpopular Interface', 'pulse_press' ); ?></label>
							<br />
							<input id="pulse_press_show_most_voted_on" type="checkbox" name="pulse_press_show_most_voted_on" <?php  checked($set_option['show_most_voted_on']); ?> value="1" />
							<label for="pulse_press_show_most_voted_on"><?php _e( 'Display Most voted-on Interface', 'pulse_press' ); ?></label>
							<br />
							<input id="pulse_press_show_vote_breakdown" type="checkbox" name="pulse_press_show_vote_breakdown" <?php  checked($set_option['show_vote_breakdown']); ?> value="1" />
							<label for="pulse_press_show_vote_breakdown"><?php _e( 'Display votes breakdown', 'pulse_press' ); ?></label>
							

						</td>
					</tr>
					
					<tr>
						<th><?php _e( 'Enable Tagging:', 'pulse_press' )?></th>
						<td>
							<input id="pulse_press_show_tagging" type="checkbox" name="pulse_press_show_tagging" <?php  checked($set_option['show_tagging']); ?> value="1" />
							<label for="pulse_press_show_tagging"><?php _e( 'Add Tagging Interface', 'pulse_press' ); ?></label>
						</td>
					</tr>
					
					<tr>
						<th><?php _e( 'Enable File Uploads:', 'pulse_press' )?></th>
						<td>
							<input id="pulse_press_allow_fileupload" type="checkbox" name="pulse_press_allow_fileupload" <?php  checked($set_option['allow_fileupload']); ?> value="1" />
							<label for="pulse_press_allow_fileupload"><?php _e( 'Add File Upload Interface', 'pulse_press' ); ?></label>
						</td>
					</tr>
					
					<tr>
						<th><?php _e( 'Twitter Style:', 'pulse_press' )?></th>
						<td>
							<input id="pulse_press_show_twitter" type="checkbox" name="pulse_press_show_twitter" <?php  checked($set_option['show_twitter']); ?> value="1" />
							<label for="pulse_press_show_twitter"><?php _e( 'Twitter Style Interface', 'pulse_press' ); ?></label><br />
							<span><em><?php _e( 'Post will be limited to 140 Characters', 'pulse_press' ); ?></em></span><br />
								<?php if(get_option('embed_autourls')): ?>
								<span><em><?php _e( 'Currently some url will be converted into embeddable content, disable Auto-embeds under <a href="'.admin_url('options-media.php').'">Media Settings</a> to disable that feature. ', 'pulse_press' ); ?></em></span><br />
								<?php else: ?>
								<span><em> <?php _e( 'Currently Auto-embeds are disabled. To enable them visit <a href="'.admin_url('options-media.php').'">Media Settings</a>. ', 'pulse_press' ); ?></em></span>
								<?php endif; ?>
								
							</ul>
							
						</td>
					</tr>
					
					<tr>
						<th><?php _e( 'URL Shortener<br /> Bitly API:', 'pulse_press' )?></th>
						<td>
							<input id="pulse_press_bitly_user" type="text" class="regular-text" name="pulse_press_bitly_user" value="<?php pulse_press_display_option($set_option['bitly_user'],''); ?>" />
							<label for="pulse_press_bitly_user"><?php _e( 'bitly Username', 'pulse_press' ); ?></label><br />
							<input id="pulse_press_bitly_api" type="text" class="regular-text" name="pulse_press_bitly_api" value="<?php pulse_press_display_option($set_option['bitly_api'],''); ?>" />
							<label for="pulse_press_bitly_api"><?php _e( 'bitly API Key', 'pulse_press' ); ?></label>
							<br /><span><em>To get your <a target="_blank" href="http://bit.ly">bit.ly</a> API key - <a target="_blank" href="http://bit.ly/a/sign_up">sign up</a> and view your <a target="_blank" href="http://bit.ly/a/your_api_key/">API KEY</a>
							 </<em></span>
							
							
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
							<input id="pulse_press_prompt_text" type="text" name="pulse_press_prompt_text" class="regular-text"  value="<?php pulse_press_display_option($set_option['prompt_text'],"What&rsquo;s happening?"); ?>" />
				 			(<?php _e( 'if empty, defaults to "What&rsquo;s happening?"', 'pulse_press' ); ?>)
						</td>
					</tr>
					
					
					<tr valign="top">
						<th scope="row"><?php _e( 'Vote text:', 'pulse_press' ); ?></th>
						<td>
							<input id="pulse_press_vote_text" type="text" name="pulse_press_vote_text" class="regular-text"  value="<?php pulse_press_display_option($set_option['vote_text'],"votes"); ?>" />
				 			(<?php _e( 'if empty, defaults to "votes"', 'pulse_press' ); ?>)
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e( 'Vote Up text:', 'pulse_press' ); ?></th>
						<td>
							<input id="pulse_press_vote_up_text" type="text" name="pulse_press_vote_up_text" class="regular-text"  value="<?php pulse_press_display_option($set_option['vote_up_text'],"up"); ?>" />
				 			(<?php _e( 'if empty, defaults to "up"', 'pulse_press' ); ?>)
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e( 'Vote down text:', 'pulse_press' ); ?></th>
						<td>
							<input id="pulse_press_vote_down_text" type="text" name="pulse_press_vote_down_text" class="regular-text"  value="<?php pulse_press_display_option($set_option['vote_down_text'],"down"); ?>" />
				 			(<?php _e( 'if empty, defaults to "down"', 'pulse_press' ); ?>)
						</td>
					</tr>
					
					
					
					<tr valign="top">
						<th scope="row"><?php _e( 'Popular text:', 'pulse_press' ); ?></th>
						<td>
							<input id="pulse_press_popular_text" type="text" name="pulse_press_popular_text" class="regular-text"  value="<?php pulse_press_display_option($set_option['popular_text'],"Popular"); ?>" />
				 			(<?php _e( 'if empty, defaults to "Popular"', 'pulse_press' ); ?>)
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e( 'Unpopular text:', 'pulse_press' ); ?></th>
						<td>
							<input id="pulse_press_unpopular_text" type="text" name="pulse_press_unpopular_text" class="regular-text"  value="<?php pulse_press_display_option($set_option['unpopular_text'],"Unpopular"); ?>" />
				 			(<?php _e( 'if empty, defaults to "Unpopular"', 'pulse_press' ); ?>)
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e( 'Most voted-on text:', 'pulse_press' ); ?></th>
						<td>
							<input id="pulse_press_most_voted_on_text" type="text" name="pulse_press_most_voted_on_text" class="regular-text"  value="<?php pulse_press_display_option($set_option['most_voted_on_text'],"Most voted-on"); ?>" />
				 			(<?php _e( 'if empty, defaults to "Most voted-on"', 'pulse_press' ); ?>)
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row"><?php _e( 'Started text:', 'pulse_press' ); ?></th>
						<td>
							<input id="pulse_press_star_text" type="text" name="pulse_press_star_text" class="regular-text"  value="<?php pulse_press_display_option($set_option['star_text'],"My Starred"); ?>" />
				 			(<?php _e( 'if empty, defaults to "My Starred"', 'pulse_press' ); ?>)
						</td>
					</tr>

				</tbody>
			</table>

			<p>
			</p>

			<p class="submit">
				<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'pulse_press' ); ?>" />
			</p>
			
		</form>
		</div>
<?php
	}
	
	
}

function pulse_press_options_help($contextual_help, $screen_id, $screen) {

	
	if ($screen_id == 'appearance_page_pulse_press-options-page') {

		$contextual_help .= '<a href="?page=pulse_press-options-page&update_custom_field_table=1">'.__('Update custom field to mach database','pulse_press').'</a><br />';
		$contextual_help .="db version: ". get_option("pulse_press_db_version");
	}
	return $contextual_help;
}

add_filter('contextual_help', 'pulse_press_options_help', 10, 3);
function pulse_press_options() {
	return array(
			'allow_users_publish',
			'hide_threads',
			'show_reply',
			'show_voting',
			'voting_type',
			'show_unpopular',
			'show_most_voted_on',
			'show_vote_breakdown',
			'show_anonymous',
			'show_fav',
			'show_tagging',
			'allow_fileupload',
			'show_twitter',
			'bitly_user',
			'bitly_api',
			'hide_sidebar',
			'prompt_text',
			'vote_text',
			'vote_up_text',
			'vote_down_text',
			'vote_style',
			'popular_text',
			'unpopular_text',
			'most_voted_on_text',
			'star_text',
			'remove_frontend_post'
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
        	'title' => __('Theme Options','pulse_press'),
        	'href' => admin_url( 'themes.php?page=pulse_press-options-page')
    	) );
}

function pulse_press_display_option($option,$default='')
{
	echo ( $option == "" ) ? __($default,'pulse_press') : esc_attr( stripslashes($option) );

}