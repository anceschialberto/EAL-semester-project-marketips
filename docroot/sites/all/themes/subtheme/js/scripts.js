jQuery(function($) {
  if($('body').hasClass('front')) {
    /* Masonry */
    var $container = $('.view-masonry-front-page .view-content').imagesLoaded(function() {
      $container.masonry({
        percentPosition: true,
        itemSelector: '.masonry-brick',
        // fast transitions
        transitionDuration: '0.2s',
        // vertical space between blocks
        gutter: 10
      });
    });

    /* Infinite scroll */
    var $container = $('.view-masonry-front-page .view-content').imagesLoaded(function() {
      $container.infinitescroll({
        navSelector  : "ul.pagination",
                       // selector for the paged navigation (it will be hidden)
        nextSelector : "ul.pagination .next a",
                       // selector for the NEXT link (to page 2)
        itemSelector : ".masonry-brick"
                       // selector for all items you'll retrieve
      },
      // trigger Masonry as a callback
      function(newElements) {
        var $newElems = $(newElements).imagesLoaded(function() {
          $container.masonry('appended', $newElems);
        });
      });
    });
  }
});
