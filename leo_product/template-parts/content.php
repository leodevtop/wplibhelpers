
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-image post-media">
		<a href="<?php echo get_permalink() ?>" class="thumb"><?php the_post_thumbnail('large', array('class'=>'resizeover', 'data-ratio'=>1.3333)) ?></a>
	</div>
	<h3 class="post-title entry-title">
		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
			<span class="sticky-post badge badge-success"><?php _e( 'Featured', 'leo_product' ); ?></span>
		<?php endif; ?>
		<a rel="bookmark" href="<?php echo get_permalink()  ?>"><?php the_title() ?></a>
	</h3>
	<span class="hidden vcard author"><span class="fn">KhoeDepNhuY.com</span></span>
	<span class="hidden date updated"><?php get_the_modified_time('F j, Y g:i a'); ?></span>
	<?php if ( 'post' == get_post_type() ) : ?>
		<div class="post-meta clearfix">
			<span class="post-date"><?php echo get_the_date(); ?></span><!-- post-date -->
			 &nbsp;|&nbsp; 
			<span class="post-comment"><a href="<?php comments_link(); ?>"><?php comments_number(); ?></a></span>
			 &nbsp;|&nbsp; 
			<span class="post-categories"><?php the_category( __( ', ', 'leo_product' )); ?></span>                  
		</div><!-- postmeta -->
	<?php endif; ?>
	<?php
		edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'leo_product' ),
				get_the_title()
			),
			'<div class="edit-link">',
			'</div>'
		);
	?>
	<!-- entry_meta -->
	<div class="entry-intro">
		<?php
			/* translators: %s: Name of current post */
			the_excerpt( sprintf(
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'leo_product' ),
				get_the_title()
			) );

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'leo_product' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'leo_product' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
	</div><!-- .entry-intro -->
</article><!-- #post-## -->
