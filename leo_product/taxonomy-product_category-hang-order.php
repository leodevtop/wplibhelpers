<?php
get_header();
//$has_widget_right = is_active_sidebar('right');
//$has_widget_main_bottom = is_active_sidebar('main_bottom');
if($page_products = get_page_by_path('san-pham'))
{
	if(function_exists('pll_get_post'))
	{
		$page_products = pll_get_post($page_products->ID);
	}
}
$this_term = get_queried_object();
?>

<main id="main">
	<div class="main-container container">
		<div class="breadcrumbs clearfix">
			<ol class="breadcrumb" itemprop="breadcrumb">
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="v:url" property="v:title"><span class="fa fa-home"></span><span class="sr-only"> <?php _e('Home', 'leo_product') ?></span></a></li>
				<li typeof="v:Breadcrumb"><a href="<?php echo get_the_permalink($page_products) ?>" rel="v:url" property="v:title"><?php _e('Product', 'leo_product') ?></a></li>
				<?php if($this_term->parent):
					$this_term_parent = get_term($this_term->parent, 'product_category'); ?>
					<li typeof="v:Breadcrumb"><a href="<?php echo esc_url(get_term_link($this_term_parent)) ?>" rel="v:url" property="v:title"><?php echo $this_term_parent->name; ?></a></li>
				<?php endif ?>
			</ol>
		</div><!-- breadcrumbs -->

		<div class="row">
			<div class="sidemain col-sm-12 col-md-8">
				<h1 class="page-header entry-title"><?php echo single_cat_title('', false) ?></h1><!-- .page-header -->
				<div class="meta hidden"><span class="author"></span></div>
				<div class="entry-excerpt has-readmore"><?php echo term_description();?></div>
				<div class="product-list">
					<h2 class="heading"><?php echo _e('Products', 'leo_product') ?></h2>
					<div class="row row-padding-sm row-list table-view">
						<?php
						$args = array(
							'post_type' => 'leo_product',
							'paged' => $paged,
							'order' => 'ASC',
							'tax_query' => array(
								array(
								'taxonomy' => 'product_category',
								'field' => 'slug',
								'terms' => $this_term->slug,
								)
							),
						);
						$myposts = new WP_Query($args);
						if($myposts->have_posts())
						{
							while($myposts->have_posts())
							{
								$myposts->the_post();
								$post->thumb_crop = false;
								//$post->thumb_size = 'thumbnail';
								$post->item_class = 'col-xs-6 col-sm-4 col-md-4 col-lg-4';
								get_template_part('template-product/item');
							}
							wp_reset_postdata();
						}
						?>
					</div>
					<?php wp_bootstrap_pagination(array('custom_query'=>$myposts)) ?>
				</div><!-- product-list -->

				<div class="entry-content"><?php // get post; ?></div>

				<!-- QC/social -->
				<div id="comments has-readmore"><!-- comments --></div>

				<div class="post-list">
					<h2 class="heading"><?php echo _e('Posts', 'leo_product') ?></h2>
					<div class="row row-padding-sm row-list">
						<?php
						$args = array(
							//'post_type' => 'tict_product',
							'showposts' => 6,
							//'order' => 'ASC',
							'tax_query' => array(
								array(
								'taxonomy' => 'product_category',
								'field' => 'slug',
								'terms' => $this_term->slug,
								)
							),
						);
						$myposts = get_posts($args);
						$myposts = array_merge($myposts,$myposts,$myposts);
						foreach($myposts as $k=>$post):
							setup_postdata($post);?>
						<div class="item col-xs-6 col-sm-6 col-md-4 col-lg-4 clearfix">
							<div class="item-inner">
								<a class="thumb thumb30" href="<?php the_permalink() ?>"><?php the_post_thumbnail('thumbnail', array('class'=>'resizeover', 'data-ratio'=>1.3334)); ?></a>
								<div class="description">
									<h4><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h4>
								</div>
							</div>
						</div>
						<?php endforeach;
						wp_reset_postdata(); ?>
					</div>
				</div><!-- post-list -->
			</div>
			<div class="post-list item col-xs-12 col-sm-12 col-md-4 col-lg-4">
			<?php
				$categories = get_categories('orderby=id&parent=0');
				$i = 0;
				foreach($categories as $k=>$term):
					if(!$k) continue;
					$i++;
			?>
				<h2 class="heading"><a href="<?php echo get_term_link($term) ?>"></a><?php if($i==1) echo __('Posts', 'leo_product').' '; ?><?php echo $term->name ?></h2>
				<div class="row row-padding-sm row-list">
					<?php
					$args = array(
						'post_type' => 'post',
						'showposts' => 6,
						//'order' => 'ASC',
						'category' =>$term->term_id,
					);
					$myposts = get_posts($args);
					foreach($myposts as $l=>$post):
						setup_postdata($post);
						if($l<2): ?>
					<div class="item col-xs-6 col-sm-6 col-md-12 col-lg-12 clearfix collection">
						<div class="item-inner">
							<a class="thumb thumb30" href="<?php the_permalink() ?>"><?php the_post_thumbnail('thumbnail', array('class'=>'resizeover', 'data-ratio'=>1.77778)); ?></a>
							<div class="description">
								<h4 style="margin-top: 0"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h4>
								<?php the_excerpt() ?>
							</div>
						</div>
					</div>
					<?php else: //$l ?>
						<div class="item col-xs-6 col-sm-6 col-md-12 col-lg-6 clearfix" style="margin: 0">
							<div class="item-inner">
								<div class="description">
									<h4><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h4>
								</div>
							</div>
						</div>
					<?php
						endif; //$l
					endforeach;
					wp_reset_postdata(); ?>
				</div>
			<?php endforeach; //$categories ?>
			</div><!-- post-list -->
		</div>

	</div><!-- main-container -->
</main>

<?php get_footer(); ?>
