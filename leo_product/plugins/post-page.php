<?php

/* =================== META BOX ============== */

if(!function_exists('leo_plg_postpage_add_metaboxes'))
{
	add_action ('add_meta_boxes', 'leo_plg_postpage_add_metaboxes');

	function leo_plg_postpage_add_metaboxes() 
	{
		// add_meta_box (string $id, string $title, callable $callback, string|array|WP_Screen $screen = null, string $context = 'advanced|side|normal', string $priority = 'default|high|low', array $callback_args = null)
		add_meta_box('leo_plg_postpage_metabox_id', __('Images Metabox'), 'leo_plg_postpage_callback', array('post', 'page', 'product'), 'side', 'high');
	}

	function leo_plg_postpage_callback($post, $metabox)
	{
		wp_nonce_field(basename(__FILE__), '_leo_plg_postpage_nonce');
	?>
		<div class="meta-row header_images-row">
			<div class="meta-th">
				<label class="header_images-lbl"><?php _e('Header images', 'leo_multihomestay'); ?></label>
			</div>
			<div class="meta-td header_images">
				<?php
				if($images_id = get_post_meta($post->ID, 'header_images', true))
				{
					foreach($images_id as $img_id)
					{
				?>
				<div class="item header_images_item_<?php echo $img_id ?>">
					<?php echo wp_get_attachment_image($img_id, 'thumbnail', false, array('class'=>'preview')) ?>
					<button type="button" title="remove" class="image_remove" onclick="removeEle('.header_images_item_<?php echo $img_id ?>')">x</button>
					<input type="hidden" class="header_image" name="header_images[]" id="header_images_item_<?php echo $img_id ?>" value="<?php echo $img_id; ?>" />
				</div>
				<?php
					}
				}
				?>
				<div class="item_add">
					<div class="clear"></div>
					<button type="button" data-add="header_images">+ <?php _e('Add Images', 'leo_multihomestay'); ?></button>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<br />
		<div class="meta-row images-row">
			<div class="meta-th">
				<label class="images-lbl"><?php _e('Other Images', 'leo_multihomestay'); ?></label>
			</div>
			<div class="meta-td images">
				<?php
				if($images_id = get_post_meta($post->ID, 'images', true))
				{
					foreach($images_id as $img_id)
					{
				?>
				<div class="item images_item_<?php echo $img_id ?>">
					<?php echo wp_get_attachment_image($img_id, 'thumbnail', false, array('class'=>'preview')) ?>
					<button type="button" title="remove" class="image_remove" onclick="removeEle('.images_item_<?php echo $img_id ?>')">x</button>
					<input type="hidden" class="image" name="images[]" id="images_item_<?php echo $img_id ?>" value="<?php echo $img_id; ?>" />
				</div>
				<?php
					}
				}
				?>
				<div class="item_add">
					<div class="clear"></div>
					<button type="button" data-add="images">+ <?php _e('Add Images', 'leo_multihomestay'); ?></button>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<style type="text/css">
			.header_images > .item,
			.images > .item
			{
				width: 60px;
				float: left;
				margin-right: 3px;
				position: relative;
			}
			.item_add button
			{
				margin-top: 10px;
			}
			.header_images > .item .image_remove,
			.images > .item .image_remove
			{
				position: absolute;
				top: 2px;
				right: 2px;
			}
			.preview{max-width:100%;height:auto;}
		</style>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				var wordpress_ver = "<?php echo get_bloginfo('version') ?>", upload_button;
				//
				jQuery(".item_add button").click(function(event){
					ele = jQuery(this).data('add');
					var frame;
					if (wordpress_ver >= "3.5") {
						event.preventDefault();
						if (frame) {
							frame.open();
							return;
						}
						frame = wp.media({
							multiple: true
						});
						frame.on("select", function() {
							// Grab the selected attachment.
							var attachments = frame.state().get("selection").toArray();
							//var attachment = attachments[0].toJSON();
							jQuery.each(attachments, function(idx, attachment){
								attachment = attachment.toJSON();
								if(jQuery('.'+ele+'_item_'+attachment.id).length) return;
								html = '';
								html += '<div class="item '+ele+'_item_'+attachment.id+'">';
								html += '	<img class="preview" src="'+attachment.sizes.thumbnail.url+'" alt="image" />';
								html += '	<button type="button" title="remove" class="image_remove" onclick="removeEle(\'.'+ele+'_item_'+attachment.id+'\')">x</button>';
								html += '	<input type="hidden" class="image" name="'+ele+'[]" id="'+ele+'_item_'+attachment.id+'" value="'+attachment.id+'" />';
								html += '</div>';
								jQuery('.'+ele+' .item_add').before(html);
							});
							frame.close();
						});
						frame.open();
					}
					else {
						tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
						return false;
					}
				});
			});
			function removeEle(ele)
			{
				jQuery(ele).remove();
			}
		</script>
	<?php 
	}

	add_action('save_post', 'leo_plg_postpage_add_metaboxes_save');
	function leo_plg_postpage_add_metaboxes_save($post_id)
	{
		// Checks save status
		$is_autosave = wp_is_post_autosave($post_id);
		$is_revision = wp_is_post_revision($post_id);
		$is_valid_nonce = (isset($_POST[ '_leo_plg_postpage_nonce' ]) && wp_verify_nonce($_POST[ '_leo_plg_postpage_nonce' ], basename(__FILE__))) ? 'true' : 'false';
		// Exits script depending on save status
		if ($is_autosave || $is_revision || !$is_valid_nonce)
		{
			return;
		}
		$post_type = get_post_type($post_id);
		$post_type_allow = array('post', 'page', 'product');
		$post_status = get_post_status($post_id);
		if (in_array($post_type, $post_type_allow) && "auto-draft" != $post_status)
		{
			leo_save_meta($post_id, 'header_images', 'object');
			leo_save_meta($post_id, 'images', 'object');
		}
		return $post_id;
	}
}



/*
if(!function_exists('leo_plg_feedback_add_metaboxes'))
{
	add_action ('add_meta_boxes', 'leo_plg_feedback_add_metaboxes');

	function leo_plg_feedback_add_metaboxes() 
	{
		// add_meta_box ( string $id, string $title, callable $callback, string|array|WP_Screen $screen = null, string $context = 'advanced|side|normal', string $priority = 'default|high|low', array $callback_args = null )
		add_meta_box('leo_plg_feedback_metabox_id', __('Feedback Metabox'), 'leo_plg_feedback_callback', 'post', 'normal', 'high');
	}

	function leo_plg_feedback_callback($post, $metabox) {
		wp_nonce_field( basename( __FILE__ ), '_leo_plg_feedback_nonce' );
		?>
		<div class="meta-row star-row">
			<div class="meta-th">
				<label for="star" class="star-lbl"><?php _e('Star', 'leo_restaurant'); ?></label>
			</div>
			<?php $star = intval(get_post_meta($post->ID, 'star', true));
			if(!$star) $star = 4; ?>
			<div class="meta-td">
			<?php for($i=1;$i<=5;$i++): ?>
				<input type="radio" class="star" name="star" id="star<?php echo $i ?>" value="<?php echo $i ?>"<?php if($star == $i) echo ' checked'; ?> /><label for="star<?php echo $i ?>" class="star-lbl"><?php echo $i ?></label>
				&nbsp;
			<?php endfor ?>
			</div>
		</div>
		<div class="meta-row by_author-row">
			<div class="meta-th">
				<label for="by_author" class="by_author-lbl"><?php _e( 'By author', 'leo_restaurant' ); ?></label>
			</div>
			<div class="meta-td">
				<input type="text" class="by_author" name="by_author" id="by_author" value="<?php echo esc_attr(get_post_meta($post->ID, 'by_author', true)); ?>" />
			</div>
		</div>
		<div class="meta-row at_time-row">
			<div class="meta-th">
				<label for="at_time" class="at_time-lbl"><?php _e( 'At time', 'leo_restaurant' ); ?></label>
			</div>
			<div class="meta-td">
				<input type="text" class="at_time" name="at_time" id="at_time" value="<?php echo esc_attr(get_post_meta($post->ID, 'at_time', true)); ?>" />
			</div>
		</div>
		<div class="meta-row address-row">
			<div class="meta-th">
				<label for="address" class="address-lbl"><?php _e( 'Address', 'leo_restaurant' ); ?></label>
			</div>
			<div class="meta-td">
				<input type="text" class="address" name="address" id="address" value="<?php echo esc_attr(get_post_meta($post->ID, 'address', true)); ?>" />
			</div>
		</div>
		<?php 
	}

	add_action( 'save_post', 'leo_plg_feedback_add_metaboxes_save' );
	function leo_plg_feedback_add_metaboxes_save($post_id)
	{
		// Checks save status
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ '_leo_plg_feedback_nonce' ] ) && wp_verify_nonce( $_POST[ '_leo_plg_feedback_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
		// Exits script depending on save status
		if ( $is_autosave || $is_revision || !$is_valid_nonce )
		{
			return;
		}
		$post_type = get_post_type($post_id);
		$post_type_allow = array('post');
		$post_status = get_post_status($post_id);
		if (in_array($post_type, $post_type_allow) && "auto-draft" != $post_status )
		{
			leo_save_meta($post_id, 'by_author', 'text');
			leo_save_meta($post_id, 'at_time', 'text');
			leo_save_meta($post_id, 'address', 'text');
			leo_save_meta($post_id, 'star', 'text');
		}
		return $post_id;
	}
}

if(!function_exists('leo_plg_price_add_metaboxes'))
{
	add_action ('add_meta_boxes', 'leo_plg_price_add_metaboxes');

	function leo_plg_price_add_metaboxes() 
	{
		// add_meta_box ( string $id, string $title, callable $callback, string|array|WP_Screen $screen = null, string $context = 'advanced|side|normal', string $priority = 'default|high|low', array $callback_args = null )
		add_meta_box('leo_plg_price_metabox_id', __('Price Metabox'), 'leo_plg_price_callback', 'page', 'side', 'high');
	}

	function leo_plg_price_callback($post, $metabox) {
		wp_nonce_field( basename( __FILE__ ), '_leo_plg_price_nonce' );
		?>
		<div class="meta-row price-row">
			<div class="meta-th">
				<label for="price" class="price-lbl"><?php _e( 'Price', 'leo_restaurant' ); ?></label>
			</div>
			<div class="meta-td">
				<input type="text" class="price" name="price" id="price" value="<?php echo esc_attr(get_post_meta($post->ID, 'price', true)); ?>" />
			</div>
		</div>
		<?php 
	}

	add_action( 'save_post', 'leo_plg_price_add_metaboxes_save' );
	function leo_plg_price_add_metaboxes_save($post_id)
	{
		// Checks save status
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ '_leo_plg_price_nonce' ] ) && wp_verify_nonce( $_POST[ '_leo_plg_price_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
		// Exits script depending on save status
		if ( $is_autosave || $is_revision || !$is_valid_nonce )
		{
			return;
		}
		$post_type = get_post_type($post_id);
		$post_type_allow = array('page');
		$post_status = get_post_status($post_id);
		if (in_array($post_type, $post_type_allow) && "auto-draft" != $post_status )
		{
			leo_save_meta($post_id, 'price', 'text');
		}
		return $post_id;
	}
}
*/

/* ==================== / METABOX ================== */