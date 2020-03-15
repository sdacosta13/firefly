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
  if (image.src.match("question.png") && x>0) {
      image.src ="006-bonfire.png" ;
  }
  if (image2.src.match("question.png") && x>=50) {
      image2.src ="007-compass.png" ;
  }
  if (image3.src.match("question.png") && x==100) {
      image3.src ="discover.png" ;
  }
  var progBar = document.getElementById('bar');
  var percent = x + "%";
  progBar.style.width = percent;
}

var oReq = new XMLHttpRequest();
oReq.onload = function() {
    changeProgress(this.responseText);
    var slideNum = 1;
    showSlides(slideNum);
};
oReq.open("get", "placePercentage.php", true);
oReq.send();
