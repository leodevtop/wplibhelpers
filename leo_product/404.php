<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

<div class="container">
	<main id="main">
		<div class="main-container">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'leo_product' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'leo_product' ); ?></p>

					<div class="row">
						<div class="col-md-6"><?php get_search_form(); ?></div>
					</div>
				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</div><!-- main-container -->
	</main>

	<?php //get_sidebar( 'content-bottom' ); ?>
</div><!-- .container -->

<?php get_footer(); ?>