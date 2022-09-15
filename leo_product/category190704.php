<?php get_header(); ?>

<div class="main-container container">
	<main id="main">
		<div class="breadcrumbs clearfix">
			<ol class="breadcrumb" itemprop="breadcrumb">
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="fa fa-home"></span><span class="sr-only"> <?php _e('Home', 'leo_product') ?></span></a></li>
				<li><?php echo single_cat_title('', false) ?></li>
			</ol>
		</div><!-- breadcrumbs -->
		<div class="row">
			<div class="col-md-10">
				<div class="heading_page">
					<h1 class="page-header"><?php echo single_cat_title('', false) ?></h1>
					<div class="description hidden"><?php the_archive_description(); ?></div>
				</div><!-- .page-header -->

				<?php if(have_posts()): ?>
					<?php
					global $more;
					$flag = false;
					while(have_posts()): the_post();
						if(!$flag):
							$more = 1;
					?>
					<div class="item-lead clearfix">
						<h2><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
						<?php
							the_content();
							$flag = true;
						?>
					</div>
						<div class="row row-list post-list">
						<?php else: // flag ?>
							<div class="item col-sm-6 col-md-6 col-lg-6 clearfix collection">
								<div class="item-inner">
										<h3 class="heading-item" style="margin-top: 0"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h3>
									<div class="description">
										<a class="thumb thumb30" href="<?php the_permalink() ?>"><?php the_post_thumbnail('thumbnail', array('class'=>'resizeover', 'data-ratio'=>1.33334)); ?></a>
										<?php the_excerpt() ?>
									</div>
								</div>
							</div>
						<?php endif // flag ?>
					<?php endwhile; ?>
						</div><!-- post-list -->
					<?php wp_bootstrap_pagination() ?>
				<?php endif; ?>
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
