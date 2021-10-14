// Handles filtering on directory search page

(function ($) {

  const $posters = $('.embed-poster');

  function displayVideo(event) {
    const element = event.currentTarget;
    const videoWrapper = element.nextElementSibling;

    element.classList.add('d-none');
    videoWrapper.classList.remove('d-none');
    videoWrapper.children[0].src += '?autoplay=1';
    videoWrapper.children[0].autoplay = true;
  }

  function init() {
    if ($posters.length) {
      for (let index = 0; index < $posters.length; index++) {
        const poster = $posters[index];
        poster.onclick = displayVideo;
      }
    }
  }

  $(init);


}(jQuery));
