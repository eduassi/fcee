var api_base_url;

var TestaCPF = function (strCPF) {
  strCPF = strCPF.replace(/[^0-9]/g, "");
  var Soma;
  var Resto;
  Soma = 0;
  if (strCPF == "00000000000") return false;
  if (strCPF == "000000000000") return false;
  if (Number(strCPF) == 0) return false;

  for (i = 1; i <= 9; i++)
    Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (11 - i);
  Resto = (Soma * 10) % 11;

  if (Resto == 10 || Resto == 11) Resto = 0;
  if (Resto != parseInt(strCPF.substring(9, 10))) return false;

  Soma = 0;
  for (i = 1; i <= 10; i++)
    Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (12 - i);
  Resto = (Soma * 10) % 11;

  if (Resto == 10 || Resto == 11) Resto = 0;
  if (Resto != parseInt(strCPF.substring(10, 11))) return false;
  return true;
};

var set_dropdown = function (data, target) {
  let body_string = "";
  $.each(data, function (index, element) {
    body_string +=
      '<option value="' + element.id + '">' + element.nome + "</option>";
  });
  $(target).append(body_string);
};

var set_genders = function () {
  $.ajax({
    type: "GET",
    url: api_base_url + "/gender/get_genders",
    contentType: "application/json",
    success: function (data) {
      set_dropdown(data, "#sexo");
    },
    error: function (data) {},
  });
};

var set_states = function () {
  $.ajax({
    type: "GET",
    url: api_base_url + "/states/get_states",
    contentType: "application/json",
    success: function (data) {
      set_dropdown(data, "#estado");
    },
    error: function (data) {},
  });
};

var set_roles = function () {
  $.ajax({
    type: "GET",
    url: api_base_url + "/role/get_roles",
    contentType: "application/json",
    success: function (data) {
      set_dropdown(data, "#atuacao");
    },
    error: function (data) {},
  });
};

var set_button = function () {
  $("#form-send").on("click", function (e) {
    let form = new FormData();

    let ready_to_send = true;
    let email = "";
    e.preventDefault();

    $.each(document.getElementById("register-form").elements, function (i, el) {
      if (
        el.id != "form-send" &&
        el.id != "doc-com-foto" &&
        el.id != "doc-vinculo"
      ) {
        let value = el.value;
        if (value) {
          if (el.id == "email") {
            email = el.value;
            form.append(el.id, el.value);
          } else if (
            el.id == "email-confirma" &&
            el.value != $("#email").val()
          ) {
            ready_to_send = false;
            let email_error_message = $(el)
              .parent()
              .find("#email-error-message");
            if (!$(email_error_message).length) {
              email_error_message = $.parseHTML(
                "<span id='email-error-message' class='warning'>Verificação do e-mail não corresponde!</span>"
              );
              $(el).parent().append(email_error_message);
              $(el).on("click", function () {
                $(email_error_message).fadeOut();
              });
            } else {
              $(email_error_message).fadeIn();
            }
          } else if (el.id == "cpf") {
            if (!TestaCPF(el.value)) {
              ready_to_send = false;
              let cpf_error_message = $(el).parent().find("#cpf-error-message");
              if (!$(cpf_error_message).length) {
                cpf_error_message = $.parseHTML(
                  "<span id='cpf-error-message' class='warning'>CPF inválido!</span>"
                );
                $(el).parent().append(cpf_error_message);
                $(el).on("click", function () {
                  $(cpf_error_message).fadeOut();
                });
              } else {
                $(cpf_error_message).fadeIn();
              }
            } else {
              form.append(el.id, el.value);
            }
          } else {
            form.append(el.id, el.value);
          }
        } else if (!$(el).hasClass("opcional")) {
          ready_to_send = false;
          let warning_message = $(el).parent().find("#warning-message");
          if (!$(warning_message).length) {
            warning_message = $.parseHTML(
              "<span id='warning-message' class='warning'>Campo não preenchido</span>"
            );
            $(el).parent().append(warning_message);
            $(el).on("click", function () {
              $(warning_message).fadeOut();
            });
          } else {
            $(warning_message).fadeIn();
          }
        } else {
          form.append(el.id, "");
        }
      } else if (el.id != "doc-com-foto" || el.id != "doc-vinculo") {
        let file_attached = el.files[0];

        if (file_attached != undefined) {
          form.append(el.id, file_attached);
        } else {
          ready_to_send = false;

          let warning_message = $(el).parent().find("#warning-message");
          if (!$(warning_message).length) {
            warning_message = $.parseHTML(
              "<span id='warning-message' class='warning'>Documento não selecionado!</span>"
            );
            $(el).parent().append(warning_message);
            $(el).on("click", function () {
              $(warning_message).fadeOut();
            });
          } else {
            $(warning_message).fadeIn();
          }
        }
      }
    });

    if (ready_to_send) {
      $("#form-send").hide();
      $("#uploading").addClass("on");
      $("#uploading").fadeIn();
      $.ajax({
        type: "POST",
        url: api_base_url + "/register/new_register",
        cache: false,
        processData: false,
        contentType: false,
        mimeType: "multipart/form-data",
        data: form,
        success: function (data) {
          $("#register-screen").hide();
          $("#feedback-screen").show();
          $("#uploading").fadeOut();
        },
        error: function (data) {
          alert(JSON.parse(data.responseText)["message"]);
          $("#form-send").show();
          $("#uploading").fadeOut();
        },
      });
    } else {
      window.scrollTo(0, document.getElementById("header").clientHeight);
    }
  });
};

$(function () {
  if (window.location.hostname == "localhost") {
    api_base_url = "https://localhost/projects/FCEE/api";
  } else {
    api_base_url = "https://inscricao.fcee-sc.net.br/api";
  }
  set_genders();
  set_states();
  set_roles();
  set_button();
});
