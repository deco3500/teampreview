/**
 * Show weather types for drop down list
 */
$(function(){  
  $("#fav-feature").click(function(){
    $(".btn:first-child").text("");
    var $new = $(this).text();
    $(".btn:first-child").append($new);
    $(".btn:first-child").val($(this).text());
  });

});