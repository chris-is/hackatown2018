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
        var base_url="http://localhost/mtlwatch/WebApp/";
        var url = base_url+'parse';
        $.ajax({
          type: "POST",
          url: url,
          data: postdata,
          success: function(data){
            console.log(data);
          }
      });
      }
        
    });
  });



