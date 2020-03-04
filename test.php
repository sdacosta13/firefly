<!DOCTYPE html>
<html>
  <head>
  </head>
  <body>
    <script>
      function reqListener () {
        console.log(this.responseText);
      }

      var oReq = new XMLHttpRequest();
      oReq.onload = function() {
        var latlongsstr = this.responseText;

        latlongsstr = latlongsstr.replace("[", "");
        latlongsstr = latlongsstr.replace("]", "");
        latlongsstr = latlongsstr.split("\"").join("");

        var latlongs = latlongsstr.split(",");
        var markerLocations = [];

        for (i = 0; i < latlongs.length; i = i + 2) {
          temp = {lat: latlongs[i], lng: latlongs[i+1]};
          markerLocations.push(temp);
        }
        alert(markerLocations[0].lat);
      };
      oReq.open("get", "getDataTest.php", true);
      oReq.send()
    </script>
  </body>
</html>
