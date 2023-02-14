 $(document).ready(function() {
    var owl = $('.owl-carousel');
    owl.owlCarousel({
    items: 1,
    loop: true,
    margin: 10,
    autoplay: true,
    autoplayTimeout: 5000,
    autoplayHoverPause: true
    });
    $('.top-stories').owlCarousel({
        loop: true,
        items: 1,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        animateOut: 'slideOutUp',
        animateIn: 'slideInUp'
    });
})