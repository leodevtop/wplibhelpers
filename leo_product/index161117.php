<?php get_header();
if($page_products = get_page_by_path('san-pham'))
{
	if(function_exists('pll_get_post'))
	{
		$page_products = pll_get_post($page_products->ID);
	}
}
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>

<main id="main">
	<div class="container main-container">
		<div class="featured row">
			<div class="slideshow col-sm-12 col-md-7">
				<!-- slideshow -->
				<?php
				if(is_home() || (!function_exists('z_taxonomy_image_url') && is_category())):
					$slideshow_ids = explode(',', get_theme_mod('slideshow_ids'));
					$slideshow_ids = array_map('intval', $slideshow_ids);
					if($slideshow_ids):
						$animates = array(
							'zoomIn','zoomInDown','zoomInUp',
							'slideInUp','slideInDown','slideInLeft','slideInRight',
							'rotateIn','rotateInUpLeft','rotateInUpRight','rotateInDownLeft','rotateInDownRight',
							'lightSpeedIn',
							'flipInX','flipInY',
							'fadeInUp','fadeInDown','fadeInLeft','fadeInRight',
							'bounceInUp','bounceInDown','bounceInLeft','bounceInRight',
							'bounce','pulse','rubberBand','swing','tada','wobble','jello',
							);
					?>
						<div id="slideshow-top" class="owl-carousel collection do-animate">
							<?php
							$args = array(
								'post_type' => array('page', 'post', 'leo_product'),
								'post__in' => $slideshow_ids,
								//'order' => 'ASC'
							);
							$myposts = get_posts($args);
							foreach($myposts as $k=>$post):
								$rand = rand(0,count($animates)-1);
								$animate = $animates[$rand];
								unset($animates[$rand]); $animates = array_values($animates);
								setup_postdata($post);
							?>
								<div class="item relative">
									<div class="image"><?php the_post_thumbnail('large',array('class'=>'resizeover', 'data-ratio'=>2)); ?></div>
									<div class="absolute text-center">
										<h3 data-animation="animated <?php echo $animate ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
										<div class="description"><em><?php the_excerpt(); ?></em></div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<!-- /slideshow -->
				<?php endif; // is_home ?>
			</div>
			<div class="sidebar col-sm-12 col-md-5">
				<section class="welcome">
					<?php if( get_theme_mod('welcome')):
						$queryvar = new WP_query('page_id='.get_theme_mod('welcome' ,true));
						while($queryvar->have_posts()):
							$queryvar->the_post();
					?>
					<!-- Welcome homepage -->
						<h2 class="heading"><?php the_title(); ?></h2>
						<div class="description"><?php the_content('<span class="btn btn-info btn-sm btn-readmore"><i class="fa fa-chevron-right"></i> '.__('View Detail', 'leo_product').'</span>'); ?></div>
					<?php endwhile;
					endif ?>
					<div class="tags"></div>
				</section>
				<hr />
				<div class="support row text-center">
					<div class="col-md-6">
						<span class="text-important">Viber</span>/<span class="text-danger">Zalo</span>
						<br />
						<span class="telephone"><i class="fa fa-phone"></i> <strong><?php echo get_theme_mod('telephone' ,true) ?></strong></span>
						</div>
					<div class="col-md-6">
							<div class="social clearfix">
								<?php if ( get_theme_mod('fb_link') != ""): ?><a target="_blank" href="<?php echo esc_url(get_theme_mod('fb_link')); ?>"><i class="fa fa-facebook"></i></a><?php endif ?>
								<?php if ( get_theme_mod('gplus_link') != ""): ?><a target="_blank" href="<?php echo esc_url(get_theme_mod('gplus_link')); ?>"><i class="fa fa-google-plus"></i></a><?php endif ?>
								<?php if ( get_theme_mod('twitt_link') != ""): ?><a target="_blank" href="<?php echo esc_url(get_theme_mod('twitt_link')); ?>"><i class="fa fa-twitter"></i></a><?php endif ?>
								<?php if ( get_theme_mod('youtube_link') != ""): ?><a target="_blank" href="<?php echo esc_url(get_theme_mod('youtube_link')); ?>"><i class="fa fa-youtube"></i></a><?php endif ?>
								<?php if ( get_theme_mod('instagram_link') != ""): ?><a target="_blank" href="<?php echo esc_url(get_theme_mod('instagram_link')); ?>"><i class="fa fa-instagram"></i></a><?php endif ?>
							</div>
					</div>
				</div>
			</div>
		</div><!-- featured -->

		<div class="videos row row-padding-sm row-list table-view">
		</div><!-- videos -->

		<div class="row row-list">
			<div class="product-list item col-xs-12 col-sm-12 col-md-8 col-lg-8">
				<h2 class="heading"><?php echo _e('Products', 'leo_product') ?></h2>
				<div class="row row-padding-sm row-list table-view">
					<?php
					$args = array(
						'post_type' => 'leo_product',
						'showposts' => 24,
					);
					$myposts = get_posts($args);
					foreach($myposts as $k=>$post)
					{
						setup_postdata($post);
						$post->thumb_crop = false;
						//$post->thumb_size = 'thumbnail';
						$post->item_class = 'col-xs-6 col-sm-4 col-md-4 col-lg-4';
						get_template_part('template-product/item');
					}
					wp_reset_postdata(); ?>
				</div>
			</div><!-- product-list -->
			<div class="post-list item col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<h2 class="heading"><?php _e('Posts', 'leo_product'); ?></h2>
				<div class="row row-padding-sm row-list">
					<?php
					$args = array(
						'post_type' => 'post',
						'showposts' => 6,
						//'order' => 'ASC',
						//'category' =>$term->term_id,
					);
					$myposts = get_posts($args);
					foreach($myposts as $l=>$post):
						setup_postdata($post);
					?>
					<div class="item col-xs-6 col-sm-6 col-md-12 col-lg-12 clearfix collection">
						<div class="item-inner">
							<a class="thumb thumb30" href="<?php the_permalink() ?>"><?php the_post_thumbnail('thumbnail', array('class'=>'resizeover', 'data-ratio'=>1.33334)); ?></a>
							<div class="description">
								<h4 style="margin-top: 0"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h4>
								<?php the_excerpt() ?>
							</div>
						</div>
					</div>
					<?php
					endforeach;
					wp_reset_postdata(); ?>
				</div>
			</div><!-- post-list -->
		</div>
	</div><!-- .container -->
</main>
<?php get_footer(); ?>