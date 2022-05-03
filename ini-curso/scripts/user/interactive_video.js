var videos_playlist = [
  {
    videoId: "fBma0wZlloQ",
    resourceId: "#ia_1",
    player: null,
    holder: null,
    duration: null,
    done: false,
  },
  {
    videoId: "wupUv0Gdxq4",
    resourceId: "#ia_2",
    player: null,
    holder: null,
    duration: null,
    done: false,
  },
  {
    videoId: "TPB1u7XUo18",
    resourceId: "#ia_3",
    player: null,
    holder: null,
    duration: null,
    done: false,
  },
];

var current_video_index = 0;
var current_video = null;

var counter = {
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

counter.registerListener(function (val) {
  if (counter.value >= videos_playlist.length) {
    $.each(videos_playlist, function (key, element) {
      let holder_id = "video-" + element["videoId"];
      element["holder"] = $("#" + holder_id);
      element["player"].playVideo();
      element["player"].stopVideo();
      element["duration"] = element["player"].getDuration() - 1;
    });
    current_video = videos_playlist[current_video_index];
    current_video["holder"].show();
  }
});

function onPlayerReady(event) {
  counter.value = counter.value + 1;
}

function aimedStopVideo(element) {
  element["player"].stopVideo();
}

function stopVideo() {
  current_video["player"].stopVideo();
}

function startVideo() {
  current_video["player"].playVideo();
}

function getTime() {
  return current_video["player"].getCurrentTime();
}

function getDuration() {
  return current_video["player"].getDuration();
}

function pauseVideo() {
  current_video["player"].pauseVideo();
}

function pickNextVideo() {
  $(current_video["resourceId"]).hide();
  current_video_index = (current_video_index + 1) % videos_playlist.length;
  current_video = videos_playlist[current_video_index];
  current_video["holder"].show();
  $("#embed-holder").addClass("embed-responsive-16by9");
  startVideo();
}

var initialize_interactive_videos = function () {
  $.each(videos_playlist, function (key, element) {
    let holder_id = "video-" + element["videoId"];
    let holder_el = $.parseHTML("<div id=" + holder_id + "></div>");
    $("#player").append(holder_el);

    element["player"] = new YT.Player(holder_id, {
      height: "360",
      width: "640",
      videoId: element["videoId"],
      playerVars: {
        color: "red",
        autoplay: 0,
        controls: 1,
        disablekb: 0,
        rel: 0,
        fs: 0,
        modestbranding: 1,
        showinfo: 0,
        autohide: 1,
      },
      events: {
        onReady: onPlayerReady,
        // onStateChange: onPlayerStateChange(this,element),
      },
    });
    $("#" + holder_id).hide();
    $(".btn-success").removeClass("d-block mx-auto");
  });
};

function onYouTubeIframeAPIReady() {
  console.log("INITIALIZED");
  initialize_interactive_videos();
}

function feedback(numb) {
  $("#modal-feedback .modal-body p").html($("#feedback" + numb).html());
  $("#modal-feedback .modal-title").html("Feedback");

  $(".btn-close").removeClass("d-none");
  $(".btn-close").removeClass("d-block mx-auto");
  $(".btn-close").addClass("d-none");

  if (numb == Number(0)) {
    $(".btn-success").removeClass("d-none");
    $(".btn-success").removeClass("d-block mx-auto");
    $(".btn-success").addClass("d-none");
  } else if (numb <= Number(2)) {
    $(".btn-success").removeClass("d-none");
    $(".btn-success").addClass("d-block mx-auto");
  } else {
    $(".btn-success").removeClass("d-none");
    $(".btn-success").removeClass("d-block mx-auto");
    $(".btn-success").addClass("d-none");

    $(".btn-close").removeClass("d-none");
    $(".btn-close").addClass("d-block mx-auto");
  }
}

function success(feedback) {
  console.log("feedback3");

  $("#feedback-ok .modal-body p").html($("#" + feedback).html());

  if (feedback != "feedback3") {
    $(".btn-success").addClass("d-block mx-auto");
  } else {
    $(".btn-success").removeClass("d-block mx-auto");
    $(".btn-success").addClass("d-none");
  }
}

setInterval(function () {
  try {
    let current_time = getTime();
    // console.log(current_time + " <? " + current_video["duration"]);
    if (current_time >= current_video["duration"]) {
      stopVideo();
      current_video["holder"].hide();
      $(current_video["resourceId"]).show();
      $("#embed-holder").removeClass("embed-responsive-16by9");
    }
  } catch (e) {}
}, 100);


var YTInit = document.getElementById("youtube_initialization");
if (!YTInit) {
  console.log("INSERT TAG AND INITIALIZE")
  var firstScriptTag = document.getElementsByTagName("script")[0];
  var tag = document.createElement("script");
  tag.src = "https://www.youtube.com/iframe_api";
  tag.id = "youtube_initialization";
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
} else {
  console.log("INITIALIZE")
  onYouTubeIframeAPIReady();
}
