<?php
// RUN >
$is_valid_nonce = (isset($_POST['_product_order_nonce']) && wp_verify_nonce($_POST['_product_order_nonce'], 'product_order'));
if($is_valid_nonce)
{
	$meta = array();
	$meta['guest_type'] = _postdata('guest_type');
	$meta['full_name'] = _postdata('full_name');
	$meta['email'] = sanitize_email(_postdata('email'));
	$meta['address'] = _postdata('address');
	$meta['city'] = _postdata('city');
	$meta['tel'] = _postdata('tel');
	$meta['message'] = _postdata('message');
	$meta['product'] = _postdata('product');
	$meta['order_time'] = date('Y-m-d h:m:s');
	$user_id = get_current_user_id();
	$post_order = array(
		'post_author' => $user_id,
		'post_content' => $meta['message'],
		'post_content_filtered' => '',
		'post_title' => wp_strip_all_tags(__('Order', 'tict_cruise').': '.$meta['full_name'].' - '.$meta['tel']),
		'post_excerpt' => '',
		'post_status' => 'publish',
		'post_type' => 'product_order', //
		'comment_status' => '',
		'ping_status' => '',
		'post_password' => '',
		'to_ping' =>  '',
		'pinged' => '',
		'post_parent' => 0,
		'menu_order' => 0,
		'guid' => '',
		'import_id' => 0,
		'context' => '',
	);
	if($post_id = wp_insert_post($post_order))
	{
		//store meta
		foreach($meta as $meta_key=>$meta_val)
		{
			if (! add_post_meta($post_id, $meta_key, $meta_val, true))
			{ 
				update_post_meta($post_id, $meta_key, $meta_val);
			}
		}

		$order = get_post($post_id);
		$order_id = str_pad($post_id, 5, "0", STR_PAD_LEFT);
		ob_start(); ?>
		<h3><strong><?php _e('Order Details', 'tict_cruise') ?></strong> - ID<?php echo $order_id ?>, <em><?php echo sprintf(__('Ordered at %s', 'tict_cruise'), $meta['order_time']) ?></em></h3>
		<table class="table" style="width:100%">
			<thead>
				<tr>
					<th><?php _e('Product list', 'leo_product') ?></th>
					<th width="10%"><?php _e('Quantity', 'leo_product') ?></th>
					<th width="15%"><?php _e('Total', 'leo_product') ?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($meta['product'] as $k=>$product):
				$product = (object) $product;
			?>
				<tr>
					<td><?php echo get_the_title($product->id) ?> <a target="_blank" href="<?php echo get_the_permalink($product->id) ?>"><?php _e('Detail', 'tict_cruise') ?></a></strong></td>
					<td><?php echo $product->qty ?></td>
					<td><?php echo number_format($product->price * $product->qty) ?></td>
				</tr>
			<?php endforeach //$products ?>
			</tbody>
			<tfoot>
				<tr class="strong">
					<td colspan="2" class="text-right"><?php _e('Subotal', 'leo_product') ?></td>
					<td class="subotal"><?php echo number_format($product->price * $product->qty) ?></td>
				</tr>
			</tfoot>
		</table>
		<h3><?php _e('Guest Details', 'tict_cruise') ?></h3>
		<table class="table" style="width:100%">
			<tbody>
				<tr>
					<td style="width: 10%;"><strong><?php _e('Your name', 'tict_cruise') ?></strong></td>
					<td><?php echo $meta['guest_type'].' '.$meta['full_name'] ?></td>
				</tr>
				<tr>
					<td><strong><?php _e('Email', 'tict_cruise') ?></strong></td>
					<td><a href="mailto:<?php echo $meta['email']; ?>"><?php echo $meta['email']; ?></a></td>
				</tr>
				<tr>
					<td><strong><?php _e('Tel', 'tict_cruise') ?></strong></td>
					<td><?php echo $meta['tel']; ?></td>
				</tr>
				<tr>
					<td><strong><?php _e('Address', 'tict_cruise') ?></strong></td>
					<td><?php echo $meta['address'].' / '.$meta['city'] ?></td>
				</tr>
				<tr>
					<td><strong><?php _e('Additional message', 'tict_cruise') ?></strong></td>
					<td><em><?php echo wpautop($meta['message']); ?></em></td>
				</tr>
			</tbody>
		</table>
		<?php
		$booking_detail = ob_get_clean();

		$sys_email = get_option('admin_email');
		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: '.$_SERVER['SERVER_NAME'].' <'.$sys_email.'>',
			'Cc: Admin <'.$sys_email.'>',
		);
		$subj = $_SERVER['SERVER_NAME'].' - Booking ID'.$post_id;
		$link_order = get_permalink($post_id);
		$body = sprintf(__('<p>Dear, %s</p><p>Thank you for booking our tour!</p><p><a class="btn" href="%s">&gt; Deposit Now</a></p>%s<p>%s</p>', 'tict_cruise'), $meta['guest_type'].' '.$meta['full_name'], $link_order, $booking_detail, get_option('blogname').' ('.$_SERVER['SERVER_NAME'].')');
		wp_mail($meta['email'], $subj, $body, $headers); // SEND
		// ->
		wp_redirect($link_order.'?thankyou=1');
	}
}

/* ////////////////////////////////////// */
get_header();
$product_id = intval(_getdata('product_id'));
$qty = intval(_getdata('qty'));
if(!$qty) $qty = 1;
$products = array(
	(object) array(
		'id' => $product_id,
		'qty' => $qty,
	)
);
?>

<main id="main">
	<div class="container main-container tour-plg tour-booking">
		<?php while(have_posts()):the_post(); ?>
			<div class="breadcrumbs clearfix">
				<ol class="breadcrumb" itemprop="breadcrumb">
					<li typeof="v:Breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>" rel="v:url" property="v:title"><span class="fa fa-home"></span><span class="sr-only"> <?php _e('Home', 'tict_cruise') ?></span></a></li>
					<li class="active" typeof="v:Breadcrumb"><a class="disabled" href="<?php the_permalink() ?>" rel="v:url" property="v:title"><?php the_title() ?></a></li>
				</ol>
			</div><!-- breadcrumbs -->
			<div class="page-destinations">
				<h1 class="page-header"><?php the_title(); ?></h1>
				<div class="desctiption lead"><?php the_content(); ?></div>
			</div>
		<?php endwhile; ?>

		<section class="clearfix">
			<form action="<?php echo the_permalink() ?>" method="post" id="cart" class="form-vertical">
				<div class="row">
					<fieldset class="col-md-6">
						<legend><?php _e('Details', 'tict_cruise') ?></legend>
						<table class="table">
							<thead>
								<tr>
									<th><?php _e('Product list', 'leo_product') ?></th>
									<th width="10%"><?php _e('Quantity', 'leo_product') ?></th>
									<th width="15%"><?php _e('Total', 'leo_product') ?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($products as $k=>$product):
								$product->price = intval(get_post_meta($product->id, 'price', true));
							?>
								<tr>
									<td>
										<span class="badge"><?php echo ($k+1) ?></span> <?php echo get_the_title($product->id) ?> - <strong><?php echo number_format($product->price) ?></strong>
										<input type="hidden" name="product[<?php echo $k ?>][id]" value="<?php echo $product->id ?>" />
										<input type="hidden" name="product[<?php echo $k ?>][price]" value="<?php echo $product->price ?>" />
									</td>
									<td><input type="number" name="product[<?php echo $k ?>][qty]" size="3" value="<?php echo $product->qty ?>" class="form-control" /></td>
									<td><?php echo number_format($product->price * $product->qty) ?></td>
								</tr>
							<?php endforeach //$products ?>
							</tbody>
							<tfoot>
								<tr class="strong">
									<td colspan="2" class="text-right"><?php _e('Subotal', 'leo_product') ?></td>
									<td class="subotal"><?php echo number_format($product->price * $product->qty) ?></td>
								</tr>
							</tfoot>
						</table>
						<div class="form-group text-right">
							<?php wp_nonce_field('product_order', '_product_order_nonce'); ?>
							<button class="btn btn-danger btn-lg" type="submit"><i class="fa fa-chevron-right"></i> <?php _e('Check Out', 'tict_cruise'); ?></button>
						</div>
					</fieldset>
					<fieldset class="col-md-6">
						<legend><?php _e('Your details', 'tict_cruise') ?></legend>
						<label class="full_name-lbl" for="full_name"><?php _e('Your name', 'tict_cruise') ?> <span class="star">&nbsp;*</span></label>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group guest_type">
									<select name="guest_type" id="guest_type" class="form-control">
										<option value="Mr."><?php _e('Mr.', 'tict_cruise') ?></option>
										<option value="Ms."><?php _e('Ms.', 'tict_cruise') ?></option>
									</select>
								</div>
							</div>
							<div class="col-md-8">
								<div class="form-group full_name">
									<input type="text" class="form-control" pattern=".{3,50}" required id="full_name" name="full_name" value="<?php echo _postdata('full_name') ?>"  placeholder="<?php _e('Your name', 'tict_cruise') ?>" />
								</div>
							</div>
							<!-- -- -->
							<div class="col-md-7">
								<div class="form-group email">
									<label class="required email-lbl" for="email"><?php _e('Email', 'tict_cruise') ?> <span class="star">&nbsp;*</span></label>
									<input type="email" class="form-control" required id="email" name="email" value="<?php echo _postdata('email') ?>" />
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group tel">
									<label class="tel-lbl" for="tel"><?php _e('Tel', 'tict_cruise') ?></label>
									<input type="text" class="form-control" pattern=".{9,50}" id="tel" name="tel" value="<?php echo _postdata('tel') ?>" />
								</div>
							</div>
							<!-- -- -->
							<div class="col-md-7">
								<div class="form-group address">
									<label class="required address-lbl" for="address"><?php _e('Address', 'tict_cruise') ?> <span class="star">&nbsp;*</span></label>
									<input type="text" class="form-control" pattern=".{3,200}" required id="address" name="address" value="<?php echo _postdata('address') ?>" />
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group city">
									<label class="required city-lbl" for="city"><?php _e('City', 'tict_cruise') ?> <span class="star">&nbsp;*</span></label>
									<input type="text" class="form-control" pattern=".{3,100}" required id="city" name="city" value="<?php echo _postdata('city') ?>" />
								</div>
							</div>
						</div>
						<div class="form-group message">
							<label class="required city-lbl" for="city"><?php _e('Message', 'tict_cruise') ?></label>
							<textarea class="form-control" id="message" name="message" rows="5" /><?php echo _postdata('message') ?></textarea>
						</div>
						<input type="hidden" name="country" value="vn" />
					</fieldset>
				</div>
			</form>
		</section>
		<script type="text/javascript">
		function pad (str, max)
		{
			str = str.toString();
			return str.length < max ? pad("0" + str, max) : str;
		}

		Number.prototype.formatMoney = function(c, d, t)
		{
		var n = this,
			c = isNaN(c = Math.abs(c)) ? 2 : c, 
			d = d == undefined ? "." : d, 
			t = t == undefined ? "," : t, 
			s = n < 0 ? "-" : "", 
			i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
			j = (j = i.length) > 3 ? j % 3 : 0;
			return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
		};
		String.prototype.sprintf = function()
		{
			var counter = 0;
			var args = arguments;

			return this.replace(/%s/g, function(){
				return args[counter++];
			});
		};
		jQuery(document).ready(function(){
			//jQuery('#num_adult').change(function(){ jQuery('.service_qty').val(jQuery(this).val()); });
			tour_select();
		});

		jQuery("#cart").validate({
			highlight: function(element){
				jQuery(element).closest('.form-group').addClass('has-error');
			},
			unhighlight: function(element){
				jQuery(element).closest('.form-group').removeClass('has-error');
			},
			errorElement: 'span',
			errorClass: 'help-block small',
			errorPlacement: function(error, element){
				if(element.parent('.input-group').length){
					error.insertAfter(element.parent());
				} else {
					error.insertAfter(element);
				}
			},
			submitHandler: function(form){
				return true;
			}
		});

		</script>
		<?php //get_sidebar();?>
	</div><!-- main-container -->
</main>
<?php
	get_footer();