
jQuery(document).ready(function(){
	// #######
	var heading = 0;
	jQuery('article h2, article h3, article h4').each(function(){
		if(!jQuery(this).attr('id'))
		{
			title = jQuery(this).text();
			name_id = safe_vietnamese(title);
			name_id = name_id.substring(0, 26);
			name_id += '-'+jQuery(this).prop('tagName').toLowerCase(); //+'_'+heading
			jQuery(this).attr('id', name_id);
			heading++;
		}
	});
	// #######
	jQuery('.gotop').click(function(){
		goToByScroll('#site-root');
	});

	jQuery('.form-validate').validate({
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
	jQuery('body').tooltip({
		selector: '.has-tips'
	});
	jQuery('article img.size-thumbnail, article img.size-medium, .thumb-sm img.size-thumbnail, .featured .images img').each(function(){
		cl='';
		if(jQuery(this).hasClass('alignright')) cl='alignright';
		else if((jQuery(this).hasClass('alignleft'))) cl='alignleft';

		jQuery(this).removeClass(cl);
		if(jQuery(this).parent().prop('tagName').toLowerCase()=='a')
		{
			jQuery(this).parent('a').addClass(cl+' thickbox').attr('rel','prettyPhoto');
		}
		else
		{
			src=this.src.replace(/-\d+x\d+/g,'');jQuery(this).wrap('<a class="'+cl+' thickbox" rel="prettyPhoto" href="'+src+'"></a>');
		}
	});
	
	jQuery('.entry-content table').addClass('table').width('100%');
	jQuery('.entry-content figure').addClass('figure');
	jQuery('.entry-content figure img').addClass('figure-img');
	jQuery('.entry-content figure figcaption').addClass('figure-caption');
	jQuery('#mainnav .menu-item-has-children').addClass('dropdown');
	jQuery('#mainnav .menu-item-has-children > a').append(' <span class="caret"></span>');
	//jQuery('#mainnav .menu-item-has-children > a').addClass('dropdown-toggle').attr('data-toggle', 'dropdown').append(' <span class="caret"></span>');
	jQuery('#mainnav .menu-item-has-children > ul.sub-menu').addClass('dropdown-menu');

	jQuery('ul.nav li.menu-item-has-children').hover(function(){
		jQuery(this).find('.sub-menu').stop(true, true).delay(100).fadeIn(100);
	}, function() {
		jQuery(this).find('.sub-menu').stop(true, true).delay(200).fadeOut(200);
	});

	$myCarousel = jQuery('.owl-carousel.do-animate');
	if($myCarousel.length>0)
	{
		var $firstAnimatingElems = $myCarousel.find('.item:first').find('[data-animation ^= "animated"]');
		doAnimations($firstAnimatingElems);
	}

	jQuery('.owl-carousel').each(function()
	{
		var items = (typeof jQuery(this).data('items') != 'undefined')? jQuery(this).data('items') : 1;
		var margin = (typeof jQuery(this).data('margin') != 'undefined')? jQuery(this).data('margin') : 0;
		var stagepadding = (typeof jQuery(this).data('stagepadding') != 'undefined')? jQuery(this).data('stagepadding') : 0;
		var loop = (typeof jQuery(this).data('loop') != 'undefined')? jQuery(this).data('loop') : true;
		var autoplay = (typeof jQuery(this).data('autoplay') != 'undefined')? jQuery(this).data('autoplay') : true;
		var autoplaytimeout = (typeof jQuery(this).data('autoplaytimeout') != 'undefined')? jQuery(this).data('autoplaytimeout') : 7000;
		var autoplayhoverpause = (typeof jQuery(this).data('autoplayhoverpause') != 'undefined')? jQuery(this).data('autoplayhoverpause') : true;
		var nav = (typeof jQuery(this).data('nav') != 'undefined')? jQuery(this).data('nav') : true;
		var dots = (typeof jQuery(this).data('dots') != 'undefined')? jQuery(this).data('dots') : false;

		var stagemarginleft = (typeof jQuery(this).data('stagemarginleft') != 'undefined')? jQuery(this).data('stagemarginleft') : 0;
		jQuery(this).owlCarousel({
			items: items,
			margin: margin,
			stagePadding: stagepadding,
			loop: loop,
			autoplay: autoplay,
			autoplayTimeout: autoplaytimeout,
			autoplayHoverPause: autoplayhoverpause,
			nav: nav,
			dots: dots,
			navText: [
				"<i class='fa fa-chevron-left'></i>",
				"<i class='fa fa-chevron-right'></i>"
			],
		});
		if(stagemarginleft) {
			jQuery(this).find('.owl-stage').css('margin-left', stagemarginleft);
		}
		
	});
	jQuery('.owl-next').click(function(){
		jQuery(this).closest('.owl-carousel').trigger('owl.next');
	});
	jQuery('.owl-prev').click(function(){
		jQuery(this).closest('.owl-carousel').trigger('owl.prev');
	});

	jQuery('a.byScroll').click(function(){
		goToByScroll(jQuery(this).attr('href'), 20);
		return false;
	});

	if(jQuery('.tax-description').length>0)
	{
		jQuery('.tax-description').readmore({
			//speed: 75,
			collapsedHeight: 300,
			moreLink: '<a class="btn-more btn btn-sm" href="#"><i class="fa fa-angle-right"></i> Read More</a>',
			lessLink: '<a class="btn-less btn btn-sm" href="#"><i class="fa fa-angle-left"></i> Read Less</a>',
			blockProcessed: function(element, collapsable) {
				if(collapsable)
				{
					jQuery('.tax-description').addClass('rm-collapsed');
				}
			},
			afterToggle: function(trigger, element, expanded) {
				if(expanded)
				{
					jQuery('.tax-description').removeClass('rm-collapsed');
				}
				else
				{
					jQuery('.tax-description').addClass('rm-collapsed');
				}
			}
		});
	}
	rsz();
});
function doAnimations(e)
{
	var animEndEv = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';

	e.each(function (){
		var $this = jQuery(this),
			$animationType = $this.data('animation');

		// Add animate.css classes to
		// the elements to be animated 
		// Remove animate.css classes
		// once the animation event has ended
		$this.addClass($animationType).one(animEndEv, function (){
			$this.removeClass($animationType);
		});
	});
}

jQuery(window).resize(rsz).scroll(scrl);
function scrl()
{
	az();
}
function rsz()
{
	az();
	var ww = jQuery(window).width();
	
	jQuery('.heightfix').each(function(){
		var r = jQuery(this).data('ratio') || 1;
		var w = jQuery(this).width();
		h = w/r;
		jQuery(this).height(h);
	});

	jQuery('.resizeautoheight').each(function(){
		var ele = jQuery(this);
		var $parent = ele.parent();
		var w = $parent.width();
		var img_w = ele.attr('width'), 
			img_h = ele.attr('height');

		if(!img_w || !img_h)
		{
			img_w = ele.width();
			img_h = ele.weight();
		}
		h = Math.floor(w*img_h/img_w);
		$parent.height(h);
		ele.css({
			'width': w,
			'height': h
		});
	});
	jQuery('.resizefix').each(function(){
		var ele = jQuery(this);
		var $parent = ele.parent();
		var w = $parent.width();
		var h = $parent.height();
		var img_w = ele.attr('width'), 
			img_h = ele.attr('height');

		var r = w/h;
		if(!img_w || !img_h)
		{
			img_w = ele.width();
			img_h = ele.weight();
		}
		if(img_w/img_h < r)
		{
			ele.css({
				'margin-top': Math.floor((h - w*img_h/img_w)/2),
				'margin-left': 'auto',
				'width': w,
				'height': 'auto'
			});
		}
		else
		{
			ele.css(
			{
				'margin-top': 'auto',
				'margin-left': Math.floor((w - h*img_w/img_h)/2),
				'width': 'auto',
				'height': h,
				'max-width': 'none'
			});
		}
	});
	
	jQuery('.resizeover').each(function(){
		var ratio = jQuery(this).data('ratio') || 1;
		szover(jQuery(this), ratio);
	});

	function szover(ele, r)
	{
		var $parent = ele.parent();
		var w = $parent.outerWidth();
		var h = Math.floor(w/r);
		var crop = (typeof ele.data('crop') != 'undefined')? ele.data('crop') : true;
		var img_w = ele.attr('width'), 
			img_h = ele.attr('height');

		if(!img_w || !img_h)
		{
				img_w = ele.outerWidth();
				img_h = ele.outerHeight();
		}

		//$parent.width(w);
		$parent.height(h);
		if((img_w/img_h < r) && crop==true)
		{
			ele.css({
				'margin-top': Math.floor((h - w*img_h/img_w)/2),
				'margin-left': 'auto',
				'width': w,
				'height': 'auto'
			});
		}
		else
		{
			ele.css(
			{
				'margin-top': 'auto',
				'margin-left': Math.floor((w - h*img_w/img_h)/2),
				'width': 'auto',
				'height': h,
				'max-width': 'none'
			});
		}
	}


	jQuery('.slide').each(function(){
		w = jQuery(this).width();
		r = jQuery(this).data('ratio') || 2;
		id = jQuery(this).attr('id');
		jQuery('#'+id+' .image img').each(function(){
			szoverslide(jQuery(this), r, w);
		});
	});

	function szoverslide(ele, r, w)
	{
		if(ww<=768)
		{
			if(r >= 2)
			{
				r = 4/3;
			}
		}
		var h = Math.floor(w/r);
		var $parent = ele.parent();
		if($parent.length > 0)
		{

			var img_w = ele.attr('width'), 
				img_h = ele.attr('height');

			if(!img_w || !img_h)
			{
				img_w = ele.outerWidth();
				img_h = ele.outerHeight();
			}

			//$parent.width(w);
			$parent.height(h);
			if(img_w/img_h < r)
			{
				ele.css({
					'margin-top': Math.floor((h - w*img_h/img_w)/2),
					'margin-left': 'auto',
					'width': w,
					'height': 'auto'
				});
			}
			else
			{
				ele.css(
				{
					'margin-top': 'auto',
					'margin-left': Math.floor((w - h*img_w/img_h)/2),
					'width': 'auto',
					'height': h,
					'max-width': 'none'
				});
			}
		}
	}
}
function az()
{
	var ww = jQuery(window).width();
	if(ww>768)
	{
		jQuery('.mainnav').removeClass('navbar-fixed-top');
		jQuery('.mainnav .navbar-brand').removeClass('small');
	}
	else
	{
		jQuery('.mainnav').addClass('navbar-fixed-top');
		jQuery('.mainnav .navbar-brand').addClass('small');
	}
}
function goToByScroll(id, offset)
{
	var offset = offset | 0;
	jQuery('html,body').animate( { scrollTop: jQuery(id).offset().top - offset}, 'fast' );
}