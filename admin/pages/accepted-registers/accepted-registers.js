var DROPDOWN_LIST = {};
var EDIT_OBJECT = {};
var CURRENT_ID = {};
var CURRENT_ROW_ID = {};

var row_constructor = function (id, row_id, date, cells, files) {
  let object = {};
  let table_row = "<tr id='" + row_id + "'>";

  let resume_button = $.parseHTML(
    "<td style='display: flex; justify-content: center'><i class='fa fa-edit' style='cursor:pointer; color: black; font-size: 32px'></i></td>"
  );

  let date_cell = $.parseHTML(
    "<td style='position: relative'><div class='hidden-date'>" +
      formatDateUS(date) +
      "</div><div class='visible-date'>" +
      formatDate(date) +
      "</div>" +
      "</td>"
  );

  $.each(cells, function (key, element) {
    object[key] = element;
    table_row +=
      "<td class='center-text' id='" +
      key +
      "'>" +
      (element == null ? "" : element) +
      "</td>";
  });
  table_row += "</tr>";

  table_row = $.parseHTML(table_row);

  $.each(files, function (key, value) {
    let new_cell = $.parseHTML(
      "<td class='doc-button center-text' id='" +
        key +
        "'><i class='far fa-file-image' style='font-size: 32px'></i></td>"
    );

    $(table_row).prepend(date_cell);
    $(table_row).append(new_cell);

    $(new_cell).on("click", function () {
      window.open(value, "_blank");
    });

    $(table_row).append(new_cell);
  });

  let button_cell = $.parseHTML(
    "<td style='display: flex; justify-content: center'></td>"
  );

  let reject_button = $.parseHTML(
    '<i class="fa fa-times-circle" style="cursor:pointer; color: red; font-size: 32px"></i>'
  );

  $(reject_button).on("click", function () {
    $.ajax({
      type: "PUT",
      url: api_base_url + "/register/reject_register",
      contentType: "application/json",
      data: JSON.stringify({
        id: id,
      }),
      headers: {
        Authorization: "Bearer " + stored_token,
      },
      success: function (data) {
        $("#users-table").animate({ scrollLeft: "0" }, 200, function () {
          $("#" + row_id).fadeOut();
        });
      },
      error: function (data) {
        alert(data.responseJSON.message);
      },
    });
  });

  $(resume_button).on("click", function () {
    CURRENT_ID = "";
    CURRENT_ROW_ID = "";
    RESUME_OBJECT = {};
    $("#resume-register-modal").modal("show");
    $.each(object, function (key, value) {
      RESUME_OBJECT[key] = value;
      $("#register-form")
        .find("#" + key)
        .val(value);
    });

    CURRENT_ID = id;
    CURRENT_ROW_ID = row_id;
    $("#documentoFotoPreview").hide();
    $("#documentoFotoPreviewPDF").hide();
    $("#documentoVinculoPreview").hide();
    $("#documentoVinculoPreviewPDF").hide();

    if (files["documentoFoto"].split("/documento-foto.")[1] != "pdf") {
      $("#register-form")
        .find("#documentoFotoPreview")
        .prop("src", files["documentoFoto"]);
      $("#documentoFotoPreview").show();
    } else {
      $("#register-form")
        .find("#documentoFotoPreviewPDF")
        .prop("src", files["documentoFoto"]);
      $("#documentoFotoPreviewPDF").show();
    }

    if (
      files["documentoVinculo"].split("/documento-comprovacao-vinculo.")[1] !=
      "pdf"
    ) {
      $("#register-form")
        .find("#documentoVinculoPreview")
        .prop("src", files["documentoVinculo"]);
      $("#documentoVinculoPreview").show();
    } else {
      $("#register-form")
        .find("#documentoVinculoPreviewPDF")
        .prop("src", files["documentoVinculo"]);
      $("#documentoVinculoPreviewPDF").show();
    }
  });

  $(table_row).prepend(resume_button);
  $(button_cell).append(reject_button);

  $(table_row).append(button_cell);

  return table_row;
};

var create_user_table = function (
  registers,
  path,
  picture_document_name,
  comprovation_document_name
) {
  $("#users-table").DataTable().destroy();
  let table_content_holder = $("#table-users-body");
  $(table_content_holder).empty();
  $.each(registers, function (i, el) {
    let row_id = "row-" + i;
    let row_values = {
      nome: el["nome"],
      cpf: el["cpf"],
      rg: el["rg"],
      orgaoEmissor: el["orgaoEmissor"],
      sexo: el["sexo"],
      estado: el["estado"],
      curso: el["curso"],
      matricula: el["matricula"],
      email: el["email"],
      ddd: el["ddd"],
      telefone: el["telefone"],
      atuacao: el["atuacao"],
      municipioReside: el["municipioReside"],
      localAtuacao: el["localAtuacao"],
      instituicaoAtuacao: el["instituicaoAtuacao"],
    };
    let button_row_values = {
      documentoFoto:
        path +
        el["cpf"] +
        "/" +
        picture_document_name +
        "." +
        el["documentoFoto"],
      documentoVinculo:
        path +
        el["cpf"] +
        "/" +
        comprovation_document_name +
        "." +
        el["documentoVinculo"],
    };
    let table_row = row_constructor(
      el["id"],
      row_id,
      new Date(el["dataRegistro"]).getTime(),
      row_values,
      button_row_values
    );

    $(table_content_holder).append(table_row);
  });
  $("#users-table").DataTable({
    language: {
      url: "./lib/jquery/datatables.pt_br.json",
    },
  });
  $(".dataTables_length").addClass("bs-select");
};

var get_registers = function () {
  return $.ajax({
    type: "GET",
    url: api_base_url + "/register/get_accepted_registers",
    contentType: "application/json",
    headers: {
      Authorization: "Bearer " + stored_token,
    },
    success: function (data) {
      create_user_table(
        data["registers"],
        data["file-path"],
        data["picture-document-name"],
        data["comprovation-document-name"]
      );
    },
    error: function (data) {
      alert(data.responseJSON.message);
    },
  });
};

var set_dropdown = function (data, target) {
  let body_string = "";
  $.each(data, function (index, element) {
    body_string +=
      '<option value="' + element.id + '">' + element.nome + "</option>";
  });
  $("#register-form").find(target).append(body_string);
};

var set_genders = function () {
  return $.ajax({
    type: "GET",
    url: api_base_url + "/gender/get_genders",
    contentType: "application/json",
    success: function (data) {
      $.each(data, function (key, value) {
        DROPDOWN_LIST[value.nome] = value.id;
      });
      set_dropdown(data, "#sexo");
    },
    error: function (data) {},
  });
};

var set_states = async function () {
  return $.ajax({
    type: "GET",
    url: api_base_url + "/states/get_states",
    contentType: "application/json",
    success: function (data) {
      $.each(data, function (key, value) {
        DROPDOWN_LIST[value.nome] = value.id;
      });
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
      $.each(data, function (key, value) {
        DROPDOWN_LIST[value.nome] = value.id;
      });
      set_dropdown(data, "#atuacao");
    },
    error: function (data) {},
  });
};

var set_csv_button = function () {
  $("#csv-export-button").on("click", function () {
    window.open(api_base_url + "/admin/export_csv_table");
  });
};


var set_modal = function () {
  $("#reject-button").on("click", function (e) {
    e.preventDefault();
    $.ajax({
      type: "PUT",
      url: api_base_url + "/register/reject_register",
      contentType: "application/json",
      data: JSON.stringify({
        id: CURRENT_ID,
      }),
      headers: {
        Authorization: "Bearer " + stored_token,
      },
      success: function (data) {
        $("#resume-register-modal").modal("hide");
        $("#users-table").animate({ scrollLeft: "0" }, 200, function () {
          $("#" + CURRENT_ROW_ID).fadeOut();
        });
      },
      error: function (data) {
        alert(data.responseJSON.message);
        $("#resume-register-modal").modal("hide");
      },
    });
  });
};

var initialize_registers_table = function () {
  Promise.all([set_genders(), set_states(), set_roles()]).then(() => {
    get_registers();
    set_csv_button();
    set_modal();
  });
};

$(function () {
  initialize_registers_table();
});
