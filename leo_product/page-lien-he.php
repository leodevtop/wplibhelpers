<?php
/* ========== vutuanict ==============  */
$siteKey = '6LeUSsASAAAAABccgo16iW_5spQvi0oCrMzMT1vR';
$secret = '6LeUSsASAAAAAIPSVhXHD5AfPyZFTJszpvMaxlTo';
$lang = get_locale();
// lowercase first letter of functions. It is more standard for PHP
function getIP()
{
	$ipaddress = '';
	if ($_SERVER['HTTP_CLIENT_IP'])
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	else if($_SERVER['HTTP_X_FORWARDED_FOR'])
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else if($_SERVER['HTTP_X_FORWARDED'])
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	else if($_SERVER['HTTP_FORWARDED_FOR'])
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	else if($_SERVER['HTTP_FORWARDED'])
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	else if($_SERVER['REMOTE_ADDR'])
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	else
		$ipaddress = 'UNKNOWN';

	return $ipaddress;
}
// RUN >
require_once __DIR__ . '/libs/Google/recaptcha/src/autoload.php';
// Register API keys at https://www.google.com/recaptcha/admin
/* ========== vutuanict ==============  */
$is_valid_nonce = (isset($_POST['_contact_nonce']) && wp_verify_nonce($_POST['_contact_nonce'], 'contact'));
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
	$meta['guest_type'] = _postdata('guest_type');
	$meta['full_name'] = _postdata('full_name');
	$meta['email'] = sanitize_email(_postdata('email'));
	$meta['address'] = _postdata('address');
	$meta['city'] = _postdata('city');
	$meta['tel'] = _postdata('tel');
	$meta['message'] = _postdata('message');
	$meta['submit_time'] = date('Y-m-d h:m:s');
	$user_id = get_current_user_id();
	$post_order = array(
		'post_author' => $user_id,
		'post_content' => $meta['message'],
		'post_content_filtered' => '',
		'post_title' => wp_strip_all_tags(__('Contact', 'leo_product').': '.$meta['full_name']),
		'post_excerpt' => '',
		'post_status' => 'publish',
		'post_type' => 'contact_form', //
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
			if (!add_post_meta($post_id, $meta_key, $meta_val, true))
			{ 
				update_post_meta($post_id, $meta_key, $meta_val);
			}
		}

		ob_start(); ?>
		<h3><?php _e('Guest Details', 'leo_product') ?></h3>
		<table class="table" style="width:100%">
			<tbody>
				<tr>
					<td style="width: 10%;"><strong><?php _e('Your name', 'leo_product') ?></strong></td>
					<td><?php echo $meta['guest_type'].' '.$meta['full_name'] ?></td>
				</tr>
				<tr>
					<td><strong><?php _e('Email', 'leo_product') ?></strong></td>
					<td><a href="mailto:<?php echo $meta['email']; ?>"><?php echo $meta['email']; ?></a></td>
				</tr>
				<tr>
					<td><strong><?php _e('Tel', 'leo_product') ?></strong></td>
					<td><?php echo $meta['tel']; ?></td>
				</tr>
				<tr>
					<td><strong><?php _e('Address', 'leo_product') ?></strong></td>
					<td><?php echo $meta['address'].' / '.$meta['city'] ?></td>
				</tr>
				<tr>
					<td><strong><?php _e('Additional message', 'leo_product') ?></strong></td>
					<td><em><?php echo wpautop($meta['message']); ?></em></td>
				</tr>
			</tbody>
		</table>
		<?php
		$detail = ob_get_clean();

		$subj = '['.$_SERVER['SERVER_NAME'].' - Contact] '.$meta['full_name'];
		$sys_email = get_option('admin_email');
		$body = sprintf(__('<p>Xin chào %s</p><p>Cám ơn bạn đã liên hệ với chúng tôi!</p>%s<p>%s</p>', 'leo_product'), $meta['guest_type'].' '.$meta['full_name'], $detail, get_option('blogname').' ('.$_SERVER['SERVER_NAME'].')');
		// ->
		$sys_email = sanitize_email(get_option('admin_email'));
		if($meta['email'])
		{
			$headers = array();
			$headers[] = 'Content-Type: text/html; charset=UTF-8';
			$headers[] = 'From: '.$_SERVER['SERVER_NAME'].' <'.$sys_email.'>';
			wp_mail($meta['email'], $subj, $body, $headers); // SEND
		}

		$headers = array();
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		$headers[] = 'From: '.$_SERVER['SERVER_NAME'].' <'.sanitize_email('lien-he-'.wp_get_session_token().'@'.$_SERVER['SERVER_NAME']).'>';
		wp_mail($sys_email, $subj, $detail, $headers); // SEND

	}
}

/* ////////////////////////////////////// */
get_header();
$has_widget_right = false;//is_active_sidebar('right');
$has_widget_main_bottom = is_active_sidebar('main_bottom');
?>

<div class="container">
	<main id="main">
		<div class="main-container row">
			<div class="<?php if($has_widget_right) echo 'col-md-9'; else echo 'col-md-12'; ?>">
				<?php
				// Start the loop.
				while ( have_posts() ) : the_post(); ?>
					<div class="breadcrumbs clearfix">
						<ol class="breadcrumb" itemprop="breadcrumb">
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="fa fa-home"></span><span class="sr-only"> <?php _e('Home', 'leo_product') ?></span></a></li>
						</ol>
					</div><!-- breadcrumbs -->
					<!-- MSG thankyou -->
					<?php if($is_valid_nonce)
					{?>
						<div role="alert" class="alert alert-success alert-dismissible fade in">
							<button aria-label="Close" data-dismiss="alert" class="close" type="button">
							<span aria-hidden="true">&times;</span>
							</button>
							<p><?php _e('Cám ơn bạn! Chúng tôi sẽ sớm liên hệ lại với bạn!', 'leo_product') ?></p>
							<p><a href="index.php" class="btn btn-success"><i class="fa fa-chevron-left"></i> Quay lại Trang chủ</a></p>
						</div>
					<?php
					}
					else
					{ //is_valid_nonce
						if(isset($alerts))
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
						<?php get_template_part( 'template-parts/content', 'page' ); ?>
						
						<form action="<?php echo the_permalink() ?>" method="post" id="contact-form" class="form-vertical">
							<fieldset>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group full_name">
											<label class="full_name-lbl" for="full_name"><?php _e('Your name', 'leo_product') ?> <span class="star">&nbsp;*</span></label>
											<input type="text" class="form-control" pattern=".{3,50}" required id="full_name" name="full_name" value="<?php echo _postdata('full_name') ?>"  placeholder="<?php _e('Your name', 'leo_product') ?>" />
										</div>
										<!-- -- -->
										<div class="row">
											<div class="col-sm-8 col-md-7">
												<div class="form-group email">
													<label class="required email-lbl" for="email"><?php _e('Email', 'leo_product') ?> <span class="star">&nbsp;*</span></label>
													<input type="email" class="form-control" required id="email" name="email" value="<?php echo _postdata('email') ?>" />
												</div>
											</div>
											<div class="col-sm-4 col-md-5">
												<div class="form-group tel">
													<label class="tel-lbl" for="tel"><?php _e('Tel', 'leo_product') ?></label>
													<input type="text" class="form-control" pattern=".{9,50}" id="tel" name="tel" value="<?php echo _postdata('tel') ?>" />
												</div>
											</div>
										</div>
										<!-- -- -->
										<div class="form-group address">
											<label class="required address-lbl" for="address"><?php _e('Address', 'leo_product') ?> <span class="star">&nbsp;*</span></label>
											<input type="text" class="form-control" pattern=".{3,200}" required id="address" name="address" value="<?php echo _postdata('address') ?>" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group message">
											<label class="required message-lbl" for="message"><?php _e('Message', 'leo_product') ?></label>
											<textarea class="form-control" id="message" name="message" rows="6" /><?php echo _postdata('message') ?></textarea>
										</div>
									</div>
								</div>
								<!--recaptcha -->
								<div class="form-group recaptcha">
									<label class="required recaptcha-lbl"><?php _e('reCaptcha', 'leo_product') ?> <span class="star">&nbsp;*</span></label>
									<div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>"></div>
									<script type="text/javascript"
											src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang; ?>">
									</script>
								</div>
								<!--//recaptcha -->
								<div class="form-group text-center">
									<?php wp_nonce_field('contact', '_contact_nonce'); ?>
									<button class="btn btn-danger btn-lg" type="submit"><i class="fa fa-chevron-right"></i> <?php _e('Submit', 'leo_product'); ?></button>
								</div>
							</fieldset>
						</form>
					<?php
					} //is_valid_nonce ?>

				<?php
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}

				endwhile;
				?>
			</div>
			<?php if($has_widget_right): ?>
				<div class="col-md-3">
					<?php dynamic_sidebar('right'); ?>
				</div>
			<?php endif ?>
		</div><!-- main-container -->
		<?php if($has_widget_main_bottom): ?>
			<?php dynamic_sidebar('main_bottom'); ?>
		<?php endif ?>
	</main>
</div><!-- .container -->

<?php get_footer(); ?>
