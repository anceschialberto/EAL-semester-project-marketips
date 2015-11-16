jQuery(document).ready(function($){
	// --- masonry --- //
	var $container = $('.view-masonry-front-page .view-content').imagesLoaded(function() {
		$container.masonry({
			percentPosition: true,
			itemSelector: '.masonry-brick',
			transitionDuration: 0,
			gutter: 10
		});
	});
});
