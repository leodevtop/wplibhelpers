<?php
$terms = array();
$terms_id = array();
if(is_archive())
{
	$terms[] = get_queried_object();

}
elseif(is_single())
{
	$terms = wp_get_post_terms(get_the_ID(), 'product_category'); //array('product_category', 'manufacturer')
}

foreach($terms as $term)
{
	if(!empty($term) && !is_wp_error($term))
	{
		$terms_id[] = $term->term_id;
		if($term->parent)
		{
			$parent = get_term($term->parent);
			if(!empty($parent) && !is_wp_error($parent))
			{
				$terms_id[] = $parent->term_id;
			}
		}
	}
}
?>
<div class="product_category-list clearfix">
	<h3 class="heading"><?php _e('Category product', 'leo_product') ?></h3>
	<ul class="nav nav-pills nav-stacked">
		<?php 
		$product_terms = get_terms('product_category', 'hide_empty=0&orderby=id&parent=0'); //&orderby=name&order=ASC
		if(!empty($product_terms) && !is_wp_error($product_terms)):
			foreach($product_terms as $term):
				$term->link = get_term_link($term);
			?>
				<li class="term<?php echo $term->term_id ?><?php if($childterms) echo ' parent'; if($terms_id && in_array($term->term_id, $terms_id)) echo ' active'; ?>">
					<a href="<?php echo $term->link ?>"><?php echo $term->name ?></a>
				</li>
			<?php endforeach; //$product_terms
		endif; //if(!empty($product_terms) ?>
	</ul>
</div><!-- product_category-list  -->
