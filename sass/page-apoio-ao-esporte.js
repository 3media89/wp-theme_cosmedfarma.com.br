jQuery(function ($) {
	$(document).ready(function() {
		$('div.gallery figure').each(function() {
			$(this).find('a').attr('data-lightbox', 'galeria-apoio-ao-esporte');
			$(this).find('a').attr('data-title', $(this).find('img').attr('alt'));
		});
	});
});