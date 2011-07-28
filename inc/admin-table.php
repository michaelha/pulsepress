<?php


add_filter( 'manage_posts_columns', 'pulse_press_modify_post_table' );
add_filter( 'manage_posts_custom_column', 'pulse_press_modify_post_table_row', 10, 2 );
function pulse_press_modify_post_table( $column ) {
		
		$date = array_pop($column);
    	$column['pulse_press_votes'] = ( get_option( 'pulse_press_vote_text' ) == ''  ? __("Votes",'pulse_press') : esc_html( get_option( 'pulse_press_vote_text' ) ) );
  		$column['date'] = $date;
    	return $column;
	}
	
function pulse_press_modify_post_table_row( $column_name, $post_id ) {
 
    $custom_fields = get_post_custom( $post_id );
 
    switch ($column_name) {
        case 'pulse_press_votes' :
            echo "total: ".$custom_fields['updates_votes'][0];
            break;
 
        default:
    }
}
 
