
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="page-header entry-title"><?php the_title(); ?></h1>
		<?php
			edit_post_link( sprintf(__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'leo_product' ), get_the_title()), '<p class="edit-link small">', '</p>' );
		?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->
	<div class="post-meta clearfix">
		<div class="text-right hidden">
			<i><?php leo_the_entry_date() ?></i>
			<br />
			<strong><span class="vcard author"><span class="fn">KhoeDepNhuY.com<?php //the_author() ?></span></span></strong>
		</div>
		<div class="post-tags"><?php the_tags(__('Tags: ', 'leo_product')); ?> </div>
	</div><!-- postmeta -->

	<footer class="entry-footer">
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->