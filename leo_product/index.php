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
		<?php
		$args = array(
			'posts_per_page' => 3,
		);
		$myposts = get_posts($args);
		foreach($myposts as $k=>$post)
		{
			setup_postdata($post);
		?>
			<div class="item col-xs-12 col-sm-6 col-md-12 col-lg-12 collection">
				<div class="item-inner">
						<h3 class="heading-item"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h3>
					<div class="description">
						<a class="thumb" href="<?php the_permalink() ?>"><?php the_post_thumbnail('large', array('class'=>'resize')); ?></a>
						<?php the_excerpt() ?>
					</div>
				</div>
			</div>
		<?php
		} ?>
</div><!-- featured -->
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
<?php get_footer(); ?>