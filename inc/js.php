<?php

add_action( 'init', array( 'PulsePressJS', 'init' ) );

class PulsePressJS {
	
	function init() {
		if ( !is_admin() ) {
			add_action( 'wp_print_scripts', array( 'PulsePressJS', 'enqueue_scripts' ) );
			add_action( 'wp_print_styles', array( 'PulsePressJS', 'enqueue_styles' ) );
		}
		add_action( 'wp_head', array( 'PulsePressJS', 'print_options' ));
	}

	function enqueue_styles() {
		if ( pulse_press_user_can_post() && is_home() && is_user_logged_in() && current_user_can( 'upload_files' ) && get_option("pulse_press_allow_fileupload") ):
		
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_script( 'thickbox' );
		endif;
	}

	function enqueue_scripts() {
		global $wp_locale;

		wp_enqueue_script( 'utils' );
		
		wp_enqueue_script( 'comment-reply' );

		if ( is_user_logged_in() ) {
			wp_enqueue_script( 'suggest' );
			wp_enqueue_script( 'jeditable', PulsePress_JS_URL . '/jquery.jeditable.js', array( 'jquery' )  );
			
			if(get_option( 'pulse_press_show_twitter' ) && is_home() && is_user_logged_in() ) {
				wp_enqueue_script( 'counter', PulsePress_JS_URL . '/counter.js', array( 'jquery' ) );
				
				if(get_option( 'pulse_press_bitly_user') && get_option( 'pulse_press_bitly_api')) {
					wp_enqueue_script( 'pp_shortner',PulsePress_JS_URL . '/shortner.js', array( 'jquery','pulse_pressjs','counter' ) );
					wp_localize_script( 'pp_shortner', 'pp_shortner', array(
	  				'user' => pulse_press_display_option( get_option( 'pulse_press_bitly_user'),'',false),
                	'api' => pulse_press_display_option( get_option( 'pulse_press_bitly_api'),'',false)
					));	
				}
			}
			//bust the cache here
			
			
		}
		if(is_home())
		wp_enqueue_script( 'pulse_pressjs', PulsePress_JS_URL . '/pulse_press.js', array( 'jquery', 'utils' ), filemtime(PulsePress_JS_PATH . '/pulse_press.js' ) );
		
		// Archives uncommented
		//wp_enqueue_script( 'jquery-ui-1.7.1.custom.min', PulsePress_JS_URL . '/jquery-ui-1.7.1.custom.min.js', array( 'jquery', 'utils' ), filemtime(PulsePress_JS_PATH . '/jquery-ui-1.7.1.custom.min.js' ) );
		// wp_enqueue_script( 'selectToUISlider.jQuery', PulsePress_JS_URL . '/selectToUISlider.jQuery.js', array( 'jquery', 'utils' ), filemtime(PulsePress_JS_PATH . '/selectToUISlider.jQuery.js' ) );
		
		wp_localize_script( 'pulse_pressjs', 'pulse_presstxt', array(
			'tags' => __( '<br />Tags:' , 'pulse_press' ),
		    'tagit' => __( 'Tag it', 'pulse_press' ),
			'citation'=> __( 'Citation', 'pulse_press' ),
			'title' => __( 'Post Title', 'pulse_press' ),
		    'goto_homepage' => __( 'Go to homepage', 'pulse_press' ),
		    // the number is calculated in the javascript in a complex way, so we can't use ngettext
		    'n_new_updates' => __( '%d new update(s)', 'pulse_press' ),
		    'n_new_comments' => __( '%d new comment(s)', 'pulse_press' ),
		    'jump_to_top' => __( 'Jump to top', 'pulse_press' ),
		    'not_posted_error' => __( 'An error has occurred, your post was not posted', 'pulse_press' ),
		    'update_posted' => __( 'Your update has been posted', 'pulse_press' ),
		    'loading' => __( 'Loading...', 'pulse_press' ),
		    'cancel' => __( 'Cancel', 'pulse_press' ),
		    'save' => __( 'Save', 'pulse_press' ),
		    'hide_threads' => __( 'Hide threads', 'pulse_press' ),
		    'show_threads' => __( 'Show threads', 'pulse_press' ),
			'unsaved_changes' => __( 'Your comments or posts will be lost if you continue.', 'pulse_press' ),
			'date_time_format' => __( '%1$s <em>on</em> %2$s', 'pulse_press' ),
			'date_format' => get_option( 'date_format' ),
			'time_format' => get_option( 'time_format' ),
			// if we don't convert the entities to characters, we can't get < and > inside
			'l10n_print_after' => 'try{convertEntities(pulse_presstxt);}catch(e){};',
			'author_avatar' => null,
			'anonymous_avatar' => get_template_directory_uri().'/i/anonymous.png',
		));
			
		wp_enqueue_script( 'scrollit', PulsePress_JS_URL .'/jquery.scrollTo-min.js', array( 'jquery' )  );

		wp_enqueue_script( 'wp-locale', PulsePress_JS_URL . '/wp-locale.js', array(), 12 );

		// the localization functinality can't handle objects, that's why
		// we are using poor man's hash maps here -- using prefixes of the variable names
		$wp_locale_txt = array();
		
		foreach( $wp_locale->month as $key => $month ) $wp_locale_txt["month_$key"] = $month;
		$i = 1;
		foreach( $wp_locale->month_abbrev as $key => $month ) $wp_locale_txt["monthabbrev_".sprintf( '%02d', $i++)] = $month;
		foreach( $wp_locale->weekday as $key => $day ) $wp_locale_txt["weekday_$key"] = $day;
		$i = 1;
		foreach( $wp_locale->weekday_abbrev as $key => $day ) $wp_locale_txt["weekdayabbrev_".sprintf( '%02d', $i++)] = $day;
		wp_localize_script( 'wp-locale', 'wp_locale_txt', $wp_locale_txt);
	}
	
	function print_options() {
		get_currentuserinfo();
		$page_options['nonce']= wp_create_nonce( 'ajaxnonce' );
		$page_options['pulse_press_updates'] = 1;
		$page_options['pulse_press_comments_updates'] = 1;
		$page_options['pulse_press_votes_updates'] = 1;
		$page_options['pulse_press_tagsuggest'] = 1;
		$page_options['pulse_press_inlineedit'] = 1;
		$page_options['pulse_press_comments_inlineedit'] = 1;
		$page_options['is_single'] = (int)is_single();
		$page_options['is_page'] = (int)is_page();
		$page_options['is_front_page'] = (int)is_front_page();
		$page_options['is_first_front_page'] = (int)(is_front_page() && !is_paged() );
		$page_options['is_user_logged_in'] = (int)is_user_logged_in();
		$page_options['login_url'] = wp_login_url( ( ( !empty($_SERVER['HTTPS'] ) && strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
?>
	<script type="text/javascript" charset="<?php bloginfo( 'charset' ); ?>">
		// <![CDATA[
		// PulsePress Configuration
		// TODO: add these int the localize block
		<?php
		function pulse_press_url($url) {
				
				if ( false !== strpos($url, 'wp-admin/' ) )
					$url = admin_url( str_replace( '/wp-admin/', '', $url) );
				else
					$url = site_url($url);
				// this makes sure that https is being used when needed
				$url = str_replace('http://',"//",$url);
				$url = str_replace('https://',"//",$url);
				
			
			return $url;
		}
		
		if(FORCE_SSL_ADMIN): ?>
			var ajaxUrl = "<?php echo esc_js( site_url() . '/?pulse_pressajax=true' ); ?>";
		<?php
		else: ?>
			var ajaxUrl = "<?php echo esc_js( pulse_press_url( '/wp-admin/admin-ajax.php?pulse_pressajax=true' ) ); ?>";
		<?php
		endif;?>
		var updateRate = "15000"; // 30 seconds
		var nonce = "<?php echo esc_js( $page_options['nonce'] ); ?>";
		var login_url = "<?php echo $page_options['login_url'] ?>";
		var templateDir  = "<?php esc_js( get_template_directory_uri() ); ?>";
		var isFirstFrontPage = <?php echo $page_options['is_first_front_page'] ?>;
		var isFrontPage = <?php echo $page_options['is_front_page'] ?>;
		var isSingle = <?php echo $page_options['is_single'] ?>;
		var isPage = <?php echo $page_options['is_page'] ?>;
		var isUserLoggedIn = <?php echo $page_options['is_user_logged_in'] ?>;
		var prologueTagsuggest = <?php echo $page_options['pulse_press_tagsuggest'] ?>;
		var prologuePostsUpdates = <?php echo $page_options['pulse_press_updates'] ?>;
		var prologueCommentsUpdates = <?php echo $page_options['pulse_press_comments_updates']; ?>;
		var prologueVotesUpdates = <?php echo $page_options['pulse_press_votes_updates']; ?>;
		var getPostsUpdate = 0;
		var getCommentsUpdate = 0;
		var getVotesUpdate = 0;
		var inlineEditPosts =  <?php echo $page_options['pulse_press_inlineedit'] ?>;
		var inlineEditComments =  <?php echo $page_options['pulse_press_comments_inlineedit'] ?>;
		var wpUrl = "<?php echo esc_js( site_url() ); ?>";
		var rssUrl = "<?php esc_js( get_bloginfo( 'rss_url' ) ); ?>";
		var pageLoadTime = "<?php echo gmdate( 'Y-m-d H:i:s' ); ?>";
		var latestPermalink = "<?php echo esc_js( pulse_press_latest_post_permalink() ); ?>";
		var original_title = document.title;
		var commentsOnPost = new Array;
		var postsOnPage = new Array;
		var postsOnPageQS = '';
		var currPost = -1;
		var currComment = -1;
		var commentLoop = false;
		var lcwidget = false;
		var hidecomments = false;
		var commentsLists = '';
		var newUnseenUpdates = 0;
		
		// thickbox errors 
		if ( typeof tb_pathToImage != 'string' ) {
			var tb_pathToImage = "/wp-includes/js/thickbox/loadingAnimation.gif";
		}
		if ( typeof tb_closeImage != 'string' ) {
		var tb_closeImage = "/wp-includes/js/thickbox/tb-close.png";
		} 
		 // ]]>
		</script>
<?php		
	}
}

function pulse_press_toggle_threads() {
	$hide_threads = get_option( 'pulse_press_hide_threads' ); ?>

	<script type="text/javascript">
		jQuery(document).ready( function() {
			function hideComments() {
				jQuery('.commentlist').hide();
				jQuery('.discussion').show();
			}
			function showComments() {
				jQuery('.commentlist').show();
				jQuery('.discussion').hide();
			}
			<?php if ( (int)$hide_threads && !is_single() && !is_page() ) : ?>
				hideComments();
			<?php endif; ?>
			
			jQuery("#togglecomments").click( function(){
				if (jQuery('.commentlist').css('display') == 'none') {
					showComments();
				} else {
					hideComments();
				}
				return false;
			});
			
			jQuery(".show_comments").click(function(){
				var commentList = jQuery(this).closest('.post').find('.commentlist');
				if (commentList.css('display') == 'none') {
					commentList.show();
				} else {
					commentList.hide();
				}
				return false;
			});
		});
	</script><?php
}
add_action( 'wp_footer', 'pulse_press_toggle_threads' );
