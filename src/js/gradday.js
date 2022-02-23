(function ($) {

  const revealButton = document.getElementById('reveal-button');
  const revealBox = document.getElementById('reveal-box');

  function revealTestimonials() {
    revealBox.classList.remove('d-none');
    revealButton.classList.add('d-none');
  }

  function init() {
    if (revealButton && revealBox) {
      revealButton.onclick = revealTestimonials;
    }
  }

  $(init);


}(jQuery));
