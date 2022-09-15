<?php
	$item_class = isset($post->item_class)? $post->item_class : 'col-xs-6 col-sm-4 col-md-4 col-lg-4';
	$thumb_ratio = isset($post->thumb_ratio)? $post->thumb_ratio : 1.33334;
	$thumb_size = isset($post->thumb_size)? $post->thumb_size : 'medium';
	$thumb_class = isset($post->thumb_class)? $post->thumb_class : 'resizeover';
	$thumb_crop = isset($post->thumb_crop)? $post->thumb_crop : true;
	$thumb_crop = intval($thumb_crop)? 'true' : 'false';
	$prices = get_post_meta(get_the_ID(), 'price', true);
	$price = new stdClass();
	if(is_array($prices))
	{
		foreach($prices as $p)
		{
			if(is_array($p))
			{
				$price->base = intval($p['price']);
				$price->off = intval($p['price_safeoff']);
				$price->isoff = $price->base && $price->off && ($price->base-$price->off>0);
				$price->price = $price->isoff? $price->off : $price->base;
			}
			break;
		}
	}
?>
<div class="item <?php echo $item_class ?> clearfix">
	<div class="item-inner">
		<a class="thumb" href="<?php the_permalink() ?>">
			<?php the_post_thumbnail($thumb_size, array('class'=>$thumb_class, 'data-ratio'=>$thumb_ratio, 'data-crop'=>$thumb_crop)); ?>
		</a>
		<div class="description">
			<h4><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h4>
			<div class="meta hidden">
					<div class="price text-center">
						<?php// if(isset($price->price) && $price->price) echo '<strong>'.number_format($price->price, 0, ',', '.').'</strong><sup>'.__('VND', 'leo_product').'</sup>'; else _e('Price Contact', 'leo_product'); ?>
						<?php// if(isset($price->isoff) && $price->isoff) echo ' <s>'.number_format($price->base, 0, ',', '.').'</s><sup>'.__('VND', 'leo_product').'</sup>' ?>
					</div>
				<?php if($promotion = get_post_meta(get_the_ID(), 'promotion', true)): ?>
					<div class="promotion"><i class="fa fa-gift"></i> <small><?php echo $promotion ?></small></div>
				<?php endif ?>
				<?php if(get_post_meta(get_the_ID(), 'status', true) == 'out_of_stock'): ?>
					<div class="out-of-stock"><i class="fa fa-exclamation"></i> <small><?php _e('Out of Stock', 'leo_product') ?></small></div>
				<?php endif ?>
			</div>
		</div>
	</div>
</div>