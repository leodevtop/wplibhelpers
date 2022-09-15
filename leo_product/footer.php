		<?php $menu_locations = get_nav_menu_locations(); ?>
		<footer id="main-footer">
			<div class="footer-menu">
				<div class="container">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-3 col-lg-2 hidden-xs hidden-sm">
							<?php
							$menu_name = 'footer';
							if(has_nav_menu($menu_name)):
								$menu_id = $menu_locations[$menu_name];
								$menu = wp_get_nav_menu_object($menu_id);
							?>
							<h4><?php echo $menu->name ?></h4>
							<?php
								wp_nav_menu(array(
									'theme_location' => $menu_name,
									'menu_class'     => 'menu nav',
									'depth' => 1,
								));
							?>
							<?php endif; ?>
						</div>
						<div class="hidden-xs hidden-sm col-md-3 col-lg-3">
							<?php
							$menu_name = 'bottom1';
							if(has_nav_menu($menu_name)):
								$menu_id = $menu_locations[$menu_name];
								$menu = wp_get_nav_menu_object($menu_id);
							?>
							<h4><?php echo $menu->name ?></h4>
							<?php
								wp_nav_menu(array(
									'theme_location' => $menu_name,
									'menu_class'     => 'menu nav',
									'depth' => 1,
								));
							?>
							<?php endif; ?>
						</div>
						<div class="col-sm-12 col-md-3 col-lg-3">
							<h4><?php _e('Follow Us', 'leo_product') ?></h4>
							<div class="social clearfix">
								<?php if ( get_theme_mod('fb_link') != ""): ?><a target="_blank" href="<?php echo esc_url(get_theme_mod('fb_link')); ?>"><i class="fa fa-facebook"></i></a><?php endif ?>
								<?php if ( get_theme_mod('gplus_link') != ""): ?><a target="_blank" href="<?php echo esc_url(get_theme_mod('gplus_link')); ?>"><i class="fa fa-google-plus"></i></a><?php endif ?>
								<?php if ( get_theme_mod('twitt_link') != ""): ?><a target="_blank" href="<?php echo esc_url(get_theme_mod('twitt_link')); ?>"><i class="fa fa-twitter"></i></a><?php endif ?>
								<?php if ( get_theme_mod('youtube_link') != ""): ?><a target="_blank" href="<?php echo esc_url(get_theme_mod('youtube_link')); ?>"><i class="fa fa-youtube"></i></a><?php endif ?>
								<?php if ( get_theme_mod('instagram_link') != ""): ?><a target="_blank" href="<?php echo esc_url(get_theme_mod('instagram_link')); ?>"><i class="fa fa-instagram"></i></a><?php endif ?>
							</div>
						</div>
						<div class="col-sm-12 col-md-3 col-lg-4">
							<h4><?php _e('Contact Us', 'leo_product') ?></h4>
							<p>
								<?php $_address = 'address'; if(function_exists('pll_current_language')) { $_address .= '_'.pll_current_language(); } ?>
								<?php echo get_theme_mod($_address) ?>
								<span class="hidden"><br /><i class="fa fa-phone"></i> <?php echo get_theme_mod('telephone') ?></span>
								<br /><i class="fa fa-envelope"></i>  <a href="mailto:<?php echo get_theme_mod('email') ?>"><?php echo get_theme_mod('email') ?></a>
							</p>
						</div>
					</div>
				</div><!-- /.container -->
			</div><!-- /.footer-menu -->
			<div class="footer-copy">
				<div class="container">
					<p class="pull-left hidden-xs hidden-sm">&copy; <?php echo date('Y'); ?> <a href="<?php echo home_url('/') ?>"><?php echo $_SERVER['SERVER_NAME']; ?></a> All Rights Reserved</p>
					<p class="text-center lead hidden"><a href="tel:<?php echo preg_replace("/[^0-9+]/", '', get_theme_mod('telephone')) ?>"><i class="fa fa-phone"></i> <?php echo get_theme_mod('telephone') ?></a></p>
				</div><!-- /.container -->
			</div><!-- /.footer-copy -->
		</footer><!-- /#main_footer -->
</div><!-- /#wapper.site -->

<a href="javascript:;" class="gotop"><i class="fa fa-arrow-up"></i></a>
<?php wp_footer(); ?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-12331311-24', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>
