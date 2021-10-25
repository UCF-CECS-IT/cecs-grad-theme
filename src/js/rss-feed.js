// Handles reveal/hide on thesis rss feeds

(function ($) {

  const $thesisBtns = $('.ucf-rss-thesis-reveal');

  function revealAdditionalItems(event) {
    const feedList = event.target.parentElement.previousElementSibling;
    const feedItems = feedList.children;

    for (let index = 0; index < feedItems.length; index++) {
      const element = feedItems[index];
      element.classList.remove('d-none');
    }

    event.target.classList.add('d-none');
  }

  function init() {
    if ($thesisBtns.length) {
      for (let index = 0; index < $thesisBtns.length; index++) {
        const element = $thesisBtns[index];
        element.onclick = revealAdditionalItems;
      }
    }
  }

  $(init);


}(jQuery));
