<?php get_header(); ?>

<div class="container">
	<main id="main">
		<div class="main-container">
			<?php
			while(have_posts()):
				the_post();

				if($page_booknow = get_page_by_path('book-now'))
				{
					if(function_exists('pll_get_post'))
					{
						$page_booknow = pll_get_post($page_booknow->ID);
					}
				}
				if($page_secure_payment = get_page_by_path('secure-payment'))
				{
					if(function_exists('pll_get_post'))
					{
						$page_secure_payment = pll_get_post($page_secure_payment->ID);
					}
				}
				if($page_secure_payment_response = get_page_by_path('secure-payment-response'))
				{
					if(function_exists('pll_get_post'))
					{
						$page_secure_payment_response = pll_get_post($page_secure_payment_response->ID);
					}
				}
				$order_id = str_pad(get_the_ID(), 5, "0", STR_PAD_LEFT);
			?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="breadcrumbs clearfix">
						<ol class="breadcrumb" itemprop="breadcrumb">
							<li typeof="v:Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="v:url" property="v:title"><span class="fa fa-home"></span></a></li>
							<li typeof="v:Breadcrumb" class="active"><a href="<?php echo get_the_permalink($page_booknow) ?>" rel="v:url" property="v:title"><?php _e('Order', 'tict_cruise') ?></a></li>
						</ol>
					</div><!-- breadcrumbs -->
					<header class="entry-header">
						<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php if(_getdata('thankyou') == 1): ?>
						<div role="alert" class="payment_tips alert alert-warning alert-dismissible fade in">
							<button aria-label="Close" data-dismiss="alert" class="close" type="button">
							<span aria-hidden="true">&times;</span>
							</button>
							<p><?php echo __(get_post_meta(get_the_ID(), 'guest_type', true), 'tict_cruise').' '.get_post_meta(get_the_ID(), 'full_name', true).'! '.__('Thank you for your booking. It has been sent!<br />Submitting this form <strong>DOES NOT</strong> mean that your tour is booked. Your booking is only finalized once you receive and reply to our confirmation email. If you do not hear back from us within 24 hours of your booking request, please check your SPAM or JUNK MAIL folder.', 'tict_cruise') ?></p>
						</div>
						<?php endif ?>
						<h3><?php _e('Details', 'tict_cruise').', ID'.$order_id ?></h3>
						<div class="row">
							<div class="col-md-8">
								<table class="table" style="width:100%">
									<thead>
										<tr>
											<th><?php _e('Product list', 'leo_product') ?></th>
											<th width="10%"><?php _e('Quantity', 'leo_product') ?></th>
											<th width="15%"><?php _e('Total', 'leo_product') ?></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach(get_post_meta(get_the_ID(), 'product', true) as $k=>$product):
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
							</div>
							<div class="col-md-4">

							</div>
						</div>

					</div><!-- .entry-content -->
				</article><!-- #post-## -->
			<?php endwhile; ?>


		</div><!-- main-container -->
	</main>

	<?php //get_sidebar( 'content-bottom' ); ?>
</div><!-- .container -->

<?php get_footer(); ?>