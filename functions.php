<?php
/**
 * Twenty Sixteen functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

/**
 * Twenty Sixteen only works in WordPress 4.4 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

/**
 * -----------------------------------
 *           NetSis Default
 * -----------------------------------
 */
function site_after_setup_theme() {
    /*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style(array('css/editor-style.css', 'genericons/genericons.css'));
}
add_action('after_setup_theme', 'site_after_setup_theme');

function netsis_wp_enqueue_scripts() {
	global $stylesheet_ver, $post;

	// Stylesheets
	// -----------
	if (file_exists(get_template_directory().'/default.css'))
		wp_enqueue_style('netsis-default-css', get_template_directory_uri().'/default.css');

	// Loads our main stylesheet.
	wp_enqueue_style('twentyfifteen-style', get_stylesheet_uri(), array('bootstrap'), $stylesheet_ver);

	$auto_import_names = array();

	if (is_page())
	{
		$auto_import_names[0] = 'page';
		$auto_import_names[1] = 'page-'.$post->post_name;
	}
	else if (is_home() || is_front_page())
		$auto_import_name = 'page-index';
	else if (is_archive())
	{
		$auto_import_names[0] = 'archive';

		if ($post != null)
			$auto_import_names[1] = 'archive-'.$post->post_type;
	}
	else if (is_single())
	{
		$auto_import_names[0] = 'single';
		$auto_import_names[1] = 'single-'.$post->post_type;
	}

	foreach ($auto_import_names as $auto_import_name)
	{
		if (file_exists(get_template_directory().'/'.$auto_import_name.'.css'))
		{
			$file_data = get_file_data(get_template_directory().'/'.$auto_import_name.'.css', array('Version' => 'Version'));
			wp_enqueue_style('netsis-'.$auto_import_name, get_template_directory_uri().'/'.$auto_import_name.'.css', array('twentyfifteen-style'), $file_data['Version']);
		}
	}

	// JS Scripts
	// ----------
	wp_deregister_script('jquery');
	wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js');

	if (file_exists(get_template_directory().'/default.js'))
		wp_enqueue_script('netsis-default-js', get_template_directory_uri().'/default.js', array(), false, true);

	foreach ($auto_import_names as $auto_import_name)
	{
		if (file_exists(get_template_directory().'/'.$auto_import_name.'.js'))
		{
			$file_data = get_file_data(get_template_directory().'/'.$auto_import_name.'.js', array('Version' => 'Version'));
			wp_enqueue_script('netsis-'.$auto_import_name.'-js', get_template_directory_uri().'/'.$auto_import_name.'.js', array(), $file_data['Version'], true);
		}
	}
}
add_action('wp_enqueue_scripts', 'netsis_wp_enqueue_scripts');

/**
 * Favicon
 *
 * @since NetSis 1.0
 */
 function fs_get_site_root_path() {
    $base = dirname(__FILE__);
    $path = false;

    if (@file_exists(dirname(dirname($base))."/wp-config.php"))
        $path = dirname(dirname($base));
    else if (@file_exists(dirname(dirname(dirname($base)))."/wp-config.php"))
        $path = dirname(dirname(dirname($base)));
    else
		$path = false;

    if ($path != false)
        $path = str_replace("\\", "/", $path);

    return $path;
}
 
function netsis_favicon() {
	if (file_exists(fs_get_site_root_path().'/favicon.png'))
		echo '<link href="'.home_url().'/favicon.png" rel="icon" type="image/png" />';

	if (file_exists(fs_get_site_root_path().'/favicon.ico'))
		echo '<link href="'.home_url().'/favicon.ico" rel="shortcut icon" type="image/x-icon" />';
}

function netsis_wp_head() {
	netsis_favicon();
}
add_action('wp_head', 'netsis_wp_head');

function netsis_admin_head() {
	netsis_favicon();
}
add_action('admin_head', 'netsis_admin_head');

/**
 * Login Customizado
 *
 * @since NetSis 1.0
 */
function netsis_login_stylesheet() {
    echo '<link rel="stylesheet" id="custom_wp_admin_css"  href="'.get_stylesheet_directory_uri().'/styleLogin.css" type="text/css" media="all" />';
}
add_action('login_enqueue_scripts', 'netsis_login_stylesheet');

function netsis_login_logo_url() {
    return home_url();
}
add_filter('login_headerurl', 'netsis_login_logo_url');

function netsis_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter('login_headertitle', 'netsis_login_logo_url_title');

remove_filter('the_content', 'wpautop');

/**
 * Altera "Obrigado por criar com o WordPress."
 *
 * @since NetSis 1.0
 */
function netsis_admin_footer_text($content) {
    return 'Desenvolvido por: <a href="http://www.netsis.com.br" target="_blank">NetSis - Sistemas Web</a>';
}
add_filter('admin_footer_text', 'netsis_admin_footer_text', 11);

/**
 * Formatação de título da página
 *
 */
function netsis_wp_title($title, $sep) {
	global $paged, $page;

	if (is_feed())
		return $title;

	// Add the site description for the home/front page.
	$site_description = get_bloginfo('description', 'display');
	if (is_home() || is_front_page()) {
		$title = get_bloginfo('name', 'display');

		if ($site_description != '')
			$title .= ' - '.$site_description;
	}

	// Add a page number if necessary.
	if ($paged >= 2 || $page >= 2)
		$title = "$title $sep " . sprintf(__('Page %s', 'twentyfifteen'), max($paged, $page));

	return $title;
}
add_filter('wp_title', 'netsis_wp_title', 16, 2);

/**
 * Adds textarea support to the theme customizer
 */
if (class_exists('WP_Customize_Control')) {
	class WP_Customize_Control_Textarea extends WP_Customize_Control {
	    public $type = 'textarea';
	 
	    public function render_content() {
	        ?>
	            <label>
	                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	                <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
	            </label>
	        <?php
	    }
	}
}

/**
 * --------------
 *      Site
 * --------------
 */

function site_wp_enqueue_scripts() {
	wp_enqueue_script('jquery');

	wp_enqueue_style('bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
	wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css');
	wp_enqueue_style('font-droid-sans', 'https://fonts.googleapis.com/css?family=Droid+Sans:400,700');

	if (is_home() || is_front_page())
	{
		wp_enqueue_style('swiper', get_template_directory_uri().'/js/swiper/css/swiper.min.css');
		wp_enqueue_script('swiper', get_template_directory_uri().'/js/swiper/js/swiper.min.js', array(), false, true);
	}
	else
	{
		global $post;

		if ($post != null)
		{
			switch($post->post_name)
			{
				case 'a-cosmed':
					wp_enqueue_style('swiper', get_template_directory_uri().'/js/swiper/css/swiper.min.css');
					wp_enqueue_script('swiper', get_template_directory_uri().'/js/swiper/js/swiper.min.js', array(), false, true);

					wp_enqueue_style('netsis-slider', get_template_directory_uri().'/slider.css', array('swiper'));

					wp_enqueue_script('netsis-gallery-to-slider', get_template_directory_uri().'/gallery-to-slider.js', array('swiper'), false, true);
					break;

				case 'apoio-ao-esporte':
					wp_enqueue_style('lightbox2', get_template_directory_uri().'/js/lightbox2/css/lightbox.min.css');
					wp_enqueue_script('lightbox2', get_template_directory_uri().'/js/lightbox2/js/lightbox.min.js', array('jquery'), false, true);
					break;

				case 'orcamento':
					wp_enqueue_style('netsis-form', get_template_directory_uri().'/form.css');
					break;

				case 'contato':
					wp_enqueue_style('netsis-form', get_template_directory_uri().'/form.css');
					break;

				case 'cadastro':
					wp_enqueue_style('netsis-form', get_template_directory_uri().'/form.css');
					break;
			}
		}
	}
}
add_action('wp_enqueue_scripts', 'site_wp_enqueue_scripts');

function netsis_wp_enqueue_scripts_localize()
{
	global $post;

	if ($post != null)
	{
		switch($post->post_name)
		{
			case 'home':
			case 'cadastro':
			case 'area-medica':
				wp_localize_script('netsis-page-'.$post->post_name.'-js', 'theme_ajax', array(
				    'url'        => admin_url('admin-ajax.php'),
				    'theme_url' => get_bloginfo('template_directory')
				));
				break;
		}

		switch($post->post_name)
		{
			case 'home':
				wp_localize_script('netsis-page-home-js', 'cosmedfarma_i18n', array(
				    'please_inform_login' => __('Preencha o campo "Login".', 'cosmed'),
				    'please_inform_password' => __('Preencha o campo "Senha".', 'cosmed')
				));
				break;

			case 'cadastro':
				wp_localize_script('netsis-page-cadastro-js', 'cosmedfarma_i18n', array(
				    'all_fields_are_required' => __('Todos os campos são de preenchimento obrigatório.', 'cosmed'),
				    'passwords_must_match' => __('As senhas devem ser iguais.', 'cosmed')
				));
				wp_localize_script('netsis-page-cadastro-js', 'cosmedfarma_global', array(
				    'is_user_logged_in' => is_user_logged_in()
				));
				break;

			case 'area-medica':
				wp_localize_script('netsis-page-area-medica-js', 'cosmedfarma_i18n', array(
				    'all_fields_are_required' => __('Todos os campos são de preenchimento obrigatório.', 'cosmed'),
				    'passwords_must_match' => __('As senhas devem ser iguais.', 'cosmed')
				));
				wp_localize_script('netsis-page-area-medica-js', 'cosmedfarma_global', array(
				    'is_user_logged_in' => is_user_logged_in()
				));
				break;
		}
	}
}
add_action('wp_enqueue_scripts', 'netsis_wp_enqueue_scripts_localize');

if ( ! function_exists( 'twentysixteen_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * Create your own twentysixteen_setup() function to override in a child theme.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Twenty Sixteen, use a find and replace
	 * to change 'twentysixteen' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'twentysixteen', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for custom logo.
	 *
	 *  @since Twenty Sixteen 1.2
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 240,
		'width'       => 240,
		'flex-height' => true,
	) );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 9999 );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'topo-1' => __( 'Topo 1', 'cosmedfarma' ),
		'topo-2'  => __( 'Topo 2', 'cosmedfarma' ),
		'rodape'  => __( 'Rodapé', 'cosmedfarma' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'status',
		'audio',
		'chat',
	) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', twentysixteen_fonts_url() ) );

	// Indicate widget sidebars can use selective refresh in the Customizer.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif; // twentysixteen_setup
add_action( 'after_setup_theme', 'twentysixteen_setup' );

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'twentysixteen_content_width', 840 );
}
add_action( 'after_setup_theme', 'twentysixteen_content_width', 0 );

/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'twentysixteen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Content Bottom 1', 'twentysixteen' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Content Bottom 2', 'twentysixteen' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'twentysixteen_widgets_init' );

if ( ! function_exists( 'twentysixteen_fonts_url' ) ) :
/**
 * Register Google fonts for Twenty Sixteen.
 *
 * Create your own twentysixteen_fonts_url() function to override in a child theme.
 *
 * @since Twenty Sixteen 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function twentysixteen_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Merriweather font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Merriweather:400,700,900,400italic,700italic,900italic';
	}

	/* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Montserrat:400,700';
	}

	/* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Inconsolata:400';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'twentysixteen_javascript_detection', 0 );

/**
 * Enqueues scripts and styles.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_scripts() {
	// Load the html5 shiv.
	wp_enqueue_script( 'twentysixteen-html5', get_template_directory_uri() . '/js/html5.js', array(), '3.7.3' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'twentysixteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20160412' );
	}
}
add_action( 'wp_enqueue_scripts', 'twentysixteen_scripts' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Twenty Sixteen 1.0
 *
 * @param array $classes Classes for the body element.
 * @return array (Maybe) filtered body classes.
 */
function twentysixteen_body_classes( $classes ) {
	// Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}

	// Adds a class of group-blog to sites with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of no-sidebar to sites without active sidebar.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'twentysixteen_body_classes' );

/**
 * Converts a HEX value to RGB.
 *
 * @since Twenty Sixteen 1.0
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function twentysixteen_hex2rgb( $color ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * @since Twenty Sixteen 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function twentysixteen_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	840 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';

	if ( 'page' === get_post_type() ) {
		840 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	} else {
		840 > $width && 600 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
		600 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'twentysixteen_content_image_sizes_attr', 10 , 2 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @since Twenty Sixteen 1.0
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function twentysixteen_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( 'post-thumbnail' === $size ) {
		is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
		! is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'twentysixteen_post_thumbnail_sizes_attr', 10 , 3 );

/**
 * Modifies tag cloud widget arguments to have all tags in the widget same font size.
 *
 * @since Twenty Sixteen 1.1
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array A new modified arguments.
 */
function twentysixteen_widget_tag_cloud_args( $args ) {
	$args['largest'] = 1;
	$args['smallest'] = 1;
	$args['unit'] = 'em';
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'twentysixteen_widget_tag_cloud_args' );
