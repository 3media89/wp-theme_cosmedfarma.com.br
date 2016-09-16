<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>
</main>

<footer class="site">
	<div class="container">
		<div class="row">
			<div class="menu-rodape col-xs-9 col-sm-10 col-md-offset-2 col-md-8">
				<?php wp_nav_menu(array('theme_location' => 'rodape')); ?>
			</div>
			<div class="sparta col-xs-3 col-sm-2 col-md-offset-1 col-md-1">
				<a href="http://www.spartacomunicacao.com.br/" target="_blank">Sparta</a>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
