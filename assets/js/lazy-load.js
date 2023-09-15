(function($) {
	$.fn.isInViewport = function() {
		var elementTop = $(this).offset().top;
		var elementBottom = elementTop + $(this).outerHeight();
		var viewportTop = $(window).scrollTop();
		var viewportBottom = viewportTop + $(window).height();
		var viewportBottomPreLoad = viewportTop + $(window).height() * 1.2;
		var viewportTopPreLoad = viewportTop - $(window).height() * .2;
		if(elementBottom > viewportTop && elementTop < viewportBottom){//if in viewport
			return '1';
		} else if(elementBottom > viewportTop && elementTop < viewportBottomPreLoad){//if 20% of viewport height below the bottom of the viewport
			return '2';
		} else if(elementBottom > viewportTopPreLoad && elementTop < viewportBottom){
			return '3';
		}else{
			return false;
		}
	};
	var lastItem = 'inViewport';
	var displayItemsInViewport = function() {
		var windowHeight = $(window).height();//assign value to variable to avoid calling window.height multiple times
		$('.lazy-hidden').each(function(index){
			var inViewport = $(this).isInViewport();//assign value to variable to avoid calling isInViewport multiple times
			if (inViewport == '1' || inViewport == '2' || inViewport == '3'){
				runningFlag = true;//set running flag to true
				$(this).addClass('loading');
				var lazyItem = $(this).find('.lazy-container')[0];//get lazy-item
				var placeholder = $(lazyItem).find('.lazy-container')[0];//get placeholder image
				var lrgImg = $(new Image()).attr('src', '' + $(lazyItem).data('img'));//create new image, assign it's source to the data attr of $this
				$(lrgImg).on('load', function(){//when the new image we created is loaded, add class 'loaded'
					$(this).closest('.lazy-item').removeClass('loading');
					$(this).addClass('loaded');
				});
				$(lrgImg).appendTo($(lazyItem));//add new image to the lazy item container
				$(this).addClass('lazy-active');//add active class
				$(this).removeClass('lazy-hidden');//remove hidden class
				runningFlag = false;//reset running flag back to false
			}
		});
	}
	$(function(){//on page load
		displayItemsInViewport();
	});
	$(window).on('resize scroll orientationchange', function() {//on scroll/resize/orientation-change
		displayItemsInViewport();
	});


	//Home Slider
	var nextSlide = function() {
		var currentSlide = $('.banner .slide.active').first();
		var currentOverlay = $('.banner .slide-overlay.active').first();
		if (currentSlide.index('.banner .slide') + 1 == $('.banner .slide').length) {
			$('.banner .slide').first().addClass('active');
			window.setTimeout(function() {
				$('.banner .slide-overlay').first().addClass('active');
			}, 700);
		} else {
			currentSlide.next('.banner .slide').addClass('active');
			window.setTimeout(function() {
				currentOverlay.next('.banner .slide-overlay').addClass('active');
			}, 500);
		}
		currentSlide.removeClass('active');
		currentOverlay.removeClass('active');
		var interval = currentSlide.addClass('sliding');
		window.setTimeout(function() {
			currentSlide.removeClass('sliding');
		}, 700);
	};
	var preSlide = function() {
		var currentSlide = $('.banner .slide.active').first();
		var x = currentSlide.index('.banner .slide');
		if (currentSlide.index('.banner .slide') == $('.banner .slide').length) {
			$('.banner .slide').first().addClass('active');
		} else if (x == 0) {
			var y = $('.banner .slide').last();
			$(y).addClass('active');
		} else {
			currentSlide.prev('.banner .slide').addClass('active');
		}
		currentSlide.removeClass('active');
		currentSlide.addClass('sliding');
		window.setTimeout(function() {
			currentSlide.removeClass('sliding');
		}, 700);
	};
	$('.carousel-control-next').click(preSlide);
	$('.carousel-control-prev').click(nextSlide);
	$(function() {
		IntID = setTimer();
		function setTimer() {
			i = setInterval(nextSlide, 4000);
			return i;
		}
		function stopSlider() {
			clearInterval(IntID);
		}
		function restartSlider() {
			IntID = setTimer();
		}
		$('#banner').on('mouseleave mouseenter', function() {
			$(this).toggleClass('dont-run');
			if (!$('#banner').hasClass('dont-run')) {
				restartSlider();
			}
			if ($('#banner').hasClass('dont-run')) {
				stopSlider();
			}
		});
		if ($('#testimonials-section').length !== 0)
			matchTestimonialHeight();
	});
	$(window).on('load', function() {
		if ($('body').hasClass('page-template-page-home')) {
			var aspectRatio = $('.banner .slide.active').data('height');
			var bannerHeight = $('.banner .slide.active').outerHeight();
		} else {
			var aspectRatio = $('#banner img').data('height');
			var bannerHeight = $('#banner').outerHeight();
		}
		$('#banner .images').css('padding-bottom', aspectRatio + '%');
		$('#page-content').css('margin-top', bannerHeight + 'px');
		$(window).resize(function() {
			var bannerHeight = $('#banner').outerHeight();
			$('#page-content').css('margin-top', bannerHeight + 'px');
			$('.scroll-down').css('top', bannerHeight - 75 + 'px')
		});
		if (window.location.hash) {
			scrollPageToAnchor(window.location.hash);
		}
	});

	//Side Nav Dropdown Menu Stuff
	$('li.menu-item-has-children').mouseover(function() {
		$('.dropdown-menu').css('display', 'block');
	});
	$('li.menu-item-has-children').mouseout(function() {
		$('.dropdown-menu').css('display', 'none');
	});
	$('#nav-toggler').click(function() {
		$('#menu-wrap').slideToggle('300');
		$(this).toggleClass('active');
	});
	var width = $(window).outerWidth();
	if (width <= 991) {
		$('.sub-menu').parent().addClass('dropdown-toggle');
		$('.sub-menu').prepend('<span class="back"></span>');
		$('.dropdown-toggle>a').append('<span class="caret"></span>');
		$('.nav-toggler').click(function() {
			$('.sub-menu').removeClass('slide-in-right');
			$('.sub-menu').removeClass('slide-out-right');
		});
		$('.caret').click(function() {
			event.preventDefault();
			event.stopImmediatePropagation();
			var dropdown = $(this).parent().parent();
			var subMenu = dropdown.children('.sub-menu');
			subMenu.removeClass('slide-out-right');
			subMenu.addClass('slide-in-right');
		});
		$('.back').click(function() {
			$(this).parent().toggleClass('slide-in-right');
			$(this).parent().toggleClass('slide-out-right');
		});
		var navHeight = $('.navbar.navbar-default').outerHeight();
		if ($(window).height() < navHeight) {
			$('ul.nav-list a').css({
				'font-size': '12px'
			});
			$('ul.nav-list li').css({
				'padding': '5px 0'
			});
			$('.back').css({
				'font-size': '14px'
			});
			$('.caret').css({
				'font-size': '16px'
			});
		}
	}
	var hasThePageLoaded = false;
	var hasViewPortBeenResized = false;
	function miscAssignCSS(x) {
		var cssObj = {
			'min-height': x,
			'height': x,
			'display': 'flex',
			'flex-direction': 'column'
		}
		$('.page-content-inner').css(cssObj);
		$('#main').css('flex', '1 0 auto');
	}

	function timeoutPushTheFooter() {
		if (!hasThePageLoaded) {
			setTimeout(function() {
				pushTheFooter();
			}, 300);
		} else {
			pushTheFooter();
		}
	}
	function pushTheFooter() {
		var windowHeight = $(window).outerHeight();
		var windowWidth = $(window).outerWidth();
		var mainNavHeight = 0;
		var socialHeight = 0;
		var mottoHeight = 0;
		var bannerHeight = 0;
		var x = 0;
		var y = $('#page-content').outerHeight() + $('#banner').outerHeight();
		if (y < windowHeight || hasViewPortBeenResized) {
			hasViewPortBeenResized = true;
			if (windowWidth <= 991) {
				if ($('#main-nav').length) {
					mainNavHeight = $('#main-nav').outerHeight();
				}
				if ($('.social').length) {
					socialHeight = $('.social').outerHeight();
				}
				if ($('.motto').length) {
					mottoHeight = $('.motto').outerHeight();
				}
				if ($('#banner').length) {
					bannerHeight = $('#banner').outerHeight();
				}
				x = windowHeight - (mainNavHeight + socialHeight + mottoHeight + bannerHeight);
				miscAssignCSS(x);
			} else {
				var bannerHeight = $('#banner').outerHeight();
				x = windowHeight - (bannerHeight);
				miscAssignCSS(x);
			}
		}
		if ($('#main').outerHeight() > $('.page-content-inner').outerHeight()) {
			$('.page-content-inner').css('height', 'auto');
		}
		hasThePageLoaded = true;
	}
	$(document).ready(function() {
		timeoutPushTheFooter();
	});
	$(window).resize(timeoutPushTheFooter);
})(jQuery);
