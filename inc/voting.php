<?php


function pulse_press_vote_on_post()
{	
	$votes = pulse_press_total_votes(get_the_ID());
		if(empty($votes))
			$votes = 0;
	
	?>
	<div class="vote" >
	<em><strong id="votes-<?php the_ID();?>"><?php echo $votes; ?></strong> votes </em>
	<?php if( pulse_press_user_can_post() ) : ?>
		<?php if(!pulse_press_is_vote(get_the_ID())) : ?>
		<a id="voteup-<?php the_ID();?>" href="?pid=<?php the_ID();?>&nononc=<?php echo wp_create_nonce('vote');?>&action=vote" class="vote-up" title="Vote Up"> <span>Vote Up</span></a> 
		<?php else: ?>
		<a id="votedw-<?php the_ID();?>"href="?pid=<?php the_ID();?>&nononc=<?php echo wp_create_nonce('vote');?>&action=vote" class="vote-down" title="Vote Down"> <span>Vote Down</span></a>
		<?php endif; ?>
	<?php endif; ?>
	</div>
	<?php
}

function pulse_press_voting_init($redirect=true)
{
	if( wp_verify_nonce($_GET['nononc'], 'vote') && ($_GET['action'] == "vote")):
		$post_id = (int)$_GET['pid'];
		
		if(!pulse_press_is_vote($post_id)):
			pulse_press_vote($post_id);
		else:
			pulse_press_delete_vote($post_id);
		endif;
		
		if($redirect==""):
			wp_redirect(pulse_press_curPageURL());
			die();
		else:
			return "vote";
		endif;
		
	endif;
	
	
}
if(!isset($_GET['do_ajax']))
	add_action('init','pulse_press_voting_init');


