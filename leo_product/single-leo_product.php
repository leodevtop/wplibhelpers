<?php
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
get_header();
$category = get_the_terms(get_the_ID(), 'product_category');
?>

<main id="main">
	<div class="main-container container">
		<div class="breadcrumbs clearfix">
			<ol class="breadcrumb" itemprop="breadcrumb">
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="fa fa-home"></span></a></li>
				<li typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="<?php echo get_the_permalink($page_products) ?>"><?php _e('Product', 'leo_product') ?></a></li>
				<?php
				if(!empty($category) && !is_wp_error($category))
				{
					foreach($category as $k=>$term)
					{ ?>
					<li typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="<?php echo get_term_link($term) ?>"><?php echo $term->name ?></a></li>
				<?php
					} //foreach
				}//empty($category) ?>
			</ol>
		</div><!-- breadcrumbs -->
		<div class="sidemain">
			<?php
			while(have_posts()):
				the_post();
				$manufacturer = get_the_terms(get_the_ID(), 'manufacturer');
				$category = get_the_terms(get_the_ID(), 'product_category');

				$product_id = get_the_ID();
				$prices = get_post_meta(get_the_ID(), 'price', true);
				$product_title = get_the_title();
				$product_option = get_post_meta(get_the_ID(), 'option', true);
				$publish_up = get_post_meta(get_the_ID(), 'publish_up', true);
				$publish_down = get_post_meta(get_the_ID(), 'publish_down', true);
				$now = current_time('Y-m-d');
			?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('product-single'); //hentry ?>>
					<header class="entry-header">
						<h1 class="page-header entry-title" itemprop="name"><?php the_title(); ?></h1>
						<?php
							edit_post_link( sprintf(__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'leo_product' ), get_the_title()), '<p class="edit-link small">', '</p>' );
						?>
					</header>
					<div class="entry-content">
						<div class="row row-padding-sm row-list product-summary">
							<div class="col-md-5">
								<?php the_post_thumbnail('lagre', array('itemprop'=>'image')); // ?>
							</div>
							<div class="col-md-7">
								<?php if(!empty($category) && !is_wp_error($category))
								{
								?>
								<p class="category tags">
									<?php foreach($category as $k=>$term)
									{ ?>
									<a href="<?php echo get_term_link($term) ?>"><i class="fa fa-angle-left"></i> <?php echo $term->name ?></a>
									<?php
										} //foreach
									?>
								</p>
								<?php
								}//empty($category) ?>
								<?php
								/*
								if($publish_down = get_post_meta(get_the_ID(), 'publish_down', true))
								{ ?>
									<p class="code_date">
										<i class="badge">
											<?php
												the_date();
												if($publish_down) echo ' - '.date_i18n(get_option('date_format'), strtotime( $publish_down));
											?>
										</i>
									</p>
								<?php
								} */ ?>
								<p class="tags">#<?php the_tags('', ', #') ?></p>
								<?php
								if($shelflife = get_post_meta(get_the_ID(), 'shelf-life', true)): ?>
									<p class="shelf-life"><strong><?php _e('Shelf life', 'leo_product') ?></strong>: <?php echo $shelflife ?></p>
								<?php endif ?>
								<div class="intro" itemprop="description"><?php the_excerpt() ?></div>
								<?php if($promotion = get_post_meta(get_the_ID(), 'promotion', true)): ?>
									<p class="promotion"><strong><i class="fa fa-gift"></i> <?php _e('Promotion', 'leo_product') ?></strong>: <?php echo $promotion ?></p>
								<?php endif ?>
								<?php if(get_post_meta(get_the_ID(), 'status', true) == 'out_of_stock'): ?>
									<p class="out-of-stock"><i class="fa fa-exclamation"></i> <?php _e('Out of Stock', 'leo_product') ?></p>
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
								/* //== ORDER OFF
								?>
								<p class="price">
									<?php _e('Price', 'leo_product') ?>:
									<?php
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
													if($count>1) echo '<br />';
													if($price && $price->price)
													{
														echo '<strong>'.number_format($price->price, 0, ',', '.').'</strong><sup itemprop="priceCurrency">'.__('VND', 'leo_product').'</sup><span class="hidden" itemprop="price">'.$price->price.'</span>';
														$price_data->price = number_format($price->price, 0, ',', '.').'<sup itemprop="priceCurrency">'.__('VND', 'leo_product').'</sup>';
													}
													else
													{
														_e('Price Contact', 'leo_product');
													}
													if($price && $price->isoff)
													{
														$price_data->priceoff = ' <span class="base-price"><s>'.number_format($price->base, 0, ',', '.').'</s><sup>'.__('VND', 'leo_product').'</sup></span>';
														echo $price_data->priceoff;
													}
													if($count>1) echo ' <span class="info">'.$price->title.'</span>';
													$price_data->title = $price->title;
													$price_data->info = $price->info;
												} //if(is_array($p))
												$price_datas[] = $price_data;
											} //foreach($prices as $p)
										} //(is_array($prices))
									?>
								</p>
								<?php
								if($publish_down && $publish_down>=$now)
								{ ?>
									<p class="text-danger">
										<i class="fa fa-lg fa-exclamation-circle"></i>
										Chỉ <strong>nhận đặt hàng</strong> trong <?php echo human_time_diff(strtotime($publish_down), current_time('timestamp')); ?> nữa. Hãy nhanh tay!</p>
								<?php
								}
								?>
								<p>
								<button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#groupOrderModal" data-backdrop="static" data-keyboard="false"><?php _e('Order Now', 'leo_product') ?> <i class="fa fa-chevron-right"></i></button>
								&nbsp;
									<i><small>* Chúng tôi sẽ liên hệ lại với bạn!</small></i>
								</p>
								<?php
								if($publish_down && $publish_down<$now)
								{
								?>
									<p class="small"><span class="text-danger"><i class="fa fa-lg fa-exclamation-circle"></i>
									Sản phẩm đã kết thúc <strong>nhận đặt hàng</strong> ngày <strong><?php echo date_i18n('d/m/Y', strtotime( $publish_down)) ?></strong>.
									<br />
									Tuy nhiên nếu bạn quan tâm, bạn hãy cứ đặt hàng, chúng tôi sẽ liên hệ lại với bạn!</span>
									</p>
								<?php
								}
								?>
								<?php //== ORDER OFF */ ?>
								<p class="well well-sm">
									<i class="fa fa-exclamation fa-fw fa-3x fa-pull-left text-warning"></i> khoedepnhuy.com không bán sản phẩm <?php echo get_post_meta(get_the_ID(), 'code', true) ?>. Bạn có nhu cầu về sản phẩm, vui lòng tìm tại các tiệm thuốc trên toàn quốc.
								</p>
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
							</div><!-- entry-overview -->
						</div><!-- .row -->
						<div class="product-content">
							<h2 class="heading"><?php _e('Product Details', 'leo_product') ?> <a href="<?php the_permalink() ?>"><?php echo get_post_meta(get_the_ID(), 'code', true) ?></a></h2>
							<?php the_content() ?>
							<div class="text-right hidden">
								<i><?php leo_the_entry_date() ?></i>
								<br />
								<strong><span class="vcard author"><span class="fn"><?php echo $_SERVER['SERVER_NAME']; //the_author() ?></span></span></strong>
							</div>
						</div><!-- product-content -->
						<p class="well well-sm">
							<i class="fa fa-exclamation fa-fw fa-2x fa-pull-left text-warning"></i> khoedepnhuy.com không bán sản phẩm này. Mua <?php echo get_post_meta(get_the_ID(), 'code', true) ?> ở đâu? Bạn hãy tìm hiểu các thông tin bên dưới!
						</p>
						<h5 class="heading">Quảng cáo</h5>
						<!-- Responsive: footer content -->
						<ins class="adsbygoogle"
							 style="display:block"
							 data-ad-client="ca-pub-8615149435929645"
							 data-ad-slot="3359366610"
							 data-ad-format="auto"
							 data-full-width-responsive="true"></ins>
						<script>
							 (adsbygoogle = window.adsbygoogle || []).push({});
						</script>
						<!--<p class="text-center"><button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#groupOrderModal" data-backdrop="static" data-keyboard="false"><i class="fa fa-chevron-right"></i> <?php _e('Order Now', 'leo_product') ?> <strong><?php echo get_post_meta(get_the_ID(), 'code', true) ?></strong></button></p>-->
					</div><!-- entry-content -->
				</article><!-- #post-## -->
			<?php endwhile; ?>
		</div><!-- sitemain -->
		<div class="row">
			<div class="other-products col-xs-12 col-sm-12 col-md-8 col-lg-8">
				<h2 class="heading"><?php _e('Other Products', 'leo_product') ?> <small><a class="btn" href="<?php echo get_the_permalink($page_products) ?>"><i class="fa fa-chevron-right"></i> <?php _e('More', 'leo_product') ?></a></small></h2>
				<div class="row row-list table-view product-list">
					<?php
					$args = array(
						'post_type' => 'leo_product',
						'showposts' => 6,
						'post__not_in' => array(get_the_ID()),
						/*
						'tax_query' => array(
							array(
							'taxonomy' => 'product_category',
							'field' => 'id',
							'terms' => $terms_id,
							)
						)
						*/
					);
					$myposts = get_posts($args);  
					foreach($myposts as $k=>$post)
					{
						$post->thumb_crop = false;
						$post->thumb_size = 'thumbnail';
						$post->item_class = 'col-xs-6 col-sm-4 col-md-4 col-lg-4';
						get_template_part('template-product/item');
					}
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
			</div>
			<div class="post-list col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<h2 class="heading"><?php _e('Posts', 'leo_product'); ?></h2>
				<div class="row row-padding-sm row-list">
					<?php
					$args = array(
						'post_type' => 'post',
						'showposts' => 4,
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
	</div><!-- main-container -->
</main>

<!-- >> Modal -->
<div id="groupOrderModal1" class="modal fade" role="dialog">
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
					echo get_template_part('template-product/book-form');
				?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', 'leo_product') ?></button>
			</div>
		</div>
	</div>
</div><!-- groupOrderModal -->
<?php get_footer(); ?>