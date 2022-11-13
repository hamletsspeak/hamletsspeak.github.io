$('.single-item').slick({
    infinite: true,
    dots: true,
    slidesToShow: 4,
    slidesToScroll: 4,
    responsive: [
	    {
	      breakpoint: 1000,
	      settings: {
	        slidesToShow: 2,
            slidesToScroll: 2,
	      }
	    }
    ]
  });

  $('.autoplay').slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
});