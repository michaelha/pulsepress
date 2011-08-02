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

	if($query)
		query_posts( $query ."&meta_key=updates_votes&orderby=meta_value&order=DESC");
	else
		query_posts( 'posts_per_page='.$num.'&meta_key=updates_votes&orderby=meta_value&order=DESC' );
		
	$html .= '<ul class="pp_leaderboard">';
	while ( have_posts() ) : the_post();
		
		$html .= '<li><span class="pp_total_votes">'.pulse_press_sum_votes(get_the_ID()).'</span> ';
		$html .= '<a class="pp_permalink" href="'.get_permalink().'" title="'.esc_attr(get_the_title()).'" >'.get_the_title().'</a>';
		$html .= ' <span class="pp_authoer">by <a href="'.get_author_posts_url(get_the_author_id()).'">'.get_the_author().'</a></span>';
		$html .= '</li>';
	endwhile;
	$html .= "</ul>".$after;
	
	// Reset Query
	wp_reset_query();
	return $html;

}