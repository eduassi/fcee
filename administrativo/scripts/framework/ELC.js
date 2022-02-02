var preloader_spinner = $("#preloader-viewer");
var content_window = $("#content-viewer");

// GLOBAL TEMPLATES
var CAROUSEL_INDICATORS_OL_TEMPLATE = "<ol class='carousel-indicators'></ol>";
var CAROUSEL_CONTROL_TEMPLATE = "<a class='carousel-control'><span></span></a>";

// GLOBAL FUNCTIONS
var ELEC_lock_top_scroll = function (element, offset_top, margin_top) {
  let y_scroll_position = $(element).offset().top - offset_top - margin_top;
  window.scrollTo(0, y_scroll_position);
};

var ELEC_equalize_size = function (holder, collection) {
  let new_height = 0;
  let taller_height = 0;
  let taller;
  $.each($(collection), function (index, object) {
    let totalHeight = 0;
    $(object)
      .children()
      .each(function () {
        totalHeight = totalHeight + $(this).outerHeight(true);
      });
    new_height = totalHeight > new_height ? totalHeight : new_height;
    if (taller_height < new_height) {
      taller = $(object);
      taller_height = new_height;
    }
  });

  console.log(taller);
  let clone = $(taller).clone();
  $(clone).addClass("shadow-element");
  $(holder).prepend($(clone));
};

var ELEC_hide = function (element, effect) {
  if (effect) {
    $(element).hide("slow");
  } else {
    $(element).hide();
  }
};

var ELEC_show = function (element, effect) {
  if (effect) {
    $(element).show("slow");
  } else {
    $(element).show();
  }
};

var refresh_objects_listeners = function () {
  //ACCORDION LOADER
  $.each($(".accordion"), function (index, element) {
    let autoadjust = $(this).hasClass("auto-adjust");
    let hideothers = $(this).hasClass("hide-others");
    let tabs = $(this).find(".card");
    let tabs_id = "aan-" + index;
    let thread_var;
    let current_tab;
    let margin_top;
    let offset_top;

    $(this).attr("id", tabs_id);
    $.each(tabs, function (i, card) {
      let children = $(card).children("div");
      let title_div = children[0];
      let body_div = children[1];
      let body_id = tabs_id + "_tab_" + i;
      $(title_div).addClass("card-header pointer collapsed");
      $(title_div).attr("data-toggle", "collapse");
      $(title_div).attr("data-target", "#" + body_id);
      $(body_div).addClass("card-body collapse");
      $(body_div).attr("id", body_id);
    });

    $(tabs).on("click", function () {
      if (hideothers && current_tab != this) {
        try {
          $(current_tab).find(".collapse").collapse("hide");
        } catch (e) {}
      }
      current_tab = this;
    });

    if (autoadjust) {
      margin_top = $($(".card-header")[0]).height();
      offset_top = $(".navbar").height() ? $(".navbar").height() : 0;
      $(tabs)
        .on("show.bs.collapse", function () {
          try {
            clearInterval(thread_var);
          } catch (e) {}
          thread_var = setInterval(() => {
            ELEC_lock_top_scroll(current_tab, offset_top, margin_top);
          }, 1);
        })
        .on("shown.bs.collapse", function () {
          clearInterval(thread_var);
          change_scroll_position_by_top_object(
            current_tab,
            offset_top,
            margin_top
          );
        });
    }
  });

  //DIGITAL PANEL
  $.each($(".digital-panel"), function (index, element) {
    let active_button;
    let current_id = -1;
    let use_effects = $(this).hasClass("effects");
    let autosize = $(this).hasClass("auto-size");
    let active_text = $(this).find(".optional-instructions");
    let buttons = $(this).find(".digital-panel-headers").children();
    let boxes_holder = $(this).find(".digital-panel-boxes");
    let text_items = $(boxes_holder).children(
      "div:not(.optional-instructions)"
    );

    if (autosize) {
      ELEC_equalize_size(boxes_holder, text_items);
    }

    $.each($(text_items), function (index, element) {
      ELEC_hide(element, use_effects);
    });

    for (let i = 0; i < buttons.length; i++) {
      $(buttons[i]).on("click", function () {
        if (current_id != i) {
          try {
            active_button.removeClass("active");
          } catch (e) {}
          try {
            ELEC_hide(active_text, use_effects);
          } catch (e) {}
          active_button = $(this);
          active_text = $(text_items[i]);
          $(active_button).addClass("active");
          ELEC_show(active_text, use_effects);
          current_id = i;
        }
      });
    }
  });

  //CAROUSEL
  $.each($(".carousel"), function (index, element) {
    let carousel_uid = "carousel-" + index;
    let carousel_item = $(this).children("div");
    let indicators_off = $(this).hasClass("indicators-off");
    let controls_off = $(this).hasClass("controls-off");
    let auto_size = $(this).hasClass("auto-size");

    let ol_element = $.parseHTML(CAROUSEL_INDICATORS_OL_TEMPLATE);
    let prev_element = $.parseHTML(CAROUSEL_CONTROL_TEMPLATE);
    let next_element = $.parseHTML(CAROUSEL_CONTROL_TEMPLATE);

    $.each($(carousel_item), function (i, el) {
      let il_element = $.parseHTML(
        "<li data-target='#" + carousel_uid + "' data-slide-to=" + i + "></li>"
      );
      $(el).addClass("carousel-item");
      if (i == 0) {
        $(el).addClass("active");
        $(il_element).addClass("active");
      }
      $(ol_element).append(il_element);
    });

    $(prev_element).attr("href", "#" + carousel_uid);
    $(prev_element).attr("data-slide", "prev");
    $(prev_element).addClass("carousel-control-prev");

    $(next_element).attr("href", "#" + carousel_uid);
    $(next_element).attr("data-slide", "next");
    $(next_element).addClass("carousel-control-next");

    $(this).wrapInner("<div class='carousel-inner'></div>");
    if (!controls_off) {
      $(this).append(ol_element);
    }
    if (!indicators_off) {
      $(this).append(prev_element);
      $(this).append(next_element);
    }
    $(this).attr("id", carousel_uid);
    if (auto_size) {
      $(carousel_item).addClass("active");
      ELEC_equalize_size($(this), carousel_item);
      $(carousel_item).removeClass("active");
      $(carousel_item[0]).addClass("active");
      $(this).find(".shadow-element").removeClass("carousel-item active");
    }
  });

  // FLIP CARD
  $.each($(".flip-card"), function (index, element) {
    let limit_height = Number($(element).attr("max-height"));
    let auto_size = $(element).hasClass("auto-size");
    let front_element = $(element).find(".flip-card-front");
    let back_element = $(element).find(".flip-card-back");
    let front_height = $(front_element).height();
    let back_height = $(back_element).height();
    if (auto_size) {
      let taller = front_height > back_height ? front_height : back_height;
      taller = !limit_height || limit_height > taller ? taller : limit_height;
      // front_element[0].style.height = taller+"px";
      // back_element[0].style.height = taller+"px";
      $(".flip-card-front").css("height", taller);
      $(".flip-card-back").css("height", taller);
    }

    $(element).on("click", function () {
      if ($(this).hasClass("rotate")) {
        $(this).removeClass("rotate");
      } else {
        $(this).addClass("rotate");
      }
    });
  });
};

var after_load = function () {};

var imagem_problem_log = function (image_problem_counter, image_error_log) {
  console.error(
    "[ELEC Manager]: " +
      image_problem_counter +
      " " +
      (image_problem_counter == 1
        ? "imagem nÃ£o foi carregada com sucesso. Verifique a lista abaixo."
        : "imagens nÃ£o foram carregadas com sucesso. Verifique a lista abaixo.")
  );
  console.warn(
    "[EL Easy Constructor]: Imagens nÃ£o achadas foram convertidas em placeholders."
  );

  console.log("Lista das imagens");
  for (var i in image_error_log) {
    console.log("--- IMAGE ---");
    console.log(image_error_log[i]["object"]);
    console.log("Full name: " + image_error_log[i]["full_name"]);
    console.log("Short Name: " + image_error_log[i]["short_name"]);
    console.log("Extension: " + image_error_log[i]["extension"]);
    console.log("Src: " + image_error_log[i]["src"]);
  }
};

var check_all_images_complete = function (callback) {
  // We are listening for lazy images, when all images have a real size on DOM, we fire our callback function
  let images_container = $("#content-viewer").find("img");
  let number_images = images_container.length;
  let image_error_log = [];
  let image_error_compact_log = [];
  let image_problem_counter = 0;

  let progress = {
    aInternal: 0,
    aListener: function (val) {},
    set value(val) {
      this.aInternal = val;
      this.aListener(val);
    },
    get value() {
      return this.aInternal;
    },
    registerListener: function (listener) {
      this.aListener = listener;
    },
  };

  progress.registerListener(function (val) {
    if (progress.value >= number_images) {
      callback();
      if (image_problem_counter > 0) {
        imagem_problem_log(image_problem_counter, image_error_log);
      }
    }
  });

  if ($(images_container).length) {
    $(images_container)
      .one("load", function () {
        progress.value = progress.value + 1;
      })
      .one("error", function () {
        let new_log;
        let image_src = this.src;
        let image_tags = this.src.split("/");
        let image_full_name = image_tags[image_tags.length - 1];
        let image_name = image_full_name.split(".");
        let extension = image_name[image_name.length - 1];
        image_name = image_name[0];

        new_log = {
          src: image_src,
          full_name: image_full_name,
          short_name: image_name,
          extension: extension,
          object: this,
        };
        image_error_log.push(new_log);
        image_error_compact_log.push(image_full_name);

        // Change to placeholder
        this.src =
          "http://localhost/projects/EL-Easy-Constructor-Framework/dist/assets/images/placeholders/placeholder-error.svg";

        // Register error
        image_problem_counter++;
      });
  } else {
    callback();
  }
};

var initial_setup = function () {
  // Start custom scripts
  $('[data-toggle="popover"]').popover();
};

var libraries_starter = function () {
  // Start libraries
  refresh_objects_listeners();
  AOS.init();
};

var load_page = function (url, callback) {
  $("#content-viewer").load(url, function () {
    initial_setup();
    check_all_images_complete(libraries_starter);
    callback();
    $("html, body").animate({ scrollTop: 0 }, 500);
    after_load();
  });
};

// WARNING: THEME_STARTER MOST BE IMPORTED FIRST

// CONFIGURACOES

var max_level;
var min_level;
var current_level;
var page_name;
var menu_items;
var current_active_item;
var current_active_ball_item;
var JSON_MENU;
var PAGES;

var set_active_item = function () {
  $(current_active_item).removeClass("active");
  $(current_active_ball_item).removeClass("active");

  current_active_item = menu_items[current_level];
  current_active_ball_item = ball_items[current_level];

  $(current_active_item).addClass("active");
  $(current_active_ball_item).addClass("active");

  $(content_window).show();
  $(preloader_spinner).hide();
};

var menu_page_loader = function (next_page) {
  $(content_window).hide();
  $(preloader_spinner).show();

  $("#content-viewer").empty();

  load_page(next_page, set_active_item);

  $("#backward_arrow").css("visibility", "visible");
  $("#forward_arrow").css("visibility", "visible");
  $("#content-next-mod-bt").css("visibility", "visible");

  if (current_level == min_level) {
    $("#backward_arrow").css("visibility", "hidden");
  }
  if (current_level == max_level) {
    $("#forward_arrow").css("visibility", "hidden");
    $("#content-next-mod-bt").css("visibility", "hidden");
  }
};

var change_route = function (page_name) {
  let object = PAGES.find((element) => element.placeholder === page_name);
  let new_url = object.url;
  let index = PAGES.indexOf(object);
  let this_level = Number(index + min_level);
  current_level = this_level;
  menu_page_loader(new_url);
};

var manual_starter = function (json) {
  $.getJSON("scripts/user/menu.json", function (json) {
    JSON_MENU = json;
    PAGES = JSON_MENU["pages"];

    let information = JSON_MENU["information"];
    let configuration = JSON_MENU["configuration"];
    let target_selector = configuration["target"];
    let ball_holder = $("#ball-item-holder");
    let open_width = configuration["open-width"];

    let menu_item_template =
      '<li>\
            <a \
                class="linker menu-item pmd-ripple-effect pmd-sidebar-toggle"\
                href="javascript:void(0);"\
                data-target="basicSidebar"\
                ">\
            </a>\
        </li>';

    let menu_ball_template =
      '<li class="ball-item"><a class="capitulo-link"></a></li>';

    let cache_module = sessionStorage.getItem("chapter");

    max_level = configuration["max-level"];
    min_level = configuration["min-level"];
    page_name = configuration["prefix-name"];
    current_level = cache_module
      ? Number(cache_module)
      : configuration["start-level"];

    // ASSIGN MENU ITEMS ON DOM
    for (let i = min_level; i < PAGES.length; i++) {
      let new_ball_item = $.parseHTML(menu_ball_template)[0];
      let new_li_item = $.parseHTML(menu_item_template)[0];

      let new_ball_a = $(new_ball_item).find(".capitulo-link");
      let new_li_a = $(new_li_item).find(".linker");

      // $(new_ball_a).text(i + 1);
      $(new_li_a).text(PAGES[i]["placeholder"]);
      $(ball_holder).append(new_ball_item);
      $(target_selector).append(new_li_item);
    }

    menu_items = $(".linker");
    ball_items = $(".ball-item");
    current_active_item = menu_items[current_level];
    current_active_ball_item = ball_items[current_level];

    // START PROPELLER
    try {
      $().pmdSidebar();
    } catch (e) {}

    // GIVE LISTENER
    $.each($(".linker"), function (index, element) {
      let this_level = Number(index + min_level); // ADJUST OFFSET
      let new_url = PAGES[this_level]["url"];
      $(element).on("click", function () {
        current_level = this_level;
        menu_page_loader(new_url);
      });
    });

    $.each($(".ball-item"), function (index, element) {
      let this_level = Number(index + min_level); // ADJUST OFFSET
      let new_url = PAGES[this_level]["url"];
      $(element).on("click", function () {
        if (this_level != current_level) {
          current_level = this_level;
          menu_page_loader(new_url);
        }
      });
    });

    $(current_active_item).addClass("active");

    // CARREGAR A PÃGINA
    menu_page_loader(page_name + current_level + ".html");

    // TÃTULOS DO MÃ“DULO
    $(".header_titulo_modulo").html(information["modulo-nome"]);
    $(".header_numero_modulo").html(information["modulo-numero"]);
    $(".header_numero_modulo_resumido").html(information["modulo-resumido"]);

    // LINKS DA NAVEGAÃ‡ÃƒO
    $("#backward_arrow").css("visibility", "hidden");
    $("#backward_arrow").on("click", function () {
      current_level = current_level > min_level ? current_level - 1 : 0;
      menu_page_loader(page_name + current_level + ".html");
    });

    $("#forward_arrow").on("click", function () {
      current_level = current_level < max_level ? current_level + 1 : max_level;
      menu_page_loader(page_name + current_level + ".html");
    });

    $("#content-next-mod-bt").on("click", function () {
      current_level = current_level < max_level ? current_level + 1 : max_level;
      menu_page_loader(page_name + current_level + ".html");
    });
  });
};

$(document).ready(function () {
  $.getJSON("scripts/user/menu.json", function (json) {
    JSON_MENU = json;
    PAGES = JSON_MENU["pages"];

    let information = JSON_MENU["information"];
    let configuration = JSON_MENU["configuration"];
    let target_selector = configuration["target"];
    let ball_holder = $("#ball-item-holder");
    let open_width = configuration["open-width"];

    let menu_item_template =
      '<li>\
              <a \
                  class="linker menu-item pmd-ripple-effect pmd-sidebar-toggle"\
                  href="javascript:void(0);"\
                  data-target="basicSidebar"\
                  ">\
              </a>\
          </li>';

    let menu_ball_template =
      '<li class="ball-item"><a class="capitulo-link"></a></li>';

    let cache_module = sessionStorage.getItem("chapter");

    max_level = configuration["max-level"];
    min_level = configuration["min-level"];
    page_name = configuration["prefix-name"];
    current_level = cache_module
      ? Number(cache_module)
      : configuration["start-level"];

    // ASSIGN MENU ITEMS ON DOM
    for (let i = min_level; i < PAGES.length; i++) {
      let new_ball_item = $.parseHTML(menu_ball_template)[0];
      let new_li_item = $.parseHTML(menu_item_template)[0];

      let new_ball_a = $(new_ball_item).find(".capitulo-link");
      let new_li_a = $(new_li_item).find(".linker");

      // $(new_ball_a).text(i + 1);
      $(new_li_a).text(PAGES[i]["placeholder"]);
      $(ball_holder).append(new_ball_item);
      $(target_selector).append(new_li_item);
    }

    menu_items = $(".linker");
    ball_items = $(".ball-item");
    current_active_item = menu_items[current_level];
    current_active_ball_item = ball_items[current_level];

    // START PROPELLER
    try {
      $().pmdSidebar();
    } catch (e) {}

    // GIVE LISTENER
    $.each($(".linker"), function (index, element) {
      let this_level = Number(index + min_level); // ADJUST OFFSET
      let new_url = PAGES[this_level]["url"];
      $(element).on("click", function () {
        current_level = this_level;
        menu_page_loader(new_url);
      });
    });

    $.each($(".ball-item"), function (index, element) {
      let this_level = Number(index + min_level); // ADJUST OFFSET
      let new_url = PAGES[this_level]["url"];
      $(element).on("click", function () {
        if (this_level != current_level) {
          current_level = this_level;
          menu_page_loader(new_url);
        }
      });
    });

    $(current_active_item).addClass("active");

    // CARREGAR A PÃGINA
    menu_page_loader(page_name + current_level + ".html");

    // TÃTULOS DO MÃ“DULO
    $(".header_titulo_modulo").html(information["modulo-nome"]);
    $(".header_numero_modulo").html(information["modulo-numero"]);
    $(".header_numero_modulo_resumido").html(information["modulo-resumido"]);

    // LINKS DA NAVEGAÃ‡ÃƒO
    $("#backward_arrow").css("visibility", "hidden");
    $("#backward_arrow").on("click", function () {
      current_level = current_level > min_level ? current_level - 1 : 0;
      menu_page_loader(page_name + current_level + ".html");
    });

    $("#forward_arrow").on("click", function () {
      current_level = current_level < max_level ? current_level + 1 : max_level;
      menu_page_loader(page_name + current_level + ".html");
    });

    $("#content-next-mod-bt").on("click", function () {
      current_level = current_level < max_level ? current_level + 1 : max_level;
      menu_page_loader(page_name + current_level + ".html");
    });
  });
});
