<?php
get_header();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>

<main id="main" class="page-products">
	<div class="main-container container">
		<div class="breadcrumbs clearfix">
			<ol class="breadcrumb" itemprop="breadcrumb">
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="fa fa-home"></span><span class="sr-only"> <?php _e('Home', 'leo_product') ?></span></a></li>
				<li><?php _e('Products', 'leo_product') ?></li>
			</ol>
		</div><!-- breadcrumbs -->

		<div class="row">
			<div class="sitemain col-sm-12 col-md-8">
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
						<div class="entry-summary<?php if($paged>1) echo ' hidden'; ?>"><?php the_excerpt() ?></div>
						<div class="product-list">
							<div class="row row-padding-sm row-list table-view">
								<?php
								$args = array(
									'post_type' => 'leo_product',
									'posts_per_page' => 24,
									'paged' => $paged,
									//'order' => 'ASC',
									/*
									'tax_query' => array(
										array(
										'taxonomy' => 'product_category',
										'field' => 'slug',
										'terms' => $term->slug,
										)
									)
									*/
								);
								$Q = new WP_Query($args);
								if($Q->have_posts())
								{
									while($Q->have_posts())
									{
										$Q->the_post();
										$post->thumb_crop = false;
										//$post->thumb_size = 'thumbnail';
										$post->item_class = 'col-xs-6 col-sm-4 col-md-4 col-lg-4';
										get_template_part('template-product/item');
									}
								}
								?>
							</div>
							<?php wp_bootstrap_pagination(array('custom_query'=>$Q));
								wp_reset_postdata(); ?>
						</div>
						<div class="<?php if($paged>1) echo 'hidden'; ?>"><?php the_content() ?></div>
						<div class="text-right hidden">
							<i><?php echo sprintf('<time class="entry-date updated" datetime="%1$s">%2$s</time>', get_the_date('c'), get_the_date()) ?></i>
							<br />
							<strong><span class="vcard author"><span class="fn"><?php the_author() ?></span></span></strong>
						</div>
					</div><!-- entry-content -->

					<!-- QC/social -->
					<div class="has-readmore">
					<!-- comments -->
					<?php
						if(comments_open() || get_comments_number())
						{
							comments_template();
						}
					?>
					</div>
				</article><!-- #post-## -->
				<?php endwhile; ?>
			</div>
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

	</div><!-- main-container -->
</main>

<?php get_footer(); ?>
