function openSlides() {
  document.getElementById("myModal").style.display = "block";
}

function closeSlides() {
  document.getElementById("myModal").style.display = "none";
}

var slideNum = 1;
showSlides(slideNum);

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
  if (unit > slides.length) {slideNum = 1}

  for (iterator = 0; iterator < slides.length; iterator++) {
    slides[iterator].style.display = "none";
  }

  for (iterator = 0; iterator < state.length; iterator++) {
    state[iterator].className = state[iterator].className.replace(" active", "");
  }

  slides[slideNum-1].style.display = "block";
  state[slideNum-1].className += "active";
}

function changeProgress(){
  var placesFound=0;
  var placesTotal = 3;
  var x = (placesFound / placesTotal) *100;
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
  if(placesFound==0){
  progBar.style.width = "5%";}
  if(placesFound==1){
  progBar.style.width = "33%";}
  if(placesFound==2){
  progBar.style.width = "66%";}
  if(placesFound==3){
  progBar.style.width = "100%";}
}
