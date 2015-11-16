jQuery(document).ready(function($){
	// --- masonry --- //
	var $container = $('.view-masonry-front-page .view-content').imagesLoaded(function() {
		$container.masonry({
			itemSelector: '.masonry-brick',
			transitionDuration: 0
		});
	});
});
