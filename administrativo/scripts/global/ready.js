$(document).ready(function () {
  ready_function();
  startCountdown();
  after_load = function () {
    check_session();
  };
});

window.onpopstate = function (e) {
  if (e.state) {
    if (e.state["page"] != "Login") {
      change_page(e.state["page"], e.state["page_attr"]);
    } else {
      if (stored_token) {
        track_history_and_change_page(root_page, {});
      } else {
        track_history_and_change_page("Login", {});
      }
    }
  }
};
