<?php get_header(); ?>
<div class="main-container container">
	<main id="main">
		<div class="breadcrumbs clearfix">
			<ol class="breadcrumb" itemprop="breadcrumb">
				<li><a href="<?php echo esc_url(home_url('/')); ?>"><span class="fa fa-home"></span><span class="sr-only"> <?php _e('Home', 'leo_tour') ?></span></a></li>
				<?php
				$categories = get_the_category();
				if(!empty($categories)):
					foreach($categories as $cat):
				?>
					<li typeof="v:Breadcrumb"><a href="<?php echo esc_url(get_category_link($cat->term_id)) ?>" rel="v:url" property="v:title"><?php echo esc_html($cat->name) ?></a></li>
				<?php endforeach;
				endif; ?>
			</ol>
		</div><!-- breadcrumbs -->
		<div class="row">
			<div class="col-md-10">
				<?php while(have_posts()):
					the_post();
				?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<h1 class="page-header entry-title"><?php the_title(); ?></h1>
							<?php
								edit_post_link( sprintf(__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'leo_product' ), get_the_title()), '<p class="edit-link small">', '</p>' );
							?>
						</header><!-- .entry-header -->

						<div class="entry-content">
							<!-- Responsive: category/title under -->
							<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
							<ins class="adsbygoogle"
								 style="display:block"
								 data-ad-client="ca-pub-8615149435929645"
								 data-ad-slot="9405900214"
								 data-ad-format="auto"
								 data-full-width-responsive="true"></ins>
							<script>
								 (adsbygoogle = window.adsbygoogle || []).push({});
							</script>
							<?php the_content(); ?>
							<ins class="adsbygoogle"
								 style="display:block"
								 data-ad-format="fluid"
								 data-ad-layout-key="-gq-h+1o-38+4u"
								 data-ad-client="ca-pub-8615149435929645"
								 data-ad-slot="6550578909"></ins>
							<script>
								 (adsbygoogle = window.adsbygoogle || []).push({});
							</script>
						</div><!-- .entry-content -->
						<div class="post-meta clearfix">
							<div class="text-right">
								<i><?php leo_the_entry_date() ?></i>
								<br />
								<strong><span class="vcard author"><span class="fn"><?php the_author() ?></span></span></strong>
							</div>
							<div class="post-tags"><?php //the_tags(__('Tags: ', 'leo_product')); ?> </div>
						</div><!-- postmeta -->

						<footer class="entry-footer">
						</footer><!-- .entry-footer -->
					</article><!-- #post-## -->
					<h3 class="heading"><?php _e('Bài khác', 'leo_product') ?></h3>
					<div class="row row-list post-list">
						<?php
							$args = array(
								'post_type' => 'post',
								'showposts' => 6,
								//'order' => 'ASC',
								'post__not_in' => array(get_the_ID()),
							);
							$myposts = get_posts($args);
							foreach($myposts as $l=>$post):
								setup_postdata($post);
						?>
							<div class="item col-sm-6 col-md-6 col-lg-6 clearfix collection">
								<div class="item-inner">
										<h3 class="heading-item" style="margin-top: 0"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h3>
									<div class="description">
										<a class="thumb thumb30" href="<?php the_permalink() ?>"><?php the_post_thumbnail('thumbnail', array('class'=>'resizeover', 'data-ratio'=>1.33334)); ?></a>
										<?php the_excerpt() ?>
									</div>
								</div>
							</div>
						<?php endforeach;
							wp_reset_postdata();
						?>
					</div><!-- articles-list -->
				<?php endwhile; ?>
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
			</div>
			<div class="product-list col-md-2">
				<h2 class="heading"><?php echo _e('Products', 'leo_product') ?></h2>
				<div class="row row-padding-sm row-list table-view">
					<?php
					$args = array(
						'post_type' => 'leo_product',
						'showposts' => 6,
					);
					$myposts = get_posts($args);
					foreach($myposts as $k=>$post)
					{
						setup_postdata($post);
						$post->thumb_crop = false;
						//$post->thumb_size = 'thumbnail';
						$post->item_class = 'col-xs-6 col-sm-4 col-md-12 col-lg-12';
						get_template_part('template-product/item');
					}
					wp_reset_postdata(); ?>
				</div>
			</div><!-- product-list -->
		</div><!-- main-container -->
	</main>

	<?php //get_sidebar( 'content-bottom' ); ?>
</div><!-- .container -->

<?php get_footer(); ?>