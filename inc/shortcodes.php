<?php 
# the list of shortcodes 
add_shortcode("leaderboard","pulse_press_leaderboard");

function pulse_press_leaderboard( $atts ) {
	extract( shortcode_atts( array(
		'num' 	=> get_option('posts_per_page'),
		'query' =>false,
		'html'	=>'',
		'before'=>'',
		'after' =>''
	), $atts ) );
	
	$html = $before.$html;
	/*
	if($query)
		query_posts( $query ."&meta_key=updates_votes&orderby=meta_value&order=DESC");
	else
	*/
		query_posts( 'posts_per_page='.$num.'&meta_key=updates_votes&orderby=meta_value&order=DESC' );
		
	$html .= "<ul>";
	while ( have_posts() ) : the_post();
		
		$html .= '<li><span>'.pulse_press_total_votes(get_the_ID()).'</span>';
		$html .= '<a href="'.get_permalink().'" title="'.esc_attr(get_the_title()).'" >'.get_the_title().'</a>';
		$html .= '<span>'.get_the_author().'</span>';
		$html .= '</li>';
	endwhile;
	$html .= "</ul>".$after;
	
	// Reset Query
	wp_reset_query();
	return $html;

}