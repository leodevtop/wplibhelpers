<?php get_header(); ?>
<?php
	if($product_id = get_post_meta(get_the_ID(), 'ref_post_id', true))
	{
		$product = get_post($product_id);
		$product_code = get_post_meta($product_id, 'code', true);
		$product_title = $product_code? $product_code : get_the_title($product);
	}
?>
<div class="main-container container">
	<main id="main">
		<div class="breadcrumbs clearfix">
			<ol class="breadcrumb" itemprop="breadcrumb">
				<li><a href="<?php echo esc_url(home_url('/')); ?>"><span class="fa fa-home"></span><span class="sr-only"> <?php _e('Home', 'leo_tour') ?></span></a></li>
				<li typeof="v:Breadcrumb"><a href="<?php echo get_permalink($product) ?>#faqs" rel="v:url" property="v:title"><?php echo 'Hỏi về sản phẩm '. $product_title ?></a></li>
			</ol>
		</div><!-- breadcrumbs -->
		<?php while(have_posts()):
			the_post();
		?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<h1 class="page-header entry-title"><?php the_title(); ?></h1>
					<?php
						edit_post_link(
							sprintf(
								/* translators: %s: Name of current post */
								' '.__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'leo_product' ),
								get_the_title()
							),
							'<p class="edit-link small">',
							'</p>'
						);
					?>
					<span class="hidden vcard author"><span class="fn">KhoeDepNhuY.com</span></span>
					<span class="hidden date updated"><?php get_the_modified_time('F j, Y g:i a'); ?></span>
				</header><!-- .entry-header -->

				<?php if($post->post_excerpt): ?>
				<div class="entry-sumary">
					<?php the_excerpt(); ?>
				</div><!-- .entry-sumary -->
				<?php endif ?>

				<div class="product clearfix">
					<strong>Thông tin sản phẩm:</strong>
					<?php
						$prices = get_post_meta($product_id, 'price', true);
						$price = new stdClass();
						if(is_array($prices))
						{
							foreach($prices as $p)
							{
								if(is_array($p))
								{
									$price->base = intval($p['price']);
									$price->off = intval($p['price_safeoff']);
									$price->isoff = $price->base && $price->off && ($price->base-$price->off>0);
									$price->price = $price->isoff? $price->off : $price->base;
								}
								break;
							}
						}
					?>
					<?php echo get_the_excerpt($product); ?>
						-
						<span class="price text-norwap">
							<?php if(isset($price->price) && $price->price) echo '<strong>'.number_format($price->price, 0, ',', '.').'</strong><sup>'.__('VND', 'leo_product').'</sup>'; else _e('Price Contact', 'leo_product'); ?>
							<?php if(isset($price->isoff) && $price->isoff) echo ' <s>'.number_format($price->base, 0, ',', '.').'</s><sup>'.__('VND', 'leo_product').'</sup>' ?>
						</span>
						<?php if($promotion = get_post_meta($product_id, 'promotion', true)): ?>
							<span class="promotion"><i class="fa fa-gift"></i> <small><?php echo $promotion ?></small></span>
						<?php endif ?>
						&nbsp; <a class="text-norwap" href="<?php echo get_permalink($product) ?>"><i class="fa fa-angle-right"></i> <?php _e('Xem và mua ', 'leo_product'); echo $product_title; ?></a>
				</div>

				<div class="entry-content">
					<?php the_content(); ?>
				</div><!-- .entry-content -->
				<div class="post-meta clearfix">
					<div class="post-tags"><?php the_tags(__('Tags: ', 'leo_product')); ?> </div>
				</div><!-- postmeta -->

				<footer class="entry-footer">
				</footer><!-- .entry-footer -->
			</article><!-- #post-## -->
			<h3 class="heading"><?php _e('Câu hỏi khác về sản phẩm '.$product_title, 'leo_product') ?></h3>
			<div class="row row-list post-list">
				<?php
					$args = array(
						'post_type' => 'leo_faq',
						'posts_per_page' => -1,
						'post__not_in' => array(get_the_ID()),
						'meta_query' => array(
							array(
								'key' => 'ref_post_id',
								'value' => $product_id,
							),
						),
					);
					$myposts = get_posts($args);
					foreach($myposts as $l=>$post):
						setup_postdata($post);
				?>
					<div class="item col-lg-6 clearfix">
						<div class="item-inner">
							<h5><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h5>
						</div>
					</div>
				<?php endforeach;
					wp_reset_postdata();
				?>
			</div><!-- articles-list -->
		<?php endwhile; ?>
	</main>

	<?php //get_sidebar( 'content-bottom' ); ?>
</div><!-- .container -->

<?php get_footer(); ?>