<?php


function pulse_press_vote_on_post()
{	
	if(get_option('pulse_press_show_voting')):
	$votes = pulse_press_total_votes(get_the_ID());
	
		if(empty($votes))
			$votes = 0;
	
	switch(get_option('pulse_press_voting_type')){
		default:
		case "one": ?>
		<div class="vote" >
		<em title="total votes"><strong id="votes-<?php the_ID();?>"><?php echo $votes; ?></strong> votes </em>
		<?php if( pulse_press_user_can_post() ) : ?>
			<?php if(!pulse_press_is_vote(get_the_ID())) : ?>
			<a id="voteup-<?php the_ID();?>" href="?pid=<?php the_ID();?>&nononc=<?php echo wp_create_nonce('vote');?>&action=vote" class="vote-up" title="Vote Up"> <span>Vote Up</span></a> 
			<?php else: ?>
			<a id="voteup-<?php the_ID();?>"href="?pid=<?php the_ID();?>&nononc=<?php echo wp_create_nonce('vote');?>&action=vote" class="vote-up vote-up-set" title="Unvote"> <span>Unvote</span></a>
			<?php endif; ?>
		<?php endif; ?>
		</div>
		
		<?php 
		break;
		case "two": ?>
		<div class="vote" >
		<em title="total votes"><strong id="votes-<?php the_ID();?>"><?php echo $votes; ?></strong> votes </em>
		<?php if( pulse_press_user_can_post() ) : ?>
			<?php if( pulse_press_is_vote(get_the_ID()) == null ) :  // still need to vote ?>
			
			<a id="voteup-<?php the_ID();?>" href="?pid=<?php the_ID();?>&nononc=<?php echo wp_create_nonce('vote');?>&action=vote" class="vote-up" title="Vote Up"> <span>Vote Up</span></a> 
			<a id="votedw-<?php the_ID();?>"href="?pid=<?php the_ID();?>&nononc=<?php echo wp_create_nonce('vote');?>&action=votedown" class="vote-down" title="Vote Down"> <span>Vote Down</span></a>
			
			<?php elseif( pulse_press_is_vote(get_the_ID()) > 0 ): // voted up ?>
			
			<a id="voteup-<?php the_ID();?>" href="?pid=<?php the_ID();?>&nononc=<?php echo wp_create_nonce('vote');?>&action=vote" class="vote-up vote-up-set" title="Unvote"> <span>Unvote</span></a> 
			<a id="votedw-<?php the_ID();?>"href="?pid=<?php the_ID();?>&nononc=<?php echo wp_create_nonce('vote');?>&action=votedown" class="vote-down" title="Vote Down"> <span>Vote Down</span></a>
			
			<?php else: // voted down ?>
			
			<a id="voteup-<?php the_ID();?>" href="?pid=<?php the_ID();?>&nononc=<?php echo wp_create_nonce('vote');?>&action=vote" class="vote-up" title="Vote Up"> <span>Vote Up</span></a> 
			<a id="votedw-<?php the_ID();?>"href="?pid=<?php the_ID();?>&nononc=<?php echo wp_create_nonce('vote');?>&action=votedown" class="vote-down vote-down-set" title="Unvote"> <span>Unvote</span></a>
			
			<?php endif; ?>
		<?php endif; ?>
		</div>
		
		<?php
		break;
	
	}
	?>
	
	<?php endif; 
}

function pulse_press_voting_init($redirect=true)
{
	if( wp_verify_nonce( $_GET['nononc'], 'vote') && in_array( $_GET['action'], array("vote","votedown") ) ):
		$post_id = (int)$_GET['pid'];
		
		switch( get_option('pulse_press_voting_type') ) {
			default:
			case "one":
				if( pulse_press_is_vote($post_id) == null ):
					pulse_press_vote($post_id);
				else:
					pulse_press_delete_vote($post_id);
				endif;
			break;
			
			case "two":
				if( pulse_press_is_vote( $post_id ) == null ) : // still need to vote
					if( $_GET['action'] == "votedown"  ):
						pulse_press_vote_down($post_id);
					endif;
					
					if( $_GET['action'] == "vote" ):
						pulse_press_vote($post_id);
					endif;
	
				elseif(  pulse_press_is_vote( $post_id ) > 0 ): // the user previously voted up
					pulse_press_delete_vote($post_id); 
					
					if( $_GET['action'] == "votedown"  ):
						
						pulse_press_vote_down($post_id); // 
					endif;
				else:
					pulse_press_delete_vote($post_id); 
					
					if( $_GET['action'] == "vote"  ):
						pulse_press_vote($post_id); // 
					endif;
				endif; 
			break;
		}
		
		
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


