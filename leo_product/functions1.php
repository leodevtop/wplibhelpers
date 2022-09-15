<?php
Header("HTTP/1.0 200 OK");
Header("HTTP/1.1 200 OK");
// Header('Last-Modified: '.gmdate('D, d M Y H:i:s', $last_update).' GMT');
Header("Cache-Control: no-store, no-cache, must-revalidate");
Header("Cache-Control: post-check=0, pre-check=0");
Header("Pragma: no-cache");

if(!function_exists('_getIP'))
{
	// lowercase first letter of functions. It is more standard for PHP
	function _getIP()
	{
		$ipaddress = '';
		if ($_SERVER['HTTP_CLIENT_IP'])
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if($_SERVER['HTTP_X_FORWARDED_FOR'])
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if($_SERVER['HTTP_X_FORWARDED'])
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if($_SERVER['HTTP_FORWARDED_FOR'])
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if($_SERVER['HTTP_FORWARDED'])
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if($_SERVER['REMOTE_ADDR'])
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';

		return $ipaddress;
	}
}
if(!function_exists('_postdata'))
{
	function _postdata($key, $val='')
	{
		return(isset($_POST[$key])? $_POST[$key] : $val);
	}
}

if(!function_exists('_getdata'))
{
	function _getdata($key, $val='')
	{
		return(isset($_GET[$key])? $_GET[$key] : $val);
	}
}

if(!function_exists('_myReplace'))
{
	function _myReplace($txt, $search=array())
	{
		foreach($search as $k=>$v)
		{
			$txt = str_replace($k,$v,$txt);
		}
		return $txt;
	}
}

if(!function_exists('cut_substr'))
{
	function cut_substr($str, $n=20, $sep='...')
	{
		if(strlen($str)<$n) return $str;
		$html = substr($str,0,$n);
		$html = substr($html,0,strrpos($html,' '));
		return $html.$sep;
	} 
}


if(!function_exists('leo_the_entry_date'))
{
	function leo_the_entry_date()
	{
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if(get_the_time('U') !== get_the_modified_time('U'))
		{
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated hidden" datetime="%3$s">%4$s</time>';
		}

		printf($time_string,
			esc_attr(get_the_date('c')),
			get_the_date(),
			esc_attr(get_the_modified_date('c')),
			get_the_modified_date()
		);
	}
}

add_action('after_setup_theme', 'leo_product_setup');
if(!function_exists('leo_product_setup')):
	function leo_product_setup()
	{
		remove_action('wp_head', 'wp_generator');
		load_theme_textdomain('leo_product', get_template_directory(). '/languages');

		add_theme_support('title-tag');
		add_theme_support('post-thumbnails');
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

		add_action('wp_enqueue_scripts', 'leo_product_scripts');
	}
endif;

if(!function_exists('leo_product_widgets_init'))
{
	function leo_product_widgets_init() {

		register_sidebar(array(
			'name'		  => __('Right', 'leo_product'),
			'id'			=> 'right',
			'before_widget' => '<div class="right-widget clearfix">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="heading heading-widget"><span>',
			'after_title'   => '</span></h3>',
		));

		register_sidebar(array(
			'name'		  => __('Left', 'leo_product'),
			'id'			=> 'left',
			'before_widget' => '<div class="left-widget clearfix">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="heading heading-widget"><span>',
			'after_title'   => '</span></h3>',
		));

		register_sidebar(array(
			'name'		  => __('Main Bottom', 'leo_product'),
			'id'			=> 'main_bottom',
			'before_widget' => '<div class="main-bottom-widget clearfix">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="heading heading-widget"><span>',
			'after_title'   => '</span></h3>',
		));

	}
	add_action('widgets_init', 'leo_product_widgets_init');
}


if(!function_exists('leo_product_scripts'))
{
	function leo_product_scripts()
	{
		$vers = array(
			'bootstrap' => '3.3.6',
			'font-awesome' => '4.7.0',
			'owl-carousel' => '2.2.1',
			'jquery-ui-smoothness' => '1.11.4',
			'animate' => '3.5.2',
			'jquery-validate' => '1.17.0',
		);
		wp_enqueue_style('bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/'.$vers['bootstrap'].'/css/bootstrap.min.css', array(), $vers['bootstrap']);
		wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/'.$vers['font-awesome'].'/css/font-awesome.css', array(), $vers['font-awesome']);
		wp_enqueue_style('owl.carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/'.$vers['owl-carousel'].'/assets/owl.carousel.min.css', array(), $vers['owl-carousel']);
		wp_enqueue_style('owl-theme-default', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/'.$vers['owl-carousel'].'/assets/owl.theme.default.min.css', array(), $vers['owl-carousel']);
		//wp_enqueue_style('jquery-ui-smoothness', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/'.$vers['jquery-ui-smoothness'].'/themes/smoothness/jquery-ui.min.css', array(), $vers['jquery-ui-smoothness']);
		//wp_enqueue_style('jquery-ui-smoothness-theme', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/'.$vers['jquery-ui-smoothness'].'/themes/smoothness/theme.min.css', array(), $vers['jquery-ui-smoothness']);
		wp_enqueue_style('animate', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/'.$vers['animate'].'/animate.min.css', array(), $vers['animate']);
		wp_enqueue_style('main', get_template_directory_uri().'/css/main.css', array(), '20160526.2');
		wp_enqueue_style('style', get_template_directory_uri().'/style.css', array(), '20170907.3');
		if(is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}
		wp_enqueue_style('thickbox', '/'.WPINC.'/js/thickbox/thickbox.css', null, '1.0', true);

		$googlefont = str_replace(',', '%2C', '//fonts.googleapis.com/css?family=Roboto:400,400italic,700,700italic|Roboto+Condensed:400,700|Open+Sans:400,700,800|Oswald:400,700&amp;subset=latin,greek,vietnamese');
		wp_enqueue_style('googlefonts', $googlefont, array(), '20160602.1');

		wp_enqueue_script('jquery');
		wp_enqueue_script('thickbox');
		//wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/'.$vers['bootstrap'].'/js/bootstrap.min.js', null, $vers['bootstrap'], true);
		wp_enqueue_script('owl.carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/'.$vers['owl-carousel'].'/assets/owl.carousel.min.js', null, $vers['owl-carousel'], true);
		wp_enqueue_script('unicode-alias', get_template_directory_uri().'/js/unicode-alias.js', null, '1.0.1', true);
		wp_enqueue_script('jquery-validate', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/'.$vers['jquery-validate'].'/jquery.validate.min.js', null, $vers['jquery-validate'], true);
		//wp_enqueue_script('bootstrap-select', get_template_directory_uri().'/js/bootstrap-select.min.js', null, '1.10.0', true);
		wp_enqueue_script('leo_product-script', get_template_directory_uri().'/js/function.js', array(), '20171012.2', true);
		wp_localize_script('leo_product-script', 'screenReaderText', array(
			'expand'   => __('expand child menu', 'leo_product'),
			'collapse' => __('collapse child menu', 'leo_product'),
		));
	}
}

if(!function_exists('wp_bootstrap_pagination'))
{
	function wp_bootstrap_pagination($args = array())
	{
		$defaults = array(
			'range'		   => 4,
			'custom_query'	=> FALSE,
			'before_output'   => '<nav class="paginations"><ul class="pagination">',
			'after_output'	=> '</ul></nav>'
		);
		
		$args = wp_parse_args(
			$args, 
			apply_filters('wp_bootstrap_pagination_defaults', $defaults)
		);
		
		$args['range'] =(int) $args['range'] - 1;
		if(!$args['custom_query'])
			$args['custom_query'] = @$GLOBALS['wp_query'];
		$count =(int) $args['custom_query']->max_num_pages;
		$page  = intval(get_query_var('paged'));
		$ceil  = ceil($args['range'] / 2);
		
		if($count <= 1)
			return FALSE;
		
		if(!$page)
			$page = 1;
		
		if($count > $args['range']) {
			if($page <= $args['range']) {
				$min = 1;
				$max = $args['range'] + 1;
			} elseif($page >=($count - $ceil)) {
				$min = $count - $args['range'];
				$max = $count;
			} elseif($page >= $args['range'] && $page <($count - $ceil)) {
				$min = $page - $ceil;
				$max = $page + $ceil;
			}
		} else {
			$min = 1;
			$max = $count;
		}
		
		$echo = '';
		$previous = intval($page) - 1;
		$previous = esc_attr(get_pagenum_link($previous));
		
		$firstpage = esc_attr(get_pagenum_link(1));
		if($firstpage &&(1 != $page))
			$echo .= '<li class="previous"><a href="'.$firstpage.'">'.__('First', 'leo_product').'</a></li>';
		if($previous &&(1 != $page))
			$echo .= '<li><a href="'.$previous.'" title="'.__('&laquo; Previous', 'leo_product').'">'.__('Previous', 'leo_product').'</a></li>';
		
		if(!empty($min) && !empty($max)) {
			for($i = $min; $i <= $max; $i++) {
				if($page == $i) {
					$echo .= '<li class="active"><span class="active">'.(int)$i.'<span class="sr-only">(current)</span></span></li>';
				} else {
					$echo .= sprintf('<li><a href="%s">%d</a></li>', esc_attr(get_pagenum_link($i)), $i);
				}
			}
		}
		
		$next = intval($page) + 1;
		$next = esc_attr(get_pagenum_link($next));
		if($next &&($count != $page))
			$echo .= '<li><a href="'.$next.'" title="'.__('Next', 'leo_product').'">'.__('Next &raquo;', 'leo_product').'</a></li>';
		
		$lastpage = esc_attr(get_pagenum_link($count));
		if($lastpage) {
			$echo .= '<li class="next"><a href="'.$lastpage.'">'.__('Last', 'leo_product').'</a></li>';
		}
		if(isset($echo))
			echo $args['before_output'].$echo.$args['after_output'];
	}
}

require get_template_directory().'/inc/customizer.php';



function _add_async_defer_attribute($tag, $handle)
{
	if(in_array($handle, array('jquery-core', 'jquery-migrate', 'jquery-ui-datepicker')))
	{
		return $tag;
	}
	return str_replace(' src', ' async defer src', $tag);
}
add_filter('script_loader_tag', '_add_async_defer_attribute', 10, 2);

