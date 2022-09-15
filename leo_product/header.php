<?php
/* ################################## */
$description = 'Chăm sóc sức khỏe và sắc đẹp như ý, cách phòng tránh bệnh tật. Giới thiệu các sản phẩm cao cấp chiết xuất từ thảo dược thiên nhiên.';
//get_bloginfo();
$alternate = site_url('/');
$title = trim(wp_title('', false));
if(!$title) $title = get_bloginfo('name').' - '.get_bloginfo('description');
if(is_singular())
{
	if(have_posts())
	{
		while(have_posts())
		{
			the_post();
			$description = get_the_excerpt();
			$image = get_the_post_thumbnail_url($post, 'large');
		}
		$alternate = get_permalink();
	}
	wp_reset_query();
}
elseif(is_tax() || is_category() || is_tag())
{
	$description = term_description();
	$alternate = get_term_link(get_queried_object());
}
$description = trim(esc_attr(strip_tags(stripslashes($description))));
?><!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="resource-type" content="document" />
<?php if(in_array(get_post_type(), array('product_order', 'contact_form'))): ?>
	<meta name="robots" content="noindex, nofollow" />
	<meta name="robots" content="nofollow" />
<?php else: ?>
	<meta name="robots" content="all, index, follow" />
	<meta name="googlebot" content="all, index, follow" />
<?php endif ?>
	<meta http-equiv="content-language" content="vi" />
	<meta name="description" content="<?php echo $description; ?>" />
<?php if(isset($image) && $image): ?>
	<meta name="twitter:image" content="<?php echo $image ?>" />
	<meta property="og:image" content="<?php echo $image ?>" />
<?php endif ?>
	<meta property="og:title" content="<?php echo $title; ?>" />
	<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
	<meta property="og:description" content="<?php echo $description; ?>" />
	<meta property="og:type" content="article" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
<?php if(is_singular() && pings_open(get_queried_object())): ?>
	<link rel="pingback" href="<?php bloginfo('pingback_url' ); ?>">
<?php endif; ?>
<!--[if lt IE 9]>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
<![endif]-->
<?php
	//add_thickbox();
	wp_head();
?>

	<link rel="alternate" href="<?php echo $alternate; ?>" hreflang="vi-vn" />
	<link rel="alternate" href="<?php echo $alternate; ?>" hreflang="x-default" />
</head>
<body <?php body_class(); ?>>
<div id="wapper" class="site">
	<div id="fb-root"></div>
	<div id="site-root"></div>
	<header id="main-header">
		<div class="topmod">
			<div class="container">
				<div class="pull-right">
					<div class="tel hidden">
						<?php if(get_theme_mod('fb_link') != ""): ?><a class="text-danger hidden-md hidden-lg" target="_blank" href="<?php echo esc_url(get_theme_mod('fb_link')); ?>"><i class="fa fa-gratipay"></i> <span class="hidden-xs"><?php _e('Like Us', 'leo_product') ?></span></a> &nbsp; <?php endif ?>
						<?php if($contact = get_page_by_path('lien-he')): ?>
							<a class="text-important hidden-sm hidden-md hidden-lg" href="<?php echo get_permalink($contact) ?>"><i class="fa fa-envelope"></i> <span class="hidden-xs"><?php echo get_the_title($contact) ?></span></a> &nbsp; 
						<?php endif; //$contact ?>
						<a class="text-warrning" href="tel:<?php echo preg_replace("/[^0-9+]/", '', get_theme_mod('telephone')) ?>"><i class="fa fa-phone"></i><span class="hidden-xs"> <?php echo get_theme_mod('telephone') ?></span></a>
					</div>
					<?php if(has_nav_menu('top')): ?>
						<div class="topnav clearfix">
							<nav class="navbar navbar-default">
								<div class="collapse navbar-collapse" id="topnav">
									<?php
										wp_nav_menu(array(
											'theme_location' => 'top',
											'items_wrap' => '<ul id="%1$s" class="%2$s nav navbar-nav">%3$s</ul>',
										));
									?>
								</div><!-- #topnav -->
							</nav>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php if(has_nav_menu('primary')): ?>
			<div class="mainnav">
				<nav class="navbar navbar-inverse"><!-- navbar-fixed-top -->
					<div class="container">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#mainnav">
								<span class="sr-only">Toggle mainnav</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="logo navbar-brand<?php if(is_home()) echo ' active' ?>" href="<?php echo esc_url(site_url('/')); ?>"><span class="txt"><i class="fa fa-home hidden-xs"></i><span class="sr-only"> <?php echo __('Home', 'leo_product') ?></span></span><img class="" src="<?php echo site_url(); ?>/logo.png" alt="<?php bloginfo('name' ); ?>" title="<?php bloginfo('name' ); ?>" /></a>
						</div>
						<div class="collapse navbar-collapse" id="mainnav">
							<?php
								wp_nav_menu(array(
									'theme_location' => 'primary',
									'items_wrap'	 => '<ul id="%1$s" class="%2$s nav navbar-nav">%3$s</ul>',
								));
							?>
						</div><!-- #mainnav -->
					</div>
				</nav>
			</div><!-- .mainnav -->
		<?php endif; ?>
	</header><!-- /#main-header -->
