<?php
get_header();
$has_widget_right = false;//is_active_sidebar('right');
$has_widget_main_bottom = is_active_sidebar('main_bottom');
?>
<div class="main-container container">
	<main id="main">
		<div class="breadcrumbs clearfix">
			<ol class="breadcrumb" itemprop="breadcrumb">
				<li><a href="<?php echo esc_url(home_url('/')); ?>"><span class="fa fa-home"></span><span class="sr-only"> <?php _e('Home', 'leo_tour') ?></span></a></li>
			</ol>
		</div><!-- breadcrumbs -->
		<div class="row">
			<div class="col-md-10">
				<?php while(have_posts()):
					the_post();
					get_template_part( 'template-parts/content', 'page' );

					if(comments_open() || get_comments_number())
					{
						comments_template();
					}
				?>
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
		<?php if($has_widget_main_bottom): ?>
			<?php dynamic_sidebar('main_bottom'); ?>
		<?php endif ?>
	</main>

	<?php //get_sidebar( 'content-bottom' ); ?>
</div><!-- .container -->

<?php get_footer(); ?>