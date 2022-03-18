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

$("ul.menu-text li").on("click", function (e) {
  e.preventDefault();

  let a_element = $(this).find('a[href^="#"]');
  let id = $(a_element).attr("href");
  let targetOffset = $(id).offset().top;
  console.log(targetOffset);
  try {
    $("ul.menu-text li a.active").removeClass("active");
  } catch (e) {}
  $(this).addClass("active");

  $("html, body").animate(
    {
      scrollTop: targetOffset - 50,
    },
    500
  );
});
