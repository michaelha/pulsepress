<?php
/**
 * @package WordPress
 * @subpackage PulsePress
 */
?>
<?php get_header(); ?>

<div class="sleeve_main">

	<div id="main">

		<h1 id="page-title">404 - Doh!</h1>
		<p>Something has gone wrong and the page you're looking for can't be found.</p>
		<p>Hopefully one of the options below will help you</p>

	<ul>
		<li>You could visit <a href="<?php echo site_url(); ?>">the homepage</a></li>
		<li>You can search the site using the search box to the below</li>
	</ul>
		<?php get_search_form(); ?>

	</div> <!-- main -->

</div> <!-- sleeve -->

<?php get_footer(); ?>