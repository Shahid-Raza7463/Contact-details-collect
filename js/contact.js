$(document).ready(function () {
  $(".jq-focus").focus(function () {
    $(this).css({
      "background-color": "#00000094",
      color: "white",
      "font-size": "18px",
    });
    // $(this).css("color", "white");
  });

  $(".jq-focus").blur(function () {
    $(this).css("background-color", "white");
    $(this).css("color", "black");
  });
});
