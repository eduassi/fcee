var handle_menu_panel_itens = function (parent_element, top_menu_json) {
  let heart_button = parent_element.parent().find(".heart");
  for (item_key in top_menu_json) {
    let url = top_menu_json[item_key]["url"];
    let placeholder = top_menu_json[item_key]["placeholder"];
    let new_tab = top_menu_json[item_key]["new-tab"];
    let item_html = $.parseHTML(
      '<div class="menu-item">' + placeholder + "</div>"
    );
    $(item_html).on("click", function () {
      if (new_tab) {
        window.open(url);
        heart_button.click();
      } else {
        top.location.href = url;
      }
    });

    parent_element.append(item_html);
  }
};

var handle_top_menu_buttons = function (parent_element, menu_itens_json) {
  for (item_key in menu_itens_json) {
    let button_element = $(item_key);
    let url = menu_itens_json[item_key]["url"];
    let new_tab = menu_itens_json[item_key]["new-tab"];

    if (!menu_itens_json[item_key]["hidden"]) {
      button_element.on("click", function () {
        if (new_tab) {
          window.open(url);
        } else {
          top.location.href = url;
        }
      });
    } else {
      button_element.addClass("inactive");
    }
  }
};

$(function () {
  /**
   * ONE_MENU_MODE: Ativa/desativa o modo de uma aba do menu por vez
   * @type {boolean}
   */

  let ONE_MENU_MODE = true;
  let active_menu;

  for (parent_key in links_JSON) {
    let parent_element = $(parent_key);
    if (parent_key == ".menu-do-topo") {
      handle_top_menu_buttons(parent_element, links_JSON[parent_key]);
    } else {
      handle_menu_panel_itens(parent_element, links_JSON[parent_key]);
    }
  }

  $.each($(".module-menu"), function () {
    let parent_element = $(this);
    let heart_button = $(parent_element).find(".heart");
    let heart_menu_panel = $(parent_element).find(".heart-menu-panel");

    let shadow_heart_button = heart_button.clone();
    let shadow_heart_menu_panel = heart_menu_panel.clone();

    shadow_heart_menu_panel.css("position", "relative");
    shadow_heart_menu_panel.css("opacity", "0");
    shadow_heart_menu_panel.css("padding-top", "15px");

    shadow_heart_button.css("position", "relative");
    shadow_heart_button.css("opacity", "0");

    parent_element.append(shadow_heart_button);
    parent_element.append(shadow_heart_menu_panel);

    shadow_heart_button.addClass("shadow-button-heart");
    shadow_heart_menu_panel.addClass("shadow-menu-heart");

    shadow_heart_menu_panel.toggleClass("hide-heart-menu");
    heart_menu_panel.toggleClass("hide-heart-menu");
    $(heart_button).on("click", function () {
      if (ONE_MENU_MODE && this != active_menu) {
        try {
          active_menu.click();
        } catch (e) {}
      }

      if (this == active_menu) {
        active_menu = null;
      } else {
        active_menu = this;
      }

      heart_menu_panel.toggleClass("hide-heart-menu");
      shadow_heart_menu_panel.toggleClass("hide-heart-menu");
    });
  });
});
