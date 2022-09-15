<?php get_header(); ?>
<div class="main-container container">
	<main id="main">
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

				<div class="entry-content">
					Thank you!
				</div><!-- .entry-content -->

			</article><!-- #post-## -->
		<?php endwhile; ?>
	</main>

	<?php //get_sidebar( 'content-bottom' ); ?>
</div><!-- .container --><!-- main-container -->

<?php get_footer(); ?>