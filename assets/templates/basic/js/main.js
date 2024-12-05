(function ($) {
  "use strict";
  // ============== Variables Start ========
  var $testimonial = $('.testimonial');
  // ============== Variables End ========
  // ============== Header Hide Click On Body Js Start ========
  $('.header-button').on('click', function () {
    $('.body-overlay').toggleClass('show')
  });
  $('.body-overlay').on('click', function () {
    $('.header-button').trigger('click')
    $(this).removeClass('show');
  });

  /*==================== custom dropdown select js ====================*/
  $('.custom--dropdown > .custom--dropdown__selected').on('click', function () {
    $(this).parent().toggleClass('open');
  });
  $('.custom--dropdown > .dropdown-list > .dropdown-list__item').on('click', function () {
    $('.custom--dropdown > .dropdown-list > .dropdown-list__item').removeClass('selected');
    $(this).addClass('selected').parent().parent().removeClass('open').children('.custom--dropdown__selected').html($(this).html());
  });
  $(document).on('keyup', function (evt) {
    if ((evt.keyCode || evt.which) === 27) {
      $('.custom--dropdown').removeClass('open');
    }
  });
  $(document).on('click', function (evt) {
    if ($(evt.target).closest(".custom--dropdown > .custom--dropdown__selected").length === 0) {
      $('.custom--dropdown').removeClass('open');
    }
  });

  $('.custom--dropdown .icon').on('click', function () {
    $('.custom--dropdown.open').toggleClass('show')
  });
  $('.body').on('click', function () {
    $('.custom--dropdown .icon.show').trigger('click')
    $(this).removeClass('show');
  });

  //      Start Document Ready function
  // ==========================================
  $(document).ready(function () {

    // ========================== Header Hide Scroll Bar Js Start =====================
    $('.navbar-toggler.header-button').on('click', function () {
      $('body').toggleClass('scroll-hide')
    });
    $('.body-overlay').on('click', function () {
      $('body').removeClass('scroll-hide')
    });
    // ========================== Header Hide Scroll Bar Js End =====================

    // ================== Password Show Hide Js Start ==========
    $(".toggle-password").on('click', function () {

      var input = $($(this).attr("id"));
      if (input.attr("type") == "password") {

        input.attr("type", "text");
        $(this).addClass("fa-eye").removeClass('fa-eye-slash');
      } else {
        input.attr("type", "password");
        $(this).addClass("fa-eye-slash").removeClass('fa-eye');
      }
    });
    // =============== Password Show Hide Js End =================

    // ========================= owl carousel Slider Js Start ==============

    if ($testimonial.length > 0) {
      $testimonial.owlCarousel({
        autoplay: true,
        autoplaySpeed: 2000,
        center: true,
        margin: 12,
        loop: true,
        nav: false,
        dots: true,
        items: 4,
        responsiveClass: true,
        responsive: {
          0: {
            items: 1,
            center: false
          },
          576: {
            items: 2,
            center: false
          },
          768: {
            items: 2,
          },
          992: {
            items: 3,
          },
          1200: {
            items: 3,
          },
          1400: {
            items: 4
          }
        }
      });
    }

    // ========================= owl carousel Slider Js End ===================
  });
  // ==========================================
  //      End Document Ready function
  // ==========================================

  // ========================= Preloader Js Start =====================
  $(window).on("load", function () {
    $('.preloader').fadeOut();
  })
  // ========================= Preloader Js End=====================

  // ========================= Header Sticky Js Start ==============
  $(window).on('scroll', function () {
    if ($(window).scrollTop() >= 300) {
      $('.header').addClass('fixed-header');
    }
    else {
      $('.header').removeClass('fixed-header');
    }
  });
  // ========================= Header Sticky Js End===================

  //============================ Scroll To Top Icon Js Start =========
  var btn = $('.scroll-top');

  $(window).scroll(function () {
    if ($(window).scrollTop() > 300) {
      btn.addClass('show');
    } else {
      btn.removeClass('show');
    }
  });

  btn.on('click', function (e) {
    e.preventDefault();
    $('html, body').animate({ scrollTop: 0 }, '300');
  });
  //========================= Scroll To Top Icon Js End ======================


  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'))
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  });

})(jQuery);
