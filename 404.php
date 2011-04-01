<?php
/**
 * @package WordPress
 * @subpackage PulsePress
 */
?>
<?php get_header(); ?>

<div class="sleeve_main">

	<div id="main">

		<h2><?php _e( 'Not Found', 'pulse_press' ); ?></h2>
		<p><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'pulse_press' ); ?></p>
		<?php get_search_form(); ?>

	</div> <!-- main -->

</div> <!-- sleeve -->

<?php get_footer(); ?>