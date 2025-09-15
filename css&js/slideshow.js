let slideIndex = 1;
let slideInterval;

// Initialize the slideshow
showSlides(slideIndex);
startSlideShow();

function plusSlides(n) {
  clearInterval(slideInterval);
  showSlides(slideIndex += n);
  startSlideShow();
}

function currentSlide(n) {
  clearInterval(slideInterval);
  showSlides(slideIndex = n);
  startSlideShow();
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}

function startSlideShow() {
  // Clear any existing interval first
  clearInterval(slideInterval);
  // Set new interval (5000ms = 5 seconds)
  slideInterval = setInterval(function() {
    plusSlides(1);
  }, 8000);
}