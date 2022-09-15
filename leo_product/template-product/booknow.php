<?php

if($page_products = get_page_by_path('san-pham'))
{
	if(function_exists('pll_get_post'))
	{
		$page_products = pll_get_post($page_products->ID);
	}
}
if($page_booknow = get_page_by_path('dat-hang'))
{
	if(function_exists('pll_get_post'))
	{
		$page_booknow = pll_get_post($page_booknow->ID);
	}
}
// RUN >
$is_valid_nonce = (isset($_POST['_ordering_nonce']) && wp_verify_nonce($_POST['_ordering_nonce'], 'ordering'));
require_once __DIR__ . '/../libs/Google/recaptcha/src/autoload.php';
// Register API keys at https://www.google.com/recaptcha/admin
/* ========== vutuanict ==============  */
$siteKey = '6LeUSsASAAAAABccgo16iW_5spQvi0oCrMzMT1vR';
$secret = '6LeUSsASAAAAAIPSVhXHD5AfPyZFTJszpvMaxlTo';
$lang = get_locale();
if($is_valid_nonce && isset($_POST['g-recaptcha-response']))
{
	// If the form submission includes the "g-captcha-response" field
	// Create an instance of the service using your secret
	$recaptcha = new \ReCaptcha\ReCaptcha($secret);

	// If file_get_contents() is locked down on your PHP installation to disallow
	// its use with URLs, then you can use the alternative request method instead.
	// This makes use of fsockopen() instead.
	// $recaptcha = new \ReCaptcha\ReCaptcha($secret, new \ReCaptcha\RequestMethod\SocketPost());

	// Make the call to verify the response and also pass the user's IP address
	$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

	if(!$resp->isSuccess())
	{
		foreach($resp->getErrorCodes() as $code)
		{
			//echo '<kbd>' , $code , '</kbd> ';
		}
		$alerts = array();
		// success, info, warning, danger
		$alerts['warning'] = array();
		$alerts['warning'][] = '<strong>Warning!</strong> Please check the the ReCaptcha form.<br /><kbd>'. implode('</kbd><kbd>', $resp->getErrorCodes()).'</kbd>';
		$is_valid_nonce = false;
	}
	else
	{
		$alerts['success'] = array();
		foreach ($resp->getErrorCodes() as $code)
		{
			$alerts['success'][] = $code;
		}
		$alerts['success'][] = '<strong>Success!</strong> Checked the the ReCaptcha form.';
	}
}
if($is_valid_nonce)
{
	$meta = array();
	$meta['your_name'] = sanitize_text_field(_postdata('your_name'));
	$meta['tel'] = sanitize_text_field(_postdata('tel'));
	$meta['email'] = sanitize_email(_postdata('email'));
	if(strpos($meta['email'], 'try-rx.com') !== false)
	{
		echo '<center>
			<h1>!!! STOP !!!</h1>
			<p><a href="/">< Back to Homepage</a></p>
		</center>';
		die();
	}
	$meta['address'] = sanitize_text_field(_postdata('address'));
	$meta['message'] = esc_html(_postdata('message'));
	$meta['product_id'] = _postdata('product_id');
	if(_postdata('option')) $meta['option'] = _postdata('option');
	$meta['created_time'] = date('Y-m-d h:m:s');
	$meta['product_qty'] = intval(_postdata('product_qty'));
	$meta['product_price_type'] = sanitize_text_field(_postdata('product_price_type'));
	$user_id = get_current_user_id();
	$product = get_post($meta['product_id']);
	$my_post = array(
		'post_author' => $user_id,
		'post_content' => $meta['message'],
		'post_content_filtered' => '',
		'post_title' => wp_strip_all_tags('['.$meta['your_name'] .' - '.$meta['tel'].'] '.$product->post_title),
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
	$post_id = wp_insert_post($my_post);

	if($post_id)
	{
		//store meta
		foreach($meta as $meta_key=>$meta_val)
		{
			if(!add_post_meta($post_id, $meta_key, $meta_val, true))
			{ 
				update_post_meta($post_id, $meta_key, $meta_val);
			}
		}
		$order = get_post($post_id);
		$order_id = str_pad($post_id, 5, "0", STR_PAD_LEFT);
		ob_start(); ?>
		<h3><?php _e('Thông tin ĐẶT HÀNG', 'leo_product') ?> - ID<?php echo $order_id ?></h3>
		<p><em><?php echo sprintf(__('Đặt lúc %s', 'leo_product'), $meta['created_time']) ?></em></p>
		<table class="table" style="width:100%">
			<tbody>
				<tr>
					<td style="width: 10%;"><strong><?php _e('Sản phẩm', 'leo_product') ?></strong></td>
					<td>
						<?php echo $product->post_title ?> <a class="has-tips" target="_blank" title="<?php esc_attr(_e('Chi tiết', 'leo_product')) ?>" href="<?php echo get_the_permalink($product) ?>"><span class="sr-only"><?php _e('View details', 'leo_product') ?> </span><i class="fa fa-external-link-square"></i></a>
					</td>
				</tr>
				<tr>
					<td><strong><?php _e('Số lượng', 'leo_product') ?></strong></td>
					<td>
						<?php echo $meta['product_qty']; ?>
					</td>
				</tr>
				<?php if(isset($meta['option']))
				{ ?>
					<tr>
						<td><strong><?php _e('Lựa chọn', 'leo_product') ?></strong></td>
						<td>
							<ul>
							<?php foreach($meta['option'] as $k=>$v)
							{ ?>
								<li><?php echo $k ?>: <strong><?php echo $v ?></strong></li>
							<?php
							} ?>
							</ul>
						</td>
					</tr>
				<?php
				} ?>
				<tr>
					<td><strong><?php _e('Đơn giá', 'leo_product') ?></strong></td>
					<td>
						<?php echo $meta['product_price_type']; ?>
					</td>
				</tr>
			</tbody>
		</table>
		<h3><?php _e('Thông tin người đặt', 'leo_product') ?></h3>
		<table style="width:100%;max-width:100%;border:1px solid #ccc;margin-bottom:10px;border-collapse:collapse;border-spacing:0;">
			<tbody>
				<tr>
					<td style="width: 15%;border-top:1px solid #ddd;padding:8px;vertical-align:top;"><strong><?php _e('Your name', 'leo_product') ?></strong></td>
					<td style="border-top:1px solid #ddd;padding:8px;vertical-align:top;"><?php echo $meta['your_name'] ?></td>
				</tr>
				<tr>
					<td style="border-top:1px solid #ddd;padding:8px;vertical-align:top;"><strong><?php _e('Email', 'leo_product') ?></strong></td>
					<td style="border-top:1px solid #ddd;padding:8px;vertical-align:top;"><a href="mailto:<?php echo $meta['email']; ?>"><?php echo $meta['email']; ?></a></td>
				</tr>
				<tr>
					<td style="border-top:1px solid #ddd;padding:8px;vertical-align:top;"><strong><?php _e('Tel', 'leo_product') ?></strong></td>
					<td style="border-top:1px solid #ddd;padding:8px;vertical-align:top;"><?php echo $meta['tel']; ?></td>
				</tr>
				<tr>
					<td style="border-top:1px solid #ddd;padding:8px;vertical-align:top;"><strong><?php _e('Address', 'leo_product') ?></strong></td>
					<td style="border-top:1px solid #ddd;padding:8px;vertical-align:top;"><?php echo $meta['address'] ?></td>
				</tr>
				<tr>
					<td style="border-top:1px solid #ddd;padding:8px;vertical-align:top;"><strong><?php _e('Message', 'leo_product') ?></strong></td>
					<td style="border-top:1px solid #ddd;padding:8px;vertical-align:top;"><pre><?php echo $meta['message']; ?></pre></td>
				</tr>
			</tbody>
		</table>
		<p><?php _e('Cám ơn bạn đã <strong>ĐẶT HÀNG</strong> của chúng tôi!', 'leo_product') ?></p>
		<p>------------------------------ <br />
		<a target="_blank" href="<?php echo home_url('/') ?>"><strong><?php echo get_option('blogname') ?></strong></a> - <?php echo get_option('description') ?> (<a target="_blank" href="<?php echo home_url('/') ?>"><?php echo home_url('/') ?></a>)</p>
		<?php
		$body = ob_get_clean();
		$body_hello_client = '
		<p>'.sprintf(__('Xin chào %s', 'leo_product'), $meta['your_name']).'</p>
		<p>Chúng tôi rất vui khi nhận được đơn ĐẶT HÀNG của bạn.
		<br />Chúng tôi sẽ sớm liên hệ lại với bạn qua số điện thoại hoặc email mà bạn đã cung cấp.</p>
		<p>Ngoài ra bạn có thể tham khảo các sản phẩm khác của chúng tôi <a target="_blank" href="'.get_the_permalink($page_products).'"><strong>tại đây</strong></a>!</p>';

		$title = get_post_meta($meta['product_id'], 'code', true)? : $product->post_title;
		$subj = '['.$_SERVER['SERVER_NAME'].' ĐẶT HÀNG]'.' ID'.$post_id.' - '.$title;

		$sys_email = sanitize_email(get_option('admin_email'));
		if($meta['email'])
		{
			$headers = array();
			$headers[] = 'Content-Type: text/html; charset=UTF-8';
			$headers[] = 'From: '.$_SERVER['SERVER_NAME'].' <'.$sys_email.'>';
			wp_mail($meta['email'], $subj, $body_hello_client.$body, $headers); // SEND
		}

		$headers = array();
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		$headers[] = 'From: '.$_SERVER['SERVER_NAME'].' <'.sanitize_email('dat-hang-'.wp_get_session_token().'@'.$_SERVER['SERVER_NAME']).'>';
		wp_mail($sys_email, $subj, $body, $headers); // SEND
		// ->
		// wp_redirect('?thankyou=1');
	}
}
//wp_enqueue_script('jquery-ui-datepicker');
if(!isset($is_short_code) || $is_short_code !== true):
	get_header();
endif;

$product_id = _postdata('product_id')? : _getdata('product_id');
if($product_id) $product = get_post($product_id);
?>

<main id="main">
	<div class="container main-container tour-plg tour-booking">
		<?php while(have_posts()):the_post();?>
			<div class="breadcrumbs clearfix">
				<ol class="breadcrumb" itemprop="breadcrumb">
					<li typeof="v:Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="v:url" property="v:title"><span class="fa fa-home"></span><span class="sr-only"> <?php _e('Home', 'tict_cruise') ?></span></a></li>
					<li typeof="v:Breadcrumb"><a rel="v:url" property="v:title" href="<?php echo get_the_permalink($page_products) ?>"><?php _e('Product', 'leo_product') ?></a></li>
					<li class="active" typeof="v:Breadcrumb"><a class="disabled" href="<?php the_permalink() ?>" rel="v:url" property="v:title"><?php the_title() ?></a></li>
				</ol>
			</div><!-- breadcrumbs -->

			<?php if(isset($alerts))
			{
				foreach($alerts as $k=>$alert)
				{
					$alert_html = implode('</p><p>', $alert);
				?>
					<div class="alert alert-<?php echo $k ?> alert-dismissable fade in">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<p><?php echo $alert_html ?></p>
					</div>
				<?php
				}
			}
			?>

			<!-- MSG thankyou -->
			<?php if($is_valid_nonce): ?>
				<div role="alert" class="alert alert-success alert-dismissible fade in">
					<button aria-label="Close" data-dismiss="alert" class="close" type="button">
					<span aria-hidden="true">&times;</span>
					</button>
					<p><?php _e('Cám ơn bạn! Đơn ĐẶT HÀNG đã được gửi đi. Chúng tôi sẽ sớm liên hệ lại với bạn!', 'leo_product') ?></p>
				</div>
				<div class="text-center"><a href="<?php echo get_the_permalink($page_products) ?>" class="btn btn-success"><i class="fa fa-cehvron-left"></i> <?php _e('Xem các Sản Phẩm khác', 'leo_product') ?></a></div>
			<?php endif //is_valid_nonce ?>
			<!-- end MSG -->

			<div class="page-destinations">
				<h1 class="page-header"><?php the_title(); ?></h1>
				<div class="desctiption lead"><?php the_content(); ?></div>
			</div>
		<?php endwhile; ?>
		<?php if(!$is_valid_nonce && isset($product)): ?>
			<div class="row">
				<div class="col-md-6">
					<section class="clearfix">
						<h4><?php _e('Product', 'leo_product') ?>: <strong><?php echo get_the_title($product) ?></strong> <a target="_blank" href="<?php echo get_the_permalink($product) ?>"><i class="fa fa-external"></i> <?php _e('View', 'leo_product') ?></a></h4>
						<?php
							$prices = get_post_meta($product_id, 'price', true);
							$price_datas = array();
							if(is_array($prices))
							{
								$count = count($prices);
								foreach($prices as $k=>$p)
								{
									$price_data = new stdClass();
									if(is_array($p))
									{
										$price = new stdClass();
										$price->title = $p['title'];
										$price->info = $p['info'];
										$price->base = intval($p['price']);
										$price->off = intval($p['price_safeoff']);
										$price->isoff = $price->base && $price->off && ($price->base-$price->off>0);
										$price->price = $price->isoff? $price->off : $price->base;
										// ---------------
										if($price && $price->price)
										{
											$price_data->price = number_format($price->price, 0, ',', '.').'<sup itemprop="priceCurrency">'.__('VND', 'leo_product').'</sup>';
										}
										if($price && $price->isoff)
										{
											$price_data->priceoff = ' <span class="base-price"><s>'.number_format($price->base, 0, ',', '.').'</s><sup>'.__('VND', 'leo_product').'</sup></span>';
										}
										$price_data->title = $price->title;
										$price_data->info = $price->info;
									} //if(is_array($p))
									$price_datas[] = $price_data;
								} //foreach($prices as $p)
							} //(is_array($prices))
							$product_option = get_post_meta($product_id, 'option', true);
							$form = new stdClass;
							$form->product_id = $product_id;
							$form->action = get_the_permalink();
							$form->price_datas = $price_datas;
							$form->product_option = $product_option;
							$post->bookform = $form;
							get_template_part('template-product/book-form');
						?>
					</section>
				</div>
				<div class="col-md-6">
				</div>
			</div>
		<?php endif //is_valid_nonce ?>
	</div><!-- main-container -->
</main>
<?php

if(!isset($is_short_code) || $is_short_code !== true)
{
	get_footer();
}