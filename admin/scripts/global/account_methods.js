var check_session = function () {
  let special_routes = window.location.href.split("#")[1];
  if (special_routes && special_routes=="finishRegister") {
    removeHashAndSetHistory();
    change_page("FinishRegisterFlow");
  } else {
    $.ajax({
      type: "GET",
      url: api_base_url + "/login/checktoken.php",
      contentType: "application/json",
      headers: {
        Authorization: "Bearer " + stored_token,
      },
      success: function (data) {
        track_history_and_change_page(localStorage.getItem("last_page"), get_optional_attr_object());
      },
      error: function (data) {
        track_history_and_change_page("Login");
      },
    });
  }
};

var check_token_active = function () {
  $.ajax({
    type: "GET",
    url: api_base_url + "/login/checktoken.php",
    contentType: "application/json",
    headers: {
      Authorization: "Bearer " + stored_token,
    },
    success: function (data) {},
    error: function (data) {
      track_history_and_change_page("Login");
    },
  });
};

var login = function () {
  let user = $("#user-form-field").val();
  let password = $("#password-form-field").val();
  let jsonData = {
    username: user,
    password: password,
  };
  $.ajax({
    type: "POST",
    url: api_base_url + "/login/signin.php",
    contentType: "application/json",
    data: JSON.stringify(jsonData),
    success: function (data) {
      let token = data["token"];
      stored_token = token;
      localStorage.setItem("token", token);
      get_optional_attr_object();
      change_page(root_page);
    },
    error: function (data) {
      alert(data.responseJSON.message);
    },
  });
};

var logout = function () {
  track_history_and_change_page("Login");
};

var register = function () {
  let user = $("#user-form-field").val();
  let password = $("#password-form-field").val();
  let code = $("#code-form-field").val();
  let jsonData = {
    username: user,
    password: password,
    code: code,
  };

  $.ajax({
    type: "POST",
    url: api_base_url + "/login/signup.php",
    contentType: "application/json",
    data: JSON.stringify(jsonData),
    success: function (data) {
      let token = data["token"];
      localStorage.setItem("token", token);
      change_page(root_page);
    },
    error: function (data) {
      alert(data.responseJSON.message);
    },
  });
};

var register_request = function () {
  let user = $("#user-form-field").val();
  let password = $("#password-form-field").val();
  let email = $("#email-form-field").val();
  let nickname = $("#nickname-form-field").val();
  let jsonData = {
    username: user,
    password: password,
    email: email,
    nickname: nickname,
  };
  console.log(jsonData);
  $.ajax({
    type: "POST",
    url: api_base_url + "/login/request_signup.php",
    contentType: "application/json",
    data: JSON.stringify(jsonData),
    success: function (data) {
      change_code_page();
      alert("Seu código foi enviado para seu E-mail!");
    },
    error: function (data) {
      alert(data.responseJSON.message);
    },
  });
};

var change_register_page = function () {
  let login_box = $("#login-box");
  let login_page_buttons = $("#login-page-buttons");
  let register_page_buttons = $("#register-page-buttons");
  let code_page_buttons = $("#code-page-buttons");
  let email_field = $("#email-field");
  let code_field = $("#code-field");
  login_page_buttons.hide();
  code_field.hide();
  code_page_buttons.hide();
  register_page_buttons.fadeIn();
  email_field.fadeIn();
  login_box.animate(
    {
      height: "550px",
    },
    700,
    function () {
      // Animation complete.
    }
  );
};

var change_login_page = function () {
  let login_box = $("#login-box");
  let login_page_buttons = $("#login-page-buttons");
  let register_page_buttons = $("#register-page-buttons");
  let code_page_buttons = $("#code-page-buttons");
  let email_field = $("#email-field");
  let code_field = $("#code-field");
  login_page_buttons.fadeIn();
  register_page_buttons.hide();
  code_page_buttons.hide();
  email_field.hide();
  code_field.hide();
  login_box.animate(
    {
      height: "400px",
    },
    700,
    function () {
      // Animation complete.
    }
  );
};

var change_code_page = function () {
  let login_box = $("#login-box");
  let login_page_buttons = $("#login-page-buttons");
  let register_page_buttons = $("#register-page-buttons");
  let code_page_buttons = $("#code-page-buttons");
  let code_field = $("#code-field");
  let email_field = $("#email-field");

  register_page_buttons.hide();
  login_page_buttons.hide();
  email_field.hide();

  code_page_buttons.fadeIn();
  code_field.fadeIn();
  login_box.animate(
    {
      height: "430px",
    },
    700,
    function () {
      // Animation complete.
    }
  );
};

