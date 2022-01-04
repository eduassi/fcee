let menu = $(".floating-menu");
let resize_timeout;

$("#widen-button").on("click", function () {
  menu.toggleClass("wide");
});

$("#hide-show-menu").on("click", function () {
  if (menu.hasClass("wide")) {
    menu.toggleClass("wide");
  }
  menu.toggleClass("hidden");
});

$(function () {
  if (window.innerWidth <= 767) {
    menu.toggleClass("hidden");
  }

  $(window).resize(function () {
    if (!!resize_timeout) {
      clearTimeout(resize_timeout);
    }
    resize_timeout = setTimeout(function () {
      if (
        (window.innerWidth > 767 && menu.hasClass("hidden")) ||
        (window.innerWidth <= 767 && !menu.hasClass("hidden"))
      ) {
        menu.toggleClass("hidden");
      }
    }, 200);
  });
});

$('ul.menu-text li a[href^="#"]').on("click", function (e) {
  e.preventDefault();

  var id = $(this).attr("href");
  var targetOffset = $(id).offset().top;
  try {
    $("ul.menu-text li a.active").removeClass("active");
  } catch (e) {}
  $(this).addClass("active");

  $("html, body").animate(
    {
      scrollTop: targetOffset - 100,
    },
    500
  );
});
