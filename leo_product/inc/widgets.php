<?php
/**
 * Extend Recent Posts Widget 
 *
 * Adds different formatting to the default WordPress Recent Posts Widget
 */

Class My_Recent_Posts_Widget extends WP_Widget_Recent_Posts
{
	public function widget($args, $instance)
	{
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Posts' );

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number ) {
			$number = 5;
		}
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		$show_thumb = isset( $instance['show_thumb'] ) ? $instance['show_thumb'] : false;

		$cat_id = isset($instance['cat_id']) ? intval($instance['cat_id']) : 0;

		/**
		 * Filters the arguments for the Recent Posts widget.
		 *
		 * @since 3.4.0
		 * @since 4.9.0 Added the `$instance` parameter.
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args     An array of arguments used to retrieve the recent posts.
		 * @param array $instance Array of settings for the current widget.
		 */
		$myargs = array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
		);
		
		if($cat_id)
		{
			$myargs['cat'] = $cat_id;
		}

		$r = new WP_Query( apply_filters( 'widget_posts_args', $myargs, $instance ) );

		if ( ! $r->have_posts() ) {
			return;
		}
		?>
		<?php echo $args['before_widget']; ?>
		<?php
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		?>
		<ul class="list-unstyled">
			<?php foreach ( $r->posts as $recent_post ) : ?>
				<?php
				$post_title = get_the_title( $recent_post->ID );
				$title      = ( ! empty( $post_title ) ) ? $post_title : __( '(no title)' );
				?>
				<li class="clearfix">
					<?php if ( $show_thumb && has_post_thumbnail($recent_post->ID) ) : ?>
						<a class="thumb" href="<?php the_permalink( $recent_post->ID ); ?>"><?php echo get_the_post_thumbnail($recent_post->ID, 'thumbnail'); ?></a>
					<?php endif; ?>
					<a href="<?php the_permalink( $recent_post->ID ); ?>"><?php echo $title ; ?></a>
					<?php if ( $show_date ) : ?>
						<span class="post-date"><?php echo get_the_date( '', $recent_post->ID ); ?></span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
		echo $args['after_widget'];
	}
	public function update($new_instance, $old_instance)
	{
		$instance = parent::update($new_instance, $old_instance);
		$instance['cat_id'] = (int)$new_instance['cat_id'];
		$instance['show_thumb'] = isset($new_instance['show_thumb'])? (bool)$new_instance['show_thumb'] : false;
		return $instance;
	}
	public function form($instance)
	{
		$show_thumb = isset($instance['show_thumb'])? (bool)$instance['show_thumb'] : false;
		$cat_id = isset($instance['cat_id']) ? intval($instance['cat_id']) : 0;
		$categories = get_categories('parent=0&hide_empty=0');
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'cat_id' ); ?>"><?php _e( 'Category:' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'cat_id' ); ?>" id="<?php echo $this->get_field_id( 'cat_id' ); ?>">
				<option value=""><?php _e( '-Select Category-' ); ?></option>
			<?php foreach($categories as $category)
			{ ?>
				<option value="<?php echo $category->term_id ?>"<?php if($category->term_id == $cat_id) echo ' selected' ?>><?php echo $category->name ?></option>
			<?php
			} ?>
			</select>
		</p>
		<?php
		parent::form($instance);
		?>
		<p><input class="checkbox" type="checkbox"<?php checked( $show_thumb ); ?> id="<?php echo $this->get_field_id( 'show_thumb' ); ?>" name="<?php echo $this->get_field_name( 'show_thumb' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_thumb' ); ?>"><?php _e( 'Display post thumbnail?' ); ?></label></p>
		<?php
	}
}