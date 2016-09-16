<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header();

$sliders = get_page_by_path('home', OBJECT, 'ns_slideshow');
$sliders = get_post_meta($sliders->ID, '_slideshow', true);

if ($sliders != '')
{
	$sliders = json_decode($sliders);
?>
<div class="swiper-container">
	<div class="swiper-wrapper">
	<?php
		foreach ($sliders->imgs as $img)
		{
			echo '<div class="swiper-slide">';

			if ($img->link != '')
				echo '<a href="'.$img->link.'">';

			$img_info = wp_get_attachment_image_src($img->id, 'full');
			echo '<img src="'.$img_info[0].'" width="'.$img_info[1].'" height="'.$img_info[2].'" alt="Banner" />';

			if ($img->link != '')
				echo '</a>';

			echo '</div>';
		}
	?>
	</div>
	<!-- Add Pagination -->
	<div class="swiper-pagination"></div>
	<!-- Add Arrows -->
	<div class="swiper-button-next"></div>
	<div class="swiper-button-prev"></div>
</div>
<?php } ?>
<div class="iso-atendemos-brasil">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<img src="<?php echo get_template_directory_uri(); ?>/images/home-iso-atentemos-brasil.jpg" alt="<?php _e('Atendemos com excelência, em toda as regiões do Brasil e o frete é gratuito.', 'cosmedfarma'); ?>" />
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="area-medica col-xs-12 col-md-4">
			<form method="post" class="login" id="form-login">
				<div>
					<?php
						if (function_exists('wp_nonce_field'))
							wp_nonce_field('cosmed_user_login_action', 'cosmed_user_login_nonce');
					?>
					<p class="login">
						<label for="login"><?php _e('Login:', 'cosmedfarma'); ?></label><br />
						<input type="text" name="login" id="login" />
					</p>
					<p class="senha">
						<label for="senha"><?php _e('Senha:', 'cosmedfarma'); ?></label><br />
						<input type="password" name="senha" id="senha" />
					</p>
					<p class="entrar">
						<input type="submit" value="<?php _e('Entrar', 'cosmedfarma'); ?>" />
						<img src="<?php echo get_template_directory_uri().'/images/ajax-loader.gif'; ?>" alt="Carregando..." style="display:none" />
					</p>
					<p class="cadastre-se">
						<a href="<?php echo get_permalink(get_page_by_path('cadastro')); ?>"><?php _e('Cadastre-se', 'cosmedfarma'); ?></a>
					</p>
				</div>
			</form>
		</div>
		<div class="faca-orcamento col-xs-12 col-md-4">
			<p><a href="<?php echo get_permalink(get_page_by_path('orcamento')); ?>">Peça agora o seu orçamento</a></p>
		</div>
		<div class="apoio-ao-esporte col-xs-12 col-md-4">
			<p><a href="<?php echo get_permalink(get_page_by_path('apoio-ao-esporte')); ?>">Apoio ao esporte</a></p>
		</div>
	</div>
</div>
<div class="texto destaque-1">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<p>Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit<br />
				consequat ipsum, nec sagittis sem nibh id elit. Aenean sollicitudin, lorem quis bibendum auctor.</p>
				<p>LOREM IPSUM DOLOR</p>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>