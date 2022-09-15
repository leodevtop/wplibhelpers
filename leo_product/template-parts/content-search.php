<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="item-image post-media">
		<a href="<?php echo get_permalink()  ?>"><?php the_post_thumbnail() ?></a>
	</div>
	<h3 class="post-title entry-title">
		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
			<span class="sticky-post"><?php _e( 'Featured', 'leo_product' ); ?></span>
		<?php endif; ?>
		<a rel="bookmark" href="<?php echo get_permalink()  ?>"><?php the_title() ?></a>
	</h3>
	<?php
		edit_post_link(
			__( '[Edit]', 'leo_product' ),
			'<span class="edit-link">',
			'</span>'
		);
	?>
	<!-- entry_meta -->
	<div class="entry-content">
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
	</div><!-- .entry-content -->
</article><!-- #post-## -->
