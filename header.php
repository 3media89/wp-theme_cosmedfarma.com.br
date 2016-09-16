<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<header class="site">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-3"><h1><a href="<?php echo get_bloginfo('url'); ?>"><?php echo get_bloginfo('name'); ?></a></h1></div>
			<div class="col-xs-12 col-md-offset-1 col-md-8 col-lg-offset-2 col-lg-7">
				<nav role="navigation" class="menu-topo-1">
					<?php wp_nav_menu(array('theme_location' => 'topo-1')); ?>
				</nav>
				<nav role="navigation" class="menu-topo-2">
					<?php wp_nav_menu(array('theme_location' => 'topo-2')); ?>
				</nav>
			</div>
		</div>
	</div>
</header>

<main class="site">