$(document).ready(function() {
    $('.reviews-slider').slick({
      infinite: true,
      speed: 500,
      prevArrow: $("#reviews-previous"),
      nextArrow: $("#reviews-next"),
      fade: true,
      swipe: false, 
      draggable: false,
      slidesToShow: 1,
      adaptiveHeight: true
    });
    
    $('.reviews-slider').on('afterChange', function(event, slick, currentSlide) {
      $('#reviews-number').text('0' + (currentSlide + 1));
    });
  
    $("#wwu-slider-1").slick({
      infinite: true,
      dots: false,
      arrows: false,
      autoplay: true,
      slidesToShow: 5,
      slidesToScroll: 1,
      centerMode: true,
      centerPadding: '10%',
      autoplaySpeed: 3500,
      responsive: [
          {
              breakpoint: 1024,
              settings: {slidesToShow: 3}
          },
          {
              breakpoint: 600, 
              settings: {slidesToShow: 2}
          }
      ]
    });
  
    $("#wwu-slider-2").slick({
      infinite: true,
      dots: false,
      arrows: false,
      autoplay: true,
      slidesToShow: 5,
      slidesToScroll: 1,
      centerMode: true,
      centerPadding: '10%',
      autoplaySpeed: 3500,
      responsive: [
          {
              breakpoint: 1024,
              settings: {slidesToShow: 3}
          },
          {
              breakpoint:600, 
              settings: {slidesToShow: 2}
          }
      ]
    });
    $(".faq-enum div:first").addClass("active");
    $(".faq-enum p:not(:first)").hide();
    $(".faq-enum h3").click(function () {
      if (!$(this).parent().hasClass("active")) {
        $(".faq-enum p:visible").slideUp("fast");
        $(this).next("p").slideToggle("fast");
        $(".faq-enum div").removeClass("active");
        $(this).parent().toggleClass("active");
      }
      else {
        $(".faq-enum p:visible").slideUp("fast");
        $(".faq-enum div").removeClass("active");
      }
    });
  });

function saveLocalStorage() {
    localStorage.setItem("footer-name", $("#footer-name").val());
    localStorage.setItem("footer-number", $("#footer-number").val());
    localStorage.setItem("footer-email", $("#footer-email").val());
    localStorage.setItem("footer-message", $("#footer-message").val());
    localStorage.setItem("footer-policy", $("#footer-policy").prop("checked"));
}

function loadLocalStorage() {
    if (localStorage.getItem("name") !== null)
        $("#footer-name").val(localStorage.getItem("footer-name"));
    if (localStorage.getItem("number") !== null)
        $("#footer-number").val(localStorage.getItem("footer-number"));
    if (localStorage.getItem("email") !== null)
        $("#footer-email").val(localStorage.getItem("footer-email"));
    if (localStorage.getItem("message") !== null)
        $("#footer-message").val(localStorage.getItem("footer-message"));
    if (localStorage.getItem("policy") !== null) {
        $("#footer-policy").prop("checked", localStorage.getItem("footer-policy") === "true");
        if ($("#footer-policy").prop("checked"))
            $("#sendButton").removeAttr("disabled");
    }
}

function clear() {
    localStorage.clear()
    $("#footer-name").val("");
    $("#footer-number").val("");
    $("#footer-email").val("");
    $("#footer-message").val("");
    $("#footer-policy").val(false);
    $("#footer-policy").removeAttr("checked");
    var button = document.getElementById("sendButton");
    button.setAttribute("disabled","disabled");
}

$(document).ready(function () {
    loadLocalStorage();
    $("#footer-form").submit(function (e) {
        e.preventDefault();
        let data = $(this).serialize();

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "https://formcarry.com/s/XZpnuvO5N",
            data: data,
            success: function (response) {
                if (response.status == "success") {
                    alert("Данные получены");
                    clear();
                } else {
                    alert("Произошла ошибка: " + response.message);
                }
            }
        });
    });

    $("#footer-policy").change(function () {
        if ((!this.checked))
            $("#sendButton").attr("disabled", "");
        else
            $("#sendButton").removeAttr("disabled");
    })


    $("#footer-form").change(saveLocalStorage);
})