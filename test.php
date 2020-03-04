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

        for (i = 0; i < latlongs.length; i = i + 2) {
          alert(latlongs[i]);
          alert(latlongs[i+1]);
        }
      };
      oReq.open("get", "getDataTest.php", true);
      oReq.send()
    </script>
  </body>
</html>
