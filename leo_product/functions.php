<?php

Header("HTTP/1.0 200 OK");
Header("HTTP/1.1 200 OK");
// Header('Last-Modified: '.gmdate('D, d M Y H:i:s', $last_update).' GMT');
Header("Cache-Control: no-store, no-cache, must-revalidate");
Header("Cache-Control: post-check=0, pre-check=0");
Header("Pragma: no-cache");

/* ================= {TAXONOMY}_ADD_FORM_FIELDS  */
// =========== category
add_action('category_add_form_fields', 'leo_term_meta_add_new_meta_field');
add_action('category_edit_form_fields', 'leo_term_meta_edit_form_field');
// =========== leo_category
add_action('pcat_add_form_fields', 'leo_term_meta_add_new_meta_field');
add_action('pcat_edit_form_fields', 'leo_term_meta_edit_form_field');

// save our taxonomy image while edit or save term
add_action('edited_term','leo_term_meta_save_taxonomy_image', 10, 2);
add_action('create_term','leo_term_meta_save_taxonomy_image', 10, 2);
/* ================= //{TAXONOMY}_ADD_FORM_FIELDS  */

add_action('after_setup_theme', 'leo_product_setup');
if(!function_exists('leo_product_setup'))
{
	function leo_product_setup()
	{
		remove_action('wp_head', 'wp_generator');
		load_theme_textdomain('leo_product', get_template_directory(). '/languages');

		add_theme_support('title-tag');
		add_theme_support('post-thumbnails');
		add_theme_support('custom-logo');
		add_post_type_support('post', 'excerpt');
		add_post_type_support('page', 'excerpt');

		register_nav_menus(array(
			'primary' => __('Primary', 'leo_product'),
			'top' => __('Top menu', 'leo_product'),
			'bottom1' => __('Bottom 1 Menu', 'leo_product'),
			'bottom2' => __('Bottom 2 Menu', 'leo_product'),
			'bottom3' => __('Bottom 3 Menu', 'leo_product'),
			'bottom4' => __('Bottom 4 Menu', 'leo_product'),
			'footer' => __('Footer Menu', 'leo_product'),
		));

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support('html5', array(
			'comment-list',
			'comment-form',
			'search-form',
			'gallery',
			'caption'
		));

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support('post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
			'status',
			'audio',
			'chat',
		));

		add_action('wp_enqueue_scripts', 'tict_products_scripts');
	}
} //leo_product_setup

if(!function_exists('tict_products_widgets_init'))
{
	function tict_products_widgets_init()
	{
		require get_template_directory().'/inc/widgets.php';
		unregister_widget('WP_Widget_Recent_Posts');
		register_widget('My_Recent_Posts_Widget');

		register_sidebar(array(
			'name'		  => __('Left', 'leo_product'),
			'id'			=> 'left',
			'before_widget' => '<div id="%1$s" class="left-widget widget %2$s clearfix">',
			'after_widget'  => '</div><!-- content-widget --></div>',
			'before_title'  => '<h3 class="heading heading-widget"><span>',
			'after_title'   => '</span></h3><div class="content-widget">',
		));

		register_sidebar(array(
			'name'		  => __('Right', 'leo_product'),
			'id'			=> 'right',
			'before_widget' => '<div id="%1$s" class="right-widget widget %2$s clearfix">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="heading heading-widget"><span>',
			'after_title'   => '</span></h3>',
		));

		register_sidebar(array(
			'name'		  => __('Main Bottom', 'leo_product'),
			'id'			=> 'main_bottom',
			'before_widget' => '<div id="%1$s" class="main-bottom-widget widget %2$s clearfix">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="heading heading-widget"><span>',
			'after_title'   => '</span></h3>',
		));

	}
	add_action('widgets_init', 'tict_products_widgets_init');
}


if(!function_exists('tict_products_scripts'))
{
	function tict_products_scripts()
	{
		$vers = array(
			'bootstrap' => '3.3.7',
			'font-awesome' => '4.7.0',
			'owl-carousel' => '2.2.1',
			'jquery-ui-smoothness' => '1.11.4',
			'animate' => '3.5.2',
			'jquery-validate' => '1.17.0',
			'parallax' => '1.5.0',
			'jquery-elevatezoom' => '3.0.8',
			'bootstrap-notify' => '0.2.0',
			'readmore' => '2.2.1',
		);
		wp_enqueue_style('bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/'.$vers['bootstrap'].'/css/bootstrap.min.css', array(), $vers['bootstrap']);
		wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/'.$vers['font-awesome'].'/css/font-awesome.css', array(), $vers['font-awesome']);
		wp_enqueue_style('owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/'.$vers['owl-carousel'].'/assets/owl.carousel.min.css', array(), $vers['owl-carousel']);
		//wp_enqueue_style('owl-theme-default', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/'.$vers['owl-carousel'].'/assets/owl.theme.default.min.css', array(), $vers['owl-carousel']);
		wp_enqueue_style('jquery-ui-smoothness', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/'.$vers['jquery-ui-smoothness'].'/themes/smoothness/jquery-ui.min.css', array(), $vers['jquery-ui-smoothness']);
		wp_enqueue_style('jquery-ui-smoothness-theme', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/'.$vers['jquery-ui-smoothness'].'/themes/smoothness/theme.min.css', array(), $vers['jquery-ui-smoothness']);
		wp_enqueue_style('animate', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/'.$vers['animate'].'/animate.min.css', array(), $vers['animate']);
		wp_enqueue_style('bootstrap-notify', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/'.$vers['bootstrap-notify'].'/css/bootstrap-notify.min.css', array(), $vers['bootstrap-notify']);
		wp_enqueue_style('main', get_template_directory_uri().'/css/main.css', array(), '20180515.3');
		wp_enqueue_style('style', get_template_directory_uri().'/style.css', array(), '20181131.5');
		if(is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}
		wp_enqueue_style('thickbox', '/'.WPINC.'/js/thickbox/thickbox.css', null, '1.0', true);

		$googlefont = str_replace(',', '%2C', '//fonts.googleapis.com/css?family=Roboto:400,400italic,700,700italic|Roboto+Condensed:400,700|Open+Sans:400,700,800|Oswald:400,700&amp;subset=latin,greek,vietnamese');
		wp_enqueue_style('googlefonts', $googlefont, array(), '20160602.1');

		wp_enqueue_script('jquery');
		wp_enqueue_script('thickbox');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/'.$vers['bootstrap'].'/js/bootstrap.min.js', null, $vers['bootstrap'], true);
		wp_enqueue_script('owl.carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/'.$vers['owl-carousel'].'/owl.carousel.min.js', null, $vers['owl-carousel'], true);
		wp_enqueue_script('unicode-alias', get_template_directory_uri().'/js/unicode-alias.js', null, '1.0.1', true);
		wp_enqueue_script('parallax', 'https://cdnjs.cloudflare.com/ajax/libs/parallax.js/'.$vers['parallax'].'/parallax.min.js', null, $vers['parallax'], true);
		wp_enqueue_script('jquery-validate', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/'.$vers['jquery-validate'].'/jquery.validate.min.js', null, $vers['jquery-validate'], true);
		wp_enqueue_script('jquery-validate-additional-methods', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/'.$vers['jquery-validate'].'/additional-methods.min.js', null, $vers['jquery-validate'], true);
		//wp_enqueue_script('jquery-validate-messages_vi', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/'.$vers['jquery-validate'].'/localization/messages_vi.min.js', null, $vers['jquery-validate'], true);
		wp_enqueue_script('jquery-elevatezoom', 'https://cdnjs.cloudflare.com/ajax/libs/elevatezoom/'.$vers['jquery-elevatezoom'].'/jquery.elevatezoom.min.js', null, $vers['jquery-elevatezoom'], true);
		wp_enqueue_script('bootstrap-notify', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/'.$vers['bootstrap-notify'].'/js/bootstrap-notify.min.js', null, $vers['bootstrap-notify'], true);
		wp_enqueue_script('readmore', 'https://cdnjs.cloudflare.com/ajax/libs/Readmore.js/'.$vers['readmore'].'/readmore.min.js', null, $vers['readmore'], true);
		wp_enqueue_script('enqueue-script', get_template_directory_uri().'/js/function.js', array(), '20181013.1', true);
		wp_localize_script('localize-script', 'screenReaderText', array(
			'expand'   => __('expand child menu', 'leo_product'),
			'collapse' => __('collapse child menu', 'leo_product'),
		));
	}
}

require get_template_directory().'/inc/customizer.php';
require get_template_directory().'/inc/functions.php';
require get_template_directory().'/plugins/post-page.php';
