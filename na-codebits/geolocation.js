/*$(document).ready(function(){
  $('#submit').click(function() {
    console.log("test");
    //Check if HTLM5 Geolocation is supported
    var latitude;
    var longitude;
    function showPosition(position) {
      latitude = position.coords.latitude; 
      longitude = position.coords.longitude; 
    }
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
        var timestamp = Date.now();
        var postdata = "latitude="+latitude+"&longitude="+longitude+"&timestamp"+timestamp;
        console.log(postdata);
        $.ajax({
        type: "POST",
        url: "http://localhost/hackatown2018/parse.php",
        data: postdata,
        success: function(data){
          console.log(data);
        }
      });
    } else {
        ('#error-textbox').html = "Geolocation is not supported by this browser.";
    }
  });

});*/

$(document).ready(function(){
    $('#submit').click(function() {
      //Check if HTLM5 Geolocation is supported
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
      } else {
        alert("Geolocation is not supported by this browser.");
      }
      function showPosition(position) {
        var latitude = position.coords.latitude; 
        var longitude = position.coords.longitude; 
        var timestamp = Date.now();
        var postdata = "latitude="+latitude+"&longitude="+longitude+"&timestamp"+timestamp;
        console.log(postdata);
        $.ajax({
          type: "POST",
          url: "http://localhost/hackatown2018/na-codebits/parse.php",
          data: postdata,
          success: function(data){
            console.log(data);
          }
      });
      }
        
    });
  });



