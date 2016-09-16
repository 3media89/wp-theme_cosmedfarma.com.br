jQuery(function ($) {
	$(document).ready(function() {
		var figures = [];
		$('div.gallery figure').each(function() {
			figures[figures.length] = {
				img: $(this).find('a').attr('href'),
				thumb: $(this).find('img').attr('src')
			};
		});

		var html_slider = '<div class="swiper-container gallery-top"><div class="swiper-wrapper">';
		var html_pager = '<div class="swiper-container gallery-thumbs"><div class="swiper-wrapper">';
		for (var i = 0; i < figures.length; i++) {
			html_slider += '<div class="swiper-slide" style="background-image:url(' + figures[i].img + ')"></div>';
			html_pager += '<div class="swiper-slide" style="background-image:url(' + figures[i].thumb + ')"></div>';
		}
		html_pager += '</div><div class="swiper-button-next"></div><div class="swiper-button-prev"></div>';
		html_pager += '</div>';

		html_slider += '</div><div class="swiper-button-next"></div><div class="swiper-button-prev"></div>';
		html_slider += '</div>';

		$('div.gallery').html('<div class="slider" style="width:651px;height:419px">' + html_slider + html_pager + '</div>');

		var galleryTop = new Swiper('.gallery-top', {
			nextButton: '.swiper-button-next',
			prevButton: '.swiper-button-prev',
			spaceBetween: 10
		});
		var galleryThumbs = new Swiper('.gallery-thumbs', {
			spaceBetween: 10,
			centeredSlides: true,
			slidesPerView: 'auto',
			touchRatio: 0.2,
			slideToClickedSlide: true
		});
		galleryTop.params.control = galleryThumbs;
		galleryThumbs.params.control = galleryTop;

		$('.gallery-thumbs .swiper-button-prev').click(function() {
			$(this).closest('.slider').find('.gallery-top').find('.swiper-button-prev').click();
		});

		$('.gallery-thumbs .swiper-button-next').click(function() {
			$(this).closest('.slider').find('.gallery-top').find('.swiper-button-next').click();
		});
	});
});