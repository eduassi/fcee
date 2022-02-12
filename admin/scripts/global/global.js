// GLOBAL VARIABLES

var base_url;

if (window.location.hostname == "localhost") {
  base_url = "https://localhost/projects/FCEE";
} else {
  base_url = "https://inscricao.fcee-sc.net.br";
}
var api_base_url = base_url + "/api";
var gallery_base_url = base_url + "/gallery/";

var stored_token;
var stored_optional_attr;
var root_page = "Homologation";
var current_page;

// GLOBAL METHODS

var clean_session = function () {
  localStorage.removeItem("token");
  localStorage.removeItem("last_page");
  localStorage.removeItem("optional_attr");
};

var get_optional_attr_object = function () {
  if (!stored_optional_attr) {
    stored_optional_attr = JSON.parse(localStorage.getItem("optional_attr"));
    stored_optional_attr = stored_optional_attr ? stored_optional_attr : {};
  }
  return stored_optional_attr;
};

var store_optional_attr_obj = function (obj) {
  stored_optional_attr = Object.assign({}, obj);
  localStorage.setItem("optional_attr", JSON.stringify(stored_optional_attr));
};

var store_optional_attr = function (key, value) {
  stored_optional_attr[key] = value;
  localStorage.setItem("optional_attr", JSON.stringify(stored_optional_attr));
};

var get_optional_attr = function (key) {
  let response;
  try {
    response = stored_optional_attr[key];
  } catch (e) {
    response = "";
  }
  return response;
};

var startCountdown = function () {
  if (stored_token) {
    let exp_time = parseJwt(stored_token)["exp"] * 1000;
    let current_time = Date.now();
    let countdown_time = exp_time - current_time;
    if (countdown_time > 0) {
      setTimeout(function () {
        track_history_and_change_page("Login");
      }, countdown_time);
    } else {
      track_history_and_change_page("Login");
    }
  }
};

var parseJwt = function (token) {
  var base64Url = token.split(".")[1];
  var base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
  var jsonPayload = decodeURIComponent(
    atob(base64)
      .split("")
      .map(function (c) {
        return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
      })
      .join("")
  );
  return JSON.parse(jsonPayload);
};

var removeHashAndSetHistory = function () {
  let uri = window.location.toString();
  let clean_uri = uri.substring(0, uri.indexOf("#"));
  window.history.replaceState({}, document.title, clean_uri);
};

var removeSpecialCharacters = function (message) {
  return message.replace(/[\u00A0-\u9999<>\&]/gim, function (i) {
    return "&#" + i.charCodeAt(0) + ";";
  });
};

var decodeText = function (message) {
  let element = document.createElement("div");
  element.innerHTML = message;
  try {
    element.innerHTML = element.firstChild.nodeValue;
    return element.firstChild.nodeValue;
  } catch (e) {
    return "";
  }
};

var TestaCPF = function (strCPF) {
  strCPF = strCPF.replace(/[^0-9]/g, "");
  var Soma;
  var Resto;
  Soma = 0;
  
  if(strCPF.length != 11) return false;
  if (strCPF == "00000000000") return false;

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


var formatDate = function (datetime) {
  let final_date;
  let new_date = new Date(datetime);
  let day = "" + new_date.getDate();
  let month = "" + (new_date.getMonth() + 1);
  let year = "" + new_date.getFullYear();
  let hours = "" + new_date.getHours();
  let minutes = "" + new_date.getMinutes();

  if (month.length < 2) month = "0" + month;
  if (day.length < 2) day = "0" + day;
  if (hours.length < 2) hours = "0" + hours;
  if (minutes.length < 2) minutes = "0" + minutes;
  final_date = day + "/" + month + "/" + year + " - " + hours + ":" + minutes;

  return final_date;
};


var formatDateUS = function (datetime) {
  let final_date;
  let new_date = new Date(datetime);
  let day = "" + new_date.getDate();
  let month = "" + (new_date.getMonth() + 1);
  let year = "" + new_date.getFullYear();
  let hours = "" + new_date.getHours();
  let minutes = "" + new_date.getMinutes();

  if (month.length < 2) month = "0" + month;
  if (day.length < 2) day = "0" + day;
  if (hours.length < 2) hours = "0" + hours;
  if (minutes.length < 2) minutes = "0" + minutes;
  final_date = year + "/" + month + "/" + day + " - " + hours + ":" + minutes;

  return final_date;
};