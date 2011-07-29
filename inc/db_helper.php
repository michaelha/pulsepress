<?php 

function pulse_press_install() {
	
	global $wpdb;

	if(PulsePress_DB_VERSION > get_option("pulse_press_db_version") ):
		
		delete_option( 'pulse_press_db_version' );
		$pulse_press_db_table = PulsePress_DB_TABLE;
				
		$sql = "CREATE TABLE " . $pulse_press_db_table . " (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				post_id bigint(11) DEFAULT '0' NOT NULL,
				user_id tinytext NOT NULL,
				counter bigint(11) DEFAULT '1' NOT NULL,
				date TIMESTAMP NOT NULL,
				date_gmt DATETIME  DEFAULT '0000-00-00 00:00:00' NOT NULL,
				type VARCHAR(64) NOT NULL,
				UNIQUE KEY id (id)
				);";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		
		add_option("pulse_press_db_version", PulsePress_DB_VERSION);
		$date = pulse_press_get_gmt_time();
		add_option("pulse_press_votes_updated", $date,'','no');
		
		// create 
		$all_posts = get_posts('posts_per_page=-1&post_type=post&post_status=');
		foreach( $all_posts as $postinfo) {
			
			add_post_meta($postinfo->ID, 'updates_votes', 0, true);
			
		}
	endif;
}
if(is_admin())  // this runs when you active the theme 
	pulse_press_install();

if(isset($_GET['update_custom_field_table']))
	pulse_press_update_custom_field_from_table();

// delete table once you switch themes
add_action("switch_theme","pulse_press_delete_tables_and_options");
function pulse_press_delete_tables_and_options($name)
{
	global $wpdb;
	$options = pulse_press_options();
	foreach($options as $option):
	
	delete_option( 'pulse_press_'.$option );
	endforeach;
	delete_option( 'pulse_press_rewrites_flushed' );
	delete_option( 'pulse_press_db_version' );
	
	delete_option( 'pulse_press_show_titles' );
	delete_option( 'pulse_press_prompt_text' );
	delete_option( 'pulse_press_votes_updated' );

	$pulse_press_db_table = PulsePress_DB_TABLE;
   	$wpdb->query("DROP TABLE IF EXISTS $pulse_press_db_table");
	// delete the different option
	

}

/** 
 * API FOR working with the new table 
 *****************************************************/
// just add and delete rows 
/**
 * Votes 
 *************************************************************/
function pulse_press_vote($post_id) {
	// store the value in custom field 
	
	$votes = pulse_press_total_votes($post_id) + 1;
	// save the number of votes to better get popular votes 
	add_post_meta($post_id, 'updates_votes', $votes, true) or update_post_meta($post_id, 'updates_votes', $votes);
	// for knowing when to update this 
	$date = pulse_press_get_gmt_time();
	update_option('pulse_press_votes_updated',$date);
	return pulse_press_add_user_post_meta($post_id,'vote',1);
	
}

function pulse_press_vote_down($post_id) {
	// store the value in custom field 
	
	$votes = pulse_press_total_votes($post_id) - 1;
	// save the number of votes to better get popular votes 
	add_post_meta($post_id, 'updates_votes', $votes, true) or update_post_meta($post_id, 'updates_votes', $votes);
	// for knowing when to update this 
	$date = pulse_press_get_gmt_time();
	update_option('pulse_press_votes_updated',$date);
	return pulse_press_add_user_post_meta($post_id,'vote',-1);
	
}
function pulse_press_delete_vote($post_id) {
	// store the value in custom field 
	
	$votes = pulse_press_total_votes($post_id) - 1;
	// save the number of votes to better get popular votes 
	add_post_meta($post_id, 'updates_votes', $votes, true) or update_post_meta($post_id, 'updates_votes', $votes);
	
	// for knowing when to update this 
	$date = pulse_press_get_gmt_time();
	update_option('pulse_press_votes_updated',$date);
	
	return pulse_press_delete_user_post_meta($post_id,'vote');
}

function pulse_press_is_vote($post_id) {
	return pulse_press_get_user_post_meta_counter($post_id,'vote');
}

function pulse_press_total_votes($post_id) {
	$result = pulse_press_get_total_posts_meta($post_id,'vote');
	
	return $result[0]->count;
}

function pulse_press_get_sum_votes_by_user($user_id){
	return pulse_press_get_sum_meta_by_user('vote',$user_id);
}
function pulse_press_get_popular_vote() {
	return pulse_press_get_popular_posts_meta('vote');
}
function pulse_press_total_posts_votes($post_ids)
{
	// make sure you are dealing with numbers
	if(is_array($post_ids)):
		foreach($post_ids as $item):
			$post_id_array[] = intval($item);
		endforeach;
		$post_ids = implode(", ",$post_id_array);
	else:
		$post_ids = intval($post_ids);
	endif;
	
	
	return pulse_press_get_total_posts_meta($post_ids,'vote');
}
function pulse_press_get_updates_since_vote($date) {
	
	return pulse_press_get_updates_since_post_meta($date,'vote');
}

function pulse_press_get_total_votes_by_post($post_id) {
	
	return pulse_press_get_total_meta_by_post('vote',$post_id);
}

function pulse_press_get_total_votes_by_user($user_id) {
	
	return pulse_press_get_total_meta_by_user('vote',$user_id);
}
/**
 * Star 
 *************************************************************/

function pulse_press_add_star($post_id) {
	return pulse_press_add_user_post_meta($post_id,"star");
}

function pulse_press_delete_star($post_id) {
	return pulse_press_delete_user_post_meta($post_id,"star");
}

function pulse_press_is_star($post_id) {
	return pulse_press_get_user_post_meta($post_id,"star");
}
function pulse_press_get_user_starred_post_meta()
{
	return  pulse_press_get_all_user_post_meta("star");
}
/**
 * API HELPER 
 *************************************************************/
function pulse_press_get_user_post_meta($post_id,$type) {

	global $wpdb,$current_user;
	wp_get_current_user();
	
	return $wpdb->get_var($wpdb->prepare("SELECT id FROM ".PulsePress_DB_TABLE." WHERE post_id = %d AND user_id = %d AND type ='%s';", $post_id,$current_user->ID,$type ));
}


function pulse_press_get_user_post_meta_counter($post_id,$type) {

	global $wpdb,$current_user;
	wp_get_current_user();
	
	return $wpdb->get_var($wpdb->prepare("SELECT counter FROM ".PulsePress_DB_TABLE." WHERE post_id = %d AND user_id = %d AND type ='%s';", $post_id,$current_user->ID,$type ));
}

function pulse_press_get_user_from_meta_post_id($type,$post_id) {
	global $wpdb;
	
	return $wpdb->get_col($wpdb->prepare("SELECT user_id FROM ".PulsePress_DB_TABLE." WHERE post_id = %d AND type ='%s';",$post_id,$type ));
}

/* return the sum of the counter for */
function pulse_press_get_total_meta_by_post($type,$post_id) {
	global $wpdb;
	
	return $wpdb->get_var($wpdb->prepare("SELECT COUNT(*)  FROM ".PulsePress_DB_TABLE." WHERE post_id = %d AND type ='%s';",$post_id,$type ));
}




/* returns all the post_id that a user voted on or stared */
function pulse_press_get_all_user_post_meta($type) {

	global $wpdb,$current_user;
	wp_get_current_user();
	
	return $wpdb->get_col($wpdb->prepare("SELECT post_id FROM ".PulsePress_DB_TABLE." WHERE user_id = %d AND type ='%s';",$current_user->ID,$type ));
}

/* returns back the SUM of the votes a user made */ 
function pulse_press_get_sum_meta_by_user($type,$user_id) {
	
	global $wpdb;

	return $wpdb->get_var($wpdb->prepare("SELECT SUM(counter) FROM ".PulsePress_DB_TABLE." WHERE user_id = %s AND  type ='%s';", $user_id,$type ));

}
/* returns back the total number of votes a user made */
function pulse_press_get_total_meta_by_user($type,$user_id) {
	global $wpdb;
	
	return $wpdb->get_var($wpdb->prepare("SELECT COUNT(*)  FROM ".PulsePress_DB_TABLE." WHERE user_id = %d AND type ='%s';",$user_id,$type ));
}

function pulse_press_add_user_post_meta($post_id,$type,$count=1) {

	global $wpdb,$current_user;
	wp_get_current_user();
	$date = pulse_press_get_gmt_time();
	$data = array( 
			'post_id' 	=> $post_id, 
			'type' 		=> $type,
			'counter'	=> $count,
			'user_id'	=> $current_user->ID,
			'date_gmt'  => $date,
			 );
	$GLOBALS[ 'wp_log' ][ 'pulsepress' ][] = 'added user post meta';
	$result = $wpdb->insert( PulsePress_DB_TABLE,$data , array( '%d', '%s', '%d', '%s') );
	
	return $result;
	
	

}

function pulse_press_delete_user_post_meta($post_id,$type){
	global $wpdb,$current_user;
	wp_get_current_user();
	
	return $wpdb->query( $wpdb->prepare( "DELETE FROM ".PulsePress_DB_TABLE." WHERE post_id = %d AND user_id = %d AND type ='%s'", $post_id,$current_user->ID,$type) );
	
}
function pulse_press_get_popular_posts_meta($type) {

	global $wpdb,$current_user;
	wp_get_current_user();
	
	return $wpdb->get_results($wpdb->prepare("SELECT post_id, COUNT(post_id) as count FROM ".PulsePress_DB_TABLE." WHERE type ='%s' GROUP BY post_id ORDER BY count DESC;", $type ));
}
/* This gives you back all the items in the table together */
function pulse_press_get_sum_posts_meta($post_ids,$type) {
	global $wpdb;
	return $wpdb->get_results($wpdb->prepare("SELECT post_id, COUNT(*) as count FROM ".PulsePress_DB_TABLE." WHERE post_id IN (".$post_ids.") AND type ='%s' GROUP BY post_id;", $type));
	
}
/* this returns a list of summed up items */
function pulse_press_get_total_posts_meta($post_ids,$type) {
	global $wpdb;
	return $wpdb->get_results($wpdb->prepare("SELECT post_id, SUM(counter) as count FROM ".PulsePress_DB_TABLE." WHERE post_id IN (".$post_ids.") AND type ='%s' GROUP BY post_id;", $type));
}
function pulse_press_get_updates_since_post_meta($date,$type) {
	global $wpdb;
	return $wpdb->get_var($wpdb->prepare("SELECT date_gmt FROM ".PulsePress_DB_TABLE." WHERE date_gmt > %s  AND type ='%s' ORDER BY date_gmt;",$date, $type));

}




/* helper functions */
function pulse_press_get_gmt_time(){

	$default_timezone = date_default_timezone_get();
		date_default_timezone_set('GMT');
	$date = date("Y-m-d H:i:s");
	date_default_timezone_set($default_timezone);
	return $date;
}


// get the earliest post 
function get_earliest_post_date()
{
	global $wpdb;
	
	return $wpdb->get_var("SELECT post_date FROM $wpdb->posts WHERE `post_type` = 'post' AND `post_status` ='publish' ORDER BY `post_date` ASC LIMIT 0 , 1");
	
	
}
// get the last post 
function get_last_post_date()
{
	global $wpdb;
	return $wpdb->get_var("SELECT post_date FROM $wpdb->posts WHERE post_type = 'post' AND post_status ='publish' ORDER BY post_date DESC LIMIT 0 , 1");
	
}

