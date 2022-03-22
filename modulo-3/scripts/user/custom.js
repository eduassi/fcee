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