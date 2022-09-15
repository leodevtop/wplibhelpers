<?php get_header();
if($page_products = get_page_by_path('san-pham'))
{
	if(function_exists('pll_get_post'))
	{
		$page_products = pll_get_post($page_products->ID);
	}
}
?>

<div class="container featured">
	<div class="slideshow">
		<!-- slideshow -->
		<?php
		if(is_home() || (!function_exists('z_taxonomy_image_url') && is_category())):
			//$slideshow_ids = explode(',', get_theme_mod('slideshow_ids'));
			//$slideshow_ids = array_map('intval', $slideshow_ids);
			//if($slideshow_ids):
			
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
			
			$images = get_posts(array('post_type'=> 'attachment', 'post__in'=>array(258,259,260)));
			?>
				<div id="slideshow-top" class="owl-carousel collection">
				<?php foreach($images as $img):
					$rand = rand(0,count($animates)-1);
					$animate = $animates[$rand];
					unset($animates[$rand]); $animates = array_values($animates);
					$img_html = wp_get_attachment_image($img->ID, 'full', false, array('class'=>'resizeover', 'data-ratio'=>2.4));
				?>
					<div class="item relative">
						<div class="image"><?php echo $img_html ?></div>
						<div class="absolute text-center lead">
							<h3 data-animation="animated <?php echo $animate ?>"><?php echo $img->post_title; ?></h3>
							<div class="description"><em><?php echo $img->post_excerpt; ?></em></div>
						</div>
					</div>
				<?php endforeach ?>
					<?php /*
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
					<?php endforeach; */ ?>
				</div>
			<?php //endif; ?>
			<!-- /slideshow -->
		<?php endif; // is_home ?>
	</div>
</div><!-- featured -->
<main id="main">
	<div class="container main-container">
		<div class="support row text-center strong hidden">
			<hr />
			<span style="color:#7C529D">Viber</span>/<span style="color:#108EE1">Zalo</span>
			<br />
			<?php $tel = get_theme_mod('telephone' ,true) ?>
			<span class="telephone"><a href="tel:<?php echo str_replace(' ', '-', $tel) ?>"><i class="fa fa-phone"></i> <?php echo $tel ?></a></span>
		</div>

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
					<div class="item col-xs-12 col-sm-6 col-md-12 col-lg-12 clearfix collection">
						<div class="item-inner">
								<h3 class="heading-item" style="margin-top: 0"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h3>
							<div class="description">
								<a class="thumb thumb30" href="<?php the_permalink() ?>"><?php the_post_thumbnail('thumbnail', array('class'=>'resizeover', 'data-ratio'=>1.33334)); ?></a>
								<?php the_excerpt() ?>
							</div>
						</div>
					</div>
					<?php
					endforeach;
					wp_reset_postdata(); ?>
				</div>
				<?php
					if($page_baiviet = get_page_by_path('bai-viet'))
					{
						if(function_exists('pll_get_post'))
						{
							$page_baiviet = pll_get_post($page_baiviet->ID);
						}
					}
				?>
				<div class="btn-more text-center"><a href="<?php echo get_permalink($page_baiviet) ?>" class="btn btn-primary"><i class="fa fa-angle-right"></i> Xem các <strong>Bài Viết</strong> khác</a></div>
			</div><!-- post-list -->
		</div>
	</div><!-- .container -->
</main>
<?php get_footer(); ?>