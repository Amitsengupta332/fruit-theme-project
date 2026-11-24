(function ($) {
  "use strict";

  // Spinner
  var spinner = function () {
    setTimeout(function () {
      if ($("#spinner").length > 0) {
        $("#spinner").removeClass("show");
      }
    }, 1);
  };
  spinner(0);

  // Fixed Navbar
  var $navbar = $(".container-fluid.fixed-top").first();

  function updateFixedNavbar() {
    var adminBar = document.getElementById("wpadminbar");
    var adminOffset = adminBar
      ? Math.max(0, adminBar.getBoundingClientRect().bottom)
      : 0;

    var scrolled = $(window).scrollTop() > 55;
    var $topbar = $navbar.find(".topbar").first();
    var topbarHeight = $topbar.is(":visible") ? $topbar.outerHeight(true) : 0;
    var hideTopbar = $(window).width() >= 992 && scrolled && topbarHeight > 0;

    $navbar
      .toggleClass("shadow", scrolled)
      .css("top", adminOffset - (hideTopbar ? topbarHeight : 0));
  }

  $(window).on("scroll resize", updateFixedNavbar);
  updateFixedNavbar();

  // Back to top button
  $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
      $(".back-to-top").fadeIn("slow");
    } else {
      $(".back-to-top").fadeOut("slow");
    }
  });
  $(".back-to-top").click(function () {
    $("html, body").animate({ scrollTop: 0 }, 1500, "easeInOutExpo");
    return false;
  });

  // Testimonial carousel
  $(".testimonial-carousel").owlCarousel({
    autoplay: true,
    smartSpeed: 2000,
    center: false,
    dots: true,
    loop: true,
    margin: 25,
    nav: true,
    navText: [
      '<i class="bi bi-arrow-left"></i>',
      '<i class="bi bi-arrow-right"></i>',
    ],
    responsiveClass: true,
    responsive: {
      0: {
        items: 1,
      },
      576: {
        items: 1,
      },
      768: {
        items: 1,
      },
      992: {
        items: 2,
      },
      1200: {
        items: 2,
      },
    },
  });

  // vegetable carousel
  $(".vegetable-carousel").owlCarousel({
    autoplay: true,
    smartSpeed: 1500,
    center: false,
    dots: true,
    loop: true,
    margin: 25,
    nav: true,
    navText: [
      '<i class="bi bi-arrow-left"></i>',
      '<i class="bi bi-arrow-right"></i>',
    ],
    responsiveClass: true,
    responsive: {
      0: {
        items: 1,
      },
      576: {
        items: 1,
      },
      768: {
        items: 2,
      },
      992: {
        items: 3,
      },
      1200: {
        items: 4,
      },
    },
  });

  // Modal Video
  $(document).ready(function () {
    var $videoSrc;
    $(".btn-play").click(function () {
      $videoSrc = $(this).data("src");
    });
    console.log($videoSrc);

    $("#videoModal").on("shown.bs.modal", function (e) {
      $("#video").attr(
        "src",
        $videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0",
      );
    });

    $("#videoModal").on("hide.bs.modal", function (e) {
      $("#video").attr("src", $videoSrc);
    });
  });

  // Product Quantity
  $(".quantity button").on("click", function () {
    var button = $(this);
    var oldValue = button.parent().parent().find("input").val();
    if (button.hasClass("btn-plus")) {
      var newVal = parseFloat(oldValue) + 1;
    } else {
      if (oldValue > 0) {
        var newVal = parseFloat(oldValue) - 1;
      } else {
        newVal = 0;
      }
    }
    button.parent().parent().find("input").val(newVal);
  });
})(jQuery);

// Homepage review controls: native, keyboard/touch-friendly scroll track.
document.querySelectorAll('.ft-reviews').forEach(function (section) {
  var track = section.querySelector('.ft-review-track');
  section.querySelectorAll('[data-ft-review]').forEach(function (button) {
    button.addEventListener('click', function () {
      var card = track.querySelector('.ft-review');
      if (!card) return;
      var step = card.getBoundingClientRect().width + 16;
      var end = track.scrollWidth - track.clientWidth;
      var next = button.dataset.ftReview === 'next';
      var left = next ? (track.scrollLeft >= end - 2 ? 0 : track.scrollLeft + step) : (track.scrollLeft <= 2 ? end : track.scrollLeft - step);
      track.scrollTo({left: left, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth'});
    });
  });
});
