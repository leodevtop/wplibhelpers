<?php

// RUN >
$is_valid_nonce = (isset($_POST['_product_order_nonce']) && wp_verify_nonce($_POST['_product_order_nonce'], 'product_order'));
if($is_valid_nonce)
{
	global $wpdb;
	$meta = array();
	$meta['guest_type'] = _postdata('guest_type');
	$meta['full_name'] = _postdata('full_name');
	$meta['email'] = sanitize_email(_postdata('email'));
	$meta['address'] = _postdata('address');
	$meta['city'] = _postdata('city');
	$meta['tel'] = _postdata('tel');
	$meta['message'] = _postdata('message');
	$meta['products'] = _postdata('products');
	$meta['order_time'] = date('Y-m-d h:m:s');
	$meta['optional_service'] = _postdata('optional_service');
	//$wpdb->insert($wpdb->prefix.'tour_orders', $meta);
	$user_id = get_current_user_id();
	$program = get_post($meta['program_id']);
	$my_post = array(
		'post_author' => $user_id,
		'post_content' => $meta['message'],
		'post_content_filtered' => '',
		'post_title' => wp_strip_all_tags('['.$meta['first_name'] .' '. $meta['last_name'].' - '.$meta['departure'].'] '.$program->post_title),
		'post_excerpt' => '',
		'post_status' => 'publish',
		'post_type' => 'program_order', //
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
			<tbody>
				<tr>
					<td style="width: 10%;"><strong><?php _e('P', 'tict_cruise') ?></strong></td>
					<td>
						<?php echo $program->post_title ?> <a class="has-tips" target="_blank" title="<?php esc_attr(_e('View details', 'tict_cruise')) ?>" href="<?php echo get_the_permalink($program) ?>"><span class="sr-only"><?php _e('View details', 'tict_cruise') ?> </span><i class="fa fa-external-link-square"></i></a>
					</td>
				</tr>
				<tr>
					<td><strong><?php _e('Price', 'tict_cruise') ?></strong></td>
					<td>
						<?php _e('from', 'tict_cruise') ?> <?php echo sprintf(__('<strong>%s</strong>$US/person', 'tict_cruise'), number_format(intval(get_post_meta($meta['program_id'], 'price', true)))); ?>
					</td>
				</tr>
				<tr>
					<td><strong><?php _e('Duration', 'tict_cruise') ?></strong></td>
					<td><?php _e($meta['duration'], 'tict_cruise'); ?></td>
				</tr>
				<tr>
					<td><strong><?php _e('Departure date', 'tict_cruise') ?></strong></td>
					<td><?php _e($meta['departure'], 'tict_cruise'); ?></td>
				</tr>
				<tr>
					<td><strong><?php _e('Person', 'tict_cruise') ?></strong></td>
					<td>
						<?php echo $meta['num_adult'].' '.__('Adult', 'tict_cruise'); ?>
						<?php if($num_child = $meta['num_child']) echo ' <em>'.__('and', 'tict_cruise').'</em> '.$num_child.' '.__('Children', 'tict_cruise'); ?>
					</td>
				</tr>
				<?php if($optional_service = $meta['optional_service']): ?>
				<tr>
						<td><strong><?php _e('Optional service', 'tict_cruise') ?></strong></td>
						<td>
							<ul>
							<?php foreach($optional_service as $service): ?>
								<li><?php echo $service ?></li>
							<?php endforeach ?>
							</ul>
						</td>
					</tr>
				<?php endif ?>
			</tbody>
		</table>
		<h3><?php _e('Guest Details', 'tict_cruise') ?></h3>
		<table class="table">
			<tbody>
				<tr>
					<td style="width: 10%;"><strong><?php _e('Your name', 'tict_cruise') ?></strong></td>
					<td><?php echo $meta['guest_type'].' '.$meta['first_name'].' '.$meta['last_name'] ?></td>
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
					<td><strong><?php _e('Nationality', 'tict_cruise') ?></strong></td>
					<td><?php echo $country[$meta['country']] ?></td>
				</tr>
				<tr>
					<td><strong><?php _e('Additional message', 'tict_cruise') ?></strong></td>
					<td><em><?php echo wpautop($meta['message']); ?></em></td>
				</tr>
			</tbody>
		</table>
		<style type="text/css">
		.table{width:100%;max-width:100%;border:1px solid #ccc;margin-bottom:10px;border-collapse:collapse;border-spacing:0;}
		.table td{border-top:1px solid #ddd;padding:8px;vertical-align:top;}
		.btn{border:1px solid #d43f3a;padding:10px 16px;background-color:#BD2927;color:#fff;font-size:18px;vertical-align:middle;border-radius:6px;cursor:pointer;}
		</style>
		<?php
		$booking_detail = ob_get_clean();

		$sys_email = get_option('admin_email');
		$headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: '.$_SERVER['SERVER_NAME'].' <'.$sys_email.'>',
			'Cc: Admin <'.$sys_email.'>'
		);
		$subj = $_SERVER['SERVER_NAME'].' - Booking ID'.$post_id;
		$link_order = get_permalink($post_id);
		$body = sprintf(__('<p>Dear, %s</p><p>Thank you for booking our tour!</p><p><a class="btn" href="%s">&gt; Deposit Now</a></p>%s<p>%s</p>', 'tict_cruise'), $meta['guest_type'].' '.$meta['first_name'].' '.$meta['last_name'], $link_order, $booking_detail, get_option('blogname').' ('.$_SERVER['SERVER_NAME'].')');
		wp_mail($meta['email'], $subj, $body, $headers); // SEND
		// ->
		wp_redirect($link_order.'?thankyou=1');
	}
	if(!function_exists('_myReplace'))
	{
		function _myReplace($txt, $search=array())
		{
			foreach($search as $k=>$v)
			{
				$txt = str_replace($k,$v,$txt);
			}
			return $txt;
		}
	}
}

//wp_enqueue_script('jquery-ui-datepicker');
if(!isset($is_short_code) || $is_short_code !== true):
	get_header();
endif;

$program_id = _postdata('program_id')? _postdata('program_id') : _getdata('program_id');
$departure = _postdata('departure')? _postdata('departure') : _getdata('departure');
$duration = _postdata('duration')? _postdata('duration') : _getdata('duration');
$optional_service = _postdata('optional_service')? _postdata('optional_service') : _getdata('optional_service');
?>

<main id="main">
	<div class="container main-container tour-plg tour-booking">
		<?php while(have_posts()):the_post();?>
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
				<table class="table">
					
				</table>
				<fieldset>
					<legend><?php _e('Which program do you want to book?', 'tict_cruise') ?></legend>
					<div class="row row-list">
						<div class="col-xs-12 col-sm-12 col-md-9">
							<?php
							$type = 'program';
							$args = array(
								'post_type' => $type,
								'posts_per_page' => 20,
								'order' => 'ASC',
								'orderby' => 'meta_value',
								'meta_key' => 'program_type',
							); /* extra, hotdeal, normal */

							$programs = array();
							$program_types = array('hotdeal', 'fixed', 'other');
							$program_options = array();
							$my_query = null;
							$my_query = new WP_Query($args);
							if($my_query->have_posts()):
								while($my_query->have_posts()):
									$my_query->the_post();
									$program = new stdClass();
									$program->ID = $post->ID;
									$program->title = $post->post_title;
									$program->price = get_post_meta($post->ID, 'price', true);
									$program->calendar = get_post_meta($post->ID, 'calendar', true);
									$program->duration = get_post_meta($post->ID, 'duration', true);
									$program->optional_service = get_post_meta($post->ID, 'optional_service', true);
									$programs[$post->ID] = $program;
									$_program_type = get_post_meta($post->ID, 'program_type', true);
									if(!$_program_type || $_program_type=='normal') $_program_type = 'fixed';
									if($_program_type=='extra') $_program_type = 'other';
									if(in_array($_program_type, $program_types))
									{
										$program_options[$_program_type][] = $program;
									}
								endwhile;
								wp_reset_query();
							?>
							<div class="form-group program_id">
								<label class="required" for="program_id"><?php _e('Programs', 'tict_cruise') ?> <span class="star">&nbsp;*</span></label>
								<select required name="program_id" id="program_id" class="form-control required">
									<?php foreach($program_types as $program_type):
										if(isset($program_options[$program_type])): ?>
										<optgroup label="<?php _e(ucfirst($program_type).' programs', 'tict_cruise') ?>">
										<?php foreach($program_options[$program_type] as $program): ?>
											<option <?php if($program_id == $program->ID) echo ' selected' ?> value="<?php echo $program->ID ?>"><?php echo $program->title; ?></option>
										<?php endforeach; //program_types ?>
										</optgroup>
										<?php endif; //isset program_options
									endforeach; //program_options ?>
								</select>
							</div>
							<?php endif; //$my_query->have_posts() ?>
							<div class="form-group tour_price">
								<label for="tour_price"><?php _e('Price', 'tict_cruise') ?>:</label>
								<?php _e('from', 'tict_cruise') ?> <span id="per_price-span">0</span><?php echo str_replace('%s', '', __('<strong>%s</strong>$US/person', 'tict_cruise')); ?>
								<input type="hidden" name="per_price" value="0" id="per_price" />
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group duration hidden">
								<label class="required" for="duration"><?php _e('Duration', 'tict_cruise') ?> <span class="star">&nbsp;*</span></label>
								<div class="durations"></div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-3">
							<div class="form-group departure">
								<label class="required" for="departure"><?php _e('Departure date', 'tict_cruise') ?> <span class="star">&nbsp;*</span></label>
								<div class="input-group">
									<input type="text" class="form-control departure date_input" required id="departure" name="departure" value="<?php echo esc_attr($departure) ?>" placeholder="YYYY-mm-dd" />
									<span class="input-group-btn">
										<a class="btn btn-default"><i class="fa fa-calendar"></i><span class="sr-only"> <? __('Select', 'tict_cruise') ?></span></a>
									</span>
								</div><!-- /input-group -->
								<div class="date_tips small"><i class="star">*</i> <?php _e('Available date only', 'tict_cruise') ?></div>
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-1">
							<div class="form-group num_adult">
								<label class="required" for="num_adult"><?php _e('Adult', 'tict_cruise') ?> <span class="star">&nbsp;*</span></label>
								<input type="number" required min="1" step="1" max="20" class="form-control change_price required" value="2" id="num_adult" name="num_adult" />
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-1">
							<div class="form-group num_child">
								<label for="num_child"><?php _e('Child', 'tict_cruise') ?></label>
								<input type="number" min="0" step="1" max="10" class="form-control" value="0" id="num_child" name="num_child" />
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group optional_service">
								<label class="optional_service-lbl" for="optional_service"><?php _e('Optional Services', 'tict_cruise') ?></label>
								<div class="services"></div>
								<!-- here -->
							</div>
						</div>
					</div>
				</fieldset>
				<fieldset class="tour_guest_form">
					<legend><?php _e('Tell Us about Yourself', 'tict_cruise') ?></legend>
					<label class="full_name-lbl" for="full_name"><?php _e('Your name', 'tict_cruise') ?> <span class="star">&nbsp;*</span></label>
					<div class="row">
						<div class="col-md-1">
							<div class="form-group guest_type">
								<select name="guest_type" id="guest_type" class="form-control">
									<option value="Mr."><?php _e('Mr.', 'tict_cruise') ?></option>
									<option value="Ms."><?php _e('Ms.', 'tict_cruise') ?></option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group first_name">
								<input type="text" class="form-control" pattern=".{3,50}" required id="first_name" name="first_name" value="<?php echo _postdata('first_name') ?>"  placeholder="<?php _e('First name', 'tict_cruise') ?>" />
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group last_name">
								<input type="text" class="form-control" pattern=".{3,50}" id="last_name" name="last_name" value="<?php echo _postdata('last_name') ?>"  placeholder="<?php _e('Last name', 'tict_cruise') ?>" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-group email">
								<label class="required email-lbl" for="email"><?php _e('Email', 'tict_cruise') ?> <span class="star">&nbsp;*</span></label>
								<input type="email" class="form-control" required id="email" name="email" value="<?php echo _postdata('email') ?>" />
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group tel">
								<label class="tel-lbl" for="tel"><?php _e('Tel', 'tict_cruise') ?></label>
								<input type="text" class="form-control" pattern=".{9,50}" id="tel" name="tel" value="<?php echo _postdata('tel') ?>" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group address">
								<label class="required address-lbl" for="address"><?php _e('Address', 'tict_cruise') ?> <span class="star">&nbsp;*</span></label>
								<input type="text" class="form-control" pattern=".{3,200}" required id="address" name="address" value="<?php echo _postdata('address') ?>" />
							</div>
						</div>
						<div class="col-md-2">
							<div class="form-group city">
								<label class="required city-lbl" for="city"><?php _e('City', 'tict_cruise') ?> <span class="star">&nbsp;*</span></label>
								<input type="text" class="form-control" pattern=".{3,100}" required id="city" name="city" value="<?php echo _postdata('city') ?>" />
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group country">
								<label class="required country-lbl" for="country"><?php _e('Nationality', 'tict_cruise') ?> <span class="star">&nbsp;*</span></label>
								<select name="country" required id="country" class="form-control">
									<option value=""><?php _e('- Select country -', 'tict_cruise') ?></option>
									<?php foreach($country as $k=>$v): ?>
										<option value="<?php echo $k ?>"<?php if($k == _postdata('country')) echo ' selected' ?>><?php echo $v ?></option>
									<?php endforeach ?>
								</select>
							</div>
						</div>
					</div>
				</fieldset>
				<fieldset class="tour_guest_form">
					<legend><?php _e('Additional message', 'tict_cruise') ?></legend>
					<div class="form-group message">
						<textarea class="form-control" id="message" name="message" rows="5" /><?php echo _postdata('message') ?></textarea>
					</div>
					<div class="form-group message">
						<?php wp_nonce_field('product_order', '_product_order_nonce'); ?>
						<button class="btn btn-danger btn-lg" type="submit"><?php _e('Book Now', 'tict_cruise'); ?></button>
					</div>
				</fieldset>
				<div role="alert" class="payment_tips alert alert-warning alert-dismissible fade in">
					<button aria-label="Close" data-dismiss="alert" class="close" type="button">
					<span aria-hidden="true">&times;</span>
					</button>
					<p><?php _e('Submitting this form <strong>DOES NOT</strong> mean that your tour is booked. Your booking is only finalized once you receive and reply to our confirmation email. If you do not hear back from us within 24 hours of your booking request, please check your SPAM or JUNK MAIL folder.', 'tict_cruise') ?></p>
				</div>
				<input type="hidden" name="order_id" value="<?php echo $program_id ?>" />
			</form>

			<script type="text/javascript">

			var weekday = new Array(
				'SUN',
				'MON',
				'TUE',
				'WED',
				'THU',
				'FRI',
				'SAT'
			);

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

			programs = <?php echo json_encode($programs) ?>;
			departure = <?php echo json_encode($departure) ?>;
			optional_service = <?php echo json_encode($optional_service) ?>;
			jQuery(document).ready(function(){
				jQuery('#num_adult').change(function(){ jQuery('.service_qty').val(jQuery(this).val()); });
				tour_select();
				jQuery('#program_id').change(function(){ tour_select(); });
			});

			jQuery("#booking").validate({
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
					//jQuery('.date').prop("readonly", false);
					return true;
				}
			});
			function tour_select()
			{
				program_id = jQuery('#program_id').val();
				program = programs[program_id];
				jQuery('.date_input').datepicker({
					minDate: 0,
					dateFormat : 'yy-mm-dd',
					//defaultDate: new Date(departure),
					beforeShowDay: function(date){
						//var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
						var todaysDay = date.getDay() + 1;					// Stores the current day number 1-7
						var todaysDate = date.getDate();					// Stores the current numeric date within the month
						var todaysMonth = date.getUTCMonth() + 1;				// Stores the current month 1-12
						var todaysYear = date.getFullYear();					// Stores the current year
						if(typeof program.calendar == 'undefined')
						{
							return true;
						}
						if(program.calendar.type=='selected')
						{
							str_today = pad(todaysYear.toString(), 4)+'-'+pad(todaysMonth.toString(), 2)+'-'+pad(todaysDate.toString(), 2);
							vals = program.calendar.val.split(/\n/);
							vals = vals.map(Function.prototype.call, String.prototype.trim);
							return [(jQuery.inArray(str_today, vals) !== -1)];
						}
						else
						{
							return [(program.calendar.type=='weekly' && jQuery.inArray(todaysDay.toString(), program.calendar.val) !== -1)
								|| (program.calendar.type=='monthly' && jQuery.inArray(todaysDate.toString(), program.calendar.val) !== -1)];
						}
					}
				}).val(departure);
				jQuery('.date_input').siblings('.input-group-btn').click(function(){ jQuery('.date_input').datepicker('show'); });
				price = parseInt(program.price);
				jQuery('#per_price').val(price);
				jQuery('#per_price-span').text(price.formatMoney(0));
				if(typeof program.optional_service == 'object')
				{
					html = '';
					jQuery.each(program.optional_service, function(key, item)
					{
						html += '<div class="service"> <label for="optional_service_'+key+'"><input type="checkbox" id="optional_service_'+key+'" name="optional_service[]" value="'+item.title+'" /> '+item.title+'</label> <small>'+item.info+'</small></div>';
					});
					jQuery('.services').html(html);
				}
				if(typeof program.duration == 'object')
				{
					html = '<select required name="duration" id="duration" class="form-control required" style="width: auto;">';
					jQuery.each(program.duration, function(key, item)
					{
						html += '<option value="'+item+'">'+item+'</option>';
					});
					jQuery('.durations').html(html);
				}
			}

			</script>
		</section>
		<?php //get_sidebar();?>
	</div><!-- main-container -->
</main>
<?php

if(!isset($is_short_code) || $is_short_code !== true)
{
	get_footer();
}