var fire_once;
var check_all_images_complete = function (callback) {
  // We are listening for lazy images, when all images have a real size on DOM, we fire our callback function
  try {
    clearTimeout(fire_once);
  } catch (e) {}
  let images_container = $("#content-viewer").find("img");
  let number_images = images_container.length;
  let progress = 0;
  if ($(images_container).length) {
    $(images_container).one("load", function () {
      progress++;
      if (progress >= number_images) {
        fire_once = setTimeout(function () {
          callback();
        }, 1000);
      }
    });
  } else {
    callback();
  }
};

var start_audio_library = function () {
  GreenAudioPlayer.init({
    selector: ".player",
    stopOthersOnPlay: true,
  });
  GreenAudioPlayer.init({
    selector: ".player-with-download",
    stopOthersOnPlay: true,
    showDownloadButton: true,
    enableKeystrokes: true,
  });
  GreenAudioPlayer.init({
    selector: ".player-with-accessibility",
    stopOthersOnPlay: true,
    enableKeystrokes: true,
  });
};

var initial_setup = function () {
  // Maybe we want to execute some custom scripts
  $('[data-toggle="popover"]').popover();
};

var libraries_starter = function () {
  // We should start our libraries here
  refresh_objects_listeners();
  start_audio_library();
  AOS.init();
};

var load_page = function (url, callback) {
  $("#content-viewer").load(url, function () {
    $("#preloader-container").show();
    initial_setup();
    check_all_images_complete(libraries_starter);
    callback();
    $("html, body").animate({ scrollTop: 0 }, 500);
  });
};


var quick_load_page = function (url) {
  $("#content-viewer").load(url, function () {
    $(".menu-item.active").removeClass("active")
    $(".menu-item[linker='" + url + "']").addClass("active")
    initial_setup();
    check_all_images_complete(libraries_starter);
    $("html, body").animate({ scrollTop: 0 }, 500);
  });
};
