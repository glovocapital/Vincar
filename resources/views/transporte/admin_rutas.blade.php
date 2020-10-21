<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Google Map de prueba</title>
    <style>
        #map {
            height: 400px;
            width: 40%;
        }
    </style>
</head>
<body>
    <h1>Seguimiento de ruta</h1>
    <div id="map"></div>

    <iframe
        width="600"
        height="450"
        frameborder="0" style="border:0"
        src="https://www.google.com/maps/embed/v1/directions?origin=San Pablo 1391, Santiago, Chile&destination=Pasaje Manuel Montt 157, Santiago, Chile&key={{ config('googlemaps.GOOGLE_MAPS_API_KEY') }}" allowfullscreen>
    </iframe>

    
    <script>
      // Note: This example requires that you consent to location sharing when
      // prompted by your browser. If you see the error "The Geolocation service
      // failed.", it means you probably did not give permission for the browser to
      // locate you.
      var map, infoWindow;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -34.397, lng: 150.644},
          zoom: 6
        });
        infoWindow = new google.maps.InfoWindow;

        // Try HTML5 geolocation.
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            infoWindow.setPosition(pos);
            infoWindow.setContent('Location found.');
            infoWindow.open(map);
            map.setCenter(pos);
          }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
          });
        } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
        }
      }

      function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');
        infoWindow.open(map);
      }
    </script>
    <script defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAKi5ps17L1x9-SVP65NaCUpVBX2GFziaI&callback=initMap">
    </script>
</body>
</html>