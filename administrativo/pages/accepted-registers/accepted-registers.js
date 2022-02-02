var row_constructor = function (id, row_id, cells, files) {
  let table_row = "<tr id='" + row_id + "'>";

  $.each(cells, function (key, element) {
    table_row +=
      "<td class='center-text' id='" + key + "'>" + element + "</td>";
  });
  table_row += "</tr>";

  table_row = $.parseHTML(table_row);

  $.each(files, function (key, value) {
    let new_cell = $.parseHTML(
      "<td class='doc-button center-text' id='" +
        key +
        "'><i class='far fa-file-image' style='font-size: 32px'></i></td>"
    );

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
  $.ajax({
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

var initialize_registers_table = function () {
  get_registers();
};

$(function () {
  initialize_registers_table();
});
