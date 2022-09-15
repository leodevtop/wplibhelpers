<?php

function searchfilter($query) {
	if($query->is_search && !is_admin()) {
		if(isset($_GET['type'])) {
			$type = $_GET['type'];
				if($type == 'product') {
					$query->set('post_type', array('product'));
				}
		}	   
	}
return $query;
}
add_filter('pre_get_posts','searchfilter');


if(!function_exists('_postdata'))
{
	function _postdata($key, $val='')
	{
		return(isset($_POST[$key])? $_POST[$key] : $val);
	}
}

if(!function_exists('_getdata'))
{
	function _getdata($key, $val='')
	{
		return(isset($_GET[$key])? $_GET[$key] : $val);
	}
}

if(!function_exists('_replace'))
{
	function _replace($txt, $search=array())
	{
		foreach($search as $k=>$v)
		{
			$txt = str_replace($k,$v,$txt);
		}
		return $txt;
	}
}

if(!function_exists('_trim_chars'))
{
	function _trim_chars($str, $n=20, $sep='...')
	{
		if(strlen($str)<$n) return $str;
		$html = substr($str,0,$n);
		$html = substr($html,0,strrpos($html,' '));
		return $html.$sep;
	} 
}


if(!function_exists('wpdocs_filter_gallery_img_atts'))
{
	function wpdocs_filter_gallery_img_atts($atts, $attachment)
	{
		if(isset($atts['class']) && $atts['class'])
		{
			$c = explode(" ", $atts['class']);
			if(in_array('lazy', $c))
			{
				if(isset($atts['src']))
				{
					$atts['data-src'] = $atts['src'];
					$atts['src'] = 'data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=';
					//unset($atts['src']);
				}
				if(isset($atts['srcset']))
				{
					$atts['data-srcset'] = $atts['srcset'];
					unset($atts['srcset']);
				}
			}
		}
		if(!isset($atts['alt']) || $atts['alt'] == '')
		{
			$atts['alt'] = esc_attr($attachment->post_title);
			$atts['alt'] = preg_replace('%\s*[-_\s]+\s*%', ' ', $atts['alt']);
		}
		return $atts;
	}
	add_filter('wp_get_attachment_image_attributes', 'wpdocs_filter_gallery_img_atts', 10, 2);
}

/* ================== */

if(!function_exists('leo_the_entry_date'))
{
	function leo_the_entry_date()
	{
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if(get_the_time('U') !== get_the_modified_time('U'))
		{
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated hidden" datetime="%3$s">%4$s</time>';
		}

		printf($time_string,
			esc_attr(get_the_date('c')),
			get_the_date(),
			esc_attr(get_the_modified_date('c')),
			get_the_modified_date()
		);
	}
}

if(!function_exists('tict_the_get_thumbnail_by_url'))
{
	function tict_the_get_thumbnail_by_url($img_url, $size = 'post-thumbnail', $attr = '')
	{
		echo tict_get_thumbnail_by_url($img_url, $size, $attr);
	}
}
if(!function_exists('tict_image_downsize_by_url'))
{
	function tict_image_downsize_by_url($img_url, $size = 'thumbnail')
	{
		global $wpdb;
		$Q = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", $img_url);
		$attachment_id = $wpdb->get_var($Q);
		if(empty($attachment_id) || !$attachment_id)
		{
			return '';
		}
		return image_downsize($attachment_id, $size);
	}
}
if(!function_exists('tict_get_thumbnail_by_url'))
{
	function tict_get_thumbnail_by_url($img_url, $size = 'post-thumbnail', $attr = '')
	{
		/**
		 * TUAN
		 * Get post_thumbnail_id by image url
		 *
		 * @param string|array $size The post thumbnail size. Image size or array of width and height
		 *                           values (in that order). Default 'post-thumbnail'.
		 */
		global $wpdb;
		$Q = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid = %s", $img_url);
		$post_thumbnail_id = $wpdb->get_var($Q);
		if(empty($post_thumbnail_id) || !$post_thumbnail_id)
		{
			return '';
		}
	 
		/**
		 * Filters the post thumbnail size.
		 *
		 * @since 2.9.0
		 *
		 * @param string|array $size The post thumbnail size. Image size or array of width and height
		 *                           values (in that order). Default 'post-thumbnail'.
		 */
		$size = apply_filters('post_thumbnail_size', $size);
	 
		if($post_thumbnail_id) {
	 
			/**
			 * Fires before fetching the post thumbnail HTML.
			 *
			 * Provides "just in time" filtering of all filters in wp_get_attachment_image().
			 *
			 * @since 2.9.0
			 *
			 * @param int          $post_id           The post ID.
			 * @param string       $post_thumbnail_id The post thumbnail ID.
			 * @param string|array $size              The post thumbnail size. Image size or array of width
			 *                                        and height values (in that order). Default 'post-thumbnail'.
			 */
			//do_action('begin_fetch_post_thumbnail_html', $post->ID, $post_thumbnail_id, $size);
			//do_action('begin_fetch_post_thumbnail_html', $post_thumbnail_id, $post_thumbnail_id, $size);
			//if(in_the_loop())
			//	update_post_thumbnail_cache();
			$html = wp_get_attachment_image($post_thumbnail_id, $size, false, $attr);
	 
			/**
			 * Fires after fetching the post thumbnail HTML.
			 *
			 * @since 2.9.0
			 *
			 * @param int          $post_id           The post ID.
			 * @param string       $post_thumbnail_id The post thumbnail ID.
			 * @param string|array $size              The post thumbnail size. Image size or array of width
			 *                                        and height values (in that order). Default 'post-thumbnail'.
			 */
			//do_action('end_fetch_post_thumbnail_html', $post->ID, $post_thumbnail_id, $size);
			//do_action('end_fetch_post_thumbnail_html', $post_thumbnail_id, $post_thumbnail_id, $size);
	 
		} else {
			$html = '';
		}
		/**
		 * Filters the post thumbnail HTML.
		 *
		 * @since 2.9.0
		 *
		 * @param string       $html              The post thumbnail HTML.
		 * @param int          $post_id           The post ID.
		 * @param string       $post_thumbnail_id The post thumbnail ID.
		 * @param string|array $size              The post thumbnail size. Image size or array of width and height
		 *                                        values (in that order). Default 'post-thumbnail'.
		 * @param string       $attr              Query string of attributes.
		 */
		//return apply_filters('post_thumbnail_html', $html, $post->ID, $post_thumbnail_id, $size, $attr);
		return $html;
	}
}
if(!function_exists('leo_bootstrap_pagination'))
{
	function leo_bootstrap_pagination($args = array())
	{
		$defaults = array(
			'range'		   => 4,
			'custom_query'	=> FALSE,
			'before_output'   => '<nav class="paginations"><ul class="pagination">',
			'after_output'	=> '</ul></nav>'
		);
		
		$args = wp_parse_args(
			$args, 
			apply_filters('wp_bootstrap_pagination_defaults', $defaults)
		);
		
		$args['range'] =(int) $args['range'] - 1;
		if(!$args['custom_query'])
			$args['custom_query'] = @$GLOBALS['wp_query'];
		$count =(int) $args['custom_query']->max_num_pages;
		$page  = intval(get_query_var('paged'));
		$ceil  = ceil($args['range'] / 2);
		
		if($count <= 1)
			return FALSE;
		
		if(!$page)
			$page = 1;
		
		if($count > $args['range']) {
			if($page <= $args['range']) {
				$min = 1;
				$max = $args['range'] + 1;
			} elseif($page >=($count - $ceil)) {
				$min = $count - $args['range'];
				$max = $count;
			} elseif($page >= $args['range'] && $page <($count - $ceil)) {
				$min = $page - $ceil;
				$max = $page + $ceil;
			}
		} else {
			$min = 1;
			$max = $count;
		}
		
		$echo = '';
		$previous = intval($page) - 1;
		$previous = esc_attr(get_pagenum_link($previous));
		
		$firstpage = esc_attr(get_pagenum_link(1));
		if($firstpage &&(1 != $page))
			$echo .= '<li class="previous"><a href="'.$firstpage.'">'.__('First', 'leo_restaurant').'</a></li>';
		if($previous &&(1 != $page))
			$echo .= '<li><a href="'.$previous.'" title="'.__('&laquo; Previous', 'leo_restaurant').'">'.__('Previous', 'leo_restaurant').'</a></li>';
		
		if(!empty($min) && !empty($max)) {
			for($i = $min; $i <= $max; $i++) {
				if($page == $i) {
					$echo .= '<li class="active"><span class="active">'.(int)$i.'<span class="sr-only">(current)</span></span></li>';
				} else {
					$echo .= sprintf('<li><a href="%s">%d</a></li>', esc_attr(get_pagenum_link($i)), $i);
				}
			}
		}
		
		$next = intval($page) + 1;
		$next = esc_attr(get_pagenum_link($next));
		if($next &&($count != $page))
			$echo .= '<li><a href="'.$next.'" title="'.__('Next', 'leo_restaurant').'">'.__('Next &raquo;', 'leo_restaurant').'</a></li>';
		
		$lastpage = esc_attr(get_pagenum_link($count));
		if($lastpage) {
			$echo .= '<li class="next"><a href="'.$lastpage.'">'.__('Last', 'leo_restaurant').'</a></li>';
		}
		if(isset($echo))
			echo $args['before_output'].$echo.$args['after_output'];
	}
}

if(!function_exists('leo_save_meta'))
{
	function leo_save_meta($post_id, $field, $type = 'text')
	{
		switch($type)
		{
			case 'int':
				$new_meta_value =(isset($_POST[$field]) ? intval($_POST[$field]) : null);
				break;
			case 'textarea':
				$new_meta_value =(isset($_POST[$field]) ? wp_kses_post($_POST[$field]) : null);
				break;
			case 'object':
				$new_meta_value =(isset($_POST[$field]) ?  $_POST[$field] : null);
				break;
			default:
				$new_meta_value =(isset($_POST[$field]) ? sanitize_text_field($_POST[$field]) : null);
				break;
		}
		/* Get the meta key. */
		$meta_key = $field;
		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta($post_id, $meta_key, true);
		update_post_meta($post_id, $meta_key, $new_meta_value);

		return $post_id;

		/* If a new meta value was added and there was no previous value, add it. */
		if(!$post_id)
			add_post_meta($post_id, $meta_key, $new_meta_value, true);
			/* If the new meta value does not match the old value, update it. */
		elseif($new_meta_value && $new_meta_value != $meta_value)
			update_post_meta($post_id, $meta_key, $new_meta_value);
			/* If there is no new meta value but an old value exists, delete it. */
		//elseif(null == $new_meta_value && $meta_value)
		//	delete_post_meta($post_id, $meta_key, $meta_value);	  
	}
}

/* =========Change WP========= 
function _add_async_defer_attribute($tag, $handle)
{
	if(in_array($handle, array('jquery-core', 'jquery-migrate', 'jquery-ui-datepicker')))
	{
		return $tag;
	}
	return str_replace(' src', ' async defer src', $tag);
}
add_filter('script_loader_tag', '_add_async_defer_attribute', 10, 2);

*/

/*================= COMMENT_META =====================*/

//Create the rating interface.
add_action('comment_form_logged_in_after', 'leo_comment_rating_field');
add_action('comment_form_before_fields', 'leo_comment_rating_field');
function leo_comment_rating_field()
{
	$post_type = get_post_type();
	$post_type_allow = array('product');
	if(in_array($post_type, $post_type_allow))
	{
	?>
		<label for="rating"><?php _e('Rating') ?></label>
		<fieldset class="comments-rating">
			<p class="rating clearfix">
				<?php for($i=5; $i>0; $i--)
				{ ?>
				<input type="radio" id="rating-<?php echo esc_attr($i); ?>" name="rating" value="<?php echo esc_attr($i); ?>" /> <label for="rating-<?php echo esc_attr($i); ?>" title="<?php echo esc_attr($i); ?>" class="has-tips"></label>
				<?php
				} ?>
			</p>
		</fieldset>
	<?php
	} //if($post_type_allow)
}
//Save the rating submitted by the user.
add_action('comment_post', 'leo_comment_rating_save_comment_rating');
function leo_comment_rating_save_comment_rating($comment_id)
{
	if((isset($_POST['rating'])) && ('' !== $_POST['rating']))
	{
		$rating = intval($_POST['rating']);
	}
	add_comment_meta($comment_id, 'rating', $rating);
}

//Make the rating required.
add_filter('preprocess_comment', 'leo_comment_rating_require_rating');
function leo_comment_rating_require_rating($commentdata)
{
	//if(!is_admin() && (!isset($_POST['rating']) || 0 === intval($_POST['rating'])))
	//wp_die(__('Error: You did not add a rating. Hit the Back button on your Web browser and resubmit your comment with a rating.'));
	return $commentdata;
}
//Display the rating on a submitted comment.
add_filter('comment_text', 'leo_comment_rating_display_rating');
function leo_comment_rating_display_rating($comment_text){

	if($rating = get_comment_meta(get_comment_ID(), 'rating', true))
	{
		$stars = '<p class="rates">';
		for ($i=1; $i<=5; $i++)
		{
			if($rating-$i>=0)
			{
				if(is_admin())
				{
					$stars .= ' <i class="dashicons dashicons-star-filled rated"></i>';
				}
				else
				{
					$stars .= ' <i class="fa fa-star rated"></i>';
				}
			}
			else
			{
				if(is_admin())
				{
					$stars .= ' <i class="dashicons dashicons-star-empty rated"></i>';
				}
				else
				{
					$stars .= ' <i class="fa fa-star-o rated"></i>';
				}
			}
		}
		$stars .= '</p>';
		$comment_text = $stars.$comment_text;
		return $comment_text;
	}
	else {
		return $comment_text;
	}
}
//Display the average rating above the content.
function leo_average_rating($post=false, $display = false)
{
	if(!$post)
	{
		global $post;
	}
	$comments = get_approved_comments($post->ID);
	$average = false;
	$rated = 0;
	if($comments)
	{
		$total = 0;
		foreach($comments as $comment)
		{
			$rate = get_comment_meta($comment->comment_ID, 'rating', true);
			if(isset($rate) && '' !== $rate)
			{
				$total += $rate;
				$rated++;
			}
		}
		if(0 ==!$rated)
		{
			$average = number_format_i18n($total/$rated, 1);
		}
	}

	if(false === $average) {
		return $content;
	}
	
	$stars   = '';

	for ($i=1; $i<=5; $i++)
	{
		if($average-$i>=0)
		{
			$stars .= ' <i class="fa fa-star rated"></i>';
		}
		elseif($average-$i<0 && $average-$i+1>0)
		{
			$stars .= ' <i class="fa fa-star-half-o rated"></i>';
		}
		else
		{
			$stars .= ' <i class="fa fa-star-o rated"></i>';
		}
	}
	$custom_content = '<span class="average-rating">'.$stars.' '.$average.'/5 - '.$rated.' votes</span>';
	if($display) echo $custom_content;
	else return $custom_content;
}
/*
add_filter('the_content', 'leo_comment_rating_display_average_rating');
function leo_comment_rating_display_average_rating($content)
{
	$custom_content = leo_average_rating();
	return $custom_content.$content;
}
*/
/*================= // COMMENT_META =====================*/

/*================= TERM_META =====================*/
function leo_term_meta_add_new_meta_field($term = false)
{
	wp_enqueue_media();
	// this will add the custom meta field to the add new term page
	?>
	<div class="form-field">
		<label for="_term_thumbnail_id"><?php _e('Featured Image'); ?></label>
		<?php
		$term_thumbnail_id = -1;
		$html = __('Set featured image', 'leo_plg_restaurant');
		if($term && is_object($term))
		{
			if($_term_thumbnail_id = get_term_meta($term->term_id, '_term_thumbnail_id', true))
			{
				$term_thumbnail_id = $_term_thumbnail_id;
				$html = wp_get_attachment_image($term_thumbnail_id, 'thumbnail', false);
			}
		}
		?>
		<p class="hide-if-no-js">
			<a href="#" class="set_term_thumbnail"><?php echo $html ?></a>
		</p>
		<p class="hide-if-no-js">
			<span <?php if($term_thumbnail_id==-1) echo 'style="display: none"' ?> id="remove_term_thumbnail">
				<span class="howto" id="set-term-thumbnail-desc"><?php _e('Click the image to edit or update'); ?></span>
				<a href="#"><?php _e('Remove featured image'); ?></a>
			</span>
			<input type="hidden" class="_term_thumbnail_id" name="term_meta[_term_thumbnail_id]" id="_term_thumbnail_id" value="<?php echo $term_thumbnail_id ?>" />
		</p>
	</div>
	<?php
	echo leo_term_meta_js();
}

function leo_term_meta_edit_form_field($term = false)
{
	if(get_bloginfo('version') >= 3.5)
		wp_enqueue_media();
	else {
		wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
	}
	// this will add the custom meta field to the add new term page
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="_term_thumbnail_id"><?php _e('Image Feature', 'leo_plg_restaurant'); ?></label></th>
		<td>
			<?php
			$term_thumbnail_id = -1;
			$html = __('Set featured image', 'leo_plg_restaurant');
			if($term && is_object($term))
			{
				$_term_thumbnail_id = get_term_meta($term->term_id, '_term_thumbnail_id', true);
				if($_term_thumbnail_id > 0)
				{
					$term_thumbnail_id = $_term_thumbnail_id;
					$html = wp_get_attachment_image($term_thumbnail_id, 'thumbnail', false);
				}
			}
			?>
			<p class="hide-if-no-js">
				<a href="#" class="set_term_thumbnail"><?php echo $html ?></a>
			</p>
			<p class="hide-if-no-js">
				<span <?php if($term_thumbnail_id==-1) echo 'style="display: none"' ?> id="remove_term_thumbnail">
					<span class="howto" id="set-term-thumbnail-desc"><?php _e('Click the image to edit or update'); ?></span>
					<a href="#"><?php _e('Remove featured image'); ?></a>
				</span>
				<input type="hidden" class="_term_thumbnail_id" name="term_meta[_term_thumbnail_id]" id="_term_thumbnail_id" value="<?php echo $term_thumbnail_id ?>" />
			</p>
		</td>
	</tr>
	<?php
	echo leo_term_meta_js();
}

if(!function_exists('leo_term_meta_js'))
{
	function leo_term_meta_js()
	{ ?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				var wordpress_ver = "<?php echo get_bloginfo('version') ?>", upload_button;
				//
				jQuery('.set_term_thumbnail').click(function(e){
					ele = jQuery(this).data('add');
					var frame;
					if(wordpress_ver >= "3.5") {
						e.preventDefault();
						if(frame) {
							frame.open();
							return;
						}
						frame = wp.media();
						frame.on("select", function() {
							// Grab the selected attachment.
							var attachment = frame.state().get('selection').first().toJSON();
							jQuery('.set_term_thumbnail').html('<img src="'+attachment.sizes.thumbnail.url+'" alt="image" />');
							jQuery('._term_thumbnail_id').val(attachment.id);
							frame.close();
							jQuery('#remove_term_thumbnail').show();
						});
						frame.open();
					}
					else {
						tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
						return false;
					}
				});
				jQuery('#remove_term_thumbnail a').click(function(e){
					e.preventDefault();
					jQuery('.set_term_thumbnail').html('<?php echo esc_attr(__('Set featured image')) ?>');
					jQuery('#_term_thumbnail_id').val('-1');
					jQuery('#remove_term_thumbnail').hide();
				});
			});
		</script>
	<?php
	}
}

if(!function_exists('leo_term_meta_save_taxonomy_image'))
{
	function leo_term_meta_save_taxonomy_image($term_id)
	{
		if(!isset($_POST['term_meta'])) return;
		foreach ($_POST['term_meta'] as $key => $value)
		{
			update_term_meta($term_id, $key, sanitize_text_field($value));
		}
	}
}
/*================= // TERM_META =====================*/



