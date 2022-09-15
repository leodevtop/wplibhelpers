<?php get_header();
if($page_products = get_page_by_path('san-pham'))
{
	if(function_exists('pll_get_post'))
	{
		$page_products = pll_get_post($page_products->ID);
	}
}
if($page_booknow = get_page_by_path('dat-hang'))
{
	if(function_exists('pll_get_post'))
	{
		$page_booknow = pll_get_post($page_booknow->ID);
	}
}
$now = current_time('Y-m-d');
$args = array(
	'post_type' => 'leo_product',
	'posts_per_page' => 1,
	'tax_query' => array(
		array(
			'taxonomy' => 'product_category',
			'field' => 'slug',
			'terms' => 'hang-order', //
		),
	),
	'meta_query' => array(
		//'relation' => 'AND',
		array(
			'relation' => 'OR',
			array(
				'key' => 'publish_up',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key' => 'publish_up',
				'value' => '',
				'compare' => '=',
			),
			array(
				'key' => 'publish_up',
				'value' => $now,
				'compare' => '<=',
			),
		),
		array(
			'relation' => 'OR',
			array(
				'key' => 'publish_down',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key' => 'publish_down',
				'value' => '',
				'compare' => '=',
			),
			array(
				'key' => 'publish_down',
				'value' => $now,
				'compare' => '>=',
			),
		),
	),
);
$featured = get_posts($args);
$product_id = 0;
if(!empty($featured) && !is_wp_error($featured))
{

	$post = $featured[0];
	$product_id = $post->ID;
	$product_title = get_the_title();
	setup_postdata($post);
	$prices = get_post_meta($product_id, 'price', true);
	$price_datas = array();
	if(is_array($prices))
	{
		$count = count($prices);
		foreach($prices as $k=>$p)
		{
			$price_data = new stdClass();
			if(is_array($p))
			{
				$price = new stdClass();
				$price->title = $p['title'];
				$price->info = $p['info'];
				$price->base = intval($p['price']);
				$price->off = intval($p['price_safeoff']);
				$price->isoff = $price->base && $price->off && ($price->base-$price->off>0);
				$price->price = $price->isoff? $price->off : $price->base;
				// ---------------
				if($price && $price->price)
				{
					$price_data->price = number_format($price->price, 0, ',', '.').'<sup itemprop="priceCurrency">'.__('VND', 'leo_product').'</sup>';
				}
				if($price && $price->isoff)
				{
					$price_data->priceoff = ' <span class="base-price"><s>'.number_format($price->base, 0, ',', '.').'</s><sup>'.__('VND', 'leo_product').'</sup></span>';
				}
				$price_data->title = $price->title;
				$price_data->info = $price->info;
			} //if(is_array($p))
			$price_datas[] = $price_data;
		} //foreach($prices as $p)
	} //(is_array($prices))
	$product_option = get_post_meta($product_id, 'option', true);
	$category = get_the_terms(get_the_ID(), 'product_category');
	$publish_up = get_post_meta(get_the_ID(), 'publish_up', true);
	$publish_down = get_post_meta(get_the_ID(), 'publish_down', true);
	//$now = current_time('Y-m-d');
	//global $more;
	// Set (inside the loop) to display content above the more tag.
	//$more = 0;
	?>
	<section class="featured background-gray">
		<div class="container product-single">
			<?php if(!empty($category) && !is_wp_error($category))
			{
			?>
			<div class="category">
				<?php foreach($category as $k=>$term)
				{
				?>
				<i class="fa fa-angle-left"></i> <a href="<?php echo get_term_link($term) ?>"><?php echo $term->name ?></a>
				<?php
					} //foreach
				?>
			</div>
			<?php
			}//empty($category) ?>
			<h2 class="heading"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
			<div class="row">
				<div class="col-md-7">
					<div class="images clearfix">
						<div class="item item_big"><a class="thumb thickbox" href="<?php echo the_post_thumbnail_url('large'); ?>"><?php the_post_thumbnail('large', array('class'=>'resizeover', 'data-ratio'=>1)); ?></a></div>
						<?php
							if($images_id = get_post_meta(get_the_ID(), 'images', true))
							{
								$more = count($images_id) - 2;
								foreach($images_id as $k=>$img_id)
								{
							?>
								<div class="item<?php if($k>=2) echo ' hidden' ?>">
									<a class="thumb thickbox" href="<?php echo wp_get_attachment_image_url($img_id, 'large') ?>">
										<?php
										echo wp_get_attachment_image($img_id, 'thumbnail');
										if($k)
										{
										?>
											<div class="ovelay"><div class="inner"><div class="text">+<?php echo $more ?></div></div></div>
										<?php
										} //if($k==($count-1))
										?>
									</a>
								</div>
							<?php
								}
							}
						?>
					</div>
				</div>
				<div class="col-md-5 product-summary">
					<p class="tags">#<?php the_tags('', ', #') ?></p>
					<p class="code_date">
						<i class="small">
							<?php
								the_date();
								$publish_down = get_post_meta(get_the_ID(), 'publish_down', true);
								if($publish_down != '0000-00-00') echo ' - '.date_i18n(get_option('date_format'), strtotime( $publish_down));
							?>
						</i>
					</p>
					<?php the_excerpt() ?>
					<p><a href="<?php the_permalink() ?>" class="readmore"><i class="fa fa-angle-right"></i> <?php _e('Read More', 'leo_product') ?></a></p>
					<?php if($promotion = get_post_meta(get_the_ID(), 'promotion', true)): ?>
						<p class="promotion"><strong><i class="fa fa-gift"></i> <?php _e('Promotion', 'leo_product') ?></strong>: <?php echo $promotion ?></p>
					<?php endif ?>
					<?php if($product_option)
					{
					?>
					<p class="product_option">
						<h4><?php _e('Lựa chọn', 'leo_product') ?></h4>
						<ul>
					<?php
						if(isset($product_option['size']))
						{
					?>
							<li class="size"><strong><?php _e('Size', 'leo_product') ?>:</strong> <?php echo str_replace('|', ', ', $product_option['size']) ?></li>
					<?php
						}
						// =>
						if(isset($product_option['color']))
						{
					?>
							<li class="size"><strong><?php _e('Color', 'leo_product') ?>:</strong> <?php echo str_replace('|', ', ', $product_option['color']) ?></li>
					<?php
						}
					?>
						</ul>
					</p><!-- product_option -->
					<?php
					}
					?>
					<p class="price">
						<?php if(isset($price->price) && $price->price) echo '<strong>'.number_format($price->price, 0, ',', '.').'</strong><sup>'.__('VND', 'leo_product').'</sup>'; else _e('Price Contact', 'leo_product'); ?>
						<?php if(isset($price->isoff) && $price->isoff) echo ' <span class="base-price"><s>'.number_format($price->base, 0, ',', '.').'</s><sup>'.__('VND', 'leo_product').'</sup></span>' ?>
					</p>
					<?php
					if($publish_down && $publish_down>=$now)
					{ ?>
						<p class="text-danger">
							<i class="fa fa-lg fa-exclamation-circle"></i>
							Chỉ <strong>nhận đặt hàng</strong> trong <strong><u><?php echo human_time_diff(strtotime($publish_down), current_time('timestamp')); ?></u></strong> nữa. Hãy nhanh tay!</p>
					<?php
					}
					?>
					<p>
						<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#groupOrderModal" data-backdrop="static" data-keyboard="false"><i class="fa fa-chevron-right"></i> <?php _e('Order Now', 'leo_product') ?></button>

						<a class="btn" href="<?php the_permalink() ?>"><i class="fa fa-angle-right"></i> <?php _e('Xem chi tiết', 'leo_product') ?></a>
					</p>
					<div class="well well-sm">
						Bạn có thể gọi, sms, zalo theo sđt <a href="tel:<?php echo preg_replace("/[^0-9+]/", '', get_theme_mod('telephone')) ?>"><strong><?php echo get_theme_mod('telephone') ?></strong></a>
					</div>
				</div>
			</div>
		</div><!-- featured -->
	</section>
<?php
	wp_reset_postdata();
}
else
{ ?>
<div class="container featured">
	<div class="slideshow">
		<!-- slideshow -->
		<?php
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
				</div>
			<?php //endif; ?>
			<!-- /slideshow -->
	</div>
</div><!-- featured -->
<?php
}// $featured ?>
<main id="main">
	<div class="container main-container">

		<div class="row row-list">
			<div class="product-list item col-xs-12 col-sm-12 col-md-8 col-lg-8">
				<h2 class="heading"><?php echo _e('Products', 'leo_product') ?></h2>
				<div class="row row-padding-sm row-list table-view">
					<?php
					$args = array(
						'post_type' => 'leo_product',
						'posts_per_page' => 24,
						'tax_query' => array(
							array(
								'taxonomy'  => 'product_category',
								'field'     => 'slug',
								'terms'     => 'hang-order',
								'operator'  => 'NOT IN'
							),
						),
					);
					$products = get_posts($args);
					foreach($products as $k=>$post)
					{
						setup_postdata($post);
						$post->thumb_crop = false;
						//$post->thumb_size = 'thumbnail';
						$post->item_class = 'col-xs-6 col-sm-4 col-md-4 col-lg-4';
						get_template_part('template-product/item');
					}
					wp_reset_postdata(); ?>
				</div>
				<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
				<ins class="adsbygoogle"
					 style="display:block"
					 data-ad-format="fluid"
					 data-ad-layout-key="-gq-h+1o-38+4u"
					 data-ad-client="ca-pub-8615149435929645"
					 data-ad-slot="6550578909"></ins>
				<script>
					 (adsbygoogle = window.adsbygoogle || []).push({});
				</script>
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
				<!-- Responsive: category/title under -->
				<ins class="adsbygoogle"
					 style="display:block"
					 data-ad-client="ca-pub-8615149435929645"
					 data-ad-slot="9405900214"
					 data-ad-format="auto"
					 data-full-width-responsive="true"></ins>
				<script>
					 (adsbygoogle = window.adsbygoogle || []).push({});
				</script>
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
<?php if($product_id)
{ ?>
<!-- >> Modal -->
<div id="groupOrderModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?php echo $product_title; ?></h4>
			</div>
			<div class="modal-body">
				<?php
					$form = new stdClass;
					$form->product_id = $product_id;
					$form->action = get_the_permalink($page_booknow);
					$form->price_datas = $price_datas;
					$form->product_option = $product_option;
					$post->bookform = $form;
					get_template_part('template-product/book-form');
				?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', 'leo_product') ?></button>
			</div>
		</div>
	</div>
</div><!-- groupOrderModal -->
<?php
} ?>
<?php get_footer(); ?>