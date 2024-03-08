function slidesPlugin(activeSlide = 0) {
    const slides = document.querySelectorAll('.slide');
  
    slides[activeSlide].classList.add('active');
    toggleButtonTextVisibility(activeSlide);
  
    slides.forEach((slide, index) => {
      slide.addEventListener('click', () => {
        clearActiveClasses();
        slide.classList.add('active');
        toggleButtonTextVisibility(index);
      });
    });
  
    function clearActiveClasses() {
      slides.forEach((slide) => {
        slide.classList.remove('active');
      });
    }
  
    function toggleButtonTextVisibility(index) {
      const buttons = document.querySelectorAll('.slide button');
      const textElements = document.querySelectorAll('.slide h3');
  
      buttons.forEach((button, i) => {
        button.style.opacity = i === index ? '1' : '0';
      });
  
      textElements.forEach((textElement, i) => {
        textElement.style.visibility = i === index ? 'visible' : 'hidden';
      });
    }
  }
  
  slidesPlugin(2);