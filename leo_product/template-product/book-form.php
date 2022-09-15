<?php
	if(!isset($post->bookform)) return;
	$form = $post->bookform;
	/* ========== vutuanict ==============  */
	$siteKey = '6LeUSsASAAAAABccgo16iW_5spQvi0oCrMzMT1vR';
	$secret = '6LeUSsASAAAAAIPSVhXHD5AfPyZFTJszpvMaxlTo';
	$lang = get_locale();
?>

<form action="<?php echo $form->action ?>" class="form-validate form-vertical" method="post" id="orderForm">
	<fieldset class="form-vertical clearfix">
		<div class="row row-list row-padding-sm">
			<div class="item col-xs-12 col-sm-12 col-md-12 product">
				<div class="form-group">
					<div class="row row-list row-padding-sm">
						<div class="item col-xs-12 col-sm-4 col-md-3">
							<label for="product_qty"><?php _e('Số lượng', 'leo_product') ?> <span class="star">*</span></label>
							<input type="number" value="1" min="1" name="product_qty" class="required form-control" id="product_qty" required value="<?= esc_attr(_postdata('product_qty')) ?>" />
						</div>
						<div class="item col-xs-12 col-sm-8 col-md-9 product_price_type">
							<label for="product-type"><?php _e('Loại', 'leo_product') ?> <span class="star">*</span></label>
							<select name="product_price_type" id="product-type" class="required form-control" required>
								<?php
									foreach($form->price_datas as $k=>$price_data)
									{
										echo '<option value="'.esc_attr(strip_tags($price_data->price.' - '.$price_data->title)).'">'.$price_data->price.' - '.$price_data->title.'</option>';
									}
								?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<?php if($form->product_option)
			{
				if(isset($form->product_option['size']))
				{
					$sizes = explode('|', $form->product_option['size']);
			?>
				<div class="item col-xs-12 col-sm-6 col-md-6 size">
					<div class="form-group">
						<label for="option_size"><?php _e('Kích thước', 'leo_product') ?></label>
						<select class="form-control" name="option[size]" id="option_size">
							<?php foreach($sizes as $size)
							{ ?>
								<option value="<?php echo esc_attr($size) ?>"><?php echo $size ?></option>
							<?php
							} ?>
						</select>
					</div>
				</div>
			<?php
				}
				// =>
				if(isset($form->product_option['color']))
				{
					$colors = explode('|', $form->product_option['color']);
			?>
				<div class="item col-xs-12 col-sm-6 col-md-6 color">
					<div class="form-group">
						<label for="option_size"><?php _e('Màu', 'leo_product') ?></label>
						<select class="form-control" name="option[color]" id="option_size">
							<?php foreach($colors as $color)
							{ ?>
								<option value="<?php echo esc_attr($color) ?>"><?php echo $color ?></option>
							<?php
							} ?>
						</select>
					</div>
				</div>
			<?php
				}
			}
			?>
			<div class="item col-xs-12 col-sm-6 col-md-6 your-name">
				<div class="form-group">
					<label for="your_name"><?php _e('Họ tên', 'leo_product') ?> <span class="star">*</span></label>
					<input name="your_name" class="required form-control" id="your_name" required type="text" placeholder="" value="<?= esc_attr(_postdata('your_name')) ?>" />
				</div>
			</div>
			<div class="item col-xs-12 col-sm-6 col-md-6 address">
				<div class="form-group">
					<label for="address"><?php _e('Địa chỉ', 'leo_product') ?></label>
					<input name="address" class="form-control" id="address" type="text" placeholder="HCM" value="<?= esc_attr(_postdata('address')) ?>" />
				</div>
			</div>
			<div class="item col-xs-12 col-sm-6 col-md-6 tel">
				<div class="form-group">
					<label for="tel"><?php _e('SĐT', 'leo_product') ?> <span class="star">*</span></label>
					<input name="tel" class="required form-control" id="tel" type="text" required placeholder="+84" value="<?= esc_attr(_postdata('tel')) ?>" />
				</div>
			</div>
			<div class="item col-xs-12 col-sm-6 col-md-6 email">
				<div class="form-group">
					<label for="email"><?php _e('Email', 'leo_product') ?></label>
					<input name="email" class="form-control" id="email" type="email" placeholder="@" value="<?= esc_attr(_postdata('email')) ?>" />
				</div>
			</div>
			<div class="item col-xs-12 col-sm-12 col-md-12 message">
				<div class="form-group">
					<label for="message"><?php _e('Tin nhắn', 'leo_product') ?></label>
					<textarea name="message" cols="40" rows="2" class="form-control form-control" id="message"><?= esc_textarea(_postdata('message')) ?></textarea>
				</div>
			</div>
			<div class="item col-xs-12 col-sm-12 col-md-12 message">
				<!--recaptcha -->
					<div class="form-group recaptcha">
						<label class="required recaptcha-lbl"><?php _e('reCaptcha', 'tict_restaurant') ?> <span class="star">&nbsp;*</span></label>
						<div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>"></div>
						<script type="text/javascript"
								src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang; ?>">
						</script>
					</div>
				<!--//recaptcha -->
			</div>
		</div>
		<?php wp_nonce_field( 'ordering', '_ordering_nonce' ); ?>
		<input name="product_id" id="product_id" type="hidden" value=<?php echo $form->product_id ?> />
		<button class="btn btn-danger" id="submitForm" type="submit"><i class="fa fa-chevron-right"></i> <?php _e('Order Now', 'leo_product') ?></button>
	</fieldset>
</form>