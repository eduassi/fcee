function animateGroup() {
  var DELAY = 200;
  $(".sequential-animation-group").hide();
  console.log($(".sequential-animation-group"))
  $.each($(".sequential-animation-group"), function (index, element) {
    setTimeout(() => {
      let effect = $(element).attr("effect");
      $(element).show();
      $(element).addClass(effect);
    }, DELAY * index);
  });
}
