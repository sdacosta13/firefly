<!DOCTYPE html>
<html>
  <head>
    <?php
      $y = 8;
    ?>

    <title>Firefly</title>
    <script>
      var y = '<?php echo $y ?>'
    </script>
  </head>
  <body>
    <button onclick="abc()">Try </button>
    <script>
      function abc() {
        alert(y);
        document.getElementById("test").innerHTML = "Test";
      }

      function reqListener () {
        console.log(this.responseText);
      }

      var oReq = new XMLHttpRequest();
      oReq.onload = function() {
        alert(this.responseText);
      };
      oReq.open("get", "getDataTest.php", true);
      oReq.send()
    </script>
  </body>
</html>
