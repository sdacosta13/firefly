var slideNum = 1;

function reqListener () {
  console.log(this.responseText);
}

function openSlides() {
  document.getElementById("myModal").style.display = "block";
}

function closeSlides() {
  document.getElementById("myModal").style.display = "none";
}

function plusSlides(unit) {
  showSlides(slideNum += unit);
}

function currentSlide(unit) {
  showSlides(slideNum = unit);
}

function showSlides(unit) {
  var slides = document.getElementsByClassName("mySlides");
  var text = document.getElementById("caption");
  var state = document.getElementsByClassName("slideImage");
  var iterator;
  if (unit > slides.length) {
    slideNum = 1;
  }

  if (slideNum < 1) {
    slideNum = 3;
  }

  for (iterator = 0; iterator < slides.length; iterator++) {
    slides[iterator].style.display = "none";
  }

  for (iterator = 0; iterator < state.length; iterator++) {
    state[iterator].className = state[iterator].className.replace(" active", "");
  }

  slides[slideNum-1].style.display = "block";
  state[slideNum-1].className += "active";
}

function changeProgress(x){
  var placesFound=0;
  var placesTotal = 3;
  var image = document.getElementById('badge1');
  var image2 = document.getElementById('badge2');
  var image3 = document.getElementById('badge3');
  var progBar = document.getElementById('bar');
  if (image.src.match("images/question.png") && x>0) {
      image.src ="images/006-bonfire.png" ;
  }
  if (image2.src.match("images/question.png") && x>=50) {
      image2.src ="images/007-compass.png" ;
  }
  if (image3.src.match("images/question.png") && x==100) {
      image3.src ="images/discover.png" ;
  }
  var progBar = document.getElementById('bar');
  var percent = x + "%";
  progBar.style.width = percent;
}

var oReq = new XMLHttpRequest();
oReq.onload = function() {
  // Get the text from the PHP file that accesses the database
  var datastr = this.responseText;

  // Clean the string that is input
  datastr = datastr.replace("[", "");
  datastr = datastr.replace("]", "");
  datastr = datastr.split("\"").join("");

  // Split the string into an array
  var data = datastr.split("*");

  // Get the percentage visited
  var percentage = parseFloat(data[0]);
  changeProgress(percentage);

  // Getting the number of $points
  var points = parseInt(data[1].substring(1));
  var message =  "You currently have ";
  message = message.concat(points, " points");
  document.getElementById("pointsNum").innerHTML =  message;

  if (data[2] != "" && data[2] != "None!") {
    // Getting the descriptions
    var descriptionsStr = data[2].split("\\r\\n").join(" ");
    descriptionsStr = descriptionsStr.split("\\r\\n").join("*");
    descriptionsStr = descriptionsStr.split("\\").join("");
    descriptionsStr = descriptionsStr.substring(1, data[2].length-1);
    descriptions = descriptionsStr.split("!,");

    // Getting the text to write to the html
    var temp;
    var finalText = "";
    for (i = 0; i < descriptions.length; i++) {
      temp = descriptions[i]
      temp = temp.replace("!", "");
      temp = temp.split("**")
      for (j = 0; j < temp.length; j++) {
        finalText = finalText.concat(temp[j], "<br>");
      }
      finalText = finalText.concat("<br>");
    }

    // Writing to the HTML
    var descriptionText = document.getElementById("descriptionText");
    descriptionText.innerHTML = finalText;
  }

  showSlides(slideNum);
};
oReq.open("get", "placePercentage.php", true);
oReq.send();
