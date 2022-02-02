var ready_function = function () {
  stored_token = localStorage.getItem("token");
  get_optional_attr_object();
};

var track_history_and_change_page = function (page, optional_attr) {
  if (page != current_page) {
    if (
      window.history.state != null &&
      !$.isEmptyObject(window.history.state.page_attr) &&
      ($.isEmptyObject(optional_attr) || !optional_attr)
    ) {
      optional_attr = window.history.state.page_attr;
    } else if (!$.isEmptyObject(optional_attr) && optional_attr) {
      optional_attr = optional_attr;
    } else {
      optional_attr = {};
    }
    window.history.pushState({ page: page, page_attr: optional_attr }, "", "");
    change_page(page, optional_attr);
  }
};

var change_page = function (page, optional_attr) {
  store_optional_attr_obj(optional_attr);
  $("#menu-viewer").show();
  $(".menu-buttons.active").removeClass("active");

  if (page != current_page) {
    current_page = page;
    switch (page) {
      case "Login":
        $("#menu-viewer").hide();
        clean_session();
        localStorage.setItem("last_page", "Login");
        change_route("Login");
        after_load = function () {};
        break;
      case "Homologation":
        $(".button-homologation").addClass("active");
        localStorage.setItem("last_page", "Homologation");
        change_route("Homologation");
        after_load = function () {};
        break;
      case "Accepted":
        $(".button-accepted-registers").addClass("active");
        localStorage.setItem("last_page", "Accepted");
        change_route("Accepted");
        after_load = function () {};
        break;
      case "Rejected":
        $(".button-rejected-registers").addClass("active");
        localStorage.setItem("last_page", "Rejected");
        change_route("Rejected");
        after_load = function () {};
        break;
    }
  }
};
