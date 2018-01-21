$(document).ready(function(){
    $('.stpostbutton').click(function() {
      //Check if HTLM5 Geolocation is supported
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
      } else {
        alert("Geolocation is not supported by this browser.");
      }
      function showPosition(position) {
        var update = $('#text').val();
        var latitude = position.coords.latitude; 
        var longitude = position.coords.longitude; 
        var postdata = "latitude="+latitude+"&longitude="+longitude+"&message="+update;
        console.log(postdata);
        var base_url="http://localhost/mtlwatch/WebApp/backend/";
        var url = base_url+'parse';
        $.ajax({
          type: "POST",
          url: url,
          data: postdata,
          success: function(data){
            $('.w-form').html(data);
            $("#about").css("padding-bottom", "450px");
          }
      });
      }
        
    });
  });





