<?php
/**
 * @package WordPress
 * @subpackage PulsePress
 */
?>
<!doctype html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title>
<?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?>
</title>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />


<meta name="HandheldFriendly" content="True">
<meta name="MobileOptimized" content="320"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php 
/* Archives uncommented
 <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/redmond/jquery-ui-1.7.1.custom.css" type="text/css" media="screen" />

 <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/css/ui.slider.extras.css" type="text/css" media="screen" />
*/
?>
<?php wp_head(); ?>


</head>
<body <?php body_class(); ?>>
<div id="shell">
<div id="header">

	<div class="sleeve">
		<h1><a href="<?php bloginfo( 'url' ); ?>/"><?php bloginfo( 'name' ); ?></a></h1>
		<?php if ( get_bloginfo( 'description' ) ) : ?>
			<small><?php bloginfo( 'description' ); ?></small>
		<?php endif; ?>
		<a class="secondary" href="<?php bloginfo( 'url' ); ?>/"></a>
	</div>
	<div id="access" role="navigation">
	<?php /*  Allow screen readers / text browsers to skip the navigation menu and get right to the good stuff */ ?>
	<div class="skip-link screen-reader-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'pulse_press' ); ?>"><?php _e( 'Skip to content', 'pulse_press' ); ?></a></div>
	<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
	<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary') ); ?>
	</div><!-- #access -->
</div>

<div id="wrapper">
	